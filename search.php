<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Retail_Motion
 */

get_header(); ?>
<div class="el-row inner">
	<section id="primary" class="el-col-small-12 content-area">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) : ?>

			<div class="el-row nested">
				<header class="page-header el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-align-center small-margin-top-bottom-x-large">
					<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'retail-motion' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				</header><!-- .page-header -->
			</div>

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'search' );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->
</div>
<?php
get_footer();
