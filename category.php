<?php

/*
 * Category Term Archive
 */

 
$el_blogs = el_blogs::getInstance();
get_header(); ?>
<div class="el-row">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) :

			//term header
			echo '<header class="el-row">';
				echo '<h1 class="el-col-small-12 page-title small-align-center fat">' . single_tag_title('', false) .'</h1>';
			echo '</header>';

			//get a listing of our categories as links
			$categories = $el_blogs::get_post_term_links('category');
			echo $categories;

			/* Start the Loop */
		
			//Listing of masonry blogs
			echo '<div class="el-row animation-container inner blog-listing masonry-elements">';
			while ( have_posts() ) : the_post();

				$html = '';
				
				$html .= $el_blogs::get_post_card_html($post->ID);
				
				echo $html;

			endwhile;

			echo '<div class="el-row inner small-align-center">';
				//the_posts_navigation();
				$post_pagination_args = array(
					'mid_size'		=> 6,
					'prev_text'		=> '',
					'next_text'		=> '',
					'screen_reader_text' => 'Post Navigation'
				);
				the_posts_pagination($post_pagination_args);
			echo '</div>';
				
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php
get_footer();
