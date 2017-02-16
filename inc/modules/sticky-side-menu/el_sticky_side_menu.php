<?php
/*
 * Sticky Side Menu
 * New content type, used to create the stick side menu displayed on the right hand side of the website. Provides quick links (title + icon)
 * to locations such as facebook, calculators and contact page.
 */

 
 class el_sticky_side_menu extends el_content_type{
 
 	private static $instance = null;
 	
	//post type args
 	private $post_type_args = array(
		'post_type_name'		=> 'sticky_menu',
		'post_type_single_name'	=> 'Menu Fragment',
		'post_type_plural_name'	=> 'Menu Fragments',
		'labels'				=> array(
			'menu_name'				=> 'Sticky Side Menu'
		),		
		'args'					=> array(
			'public'				=> false,
			'menu_icon'				=> 'dashicons-menu',
			'supports'				=> array('title', 'thumbnail')
		)
	);
	
	//meta boxes
	private $meta_box_args = array(
		array(
			'id'			=> 'sticky_side_menu_metabox',
			'title'			=> 'Additional Information',
			'context'		=> 'advanced',
			'args'			=> array(
				'description' 	=> 'Please provide information about your menu fragment. These options dictate how your fragment inside the stick menu will display'
			)
		)
	);

	//meta field elements
	private $meta_field_args = array(
	
		array(
			'id'			=> 'fragment_url',
			'title'			=> 'Fragment URL',
			'description'	=> 'URL that this fragment will go to when selected. Enter the full URL address',
			'type'			=> 'text',
			'meta_box_id'	=> 'sticky_side_menu_metabox'
		),
		array(
			'id'			=> 'fragment_order',
			'title'			=> 'Fragment Order',
			'description'	=> 'order of this element in relation to other fragments, lower orders come first',
			'type'			=> 'number',
			'meta_box_id'	=> 'sticky_side_menu_metabox',
		)
		
	);
 
 	//child constructor, add custom function here
 	public function __construct(){
 		
		
		//output
		add_action('el_display_sticky_side_menu', array($this, 'display_sticky_side_menu'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts_and_styles'));
			
 		//call parent construct to set this up
 		parent::__construct(
 			$this->post_type_args, 
 			$this->meta_box_args, 
 			$this->meta_field_args
		);
		
 	}
	
	//displays the sticky side menu
	public static function display_sticky_side_menu(){
			
		$instance = self::getInstance();
		$html = $instance::get_sticky_menu_html();
		
		echo $html;
	}
	
	//gets the HTML output for the menu
	public static function get_sticky_menu_html(){
		
		$instance = self::getInstance();
		
		$html = '';
		
		
		$post_args = array(
			'post_type'			=> $instance->post_type_args['post_type_name'],
			'posts_per_page'	=> -1,
			'order'				=> 'ASC',
			'orderby'			=> 'meta_value',
			'meta_key'			=> 'fragment_order'
		);

		$posts = get_posts($post_args);

		if($posts){
			
			$html .= '<div class="sticky-side-menu">';
			
				//menu toggle
				$html .= '<span class="menu-toggle"><i class="icon fa fa-angle-left" aria-hidden="true"></i></span>';
			
				foreach($posts as $post){
					
					$post_id = $post->ID;
					$post_title = apply_filters('the_title', $post->post_title);
					$fragment_url = get_post_meta($post->ID, 'fragment_url', true);
	
					//featured image
					$post_thumbnail_url = '';
					$post_thumbnail_id = has_post_thumbnail($post_id) ? get_post_thumbnail_id($post_id) : '';
					if($post_thumbnail_id){
						$post_attachment = get_post($post_thumbnail_id);
						if($post_attachment->post_mime_type == 'image/svg+xml'){
							$post_thumbnail_url = $post_attachment->guid;
						}else{
							$post_thumbnail_url = wp_get_attachment_image_src($post_thumbnail_id, 'medium', false)[0];
						}
					}
					
					//output each fragment
					$html .= '<div class="fragment">';
					
						if(!empty($fragment_url)){
							$html .= '<a href="' . $fragment_url . '">';
						}
					
						if(!empty($post_title)){
							$html .= '<div class="title">' . $post_title . '</div>';
						}
						
						if(!empty($post_thumbnail_id)){
							$html .= '<div class="background-wrap">';
								$html .= '<div class="background-image" style="background-image: url(' . $post_thumbnail_url . ');"></div>';
							$html .= '</div>';
						}
						
						if(!empty($fragment_url)){
							$html .= '</a>';
						}
					
					$html .= '</div>';
				}
				
				
				
			$html .= '</div>';
			
			//sticky nav menu background
			$html .= '<div class="sticky-side-menu-background">';
			
			$html .= '</div>';
		}
		
		return $html;
		
	}
	
	//returns the singleton of this class
	public static function getInstance(){
		
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	//enqueue public scripts and styles
	public function enqueue_public_scripts_and_styles(){
		
	
		$directory = get_stylesheet_directory_uri() . '/inc/modules/sticky-side-menu';
		
		wp_enqueue_script('sticky-menu-scripts', $directory . '/js/stick_side_menu.js', array('jquery'));	

	}

	
	
 }
 $el_sticky_side_menu = el_sticky_side_menu::getInstance();


?>