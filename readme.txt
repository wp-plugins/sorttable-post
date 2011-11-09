=== SortTable Post ===

Contributors: sscovil
Tags: post, table, sort, sortable, sorttable, index
Requires at least: 3.2.1
Tested up to: 3.2.1
Stable tag: 3.0

This plugin allows you to display an index of posts (or a custom post type) in a sortable table using a simple shortcode.

== Description ==

Ever want to list your WordPress posts (or custom post type entries) in an easy-to-read, sortable data table? This plugin makes it easy to do just that.

**How To Use**

Add a sortable table of all blog posts by placing this shortcode into a post or page:

`[sorttablepost]`


Or you can specify in the shortcode the name of a custom post type to show.

`[sorttablepost type="my-custom-post-type"]`


**Use Custom Taxonomies**

You can replace the Categories or Tags columns (or both) with your own custom taxonomies.

`[sorttablepost cat="my-custom-taxonomy" tag="another-custom-taxonomy"]`


**Hide Columns**

You can omit undesired columns by using one or more of the following shortcode options:

`[sorttablepost nothumb="true" nodate="true" nocats="true" notags="true"]`


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

I know, I know. The default colors look pretty nasty in most themes. Also, you may want to style individual columns. Below is the code I used to change the default colors to match my theme on the live demo seen here: http://mynewsitepreview.com/csv2sorttable-wordpress-plugin-live-demo

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

= 3.0 =
* Added options to omit the Thumbnail, Date, and Category columns.
* Cleaned up code for human readability.

= 2.0 =
* Added support for custom taxonomies.
* Added option to omit the Tags column.

= 1.0 =
* First public release.