<?php
/* Template Name: Estimate Page
 * Template for estimate pages
 * 
 */

get_header(); ?>

<div class="el-row inner">
	<div id="primary" class="el-col-small-12 content-area small-margin-top-small medium-margin-top-medium">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'estimate' );
			
			
				//display call to action elements
				do_action('el_display_post_call_to_action', $post);

				do_action('el_display_portfolio_tiles');

				//displays the comments template
				do_action('el_display_comment_template');

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php get_footer(); ?>