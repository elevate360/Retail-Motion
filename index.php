<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Retail_Motion
 */

 
$el_blogs = el_blogs::getInstance();
get_header(); ?>
<div class="el-row">
	<div id="primary" class="content-area el-col-small-12">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) :

			//blog header
			echo '<div class="el-row inner">';
				echo '<header class="page-header el-col-small-8 small-align-center el-col-small-offset-2">';
					echo '<h1 class="page-title big fat">Our Blog</h1>';
				echo '</header>';
			echo '</div>';
			

			//get a listing of our categories as links
			$categories = $el_blogs::get_post_term_links();
			echo $categories;

			//Listing of masonry blogs
			echo '<div class="el-row animation-container inner blog-listing masonry-elements">';
			while ( have_posts() ) : the_post();

				$html = '';
				
				$html .= $el_blogs::get_post_card_html($post->ID);
				
				echo $html;

			endwhile;
			echo '</div>';

			echo '<div class="el-row inner small-align-center small-margin-bottom-small">';
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
