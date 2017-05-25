=== Share a Password ===
Contributors: fatlabllc
Donate link: N/A
Tags: passwords, security
Requires at least: 3.8.1
Tested up to: 4.7.5
Stable tag: 1
License: GPLv2 or later

Securely (as possible) share a password via temporary URL. Passwords are stored in an encrypted format and deleted after 24 hours.

== Description ==

Securely (as possible) share a password via temporary URL. Passwords are stored in an encrypted format and deleted after 24 hours. Each entry is encrypted with its own unique key using open-ssl. Any page in your site can become a password sharing page by using the shortcode [share-a-password] - It's that simple.

Share a Password was developed by FatLab, LLC (https://www.fatlabwebsupport.com/) and is maintained at GitHub (https://github.com/fatlabllc/fatlab-share-a-password-plugin). It's open source. If you can make it better or find a bug to squash, please do!

Warranty: There is none. Just like any other software you install, you are responsible for how it is used. This is a security focused plugin though we don't make any warranty or claim to how 'secure' it is. Use with caution and be smart, don't use it to store credit card information, social security numbers etc. The idea here was simply that it would be more secure than sending a password by email. That's it, the rest is up to you.

== Installation ==

Upload the Share a Password plugin to your website by using the administrative area to upload the zip file or by FTP'ing the share-a-password to your /wp-content/plugins directory.  Activate it. Place the shortcode: [share-a-password] with any page you want the form to appear in. The same page will be used to generate the form, display the temporary URL and display the decrypted information to the end user.

 == Screenshots ==
1. /assets/sap-admin.png
2. /assets/sap-form.png
3. /assets/sap-url.png
4. /assets/sap-result.png

== Changelog ==

Current Version: 1

= 1 =
* Updates to readme
* Screenshots added
* Test and moved to version 1 as first stable release

= 0.4 BETA =
* BIG Change: Moved encryption from outdated mcrypt to open-ssl - thanks to https://bhoover.com/using-php-openssl_encrypt-openssl_decrypt-encrypt-decrypt-data/
* Encryption change fixed issues with large encrypted data appearing blank on output
* Created simple admin screen under tools
* Cleaned up front-end interface
* Changed database field for encrypted password to varchar(5000)

= 0.3 BETA =
* Fixed issue with cron job not deleting records over 24 hours old
* fixed bug in schedule/cron job to delete older records
* Set form field limit to 900 characters (that should be enough)

= 0.2 BETA =
* Added plugin version number to options table (remove on deletion)
* Added view count to display of password
* Added views column to database table
* Added incrementing integer upon view or url
* Changed database field for encrypted password to varchar(255)
* Set form field limit to 150 characters

= 0.1 BETA =
* It was what it was... you gotta start somewhere, huh?

== Upgrade Notice ==

= 1 =
* Updates to readme
* Screenshots added
* Tested and moved to version 1 as first stable release

= 0.4 =
* Changed encryption from outdated mcrypt to open-ssl
* Interface improvements
* Introduced simple admin screen under Tools