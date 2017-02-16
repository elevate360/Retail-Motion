<?php
/*
 * Hero Sections
 * Content type to create hero / featured sections. Used to showcase a title, subtitle 
 */

 class hero_section extends el_content_type{
 	
	private static $instance = null;
 	
	//post type args
 	private $post_type_args = array(
		'post_type_name'		=> 'hero_section',
		'post_type_single_name'	=> 'Hero Section',
		'post_type_plural_name'	=> 'Hero Sections',
		'labels'				=> array(
			'menu_name'				=> 'Hero Sections'
		),
		'args'					=> array(
			'menu_icon'				=> 'dashicons-editor-insertmore',
			'public'				=> false,
			'has_archive'			=> false,
			'supports'				=> array('title')
		)
	);
	
	//meta boxes
	private $meta_box_args = array(
		array(
			'id'			=> 'hero_section_metabox',
			'title'			=> 'Hero Section Information',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'Use the information below to define how your hero section will look'
			)
		)
	);
	
	//meta field elements
	private $meta_field_args = array(
		
		
		array(
			'id'			=> 'hero_background_color',
			'title'			=> 'Background Color',
			'description'	=> 'Background color for the hero section.',
			'type'			=> 'color',
			'meta_box_id'	=> 'hero_section_metabox'
		),
		array(
			'id'			=> 'hero_text_color',
			'title'			=> 'Text Color',
			'description'	=> 'Color for the main hero text',
			'type'			=> 'color',
			'meta_box_id'	=> 'hero_section_metabox'
		),
		array(
			'id'			=> 'hero_button_text',
			'title'			=> 'Readmore Text',
			'description'	=> 'Text to be displayed inside a readmore button inline with the primary text',
			'type'			=> 'text',
			'meta_box_id'	=> 'hero_section_metabox'
		),	
		array(
			'id'			=> 'hero_text_primary',
			'title'			=> 'Primary Text',
			'description'	=> 'Primary text used in the hero section',
			'type'			=> 'text',
			'meta_box_id'	=> 'hero_section_metabox'
		),
		array(
			'id'			=> 'hero_icon_image',
			'title'			=> 'Icon',
			'description'	=> 'Choose an optional image to display on your section',
			'type'			=> 'upload-image',
			'meta_box_id'	=> 'hero_section_metabox'
		),
		array(
			'id'			=> 'hero_url',
			'title'			=> 'URL',
			'description'	=> 'Determine where this hero section will go to when selected',
			'type'			=> 'text',
			'meta_box_id'	=> 'hero_section_metabox'
		)
		
	);
	
	
	//constrcutor
	public function __construct(){
		
		parent::__construct(
			$this->post_type_args,
			$this->meta_box_args,
			$this->meta_field_args
		);
		
		//action hooks
		add_action('el_display_hero_sections', array($this, 'el_display_hero_sections'), 10, 1);

	}
	
	
	//given a single object, display it's applicable hero sections
	//Displays on either posts or terms
	public static function el_display_hero_sections($object){
			
		$instance = self::getInstance();
		$html = '';
		
		$hero_section_elements = '';
		if($object instanceof WP_Post){
			$hero_section_elements = get_post_meta($object->ID, 'hero_section_elements', true);
		}else if($object instanceof WP_Term){
			$hero_section_elements = get_term_meta($object->term_id, 'category-hero-element', true);
		}
		

		if(!empty($hero_section_elements)){
			$hero_section_elements = json_decode($hero_section_elements);

			$html .= '<div class="hero-sections small-align-center">';
			foreach($hero_section_elements as $hero_section_id){
				$html .= $instance::get_hero_section_html($hero_section_id);
			}
			$html .= '</div>';

		}
	
		echo $html;
		
	}
	
	//given a single hero section ID, get the HTML output for it
	public static function get_hero_section_html($hero_id){
			
		$instance = self::getInstance();
		$html = '';
		
		$post = get_post($hero_id);
		if($post){
			
			//header info
			$hero_background_color = get_post_meta($post->ID, 'hero_background_color', true);
			$hero_text_color = get_post_meta($post->ID, 'hero_text_color', true);
			$hero_button_text = get_post_meta($post->ID, 'hero_button_text', true);
			$hero_text_primary = get_post_meta($post->ID, 'hero_text_primary', true);		
			$hero_icon_image = get_post_meta($post->ID, 'hero_icon_image', true);
			$hero_url = get_post_meta($post->ID, 'hero_url', true);
			
			//get image
			if(!empty($hero_icon_image)){
				$post_attachment = get_post($hero_id);
				if($post_attachment->post_mime_type == 'image/svg+xml'){
					$hero_icon_image_url = $post_attachment->guid;
				}else{
					$hero_icon_image_url = wp_get_attachment_image_src($hero_icon_image, 'medium', false)[0];
				}
			}
			
			$style = ''; 
			if(!empty($hero_background_color)){
				$style .= 'background-color: ' . $hero_background_color . ';';
			}
			if(!empty($hero_text_color)){
				$style .= 'color: ' . $hero_text_color . ';';
			}
			

			//OUTPUT
			$html .= '<article class="el-row hero-section animation-container small-padding-top-bottom-small small-margin-top-bottom-small medium-margin-top-bottom-small">';

				$html .= '<div class="wrapper inline-block">';
				
					$html .= '<div class="inner-wrapper" style="'. $style .'">';
					
						//readmore + title section
						if(!empty($hero_button_text) || !empty($hero_text_primary)){
								
							$html .= '<h2 class="h1 big fat text-wrap inline-block small-margin-top-medium">';
							if(!empty($hero_button_text)){
								$html .= '<a class="button orange no-border medium-margin-right-small medium-margin-left-small" href="' . $hero_url . '" class="readmore">' . $hero_button_text . '</a>';
							}
							if(!empty($hero_text_primary)){
								$html .= '<span class="text">' . $hero_text_primary . '</span>';
							}
							
							$html .= '</h2>';
							
						}
						//icon
						if(!empty($hero_icon_image)){
							$html .= '<div class="image-wrap inline-block medium-align-right">';
								$html .= '<img class="medium-margin-right-small" src="' . $hero_icon_image_url . '"/>';
							$html .= '</div>';
						}
					$html .= '</div>';
					
				$html .= '</div>';
			
			$html .= '</article>';
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
 }
 $el_hero_section = hero_section::getInstance();

?>