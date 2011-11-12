=== SortTable Post ===

Contributors: sscovil
Tags: post, table, sort, sortable, sorttable, index
Requires at least: 3.2.1
Tested up to: 3.2.1
Stable tag: 4.2

This plugin allows you to display an index of posts (or a custom post type) in a sortable table on any page or post, using a simple shortcode.

== Description ==

Ever want to list your WordPress posts in an easy-to-read, sortable data table? This plugin makes it easy to do just that. It supports post thumbnails, custom post types, custom taxonomies, and (as of v4.0) custom fields.

By default, the plugin outputs the following columns:

1. Post Thumbnail (if enabled)
1. Post Title
1. Post Date
1. Post Categories
1. Post Tags

Options include:

* Omit any of the default columns
* Replace `Post` with a custom post type
* Replace `Categories` and `Tags` with custom taxonomies
* As of version 4.0 you can insert any number of custom field columns


**How To Use**

`[sorttablepost]`

Place this shortcode into a post or page to insert a sortable table of all posts.

**Hide Standard Columns**

`[sorttablepost nothumb="true" notitle="true" nodate="true" nocats="true" notags="true"]`

You can omit any undesired columns by using one or more of these shortcode options.

**Show Custom Post Type**

`[sorttablepost type="my-custom-post-type"]`

You can specify the name of a custom post type (or `page`), instead of showing posts.

**Use Custom Taxonomies**

`[sorttablepost cat="my-custom-taxonomy" tag="another-custom-taxonomy"]`

You can replace the standard `Categories` or `Tags` columns (or both) with custom taxonomies.

**Use Custom Fields**

`[sorttablepost meta="Custom Field Key,Another Custom Field Key,Yet Another"]`

As of v4.0, you can add as many custom field columns as you like. Use a comma-seperated list of field keys.

**Assign Unique ID to Table**

`[sorttablepost id="mytable"]`

As of v4.2, you can give each table a unique ID. This is particularly useful when displaying multiple tables on the same page, as it allows you to style each one differently.

**About This Plugin**

For more information about this plugin, visit: http://mynewsitepreview.com/sorttablepost/

To see a live demo, visit: http://mynewsitepreview.com/sorttablepost-wordpress-plugin-live-demo

**About SortTable.js**

The sortable table portion of this plugin is made possible by Stuart Langridge's awesome Javascript library.

Documentation for sorttable.js can be found at: http://www.kryogenix.org/code/browser/sorttable/

== Installation ==

1. Upload the entire folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Insert the shortcode `[sorttablepost]` in any post or page

== Frequently Asked Questions ==

= How do I style my sortable tables? =

I know, I know. The default colors look pretty nasty in most themes. Also, you may want to style individual columns. Below is the code I used to change the default colors to match my theme on the live demo seen here: http://mynewsitepreview.com/sorttablepost-wordpress-plugin-live-demo

You can add similar code to your theme's `style.css`:

`/* Header Row Colors */
table.sortable thead tr {
	background-color: #71a7c8 !important;
	color: #fff !important;
}

/*  Highlight Color for Header Row Cells on Hover*/
table.sortable th:hover:not(.sorttable_nosort) {
	background: #b3d0e1 !important;
}

/* Shading For Even Rows */
table.sortable tr:nth-child(even) { background: #f6f6f6 !important; }

/* Table Border Color */
table.sortable th,
table.sortable td {
	border: 1px solid #71a7c8 !important;
}

/*  Style & Width of Particular Columns */
table.sortable td.col3,
table.sortable td.col4,
table.sortable td.col5,
table.sortable td.col6,
table.sortable td.col7 {
	text-align: center !important;
	width: 10% !important;
}`


== Changelog ==

= 4.2 =
* Added function to convert links and email addresses contained in custom field values into HTML links.
* Added option to assign a unique ID to the table.

= 4.1 =
* Added option to include a Post Excerpt column.
* Cleaned up code by grouping variable initializations.

= 4.0 =
* Added option to omit the Title column.
* Added support for custom field columns.
* Made hide-column shortcode options work regardless of singular or plural (e.g. `nothumb="true"` works the same as `nothumbs="true"`).

= 3.0 =
* Added options to omit the Thumbnail, Date, and Category columns.
* Cleaned up code for human readability.

= 2.0 =
* Added support for custom taxonomies.
* Added option to omit the Tags column.

= 1.0 =
* First public release.