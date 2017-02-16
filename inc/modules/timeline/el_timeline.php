<?php
/*
 * Timeline
 * Timeline content type. Used by the timeline template to show a vertical timeline 
 */

 
 class el_timeline extends el_content_type{
 
 	private static $instance = null;
 	
	//post type args
 	private $post_type_args = array(
		'post_type_name'		=> 'timeline',
		'post_type_single_name'	=> 'Timeline Fragment',
		'post_type_plural_name'	=> 'Timeline Fragments',
		'labels'				=> array(
			'menu_name'				=> 'Timeline'
		),		
		'args'					=> array(
			'public'				=> false,
			'menu_icon'				=> 'dashicons-schedule'
		)
	);
	
	//meta boxes
	private $meta_box_args = array(
		array(
			'id'			=> 'timeline_metabox',
			'title'			=> 'Additional Information',
			'context'		=> 'advanced',
			'args'			=> array(
				'description' 	=> 'Please provide information about your fragment. These options determine how your content will be displayed'
			)
		)
	);

	//meta field elements
	private $meta_field_args = array(
		array(
			'id'			=> 'timeline_type',
			'title'			=> 'Fragment Type',
			'description'	=> 'Determine if your fragment will be a \'card\' or \'text\' type',
			'type'			=> 'select',
			'meta_box_id'	=> 'timeline_metabox',
			'args'			=> array(
				'options'		=> array(
					array(
						'id'	=> 'card',
						'title'	=> 'Card Layout'
					),
					array(
						'id'	=> 'text',
						'title'	=> 'Text Layout'
					)
				)
			)
		),
		array(
			'id'			=> 'timeline_position',
			'title'			=> 'Fragment Position',
			'description'	=> '(Only applicable on \'Card Layout\') Determines the horizontal position of this fragment',
			'type'			=> 'select',
			'meta_box_id'	=> 'timeline_metabox',
			'args'			=> array(
				'options'		=> array(
					array(
						'id'	=> 'left',
						'title'	=> 'Left'
					),
					array(
						'id'	=> 'right',
						'title'	=> 'Right'
					)
				)
			)
		),
		array(
			'id'			=> 'timeline_order',
			'title'			=> 'Timeline Order',
			'description'	=> 'order of this element in relation to other fragments, lower orders come first',
			'type'			=> 'number',
			'meta_box_id'	=> 'timeline_metabox',
		)
	);
 
 	//child constructor, add custom function here
 	public function __construct(){
 		
		
		//output
		add_action('el_display_timeline', array($this, 'display_timeline'));
		//add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts_and_styles'));
			
 		//call parent construct to set this up
 		parent::__construct(
 			$this->post_type_args, 
 			$this->meta_box_args, 
 			$this->meta_field_args
		);
		
 	}
	
	//displays timeline, used by action 
	public static function display_timeline(){
			
		$instance = self::getInstance();
		$html = $instance::get_timeline_html();
		
		echo $html;
	}
	
	//gets the HTML output for the timeline
	public static function get_timeline_html(){
		
		$instance = self::getInstance();
		
		$html = '';
		
		
		$post_args = array(
			'post_type'			=> $instance->post_type_args['post_type_name'],
			'posts_per_page'	=> -1,
			'order'				=> 'ASC',
			'orderby'			=> 'meta_value',
			'meta_key'			=> 'timeline_order'
		);

		$posts = get_posts($post_args);

		if($posts){
			$html .= '<div class="el-row inner timeline small-margin-top-medium ">';
				$html .= '<div class="dividing-line"></div>';
				foreach($posts as $post){
					$post_id = $post->ID;
					$post_title = apply_filters('the_title', $post->post_title);
					$post_content = apply_filters('the_content', $post->post_content);
					$post_timeline_type = get_post_meta($post_id, 'timeline_type', true );
					$post_timeline_position = get_post_meta($post_id, 'timeline_position', true);
					
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
					
					//output
					if($post_timeline_type == 'card'){
						$classes = ($post_timeline_position == 'right') ? 'el-col-medium-offset-7' : '';
						$html .= '<article class="fragment card animation-container el-col-small-12 el-col-medium-5 small-margin-top-bottom-medium medium-margin-top-x-large ' . $classes .'">';
							$html .= '<div class="anchor ' . $post_timeline_position .'"></div>';
							$html .= '<div class="line ' . $post_timeline_position .'"></div>';
							$html .= '<div class="inner">';
								//content
								if(!empty($post_content) || !empty($post_title)){
									$html .= '<div class="content-wrap">';
										
										if(!empty($post_title)){
											$html .= '<h2 class="title fat">' . $post_title . '</h2>';
										}
										
										if(!empty($post_content)){
											$html .= '<div class="content">' . $post_content . '</div>';
										}
									$html .= '</div>';	
								}
		
								//image
								if(!empty($post_thumbnail_id)){
									$html .= '<div class="background-wrap small-aspect-1-1 medium-aspect-1-3">';
										$html .= '<div class="background-image" style="background-image: url(' . $post_thumbnail_url . ');"></div>';
									$html .= '</div>';
								}
							$html .= '</div>';
							
						$html .= '</article>';
						
					}
					//Text layout (no image)
					else if($post_timeline_type == 'text'){
						$html .= '<article class="fragment text animation-container small-align-center el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-margin-top-bottom-medium">';
							
							//content
							if(!empty($post_content) || !empty($post_title)){
								$html .= '<div class="content-wrap">';
								if(!empty($post_title)){
									$html .= '<h2 class="title big fat medium-margin-top-bottom-large">' . $post_title . '</h2>';
								}
								
								if(!empty($post_content)){
									$html .= '<div class="content">' . $post_content . '</div>';
								}
								$html .= '</div>';
							}
							
						$html .= '</article>';
						
					}
					
				
				}
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
	
	public function enqueue_public_scripts_and_styles(){
		$directory = get_stylesheet_directory_uri() . '/inc/modules/services';
		wp_enqueue_script('services-public-scripts', $directory . '/js/services-public-scripts.js', array('jquery'));	
	}

	
	
 }
 $el_timeline = el_timeline::getInstance();


?>