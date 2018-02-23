# Events Manager Emails Addon

Welcome to the Email Addon for [Events Manager](http://wp-events-plugin.com/). 

## Version 
0.1

## Description 

This plugin gives you the option to send an email to every person who registered for an event, through Events Manager.

## Installation

Uploading a zip file
1. Go to your WordPress Admin plugins page.
1. Click on the "Upload" link near the top of the page and browse for the Events Manager Emails zip file
1. Activate the plugin by clicking `Activate` after installation.

Uploading the extracted zip by FTP
1. Extract the Events Manager Emails zip file.
1. Upload them by FTP to your plugins directory (mostly wp-content/plugins).
1. Go to your WordPress Admin plugins page.
1. Activate the plugin by clicking `Activate` after installation.

## Usage

When activated the plugin sets a :

* default template
* default styling
* default message

## Available variables

* %site_name% : replaced by the "Site Title", which is defined in Settings > General
* %email_styling% : replaced by the CSS, which is defined on the styling page.
* %home_url% : replaced by the url, which is defined in get_option('home_url')
* %logo% : replaced by the URL stored in "Header logo"
* %display_name% : replaced by the display name of the user
* %first_name% : replaced by the user's first name
* %last_name% : replaced by the user's last name
* %email_message% : replaced by the message you want to send

## Available actions

There are a couple of actions available, meant to ouput an optional extra text/description for your users.

* eme_after_event_label
* eme_after_email_type_label
* eme_after_email_subject_label
* eme_after_email_content_label
* eme_after_test_label
* eme_after_logo_label
* eme_after_styling_label
* eme_after_template_label

### Suggestions

If you have any suggestions, post them on [GitHub](https://github.com/Beee4life/events-manager-emails/issues).
