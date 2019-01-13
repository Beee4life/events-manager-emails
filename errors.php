<?php
    /**
     * @return WP_Error
     */
    function eme_errors() {
        static $wp_error; // Will hold global variable safely
        
        return isset( $wp_error ) ? $wp_error : ( $wp_error = new WP_Error( null, null, null ) );
    }
    
    /**
     * Displays error messages from form submissions
     */
    function eme_show_admin_notices() {
        if ( $codes = eme_errors()->get_error_codes() ) {
            if ( is_wp_error( eme_errors() ) ) {
                
                // Loop error codes and display errors
                $error      = false;
                $span_class = false;
                $prefix     = false;
                foreach ( $codes as $code ) {
                    if ( strpos( $code, 'success' ) !== false ) {
                        $span_class = 'notice-success ';
                        $prefix     = false;
                    } elseif ( strpos( $code, 'error' ) !== false ) {
                        $span_class = 'notice-error ';
                        $prefix     = esc_html( __( 'Warning', 'em-emails' ) );
                    } elseif ( strpos( $code, 'info' ) !== false ) {
                        $span_class = 'notice-info ';
                        $prefix     = false;
                    } else {
                        $error      = true;
                        $span_class = 'notice-error ';
                        $prefix     = esc_html( __( 'Error', 'em-emails' ) );
                    }
                }
                echo '<div class="notice eme-notice ' . $span_class . 'is-dismissible">';
                foreach ( $codes as $code ) {
                    $message = eme_errors()->get_error_message( $code );
                    echo '<div class="">';
                    if ( true == $prefix ) {
                        echo '<strong>' . $prefix . ':</strong> ';
                    }
                    echo $message;
                    echo '</div>';
                    echo '<button type="button" class="notice-dismiss"><span class="screen-reader-text">' . esc_html( __( 'Dismiss this notice', 'em-emails' ) ) . '</span></button>';
                }
                echo '</div>';
            }
        }
    }
