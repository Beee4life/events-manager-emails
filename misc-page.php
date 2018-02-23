<?php

    /**
     * Content for the misc page
     */
    function em_emails_misc_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sorry, you do not have sufficient permissions to access this page.' ) );
        }
        ?>
        <div class="wrap">
            <div id="icon-options-general" class="icon32"><br /></div>

            <h2>Events Manager Email misc</h2>
        
            <?php eme_show_admin_notices(); ?>
        
            <?php echo EM_Emails_Addon::eme_admin_menu(); ?>
            <p><?php _e( 'Here are some settings to reset to default and to preserve the settings om uninstall.', 'em-emails' ); ?></p>
    
            <form name="eme-styling" method="post" action="">
                <input name="em_emails_settings_nonce" type="hidden" value="<?php echo wp_create_nonce( 'em-emails-settings-nonce' ); ?>" />
            
                <h3><?php _e( 'Reset to default settings', 'em-emails' ); ?></h3>
                <p>
                    <label for="eme_email_reset" class="screen-reader-text"><?php _e( 'Reset all', 'em-emails' ); ?></label>
                    <input type="checkbox" id="eme_email_reset" name="eme_email_reset" value="1" /> <?php _e( 'Reset to default settings', 'em-emails' ); ?>
                </p>
            
                <h3><?php _e( 'Preserve values on uninstall', 'em-emails' ); ?></h3>
                <p><?php _e( 'If you deactivate and delete the plugin, all saved values are deleted. Unless you tick this box, then they\'re saved.', 'em-emails' ); ?></p>
                <p>
                    <?php $selected = false; ?>
                    <?php if ( true == get_option( 'eme_emails_preserve_settings' ) ) { $selected = ' checked="checked"'; } ?>
                    <label for="eme_emails_preserve_settings" class="screen-reader-text"><?php _e( 'Preserve values', 'em-emails' ); ?></label>
                    <input type="checkbox" id="eme_emails_preserve_settings" name="eme_emails_preserve_settings" value="1" <?php echo $selected; ?>/> <?php _e( 'Preserve values on uninstall', 'em-emails' ); ?>
                </p>
            
                <p><?php submit_button( __( 'Save settings', 'em-emails' ), 'primary' ); ?></p>
            </form>
    
            <h3><?php _e( 'Support', 'em-emails' ); ?></h3>
            <p><?php echo sprintf( __( 'If you need support, turn to my <a href="%s">Github page</a>.', 'em-emails' ), esc_url( 'https://github.com/Beee4life/events-manager-emails/issues' ) ); ?></p>
    
            <h3><?php _e( 'About the author', 'em-emails' ); ?></h3>
            <p><?php echo sprintf( __( 'I\'m a Wordpress developer, specializing in rebuilds and custom plugin development. If you\'re interested to hire me, contact me through my <a href="%s">website</a>.', 'em-emails' ), esc_url( 'http://www.berryplasman.com' ) ); ?></p>
            <p></p>

        </div>
<?php
    }
