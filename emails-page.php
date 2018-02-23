<?php

    /**
     * Content for the emails page
     */
    function em_emails_emails_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sorry, you do not have sufficient permissions to access this page.' ) );
        }
        ?>
        <div class="wrap">
            <div id="icon-options-general" class="icon32"><br /></div>

            <h2>Events Manager Emails</h2>
        
            <?php eme_show_admin_notices(); ?>
        
            <?php echo EM_Emails_Addon::eme_admin_menu(); ?>

            <p><?php _e( 'To see which variables are available, see the "Help" menu (top right).', 'em-emails' ); ?></p>
        
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

        </div>
<?php
    }
