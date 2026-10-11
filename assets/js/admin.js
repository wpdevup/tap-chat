/* Tap Chat admin. */
( function ( $ ) {
	'use strict';

	var OPTION = 'tap_chat_settings';
	var AGENT_MAX = 3;
	var DESKTOP_SCALE = 0.72; // Matches the scale of .tap-chat-preview__dock in admin.css.
	var settings = window.tapChatAdmin || {};
	var i18n = settings.i18n || {};

	$( function () {
		var $form = $( '[data-tap-chat-form]' );
		if ( ! $form.length ) {
			return;
		}

		initViews( $form );
		initConditionals( $form );
		initRanges( $form );
		initColorPicker( $form );
		initMedia( $form );
		initPickers( $form );
		initDays( $form );
		initAgents( $form );
		initPreview( $form );

		// admin.js is in charge now; drop the first-paint mark set by the boot script.
		$( '[data-tap-chat-boot]' ).removeAttr( 'data-tap-chat-boot' );

		$( document ).on( 'click', '[data-tap-chat-confirm]', function ( event ) {
			if ( ! window.confirm( $( this ).attr( 'data-tap-chat-confirm' ) ) ) {
				event.preventDefault();
			}
		} );
	} );

	/* Current value of a setting, read from the form the way it would be saved. */
	function settingValue( $form, key ) {
		var $fields = $form.find( '[name="' + OPTION + '[' + key + ']"]' );
		if ( ! $fields.length ) {
			return '';
		}
		var $radios = $fields.filter( ':radio' );
		if ( $radios.length ) {
			return $radios.filter( ':checked' ).val() || '';
		}
		var $box = $fields.filter( ':checkbox' );
		if ( $box.length ) {
			return $box.is( ':checked' ) ? $box.val() : 'no';
		}
		return $fields.last().val();
	}

	/* ------------------------------------------------------------------
	 * Views: one form, several sections, picked by the URL hash.
	 * ---------------------------------------------------------------- */
	function initViews( $form ) {
		var aliases = {
			'analytics': 'analytics',
			'analytics/overview': 'analytics',
			'analytics/settings': 'tracking',
			'tracking': 'tracking'
		};
		var $referer = $form.find( 'input[name="_wp_http_referer"]' );
		var refererBase = $referer.length ? String( $referer.val() ).split( '#' )[ 0 ] : '';

		function viewFromHash() {
			var hash = window.location.hash.replace( /^#/, '' );
			var view = aliases[ hash ] || hash.split( '/' )[ 0 ];
			return $form.find( '[data-tap-chat-view="' + view + '"]' ).length ? view : 'general';
		}

		function activate( view ) {
			var $views = $form.find( '[data-tap-chat-view]' );
			var $target = $views.filter( '[data-tap-chat-view="' + view + '"]' );
			$views.prop( 'hidden', true );
			$target.prop( 'hidden', false );

			$( '[data-tap-chat-nav]' ).removeAttr( 'aria-current' )
				.filter( '[data-tap-chat-nav="' + view + '"]' ).attr( 'aria-current', 'page' );

			$form.find( '[data-tap-chat-savebar]' ).prop( 'hidden', $target.is( '[data-tap-chat-no-save]' ) );

			var scene = $target.attr( 'data-tap-chat-scene' ) || '';
			$form.find( '[data-tap-chat-workspace]' ).toggleClass( 'has-preview', '' !== scene );
			if ( scene ) {
				$form.trigger( 'tapchat:scene', scene );
			}

			// After saving, options.php returns to this referer, so keep the open view in it.
			if ( $referer.length && refererBase ) {
				var hash = window.location.hash;
				$referer.val( refererBase + ( hash && hash.length > 1 ? hash : '' ) );
			}
		}

		$( window ).on( 'hashchange', function () {
			activate( viewFromHash() );
			window.scrollTo( 0, 0 );
		} );
		activate( viewFromHash() );
	}

	/* ------------------------------------------------------------------
	 * Show or hide parts that depend on another setting.
	 * ---------------------------------------------------------------- */
	function initConditionals( $form ) {
		var $dependents = $form.find( '[data-tap-chat-show-if]' );
		if ( ! $dependents.length ) {
			return;
		}
		function refresh() {
			$dependents.each( function () {
				var rule = String( $( this ).attr( 'data-tap-chat-show-if' ) ).split( ':' );
				$( this ).prop( 'hidden', settingValue( $form, rule[ 0 ] ) !== rule[ 1 ] );
			} );
		}
		$form.on( 'change', 'input[type="radio"], input[type="checkbox"]', refresh );
	}

	/* ------------------------------------------------------------------
	 * Range sliders show their value.
	 * ---------------------------------------------------------------- */
	function initRanges( $form ) {
		var unit = i18n.px || 'px';
		$form.on( 'input change', '.tap-chat-range__input', function () {
			$( this ).siblings( '.tap-chat-range__value' ).text( this.value + ' ' + unit );
		} );
	}

	function initColorPicker( $form ) {
		var $picker = $form.find( '.tap-chat-color-picker' );
		if ( ! $picker.length || ! $.fn.wpColorPicker ) {
			return;
		}
		$picker.wpColorPicker( {
			change: function ( event, ui ) {
				$form.trigger( 'tapchat:preview', { color: ui.color.toString() } );
			},
			clear: function () {
				$form.trigger( 'tapchat:preview', { color: '' } );
			}
		} );
	}

	/* ------------------------------------------------------------------
	 * Media library pickers (custom icon, bubble avatar, agent avatars).
	 * ---------------------------------------------------------------- */
	function initMedia( $form ) {
		function sync( $box ) {
			var url = $.trim( $box.find( '[data-tap-chat-media-url]' ).val() || '' );
			$box.find( '[data-tap-chat-media-img]' ).attr( 'src', url );
			$box.find( '.tap-chat-media__preview' ).toggleClass( 'has-image', '' !== url );
		}

		$form.on( 'click', '[data-tap-chat-media-choose]', function ( event ) {
			event.preventDefault();
			if ( typeof wp === 'undefined' || ! wp.media ) {
				return;
			}
			var $box = $( this ).closest( '[data-tap-chat-media]' );
			var frame = wp.media( {
				title: i18n.mediaTitle || '',
				button: { text: i18n.mediaButton || '' },
				multiple: false,
				library: { type: 'image' }
			} );
			frame.on( 'select', function () {
				var attachment = frame.state().get( 'selection' ).first().toJSON();
				$box.find( '[data-tap-chat-media-url]' ).val( attachment.url ).trigger( 'change' );
				sync( $box );
			} );
			frame.open();
		} );

		$form.on( 'click', '[data-tap-chat-media-clear]', function ( event ) {
			event.preventDefault();
			var $box = $( this ).closest( '[data-tap-chat-media]' );
			$box.find( '[data-tap-chat-media-url]' ).val( '' ).trigger( 'change' );
			sync( $box );
		} );

		$form.on( 'change', '[data-tap-chat-media-url]', function () {
			sync( $( this ).closest( '[data-tap-chat-media]' ) );
		} );
	}

	/* ------------------------------------------------------------------
	 * Page pickers: search and selected count.
	 * ---------------------------------------------------------------- */
	function initPickers( $form ) {
		function count( $picker ) {
			var template = String( $picker.attr( 'data-count-template' ) || '%d' );
			var n = $picker.find( '[data-tap-chat-picker-item] input:checked' ).length;
			$picker.find( '[data-tap-chat-picker-count]' ).text( template.replace( '%d', n ) );
		}

		$form.on( 'input', '[data-tap-chat-picker-search]', function () {
			var term = $.trim( this.value ).toLowerCase();
			$( this ).closest( '[data-tap-chat-picker]' ).find( '[data-tap-chat-picker-item]' ).each( function () {
				var title = $( this ).find( '.tap-chat-picker__title' ).text().toLowerCase();
				$( this ).prop( 'hidden', '' !== term && -1 === title.indexOf( term ) );
			} );
		} );

		// Enter inside the search box must not submit the settings form.
		$form.on( 'keydown', '[data-tap-chat-picker-search]', function ( event ) {
			if ( 13 === event.which ) {
				event.preventDefault();
			}
		} );

		$form.on( 'change', '[data-tap-chat-picker-item] input', function () {
			count( $( this ).closest( '[data-tap-chat-picker]' ) );
		} );
	}

	/* ------------------------------------------------------------------
	 * Working hours: dim closed days. Times stay editable and are saved.
	 * ---------------------------------------------------------------- */
	function initDays( $form ) {
		$form.on( 'change', '[data-tap-chat-day-open]', function () {
			$( this ).closest( '[data-tap-chat-day]' ).toggleClass( 'is-closed', ! this.checked );
		} );
	}

	/* ------------------------------------------------------------------
	 * Agents repeater.
	 * ---------------------------------------------------------------- */
	function initAgents( $form ) {
		var $root = $form.find( '[data-tap-chat-agents]' );
		if ( ! $root.length ) {
			return;
		}
		var $items = $root.find( '[data-tap-chat-agents-items]' );
		var template = $root.find( '[data-tap-chat-agent-template]' ).html() || '';

		function reindex() {
			$items.children( '[data-tap-chat-agent]' ).each( function ( i ) {
				var $card = $( this );
				$card.find( '[name]' ).each( function () {
					this.name = this.name.replace( /\[agents\]\[[^\]]*\]/, '[agents][' + i + ']' );
				} );
				$card.find( '[id]' ).each( function () {
					this.id = this.id.replace( /^tap-chat-agent-[^-]+-/, 'tap-chat-agent-' + i + '-' );
				} );
				$card.find( 'label[for]' ).each( function () {
					this.htmlFor = this.htmlFor.replace( /^tap-chat-agent-[^-]+-/, 'tap-chat-agent-' + i + '-' );
				} );
				$card.find( '[data-tap-chat-agent-number]' ).first().text( i + 1 );
			} );
			var total = $items.children( '[data-tap-chat-agent]' ).length;
			$root.find( '[data-tap-chat-agent-add]' ).prop( 'hidden', total >= AGENT_MAX );
			$root.find( '[data-tap-chat-agent-limit]' ).prop( 'hidden', total < AGENT_MAX );
		}

		$root.on( 'change', '[data-tap-chat-agent-channel]', function () {
			var $card = $( this ).closest( '[data-tap-chat-agent]' );
			var value = this.value;
			$card.find( '[data-tap-chat-agent-panel]' ).each( function () {
				$( this ).prop( 'hidden', $( this ).attr( 'data-tap-chat-agent-panel' ) !== value );
			} );
		} );

		$root.on( 'change', '[data-tap-chat-agent-enabled]', function () {
			$( this ).closest( '[data-tap-chat-agent]' ).toggleClass( 'is-disabled', ! this.checked );
		} );

		$root.on( 'click', '[data-tap-chat-agent-add]', function ( event ) {
			event.preventDefault();
			var total = $items.children( '[data-tap-chat-agent]' ).length;
			if ( total >= AGENT_MAX || ! template ) {
				return;
			}
			var $card = $( $.parseHTML( template.replace( /__INDEX__/g, String( total ) ) ) ).filter( '[data-tap-chat-agent]' );
			$items.append( $card );
			reindex();
			$card.find( 'input[type="text"]' ).first().trigger( 'focus' );
		} );

		$root.on( 'click', '[data-tap-chat-agent-remove]', function ( event ) {
			event.preventDefault();
			$( this ).closest( '[data-tap-chat-agent]' ).remove();
			reindex();
		} );

		if ( $.fn.sortable ) {
			$items.sortable( {
				handle: '[data-tap-chat-agent-drag]',
				items: '> [data-tap-chat-agent]',
				axis: 'y',
				tolerance: 'pointer',
				forcePlaceholderSize: true,
				placeholder: 'tap-chat-agent ui-sortable-placeholder',
				update: reindex
			} );
		}

		// Guarantee sequential indices before the form is saved.
		$form.on( 'submit', reindex );
		reindex();
	}

	/* ------------------------------------------------------------------
	 * Live preview: the button, the agent picker and the welcome bubble.
	 * ---------------------------------------------------------------- */
	function initPreview( $form ) {
		var $preview = $form.find( '[data-tap-chat-preview]' );
		if ( ! $preview.length ) {
			return;
		}
		var $screen = $preview.find( '.tap-chat-preview__screen' );
		var $fab = $preview.find( '[data-tap-chat-preview-fab]' );
		var $dock = $preview.find( '[data-tap-chat-preview-dock]' );
		var animations = [ 'fade', 'slide', 'bounce', 'ring', 'attention' ];
		var $img = $fab.find( '.tap-chat-preview__img' );
		var $label = $fab.find( '.tap-chat-preview__label' );
		var $panel = $preview.find( '[data-tap-chat-preview-panel]' );
		var $bubble = $preview.find( '[data-tap-chat-preview-bubble]' );
		var $hint = $preview.find( '[data-tap-chat-preview-hint]' );
		var glyph = $fab.find( '.tap-chat-preview__glyph' ).prop( 'outerHTML' ) || '';
		var linkGlyph = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3A4 4 0 0 0 11 18.7l1-1"/></svg>';
		var state = {
			device: 'desktop',
			scene: 'button',
			color: settingValue( $form, 'color' )
		};

		function text( value, fallback ) {
			value = $.trim( value || '' );
			return value || fallback || '';
		}

		/* Enabled agents, read from the repeater cards in their current order. */
		function readAgents() {
			var list = [];
			$form.find( '[data-tap-chat-agent]' ).each( function ( i ) {
				var $card = $( this );
				if ( ! $card.find( '[data-tap-chat-agent-enabled]' ).is( ':checked' ) ) {
					return;
				}
				list.push( {
					name: text( $card.find( 'input[name$="[name]"]' ).val(), String( i18n.agentName || '%d' ).replace( '%d', i + 1 ) ),
					role: text( $card.find( 'input[name$="[role]"]' ).val() ),
					avatar: text( $card.find( 'input[name$="[avatar]"]' ).val() ),
					online: $card.find( 'input[type="checkbox"][name$="[online]"]' ).is( ':checked' ),
					custom: 'custom' === $card.find( '[data-tap-chat-agent-channel]:checked' ).val()
				} );
			} );
			return list.slice( 0, AGENT_MAX );
		}

		function renderAgents() {
			var $list = $panel.find( '[data-tap-chat-preview-agents]' ).empty();
			var agents = readAgents();
			$panel.find( '[data-tap-chat-preview-panel-title]' ).text( text( settingValue( $form, 'agents_panel_title' ) ) );
			$panel.find( '[data-tap-chat-preview-panel-sub]' ).text( text( settingValue( $form, 'agents_panel_subtitle' ) ) );
			if ( ! agents.length ) {
				$list.append( $( '<span class="tap-chat-preview__empty"></span>' ).text( i18n.noAgents || '' ) );
				return;
			}
			$.each( agents, function ( i, agent ) {
				var $row = $( '<span class="tap-chat-preview__agent"></span>' ).toggleClass( 'is-offline', ! agent.online );
				var $avatar = $( '<span class="tap-chat-preview__agent-avatar"></span>' );
				if ( agent.avatar ) {
					$avatar.append( $( '<img alt="">' ).attr( 'src', agent.avatar ) );
				} else {
					$avatar.append( glyph );
				}
				if ( agent.online ) {
					$avatar.append( '<span class="tap-chat-preview__dot"></span>' );
				}
				var $info = $( '<span class="tap-chat-preview__agent-info"></span>' )
					.append( $( '<span class="tap-chat-preview__agent-name"></span>' ).text( agent.name ) );
				if ( agent.role ) {
					$info.append( $( '<span class="tap-chat-preview__agent-role"></span>' ).text( agent.role ) );
				}
				$info.append( $( '<span class="tap-chat-preview__agent-status"></span>' ).text( agent.online ? i18n.online : i18n.offline ) );
				$row.append( $avatar, $info, $( '<span class="tap-chat-preview__agent-channel"></span>' ).append( agent.custom ? linkGlyph : glyph ) );
				$list.append( $row );
			} );
		}

		function renderBubble() {
			var simple = 'simple' === settingValue( $form, 'bubble_style' );
			var avatar = text( settingValue( $form, 'welcome_bubble_avatar' ) );
			$bubble.toggleClass( 'is-simple', simple )
				.toggleClass( 'is-side', simple && 'side' === settingValue( $form, 'bubble_position' ) );
			$bubble.find( '[data-tap-chat-preview-bubble-msg]' ).text( text( settingValue( $form, 'welcome_bubble_message' ), i18n.bubbleText ) );
			$bubble.find( '[data-tap-chat-preview-bubble-name]' ).text( text( settingValue( $form, 'welcome_bubble_name' ), i18n.bubbleName ) );
			$bubble.find( '[data-tap-chat-preview-bubble-img]' ).prop( 'hidden', ! avatar ).attr( 'src', avatar );
			$bubble.find( '.tap-chat-preview__bubble-avatar' ).toggleClass( 'has-img', !! avatar );
		}

		function render() {
			var mobile = 'mobile' === state.device;
			var size = parseInt( settingValue( $form, mobile ? 'mobile_size' : 'size' ), 10 ) || 40;
			var iconOnly = 'yes' === settingValue( $form, mobile ? 'hide_label_mobile' : 'hide_label_desktop' );
			var icon = text( settingValue( $form, 'custom_icon' ) );
			var left = 'left' === settingValue( $form, 'position' );
			var agentsOn = 'agents' === settingValue( $form, 'button_mode' );
			var bubbleOn = 'yes' === settingValue( $form, 'enable_welcome_bubble' );
			var showPanel = 'agents' === state.scene && agentsOn;
			var showBubble = 'bubble' === state.scene && bubbleOn;
			var hint = '';

			$preview.attr( { 'data-device': state.device, 'data-scene': state.scene } );
			$screen.toggleClass( 'is-left', left ).toggleClass( 'is-right', ! left ).css( {
				'--tap-chat-fab-color': state.color || '#25D366',
				'--tap-chat-fab-size': size + 'px'
			} );
			var animation = settingValue( $form, 'button_animation' );
			$fab.toggleClass( 'is-icon-only', iconOnly )
				.toggleClass( 'has-img', '' !== icon )
				.toggleClass( 'has-badge', 'attention' === animation );
			$.each( animations, function ( i, name ) {
				$fab.toggleClass( 'is-anim-' + name, name === animation );
			} );
			$img.prop( 'hidden', '' === icon ).attr( 'src', icon );
			$label.text( text( settingValue( $form, 'label' ), i18n.defaultLabel ) );

			// The panel and the bubble sit next to the button, so expose its size in the
			// screen's own units: scaled down on desktop, real size inside the zoomed phone.
			var fabEl = $fab[ 0 ];
			if ( fabEl.offsetHeight ) {
				var factor = mobile ? 1 : DESKTOP_SCALE;
				$screen.css( {
					'--tap-chat-fab-h': Math.round( fabEl.offsetHeight * factor ) + 'px',
					'--tap-chat-fab-w': Math.round( fabEl.offsetWidth * factor ) + 'px'
				} );
			}

			if ( showPanel ) {
				renderAgents();
			}
			if ( showBubble ) {
				renderBubble();
			}
			$panel.prop( 'hidden', ! showPanel );
			$bubble.prop( 'hidden', ! showBubble );

			if ( 'agents' === state.scene && ! agentsOn ) {
				hint = i18n.agentsOff;
			} else if ( 'bubble' === state.scene && ! bubbleOn ) {
				hint = i18n.bubbleOff;
			}
			$hint.text( hint || '' ).prop( 'hidden', ! hint );
		}

		$preview.on( 'click', '[data-tap-chat-device]', function () {
			state.device = $( this ).attr( 'data-tap-chat-device' );
			$preview.find( '[data-tap-chat-device]' ).attr( 'aria-pressed', 'false' );
			$( this ).attr( 'aria-pressed', 'true' );
			render();
		} );

		$form.on( 'tapchat:scene', function ( event, scene ) {
			state.scene = scene;
			render();
		} );

		$form.on( 'tapchat:preview', function ( event, data ) {
			if ( data && Object.prototype.hasOwnProperty.call( data, 'color' ) ) {
				state.color = data.color;
			}
			render();
		} );

		// Any edit may change the preview; rendering is cheap, so re-render on every input.
		$form.on( 'input change', 'input, select, textarea', function () {
			if ( this.name === OPTION + '[color]' ) {
				state.color = this.value;
			}
			render();
		} );
		// Adding, removing or reordering agents.
		$form.on( 'click', '[data-tap-chat-agent-add], [data-tap-chat-agent-remove]', function () {
			window.setTimeout( render, 0 );
		} );
		$form.find( '[data-tap-chat-agents-items]' ).on( 'sortupdate', render );

		state.scene = $form.find( '[data-tap-chat-view]:not([hidden])' ).attr( 'data-tap-chat-scene' ) || 'button';
		render();
	}
}( jQuery ) );
