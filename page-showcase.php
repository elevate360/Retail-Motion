<?php
/* Template Name: Showcase Page
 * Used to showcase / highlight a page. It's layout will be determined by several settings inside a metabox that are
 * only displayed when this template is selected. It's layout is similar to the single portfolio posts. 
 * - Main content area at the top
 * - Secondary content area + image
 * - Bottom content area (with shortcodes inside)
 * - Link to data summary graph 
 */

get_header(); ?>

<div class="el-row inner">
	<div id="primary" class="el-col-small-12 content-area small-margin-top-small medium-margin-top-medium ">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				do_action('el_display_showcase_main_content', $post);
			
				do_action('el_display_showcase_secondary_content', $post);
				
				do_action('el_displau_showcase_bottom_content', $post);
				
				do_action('el_display_showcase_data_summaries', $post);
						
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
