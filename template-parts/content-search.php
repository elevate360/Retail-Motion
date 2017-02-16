<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Retail_Motion
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('el-row nested small-margin-bottom-medium'); ?>>
	<header class="entry-header el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-align-center">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php retail_motion_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-summary el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-align-center">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

</article><!-- #post-## -->
