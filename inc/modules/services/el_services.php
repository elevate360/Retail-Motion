<?php
/*
 * Services
 * Services & Service categories
 */

 
 class el_services extends el_content_type{
 
 	private static $instance = null;
 	
	//post type args
 	private $post_type_args = array(
		'post_type_name'		=> 'services',
		'post_type_single_name'	=> 'service',
		'post_type_plural_name'	=> 'services',
		'labels'				=> array(
			'menu_name'				=> 'Services'
		),
		'args'					=> array(
			'menu_icon'				=> 'dashicons-forms'
		)
	);
	
	//taxonomies
	private $taxonomy_args = array(
		array(
			'taxonomy_name'			=> 'service_category',
			'taxonomy_single_name'	=> 'Service Category',
			'taxonomy_plural_name'	=> 'Service Categories',
			'labels' 	=> array(
				'menu_name'  	=> 'Categories'
			),
			'args'		=> array(
				'hierarchical'	=> true
			)
		),
		array(
			'taxonomy_name'			=> 'service_tag',
			'taxonomy_single_name'	=> 'Service Tag',
			'taxonomy_plural_name'	=> 'Service Tags',
			'labels' 	=> array(
				'menu_name'  	=> 'Tags'
			),
			'args'		=> array(
				'hierarchical'	=> false
			)
		)
	);
	
	//taxonomy fields
	private $taxonomy_field_args = array(
		array(
			'id'			=> 'category-image-svg',
			'title'			=> 'Category SVG Image',
			'description'	=> 'Icon to be displayed for the category. Please upload a simple SVG',
			'taxonomy_name'	=> 'service_category',
			'type'			=> 'upload-svg'
		),
		array(
			'id'			=> 'category-color',
			'title'			=> 'Category Background Color',
			'description'	=> 'Color used for the background of the category when displayed',
			'taxonomy_name'	=> 'service_category',
			'type'			=> 'color'
		),
		array(
			'id'			=> 'category-order',
			'title'			=> 'Category Order',
			'description'	=> 'Ordering for this category in relation to other categories when displayed on the front end',
			'taxonomy_name'	=> 'service_category',
			'type'			=> 'number'
		),
		array(
			'id'			=> 'category-hero-element',
			'title'			=> 'Category Hero Elements',
			'description'	=> 'Determines what hero elements (if any) are required when viewing this service category',
			'taxonomy_name'	=> 'service_category',
			'type'			=> 'related-posts',
			'args'			=> array(
				'type'						=> 'checkbox',
				'related_post_type_name'	=> 'hero_section'
			)
		)
		
	);
	
	//meta boxes
	private $meta_box_args = array(
		array(
			'id'			=> 'service_metabox',
			'title'			=> 'Additional Information',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'Please provide the following additional information about your service'
			)
		),
		array(
			'id'			=> 'service_subtitle_metabox',
			'title'			=> 'Additional Information',
			'context'		=> 'advanced',
			'priority'		=> 'high',
			'args'			=> array(
				'description'	=> 'Provide the following information'
			)
		)
	
	);

	//meta field elements
	private $meta_field_args = array(
		array(
			'id'			=> 'subtitle',
			'title'			=> 'Subtitle',
			'description'	=> 'Subtitle / tagline to be displayed under the title of the service (front of the card)',
			'type'			=> 'text',
			'meta_box_id'	=> 'service_subtitle_metabox'
		),
		array(
			'id'			=> 'service-order',
			'title'			=> 'Service Order',
			'description'	=> 'Specifies the order of this service in relation to other services when being displayed',
			'type'			=> 'number',
			'meta_box_id'	=> 'service_metabox'
		),
		array(
			'id'			=> 'service-card-back',
			'title'			=> 'Service Card Back Content',
			'description'	=> 'Content to be displayed on the back of the card',
			'type'			=> 'editor',
			'meta_box_id'	=> 'service_metabox'
		),
		array(
			'id'			=> 'service-clicks-through',
			'title'			=> 'Display link to service page',
			'description'	=> 'Determine if your service card should click through to your individual service page',
			'type'			=> 'select',
			'args'			=> array(
				'options'		=> array(
					array(
						'id'	=> 'true',
						'title'	=> 'Yes'
					),
					array(
						'id'	=> 'false',
						'title'	=> 'No'
					)
				)
			),
			'meta_box_id'	=> 'service_metabox'
		)
	);
 
 	//child constructor, add custom function here
 	public function __construct(){
 		
		
		//output
		add_action('el_display_service_categories_and_services', array($this, 'el_display_service_categories_and_services'), 10, 1); //display interactive category and service listing
		add_action('el_display_services_by_category', array($this, 'el_display_services_by_category'), 10, 1); //display services listing by category
		add_action('el_display_service_categories', array($this, 'el_display_service_categories'), 10, 1); //displays a listing of service categories (in their circular form)
		
		add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts_and_styles'));
			
 		//call parent construct to set this up
 		parent::__construct(
 			$this->post_type_args, 
 			$this->meta_box_args, 
 			$this->meta_field_args,
 			$this->taxonomy_args,
 			$this->taxonomy_field_args
		);
		
 	}
	
	//returns the singleton of this class
	public static function getInstance(){
		
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function enqueue_public_scripts_and_styles(){
		$directory = get_stylesheet_directory_uri() . '/inc/modules/services';
		wp_enqueue_script('services-public-scripts', $directory . '/js/services-public-scripts.js', array('jquery'));	
	}


	
	//hook to display service categories and it's associated services
	public function el_display_service_categories_and_services($args = array()){
		$html = $this->get_service_categories_and_services($args);
		echo $html;
	}
	
	//get our grid of service categories at the top, followed by service categories below
	public static function get_service_categories_and_services($args = array()){
		
		$html = '';
		$instance = self::getInstance();
		
		$terms_array = array();
		$posts_array = array();  
		
		//get all service categories
		$category_args = array(
			'hide_empty'		=> false,
			'taxonomy'			=> 'service_category',
			'orderby'			=> 'meta_value_num',
			'order'				=> 'ASC',
			'meta_key'			=> 'category-order'
		);
		$terms = get_terms($category_args);
		if($terms){
			foreach($terms as $term){
				$terms_array[$term->term_id] = $term;
			}
		}	
		
		//display title and subtitle (determined by theme mod settings / manual passed args)
		$service_title = get_theme_mod('retail_services_title');
		$service_subtitle = get_theme_mod('retail_services_subtitle');
		
		//check for passed arguments
		if(isset($args['title']) || isset($args['subtitle'])){
			$html .= '<div class="el-row service-category-header small-margin-top-medium medium-margin-top-x-large">';
			if(isset($args['title'])){
				$html .= '<h2 class="h1 fat big el-col-small-12 orange small-align-center small-margin-top-bottom-none ">' . $args['title'] . '</h2>';
			}	
			if(isset($args['subtitle'])){
				$html .= '<p class="el-col-small-8 el-col-small-offset-2 small-align-center small-margin-top-bottom-tiny">' . $args['subtitle'] . '</p>';
			}
			$html .= '</div>';
		}//check for theme mod defaults
		else if(!empty($service_title) || !empty($service_subtitle)){
			$html .= '<div class="el-row service-category-header small-margin-top-medium medium-margin-top-x-large">';
			if(!empty($service_title)){
				$html .= '<h2 class="h1 fat big el-col-small-12 orange small-align-center small-margin-top-bottom-none ">' . $service_title . '</h2>';
			}
			if(!empty($service_subtitle)){
				$html .= '<p class="el-col-small-8 el-col-small-offset-2 small-align-center small-margin-top-bottom-tiny">' . $service_subtitle . '</p>';
			}
			$html .= '</div>';
		}
		

		//Main wrapper for categories and services
		$html .= '<article class="el-row services-and-categories animation-container">';
		
			//Get all posts, separated into categories
			if($terms){
				foreach($terms as $term){
					
					$term_slug = $term->slug;
					$term_id = $term->term_id;
					$taxonomy_name = $term->taxonomy;
					
					//add our top level elements, the term object and empty array for posts
					$posts_array[$term_id] = array('term' => $term, 'posts' => array()); 
					
					//find post belonging to tax
					$post_args = array(
						'post_type'			=> 'services',
						'posts_per_page'	=> -1,
						'order'				=> 'ASC',
						'orderby'			=> 'meta_value',
						'meta_key'			=> 'service-order',
						'tax_query'			=> array(
													array(
														'taxonomy'	=> $taxonomy_name,
														'terms'		=>  $term_id,
														'field'		=> 'term_id'	
													)
						)
					);
				
					$posts = get_posts($post_args);
					
					//push each post onto the array, into the correct term
					if($posts){
						foreach($posts as $post){
							
							$posts_array[$term_id]['posts'][] = $post;
						}
					}
					
				}
			}
	
			//SERVICE Categories
			if($terms_array){
				//get the fancy category output (circles with information)
				$html .= $instance::get_service_categories_html($terms_array); 
			}
			
			//SERVICES
			if($posts_array){
				$counter = 0;
				foreach($posts_array as $object){
					//get the term object and posts
					$term = $object['term'];
					$posts = $object['posts'];
					
					$term_id = $term->term_id;
					
					$class = ($counter == 0) ? 'active' : '';
					$html .= '<div class="services-cards equal-height-items el-row  ' . $class . '" id="services-for-category-' . $term_id . '">';
					
					//each card
					foreach($posts as $post){
							
						$html .= $instance::get_single_service_card_html($post->ID);
						
					}
					$html .= '</div>';
					$counter++;
				}
				
				
			}
		
		$html .= '</article>';
		
		return $html;
	}

	//hook to display the service categories (in their circular display)
	public static function el_display_service_categories($args = array()){
			
		$instance = self::getInstance();
		$html = '';
		
		$terms_array = array();
		
		//get all service categories
		$category_args = array(
			'hide_empty'		=> false,
			'taxonomy'			=> 'service_category',
			'orderby'			=> 'meta_value_num',
			'order'				=> 'ASC',
			'meta_key'			=> 'category-order'
		);
		$terms = get_terms($category_args);
		
		if($terms){
			$html .= '<article class="el-row service-categories animation-container">';
				//display title and subtitle
				if(isset($args['title']) || isset($args['subtitle'])){
					$html .= '<div class="el-row service-category-header small-margin-top-medium medium-margin-top-large">';
					if(isset($args['title'])){
						$html .= '<h1 class="fat big el-col-small-12 orange small-align-center small-margin-top-bottom-none ">' . $args['title'] . '</h1>';
					}	
					if(isset($args['subtitle'])){
						$html .= '<h3 class="el-col-small-12 small-align-center">' . $args['subtitle'] . '</h3>';
					}
					$html .= '</div>';
					
				}
				
				
				//get a listing of categories
				$html .= $instance::get_service_categories_html($terms);
			$html .= '</article>';
		}
			
		echo $html;
	}

	//given an array of service categories (terms). Display the categories in their circular display
    public static function get_service_categories_html($terms_array){
    	
		$instance = self::getInstance();
    	$html = '';
		
		$html .= '<div class="services-categories el-row  inner small-padding-top-small medium-padding-top-x-large">';
			$counter = 0;
			//display each category circle 
			foreach($terms_array as $term){
				
				$term_name = $term->name; 
				$term_image = get_term_meta($term->term_id, 'category-image-svg', true);
				$term_background_color = get_term_meta($term->term_id, 'category-color', true);
				
				if(!empty($term_image)){
					$post_attachment = get_post($term_image);
					if($post_attachment->post_mime_type == 'image/svg+xml'){
						$term_image_url = $post_attachment->guid;
					}else{
						$term_image_url = wp_get_attachment_image_src($post_thumbnail_id, 'medium', false)[0];
					}
				}
			
				$class = ($counter == 0) ? 'active' : '';
				//Display each category
				$html .= '<div class="category ' . $class .'" id="category-' . $term->term_id . '">';
					$style = !empty($term_background_color) ? 'style="background-color: ' . $term_background_color . ';"' : '';
					$html .= '<div class="cat-inner" ' . $style . '>';
						$html .= '<div class="image-wrap">';
							if(!empty($term_image)){
								$html .= '<img src="' . $term_image_url . '">';
							}
							$html .= '<h3 class=" title small-margin-top-bottom-small">' . $term_name . '</h3>';			
						$html .= '</div>';
					$html .= '</div>';
					$html .= '<div class="shadow"></div>';
				$html .= '</div>';
				$counter++; 
			}
			//display information for each category
			$html .= '<div class="category-information small-margin-top-large small-margin-bottom-medium el-row">';
				$counter = 0;
				foreach($terms_array as $term){
					$term_id = $term->term_id;
					$term_name = $term->name; 
					$term_permalink = get_term_link($term);
					$term_description = $term->description;
					$term_background_color = get_term_meta($term->term_id, 'category-color', true);
					
					
					$html .= '<div class="information el-col-small-12 el-col-medium-8 el-col-medium-offset-2" id="information-for-category-' . $term_id  . '">';
						if(!empty($term_description)){
							$html .= '<h3 class="big fat small-margin-top-bottom-none">' . $term_name . '</h3>';
							$html .= '<p>' . $term_description . '</p>';
							$html .= '<a class="button white" href="' . $term_permalink . '" style="background-color: ' . $term_background_color . ';" title="Find out more">Read More</a>';
						}
						
					$html .= '</div>';
					$counter++;
				}
			$html .= '</div>';
		$html .= '</div>';
		
		return $html;
    }
	
	//given a service category ID, display all services
	public static function el_display_services_by_category($category_id){
		
		$instance = self::getInstance();
		$html = '';
		
		//find all services belonging to this tax
		$post_args = array(
			'post_type'			=> 'services',
			'posts_per_page'	=> -1,
			'order'				=> 'ASC',
			'orderby'			=> 'meta_value',
			'meta_key'			=> 'service-order',
			'tax_query'			=> array(
										array(
											'taxonomy'	=> 'service_category',
											'terms'		=>  $category_id,
											'field'		=> 'term_id'	
										)
			)
		);
		
		
		$posts = get_posts($post_args);
		//get a grid listing of services
		if($posts){
			$html .= '<div class="services-cards equal-height-items el-row">';
				foreach($posts as $post){
					$html .= $instance::get_single_service_card_html($post->ID);
				}
			$html .= '</div>';
		}
		
		echo $html;
	}
	
	
	//gets the HTML output for a single service card
	public static function get_single_service_card_html($post_id){
			
		$instance = self::getInstance();
		$html = '';
		$post = get_post($post_id);
		if($post){
			
			$post_title = apply_filters('the_title', $post->post_title);
			$post_content = apply_filters('the_content', $post->post_content);
			$post_permalink = get_permalink($post_id);
			$post_excerpt = $post->post_excerpt;
			$post_subtitle = get_post_meta($post_id, 'subtitle', true);
			$post_back_content = get_post_meta($post_id, 'service-card-back', true);
			$post_clicks_through = get_post_meta($post_id,'service-clicks-through', true);
			
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
			//get terms
			$post_terms = wp_get_post_terms($post->ID, 'service_category');
			$term_color = '#ccc';
			if($post_terms){
				$main_term = $post_terms[0];
				//get associated term color
				$main_term_color = get_term_meta($main_term->term_id, 'category-color', true);
				if(!empty($main_term_color)){
					$term_color = $main_term_color;
				}
				$term_name = $main_term->name;
			}
			

			$html .= '<div class="service-card equal-height-item el-col-small-12 el-col-medium-4 el-col-large-3 small-margin-bottom-medium">';
			
				//determine if we can click through to single service page
				if(!empty($post_clicks_through) && $post_clicks_through == 'true'){
				$html .= '<a href="' . $post_permalink .'">';
				}
					$html .= '<div class="inner">';	
						//card front
						$html .= '<div class="front">';
							$html .= '<div class="front-inner">';
								$html .= '<h2 class="small" style="color: ' . $term_color . ';">' . $post_title . '</h2>';
								$html .= '<h3 class="small">' . $post_subtitle . '</h3>';
								if($post_thumbnail_id){
									$html .= '<img src="' . $post_thumbnail_url . '"/>';
								}
							$html .= '</div>';
							
							//category name and colour
							$html .= '<div class="category-name" style="background-color:' . $term_color . ';">' . $term_name . '</div>';
						$html .= '</div>';
						//card back
						$html .= '<div class="back">';
							$html .= '<div class="back-inner" style="background-color: ' . $term_color . ';">';
								//display back content if set
								if(!empty($post_back_content)){
									$html .= '<div class="excerpt">' . apply_filters('the_content', $post_back_content) . '</div>';
								}
								//display click through if needed
								if(!empty($post_clicks_through) && $post_clicks_through == 'true'){
									$html .= '<div class="readmore">Find out More</div>';
								}
							$html .= '</div>';
						$html .= '</div>';
					$html .= '</div>';
					
				if(!empty($post_clicks_through) && $post_clicks_through == 'true'){
				$html .= '</a>';
				}
				
				
			$html .= '</div>';
			
			
		}

		return $html;
		
		
	}

	
	
 }
 $el_services = el_services::getInstance();


?>