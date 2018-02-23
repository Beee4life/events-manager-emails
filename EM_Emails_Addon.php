<?php
    /*
    Plugin Name: Events Manager Emails
    Version: 0.1 beta
    Plugin URI:
    Description: This plugin creates an extendable email 'part' to Events Manager.
    Author: Beee
    Author URI: http://berryplasman.com
    License: GPL v2
    Text-domain: em-emails

            http://www.berryplasman.com
               ___  ____ ____ ____
              / _ )/ __/  __/  __/
             / _  / _/   _/   _/
            /____/___/____/____/

    */

    if ( ! defined( 'ABSPATH' ) )
        exit; // Exit if accessed directly
    
    if ( ! class_exists( 'EM_Emails_Addon' ) ):

        class EM_Emails_Addon {
            var $settings;

            /**
             *  A dummy constructor to ensure plugin is only initialized once
             */
            function __construct() {
            }

            function initialize() {
                // vars
                $this->settings = array(
                    'path'    => trailingslashit( dirname( __FILE__ ) ),
                    'version' => '0.1',
                );
    
                // (de)activation hooks
                register_activation_hook( __FILE__,    array( $this, 'eme_plugin_activation' ) );
                register_deactivation_hook( __FILE__,  array( $this, 'eme_plugin_deactivation' ) );
    
                // actions
                add_action( 'plugins_loaded',   array( $this, 'eme_load_plugin_textdomain' ) );
                add_action( 'admin_menu',       array( $this, 'eme_admin_pages' ) );
                add_action( 'admin_init',       array( $this, 'eme_admin_menu' ) );
                add_action( 'admin_init',       array( $this, 'eme_send_email_function' ) );
                add_action( 'admin_init',       array( $this, 'eme_store_emails' ) );
                add_action( 'admin_init',       array( $this, 'eme_store_templates' ) );
                add_action( 'admin_init',       array( $this, 'eme_store_settings' ) );
    
                add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'eme_plugin_link' ) );
    
                include( 'dashboard-page.php' );
                include( 'emails-page.php' );
                include( 'template-page.php' );
                include( 'misc-page.php' );
                include( 'errors.php' );
                include( 'help-tabs.php' );

            }
    
            /**
             * Function which runs upon plugin activation
             */
            public function eme_plugin_activation() {
                // set default settings
                $this->eme_set_default_values();
            }
    
            /**
             * Function which runs upon plugin deactivation
             */
            public function eme_plugin_deactivation() {
                // nothing yet, only actions on uninstall, defined in uninstall.php
            }
    
            /**
             * Load language files
             */
            public function eme_load_plugin_textdomain() {
                load_plugin_textdomain( 'em-emails', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
            }
    
            /**
             * Here we build a simple array of available log actions and store them in an option value.
             */
            public function eme_set_default_values() {
                include( 'defaults.php' );
            }
    
            /**
             * Return Admin menu
             *
             * @return string
             */
            public static function eme_admin_menu() {
                return '<p><a href="' . site_url() . '/wp-admin/edit.php?post_type=event&page=em-emails">Dashboard</a> | <a href="'.site_url().'/wp-admin/options-general.php?page=em-emails-emails">Emails</a> | <a href="'.site_url().'/wp-admin/options-general.php?page=em-emails-template">Template</a> | <a href="'.site_url().'/wp-admin/options-general.php?page=em-emails-misc">Misc</a></p>';
            }

            /**
             * Add an administration pages below 'Events'
             */
            public function eme_admin_pages() {
                add_submenu_page(
                    'edit.php?post_type=event',
                    'Event emails',
                    'Event emails',
                    'manage_options',
                    'em-emails',
                    'em_emails_dashboard_page'
                );
                add_submenu_page(
                    null,
                    'Event email emails',
                    'Event email emails',
                    'manage_options',
                    'em-emails-emails',
                    'em_emails_emails_page'
                );
                add_submenu_page(
                    null,
                    'Event email template',
                    'Event email template',
                    'manage_options',
                    'em-emails-template',
                    'em_emails_template_page'
                );
                add_submenu_page(
                    null,
                    'Event email template',
                    'Event email template',
                    'manage_options',
                    'em-emails-misc',
                    'em_emails_misc_page'
                );
            }
    
            /**
             * Function to send the email
             */
            public function eme_send_email_function( $post_data = false ) {
    
                if ( false != $post_data ) {
    
                    $email_these_users = array( $post_data[ 'eme_emails_test_user' ] );
                    include( 'send-email.php' );
                
                } elseif ( isset( $_POST[ 'send_em_emails_nonce' ] ) ) {
                    
                    if ( ! wp_verify_nonce( $_POST[ 'send_em_emails_nonce' ], 'send-em-emails-nonce' ) ) {
                        eme_errors()->add( 'error_no_nonce_match', esc_html( __( 'Something went wrong. Please try again.', 'em-emails' ) ) );

                        return;
                    } else {
                        
                        if ( empty( $_POST[ 'event_id' ] ) ) {
                            eme_errors()->add( 'error_no_event', esc_html( __( 'No event selected.', 'em-emails' ) ) );
    
                            return;
                        }
                        if ( empty( $_POST[ 'email_type' ] ) ) {
                            eme_errors()->add( 'error_no_type', esc_html( __( 'No email type selected.', 'em-emails' ) ) );
    
                            return;
                        }
    
                        // get bookings from $_POST[ 'event_post_id' ]
                        global $wpdb;
                        $event_bookings = $wpdb->get_results(
                            "
                            SELECT *
                            FROM {$wpdb->prefix}em_bookings
                            WHERE event_id = {$_POST['event_id']}
                        ");
    
                        $email_these_users = [];
                        if ( count( $event_bookings ) > 0 ) {
                            foreach( $event_bookings as $booking ) {
                                $email_these_users[] = intval( $booking->person_id );
                            }
                        }
    
                        include( 'send-email.php' );

                    }
                }
            }
    
            /**
             * Store email settings
             */
            public function eme_store_emails() {
                if ( isset( $_POST[ 'em_emails_emails_nonce' ] ) ) {
                    if ( ! wp_verify_nonce( $_POST[ 'em_emails_emails_nonce' ], 'em-emails-emails-nonce' ) ) {
                        eme_errors()->add( 'error_nonce_no_match', esc_html( __( 'Something went wrong. Please try again.', 'em-emails' ) ) );
                        return;
                    } else {
    
                        if ( ! empty( $_POST[ 'eme_emails_test_user' ] ) ) {
                            
                            // send test email
                            $this->eme_send_email_function( $_POST );

                        } else {
    
                            if ( ! empty( $_POST['eme_emails_subject_general'] ) ) {
                                update_option( 'eme_emails_subject_general', esc_html( $_POST['eme_emails_subject_general'] ) );
                            } else {
                                delete_option( 'eme_emails_subject_general' );
                            }
                            
                            if ( ! empty( $_POST['eme_emails_content_general'] ) ) {
                                update_option( 'eme_emails_content_general', stripslashes( $_POST[ 'eme_emails_content_general' ] ) );
                            } else {
                                delete_option( 'eme_emails_content_general' );
                            }
    
                            eme_errors()->add( 'success_settings_saved', esc_html( __( 'Settings saved successfully.', 'em-emails' ) ) );

                        }
    
                        return;
                    }
                }
            }
    
            /**
             * Store template settings
             */
            public function eme_store_templates() {
                
                if ( isset( $_POST[ 'em_emails_styling_nonce' ] ) ) {
                    if ( ! wp_verify_nonce( $_POST[ 'em_emails_styling_nonce' ], 'em-emails-styling-nonce' ) ) {
                        eme_errors()->add( 'error_nonce_no_match', esc_html( __( 'Something went wrong. Please try again.', 'em-emails' ) ) );
                        
                        return;
                    } else {
    
                        if ( ! empty( $_POST['eme_emails_logo'] ) ) {
                            update_option( 'eme_emails_logo', esc_url( $_POST['eme_emails_logo'] ) );
                        } else {
                            delete_option( 'eme_emails_logo' );
                        }
        
                        if ( ! empty( $_POST['eme_emails_styling'] ) ) {
                            update_option( 'eme_emails_styling', $_POST['eme_emails_styling'] );
                        } else {
                            delete_option( 'eme_emails_styling' );
                        }
        
                        if ( ! empty( $_POST['eme_emails_template'] ) ) {
                            update_option( 'eme_emails_template', stripslashes( $_POST['eme_emails_template'] ) );
                        } else {
                            delete_option( 'eme_emails_template' );
                        }
    
                        eme_errors()->add( 'success_settings_saved', esc_html( __( 'Settings saved successfully.', 'em-emails' ) ) );
                        
                        return;
    
                    }
                }
            }
    
            /**
             * Store misc settings
             */
            public function eme_store_settings() {
                
                if ( isset( $_POST[ 'em_emails_settings_nonce' ] ) ) {
                    if ( ! wp_verify_nonce( $_POST[ 'em_emails_settings_nonce' ], 'em-emails-settings-nonce' ) ) {
                        eme_errors()->add( 'error_nonce_no_match', esc_html( __( 'Something went wrong. Please try again.', 'em-emails' ) ) );
                        
                        return;
                    } else {
                        
                        if ( isset( $_POST[ 'eme_email_reset' ]) ) {
                            $options = array(
                                'content_general',
                                'email_logo',
                                'email_styling',
                                'subject_general',
                                'template',
                                'preserve_settings',
                            );
                            foreach( $options as $option ) {
                                delete_option( 'eme_emails_' . $option );
                            }
                            $this->eme_set_default_values();
    
                            eme_errors()->add( 'success_settings_to_default', esc_html( __( 'Settings successfully reset to default.', 'em-emails' ) ) );
    
                            return;
                        }
    
                        if ( isset( $_POST[ 'eme_emails_preserve_settings' ]) ) {
                            update_option( 'eme_emails_preserve_settings', $_POST['eme_emails_preserve_settings'] );
                            
                            eme_errors()->add( 'success_settings_saved', esc_html( __( 'Setting saved successfully.', 'em-emails' ) ) );
    
                            return;
                        } else {
                            delete_option( 'eme_emails_preserve_settings' );

                            eme_errors()->add( 'success_settings_saved', esc_html( __( 'Setting saved successfully.', 'em-emails' ) ) );
                            
                            return;
                        }
                    }
                }
            }
            
            /**
             * Adds a link to plugin actions
             * @param $links
             * @return array
             */
            public function eme_plugin_link( $links ) {
                $add_this = array(
                    '<a href="' . admin_url( 'edit.php?post_type=event&page=em-emails' ) . '">Dashboard</a>',
                );
                return array_merge( $add_this, $links );
            }
    
        }

        /**
         * The main function responsible for returning the one true EM_Emails_Addon instance to functions everywhere.
         *
         * @return \EM_Emails_Addon
         */
        function init_em_emails() {
            global $em_emails_plugin;

            if ( ! isset( $em_emails_plugin ) ) {
                $em_emails_plugin = new EM_Emails_Addon();
                $em_emails_plugin->initialize();
            }

            return $em_emails_plugin;
        }

        // initialize
        init_em_emails();

    endif; // class_exists check
