<?php
/*
 * Service Category Taxonomy Page
 * Displayed when viewing a single service taxonomy. Displays all applicable services in a grid
 */


get_header();
$el_services = el_services::getInstance();
$term_object = get_queried_object();
 ?>
<div class="el-row inner">
	<div id="primary" class="content-area el-col-small-12">
		<main id="main" class="site-main el-row nested" role="main">

		<?php
		if ( have_posts() ) : ?>

			<header class="page-header el-col-small-8 small-align-center el-col-small-offset-2">
				<?php
					echo '<h1 class="page-title big fat">' . $term_object->name . '</h1>';
					if(!empty($term_object->description)){
						echo '<p>' . $term_object->description .'</p>';
					}
				?>
			</header><!-- .page-header -->
			
			<?php
			//get term id
			$term_id = $term_object->term_id;

			//display grid listing of services
			do_action('el_display_services_by_category', $term_id);
			
			//display hero elements for term
			do_action('el_display_hero_sections', $term_object);
			
			?>
			
				
			<?php

		else :
			//get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php
get_footer();
