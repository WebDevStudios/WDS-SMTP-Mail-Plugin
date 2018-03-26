# WDS SMTP Mail Plugin

**Contributors:** chmac, webdevstudios  
**Donate link:** http://webdevstudios.com  
**Tags:** mail, smtp, wp_mail, mailer, phpmailer  
**Requires at least:** 2.7  
**Tested up to:** 4.2.2  
**Stable tag:** 1.0.0  

Reconfigures the wp_mail() function to use SMTP instead of mail() and creates an options page to manage the settings. Based on WP Mail SMTP.

## Description

This plugin reconfigures the wp_mail() function to use SMTP instead of mail() and creates an options page that allows you to specify various options.

You can set the following options:

* Specify the from name and email address for outgoing email.
* Choose to send mail by SMTP or PHP's mail() function.
* Specify an SMTP host (defaults to localhost).
* Specify an SMTP port (defaults to 25).
* Choose SSL / TLS encryption (not the same as STARTTLS).
* Choose to use SMTP authentication or not (defaults to not).
* Specify an SMTP username and password.

## Installation

1. Download
2. Upload to your `/wp-contents/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.

### Using Composer

Update `composer.json` with something similar to:

```json
{
    "repositories": [
        {
            "type": "git",
            "url": "git@github.com:WebDevStudios/WDS-SMTP-Mail-Plugin.git"
        }
    ],
    "require": {
        "WebDevStudios/WDS-SMTP-Mail-Plugin": "dev-master"
    },
    "extra": {
        "installer-paths": {
            "plugins/{$name}": ["type:wordpress-plugin"]
        }
    }
}
```

...and run `composer install` or `composer update`.

#### Parent Repos

If you do not intend to commit changes to the parent repository, make sure and add `/plugins/WDS-SMTP-Mail-Plugin` to your `.gitignore`.

## Frequently Asked Questions

### My plugin still sends mail via the mail() function

If other plugins you're using are not coded to use the wp_mail() function but instead call PHP's mail() function directly, they will bypass the settings of this plugin. Normally, you can edit the other plugins and simply replace the `mail(` calls with `wp_mail(` (just adding wp_ in front) and this will work. I've tested this on a couple of plugins and it works, but it may not work on all plugins.

### Will this plugin work with WordPress versions less than 2.7?

No. WordPress 2.7 changed the way options were updated, so the options page will only work on 2.7 or later.

### Can I use this plugin to send email via Gmail / Google Apps

Yes. Use these settings:  
Mailer: SMTP  
SMTP Host: smtp.gmail.com  
SMTP Port: 465  
Encryption: SSL  
Authentication: Yes  
Username: your full gmail address  
Password: your mail password  

## Screenshots

![](https://raw.githubusercontent.com/WebDevStudios/WDS-SMTP-Mail-Plugin/master/screenshot-1.png)
Screenshot of the Options > Email Options page.

## Changelog

### 1.0.0
* Forked and revamped by WebDevStudios

### 0.9.5
* Minor security fix, hat tip JD Grimes.

### 0.9.4
* Improvement to the test email function, very low priority update.

### 0.9.3
* Fixing reported issue with passing by reference. props Adam Conway

### 0.9.2
* Removing the deprecation notice.

### 0.9.1
* $phpmailer->language became protected in WP 3.2, no longer unset on debug output.

### 0.9.0
* Typo in the From email description.
* Removed changelog from plugin file, no need to duplicate it.
* Optionally set $phpmailer->Sender from from email, helps with sendmail / mail().

### 0.8.7
* Fix for a long standing bug that caused an error during plugin activation.

### 0.8.6
* The Settings link really does work this time, promise. Apologies for the unnecessary updates.

### 0.8.5
* Bugfix, the settings link on the Plugin page was broken by 0.8.4.

### 0.8.4
* Minor bugfix, remove use of esc_html() to improve backwards compatibility.
* Removed second options page menu props ovidiu.

### 0.8.3
* Bugfix, return WPMS_MAIL_FROM_NAME, props nacin.
* Add Settings link, props Mike Challis http://profiles.wordpress.org/MikeChallis/

### 0.8.2
* Bugfix, call phpmailer_init_smtp() correctly, props Sinklar.

### 0.8.1
* Internationalisation improvements.

### 0.8
* Added port, SSL/TLS, option whitelisting, validate_email(), and constant options.

### 0.7
* Added checks to only override the default from name / email

### 0.6
* Added additional SMTP debugging output

### 0.5.2
* Fixed a pre 2.3 bug to do with mail from

### 0.5.1
* Added a check to display a warning on versions prior to 2.3

### 0.5.0
* Upgraded to match 2.3 filters which add a second filter for from name

### 0.4.2
* Fixed a bug in 0.4.1 and added more debugging output

### 0.4.1
* Added $phpmailer->ErroInfo to the test mail output

### 0.4
* Added the test email feature and cleaned up some other bits and pieces

### 0.3.2
* Changed to use register_activation_hook for greater compatability

### 0.3.1
* Added readme for WP-Plugins.org compatability

### 0.3
* Various bugfixes and added From options

### 0.2
* Reworked approach as suggested by westi, added options page

### 0.1
* Initial approach, copying the wp_mail function and replacing it
