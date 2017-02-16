<?php
/**
 * Displays all single posts (blog posts or custom content types if no custom file defined)
 */

get_header(); ?>
<div class="el-row inner">
	<div id="primary" class="el-col-small-12 content-area medium-margin-top-medium">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'service');

			//optionally show portfolios
			$display_portfolio_cards = get_post_meta($post->ID,'display_portfolio_cards', true);		
			if($display_portfolio_cards == 'yes'){
				do_action('el_display_portfolio_tiles');
			}

			//optionally display featured posts
			$display_featured_posts = get_post_meta($post->ID, 'display_featured_posts', true);
			if($display_featured_posts == 'yes'){
				do_action('el_display_featured_posts');
			}

			//displays the comments template
			do_action('el_display_comment_template');

			//displays post navigation
			do_action('el_display_post_navigation');

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php
get_footer();
