<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Retail_Motion
 */

get_header(); ?>
<div class="el-row inner">
	<div id="primary" class="el-col-small-12 content-area">
		<main id="main" class="site-main animation-container" role="main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', get_post_format() );

			//display call  to action elements
			do_action('el_display_post_call_to_action', $post);

			
			
			//displays the comments template
			do_action('el_display_comment_template');
			
			//displays post navigation
			do_action('el_display_post_navigation');
			
			?>
			
			<?php
		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php
get_footer();
