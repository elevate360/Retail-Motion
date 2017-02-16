<?php
/**
 * Template part for displaying a message that posts cannot be found.
 */

?>

<section class="no-results not-found el-row small-margin-top-bottom-medium">
	<header class="page-header  el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-align-center">
		<h1 class="entry-title big fat"><?php esc_html_e( 'Nothing Found', 'retail-motion' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content  el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-align-center">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'retail-motion' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'retail-motion' ); ?></p>
			<?php
				get_search_form();

		else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'retail-motion' ); ?></p>
			<p><?php esc_html_e( 'Please use the menu in the top right to navigate to one of our other pages', 'retail-motion' ); ?></p>
			<?php
				

		endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
