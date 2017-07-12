=== Dewey's Open Graph Helper ===
Contributors: whatadewitt
Tags: social, open graph, facebook, sharing
Requires at least: 3.5.1
Tested up to: 4.5
Stable tag: 2.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple plugin for adding open graph tags to WordPress sites. Focuses more on theme and plugin developers. No admin interface (yet), everything is configured via code.

== Description ==

A simple plugin for adding open graph tags to WordPress sites. Focuses more on theme and plugin developers. No admin interface (yet), everything is configured via code. (I'll write a better description when I can think of one...)

== Installation ==

1. Upload the `wad_open_graph` directory to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. `add_post_type_support` for `ogtags` to the different post types you want to enable open graph tags for
4. Customize the open graph settings by using the `og_tags` filter

== Frequently Asked Questions ==

= How do I use this? =

After you have installed the plugin, the plugin will automatically add open graph tags to your site's front page. `og:site_name` and `og:title` will both be set to your site's name, `og:type` will be "website", `og:url` is the site's url and `og:description` is the WP site description.

After you've enabled support for each of the post type(s), you begin to see open graph tags appear in those post type page `<head>` tags.

On post types that support 'ogtags', the plugin will add og tags for `og:site_name` (your blog's title), `og:title` (the post title), `og:url` (the post permalink), `og:description` (the post excerpt) and `og:type` is automatically set to "article". If your post has a thumbnail image, that will be set as the `og:image`.

= Can I customize the og tags? =

You can!

You can customize all of the different posts you're supporting using the `og_tags` filter. Also, if you're supporting multiple post types and only want to customize a particular post type, you can use the `[POST_TYPE]_og_tags` filter, where you can replace `[POST_TYPE]` with `post` or `page` (or whatever the post_type is) to customize the content of a particular post type.

== Changelog ==

= 2.0.5 =
* properly defined the php section of the template

= 2.0.4 =
* fixed the issue where apostrophes aren't always showing properly in shares

= 2.0.3 =
* Small tweaks based on some feedback around custom data

= 2.0.2 =
* Small changes to fix some issues with some of the og tag meta not being rendered properly

= 2.0.1 =
* Small changes to fix some issues with some of the og tag meta not being rendered properly

= 2.0.0 =
* Added a meta box to make it easier to use custom og tag meta

= 1.1.0 =
* Removed the filter from being called on the homepage, as it was causing unwanted overwrites

= 1.0.1 =
* Moved array declaration to fix error notice

= 1.0 =
* Initial "open" release
