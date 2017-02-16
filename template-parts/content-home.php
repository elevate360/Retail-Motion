<?php
/**
 * Content displayed on the homepage, before all other features and functions
 * Used to output the post content (minus the header)
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('el-row nested'); ?>>

	<div class="entry-content el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-margin-top-medium ">
		<?php
		global $post;
		echo apply_filters('the_content', $post->post_content);
		?>
	</div><!-- .entry-content -->
	

</article><!-- #post-## -->
