<?php
    
    /**
     * Add help tabs
     *
     * @param $old_help  string
     * @param $screen_id int
     * @param $screen    object
     */
    function eme_help_tabs( $old_help, $screen_id, $screen ) {
        
        $screen_array = array(
            'event_page_em-emails',
            'settings_page_em-emails-emails',
            'settings_page_em-emails-template',
            'settings_page_em-emails-misc',
        );
        if ( ! in_array( $screen_id, $screen_array ) ) {
            return false;
        }
        
        if ( 'event_page_em-emails' == $screen_id ) {
            $screen->add_help_tab( array(
                'id'      => 'eme-dashboard',
                'title'   => esc_html__( 'Dashboard', 'em-emails' ),
                'content' => '<h5>Dashboard</h5><p>' . esc_html__( 'This page will show all your logged entries.', 'em-emails' ) . '</p>' .
                    '<p>' . esc_html__( 'You can select individual logs by checking the checkbox and click "Delete selected items".', 'em-emails' ) . '</p>'
            ) );
        }
    
        if ( in_array( $screen_id, ['event_page_em-emails','settings_page_em-emails-emails','settings_page_em-emails-template','settings_page_em-emails-misc'] ) ) {
            $tab_variables = '<p>' . __( 'All variables can be used in the email subject, email content and template, but be careful which you use in the subject.', 'em-emails' ) . '</p>';
            $tab_variables .= '<ul>';
            $tab_variables .= '<li>%site_name% : ' . __( 'replaced by the "Site Title", which is defined in Settings > General', 'em-emails' ) . '</li>';
            $tab_variables .= '<li>%email_styling% : ' . __( 'replaced by the CSS, which is defined on the styling page.', 'em-emails' ) . '</li>';
            $tab_variables .= '<li>%home_url% : ' . __( 'replaced by the url, which is defined in get_option(\'home_url\')', 'em-emails' ) . '</li>';
            $tab_variables .= '<li>%logo% : ' . __( 'replaced by the URL stored in "Header logo"', 'em-emails' ) . '</li>';
            $tab_variables .= '<li>%display_name% : ' . __( 'replaced by the display name of the user', 'em-emails' ) . '</li>';
            $tab_variables .= '<li>%first_name% : ' . __( 'replaced by the user\'s first name', 'em-emails' ) . '</li>';
            $tab_variables .= '<li>%last_name% : ' . __( 'replaced by the user\'s last name', 'em-emails' ) . '</li>';
            $tab_variables .= '<li>%email_message% : ' . __( 'replaced by the message you want to send', 'em-emails' ) . '</li>';
            $tab_variables .= '</ul>';
    
            $screen->add_help_tab( array(
                'id'      => 'eme-dashboard',
                'title'   => esc_html__( 'Dashboard', 'em-emails' ),
                'content' => '<h5>Dashboard</h5><p>' . esc_html__( 'From this page you can select which event you want to send an email to.', 'em-emails' ) . '</p>' .
                    '<p>' . esc_html__( 'The dropdown features all events which have bookings enabled, regardless whether it\'s open or not. Now you can also send an email after the event has past (and bookings are closed).', 'em-emails' ) . '</p>' .
                    '<p>' . esc_html__( 'Right now there\'s only 1 template to choose from. Maybe more will come...', 'em-emails' ) . '</p>'
            ) );

            $screen->add_help_tab(
                array(
                    'id'      => 'eme-variables',
                    'title'   => esc_html__( 'Variables', 'em-emails' ),
                    'content' => '<h4>Available variables</h4>' . $tab_variables,
                )
            );
    
            $screen->add_help_tab( array(
                'id'      => 'eme-send-email',
                'title'   => esc_html__( 'Send an email', 'em-emails' ),
                'content' => '<h5>Send an email</h5><p>' . esc_html__( 'If any of the fields (template, styling, subject or content) is left blank, no email will be sent.', 'em-emails' ) . '</p>'
            ) );
    
            $donate_link = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="donation">
                    <div>
                        <input type="hidden" name="cmd" value="_xclick">
                        <input type="hidden" name="business" value="info@berryplasman.com">
                        <input type="hidden" name="item_name" value="Events Manager Emails">
                        <input type="hidden" name="buyer_credit_promo_code" value="">
                        <input type="hidden" name="buyer_credit_product_category" value="">
                        <input type="hidden" name="buyer_credit_shipping_method" value="">
                        <input type="hidden" name="buyer_credit_user_address_change" value="">
                        <input type="hidden" name="no_shipping" value="1">
                        <input type="hidden" name="return" value="' . site_url() . '/wp-admin/edit.php?post_type=event&page=em-emails">
                        <input type="hidden" name="no_note" value="1">
                        <input type="hidden" name="currency_code" value="EUR">
                        <input type="hidden" name="tax" value="0">
                        <input type="hidden" name="lc" value="US">
                        <input type="hidden" name="bn" value="PP-DonationsBF">
                        <div class="donation-amount">&euro;
                            <label for="donate-amount" class="screen-reader-text"></label>
                            <input type="number" id="donate-amount" min="1" name="amount" value="5">
                            <input type="submit" class="button-primary" value="Donate 💰">
                        </div>
                    </div>
                </form>';
            
            $screen->add_help_tab( array(
                'id'      => 'eme-donate',
                'title'   => esc_html__( 'Donate', 'em-emails' ),
                'content' => '<h5>Donate</h5><p>' . esc_html__( 'If you like this plugin, please send a donation to the "Help Beee develop this plugin further" fund.', 'em-emails' ) . '</p>' .
                    '<p>' . $donate_link . '</p>'
            ) );
    
        }
    
        get_current_screen()->set_help_sidebar(
            '<p><strong>' . esc_html__( 'Author\'s website', 'em-emails' ) . '</strong></p>' .
            '<p><a href="http://www.berryplasman.com?utm_source=' . $_SERVER[ 'SERVER_NAME' ] . '&utm_medium=plugin_admin&utm_campaign=free_promo">berryplasman.com</a></p>'
        );
        
        return $old_help;
    }
    add_filter( 'contextual_help', 'eme_help_tabs', 5, 3 );
