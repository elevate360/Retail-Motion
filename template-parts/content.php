<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Retail_Motion
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('el-row nested'); ?>>
	<header class="entry-header el-col-medium-8 el-col-medium-offset-2 small-align-cente">
		<?php
		if ( is_single() ) :
			/* the_title( '<h1 class="entry-title big fat">', '</h1>' ); */
		else :
			/* the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); */
		endif;

		?>
	</header><!-- .entry-header -->

	<div class="entry-content el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-margin-top-medium medium-margin-top-bottom-small">
		<?php
			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'retail-motion' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'retail-motion' ),
				'after'  => '</div>',
			) );
		?>
		<hr class="orange medium"/>
		
		<div class="el-col-small-12 el-col-medium-8 el-col-medium-offset-2">
			<?php echo do_shortcode("[mashshare]"); ?>
		</div>	
			
	</div><!-- .entry-content -->
	<div class="entry-content el-col-small-12 small-align-center el-col-medium-8 el-col-medium-offset-2">
	<?php
	//Entry meta if post
	if ( get_post_type() == 'post' ){
		
		echo '<div class="entry-meta">';
			retail_motion_posted_on();
			retail_motion_categories_and_tags();
		echo '</div>';
		
	}?>
	</div>
	
	

</article><!-- #post-## -->
