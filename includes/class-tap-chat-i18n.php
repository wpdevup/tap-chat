<?php
/**
 * Translation of the texts site owners type in (labels, messages, agent details)
 * through WPML String Translation or Polylang.
 *
 * @package Tap_Chat
 */

namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Strings {

    /** Group the strings are listed under in WPML and Polylang. */
    const CONTEXT = 'Tap Chat';

    /** Option holding a hash of the strings last registered with WPML. */
    const WPML_HASH = 'tap_chat_wpml_strings_hash';

    /**
     * Translatable settings and the name shown to translators.
     *
     * @return array<string, string>
     */
    public static function setting_keys() {
        return array(
            'label'                  => 'Button label',
            'message'                => 'Default message',
            'custom_url'             => 'Custom link',
            'offline_message'        => 'Offline message',
            'welcome_bubble_message' => 'Welcome bubble message',
            'welcome_bubble_name'    => 'Welcome bubble name',
            'agents_panel_title'     => 'Agents panel title',
            'agents_panel_subtitle'  => 'Agents panel subtitle',
            'product_message'        => 'Product page message',
        );
    }

    /**
     * Translatable agent fields and the name shown to translators.
     *
     * @return array<string, string>
     */
    public static function agent_keys() {
        return array(
            'name'       => 'name',
            'role'       => 'role',
            'message'    => 'message',
            'custom_url' => 'custom link',
        );
    }

    /**
     * Whether a multilingual plugin that can translate the strings is active.
     *
     * @return bool
     */
    public static function available() {
        return function_exists( 'pll_register_string' ) || defined( 'ICL_SITEPRESS_VERSION' ) || has_filter( 'wpml_translate_single_string' );
    }

    /**
     * String name for an agent field, stable across reordering.
     *
     * @param array  $agent Agent row.
     * @param string $field Field key.
     * @return string
     */
    public static function agent_name( $agent, $field ) {
        $id     = isset( $agent['id'] ) ? $agent['id'] : '';
        $labels = self::agent_keys();
        return sprintf( 'Agent %s %s', $id, isset( $labels[ $field ] ) ? $labels[ $field ] : $field );
    }

    /**
     * Every translatable string in the settings, keyed by its translator-facing name.
     *
     * @param array $settings Plugin settings.
     * @return array<string, string>
     */
    public static function collect( $settings ) {
        $out = array();
        if ( ! is_array( $settings ) ) {
            return $out;
        }
        foreach ( self::setting_keys() as $key => $name ) {
            if ( isset( $settings[ $key ] ) && is_string( $settings[ $key ] ) && '' !== trim( $settings[ $key ] ) ) {
                $out[ $name ] = $settings[ $key ];
            }
        }
        if ( ! empty( $settings['agents'] ) && is_array( $settings['agents'] ) ) {
            foreach ( $settings['agents'] as $agent ) {
                if ( ! is_array( $agent ) ) {
                    continue;
                }
                foreach ( array_keys( self::agent_keys() ) as $field ) {
                    if ( isset( $agent[ $field ] ) && is_string( $agent[ $field ] ) && '' !== trim( $agent[ $field ] ) ) {
                        $out[ self::agent_name( $agent, $field ) ] = $agent[ $field ];
                    }
                }
            }
        }
        return $out;
    }

    /**
     * Register the strings with Polylang (each admin request, as it expects)
     * and with WPML (only when they changed, as WPML stores them).
     */
    public static function register() {
        $strings = self::collect( get_option( 'tap_chat_settings', array() ) );

        if ( function_exists( 'pll_register_string' ) ) {
            foreach ( $strings as $name => $value ) {
                pll_register_string( $name, $value, self::CONTEXT, false !== strpos( $value, "\n" ) );
            }
        }

        if ( defined( 'ICL_SITEPRESS_VERSION' ) || has_action( 'wpml_register_single_string' ) ) {
            $hash = md5( wp_json_encode( $strings ) );
            if ( get_option( self::WPML_HASH ) !== $hash ) {
                foreach ( $strings as $name => $value ) {
                    do_action( 'wpml_register_single_string', self::CONTEXT, $name, $value );
                }
                update_option( self::WPML_HASH, $hash, false );
            }
        }
    }

    /**
     * The string in the current language, or the original when no translation exists.
     *
     * @param string $name  Translator-facing name.
     * @param mixed  $value Original value.
     * @return mixed
     */
    public static function translate( $name, $value ) {
        if ( ! is_string( $value ) || '' === trim( $value ) ) {
            return $value;
        }
        if ( function_exists( 'pll__' ) ) {
            return pll__( $value );
        }
        if ( has_filter( 'wpml_translate_single_string' ) ) {
            return apply_filters( 'wpml_translate_single_string', $value, self::CONTEXT, $name );
        }
        return $value;
    }

    /**
     * Translated copy of one setting, if it is translatable.
     *
     * @param string $key   Setting key.
     * @param mixed  $value Stored value.
     * @return mixed
     */
    public static function setting( $key, $value ) {
        $keys = self::setting_keys();
        return isset( $keys[ $key ] ) ? self::translate( $keys[ $key ], $value ) : $value;
    }

    /**
     * Agent row with its texts in the current language.
     *
     * @param array $agent Agent row.
     * @return array
     */
    public static function agent( $agent ) {
        foreach ( array_keys( self::agent_keys() ) as $field ) {
            if ( isset( $agent[ $field ] ) ) {
                $agent[ $field ] = self::translate( self::agent_name( $agent, $field ), $agent[ $field ] );
            }
        }
        return $agent;
    }
}
