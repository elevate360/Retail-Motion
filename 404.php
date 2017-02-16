<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Retail_Motion
 */

get_header(); ?>
<div class="el-row inner">
	<div id="primary" class="el-col-small-12 content-area small-margin-top-small medium-margin-top-medium ">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title big small-align-center"><?php esc_html_e( 'Sorry, that resource can\' be found', 'retail-motion' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p class="small-align-center"><?php esc_html_e( 'We couldn\'t find the resource that you were looking for.', 'retail-motion' ); ?></p>
					<p class="small-align-center"><?php esc_html_e( 'You can try to use the search functionality / navigation menu at the top of our website to find what you are looking for.', 'retail-motion' ); ?></p>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php
get_footer();
