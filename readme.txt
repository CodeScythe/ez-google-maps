=== EZ Google Maps ===
Contributors: CodeScythe
Tags: maps, markers, googlemaps
Requires at least: 4.5.4
Tested up to: 4.6.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily create and embed Google Maps into Pages and Posts

== Description ==

EZ Google Maps is a simple plugin that allows you to create a Google Map with custom markers without any coding! It works by converting an address to lat lang coordinates and placing it on a map as a marker.

A marker identifies a location on a map. By default, the EZGM marker uses the standard Google Maps marker image. You can select custom images for markers. 

You can also enter content for each marker to be displayed when clicked.

Follow plugin installation then activate.

You will need to obtain a Google Maps API key before you can use this plugin. 

An API key can be obtained from the following url. Make sure you have a Google account first and you’re logged in. [Get key](https://developers.google.com/maps/documentation/javascript/)

Just click on “GET A KEY” and follow the instructions. 

Once you have your key, copy and paste it into the "Google Maps API key” text area in the EZ Google Maps options page found in the admin “Settings” menu 

In order to display the fields for creating the map select the post type(s) you wish to display it on.

Hold the command key ( control for PC ) to select more than one post type.

Head to the edit screen for the selected type(s). 

Below the content WYSIWYG editor you’ll see a box with the heading “EZ Google Maps”. 

Follow the instructions to add markers to your map. 

The coordinates for the marker are automatically generated after you enter an address in the address field. 

Make sure the coordinates column reads “Complete” before saving.

If no image is selected Google’s default marker will be used.  

The width and height of the map can be set using the relevant fields. 

The map is inserted into the content area using the following shortcode: 

[ez-google-map]

If you wish to use a map from a different page add the attribute post_id with the id of the page you wish to use:

Example:

[ez-google-map post_id="10"]

By default the map automatically zooms to a level that displays all of the markers. 


== Installation ==

Installing "EZ Google Maps" can be done either by searching for "EZ Google Maps" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
2. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard
3. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Selecting post type the marker will be displayed on
2. EZ Google Maps custom fields in edit screen

== Frequently Asked Questions ==

= What is this plugin for? =

Simply put, this plugin creates a google map specfically for displaying multiple address points as markers.

== Changelog ==

= 1.0.0 =
* 2016-11-14
* Initial release

== Upgrade Notice ==

= 1.0.0 =
* 2016-11-14
* Initial release
