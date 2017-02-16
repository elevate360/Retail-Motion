<?php
/*
 * clients
 * Content type for outlining clients
 * - 
 */
 
 class el_clients extends el_content_type{
 	
	private static $instance = null;
	
	//post type args
 	private static $post_type_args = array(
		'post_type_name'		=> 'client',
		'post_type_single_name'	=> 'Client',
		'post_type_plural_name'	=> 'Clients',
		'labels'				=> array(
			'menu_name'				=> 'Clients'
		),
		'args'					=> array(
			'menu_icon'				=> 'dashicons-groups',
			'public'				=> false,
			'supports'				=> array('title')
		)
	);
	

	//meta boxes (several here to separate functionality)
	private static $meta_box_args = array(
		array(
			'id'			=> 'client_information_meta_box',
			'title'			=> 'Client Info',
			'context'		=> 'advanced',
			'args'			=> array(
				'description' => 'Please complete the following information about your client'
			)
		)
	);
	
	//meta field args
	private static $meta_fields_args = array(
		array(
			'id'			=> 'client_title',
			'title'			=> 'Client Title',
			'description'	=> 'Title displayed when interacting with the client tile',
			'type'			=> 'text',
			'meta_box_id'	=> 'client_information_meta_box'
		),
		array(
			'id'			=> 'client_logo',
			'title'			=> 'Client Logo',
			'description'	=> 'Image icon to display for the client',
			'type'			=> 'upload-image',
			'meta_box_id'	=> 'client_information_meta_box'
		),
		array(
			'id'			=> 'client_related_portfolio',
			'title'			=> 'Client Portfolio',
			'description'	=> 'Select that portfolio that relates to this client. This will be the page linked to when clicking on this clients logo inside the client grid',
			'type'			=> 'related-posts',
			'meta_box_id'	=> 'client_information_meta_box',
			'args'			=> array(
				'related_post_type_name'	=> 'portfolio',
				'type'						=> 'radio'
			)
		),
		array(
			'id'			=> 'client_order',
			'title'			=> 'Client Order',
			'description'	=> 'Order of this client in relation to other client logos when displayed in the client grid. Lower numbers come first',
			'type'			=> 'number',
			'meta_box_id'	=> 'client_information_meta_box'
		),
	
		
	);
	

	public function __construct(){
		 

		 //call parent constrcutor 
		  parent::__construct(
		 	 self::$post_type_args,
		 	 self::$meta_box_args,
		 	 self::$meta_fields_args
		  );

		add_action('el_display_client_card_listing', array($this, 'display_client_cards'), 10, 1);
			
		
	}

	//hooked function to display a listing of client cards
	public static function display_client_cards($args = array()){
		
		$instance = self::getInstance();
		$html = '';
		
		$post_args = array(
			'post_type'		=> $instance::$post_type_args['post_type_name'],
			'posts_per_page'=> -1,
			'meta_key'		=> 'client_order',
			'orderby'		=> 'meta_value_num',
			'order'			=> 'ASC'
		);
		$posts = get_posts($post_args);
	
		if($posts){
			$html .= '<article class="client-cards animation-container el-row small-margin-top-bottom-medium">';
				
				//Determine if we want to display a title / subtitle
				$client_title = get_theme_mod('retail_partners_title');
				$client_subtitle = get_theme_mod('retail_partners_subtitle');
				
				//if we have manual settings passed
				if(isset($args['title']) || isset($args['subtitle'])){
					$html .= '<div class="el-col-small-12 small-align-center">';
					if(isset($args['title'])){
						$html .= '<h2 class="h1 big fat small-margin-top-bottom-none">' . $args['title'] .'</h2>';
					}
					if(isset($args['subtitle'])){
						$html .= '<p class="small-margin-top-bottom-tiny">' . $args['subtitle'] .'</p>';
					}
					$html .= '</div>';
				}//check theme mod settings to display
				else if(!empty($client_title) || !empty($client_subtitle)){
					$html .= '<div class="el-col-small-12 small-align-center">';
					if(!empty($client_title)){
						$html .= '<h2 class="h1 big fat small-margin-top-bottom-none">' . $client_title .'</h2>';
					}
					if(!empty($client_subtitle)){
						$html .= '<p class="small-margin-top-bottom-tiny">' . $client_subtitle .'</p>';
					}
					$html .= '</div>';
				}

				//listing of cient cards
				$html .= '<div class="el-col-small-12 small-margin-top-small">';
					foreach($posts as $post){
						$html .= $instance::get_client_html($post->ID);
					}
				$html .= '</div>';
			$html .= '</article>';
		}

		echo $html;
	}
	
	//gets the markup for a single client card
	public static function get_client_html($post_id){
	
		$instance = self::getInstance();
		$html = '';
		
		$post = get_post($post_id);
		if($post){
			
			$post_permalink = get_permalink($post_id);
			$post_client_title = get_post_meta($post_id, 'client_title', true);
			$post_client_related_portfolio = get_post_meta($post_id, 'client_related_portfolio', true);
			$post_client_logo = get_post_meta($post_id, 'client_logo', true);
			$post_client_logo_url = '';
		
			if(!empty($post_client_logo)){
				$post_attachment = get_post($post_client_logo);
				if($post_attachment->post_mime_type == 'image/svg+xml'){
					$post_client_logo_url = $post_attachment->guid;
				}else{
					$post_client_logo_url = wp_get_attachment_image_src($post_client_logo, 'large', false)[0];
				}	
			}
			
			
			$html .= '<section class="client-card el-col-small-6 el-col-medium-4 el-col-large-3 small-margin-bottom-medium">';
				$html .= '<div class="inner">';
				if(!empty($post_client_logo)){
					$html .= '<div class="background-wrap small-aspect-1-1 medium-aspect-3-4">';
						//start link
						if(!empty($post_client_related_portfolio)){
							$url = get_permalink(json_decode($post_client_related_portfolio)[0]);
							$html .= '<a class="" href="' . $url .'">';
						}	
						$html .= '<div class="background-image" style="background-image: url(' . $post_client_logo_url . ');"></div>';
						//end link
						if(!empty($post_client_related_portfolio)){
							$html .= '</a>';
						}	
					$html .= '</div>';
					//readmore
					
					
					
					
					//overlay
					//$html .= '<div class="overlay"></div>';
					//title and readmore
					// $html .= '<div class="content">';
						// $url = '';
						// if(!empty($post_client_related_portfolio)){
							// $url = get_permalink(json_decode($post_client_related_portfolio)[0]);
						// }	
						// //title
						// $html .= '<h3 class="title small-margin-top-none small-margin-bottom-small">';
						// if(!empty($url)){
							// $html .= '<a href="' . $url . '">';
						// }
						// $html .= $post_client_title;
						// if(!empty($url)){
							// $html .= '</a>';
						// }
						// $html .= '</h3>';
// 						
						// //readmore
						// if(!empty($url)){
							// $html .= '<a class="button white small" href="' . $url .'">Read More</a>';
						// }
// 								
					// $html .= '</div>';
				}
				$html .= '</div>';
			$html .= '</section>';
			
			
		}
		
		
		return $html;
	}



	//returns the singleton of this specific
	public static function getInstance(){
		
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	
 }
 $el_clients = el_clients::getInstance();
 


?>