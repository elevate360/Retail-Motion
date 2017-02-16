<?php
/*
 * Front page
 * Displayed on the front page (homepage)
 */

get_header(); ?>
<div class="el-row inner">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		
		<?php
		if ( have_posts() ){
			
			
			//display main content
			get_template_part( 'template-parts/content', 'home');
			
			//display service categories and services
			do_action('el_display_service_categories_and_services');
			
			//display all related call to action elements
			do_action('el_display_post_call_to_action', $post);
				
			//display a listing a grid of client cards
			do_action('el_display_client_card_listing');
				
			//display recent portfolios
			$display_portfolio_cards = get_post_meta($post->ID,'display_portfolio_cards', true);		
			if($display_portfolio_cards == 'yes'){
				do_action('el_display_portfolio_tiles');
			}
			
			//display testimonial slider
			do_action('el_display_recent_testimonial_slider');
			
			//display any hero sections if required
			do_action('el_display_hero_sections', $post);
			
			//display featured posts
			$display_featured_posts = get_post_meta($post->ID, 'display_featured_posts', true);
			if($display_featured_posts == 'yes'){
				do_action('el_display_featured_posts');
			}
							
			
		}	
		else{
			get_template_part( 'template-parts/content', 'none' );			
		}

		?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<script>
		(function ($){

			var counterImg = '<img src="http://retailmotion.dev.elevate360.com.au/wp-content/uploads/2017/01/RET0008_Web_Charts_03_End.png">';

			var counterGif = '<img src="http://retailmotion.dev.elevate360.com.au/wp-content/uploads/2017/01/RET0008_Web_Charts_01.gif?' + Math.random() + '">';

			var appended = $(counterImg).appendTo('.home-icons');

			var hasPlayed = false;

			// Determine if an element is in the visible viewport
            function isInViewport(element){
               var rect = element.getBoundingClientRect();
               var html = document.documentElement;
               return (
                 rect.top >= 0 &&
                 rect.left >= 0 &&
                 rect.bottom <= (window.innerHeight || html.clientHeight) &&
                 rect.right <= (window.innerWidth || html.clientWidth)
               );
            }

			function playVideo(){
			   // check if video already played
               if(hasPlayed){
                   return;
               } else {
               	
			       if( isInViewport( appended[0] )){
						$(appended).remove();
		
						$('.home-icons').append(counterGif);
						hasPlayed = true;
				   }	
			   }
			}

            window.addEventListener("scroll", function(){ playVideo(); }, false);	
            
		})(jQuery);	
	</script>
</div>
<?php

get_footer();
