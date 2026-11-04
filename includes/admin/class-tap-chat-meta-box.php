<?php
/**
 * "Tap Chat" box in the post editor: per-page visibility and message.
 *
 * @package Tap_Chat
 */

namespace Tap_Chat;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Meta_Box {

    const NONCE = 'tap_chat_meta_box';

    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'register' ) );
        add_action( 'save_post', array( $this, 'save' ), 10, 2 );
    }

    /**
     * Post types that get the box: every public type except media.
     *
     * @return string[]
     */
    private function post_types() {
        $types = get_post_types( array( 'public' => true ) );
        unset( $types['attachment'] );
        /**
         * Filters the post types that show the Tap Chat box in the editor.
         *
         * @param string[] $types Post type slugs.
         */
        return (array) apply_filters( 'tap_chat_meta_box_post_types', array_values( $types ) );
    }

    public function register() {
        foreach ( $this->post_types() as $type ) {
            add_meta_box(
                'tap-chat-page',
                __( 'Tap Chat', 'tap-chat' ),
                array( $this, 'render' ),
                $type,
                'side',
                'default'
            );
        }
    }

    /**
     * @param \WP_Post $post Post being edited.
     */
    public function render( $post ) {
        $visibility = (string) get_post_meta( $post->ID, Plugin::META_VISIBILITY, true );
        $message    = (string) get_post_meta( $post->ID, Plugin::META_MESSAGE, true );

        wp_nonce_field( self::NONCE, self::NONCE . '_nonce' );

        echo '<p><label for="tap-chat-page-visibility"><strong>' . esc_html__( 'Chat button on this page', 'tap-chat' ) . '</strong></label></p>';
        echo '<select class="widefat" id="tap-chat-page-visibility" name="tap_chat_page_visibility">';
        $choices = array(
            ''     => __( 'Follow the Visibility settings', 'tap-chat' ),
            'show' => __( 'Always show', 'tap-chat' ),
            'hide' => __( 'Hide', 'tap-chat' ),
        );
        foreach ( $choices as $value => $label ) {
            printf( '<option value="%s"%s>%s</option>', esc_attr( $value ), selected( $visibility, $value, false ), esc_html( $label ) );
        }
        echo '</select>';
        echo '<p class="description">' . esc_html__( 'Overrides the page, content type and selected-page rules. Working hours still apply.', 'tap-chat' ) . '</p>';

        echo '<p><label for="tap-chat-page-message"><strong>' . esc_html__( 'Message for this page (optional)', 'tap-chat' ) . '</strong></label></p>';
        printf(
            '<textarea class="widefat" rows="3" id="tap-chat-page-message" name="tap_chat_page_message" placeholder="%s">%s</textarea>',
            esc_attr__( 'Leave empty to use the default message.', 'tap-chat' ),
            esc_textarea( $message )
        );
        echo '<p class="description">' . esc_html__( 'Used for WhatsApp links on this page, for the button and every agent. Variables such as {TITLE} and {PRODUCT} work here too.', 'tap-chat' ) . '</p>';
    }

    /**
     * @param int      $post_id Post ID.
     * @param \WP_Post $post    Post object.
     */
    public function save( $post_id, $post ) {
        if ( ! isset( $_POST[ self::NONCE . '_nonce' ] ) ) {
            return;
        }
        if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE . '_nonce' ] ) ), self::NONCE ) ) {
            return;
        }
        if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) || ! in_array( $post->post_type, $this->post_types(), true ) ) {
            return;
        }

        $visibility = isset( $_POST['tap_chat_page_visibility'] ) ? sanitize_key( wp_unslash( $_POST['tap_chat_page_visibility'] ) ) : '';
        if ( in_array( $visibility, array( 'show', 'hide' ), true ) ) {
            update_post_meta( $post_id, Plugin::META_VISIBILITY, $visibility );
        } else {
            delete_post_meta( $post_id, Plugin::META_VISIBILITY );
        }

        $message = isset( $_POST['tap_chat_page_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['tap_chat_page_message'] ) ) : '';
        if ( '' !== trim( $message ) ) {
            update_post_meta( $post_id, Plugin::META_MESSAGE, $message );
        } else {
            delete_post_meta( $post_id, Plugin::META_MESSAGE );
        }
    }
}
