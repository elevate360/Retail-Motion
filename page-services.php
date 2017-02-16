<?php
/* Template Name: Services Master Page
 * Used to display an interactive services module (showing service categories + applicable services)
 * - Displays a grid of service cards 
 * 
 */

get_header(); ?>

<div class="el-row inner">
	<div id="primary" class="el-col-small-12 content-area small-margin-top-small medium-margin-top-medium ">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				//get_template_part( 'template-parts/content', 'page' );
			
				//display services categories and applicable services
				do_action('el_display_service_categories_and_services');
			
				//display call to action elements
				do_action('el_display_post_call_to_action', $post);
				
				//optionally show portfolios
				$display_portfolio_cards = get_post_meta($post->ID,'display_portfolio_cards', true);		
				if($display_portfolio_cards == 'yes'){
					do_action('el_display_portfolio_tiles');
				}
				
				//display any hero sections if required
				do_action('el_display_hero_sections', $post);

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
