=== Plugin Name ===
Contributors: rgyure
Donate link: http://www.ryangyure.com/
Tags: mailman
Requires at least: 3.8
Tested up to: 4.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple integration of GNU Mailman into Wordpress.

== Description ==

Integrate GNU Mailman into your Wordpress website.  This script allows administrators to add multiple mailman mailing lists for users to subscribe to.  Users can subscribe or unsubscribe to any of the mailing lists through their user profile.

== Installation ==

1. Upload the contents of the zip to the '/wp-content/plugins/' directory.
2. Navigate to the 'Add New' in the plugins dashboard
3. Search for 'gnu-mailman'
4. Click 'Install Now'
5. Activate the plugin on the Plugin dashboard

== Screenshots ==
1. /assets/screenshot-1.png
2. /assets/screenshot-2.png

== Frequently Asked Questions ==

= How do I use the plugin? =

After following the installation instructions, find the "Mailman" menu item on the left-hand menu.  Click on the "Lists" submenu.  Enter in the Mailing List Name (for display purposes), the mailing list URL (e.g. http://srv.test.com/mailman/admin/test_test.com), and the mailing list password.

== Changelog ==

= 1.0.0 =
* Initial Release

= 1.0.1 =
* Fixed a not valid array bug on initial installation.

= 1.0.2 =
* Fixed bug where an administrator couldn't change user's subscription preferences

= 1.0.3 =
* Fixed various PHP notices
* Added support to verify Mailman list during creation
* Added error logging and catching
* Added Default Timeout to Settings Page

= 1.0.4 =
* Fixed display error when user was set to "nomail"

= 1.0.5 =
* Refactor Subscribe/Unsubscribe functions
* PHP Code Standard Fixes
* Fixed bug in user-forms.php where index ids would change when a list is added/removed

= 1.1.0 =
* Added extension for the Wordpress Groups plugin

== Known Issues ==
* Re-enabling users set to "nomail" may not always work correctly.