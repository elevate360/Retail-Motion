<?php
/*
 * Call to Action
 * Content type for defining several different types of call to actions areas, displayed on a page by page basis
 * - Call to action areas as displayed via a metabox on applicable post types, displaying them below the main content area
 */

 class el_call_to_action extends el_content_type{
 	
	private static $instance = null;
	
	//post type args
 	private $post_type_args = array(
		'post_type_name'		=> 'call_to_action',
		'post_type_single_name'	=> 'Call to Action',
		'post_type_plural_name'	=> 'Call to Actions',
		'labels'				=> array(
			'menu_name'				=> 'Call to Action'
		),
		'args'					=> array(
			'menu_icon'				=> 'dashicons-migrate',
			'supports'				=> array('title'),
			'public'				=> false
		)
	);
	

	//meta boxes (several here to separate functionality)
	private $meta_box_args = array(
		array(
			'id'			=> 'call_to_action_metabox',
			'title'			=> 'Call To Action Info',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'Complete the following information about your call to action item'
			)
		)
	);
	
	//meta field args
	private $meta_fields_args = array(
	
		//universal fields (affecting the layout and design of the element)
		array(
			'id'			=> 'cta_accent_color',
			'title'			=> 'Accent Colour',
			'description'	=> 'Accent colour to use for this call to action element. Colour will be used on both titles and for the button link',
			'type'			=> 'color',
			'meta_box_id'	=> 'call_to_action_metabox'
		),
		array(
			'id'			=> 'cta_layout_type',
			'title'			=> 'Layout Type',
			'description'	=> 'Determines the layout style of this call to action element. Dictates the visual output',
			'type'			=> 'select',
			'meta_box_id'	=> 'call_to_action_metabox',
			'args'			=> array(
				'options'		=> array(
					array(
						'id'		=> 'layout_style_left',
						'title'		=> 'Left Style'
					),
					array(
						'id'		=> 'layout_style_center',
						'title'		=> 'Center Style'
					),
					array(
						'id'		=> 'layout_style_right',
						'title'		=> 'Right Style'
					)
				)
			)
		),
		
		
		//Call to action primary elements (relates to the 'text' portion of the cta )
		array(
			'id'			=> 'cta_text_title',
			'title'			=> 'Text Title',
			'description'	=> 'Large primary title used for the call to action element. Most important element',
			'type'			=> 'text',
			'meta_box_id'	=> 'call_to_action_metabox'
		),
		array(
			'id'			=> 'cta_text_content',
			'title'			=> 'Text Title',
			'description'	=> 'Summary excerpt text displayed under the primary call to action title. Describes the call to action',
			'type'			=> 'textarea',
			'meta_box_id'	=> 'call_to_action_metabox'
		),
		array(
			'id'			=> 'cta_text_url',
			'title'			=> 'Text URL',
			'description'	=> 'The URL where this call to action element will point to.',
			'type'			=> 'text',
			'meta_box_id'	=> 'call_to_action_metabox'
		),
		array(
			'id'			=> 'cta_card_background_image',
			'title'			=> 'Background Image',
			'description'	=> 'Optional image displayed in the background of this call to action element',
			'type'			=> 'upload-image',
			'meta_box_id'	=> 'call_to_action_metabox'
		),
	
		//Call to action secondary elements (relates to the 'card' portion of the cta)
		array(
			'id'			=> 'cta_card_title',
			'title'			=> 'Card Title',
			'description'	=> 'Title displayed on the call to action card element.',
			'type'			=> 'text',
			'meta_box_id'	=> 'call_to_action_metabox'
		),
		array(
			'id'			=> 'cta_card_subtitle',
			'title'			=> 'Card Subtitle',
			'description'	=> 'Subtitle displayed on the call to action card element. Right below the title',
			'type'			=> 'textarea',
			'meta_box_id'	=> 'call_to_action_metabox'
		),
		array(
			'id'			=> 'cta_card_icon_image',
			'title'			=> 'Card Icon Image',
			'description'	=> 'Icon displayed on the call to action card element. Will be displayed on a white background',
			'type'			=> 'upload-svg',
			'meta_box_id'	=> 'call_to_action_metabox'
		),
		array(
			'id'			=> 'cta_card_readmore_text',
			'title'			=> 'Card Readmore Title',
			'description'	=> 'Very bottom element of the card. Used as a final call to action text. Background coloured by the chosen accent colour',
			'type'			=> 'text',
			'meta_box_id'	=> 'call_to_action_metabox'
		),
	);
	

	public function __construct(){
		 
		 //call parent constrcutor 
		 parent::__construct(
		 	$this->post_type_args,
		 	$this->meta_box_args,
		 	$this->meta_fields_args
		 );


		//action hook 
		add_action('el_display_post_call_to_action', array($this, 'display_call_to_action_for_post'), 10, 1);
	}

	//given a post, output it's related call to action elements if any are assigned
	public static function display_call_to_action_for_post($post){
		$instance = self::getInstance();
		$html = '';
		
		$call_to_action_elements = get_post_meta($post->ID, 'call_to_action_elements', true);
		if(!empty($call_to_action_elements)){
			$call_to_action_elements = json_decode($call_to_action_elements);
			
			$html .= '<article class="call-to-actions el-row ">';
			foreach($call_to_action_elements as $cta_id){
				$html .= $instance::get_call_to_action_html($cta_id);
			}
			$html .= '</article>';
		}
	
		echo $html;
	}

	//given an ID, generate the HTML output for the call to action element
	//Provides both a left (text + card) or right (image + text) layout
	public static function get_call_to_action_html($post_id){
			
		$instance = self::getInstance();
		$html = '';
		
		//get the CTA element
		$post = get_post($post_id);
		if($post){
			
			$cta_accent_color = get_post_meta($post->ID, 'cta_accent_color', true);
			$cta_layout_type = get_post_meta($post->ID, 'cta_layout_type', true);
			$cta_text_title = get_post_meta($post->ID, 'cta_text_title', true);
			$cta_text_url = get_post_meta($post->ID,'cta_text_url', true);
			$cta_text_content = get_post_meta($post->ID, 'cta_text_content', true);
			//background image
			$cta_card_background_image = get_post_meta($post->ID, 'cta_card_background_image', true);
			$cta_card_background_url = '';
			if(!empty($cta_card_background_image)){
				$post_attachment = get_post($cta_card_background_image);
				if($post_attachment->post_mime_type == 'image/svg+xml'){
					$cta_card_background_url = $post_attachment->guid;
				}else{
					$cta_card_background_url = wp_get_attachment_image_src($cta_card_background_image, 'large', false)[0];
				}	
			}
			
			
			$cta_id = $post->ID;
			$cta_card_title = get_post_meta($post->ID, 'cta_card_title', true);
			$cta_card_subtitle = get_post_meta($post->ID, 'cta_card_subtitle', true);
			//card icon
			$cta_card_icon_image = get_post_meta($post->ID, 'cta_card_icon_image', true);
			$cta_card_icon_url = '';
			if(!empty($cta_card_icon_image)){
				$post_attachment = get_post($cta_card_icon_image);
				if($post_attachment->post_mime_type == 'image/svg+xml'){
					$cta_card_icon_url = $post_attachment->guid;
				}else{
					$cta_card_icon_url = wp_get_attachment_image_src($cta_card_icon_image, 'large', false)[0];
				}	
			}
			$cta_card_readmore_text = get_post_meta($post->ID,'cta_card_readmore_text', true);
			
			//OUTPUT
			//Conditionally formatted to provide the right layout / offset for the card and text
			$html .= '<div class="el-row nested">';
				$html .= '<article class="action action-' . $cta_id . ' animation-container el-col-small-12 ' . $cta_layout_type . ' small-padding-top-bottom-small medium-padding-top-bottom-xx-large">';
					
					//background image
					if(!empty($cta_card_background_url)){
						$html .= '<div class="background-image" style="background-image:url(' . $cta_card_background_url .');"></div>';
					}
					
					//PRIMARY (Text section)
					$primary_html = '';
					
					$classes = '';
					if($cta_layout_type == 'layout_style_left'){
						$classes .= 'el-col-small-12 el-col-medium-4 el-col-medium-offset-1';
					}
					else if($cta_layout_type == 'layout_style_center'){
						$classes .= 'el-col-small-12 el-col-medium-4 el-col-medium-offset-4';
					}
					else if($cta_layout_type == 'layout_style_right'){
						$classes .= 'el-col-small-12 el-col-medium-4 el-col-medium-offset-4 ';
					}
					
					$primary_html .= '<section class="primary  ' . $classes . ' small-margin-top-bottom-small medium-margin-top-bottom-large">';
						
						//accent line
						if(!empty($cta_accent_color)){
							$primary_html .= '<div class="accent-line" style="background-color: ' . $cta_accent_color . ';"></div>';
						}
						if(!empty($cta_text_title)){
							$style = !empty($cta_accent_color) ? 'color:' . $cta_accent_color . ';' : '';
							$primary_html .= '<h1 class="title fat" style="' . $style .'">' . $cta_text_title . '</h1>';
						}
						if(!empty($cta_text_content)){
							$primary_html .= '<p class="content">' . $cta_text_content . '</p>';	
						}
						if(!empty($cta_text_url)){
							//style link background color
							$style = !empty($cta_accent_color) ? 'color:#fff;background-color:' . $cta_accent_color . ';' : '';
							$primary_html .= '<a class="button no-border large small-margin-top-medium" style=' . $style .'" href="' . $cta_text_url .'" title="find out more">Find Out More</a>';
						}
					$primary_html .= '</section>';
					
					//SECONDARY (Image Card)
					$secondary_html .= '';
					
					$classes = '';
					if($cta_layout_type == 'layout_style_left'){
						$classes .= 'el-col-small-12 el-col-medium-3 el-col-medium-offset-2 ';
					}
					else if($cta_layout_type == 'layout_style_center'){
						$classes = 'el-col-small-12 el-col-medium-4 el-col-medium-offset-4 clear';
					}
					else if($cta_layout_type == 'layout_style_right'){
						$classes .= 'el-col-small-12 el-col-medium-3 el-col-medium-offset-3 ';
					}
					
					$secondary_html .= '<section class="secondary ' . $classes .' small-margin-top-bottom-small">';
						$secondary_html .= '<div class="card small-align-center small-padding-top-small small-padding-bottom-medium">';
						if(!empty($cta_card_title)){
							$style = !empty($cta_accent_color) ? 'color:' . $cta_accent_color . ';' : '';
							$secondary_html .= '<h2 class="small  title" style="' . $style .'">' . $cta_card_title . '</h3>';
						}
						if(!empty($cta_card_subtitle)){
							$secondary_html .= '<h3 class="small subtitle">' . $cta_card_subtitle  .'</h3>';
						}
	
						if(!empty($cta_card_icon_url)){
							$secondary_html .= '<img class="image" src="' . $cta_card_icon_url .'" alt="call to action image"/>';
						}
						if(!empty($cta_card_readmore_text)){
							//have link defined
							if(!empty($cta_text_url)){
								$secondary_html .= '<a href="' . $cta_text_url . '">';
									$secondary_html .= '<div class="readmore" style="background-color: ' . $cta_accent_color . ';">' . $cta_card_readmore_text . '</div>';
								$secondary_html .= '</a>';
							}else{
								$secondary_html .= '<div class="readmore" style="background-color: ' . $cta_accent_color . ';">' . $cta_card_readmore_text . '</div>';
							}	
							
						}
						$secondary_html .= '</div>';
					$secondary_html .= '</section>';
					
					
					//Determine how layout pieces together
					//Card on left, text on right
					if($cta_layout_type == 'layout_style_left'){	
						$html .= $primary_html;
						$html .= $secondary_html;
					}
					//Card and text in center
					else if($cta_layout_type == 'layout_style_center'){
						$html .= $primary_html;
						$html .= $secondary_html;
					}
					//Card on right, text on left
					else if($cta_layout_type == 'layout_style_right'){	
						$html .= $secondary_html;
						$html .= $primary_html;
					}
					
					
				$html .= '</article>';
			$html .= '</div>';
			
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
 $el_call_to_action = el_call_to_action::getInstance();
 


?>