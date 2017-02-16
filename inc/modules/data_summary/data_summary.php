<?php
/*
 * Data Summary 
 * Content type to create new data summary elements, useful to showcase numbers and labels in different styles
 */

 class el_data_summary extends el_content_type{
 	
	private static $instance = null;
 	
	//post type args
 	private $post_type_args = array(
		'post_type_name'		=> 'data_summary',
		'post_type_single_name'	=> 'Data Summary',
		'post_type_plural_name'	=> 'Data Summaries',
		'labels'				=> array(
			'menu_name'				=> 'Data Summaries'
		),
		'args'					=> array(
			'menu_icon'				=> 'dashicons-chart-bar',
			'public'				=> false,
			'supports'				=> array('title'),
			'exclude_from_search'	=> true
		)
	);
	
	//meta boxes
	private $meta_box_args = array(
		array(
			'id'			=> 'data_summary_header_metabox',
			'title'			=> 'Data Summary Header',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'This information is displayed above your data summary, an introduction to what is being displayed'
			)
		),
		array(
			'id'			=> 'data_summary_content_metabox',
			'title'			=> 'Data Summary Content',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'The following settings determine how your data summary will be displayed.'
			)
		)
	);
	
	//meta field elements
	private $meta_field_args = array(
		//header fields
		array(
			'id'			=> 'data_title',
			'title'			=> 'Title',
			'description'	=> 'Large H1 title text to be displayed above the data summary',
			'type'			=> 'text',
			'meta_box_id'	=> 'data_summary_header_metabox'
		),
		array(
			'id'			=> 'data_title_color',
			'title'			=> 'Title Color',
			'description'	=> 'Color used for the title, if not set will default to the theme color for H tags',
			'type'			=> 'color',
			'meta_box_id'	=> 'data_summary_header_metabox'
		),
		array(
			'id'			=> 'data_subtitle',
			'title'			=> 'Subtitle',
			'description'	=> 'Secondary title text to be displayed below the main title, displayed as a H3 element',
			'type'			=> 'text',
			'meta_box_id'	=> 'data_summary_header_metabox'
		),
		array(
			'id'			=> 'data_subtitle_color',
			'title'			=> 'Subtitle Color',
			'description'	=> 'Color used for the suibtitle, if not set will default to the theme color for H tags',
			'type'			=> 'color',
			'meta_box_id'	=> 'data_summary_header_metabox'
		),
		//main content
		array(
			'id'			=> 'data_type',
			'title'			=> 'Summary Display Type',
			'description'	=> 'Dictates the layout for the data summary. By Default (standard) your data will appear horizontally in rows. Choose the style you want',
			'type'			=> 'select',
			'meta_box_id'	=> 'data_summary_content_metabox',
			'args'			=> array(
				'options'		=> array(
					array(
						'id'	=> 'standard',
						'title'	=> 'Standard Listing'
					),
					array(
						'id'	=> 'circle',
						'title'	=> 'Circular Background'
					),
				
				)
			)
		),
		array(
			'id'			=> 'data_shape_color',
			'title'			=> 'Shape Color',
			'description'	=> 'If the \'Circular\' layout has been chosen a circular shape will appear behind the data summary. You can choose it\'s color here',
			'type'			=> 'color',
			'meta_box_id'	=> 'data_summary_content_metabox'
		),
		array(
			'id'			=> 'data_color',
			'title'			=> 'Text Colour',
			'description'	=> 'Defines the text colour of the elements below. By default they will use the website\'s body copy colour, else you can manually override it here',
			'type'			=> 'color',
			'meta_box_id'	=> 'data_summary_content_metabox'
		),
		
		//First Fragment
		array(
			'type'			=> 'line-break-title',
			'title'			=> 'Data Fragment 1',
			'description'	=> 'Enter information about the fragment below',
			'meta_box_id'	=> 'data_summary_content_metabox'
		),
		array(
			'id'			=> 'data_content_number_1',
			'title'			=> 'Value',
			'description'	=> 'Value for this fragment out of 100',
			'type'			=> 'number',
			'meta_box_id'	=> 'data_summary_content_metabox'
		),
		array(
			'id'			=> 'data_content_title_1',
			'title'			=> 'Title',
			'description'	=> 'Text displayed along side the fragment value, used to explain the main value',
			'type'			=> 'text',
			'meta_box_id'	=> 'data_summary_content_metabox'
		),
		//Second fragment
			array(
			'type'			=> 'line-break-title',
			'title'			=> 'Data Fragment 2',
			'description'	=> 'Enter information about the fragment below',
			'meta_box_id'	=> 'data_summary_content_metabox'
		),
		array(
			'id'			=> 'data_content_number_2',
			'title'			=> 'Value',
			'description'	=> 'Value for this fragment out of 100',
			'type'			=> 'number',
			'meta_box_id'	=> 'data_summary_content_metabox'
		),
		array(
			'id'			=> 'data_content_title_2',
			'title'			=> 'Title',
			'description'	=> 'Text displayed along side the fragment value, used to explain the main value',
			'type'			=> 'text',
			'meta_box_id'	=> 'data_summary_content_metabox'
		),
		//Third fragment
			array(
			'type'			=> 'line-break-title',
			'title'			=> 'Data Fragment 3',
			'description'	=> 'Enter information about the fragment below',
			'meta_box_id'	=> 'data_summary_content_metabox'
		),
		array(
			'id'			=> 'data_content_number_3',
			'title'			=> 'Value',
			'description'	=> 'Value for this fragment out of 100',
			'type'			=> 'number',
			'meta_box_id'	=> 'data_summary_content_metabox'
		),
		array(
			'id'			=> 'data_content_title_3',
			'title'			=> 'Title',
			'description'	=> 'Text displayed along side the fragment value, used to explain the main value',
			'type'			=> 'text',
			'meta_box_id'	=> 'data_summary_content_metabox'
		),
		//Fourth fragment
			array(
			'type'			=> 'line-break-title',
			'title'			=> 'Data Fragment 4',
			'description'	=> 'Enter information about the fragment below',
			'meta_box_id'	=> 'data_summary_content_metabox'
		),
		array(
			'id'			=> 'data_content_number_4',
			'title'			=> 'Value',
			'description'	=> 'Value for this fragment out of 100',
			'type'			=> 'number',
			'meta_box_id'	=> 'data_summary_content_metabox'
		),
		array(
			'id'			=> 'data_content_title_4',
			'title'			=> 'Title',
			'description'	=> 'Text displayed along side the fragment value, used to explain the main value',
			'type'			=> 'text',
			'meta_box_id'	=> 'data_summary_content_metabox'
		),
		
		
		
	);
	
	
	//constrcutor
	public function __construct(){
		
		parent::__construct(
			$this->post_type_args,
			$this->meta_box_args,
			$this->meta_field_args
		);
		
		//action hooks
		add_action('el_display_data_summary', array($this, 'el_display_data_summary'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts_and_styles'));
	}
	
	public function enqueue_public_scripts_and_styles(){
		wp_enqueue_script( 'data_summary_script', get_stylesheet_directory_uri() . '/inc/modules/data_summary/data_summary.js' , array('jquery') );
	}
	
	//given a single data summary ID, display the summary
	public static function el_display_data_summary($data_summary_id){
			
		$instance = self::getInstance();
		$html = '';
		
		$html .= $instance::get_data_summary_html($data_summary_id);
		
		echo $html;
	}
	
	//given a single data summary ID, get the HTML output for it
	public static function get_data_summary_html($data_summary_id){
			
		$instance = self::getInstance();
		$html = '';
		
		$post = get_post($data_summary_id);
		if($post){
			//header info
			$data_title = get_post_meta($post->ID, 'data_title', true);
			$data_title_color = get_post_meta($post->ID, 'data_title_color', true);
			$data_subtitle = get_post_meta($post->ID, 'data_subtitle', true);
			$data_subtitle_color = get_post_meta($post->ID, 'data_subtitle_color', true);
			$data_type = get_post_meta($post->ID, 'data_type', true);
			$data_color = get_post_meta($post->ID, 'data_color', true);
			$data_shape_color = get_post_meta($post->ID,'data_shape_color', true);
			
			//first fragment data
			$data_content_number_1 = get_post_meta($post->ID, 'data_content_number_1', true);
			$data_content_title_1 = get_post_meta($post->ID, 'data_content_title_1', true);
			//second fragment data
			$data_content_number_2 = get_post_meta($post->ID, 'data_content_number_2', true);
			$data_content_title_2 = get_post_meta($post->ID, 'data_content_title_2', true);
			//third fragment data
			$data_content_number_3 = get_post_meta($post->ID, 'data_content_number_3', true);
			$data_content_title_3 = get_post_meta($post->ID, 'data_content_title_3', true);
			//fourth fragment data
			$data_content_number_4 = get_post_meta($post->ID, 'data_content_number_4', true);
			$data_content_title_4 = get_post_meta($post->ID, 'data_content_title_4', true);
			
			
			//OUTPUT
			$html .= '<article class="el-row data-summary animation-container small-margin-top-bottom-small medium-margin-top-bottom-large">';
				
				//header section
				if(!empty($data_title) || !empty($data_subtitle)){
					$html .= '<section class="el-col-small-12 el-col-medium-8 el-col-medium-offset-2 summary-header">';
						if(!empty($data_title)){
							$style = (!empty($data_title_color)) ? 'color: ' . $data_title_color . ';' : '';
							$html .= '<h1 class="big fat small-align-center" style="' . $style . '">' . $data_title . '</h1>';
						}
						if(!empty($data_subtitle)){
							$style = (!empty($data_subtitle_color)) ? 'color: ' . $data_subtitle_color . ';' : '';
							$html .= '<h3 class="small-align-center" style="' . $style .'">' . $data_subtitle . '</h3>';
						}
					$html .= '</section>';
				}
				//main content
				$html .= '<section class="el-col-small-12 el-col-medium-8 el-col-medium-offset-2 medium-padding-top-bottom-large summary-content">';
					
					
					//fragment wrapper
					$style = !empty($data_color) ? 'color: ' . $data_color . ';' : '';
					$html .= '<div class="fragments medium-padding-top-bottom-large" style="' . $style .'">';
						//Shape (based on type)
						if($data_type == 'circle'){
							$style = !empty($data_shape_color) ? 'background-color: ' . $data_shape_color . ';' : '';
							$html .= '<div class="shape" style="' . $style . '"></div>';
						}
					
						//fragment 1
						if(!empty($data_content_number_1) || !empty($data_content_title_1)){
							$html .= '<div class="fragment clear">';
								if(!empty($data_content_number_1)){
									$html .= '<div class="el-col-small-12 el-col-medium-4 small-align-center medium-align-right"><div class="value">' . $data_content_number_1 . '</div></div>';
								}
								if(!empty($data_content_title_1)){
									$html .= '<div class="el-col-small-12 el-col-medium-8 small-align-center medium-align-left"><div class="content">' . $data_content_title_1 . '</div></div>';
								}
							$html .= '</div>';
						}
						//fragment 2
						if(!empty($data_content_number_2) || !empty($data_content_title_2)){
							$html .= '<div class="fragment clear">';
								if(!empty($data_content_number_2)){
									$html .= '<div class="el-col-small-12 el-col-medium-4 small-align-center medium-align-right"><div class="value">' . $data_content_number_2 . '</div></div>';
								}
								if(!empty($data_content_title_2)){
									$html .= '<div class="el-col-small-12 el-col-medium-8 small-align-center medium-align-left"><div class="content">' . $data_content_title_2 . '</div></div>';
								}
							$html .= '</div>';
						}
						//fragment 3
						if(!empty($data_content_number_1) || !empty($data_content_title_3)){
							$html .= '<div class="fragment clear">';
								if(!empty($data_content_number_3)){
									$html .= '<div class="el-col-small-12 el-col-medium-4 small-align-center medium-align-right"><div class="value">' . $data_content_number_3 . '</div></div>';
								}
								if(!empty($data_content_title_3)){
									$html .= '<div class="el-col-small-12 el-col-medium-8 small-align-center medium-align-left"><div class="content">' . $data_content_title_3 . '</div></div>';
								}
							$html .= '</div>';
						}
						//fragment 4
						if(!empty($data_content_number_4) || !empty($data_content_title_4)){
							$html .= '<div class="fragment clear">';
								if(!empty($data_content_number_4)){
									$html .= '<div class="el-col-small-12 el-col-medium-4 small-align-center medium-align-right"><div class="value">' . $data_content_number_4 . '</div></div>';
								}
								if(!empty($data_content_title_4)){
									$html .= '<div class="el-col-small-12 el-col-medium-8 small-align-center medium-align-left"><div class="content">' . $data_content_title_4 . '</div></div>';
								}
							$html .= '</div>';
						}
					$html .= '</div>';
				$html .= '</secton>';
			
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
 $el_data_summary = el_data_summary::getInstance();

?>