<?php
/*
 * Template Name: Page with Sidebar
 * Page Template
 * Used to display a standard page, with the addition of a sidebar
 */

get_header(); ?>

<div class="el-row inner">
	<div id="primary" class="el-col-small-12 el-col-medium-9 content-area small-margin-top-small medium-margin-top-medium">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );
			
				do_action('el_display_post_call_to_action', $post);
				
				do_action('el_display_portfolio_tiles');

				//displays the comments template
				do_action('el_display_comment_template');

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
