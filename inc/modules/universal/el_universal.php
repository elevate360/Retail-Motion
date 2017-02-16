<?php
/*
 * Universal theme elements. Functionality shared between modules is handled here
 * - Adds Custom Header metabox to several post types (To allow output of an image, logo, text, subtitle etc)
 * - Adds Call to Action metabox (Letting pages, posts, ccts output applicable call to action elements)
 * - Adds SVG support for image uploads 
 * - Registers widget areas for the footer (3 columns)
 * - Outputs the universal header hero used across all pages
 * - Outputs Google Tag Manager in the head
 */

 
 class el_universal{
 
 	private static $instance = null;

 	public function __construct(){
 	
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts_and_styles'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts_and_styles'));
		add_filter('upload_mimes', array($this, 'add_file_types_to_uploads'));
		add_action('edit_form_after_title', array($this, 'move_advanced_metaboxes_above_editor')); //output 'advanced' metaboxes just below the title
		add_action('widgets_init', array($this, 'register_widget_areas'));
		add_action('after_setup_theme', array($this, 'set_theme_content_width'));
		add_action('wp_ajax_getattachmenturl', array($this, 'get_attachment_url_media_library')); //custom ajax function to be used for the media library
		add_action('el_display_google_tag_manager_head', array($this, 'display_google_tag_manager_head')); //head tag manager
		add_action('el_display_google_tag_manager_body', array($this, 'display_google_tag_manager_body')); //body tag manager fallback
		add_action('wp_head', array($this, 'display_favicons'));
		
		
		//display hooks
		add_action('el_display_header_hero', array($this, 'el_display_header_hero')); //dsiplays header hero on page
		add_action('header_signature', array($this, 'display_header_signature')); 
		
		//add metaboxes
		add_action('add_meta_boxes', array($this, 'register_header_metabox')); //register header metabox
		add_action('add_meta_boxes', array($this, 'register_call_to_action_metabox')); //call to action metabox
		add_action('add_meta_boxes', array($this, 'register_hero_section_metabox')); //hero section metabox
		//add_action('service_category_add_form_fields', array($this, 'register_hero_section_to_terms')); //hero section fields to be registered on terms
		
		add_action('add_meta_boxes', array($this, 'register_showcase_page_meta_box')); //showcase page template metabox
		add_action('add_meta_boxes', array($this, 'register_show_hide_posts_meta_box')); //show or hide featured posts on a page by page basis
		add_action('add_meta_boxes', array($this, 'register_show_hide_portfolios_meta_box')); //show or hide the portfolio cards on a page by page basis
		
		//saving 
		add_action('save_post', array($this, 'save_post_header_metabox')); //saves metadata for header metabox
		add_action('save_post', array($this, 'save_post_call_to_action_metabox')); //saves metadata for call to action metabox
		add_action('save_post', array($this, 'save_post_hero_section_metabox')); //saves metadata for the hero section metabox
		add_action('save_post', array($this, 'save_post_showcase_metabox')); //saves metadata for the showcase metabox
		add_action('save_post', array($this, 'save_post_show_hide_posts_metabox')); //saves metadata to determine if featured blogs will be dsiplayed on this post or not
		add_action('save_post', array($this, 'save_post_show_hide_portfolio_metabox')); //saves metadata to determine if the latest portfolios will be displayed for this post
	
		//universal shortcodes
		add_action('init', array($this, 'register_shortcodes'),10, 1);
	
		//universal action hooks
		add_action('el_display_post_navigation', array($this, 'display_post_navigation'));
		add_action('el_display_social_media_icons', array($this,'el_display_social_media_icons' ));
		add_action('el_display_comment_template', array($this, 'el_display_comment_template'));
		
		//action hooks for showcase template
		add_action('el_display_showcase_main_content', array($this, 'el_display_showcase_main_content'), 10, 1);
		add_action('el_display_showcase_secondary_content', array($this, 'el_display_showcase_secondary_content'), 10, 1);
		add_action('el_displau_showcase_bottom_content', array($this, 'el_displau_showcase_bottom_content'), 10, 1); //bottom content (sits negatively margined into the secondary content area)
		add_action('el_display_showcase_data_summaries', array($this, 'el_display_showcase_data_summaries'), 10, 1); //Data summaries for this page
 	}

	//displays favicons
	public function display_favicons(){
		
		$html = '';
		$directory = get_stylesheet_directory_uri();
		
		$html .= '<link rel="apple-touch-icon" sizes="57x57" href="' . $directory .'/img/favicons/apple-touch-icon-57x57.png">';
		$html .= '<link rel="apple-touch-icon" sizes="60x60" href="' . $directory .'/img/favicons/apple-touch-icon-60x60.png">';
		$html .= '<link rel="apple-touch-icon" sizes="72x72" href="' . $directory .'/img/favicons/apple-touch-icon-72x72.png">';
		$html .= '<link rel="apple-touch-icon" sizes="76x76" href="' . $directory .'/img/favicons/apple-touch-icon-76x76.png">';
		$html .= '<link rel="apple-touch-icon" sizes="114x114" href="' . $directory .'/img/favicons/apple-touch-icon-114x114.png">';
		$html .= '<link rel="apple-touch-icon" sizes="120x120" href="' . $directory .'/img/favicons/apple-touch-icon-120x120.png">';
		$html .= '<link rel="apple-touch-icon" sizes="144x144" href="' . $directory .'/img/favicons/apple-touch-icon-144x144.png">';
		$html .= '<link rel="apple-touch-icon" sizes="152x152" href="' . $directory .'/img/favicons/apple-touch-icon-152x152.png">';
		$html .= '<link rel="apple-touch-icon" sizes="180x180" href="' . $directory .'/img/favicons/apple-touch-icon-180x180.png">';
		$html .= '<link rel="icon" type="image/png" href="' . $directory .'/img/favicons/favicon-32x32.png" sizes="32x32">';
		$html .= '<link rel="icon" type="image/png" href="' . $directory .'/img/favicons/favicon-194x194.png" sizes="194x194">';
		$html .= '<link rel="icon" type="image/png" href="' . $directory .'/img/favicons/android-chrome-192x192.png" sizes="192x192">';
		$html .= '<link rel="icon" type="image/png" href="' . $directory .'/img/favicons/favicon-16x16.png" sizes="16x16">';
		$html .= '<link rel="manifest" href="' . $directory .'/img/favicons/manifest.json">';
		$html .= '<link rel="mask-icon" href="' . $directory .'/img/favicons/safari-pinned-tab.svg" color="#febd55">';
		$html .= '<link rel="shortcut icon" href="' . $directory .'/img/favicons/favicon.ico">';
		$html .= '<meta name="msapplication-TileColor" content="#febd55">';
		$html .= '<meta name="msapplication-TileImage" content="' . $directory .'/img/favicons/mstile-144x144.png">';
		$html .= '<meta name="msapplication-config" content="' . $directory .'/img/favicons/browserconfig.xml">';
		$html .= '<meta name="theme-color" content="#febd55">';
		
		echo $html;
	}

	//Embrace the DINO
	public function display_header_signature(){
		?>
		<!--You have been visited by the Elevate Code Dinosaur!
		                                                   -- __
		                                                 ~ (@)  ~~~---_
		                                               {     `-_~,,,,,,)
		                                               {    (_  ',
		                                                ~    . = _',
		                                                 ~    '.  =-'
		                                                   ~     :
		.                                                -~     ('');
		'.                                         --~        \  \ ;
		  '.-_                                   -~            \  \;      _-=,.
		     -~- _                          -~                 {  '---- _'-=,.
		       ~- _~-  _              _ -~                     ~---------=,.`
		            ~-  ~~-----~~~~~~       .+++~~~~~~~~-__   /
		                ~-   __            {   -     +   }   /
		                         ~- ______{_    _ -=\ / /_ ~
		                             :      ~--~    // /         ..-
		                             :   / /      // /         ((
		                             :  / /      {   `-------,. ))
		                             :   /        ''=--------. }o
		                .=._________,'  )                     ))
		                )  _________ -''                     ~~
		               / /  _ _
		              (_.-.'O'-'.
		
		*/
		-->
		
		<?php
	}

	//displays the google tag manager code in the head
	public function display_google_tag_manager_head(){
		?>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-5LPW3KG');</script>
		<!-- End Google Tag Manager -->
		<?php
	}
	
	/**
	 * Display tag manager in the body
	 */
	public function display_google_tag_manager_body(){
		?>
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5LPW3KG" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
		<?php
	}

	//displays the header hero
	public function el_display_header_hero(){
		
		$html = '';
		$html = $this->get_header_hero();
		echo $html;
	}
	
	//get header hero, either based on default theme settings or individual page overrides
	public function get_header_hero(){
			
		global $post;
		
		//get the current object, determines how we handle the hero section
		$object = get_queried_object();
		
		$html = '';
				
		//if we're on a single post
		if( is_a($object, 'WP_Post')){
			
			$post_id = null; 
			
			//is on the blog index page, fetch page ID
			if(is_home() == true){
				$post_id = get_option( 'page_for_posts' ); 
			}else{
				$post_id = $object->ID;			
			}
			
			//determine if this post wants to override the header, else use theme universal header
			$header_enabled = get_post_meta($post_id, 'header_enabled', true);
		
			if($header_enabled == 'yes'){
				$html .= $this->get_header_html_for_page($post_id);	
			}else{
				$html .= $this->get_header_default_html();
			}
			
		}//use defaults for the header (theme mods)
		else{
			$html .= $this->get_header_default_html();
		}

		return $html;
	}


	//gets the HTML markup for the header for a single page, this is used by single pages that want to override the 
	//default header set in theme mods
	public function get_header_html_for_page($post_id){
			
		$html = '';
		
		$header_enabled = get_post_meta($post_id, 'header_enabled', true);
		$header_background_image = get_post_meta($post_id, 'header_background_image', true);
		$header_background_color = get_post_meta($post_id, 'header_background_color', true);
		$header_overlay_color = get_post_meta($post_id, 'header_overlay_color', true);
		$header_overlay_opacity = get_post_meta($post_id,'header_overlay_opacity', true);
		$header_text_color = get_post_meta($post_id, 'header_text_color', true);
		$header_logo = get_post_meta($post_id, 'header_logo', true);
		$header_title = get_post_meta($post_id, 'header_title', true);
		$header_subtitle = get_post_meta($post_id, 'header_subtitle', true);
		$header_video_url = get_post_meta($post_id, 'header_video_url', true);
		
		//if we have a background-color
		if(!empty($header_background_color)){
			$html .= '<div class="header-background-color" style="background-color:' . $header_background_color . ';"></div>';
		}
		
		//if we have a background-image set
		if(!empty($header_background_image)){
			$header_background_image_url = wp_get_attachment_image_src($header_background_image, 'large', false)[0];
			$html .=  '<div class="header-background-image background-image" style="background-image: url(' . $header_background_image_url . ');"></div>';	
		}
		
		//if we have an overlay color
		if(!empty($header_overlay_color)){
			
			//display based on opacity
			if($header_overlay_opacity){
				$html .= '<div class="header-overlay-color" style="background-color: ' . $header_overlay_color . '; opacity: ' . $header_overlay_opacity . ';"></div>';
			}else{
				$html .= '<div class="header-overlay-color" style="background-color:' . $header_overlay_color . ';"></div>';
			}	
		} 

		//if we have a video
		if(!empty($header_video_url)){
			
			$html .= '<div class="video-popup">';
				$html .= '<div class="background-overlay"></div>';
				$html .= '<div class="video-container">';
					$html .= '<div class="toggle-video-popup"><i class="icon fa fa-times" aria-hidden="true"></i></div>';
					$html .= '<iframe seamless="seamless" src="' . $header_video_url . '"></iframe>';
				$html .= '</div>';
			$html .= '</div>';
			
		}
		
		
				
		$style = '';
		if(!empty($header_text_color)){
			$style = 'color: ' . $header_text_color . ';';
		}

		$html .= '<div class="header-inner el-row inner small-padding-top-bottom-small medium-padding-top-bottom-medium medium-padding-bottom-large" style="' . $style . ';">';
			//Logo
			$html .= '<div class="logo-wrap el-col-small-6">';
				$html .= '<a href="' . esc_url( home_url( '/' ) ) . '">';
					$html .= '<img class="logo" src="' .  get_stylesheet_directory_uri() . '/img/retail_motion_logo_white.png" alt="Retail Motion"/>';
				$html .= '</a>';
			$html .= '</div>';
			//Menu + search
			$html .= '<div class="action-wrap el-col-small-6 small-align-right">';
				$html .= '<div class="menu-toggle inline-block small-margin-right-small" data-menu-id="primary-menu">';
					$html .= '<i class="toggle-main-menu icon fa fa-bars fa-2x" aria-hidden="true"></i>';
				$html .= '</div>';
				$html .= '<div class="search-toggle inline-block">';
					$html .= '<i class="toggle-search icon fa fa-search fa-2x" aria-hidden="false"></i>';
				$html .= '</div>';
			$html .= '</div>';
			
			
			//Main content block
			$html .=' <div class="content clear el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-align-center medium-margin-top-large medium-margin-bottom-large">';
				if(!empty($header_title)){
					$html .= '<h1 class="title uppercase big fat">' . $header_title . '</h1>';
				}
				if(!empty($header_logo)){
					$post_attachment = get_post($header_logo);
					$image_url = '';
					if($post_attachment->post_mime_type == 'image/svg+xml'){
						$image_url = $post_attachment->guid;
					}else{
						$image_url = wp_get_attachment_image_src($header_logo, 'medium', false)[0];
					}
					
					$html .= '<img class="header-logo small-margin-top-botom-large" src="' . $image_url . '"/>';
					
				}
				
				//Video functionality
				if(!empty($header_video_url)){
					$html .= '<div class="video-button-wrap">';
						$html .= '<div class="video-title">Watch Video</div>';
						$html .= '<div class="video-button"><i class="icon fa fa-play-circle" aria-hidden="true"></i></div>';
					$html .= '</div>';
				}
				if(!empty($header_subtitle)){
					$html .= '<h2 class="subtitle small-margin-top-bottom-large small-margin-top-large fat">' . $header_subtitle . '</h2>';
				}
				
				
				
			$html .= '</div>';
			$html .= '<div class="fold-arrow align-center">';
				$html .= '<i class="fold-icon fa fa-angle-down" aria-hidden="true"></i>';
			$html .= '</div>';
		$html .= '</div>';
		
		
		
		return $html;
		
	}


	//gets the default HTML markup for the header, used on archive pages, terms (categories, tags) and other
	//universal areas. Ensures we always have a header that looks good. 
	public function get_header_default_html(){
			
		$html = '';
		
		//collect theme mod values
		$retail_header_background_image = get_theme_mod('retail_header_background_image');
		$retail_header_background_color = get_theme_mod('retail_header_background_color');
		$retail_header_text_color = get_theme_mod('retail_header_text_color');
		$retail_header_overlay_color = get_theme_mod('retail_header_overlay_color');
		$retail_header_overlay_opacity = get_theme_mod('retail_header_overlay_opacity');		
		$retail_header_logo = get_theme_mod('retail_header_logo');
		$retail_header_title = get_theme_mod('retail_header_title');
		$retail_header_subtitle = get_theme_mod('retail_header_subtitle');
		$retail_header_video_url = get_theme_mod('retail_header_video_url');
	
		
		//if we have a background-color
		if(!empty($retail_header_background_color)){
			$html .= '<div class="header-background-color" style="background-color:' . $retail_header_background_color . ';"></div>';
		}
		
		//if we have a background-image set
		if(!empty($retail_header_background_image)){	
			$html .=  '<div class="header-background-image background-image" style="background-image: url(' . $retail_header_background_image . ');"></div>';	
		}
		
		//if we have an overlay color
		if(!empty($retail_header_overlay_color)){
			//display based on opacity
			if($retail_header_overlay_opacity){
				$html .= '<div class="header-overlay-color" style="background-color: ' . $retail_header_overlay_color . '; opacity: ' . $retail_header_overlay_opacity . ';"></div>';
			}else{
				$html .= '<div class="header-overlay-color" style="background-color:' . $retail_header_overlay_color . ';"></div>';
			}
		} 
		
		
		//if we have a video
		if(!empty($retail_header_video_url)){
			
			$html .= '<div class="video-popup">';
				$html .= '<div class="background-overlay"></div>';
				$html .= '<div class="video-container">';
					$html .= '<div class="toggle-video-popup"><i class="icon fa fa-times" aria-hidden="true"></i></div>';
					$html .= '<iframe seamless="seamless" src="' . $retail_header_video_url . '"></iframe>';
				$html .= '</div>';
			$html .= '</div>';
			
		}
		
		
		
		$style = '';
		if(!empty($retail_header_text_color)){
			$style = 'color: ' . $retail_header_text_color . ';';
		}

		$html .= '<div class="header-inner el-row inner small-padding-top-bottom-small medium-padding-top-bottom-medium medium-padding-bottom-large" style="' . $style . ';">';
			//Logo
			$html .= '<div class="logo-wrap el-col-small-6">';
				$html .= '<a href="' . esc_url( home_url( '/' ) ) . '">';
					$html .= '<img class="logo" src="' .  get_stylesheet_directory_uri() . '/img/retail_motion_logo_white.png" alt="Retail Motion"/>';
				$html .= '</a>';
			$html .= '</div>';
			//Menu + search
			$html .= '<div class="action-wrap el-col-small-6 small-align-right">';
				$html .= '<div class="menu-toggle inline-block small-margin-right-small" data-menu-id="primary-menu">';
					$html .= '<i class="toggle-main-menu icon fa fa-bars fa-2x" aria-hidden="true"></i>';
				$html .= '</div>';
				$html .= '<div class="search-toggle inline-block">';
					$html .= '<i class="toggle-search icon fa fa-search fa-2x" aria-hidden="false"></i>';
				$html .= '</div>';
			$html .= '</div>';
			
			
			//Main content block
			$html .=' <div class="content clear el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-align-center medium-margin-top-large medium-margin-bottom-large">';
				if(!empty($retail_header_title)){
					$html .= '<h1 class="title uppercase big fat">' . $retail_header_title . '</h1>';
				}
				if(!empty($retail_header_logo)){
					$html .= '<img class="header-logo small-margin-top-botom-large" src="' . $retail_header_logo . '"/>';
					
				}
				
				//Video functionality
				if(!empty($retail_header_video_url)){
					
					$html .= '<div class="video-button-wrap">';
						$html .= '<div class="video-title">Watch Video</div>';
						$html .= '<div class="video-button"><i class="icon fa fa-play-circle" aria-hidden="true"></i></div>';
					$html .= '</div>';
			
				}
				if(!empty($retail_header_subtitle)){
					$html .= '<h2 class="subtitle small-margin-top-bottom-large small-margin-top-large fat">' . $retail_header_subtitle . '</h2>';
				}
				
			$html .= '</div>';
			$html .= '<div class="fold-arrow align-center">';
				$html .= '<i class="fold-icon fa fa-angle-down" aria-hidden="true"></i>';
			$html .= '</div>';
		$html .= '</div>';

		return $html;
		
	}


	//Function used to return the URL to a media resource 
	public function get_attachment_url_media_library(){
		
		$url = '';
		$attachmentID = isset($_REQUEST['attachmentID']) ? $_REQUEST['attachmentID'] : '';
		if($attachmentID){
			$url = wp_get_attachment_url($attachmentID);
		}
		
		echo $url;
		
		die();
	}

	//registers universal shortcodes for use
	public function register_shortcodes(){
		add_shortcode('el_row', array($this, 'render_shortcodes'));
		add_shortcode('el_col', array($this, 'render_shortcodes'));
		add_shortcode('el_showcase_block', array($this, 'render_shortcodes'));
	}
	
	//output for the shortcodes
	public function render_shortcodes($atts, $content = '', $tag){
			
		$html = '';
		
		//Row shortcode - [el_row][/el_row]
		if($tag == 'el_row'){
			$html .= '<div class="el-row">';
				$html .= do_shortcode(trim($content)); 
			$html .= '</div>';
		}

		//Column shortcode - [el_col small="12" medium="6" large="4"][/el_col]
		else if($tag == 'el_col'){
			//column arguments
			$args = shortcode_atts(array(
				'small'	 => '12',
				'medium' => '',
				'large'	 => ''
			), $atts, $tag);
			
			//build output
			$classes = 'el-col-small-' . $args['small'];
			$classes .= (!empty($args['medium'])) ? ' el-col-medium-' . $args['medium'] : '';
			$classes .= (!empty($args['large'])) ? ' el-col-large-' . $args['large'] : '';
				
			$html .= '<div class="' . $classes .'">';
				$html .= do_shortcode(trim($content)); 
			$html .= '</div>';
		}
		
		//Showcase block (white background section with title, content and border)
		//[el_showcase_section title="" content=""][/el_showcase_section]
		else if($tag == 'el_showcase_block'){
			
			$args = shortcode_atts(array(
				'title'		=> '',
				'content'	=> ''
			), $atts, $tag);
			
			$html .= '<div class="showcase-block">';
				if(!empty($args['title'])){
					$html .= '<h3 class="title fat small-align-center">' . $args['title'] . '</h3>';
				}
				if(!empty($args['content'])){
					$html .= '<div class="content small-margin-bottom-medium">' . esc_html($args['content']) . '</div>';
				}
			$html .= '</div>';
		}
		
		
		return $html;
	}

	//displays the main content for a page using the showcase template
	public static function el_display_showcase_main_content($post){
			
		$instance = self::getInstance();
		$html .= '';
		
		
		$showcase_main_content = get_post_meta($post->ID, 'showcase_main_content', true);
		
		//output
		$html .= '<article class="el-row animation-container">';
			$html .= '<div class="showcase-main-content el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-align-center small-margin-bottom-medium ">';
				if(!empty($showcase_main_content)){
					$html .= '<div class="content">' . apply_filters('the_content', $showcase_main_content) . '</div>';
				}
			$html .= '</div>';
		$html .= '</article>';
		
		
		echo $html;
		
	}
	//displays the secondary content for a page using showcase template (left image + right content block)
	public static function el_display_showcase_secondary_content($post){
		
		$instance = self::getInstance();
		$html .= '';
		
		$showcase_right_image = get_post_meta($post->ID, 'showcase_right_image', true);
		$showcase_left_content = get_post_meta($post->ID, 'showcase_left_content', true);
		$image_url = '';
		if(!empty($showcase_right_image)){
			$post_attachment = get_post($showcase_right_image);
			if($post_attachment->post_mime_type == 'image/svg+xml'){
				$image_url = $post_attachment->guid;
			}else{
				$image_url = wp_get_attachment_image_src($showcase_right_image, 'large', false)[0];
			}
		}
		
		
		
		//$style = (!empty($image_url)) ? 'background-image: url(' . $image_url . ');' : '';
		$style = '';
		$html .= '<article class="el-row animation-container">';
		
			$html .= '<div class="showcase-secondary-content el-col-small-12  small-margin-top-medium" style="' . $style .'">';
				
				$html .= '<div class="content el-row nested">';
					
					//left content
					if(!empty($showcase_left_content)){
						$html .= '<div class="el-col-small-12 el-col-medium-6 el-col-large-4">';
							$html .= apply_filters('the_content', $showcase_left_content);
						$html .= '</div>';
					}
					
					//right image
					if(!empty($showcase_right_image) && !empty($image_url)){
						$html .= '<div class="el-col-small-12 el-col-medium-6 el-col-large-offset-2">';
							$html .= '<img src="' . $image_url . '"/>';
						$html .= '</div>';
					}
					
					
					
				$html .= '</div>';
		
			$html .= '</div>';
		
		$html .= '</article>';
		
		echo $html;
		
	}

	//displays the bottom content section for the showcase template page
	public static function el_displau_showcase_bottom_content($post){
		
		$instance = self::getInstance();
		$html .= '';
		
		$showcase_bottom_content = get_post_meta($post->ID, 'showcase_bottom_content', true);
		
		if(!empty($showcase_bottom_content)){
			$html .= '<article class="el-row animation-container">';
				$html .= '<div class="showcase-bottom-content el-col-small-12 small-margin-bottom-medium">';
					$html .= apply_filters('the_content', $showcase_bottom_content);
				$html .= '</div>';
			$html .= '</article>';
		}

		echo $html;
	}


	//displays the data summaries associated with this showcase page
	public static function el_display_showcase_data_summaries($post){
		
		$instance = self::getInstance();
		$html .= '';
		
		$showcase_data_summaries = get_post_meta($post->ID,'showcase_data_summaries', true);
		if(!empty($showcase_data_summaries)){
			//get our summary data class
			$el_data_summary = el_data_summary::getInstance();
			//get summaries
			$showcase_data_summaries = json_decode($showcase_data_summaries);
			
			foreach($showcase_data_summaries as $summary_id){
				$html .= $el_data_summary::get_data_summary_html($summary_id);
			}
		}

		echo $html;
		
	}
	
	
	
	//displays the comments template on single items, wrapped in the grid style
	public static function el_display_comment_template(){
			
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ){
			//display post navigation 
			echo '<div class="el-row animation-container">';
				echo '<div class="el-col-small-12 el-col-medium-8 el-col-medium-offset-2">';
					comments_template();
				echo '</div>';
			echo '</div>';
			
		}

	}
	
	//displays social media icons, simple
	public static function el_display_social_media_icons(){
		$instance = self::getInstance();
		$html = '';
		
		$html .= $instance::get_social_media_icons();
		
		echo $html;
	}
	
	//generates markup for social media icons
	public static function get_social_media_icons(){
		$instance = self::getInstance();
		$html = '';
		
		$facebook_url = get_theme_mod('retail_social_facebook');
		$twitter_url = get_theme_mod('retail_social_twitter');
		$instagram_url = get_theme_mod('retail_social_instagram');
		$youtube_url = get_theme_mod('retail_social_youtube');
		$linkedin_url = get_theme_mod('retail_social_linkedin');
		$googleplus_url = get_theme_mod('retail_social_googleplus');
		$display_blog_link = get_theme_mod('retail_social_show_blog_link');
		
		$html .= '<div class="el-row social-media">';
			$html .= '<div class="el-col-small-12">';
				//facebook icon
				if(!empty($facebook_url)){
					$html .= '<div class="inline-block small-margin-right-small">';
						$html .= '<a href="' . $facebook_url . '" target="_blank" title="Join us on Facebook">';
							$html .= '<i class="fa fa-facebook" aria-hidden="true"></i>';
						$html .= '</a>';
					$html .= '</div>';
				}
				//Instagram icon
				if(!empty($instagram_url)){
					$html .= '<div class="inline-block small-margin-right-small">';
						$html .= '<a href="' . $instagram_url . '" target="_blank" title="View us on Instgram">';
							$html .= '<i class="fa fa-instagram" aria-hidden="true"></i>';
						$html .= '</a>';
					$html .= '</div>';
				}
				//Twitter icon
				if(!empty($twitter_url)){
					$html .= '<div class="inline-block small-margin-right-small">';
						$html .= '<a href="' . $twitter_url . '" target="_blank" title="Follow our Tweets">';
							$html .= '<i class="fa fa-twitter" aria-hidden="true"></i>';
						$html .= '</a>';
					$html .= '</div>';
				}
				//youtube
				if(!empty($youtube_url)){
					$html .= '<div class="inline-block small-margin-right-small">';
						$html .= '<a href="' . $youtube_url . '" target="_blank" title="Watch our Channel on YouTube">';
							$html .= '<i class="fa fa-youtube" aria-hidden="true"></i>';
						$html .= '</a>';
					$html .= '</div>';
				}
				//Google plus
				if(!empty($googleplus_url)){
					$html .= '<div class="inline-block small-margin-right-small">';
						$html .= '<a href="' . $googleplus_url . '" target="_blank" title="Join Our Community">';
							$html .= '<i class="fa fa-google-plus" aria-hidden="true"></i>';
						$html .= '</a>';
					$html .= '</div>';
				}
				//Linkin
				if(!empty($linkedin_url)){
					$html .= '<div class="inline-block small-margin-right-small">';
						$html .= '<a href="' . $linkedin_url . '" target="_blank" title="Join Us">';
							$html .= '<i class="fa fa-linkedin" aria-hidden="true"></i>';
						$html .= '</a>';
					$html .= '</div>';
				}
				
				if($display_blog_link == true){
					$blog_page_id = get_option( 'page_for_posts' );
					if($blog_page_id){
						$blog_link = get_permalink($blog_page_id);
						$html .= '<div class="inline-block small-margin-right-small">';
							$html .= '<a href="' . $blog_link . '" title="Visit Our Blog">';
								$html .= '<i class="fa fa-comments" aria-hidden="true"></i>';
							$html .= '</a>';
						$html .= '</div>';
					}		
					
				}

			$html .= '</div>';
			
			
		$html .= '</div>';
		
		echo $html;
	}
	
	//registeres widget areas for the theme
	public function register_widget_areas(){
		
		
		//Page with sidebar template	
		register_sidebar( array(
			'name'          => esc_html__( 'Standard Page Sidebar', 'retail-motion' ),
			'id'            => 'widget-page-sidebar',
			'description'   => esc_html__( 'Displayed when selecting the \'Page with Sidebar\' page template' , 'retail-motion' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title small-margin-top-none small-margin-bottom-small">',
			'after_title'   => '</h3><hr class="orange medium"/>',
		) );
		
		//social media sidebar (used only in the header to display social media menu)
		register_sidebar( array(
			'name'          => esc_html__( 'Header Social Media Sidebar', 'retail-motion' ),
			'id'            => 'social-header-sidebar',
			'description'   => esc_html__( 'used to display the custom social media icons in the header menu' , 'retail-motion' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title small-margin-top-none small-margin-bottom-small">',
			'after_title'   => '</h3><hr class="orange medium"/>',
		) );
		
		//Footer 1 widget
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget 1', 'retail-motion' ),
			'id'            => 'widget-footer-1',
			'description'   => esc_html__( 'First widget zone displayed in the footer' , 'retail-motion' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
		//Footer 2 widget
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget 2', 'retail-motion' ),
			'id'            => 'widget-footer-2',
			'description'   => esc_html__( 'Second widget zone displayed in the footer' , 'retail-motion' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
		
		//Footer 3 widget
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget 3', 'retail-motion' ),
			'id'            => 'widget-footer-3',
			'description'   => esc_html__( 'Third widget zone displayed in the footer' , 'retail-motion' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
		//Footer 4 widget
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget 4', 'retail-motion' ),
			'id'            => 'widget-footer-4',
			'description'   => esc_html__( 'Fourth widget zone displayed in the footer' , 'retail-motion' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
	
	//Set content width
	function set_theme_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'retail_motion_content_width', 640 );
	}
	
	//displays the next / prev post navigation on single posts
	public function display_post_navigation(){
		$html .= '';
		
		$html .= '<div class="el-row clear animation-container">';
			$html .= '<div class="el-col-small-12">';
				$args = array(
					'prev_text'	=> '<p class="control"><i class="icon fa fa-angle-left" aria-hidden="true"></i> Previous</p><div class="title">%title</div>',
					'next_text'	=> '<p class="control">Next <i class="icon fa fa-angle-right" aria-hidden="true"></i></p><div class="title">%title</div>'
				);
				$html .= get_the_post_navigation($args);
			$html .= '</div>';
		$html .= '</div>';
		
		echo $html;
	}

	//register metabox to conditionally show or hide featured posts
	public function register_show_hide_posts_meta_box(){
			
		$post_types = array('post','page','portfolio','services');
		
		foreach($post_types as $post_type){
			
			add_meta_box(
				'show-hide-posts-meta-box',
				'Show / Hide Posts',
				array($this, 'render_show_hide_metabox'),
				$post_type,
				'normal',
				'default'
			);	
		}	
		
	}
	
	//register metaboxes for applicable post types
	public function register_header_metabox(){
		
		$post_types = array('post','page','portfolio','services');
		
		foreach($post_types as $post_type){
			
			add_meta_box(
				'header-meta-box',
				'Header Info',
				array($this, 'render_header_metabox'),
				$post_type,
				'normal',
				'default'
			);	
		}
		
	}
	
	//show / hide portfolio cards meta box
	public function register_show_hide_portfolios_meta_box(){
		$post_types = array('post','page','portfolio','services');
		
		foreach($post_types as $post_type){
			add_meta_box(
				'show-hide-portfolios-meta-box',
				'Show / Hide Portfolios',
				array($this, 'render_show_hide_portfolios_metabox'),
				$post_type,
				'normal',
				'default'
			);
		}	
	}
	
	//call to action metabox
	public function register_call_to_action_metabox(){
		
		$post_types = array('post','page','portfolio','services');
		
		foreach($post_types as $post_type){
			add_meta_box(
				'call-to-action-meta-box',
				'Call to Action Elements',
				array($this, 'render_call_to_action_metabox'),
				$post_type,
				'normal',
				'default'
			);
		}	
	}
	
	//call to action metabox
	public function register_hero_section_metabox(){
		
		$post_types = array('post','page','portfolio','services');
		
		foreach($post_types as $post_type){
			add_meta_box(
				'hero-section-meta-box',
				'Hero Section Elements',
				array($this, 'render_hero_section_action_metabox'),
				$post_type,
				'normal',
				'default'
			);
		}			
	}
	
	//adds new fields to be used on terms, lets users select if a hero element should be displayed
	//public function register_hero_section_to_terms($fields){
	//	echo 'H!LLO';
	//	die();
	//}

	//registers a metabox that will be displayed only on pages using the 'Showcase Page' template
	public function register_showcase_page_meta_box(){
			
		add_meta_box(
			'showcase-page-meta-box',
			'Showcase Page Template Options',
			array($this, 'render_showcase_template_metabox'),
			'page',
			'advanced',
			'default'
		);
		
	}

	//renders the metabox for selecting which hero sections to display
	public function render_hero_section_action_metabox($post){
			
		$html = '';
		
		$fields = array(
			array(
				'id'			=> 'hero_section_elements',
				'title'			=> 'Hero Sections',
				'description'	=> 'Select the Hero Sections will be displayed on this post type.',
				'type'			=> 'related-posts',
				'meta_box_id'	=> 'hero-section-meta-box',
				'args'			=> array(
					'related_post_type_name'	=> 'hero_section'
				)
			)
		);
		
		foreach($fields as $field){
			$html .= el_content_type::render_metafield_element($field, $post);
		}
		
		echo $html;
	}
	
	//Renders the show / hide metabox for posts
	//Uses the `el_content_type` class to output our fields for simplicity
	public function render_show_hide_metabox($post){
		
		$html = '';
		$html .= '<p>Here you can control if this post will display upcoming featured articles</p>';
		
		$fields = array(
			array(
				'id'			=> 'display_featured_posts',
				'title'			=> 'Show Featured Posts',
				'description'	=> 'Do you want the featured articles to be displayed on this post?',
				'type'			=> 'radio',
				'args'			=> array(
					'options'	   => array(
						array('id' => 'yes', 'title' => 'Yes'),
						array('id' => 'no', 'title' => 'No')
					)
				) 
			)
		);
		
		//render all fields
		foreach($fields as $field){
			$html .= el_content_type::render_metafield_element($field, $post);
		}

		echo $html;
	}

	//Renders the show / hide metabox for optional portfolio card display
	//Uses the `el_content_type` class to output our fields for simplicity
	public function render_show_hide_portfolios_metabox($post){
			
		$html = '';
		$html .= '<p>Here you can control if this post will display the latest portfolio cards</p>';
		
		$fields = array(
			array(
				'id'			=> 'display_portfolio_cards',
				'title'			=> 'Show Portfolio Posts',
				'description'	=> 'Do you want the latest portfolio cards to be displayed on this post?',
				'type'			=> 'radio',
				'args'			=> array(
					'options'	   => array(
						array('id' => 'yes', 'title' => 'Yes'),
						array('id' => 'no', 'title' => 'No')
					)
				) 
			)
		);
		
		//render all fields
		foreach($fields as $field){
			$html .= el_content_type::render_metafield_element($field, $post);
		}
		
		
		
		echo $html;
	}
	
	
	//renders the showcase metabox output
	//Uses the `el_content_type` class to output our fields for simplicity
	//Content of metabox will be dynamically hidden or shown with JS when editing single pages
	public function render_showcase_template_metabox($post){
			
		$html = '';
		$html .= '<p>Enter the following information for this page. The following settings will determine the visual layout of your page</p>';
		
		$fields = array(
			array(
				 'id'			=> 'showcase_main_content',
				 'title'		=> 'Main Content',
				 'description'	=> 'Content area displayed at the very top of the showcase page, used as an introduction / full width content',
				 'type'			=> 'editor',
			),
			array(
				 'id'			=> 'showcase_left_content',
				 'title'		=> 'Right Content',
				 'description'	=> 'Content area displayed under the primary content area. Displayed on the left hand side of the showcase image',
				 'type'			=> 'editor',
			),
			array(
				 'id'			=> 'showcase_right_image',
				 'title'		=> 'Left Image',
				 'description'	=> 'Left image used under the primary content area. Displayed as a long background image on the left',
				 'type'			=> 'upload-image',
			),
			
			array(
				 'id'			=> 'showcase_bottom_content',
				 'title'		=> 'Bottom Content',
				 'description'	=> 'Content area displayed below the secondary content area. It\'s position will be negatively margined so it will slightly overlap the secondary area',
				 'type'			=> 'editor',
			),
			array(
				 'id'			=> 'showcase_data_summaries',
				 'title'		=> 'Data Summaries',
				 'description'	=> 'Optional data summaries that should be displayed on this page',
				 'type'			=> 'related-posts',
				 'args'			=> array(
				 	'related_post_type_name'	=> 'data_summary'
				 )
			),
		);
		
		foreach($fields as $field){
			$html .= el_content_type::render_metafield_element($field, $post);
		}

		echo $html;
		
	}
	
	//renders the header metabox output
	//Uses the `el_content_type` to output our fields for simplicity 
	public function render_header_metabox($post){
		
		$html = '';
		$html .= '<p>Enter the following information for the header</p>';
		
		$fields = array(
			array(
				'id'			=> 'header_enabled',
				'title'			=> 'Enable Custom Header',
				'description'	=> 'Determine if you want this post\'s header to be manually controlled (background image, colour, text, video link etc)',
				'type'			=> 'radio',
				'args'			=> array(
					'options'	   => array(
						array('id' => 'no', 'title' => 'No'),
						array('id' => 'yes', 'title' => 'Yes')
					)
				) 
			),
			array(
				 'id'			=> 'header_background_image',
				 'title'		=> 'Background Image',
				 'description'	=> 'Select a background image for use in the header',
				 'type'			=> 'upload-image',
			 ),
			 array(
				 'id'			=> 'header_background_color',
				 'title'		=> 'Background Color',
				 'description'	=> 'Select a solid color to be used in the background if you don\'t want to use an image',
				 'type'			=> 'color',
				 'args'			=> array(
					'default-color'		=> '#eee' 
			 	 )
			 ),
			  array(
				 'id'			=> 'header_overlay_color',
				 'title'		=> 'Overlay Color',
				 'description'	=> 'Select a color that will be displayed as an overlay on top of the image. This is optional',
				 'type'			=> 'color'
			 ),
			 array(
			 	'id'			=> 'header_overlay_opacity',
			 	'title'			=> 'Header Overlay Opacity',
			 	'description'	=> 'Opacity of the overlay element (when the overlay color has been set), lower values are more transparent',
			 	'type'			=> 'select',
			 	'args'			=> array(
			 		'options'		=> array(
			 			array('id'	=> '0.0',  'title'	=> '0%'),
						array('id'	=> '0.1', 'title'	=> '10%'),
						array('id'	=> '0.2', 'title'	=> '20%'),
						array('id'	=> '0.3', 'title'	=> '30%'),
						array('id'	=> '0.4', 'title'	=> '40%'),
						array('id'	=> '0.5', 'title'	=> '50%'),
						array('id'	=> '0.6', 'title'	=> '60%'),
						array('id'	=> '0.7', 'title'	=> '70%'),
						array('id'	=> '0.8', 'title'	=> '80%'),
						array('id'	=> '0.9', 'title'	=> '90%'),
						array('id'	=> '100', 'title'	=> '100%')
					)
				)
			 ),
			  array(
				 'id'			=> 'header_text_color',
				 'title'		=> 'Text Color',
				 'description'	=> 'Color of the text when superimposed over your image / ',
				 'type'			=> 'color',
				 'args'			=> array(
					'default-color'		=> '#333' 
			 	 )
			 ),
			 array(
			  	'id'			=> 'header_logo',
				 'title'		=> 'Header Logo',
				 'description'	=> '(Optional) - Select a logo to be used in the header',
				 'type'			=> 'upload-image',
			 ),
			 array(
			 	'id'			=> 'header_title',
			 	'title'			=> 'Header Title',
			 	'description'	=> 'Primary call to action text displayed in the header',
			 	'type'			=> 'text'
			 ),
			 array(
			 	'id'			=> 'header_subtitle',
			 	'title'			=> 'Header Subtitle',
			 	'description'	=> 'Secondary call to action text displayed in the header, below the primary title',
			 	'type'			=> 'text'
			 ),
			 array(
			 	'type'			=> 'line-break-title',
			 	'title'			=> 'Video Options',
			 	'description'	=> 'These settings relate to your video options from Wistia' 
			 ),
			 array(
			 	'id'			=> 'header_video_url',
			 	'title'			=> 'Video Embed URL',
			 	'description'	=> 'ULR of the iFrame element. This is found when clicking the \'Embed & Share\' option while managing your upload on Wistia.',   
			 	'type'			=> 'text',
			 	'args'			=> array(
					'placeholder'		=> 'E.g //fast.wistia.net/embed/iframe/qznuwjoj98'
				)
			 ),

		);
		
		foreach($fields as $field){
			$html .= el_content_type::render_metafield_element($field, $post);
		}

		echo $html;
	}

	//Displays the call to action metabox on post types, lets users assign CTA's on a post by post basis
	public function render_call_to_action_metabox($post){
			
		$html = '';
		
		$fields = array(
			array(
				'id'			=> 'call_to_action_elements',
				'title'			=> 'Call to Actions',
				'description'	=> 'Select the CTA\'s that will be displayed on this post type.',
				'type'			=> 'related-posts',
				'meta_box_id'	=> 'call-to-action-meta-box',
				'args'			=> array(
					'related_post_type_name'	=> 'call_to_action'
				)
			)
		);
		
		foreach($fields as $field){
			$html .= el_content_type::render_metafield_element($field, $post);
		}
		
		echo $html;
		
	}
	
	//save metadata from our header metabox
	public function save_post_header_metabox($post_id){
		
		$post_types = array('post','page','portfolio','services');
		$post_type = get_post_type($post_id);
		
		if(in_array($post_type, $post_types)){
			
			//Header Metabox
			$header_enabled = isset($_POST['header_enabled']) ? sanitize_text_field($_POST['header_enabled']) : 'no';
			$header_background_image = isset($_POST['header_background_image']) ? sanitize_text_field($_POST['header_background_image']) : '';
			$header_background_color = isset($_POST['header_background_color']) ? $_POST['header_background_color'] : '';
			$header_overlay_color = isset($_POST['header_overlay_color']) ? $_POST['header_overlay_color'] : '';
			$header_overlay_opacity = isset($_POST['header_overlay_opacity'])? $_POST['header_overlay_opacity'] : '';
			$header_text_color = isset($_POST['header_text_color']) ? $_POST['header_text_color'] : '';
			$header_logo = isset($_POST['header_logo']) ? sanitize_text_field($_POST['header_logo']) : '';
			$header_title = isset($_POST['header_title']) ? sanitize_text_field($_POST['header_title']) : '';
			$header_subtitle = isset($_POST['header_subtitle']) ? sanitize_text_field($_POST['header_subtitle']) : '';
			$header_video_url = isset($_POST['header_video_url']) ? esc_url($_POST['header_video_url']) : '';
			
			
			update_post_meta($post_id, 'header_enabled', $header_enabled);
			update_post_meta($post_id, 'header_background_image', $header_background_image);
			update_post_meta($post_id, 'header_background_color', $header_background_color);
			update_post_meta($post_id, 'header_overlay_color', $header_overlay_color);
			update_post_meta($post_id, 'header_overlay_opacity', $header_overlay_opacity);
			update_post_meta($post_id, 'header_text_color', $header_text_color);
			update_post_meta($post_id, 'header_logo', $header_logo);
			update_post_meta($post_id, 'header_title', $header_title);
			update_post_meta($post_id, 'header_subtitle', $header_subtitle);
			update_post_meta($post_id, 'header_video_url', $header_video_url);
			
		}
		
	}

	//save metadata from our call to action metabox
	public function save_post_call_to_action_metabox($post_id){
		
		$post_types = array('post','page','portfolio','services');
		$post_type = get_post_type($post_id);
		
		if(in_array($post_type, $post_types)){
				
			$call_to_action_elements = isset($_POST['call_to_action_elements']) ? $_POST['call_to_action_elements'] : '';
			if(!empty($call_to_action_elements)){
				$call_to_action_elements = json_encode($call_to_action_elements);
			}
			update_post_meta($post_id, 'call_to_action_elements', $call_to_action_elements);

		}
	}

	//save metadata for our hero section metabox
	public function save_post_hero_section_metabox($post_id){
		
		$post_types = array('post','page','portfolio','services');
		$post_type = get_post_type($post_id);
		
		if(in_array($post_type, $post_types)){
				
			$hero_section_elements = isset($_POST['hero_section_elements']) ? $_POST['hero_section_elements'] : '';			
			
			if(!empty($hero_section_elements)){
				$hero_section_elements = json_encode($hero_section_elements);
			}
			update_post_meta($post_id, 'hero_section_elements', $hero_section_elements);

		}
		
	}

	//save metabox for the showcase metabox 
	//Only want to save values if we're using the right template, else delete values
	public function save_post_showcase_metabox($post_id){
		
		$post_type = get_post_type($post_id);
		if($post_type === 'page'){
			
			if(isset($_POST['page_template'])){
					
				//if page is using the showcase template, update values
				if($_POST['page_template'] == 'page-showcase.php'){
					
					$showcase_main_content = isset($_POST['showcase_main_content']) ? $_POST['showcase_main_content'] : '';
					$showcase_right_image = isset($_POST['showcase_right_image']) ? $_POST['showcase_right_image'] : '';
					$showcase_left_content = isset($_POST['showcase_left_content']) ? $_POST['showcase_left_content'] : '';
					$showcase_data_summaries = isset($_POST['showcase_data_summaries']) ? json_encode($_POST['showcase_data_summaries']) : '';
					$showcase_bottom_content = isset($_POST['showcase_bottom_content']) ? $_POST['showcase_bottom_content'] : '';
					
					update_post_meta($post_id, 'showcase_main_content', $showcase_main_content);
					update_post_meta($post_id, 'showcase_right_image', $showcase_right_image);
					update_post_meta($post_id, 'showcase_left_content', $showcase_left_content);
					update_post_meta($post_id, 'showcase_bottom_content', $showcase_bottom_content);
					update_post_meta($post_id, 'showcase_data_summaries', $showcase_data_summaries);
					
				}
				//else remove set value if exist
				else{
					delete_post_meta($post_id, 'showcase_main_content');
					delete_post_meta($post_id, 'showcase_right_image');
					delete_post_meta($post_id, 'showcase_left_content');
					delete_post_meta($post_id, 'showcase_bottom_content');
					delete_post_meta($post_id, 'showcase_data_summaries');
				}
				
			}
	
		}
		
	}

	//save settings outlined in the show / hide metabox on applicable post types
	public function save_post_show_hide_posts_metabox($post_id){
			
		$post_types = array('post','page','portfolio','services');
		$post_type = get_post_type($post_id);
		
		if(in_array($post_type, $post_types)){
				
			$display_featured_posts = isset($_POST['display_featured_posts']) ? $_POST['display_featured_posts'] : '';
			update_post_meta($post_id, 'display_featured_posts', $display_featured_posts);

		}
	}
	
	//save settings outlined in the show / hide portfolio metabox
	public function save_post_show_hide_portfolio_metabox($post_id){
			
		$post_types = array('post','page','portfolio','services');
		$post_type = get_post_type($post_id);
		
		if(in_array($post_type, $post_types)){
				
			$display_portfolio_cards = isset($_POST['display_portfolio_cards']) ? $_POST['display_portfolio_cards'] : '';
			update_post_meta($post_id, 'display_portfolio_cards', $display_portfolio_cards);

		}
		
		
		
	}
	
	
	
	//admin only scripts / styles
	public function enqueue_admin_scripts_and_styles(){
		
		wp_enqueue_style('theme-admin-style', get_stylesheet_directory_uri() . '/inc/modules/universal/css/theme_universal_admin_styles.css');
		wp_enqueue_style('theme-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script('theme-admin-script', get_stylesheet_directory_uri() . '/inc/modules/universal/js/theme_universal_admin_scripts.js', array('jquery','jquery-ui-sortable','wp-color-picker'));
	}
	
	//public only scripts / styles
	public function enqueue_public_scripts_and_styles(){
		wp_enqueue_style('theme-fonts', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700');
		wp_enqueue_script('theme-masonry-script', get_stylesheet_directory_uri() . '/inc/modules/universal/js/jquery-masonry.js', array('jquery'));
		wp_enqueue_style('theme-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
		wp_enqueue_script('theme-flexslider-script', get_stylesheet_directory_uri() . '/inc/modules/universal/js/jquery.flexslider-min.js' , array('jquery'));
		wp_enqueue_style('theme-flexslider-styles', get_stylesheet_directory_uri() . '/inc/modules/universal/css/flexslider.css');
		wp_enqueue_script('theme-public-script', get_stylesheet_directory_uri() . '/inc/modules/universal/js/theme_universal_public_scripts.js', array('jquery', 'theme-masonry-script','theme-flexslider-script'));
		wp_enqueue_script('theme-wistia-script-primary', '//fast.wistia.com/assets/external/E-v1.js');	
	}
	
	//'advanced' metaboxes should be moved above the main post editor
	public function move_advanced_metaboxes_above_editor($post){
		
		global $wp_meta_boxes;
		
		//output all advanced metaboxes
		do_meta_boxes(get_current_screen(), 'advanced', $post);		
		
		//remove these metaboxes so they don't get outputted again
		unset($wp_meta_boxes[get_post_type($post)]['advanced']);
		
	}
	
	//add SVG to allowed file uploads
	public function add_file_types_to_uploads($file_types){
			
		$new_filetypes = array();
		$new_filetypes['svg'] = 'image/svg+xml';
		$file_types = array_merge($file_types, $new_filetypes );

		return $file_types;
	}
	
	//returns the singleton of this class
	public static function getInstance(){
		
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
 }
$el_universal = el_universal::getInstance();



?>