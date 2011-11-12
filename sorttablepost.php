<?php
/*
Plugin Name: SortTable Post
Plugin URI: http://mynewsitepreview.com/sorttablepost
Description: Display an index of posts (or a custom post type) in a sortable table using a simple shortcode.
Version: 4.2
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

// Get options from shortcode and generate SortTable
function sorttablepost($args){
	// Options for disabling certain columns
	$opt_nothumbs = $args['nothumb'] . $args['nothumbs']; // Boolean, default 'false'
		if( !current_theme_supports( 'post-thumbnails' ) == TRUE ) {
			$opt_nothumbs = 'true'; // Automatically disable thumbnails if the current theme does not support them
		}
	$opt_notitles = $args['notitle'] . $args['notitles']; // Boolean, default 'false'
	$opt_nodates = $args['nodate'] . $args['nodates']; // Boolean, default 'false'
	$opt_nocats = $args['nocat'] . $args['nocats']; // Boolean, default 'false'
	$opt_notags = $args['notag'] . $args['notags']; // Boolean, default 'false'

	// Option for adding a post excerpt column
	$opt_excerpts = $args['excerpt'] . $args['excerpts']; // Value, if any, will be used as the column heading

	// Option for giving the table a unique ID
	$opt_tableid = $args['id']; // Value, if any, will be used as the ID

	// Option for custom post type
	$valid_post_types = get_post_types('','names');
	$opt_type = $args['type']; // Allow user to specify a Custom Post Type
		if (!in_array($opt_type, $valid_post_types)) { // Validate custom post type
			$opt_type = 'post'; // By default, use Posts
		}

	// Options for custom taxonomies
	$valid_taxonomies = get_taxonomies('','names');
	$opt_cat = $args['cat']; // Allow user to replace 'Category' with a Custom Taxonomy
		if (!in_array($opt_cat, $valid_taxonomies)) { // Validate custom taxonomy ('cat' option)
			$opt_cat = '';
			$catlabel = 'Categories'; // By default, use Categories
		} else {
			$taxa = get_taxonomy($opt_cat);
			$catlabel = $taxa->labels->name;
		}
	$opt_tag = $args['tag']; // Allow user to replace 'Tags' with a Custom Taxonomy
		if (!in_array($opt_tag, $valid_taxonomies)) { // Validate custom taxonomy ('tag' option)
			$opt_tag = '';
			$taglabel = 'Tags'; // By default, use Tags
		}
		else {
			$taxb = get_taxonomy($opt_tag);
			$taglabel = $taxb->labels->name;
		}
	
	// Option for custom fields
	if( $args['meta'] ) {
		$opt_meta = explode(",", $args['meta']); // Allow user to specify Custom Fields in a comma-separated list
	} // Note: We cannot validate custom fields outside the loop, because they are unique to each post

	// Create a counter for the loop, to assign a custom key to each cell in the date column.
	$count = 0; // This hack is used because most date formats will not sort correctly otherwise.

	// Begin recording echos as an output string
	ob_start();

	// Create table header row
	echo '<table class="sortable" id="'. $opt_tableid .'"><tr>';
	if( !$opt_nothumbs == 'true' ) { echo '<th class="sorttable_nosort"></th>'; }
	if( !$opt_notitles == 'true' ) { echo '<th>Title</th>'; }
	if( !$opt_nodates == 'true' ) { echo '<th>Date</th>'; }
	if( !$opt_excerpts == '' ) { echo '<th>' . $opt_excerpts . '</th>'; }
	if( $opt_meta ) : foreach( $opt_meta as $key ) {
		echo '<th>' . $key . '</th>';
	} endif;
	if( !$opt_nocats == 'true' ) { echo '<th>' . $catlabel . '</th>'; }
	if( !$opt_notags == 'true' ) { echo '<th>' . $taglabel . '</th>'; }
	echo '</tr>';
	
	// Begin the loop to generate the table body
	$sorttable = new WP_Query( array( 'posts_per_page' => -1, 'post_type' => $opt_type, 'orderby' => 'date', 'order' => 'DESC' ) );
	if($sorttable->have_posts()) : while($sorttable->have_posts()) : $sorttable->the_post();
	
		// Set variables to use in output strings
		global $post;
		$count++;
		$values = array();
		$link = get_permalink();
		
		if( !$opt_nothumbs == "true" ) { $thumb = get_the_post_thumbnail($post->ID, array(50,50) ); }
		if( !$opt_notitles == "true" ) { $title = get_the_title(); }
		if( !$opt_nodates == "true" ) { $date = get_the_date(); }
		if( !$opt_excerpts == '' ) { $excerpt = get_the_excerpt(); }
		if( $opt_meta ) : foreach( $opt_meta as $key ) {
			$values[] = get_post_meta($post->ID, $key, true);
		} endif;
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
		if( !$opt_nothumbs == "true" ) {
			if ( $thumb ) {
				echo '<td><a href="' . $link . '">' . $thumb . '</a></td>';
			} else {
				echo '<td>No Image</td>';
			}
		}
		if( !$opt_notitles == "true" ) { echo '<td><a href="' . $link . '">' . $title . '</a></td>'; }
		if( !$opt_nodates == "true" ) { echo '<td sorttable_customkey="' . $count . '">' . $date . '</td>'; }
		if( !$opt_excerpts == '' ) { echo '<td>' . $excerpt . '</td>'; }
		if( $opt_meta ) : foreach( $values as $value ) {
			$value = st_findlinks($value);
			echo '<td>' . $value . '</td>';
		} endif;
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
add_shortcode("sorttablepost", "sorttablepost");

function st_findlinks($text) {
        $email_pattern = "/[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}/i";
        $url_pattern = "/((http|https|ftp|sftp):\/\/)[a-z0-9\-\._]+\/?[a-z0-9_\.\-\?\+\/~=&#;,]*[a-z0-9\/]{1}/si";
        $www_pattern = "/(www)[a-z0-9\-\._]+\/?[a-z0-9_\.\-\?\+\/~=&#;,]*[a-z0-9\/]{1}/si";
 
        // First, check if the string contains an email address...
        if( preg_match( $email_pattern, $text, $email ) ) {
                $replacement = '<a href="mailto:' . $email[0]. '">' . $email[0] . '</a> ';
                $text = preg_replace($email_pattern, $replacement, $text);
        }
        // Next, check if the string contains a URL beginning with http://, https://, ftp://, or sftp://
        // ...and if not, check for a plain old www address
        if( preg_match( $url_pattern, $text, $url ) ) {
                $replacement = '<a href="' . $url[0]. '">' . $url[0] . '</a> ';
                $text = preg_replace($url_pattern, $replacement, $text);
        } elseif( preg_match( $www_pattern, $text, $www ) ) {
                $replacement = '<a href="http://' . $www[0]. '">' . $www[0] . '</a> ';
                $text = preg_replace($www_pattern, $replacement, $text);
        }
 
        return $text; 
}
?>