<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Retail_Motion
 */

if ( ! function_exists( 'retail_motion_posted_on' ) ) :
function retail_motion_posted_on() {
	$time_string = '<time  class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( '%s', 'post date', 'retail-motion' ),
		$time_string 
	);

	echo '<h3 style="display: inline-block; margin-right: 15px;" class="posted-on small-margin-bottom-small fat small">' . $posted_on . '</h3>'; 

}
endif;

if ( ! function_exists( 'retail_motion_entry_footer' ) ) :
	
	
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function retail_motion_categories_and_tags() {
		
	// Hide category and tag text for pages.
	if ( get_post_type() == 'post' ) {
		
		$categories_list = get_the_category_list( esc_html__( ', ', 'retail-motion' ) );
		if ( $categories_list && retail_motion_categorized_blog() ) {
			printf( '<span class="cat-links small-margin-right-small">' . '<i class="fa fa-file" aria-hidden="true"></i> %1$s</span>', $categories_list ); 
		}

		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'retail-motion' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links small-margin-right-small">' . '<i class="fa fa-tags" aria-hidden="true"></i> %1$s</span>', $tags_list ); 
		}
	}


	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'retail-motion' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			esc_html__( 'Edit %s', 'retail-motion' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>',
		'',
		'button small orange'
	);
}
endif;


/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function retail_motion_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'retail_motion_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'retail_motion_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so retail_motion_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so retail_motion_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in retail_motion_categorized_blog.
 */
function retail_motion_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'retail_motion_categories' );
}
add_action( 'edit_category', 'retail_motion_category_transient_flusher' );
add_action( 'save_post',     'retail_motion_category_transient_flusher' );
