<?php
/**
 * Shared admin components: headers, cards, toggle rows, choices and fields.
 *
 * @package Tap_Chat
 */

namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Markup helpers used by every admin view.
 */
class Admin_UI {

    const OPTION = 'tap_chat_settings';

    /**
     * Form field name for a setting key.
     *
     * @param string $key Setting key.
     * @return string
     */
    public static function name( $key ) {
        return self::OPTION . '[' . $key . ']';
    }

    /**
     * Element ID for a setting key.
     *
     * @param string $key Setting key.
     * @return string
     */
    public static function id( $key ) {
        return 'tap-chat-' . str_replace( '_', '-', $key );
    }

    /**
     * Attributes that show an element only while a setting has a given value.
     * The initial state is rendered on the server; admin.js keeps it in sync.
     *
     * @param string $key     Setting key.
     * @param string $value   Value that makes the element visible.
     * @param string $current Current value.
     * @return string
     */
    public static function show_if( $key, $value, $current ) {
        $attr = ' data-tap-chat-show-if="' . esc_attr( $key . ':' . $value ) . '"';
        if ( (string) $current !== (string) $value ) {
            $attr .= ' hidden';
        }
        return $attr;
    }

    /**
     * Page header.
     *
     * @param string $title       Title.
     * @param string $description Description.
     * @param string $actions     Optional pre-escaped actions markup.
     */
    public static function page_header( $title, $description = '', $actions = '' ) {
        echo '<header class="tap-chat-page-head"><div class="tap-chat-page-head__main">';
        printf( '<h1 class="tap-chat-page-head__title">%s</h1>', esc_html( $title ) );
        if ( '' !== $description ) {
            printf( '<p class="tap-chat-page-head__desc">%s</p>', esc_html( $description ) );
        }
        echo '</div>';
        if ( '' !== $actions ) {
            echo '<div class="tap-chat-page-head__actions">' . $actions . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped by the caller.
        }
        echo '</header>';
    }

    /**
     * Section heading.
     *
     * @param string $title       Title.
     * @param string $description Optional description.
     */
    public static function section_title( $title, $description = '' ) {
        printf( '<h2 class="tap-chat-section-title">%s</h2>', esc_html( $title ) );
        if ( '' !== $description ) {
            printf( '<p class="tap-chat-section-desc">%s</p>', esc_html( $description ) );
        }
    }

    /**
     * Sub-heading inside a card.
     *
     * @param string $title       Title.
     * @param string $description Optional description.
     */
    public static function card_subtitle( $title, $description = '' ) {
        printf( '<hr class="tap-chat-divider"><h3 class="tap-chat-card__title">%s</h3>', esc_html( $title ) );
        if ( '' !== $description ) {
            printf( '<p class="tap-chat-card__desc">%s</p>', esc_html( $description ) );
        }
    }

    /**
     * Toggle row: icon, label, description and a switch.
     * A hidden "no" field precedes the switch so an unchecked switch is saved too.
     *
     * @param string $key         Setting key.
     * @param string $icon        Icon name.
     * @param string $label       Label.
     * @param string $description Description.
     * @param bool   $checked     State.
     * @param string $name        Optional full field name (defaults to the setting key).
     */
    public static function toggle_row( $key, $icon, $label, $description, $checked, $name = '' ) {
        $name = '' !== $name ? $name : self::name( $key );
        printf( '<label class="tap-chat-toggle-row" for="%s"><span class="tap-chat-toggle-row__icon">', esc_attr( self::id( $key ) ) );
        Admin_Icons::render( $icon );
        echo '</span><span class="tap-chat-toggle-row__text">';
        printf( '<span class="tap-chat-toggle-row__label">%s</span>', esc_html( $label ) );
        if ( '' !== $description ) {
            printf( '<span class="tap-chat-toggle-row__desc">%s</span>', esc_html( $description ) );
        }
        printf(
            '</span><input type="hidden" name="%1$s" value="no"><input type="checkbox" class="tap-chat-switch" role="switch" id="%2$s" name="%1$s" value="yes"%3$s></label>',
            esc_attr( $name ),
            esc_attr( self::id( $key ) ),
            checked( $checked, true, false )
        );
    }

    /**
     * Choice card (a radio button presented as a card).
     *
     * @param string $key         Setting key.
     * @param string $value       Option value.
     * @param string $icon        Icon name.
     * @param string $title       Title.
     * @param string $description Description.
     * @param string $current     Current value.
     */
    public static function choice( $key, $value, $icon, $title, $description, $current ) {
        printf(
            '<label class="tap-chat-choice"><input type="radio" name="%1$s" value="%2$s"%3$s><span class="tap-chat-choice__icon">',
            esc_attr( self::name( $key ) ),
            esc_attr( $value ),
            checked( (string) $current, (string) $value, false )
        );
        Admin_Icons::render( $icon );
        printf(
            '</span><span class="tap-chat-choice__text"><span class="tap-chat-choice__title">%1$s</span><span class="tap-chat-choice__desc">%2$s</span></span></label>',
            esc_html( $title ),
            esc_html( $description )
        );
    }

    /**
     * Opening markup of a labelled field.
     *
     * @param string $label Label.
     * @param string $for   ID of the control.
     * @param string $class Optional extra class (for example tap-chat-field--wide).
     * @param string $attrs Optional pre-escaped attributes.
     */
    public static function field_open( $label, $for = '', $class = '', $attrs = '' ) {
        printf( '<div class="%s"%s>', esc_attr( trim( 'tap-chat-field ' . $class ) ), $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Attributes are built with esc_attr().
        if ( '' !== $for ) {
            printf( '<label for="%1$s">%2$s</label>', esc_attr( $for ), esc_html( $label ) );
        } else {
            printf( '<span class="tap-chat-field__label">%s</span>', esc_html( $label ) );
        }
    }

    /**
     * Closing markup of a labelled field.
     *
     * @param string $help Optional help text (plain text).
     */
    public static function field_close( $help = '' ) {
        if ( '' !== $help ) {
            printf( '<p class="tap-chat-field__help">%s</p>', esc_html( $help ) );
        }
        echo '</div>';
    }

    /**
     * Inline message.
     *
     * @param string $type    success|error|warning|info.
     * @param string $message Message (allowed: strong, code, a).
     */
    public static function alert( $type, $message ) {
        $icons = array(
            'success' => 'check',
            'error'   => 'alert',
            'warning' => 'alert',
            'info'    => 'info',
        );
        $type = isset( $icons[ $type ] ) ? $type : 'info';
        printf( '<div class="tap-chat-alert tap-chat-alert--%1$s" role="%2$s">', esc_attr( $type ), esc_attr( 'error' === $type ? 'alert' : 'status' ) );
        Admin_Icons::render( $icons[ $type ] );
        printf(
            '<p>%s</p></div>',
            wp_kses(
                $message,
                array(
                    'strong' => array(),
                    'code'   => array(),
                    'a'      => array( 'href' => true ),
                )
            )
        );
    }

    /**
     * Image picker (media library) with a URL field and a preview.
     *
     * @param string $key         Setting key.
     * @param string $value       Current URL.
     * @param string $placeholder URL placeholder.
     * @param string $choose      Choose button label.
     * @param string $clear       Clear button label.
     * @param string $shape       round|square.
     */
    public static function image_picker( $key, $value, $placeholder, $choose, $clear, $shape = 'square' ) {
        $has = '' !== (string) $value;
        printf( '<div class="tap-chat-media" data-tap-chat-media><span class="tap-chat-media__preview tap-chat-media__preview--%1$s%2$s">', esc_attr( $shape ), $has ? ' has-image' : '' );
        printf( '<img src="%s" alt="" data-tap-chat-media-img>', esc_url( $value ) );
        Admin_Icons::render( 'image' );
        echo '</span><span class="tap-chat-media__body">';
        printf(
            '<input type="url" class="tap-chat-input" id="%1$s" name="%2$s" value="%3$s" placeholder="%4$s" data-tap-chat-media-url>',
            esc_attr( self::id( $key ) ),
            esc_attr( self::name( $key ) ),
            esc_url( $value ),
            esc_attr( $placeholder )
        );
        printf(
            '<span class="tap-chat-media__actions"><button type="button" class="tap-chat-btn tap-chat-btn--ghost tap-chat-btn--sm" data-tap-chat-media-choose>%1$s</button><button type="button" class="tap-chat-btn tap-chat-btn--quiet tap-chat-btn--sm" data-tap-chat-media-clear>%2$s</button></span>',
            esc_html( $choose ),
            esc_html( $clear )
        );
        echo '</span></div>';
    }

    /**
     * Default WhatsApp glyph (same artwork as the front-end button).
     *
     * @param string $class CSS class.
     * @return string
     */
    public static function whatsapp_svg( $class ) {
        return '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.031-.967-.273-.1-.472-.149-.672.15-.197.297-.768.966-.94 1.164-.173.199-.347.224-.644.075-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.173.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.672-1.611-.921-2.206-.242-.58-.487-.502-.672-.512l-.573-.01c-.198 0-.521.074-.793.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.71.306 1.263.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.718 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.37l-.361-.214-3.741.982.999-3.648-.236-.374a9.86 9.86 0 0 1-1.51-5.26c0-5.45 4.436-9.884 9.89-9.884 2.64 0 5.122 1.027 6.988 2.893a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.439 9.884-9.887 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .154 5.338.157 11.892c0 2.096.549 4.142 1.595 5.945L.057 24l6.3-1.654a11.89 11.89 0 0 0 5.683 1.448h.005c6.554 0 11.895-5.338 11.898-11.893a11.82 11.82 0 0 0-3.479-8.413"/></svg>';
    }
}
