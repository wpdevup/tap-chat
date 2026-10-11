<?php
/**
 * Line icons for the admin screens (24x24, stroke uses currentColor).
 *
 * @package Tap_Chat
 */

namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Icon set for the admin screens.
 */
class Admin_Icons {

    /**
     * SVG shapes keyed by icon name.
     *
     * @var array
     */
    private static $paths = array(
        'chat'       => '<path d="M20 11.5a8 8 0 0 1-11.6 7.1L4 20l1.4-4.2A8 8 0 1 1 20 11.5z"/><path d="M8.5 11.5h.01M12 11.5h.01M15.5 11.5h.01"/>',
        'users'      => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0M16 4.6a3.5 3.5 0 0 1 0 6.8M18 14.2A6.5 6.5 0 0 1 21.5 20"/>',
        'user'       => '<circle cx="12" cy="8" r="4"/><path d="M4.5 20.5a7.5 7.5 0 0 1 15 0"/>',
        'message'    => '<path d="M4 5h16a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-9l-5 4v-4H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z"/><path d="M7.5 9.5h9M7.5 12.5h6"/>',
        'clock'      => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
        'eye'        => '<path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12z"/><circle cx="12" cy="12" r="3"/>',
        'eye-off'    => '<path d="M4 4l16 16M10.6 6A9.9 9.9 0 0 1 12 5.5c6 0 9.5 6.5 9.5 6.5a16 16 0 0 1-2.6 3.4M6.6 7.5A15.6 15.6 0 0 0 2.5 12s3.5 6.5 9.5 6.5a9.4 9.4 0 0 0 4.4-1.1M9.9 9.9a3 3 0 0 0 4.2 4.2"/>',
        'sliders'    => '<path d="M4 7h9M17 7h3M4 12h3M11 12h9M4 17h11M19 17h1"/><circle cx="15" cy="7" r="2"/><circle cx="9" cy="12" r="2"/><circle cx="17" cy="17" r="2"/>',
        'chart'      => '<path d="M4 4v16h16"/><path d="M8 16v-4M12 16V8M16 16v-6"/>',
        'activity'   => '<path d="M3 12h4l3-7 4 14 3-7h4"/>',
        'phone'      => '<rect x="6.5" y="2.5" width="11" height="19" rx="2.5"/><path d="M11 18.5h2"/>',
        'monitor'    => '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8.5 20h7M12 16v4"/>',
        'tablet'     => '<rect x="4.5" y="2.5" width="15" height="19" rx="2.5"/><path d="M11 18.5h2"/>',
        'link'       => '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3A4 4 0 0 0 11 18.7l1-1"/>',
        'plus'       => '<path d="M12 5v14M5 12h14"/>',
        'trash'      => '<path d="M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V4h6v3"/>',
        'grip'       => '<path d="M9 6h.01M15 6h.01M9 12h.01M15 12h.01M9 18h.01M15 18h.01"/>',
        'image'      => '<rect x="3" y="4" width="18" height="16" rx="2.5"/><circle cx="9" cy="9.5" r="1.8"/><path d="m3.5 17.5 5-5 4 4 2.5-2.5 5.5 5"/>',
        'power'      => '<path d="M12 3v8"/><path d="M6.3 6.8a8 8 0 1 0 11.4 0"/>',
        'zap'        => '<path d="M13 3 5 13.5h6l-1 7.5 8-10.5h-6l1-7.5z"/>',
        'timer'      => '<circle cx="12" cy="13.5" r="7.5"/><path d="M12 9.5v4l2.5 1.5M9.5 2.5h5"/>',
        'scroll'     => '<rect x="7" y="3" width="10" height="18" rx="5"/><path d="M12 7v3"/>',
        'exit'       => '<path d="M14 4h4a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-4M10 16l-4-4 4-4M6 12h9"/>',
        'moon'       => '<path d="M20 14.5A8 8 0 0 1 9.5 4 8 8 0 1 0 20 14.5z"/>',
        'calendar'   => '<rect x="3.5" y="5" width="17" height="15.5" rx="2.5"/><path d="M3.5 10h17M8 3v4M16 3v4"/>',
        'globe'      => '<circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.4 2.4 3.6 5.2 3.6 8.5s-1.2 6.1-3.6 8.5c-2.4-2.4-3.6-5.2-3.6-8.5S9.6 5.9 12 3.5z"/>',
        'file'       => '<path d="M14 3H7a1.5 1.5 0 0 0-1.5 1.5v15A1.5 1.5 0 0 0 7 21h10a1.5 1.5 0 0 0 1.5-1.5V7.5L14 3z"/><path d="M14 3v4.5h4.5M9 13h6M9 16.5h4"/>',
        'shield'     => '<path d="M12 3 19 6v5.5c0 4.4-2.9 7.7-7 9.5-4.1-1.8-7-5.1-7-9.5V6l7-3z"/><path d="m9 12 2 2 4-4"/>',
        'database'   => '<ellipse cx="12" cy="6" rx="7.5" ry="3"/><path d="M4.5 6v12c0 1.7 3.4 3 7.5 3s7.5-1.3 7.5-3V6M4.5 12c0 1.7 3.4 3 7.5 3s7.5-1.3 7.5-3"/>',
        'user-off'   => '<circle cx="10" cy="8" r="4"/><path d="M2.5 20.5a7.5 7.5 0 0 1 11.4-6.4M16.5 15.5l5 5M21.5 15.5l-5 5"/>',
        'tag'        => '<path d="M3.5 12.2V4.5a1 1 0 0 1 1-1h7.7l8.3 8.3a1 1 0 0 1 0 1.4l-7.3 7.3a1 1 0 0 1-1.4 0l-8.3-8.3z"/><path d="M8 8h.01"/>',
        'search'     => '<circle cx="11" cy="11" r="6.5"/><path d="m20 20-4.4-4.4"/>',
        'reset'      => '<path d="M4 12a8 8 0 1 0 2.4-5.7M4 4v4.5h4.5"/>',
        'info'       => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 7.8h.01"/>',
        'check'      => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'alert'      => '<path d="M12 4 21 20H3L12 4z"/><path d="M12 10v4M12 17h.01"/>',
        'star'       => '<path d="m12 3.5 2.6 5.4 5.9.8-4.3 4.1 1 5.8L12 16.8l-5.2 2.8 1-5.8-4.3-4.1 5.9-.8L12 3.5z"/>',
        'arrow-up'   => '<path d="M12 19V5M6 11l6-6 6 6"/>',
        'arrow-down' => '<path d="M12 5v14M6 13l6 6 6-6"/>',
        'arrow-right' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'external'   => '<path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>',
        'help'       => '<circle cx="12" cy="12" r="9"/><path d="M9.6 9.3a2.5 2.5 0 0 1 4.8.9c0 1.7-2.4 2.2-2.4 3.8M12 17h.01"/>',
    );

    /**
     * Markup of one icon.
     *
     * @param string $name  Icon name.
     * @param string $class Optional extra class.
     * @return string
     */
    public static function get( $name, $class = '' ) {
        $shape = isset( self::$paths[ $name ] ) ? self::$paths[ $name ] : '';
        $class = 'tap-chat-icon' . ( '' !== $class ? ' ' . $class : '' );
        return '<svg class="' . esc_attr( $class ) . '" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $shape . '</svg>';
    }

    /**
     * Print one icon.
     *
     * @param string $name  Icon name.
     * @param string $class Optional extra class.
     */
    public static function render( $name, $class = '' ) {
        echo wp_kses( self::get( $name, $class ), self::allowed() );
    }

    /**
     * Allowed SVG markup for wp_kses().
     *
     * @return array
     */
    public static function allowed() {
        $shape = array_fill_keys( array( 'd', 'cx', 'cy', 'r', 'rx', 'ry', 'x', 'y', 'width', 'height' ), true );
        return array(
            'svg'     => array_fill_keys( array( 'class', 'viewbox', 'width', 'height', 'fill', 'stroke', 'stroke-width', 'stroke-linecap', 'stroke-linejoin', 'aria-hidden', 'focusable' ), true ),
            'path'    => $shape,
            'circle'  => $shape,
            'rect'    => $shape,
            'ellipse' => $shape,
        );
    }
}
