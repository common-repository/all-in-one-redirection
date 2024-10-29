=== All In One Redirection ===
Contributors: vsourz1td
Tags: Redirection, 301 Redirection, http to https redirection, All In One Redirection, WWW Redirection, SEO Redirection Plugin, SEO, Single Time Redirection, One to one redirection, Detect 404 Page
Requires at least: 3.5
Tested up to: 6.2.2
Stable tag: 2.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

All In One Redirection plugin provide you to manage 301/302 redirections in 1:1 relationship, Host/www redirection, tracking of 404 pages and Import/Export redirection list. 

== Description ==
Best ways that indicate the page has **Permanently/Temporary** moved to a new location.

Best recommended to set up 301 redirects in **1:1 relationship** in WordPress when you change the URLs of your posts and pages or move your website from one domain to another to manage their page rank (or what-have-you) will be passed along to them.

Manage redirection list with **Access Date, Source URL, Referrer, Access IP and Hits**.

Manage the requested URL structure with **host and www** settings.

Don't require to write rules in Apache .htaccess – it works entirely inside WordPress.

**Eg:** If you need to redirect all the old 'HTTP' URL to new secure 'HTTPS' URL then you just need to activate the plugin, it will set destination URL based on your WordPress Site URL and if you want to do custom settings in host/www then you can change it from Redirection Setting in plugin menu.

**Keep tracking of 404 pages and redirect on relevant pages** – Capture 404 pages from the website with their Access Date, Source URL, Referrer, Access IP, Hits, easily find that URLs and redirect it to relevant pages with Action tab.

**Bulk Import/Export** - Download/Upload redirection URLs in csv format from Tool in plugin menu.

It's 100% free!


**Need Support?** <wp.support@vsourz.com>

= Features =
* 301/302 Redirection.
* All 404 Redirect to Homepage on One Click.
* All redirection is occur based on **1:1 relationship** for best SEO practice.
* **Bulk Import/Export** in CSV format of redirection list.
* **Full logs of all redirected URLs** with Last Access Date, Referrer URL and Access IP address.
* You can find the number of Hits in redirection links.
* Redirection based on **Regular Expression**.
* Option to hide the URL redirection, so you can exclude any URL from redirection list.
* Exclude the back-end from redirection
* Entire website redirected on another website
* **Multiple Update/Delete** option.
* **Auto 404 page Tracking** with Access Date, Source URL, Referrer, Access IP.
* **Custom Redirection** based on host and www setting.
* Apache .htaccess is not required – works entirely inside WordPress.
* WWW to NON-WWW and NON-WWW to WWW redirection without Apache .htaccess rules.
* HTTP to HTTPS and HTTPS to HTTP redirection without Apache .htaccess rules.

= How to use? =
1. Install and activate the plugin.
2. Add new redirection rule.
3. You need to save the Redirection Setting once if you change the domain.


= License =
GPLv2 - https://www.gnu.org/licenses/gpl-2.0.html


== Installation ==

= Install via WordPress Admin =
1. Ready the zip file of the plugin
2. Go to Admin > Plugins > Add New
3. On the upper portion click the Upload link
4. Using the file upload field, upload the plugin zip file here and activate the plugin

= Install via FTP =
1. First, unzip the plugin file
2. Using FTP go to your server's wp-content/plugins directory
3. Upload the unzipped plugin here
4. Once finished login into your WP Admin and go to Admin > Plugins
5. Look for 'All In One Redirection' and activate it


== Frequently Asked Questions ==

= Is provide the option for bulk upload? =
Yes, Its provide the bulk import and export functionality using CSV file.

= What is the Redirection Setting? =
You need to set your default host and www settings based on your domain so it will redirect other URL based on settings. There is no need to write any rules in Apache .htaccess file. (Eg: for secure redirection or non-www redirection)

= How regular expression will work in redirection? =
You need to enable the regular expression option in that specific redirection.
The plugin will match the specific string in requested URL. If match, it will redirection to the destination URL.
**Eg.** /team (It will match 'team' word in requested URL. If match found, it will redirect to the destination URL.
**Eg.** /news/* (It will match 'news' word in requested URL and it will find any string after the 'news' word. If found, it will redirect to the destination URL.

= Can we redirect the homepage to another page? =
Yes, you need to add slash(/) in source URL to redirect the homepage.

= How we can redirect the entire website to another website? =
You need to add slash(/) in source URL and need to mark for regular expression to redirect entire website.

= Can we hide the specific redirection rules? =
Yes, you can hide the specific redirection rules from the redirection list setting.

= Is there option to add redirection from detected 404 pages list? =
Yes, you can easily add redirection rule for detected 404 page.

= Can we delete the multiple redirection rules? =
Yes, you can delete multiple redirection rules from redirection list.

= What to do if redirection plugin not work? =
If the plugin does not work on the website, contact our support Team via following email address: <wp.support@vsourz.com>.
If you think, that you found a bug in our redirection plugin or have any question contact us at <wp.support@vsourz.com>. Our support team will solve within 24 hours.

== Screenshots ==
1. Plugin Dashboard
2. Add Redirection
3. Redirection List
4. Redirection Setting
5. 404 Pages List


== Changelog ==

= 2.2.0 =
* Compatible with latest version wordpress 6.2.2
* We have upgraded with Security patches
* Enhancement 404 page to Home Page redirection feature

= 2.1.0 =
* Provide the home page redirect to another page
* Entire website redirected on another website using the regular expression
* Exclude the back-end from redirection
* Auto skip the domain name from source URL
* Prevent the duplicate entry of rules
* Bug Fixing

= 2.0.0 =
* Now you can redirect all URLs which contain space/%20 in URLs
* Compatible up to PHP 7.1.10
* Change in Add redirection functionality on 404 Page
* Add Note in Redirection Setting while you change custom setting in redirection

= 1.1.0 =
* Highlight the Hidden records
* Add Filter and Search Option in 301/302 Record list
* Add Search option in 404 Record list
* Improve Pagination Functionality

= 1.0.1 = 
* Security update
* Bug Fixing

= 1.0.0 = 
* Initial


== Upgrade Notice ==

See changelog