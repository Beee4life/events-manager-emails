<?php

    /**
     * Content for the administration page
     */
    function em_emails_dashboard_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sorry, you do not have sufficient permissions to access this page.' ) );
        }
?>
        <div class="wrap">
            <div id="icon-options-general" class="icon32"><br /></div>

            <h2>Events Manager Email Add-on</h2>
            
            <?php if ( ! class_exists( 'EM_Events' ) ) { ?>
                <?php eme_errors()->add( 'error_no_em', esc_html( __( 'Events Manager is not installed.', 'em-emails' ) ) ); ?>
                <?php eme_show_admin_notices(); ?>
                <p><?php printf( __( 'Install it <a href="%s">here</a>.', 'sexdates' ), get_admin_url(null, 'plugins.php' ) ); ?></p>
            <?php } else { ?>
                <?php eme_show_admin_notices(); ?>
    
                <?php echo EM_Emails_Addon::eme_admin_menu(); ?>
    
                <p>
                    <?php printf( __( 'Welcome to the Events Manager Emails plugin, an extension for <a href="">Events Manager</a>.', 'em-emails' ), esc_url( 'http://wp-events-plugin.com/' ) ); ?>
                    <br />
                    <?php _e( 'For more info see the "Help" menu (top right).', 'em-emails' ); ?>
                </p>
    
                <?php
                    // get events with bookings enabled
                    global $wpdb;
                    $today = date( 'Y-m-d', current_time( 'timestamp' ) );
                    $rsvp_events = $wpdb->get_results(
                        "SELECT event_id, post_id, event_start_date
                            FROM {$wpdb->prefix}em_events
                            WHERE event_rsvp = 0 AND event_status = 1 AND event_start_date > '{$today}'
                            ORDER BY event_start_date ASC"
                    );
                ?>
                <?php if ( $rsvp_events ) { ?>
                    <form name="send-em-emails" action="" method="post">
                        <input name="send_em_emails_nonce" type="hidden" value="<?php echo wp_create_nonce( 'send-em-emails-nonce' ); ?>" />

                        <p>
                            <b><?php _e( 'Events are sorted descending by (post) ID.', 'em-emails' ); ?></b><br />
                            <label for="eme_event_name" class="screen-reader-text"><?php _e( 'Select an event', 'em-emails' ); ?></label>
                            <?php do_action( 'eme_after_event_label' ); ?>
                            <select id="eme_event_name" name="eme_event_id">
                                <option value=""><?php _e( 'Select an event', 'em-emails' ); ?></option>
                                <?php foreach ( $rsvp_events as $event ) { ?>
                                    <?php if ( date( 'Y-m-d', current_time( 'timestamp' ) ) < $event->event_start_date ) { ?>
                                        <option value="<?php echo $event->event_id; ?>"><?php echo get_the_title( $event->post_id ); ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </p>
            
                        <?php $email_types = array( 'general_info' => 'General info' ); ?>
                        <p>
                            <b><?php _e( 'Email types', 'em-emails' ); ?></b><br />
                            <label for="eme_email_type" class="screen-reader-text"><?php _e( 'Select which email to send', 'em-emails' ); ?></label>
                            <?php do_action( 'eme_after_email_type_label' ); ?>
                            <select id="eme_email_type" name="eme_email_type">
                                <option value=""><?php _e( 'Select which email to send', 'em-emails' ); ?></option>
                                <?php foreach ( $email_types as $key => $value ) { ?>
                                    <option value="<?php echo $key; ?>"><?php echo  $value; ?></option>
                                <?php } ?>
                            </select>
                        </p>
            
                        <?php
                            $booking_status = array(
                                'all'       => 'All',
                                'confirmed' => 'Confirmed',
                                'pending'   => 'Pending',
                            );
                        ?>
                        <p>
                            <b><?php _e( 'Booking status', 'em-emails' ); ?></b><br />
                            <label for="eme_booking_status" class="screen-reader-text"><?php _e( 'Select which booking status to send to', 'em-emails' ); ?></label>
                            <?php do_action( 'eme_after_booking_status_label' ); ?>
                            <select id="eme_booking_status" name="eme_booking_status">
                                <!--                            <option value="">--><?php //_e( 'Select booking status', 'em-emails' ); ?><!--</option>-->
                                <?php foreach ( $booking_status as $key => $value ) { ?>
                                    <option value="<?php echo $key; ?>"><?php echo  $value; ?></option>
                                <?php } ?>
                            </select>
                        </p>
            
                        <?php submit_button( __( 'Send email', 'em-emails' ), 'primary' ); ?>

                    </form>
                <?php } else { ?>
                    <p><?php _e( 'No coming events with bookings.', 'em-emails' ); ?></p>
                <?php } ?>
            <?php } ?>
        </div>
<?php
    }
