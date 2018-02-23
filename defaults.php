<?php

$css = "body {
    font-family: Trebuchet MS, Arial, Verdana, sans-serif;
    margin: 0;
    padding: 0;
}
table#main {
    width: 100%;
    height: 100%;
}
table.contenttable {
    width: 600px;
    padding: 20px;
    background: #ffffff;
}
table.mailcontent {
    padding-top: 0;
    margin-bottom: 20px;
}
.header {
    margin-top: 20px;
}
.eventimage {
    float: right;
    max-width: 150px;
    margin-left: 15px;
    margin-bottom: 15px;
}
.eventimage img {
    max-width: 150px;
    height: auto;
}
td img.headerimage {
    max-width: 100%;
    height: auto;
    margin-bottom: 15px;
}
span.underline {
    text-decoration: underline;
}
p.paymentinfo {
    border: 2px dotted #ed2628;
    padding: 5px;
    clear: both;
}
.hr {
    width: 100%;
    border-bottom: 1px solid #444444;
    margin: 0;
}
a:link,
a:visited,
a:active {
    color: #ed2628;
}
.hide {
    display: none;
}";
update_option( 'eme_emails_styling', $css );

$template = '<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>%site_name%</title>
        <style>%email_styling%</style>
    </head>
    <body style="">

    <!-- Main Table -->
    <table id="main" cellpadding="0" style="background: #eeeeee;">
        <tr align="center">
            <td>

                <!-- Header -->
                <table class="contenttable header">
                    <tr>
                        <td align="center"><a href="%home_url%"><img src="%logo%" alt="" /></a></td>
                    </tr>
                    <tr>
                        <td><p class="hr"> </p></td>
                    </tr>
                </table>

                <!-- Content -->
                <table class="contenttable mailcontent" cellpadding="0" cellspacing="0">
                    <tr>
                        <td valign="top">

                            Dear %first_name%,<br /><br />

                            %email_message%

                            <br /><br /><hr />

                            This is an automated email from the <a href="%home_url%">%site_name%</a>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

    </body>
</html>
';
update_option( 'eme_emails_template', $template );

$message = '<p>This is the actual email content, which will replace %email_message% in the template.</p>
<p>Paste anything you want here. HTML is allowed, php is not.</p>
<p>Find all available variables in the "Help" menu (top right).</p>
<p>Please visit <a href="%home_url%/">our site</a> for more info.
';
update_option( 'eme_emails_content_general', $message );
