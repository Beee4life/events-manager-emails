<?php

    /**
     * Content for the administration page
     */
    function em_emails_template_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sorry, you do not have sufficient permissions to access this page.' ) );
        }
        ?>
        <div class="wrap">
            <div id="icon-options-general" class="icon32"><br /></div>

            <h2>Events Manager Email Add-on</h2>
        
            <?php eme_show_admin_notices(); ?>
        
            <?php echo EM_Emails_Addon::eme_admin_menu(); ?>
    
            <?php
                // get events with bookings enabled
                global $wpdb;
                $rsvp_events = $wpdb->get_results(
                "
                        SELECT post_id, event_start_date
                        FROM {$wpdb->prefix}em_events
                        WHERE event_rsvp = 1
                        ORDER BY event_start_date DESC
                    ");
                
                if ( count( $rsvp_events ) > 0 ) {
                    $event_post_ids = array();
                    foreach( $rsvp_events as $event ) {
                        $event_post_ids[] = intval( $event->post_id );
                        
                    }
                }
            ?>

            <p><?php _e( 'For more info see the "Help" menu (top right).', 'em-emails' ); ?></p>

            <form name="eme-styling" method="post" action="">
                <input name="em_emails_styling_nonce" type="hidden" value="<?php echo wp_create_nonce( 'em-emails-styling-nonce' ); ?>" />
        
                <p><label for="eme_emails_logo">Header logo</label></p>
                <?php do_action( 'eme_after_logo_label' ); ?>
                <p><input type="text" name="eme_emails_logo" size="100" value="<?php echo esc_url( get_option( 'eme_emails_logo' ) ); ?>" placeholder="<?php _e( 'Paste full url of logo', 'em-emails' ); ?>" /></p>
        
                <p><label for="eme_emails_styling">CSS for email</label></p>
                <?php do_action( 'eme_after_styling_label' ); ?>
                <p><textarea name="eme_emails_styling" rows="10" cols="100" placeholder="<?php _e( 'Enter CSS here (if not entered, email will not be sent)', 'em-emails' ); ?>"><?php echo esc_attr( get_option('eme_emails_styling'), ENT_QUOTES ); ?></textarea></p>
        
                <p><label for="eme_emails_template">Template for email</label></p>
                <?php do_action( 'eme_after_template_label' ); ?>
                <p><textarea name="eme_emails_template" rows="10" cols="100" placeholder="<?php _e( 'Enter the email template here (if not entered, email will not be sent)', 'em-emails' ); ?>"><?php echo get_option('eme_emails_template'); ?></textarea></p>
        
                <p><?php submit_button( __( 'Save settings', 'em-emails' ), 'primary' ); ?></p>
            </form>

        </div>
<?php
    }
