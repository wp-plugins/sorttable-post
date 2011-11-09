<?php
/*
Plugin Name: SortTable Post
Plugin URI: http://mynewsitepreview.com/sorttablepost
Description: Display an index of posts (or a custom post type) in a sortable table using a simple shortcode.
Version: 3.0
Author: Shaun Scovil
Author URI: http://shaunscovil.com/
License: GPL2
*/

/*  Copyright 2011  Shaun Scovil  (email : sscovil@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Load plugin stylesheet, as well as sorttable.js by Stuart Langridge
if ( !is_admin() ) {
	function sorttablepost_enqueue_scripts() { 
		wp_enqueue_script( 'sorttable', plugins_url( '/scripts/sorttable.js', __FILE__ ) );
	} 
	add_action('wp_enqueue_scripts', 'sorttablepost_enqueue_scripts'); 
	
	function sorttablepost_enqueue_styles() {
		$myStyleUrl = plugins_url('/css/sorttablepost.css', __FILE__);
		wp_register_style('sorttablepost', $myStyleUrl);
		wp_enqueue_style( 'sorttablepost');
	}
	add_action('wp_print_styles', 'sorttablepost_enqueue_styles');
}

function sorttablepost($args){

	// Options for disabling certain columns
	$opt_nothumb = $args['nothumb'];
	$opt_nodate = $args['nodate'];
	$opt_nocats = $args['nocats'];
	$opt_notags = $args['notags'];

	// Automatically disable thumbnails if the current theme does not support them
	if( !current_theme_supports( 'post-thumbnails' ) == TRUE ) {
		$opt_nothumb = '';
	}

	// Options for custom post type and taxonomies
	$opt_type = $args['type']; // Allow user to specify a Custom Post Type
	$opt_cat = $args['cat']; // Allow user to replace 'Category' with a Custom Taxonomy
	$opt_tag = $args['tag']; // Allow user to replace 'Tags' with a Custom Taxonomy

	// Used to validate custom post type and taxonomies
	$valid_post_types = get_post_types('','names');
	$valid_taxonomies = get_taxonomies('','names');

	// Validate custom post type
	if (!in_array($opt_type, $valid_post_types)) {
		$opt_type = 'post'; // By default, use Posts
	}
	// Validate custom taxonomy ('cat' option)
	if (!in_array($opt_cat, $valid_taxonomies)) {
		$opt_cat = '';
		$catlabel = 'Categories'; // By default, use Categories
	} else {
		$taxa = get_taxonomy($opt_cat);
		$catlabel = $taxa->labels->name;
	}
	// Validate custom taxonomy ('tag' option)
	if (!in_array($opt_tag, $valid_taxonomies)) {
		$opt_tag = '';
		$taglabel = 'Tags'; // By default, use Tags
	}
	else {
		$taxb = get_taxonomy($opt_tag);
		$taglabel = $taxb->labels->name;
	}
	// Used in the loop below to assign a custom key to each cell in the date column
	// This hack is used because most date formats will not sort correctly otherwise
	$count = 0;

	// Begin recording echos as an output string
	ob_start();

	// Create table header row
	echo '<table class="sortable"><tr>';
	if( !$opt_nothumb == "true" ) { echo '<th class="sorttable_nosort"></th>'; }
	echo '<th>Title</th>';
	if( !$opt_nodate == "true" ) { echo '<th>Date</th>'; }
	if( !$opt_nocats == "true" ) { echo '<th>' . $catlabel . '</th>'; }
	if( !$opt_notags == "true" ) { echo '<th>' . $taglabel . '</th>'; }
	echo '</tr>';
	
	// Begin the loop to generate the table body
	$sorttable = new WP_Query( array( 'posts_per_page' => -1, 'post_type' => $opt_type, 'orderby' => 'date', 'order' => 'DESC' ) );
	if($sorttable->have_posts()) : while($sorttable->have_posts()) : $sorttable->the_post();

		// Set variables to use in output strings
		$count++;
		$link = get_permalink();
		if( !$opt_nothumb == "true" ) { $thumb = get_the_post_thumbnail($post->ID, array(50,50) ); }
		$title = get_the_title();
		if( !$opt_nodate == "true" ) { $date = get_the_date(); }
		if( !$opt_nocats == "true" ) {
			if( $opt_cat == '' ) {
				$categories = get_the_category(); // Default to categories
			} else {
				// $categories = get_terms( $opt_cat );
				$categories = get_the_term_list( $post->ID, $opt_cat, '', ', ', '' ); // Use custom taxonomy
			}
		}
		if( !$opt_notags == "true" ) {
			if( $opt_tag == '' ) {
				$tags = get_the_tags(); // Default to tags
			} else {
				// $tags = get_terms( $opt_tag );
				$tags = get_the_term_list( $post->ID, $opt_tag, '', ', ', '' ); // Use custom taxonomy
			}
		}
		
		// Create table body row
		echo '<tr>';
		if( !$opt_nothumb == "true" ) {
			if ( $thumb ) {
				echo '<td><a href="' . $link . '">' . $thumb . '</a></td>';
			} else {
				echo '<td>No Image</td>';
			}
		}
		echo '<td><a href="' . $link . '">' . $title . '</a></td>';
		if( !$opt_nodate == "true" ) { echo '<td sorttable_customkey="' . $count . '">' . $date . '</td>'; }
		if( !$opt_nocats == "true" ) {
			echo '<td>';
			if( $opt_cat == '' ) {
				if( $categories ) : foreach($categories as $category) {
					echo '<a href="' . get_category_link( $category->cat_ID ) . '">' . $category->cat_name . '</a> '; // Default to categories
				} endif;
			} else {
				// echo '<a href="' . get_term_link( $categories ) . '">' . $category->name . '</a> ';
				echo $categories; // Use custom taxonomy
			}
			
			echo '</td>';
		}
		if( !$opt_notags == "true" ) {
			echo '<td>';
			if( $opt_tag == '' ) {
				if( $tags ) : foreach($tags as $tag) {
					echo '<a href="' . get_tag_link( $tag->term_id ) . '">' . $tag->name . '</a> '; // Default to tags
				} endif;
			} else {
				// echo '<a href="' . get_term_link( $tags ) . '">' . $tag->name . '</a> ';
				echo $tags; // Use custom taxonomy
			}
			echo '</td>';
		}
		echo '</tr>';
		// End of create table body row

	endwhile; endif;
	// End of loop

	echo '</table>';
	// End of table

	$content = ob_get_contents();;
	ob_end_clean();
	return $content;
}

// Add shortcode
add_shortcode("sorttablepost", "sorttablepost");

?>