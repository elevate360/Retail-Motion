<?php
/* Template Name: Timeline Page
 * Used to display our timeline module below the main post content
 * 
 */

get_header(); ?>

<div class="el-row inner">
	<div id="primary" class="el-col-small-12 content-area small-margin-top-small medium-margin-top-medium">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );
			
				//display timeline
				do_action('el_display_timeline');
			
				//display call to action elements
				do_action('el_display_post_call_to_action', $post);
				
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

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php get_footer(); ?>
