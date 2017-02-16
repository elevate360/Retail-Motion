<?php
/**
 * Template for displaying a single portfolio item. Based on a multi layout design
 * - Displays summary content (single column if desired)
 * - Displays multi column layout with the brief, insight, solution meta boxes content (as per mockup)
 * - Displays call to actions
 * - Displays gallery slider
 * - Displays applicable testimonials for portfolio
 */

get_header(); ?>
<?php



?>
<div class="el-row inner">
	<div id="primary" class="content-area">
		<main id="main" class="site-main portfolio" role="main">

		<?php
		while ( have_posts() ) : the_post();

			echo '<div class="el-col-small-12 small-align-center">';
				echo '<h1 class="entry-title fat big">' . get_the_title() . '</h1>';
			echo '</div>';

			do_action('el_display_portfolio_summary_content', $post);

			do_action('el_display_portfolio_brief', $post);

			do_action('el_display_portfolio_insight', $post);
			
			do_action('el_display_portfolio_solution', $post);
		
			do_action('el_display_post_call_to_action', $post);
			
			do_action('el_display_portfolio_gallery_slider', $post);
		
			do_action('el_display_portfolio_testimonial_slider', $post);
			
			//optionally show portfolios (Red)
			$display_portfolio_cards = get_post_meta($post->ID,'display_portfolio_cards', true);		
			if($display_portfolio_cards == 'yes'){
				do_action('el_display_portfolio_tiles');
			}

			//optionally display featured posts
			$display_featured_posts = get_post_meta($post->ID, 'display_featured_posts', true);
			if($display_featured_posts == 'yes'){
				do_action('el_display_featured_posts');
			}

			//display post navigation 
			do_action('el_display_post_navigation');
			

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php
get_footer();
