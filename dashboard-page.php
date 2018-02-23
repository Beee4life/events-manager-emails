<?php

    /**
     * Content for the administration page
     */
    function em_emails_dashboard_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sorry, you do not have sufficient permissions to access this page.' ) );
        }

        // Now display the settings editing screen
        echo '<div class="wrap">';
        echo '<div id="icon-options-general" class="icon32"><br /></div>';

        // header
        echo "<h2>Events Manager Email Add-on</h2>";
    
        eme_show_admin_notices();
    
        echo EM_Emails_Addon::eme_admin_menu();
        
        // get events with bookings enabled
        global $wpdb;
        $rsvp_events = $wpdb->get_results(
        "
                SELECT event_id, post_id, event_start_date
                FROM {$wpdb->prefix}em_events
                WHERE event_rsvp = 1
                ORDER BY event_start_date DESC
            ");
    ?>
        <p>For more info see the "Help" menu (top right).</p>
        
        <form name="send-em-emails" action="" method="post">
        <input name="send_em_emails_nonce" type="hidden" value="<?php echo wp_create_nonce( 'send-em-emails-nonce' ); ?>" />

        <p>
            <b>Events are sorted descending by (post) ID.</b><br />
            <label for="event_name" class="screen-reader-text">Select an event</label>
            <select id="event_name" name="event_id">
                <option value=""><?php _e( 'Select an event', 'em-emails' ); ?></option>
                <?php foreach ( $rsvp_events as $event ) { ?>
                    <option value="<?php echo $event->event_id; ?>"><?php echo get_the_title( $event->post_id ); ?></option>
                <?php } ?>
            </select>
        </p>
        
        <?php $email_types = array( 'general_info' => 'General info' ); ?>
        <p>
            <b>Email types</b><br />
            <label for="email_type" class="screen-reader-text"><?php _e( 'Select which email to send', 'em-emails' ); ?></label>
            <select id="email_type" name="email_type">
                <option value=""><?php _e( 'Select which email to send', 'em-emails' ); ?></option>
                <?php foreach ( $email_types as $key => $value ) { ?>
                    <option value="<?php echo $key; ?>"><?php echo  $value; ?></option>
                <?php } ?>
            </select>
        
            <?php submit_button( __( 'Send email', 'em-emails' ), 'primary' ); ?>
        </p>
        </form>
        
<?php
        echo '</div><!-- end .wrap -->';
    }
