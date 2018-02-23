<?php

    /**
     * Content for the administration page
     */
    function em_emails_misc_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sorry, you do not have sufficient permissions to access this page.' ) );
        }
    
        // Now display the settings editing screen
        echo '<div class="wrap">';
        echo '<div id="icon-options-general" class="icon32"><br /></div>';

        // header
        echo "<h2>Events Manager Email misc</h2>";
    
        eme_show_admin_notices();
        
        echo EM_Emails_Addon::eme_admin_menu();
    
?>
        <p>Here are some settings to reset to default and to preserve the settings om uninstall.</p>

        <form name="eme-styling" method="post" action="">
            <input name="em_emails_settings_nonce" type="hidden" value="<?php echo wp_create_nonce( 'em-emails-settings-nonce' ); ?>" />
        
            <h3>Reset to default settings</h3>
            <p>
                <label for="eme_email_reset" class="screen-reader-text">Reset all</label>
                <input type="checkbox" id="eme_email_reset" name="eme_email_reset" value="1" /> Reset to default settings
            </p>
        
            <h3>Preserve values on uninstall</h3>
            <p>If you deactivate and delete the plugin, all saved values are deleted. Unless you tick this box, then they're saved.</p>
            <p>
                <?php $selected = false; ?>
                <?php if ( true == get_option( 'eme_emails_preserve_settings' ) ) { $selected = ' checked="checked"'; } ?>
                <label for="eme_emails_preserve_settings" class="screen-reader-text">Preserve values</label>
                <input type="checkbox" id="eme_emails_preserve_settings" name="eme_emails_preserve_settings" value="1" <?php echo $selected; ?>/> Preserve values on uninstall
            </p>
        
            <p><?php submit_button( __( 'Save settings', 'em-emails' ), 'primary' ); ?></p>
        </form>

        <h3>Support</h3>
        <p>if you need support, turn to my <a href="https://github.com/Beee4life">Github page</a>.</p>

        <h3>About the author</h3>
        <p>I'm a Wordpress developer, specializing in rebuilds and custom plugin development. If you're interested to hire me, contact me through my <a href="http://www.berryplasman.com">website</a>.</p>
<?php
    echo '</div><!-- end .wrap -->';
}
