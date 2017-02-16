<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Retail_Motion
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
<?php do_action('el_display_google_tag_manager_head'); ?>
</head>

<body <?php body_class(''); ?>>
<?php do_action('el_display_google_tag_manager_body'); ?>
<?php
//Uncomment this to see the tempate for debugging
//global $template;
//echo $template;

//output our sticky side menu element
do_action('el_display_sticky_side_menu');


?>
<div id="page" class="site">
	
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'retail-motion' ); ?></a>

	<?php
	//Scroll to top button
	$html .= '<div class="scroll-to-top-wrap">';
		$html .= '<i class="icon fa fa-angle-up" aria-hidden="false"></i>';
	$html .= '</div>';
	echo $html;
	?>

	<!--Header, information pulled in on a page-by-page basis -->
	<header id="masthead" class="site-header el-row" role="banner">
		
		<?php
		//Display the main header section, showing a background image, overlay, text, video or other content (defined in customizer + overriden on a page by page basis)
		do_action('el_display_header_hero');
		?>

		
		<!--Nav + Social media-->
		<nav id="site-navigation" class="vertical-nav nav-menu" role="navigation">
			<div class="background-overlay"></div>
			<div class="el-row relative inner small-padding-top-bottom-small">
				<div class="toggle-main-menu el-col-small-12 small-align-right small-margin-bottom-small"><i class="fa fa-times" aria-hidden="true"></i></div>
				<div class="menu-inner el-col-small-12 medium-margin-top-large">
					<?php
					$menu_args = array(
						'theme_location' 	=> 'primary', 
						'menu_id' 			=> 'primary-menu', 
						'container' 		=> false, 
						'link_before'			=> '<div class="link-text">',
						'link_after' 		=> '</div><div class="submenu-toggle"><i class="fa fa-angle-down" aria-hidden="true"></i></div>'
					);
					
					?>
					<?php wp_nav_menu( $menu_args ); ?>
				</div>
				<!--social media-->
				<?php
				//TODO: Theme customizer social media discarded in favour of nav menu the client can update.
				//$el_universal = el_universal::getInstance();
				//$social_media =  $el_universal::el_display_social_media_icons();
				
				//echo $social_media;
				
				//social media menu
				if(is_active_sidebar( 'social-header-sidebar' )){
					echo '<aside class="widget-area " role="complementary">';
						dynamic_sidebar( 'social-header-sidebar' );
					echo '</aside>';
				}
				
				
				?>
				
			</div>
		</nav><!-- #site-navigation -->
		<!--Search Popup -->
		<div class="site-search">
			<div class="background-overlay"></div>
			<div class="el-row inner small-margin small-padding-top-bottom-large">
				
				<div class="toggle-search el-col-small-12 small-align-right small-margin-bottom-small"><i class="fa fa-times" aria-hidden="true"></i></div>
				<div class="el-col-small-12">
					<?php get_search_form(); ?>
				</div>
				
			</div>
			
		</div>
		<!-- Video Popup-->
		<?php if(!empty($header_video_url)){ ?>
		<div class="video-popup">
			<div class="background-overlay"></div>
			
			<div class="el-row inner small-margin small-padding-top-bottom-large">
				<div class="popup-inner">
					<div class="toggle-video-popup el-col-small-12 small-align-right small-margin-bottom-small">
						<i class="icon fa fa-times" aria-hidden="true"></i>
					</div>
					<div class="video-container el-col-small-12">
						<div>
							<iframe src="<?php echo $header_video_url; ?>"></iframe>
						</div>
						
					</div>	
				</div>			
			</div>
		</div>
		<?php } ?>
			
	</header><!-- #masthead -->

	<div id="content" class="site-content">
		