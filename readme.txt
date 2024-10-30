=== Knowledgeblog Table of Contents ===

Contributors: philliplord, sjcockell, knowledgeblog, d_swan
Tags: table-of-contents, res-comms, scholar, academic, science
Requires at least: 3.0
Tested up to: 3.2
Stable tag: 0.4

This plugin writes an alphabetic list of posts in a single category in place of a [ktoc] shortcode.

== Description ==

Interprets the &#91;ktoc&#93; shortcode to produce a bulleted list, alphabetical table of contents, like those used on [Knowledgeblogs](http://knowledgeblog.org).

The ToC is generated from a single category, which can be controlled through a simple settings menu, or through the "cat" attribute on the shortcode (which overrides the default setting).

Unlike other Table of Contents plugins, KToC does not embed affiliate links in the output.

== Installation ==

1. Unzip the downloaded .zip archive to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Click 'Settings' and select which Category you wish to display by default
1. Use the [ktoc] shortcode somewhere on your site, to automatically generate a table of contents.

== Changelog ==

= 0.2 =
* Version 0.2 can deal with multiple authors, when controlled by the [Co-authors Plus](http://wordpress.org/extend/plugins/co-authors-plus/) plugin.
* It retains the capacity to cope with normal, singly authored posts.

= 0.3 =
* Put in a workaround for a bug where get_coauthors() sometimes returns the wrong info. 
   * Defaults to Wordpress-native author information for posts with a single author.

= 0.4 =
* Added 'fill' attribute to shortcode, which allows for custom attribution (i.e. 'posted by' to replace the default 'by')

== Upgrade Notice ==

= 0.2 =
This version provides compatibility with Co-authors Plus.

= 0.3 =
Bugfix release.

= 0.4 =
Added 'fill' attribute to shortcode, which allows for custom attribution (i.e. 'posted by' to replace the default 'by')


== Copyright ==

This plugin is copyright Simon Cockell, Newcastle University and is licensed
under GPLv2. 
