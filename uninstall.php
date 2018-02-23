<?php
    
    // If uninstall.php is not called by WordPress, die
    if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        die;
    }
    
    // If preserve settings is false
    if ( false == get_option( 'eme_preserve_settings' ) ) {
        
        $options = array(
            'content_general',
            'email_logo',
            'email_styling',
            'subject_general',
            'template',
        );
        foreach( $options as $option ) {
            delete_option( 'eme_email_' . $option );
        }

    }
