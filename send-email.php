<?php

    $event_object   = new EM_Event( $_POST['event_id'] );
    $from_email     = get_bloginfo( 'admin_email' );
    $site_name      = get_bloginfo( 'name' );
    $home_url       = get_option( 'home' );
    $headers[]      = "From: {$site_name} <{$from_email}>";
    $headers[]      = "Content-Type: text/html; charset=UTF-8";
    $email_template = get_option( 'eme_emails_template' );
    $email_styling  = get_option( 'eme_emails_styling' );
    $email_subject  = get_option( 'eme_emails_subject_general' );
    $email_logo     = get_option( 'eme_emails_logo' );
    $email_message  = get_option( 'eme_emails_content_general' );
    $email_content  = str_replace( '%email_message%', $email_message, $email_template );
    $send_to        = $_POST[ 'booking_status' ];
    
    
    if ( false == $email_subject ) {
        eme_errors()->add( 'error_no_email_subject', esc_html( __( 'No email subject has been entered. You need to enter one before you can send it.', 'em-emails' ) ) );
    
        return;
    }
    
    if ( false == $email_styling ) {
        eme_errors()->add( 'error_no_email_styling', esc_html( __( 'No email styling has been entered. You need to enter one before you can send it.', 'em-emails' ) ) );
    
        return;
    }
    
    if ( false == $email_message ) {
        eme_errors()->add( 'error_no_email_message', esc_html( __( 'No email message has been entered. You need to enter one before you can send it.', 'em-emails' ) ) );
    
        return;
    }
    
    if ( false == $email_template ) {
        eme_errors()->add( 'error_no_email_template', esc_html( __( 'No email template has been entered. You need to enter one before you can send it.', 'em-emails' ) ) );
    
        return;
    }
    
    if ( false != $email_subject || false != $email_styling || false != $email_message || false != $email_template ) {
        foreach( $email_these_users as $user ) {
            $user_data        = get_userdata( $user );
            $to               = $user_data->user_email;
            $replacement_vars = array(
                '%email_styling%' => $email_styling,
                '%logo%'          => $email_logo,
                '%display_name%'  => $user_data->display_name,
                '%first_name%'    => $user_data->first_name,
                '%last_name%'     => $user_data->last_name,
                '%event_name%'    => $event_object->event_name,
                '%home_url%'      => $home_url,
                '%site_name%'     => $site_name,
            );
            $message          = strtr( $email_content, $replacement_vars );
            $email_subject    = strtr( $email_subject, $replacement_vars );
            
            if ( true == wp_mail( $to, $email_subject, $message, $headers ) ) {
                eme_errors()->add( 'success_email_sent', esc_html( __( 'Email successfully sent.', 'em-emails' ) ) );
            } else {
                eme_errors()->add( 'error_nonce_no_match', esc_html( __( 'Email NOT sent.', 'em-emails' ) ) );
            }
        }
    }
