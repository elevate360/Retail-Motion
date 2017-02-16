<?php
/*
 * Retail Motion Functions
 * Main entry point into the retail motion website. Handels custom functionality.
 * - Loads all other modules for the site
 * 
 */ 
 
 
 
/*
 * Theme
 * Primary starting class, contains all project related functionality, loads other functions
 */
 class el_project{
 	
	private static $instance = null;
	
	public function __construct(){
		
		 include(get_stylesheet_directory() . '/inc/modules/universal/el_content_type.php'); 
		 
		 include(get_stylesheet_directory() . '/inc/modules/hero_sections/hero_sections.php');
		 include(get_stylesheet_directory() . '/inc/modules/services/el_services.php'); 
		 
		 include(get_stylesheet_directory() . '/inc/modules/universal/el_universal.php'); 
		 include(get_stylesheet_directory() . '/inc/modules/testimonials/el_testimonials.php');
		
		 include(get_stylesheet_directory() . '/inc/modules/blogs/el_blogs.php'); 
		 include(get_stylesheet_directory() . '/inc/modules/timeline/el_timeline.php'); 
		 include(get_stylesheet_directory() . '/inc/modules/portfolios/el_portfolios.php');
		 include(get_stylesheet_directory() . '/inc/modules/data_summary/data_summary.php');
		 include(get_stylesheet_directory() . '/inc/modules/call_to_action/el_call_to_action.php');
		 include(get_stylesheet_directory() . '/inc/modules/clients/el_clients.php');
		 include(get_stylesheet_directory() . '/inc/modules/sticky-side-menu/el_sticky_side_menu.php');
		 
		 
	
	}

	public static function getInstance(){
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
 }
 $el_project = el_project::getInstance();
 
 
 
 
 
 
 
 
 
 
 
 


/* Sets up theme defaults and registers support for various WordPress features.
* Note that this function is hooked into the after_setup_theme hook, which runs before the init hook. (post thumbnails)*/
if ( ! function_exists( 'retail_motion_setup' ) ){
	
	function retail_motion_setup() {
		
		//text domain
		load_theme_textdomain( 'retail-motion', get_template_directory() . '/languages' );
	
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
	
		//theme supports for the <title> element
		add_theme_support( 'title-tag' );
	
		//Post thumbnail support
		add_theme_support( 'post-thumbnails' );
	
		//Register nav menus
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'retail-motion' ),
			'footer'	=> esc_html__('Footer', 'retail-motion')
		) );
	
		//HTML5 theme support
		add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption') );
	
		// Set up the WordPress core custom background feature.
		add_theme_support( 
			'custom-background', 
			apply_filters( 'retail_motion_custom_background_args', array('default-color' => 'ffffff','default-image' => '',) ) 
		);
	}
}
add_action( 'after_setup_theme', 'retail_motion_setup' );


//Enqueue scripts and styles
function retail_motion_scripts() {
	wp_enqueue_style( 'retail-motion-style', get_stylesheet_uri() );
	wp_enqueue_script( 'retail-motion-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );
	wp_enqueue_script( 'retail-motion-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	//comment reply on on singular post pages
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'retail_motion_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
