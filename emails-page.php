<?php

    /**
     * Content for the administration page
     */
    function em_emails_emails_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sorry, you do not have sufficient permissions to access this page.' ) );
        }
    
        // Now display the settings editing screen
        echo '<div class="wrap">';
        echo '<div id="icon-options-general" class="icon32"><br /></div>';

        // header
        echo "<h2>Events Manager Emails</h2>";
    
        eme_show_admin_notices();
    
        echo EM_Emails_Addon::eme_admin_menu();
        
        echo '<p>To see which variables are available, check the "Help" menu (top right).</p>';
    
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
        <form name="eme-settings" method="post" action="">
            <input name="em_emails_emails_nonce" type="hidden" value="<?php echo wp_create_nonce( 'em-emails-emails-nonce' ); ?>" />

            <h3><?php _e( 'General email', 'em-emails' ); ?></h3>

            <?php $subject_value = get_option( 'eme_emails_subject_general' ); ?>
            <p><label for="eme_emails_subject_general">Email subject</label></p>
            <p><input type="text" name="eme_emails_subject_general" size="100" value="<?php echo $subject_value; ?>" placeholder="<?php  _e( 'Enter an email subject here (if not entered, email will not be sent)', 'em-emails' ); ?>" /></p>
        
            <?php $content_value = get_option( 'eme_emails_content_general' ); ?>
            <p><label for="eme_emails_content_general">Email content</label></p>
            <p><textarea name="eme_emails_content_general" rows="8" cols="100" placeholder="<?php  _e( 'Enter the email content here (if not entered, email will not be sent)', 'em-emails' ); ?>"><?php echo $content_value; ?></textarea></p>
    
            <p><?php submit_button( __( 'Save email settings', 'em-emails' ), 'primary' ); ?></p>
        
        </form>

<?php
        echo '</div><!-- end .wrap -->';
    }
