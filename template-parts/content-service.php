<?php
/**
 * Template for dislaying the elements for a single service page
 *
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('el-row nested '); ?>>
	<!-- Hidden for now
	<header class="entry-header el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-align-center">
		<?php the_title( '<h1 class="entry-title big fat">', '</h1>' );  ?>
	</header><!-- .entry-header -->

	<div class="entry-content el-col-small-12 el-col-medium-8 el-col-medium-offset-2">
		<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'retail-motion' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer el-col-small-12 el-col-medium-8 el-col-medium-offset-2">
			<?php
				edit_post_link(
					sprintf(
						/* translators: %s: Name of current post */
						esc_html__( 'Edit %s', 'retail-motion' ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					),
					'<span class="edit-link">',
					'</span>',
					'',
					'button orange small'
				);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-## -->
