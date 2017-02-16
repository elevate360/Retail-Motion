<?php
/*
 * Testimonails
 * Content type for outlining testimonials.
 * - Relate testimonials to single 'portfolios'
 * - Rotator of applicable testimonials
 */

 class el_testimonials extends el_content_type{
 	
	private static $instance = null;
	
	//post type args
 	private $post_type_args = array(
		'post_type_name'		=> 'testimonial',
		'post_type_single_name'	=> 'Testimonial',
		'post_type_plural_name'	=> 'Testimonials',
		'labels'				=> array(
			'menu_name'				=> 'Testimonials'
		),
		'args'					=> array(
			'menu_icon'				=> 'dashicons-format-status'
		)
	);
	

	public function __construct(){
		 
		 //call parent constrcutor 
		 parent::__construct(
		 	$this->post_type_args
		 );
		 
		 //action hook to display recent testimonials
		 add_action('el_display_recent_testimonial_slider', array($this, 'el_display_recent_testimonial_slider'));

	}

	//displays a testimonials slider of recent testimonials
	public static function el_display_recent_testimonial_slider($passed_args = array()){
		
		$instance = self::getInstance();
		$html = '';
	
		$args = array(
			'post_type'		=> 'testimonial',
			'posts_per_page'=> 4,
			'orderby'		=> 'date',
			'order'			=> 'ASC',
			'post_status'	=> 'publish'
		);
		$posts = get_posts($args);
		if($posts){
			//output
			$html .= '<article class="testimonial-slider animation-container el-row">';
				$html .= '<div class="el-col-small-12 small-align-center">';
					$html .= '<h1 class="title big fat">Recent Testimonials</h1>';
				$html .= '</div>';
				//slider
				$html .= '<div class="flexslider slider fade">';
					$html .= '<div class="slides">';
					foreach($posts as $post){
						//generate the testimonial single html
						$html .= $instance::get_single_testimonial_slide_html($post->ID);
					}
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</article>';
	
		}

		echo $html;

	}
	
	//generates the markup for a single testimonial slide (to be included in a slider)
	public static function get_single_testimonial_slide_html($testimonial_id){
		
		$instance = self::getInstance();
		$html = '';
		
		//Generate output
		$testimonial = get_post($testimonial_id);
		$testimonial_title = apply_filters('the_title', $testimonial->post_title);
		$testimonial_permalink = get_permalink($testimonial->ID);
		$testimonial_excerpt = $testimonial->post_excerpt;
		if(!empty($testimonial_excerpt)){
			$testimonial_excerpt = wp_trim_words($testimonial_excerpt, 25 , '...');
		}
		$testimonial_content = apply_filters('the_content', $testimonial->post_content); 
		if(!empty($testimonial_content)){
			$testimonial_content = wp_strip_all_tags(strip_shortcodes($testimonial_content)); 
		}
		
		
		
		//featured image
		$testimonial_thumbnail_id = has_post_thumbnail($testimonial->ID) ? get_post_thumbnail_id($testimonial->ID) : '';
		$testimonial_image_url = '';
		if(!empty($testimonial_thumbnail_id)){
			$post_attachment = get_post($testimonial_thumbnail_id);
			if($post_attachment->post_mime_type == 'image/svg+xml'){
				$testimonial_image_url = $post_attachment->guid;
			}else{
				$testimonial_image_url = wp_get_attachment_image_src($testimonial_thumbnail_id, 'medium', false)[0];
			}
		}
		
		//testimonial content
		$html .= '<div class="testimonial slide animation-container el-row">';
			//left quote
			$html .= '<div class="left-quote el-col-small-2 small-align-center">';
				$html .= '<div class="quote"></div>';
			$html .= '</div>';
			//main testimonial element
			$html .= '<div class="inner el-col-small-8 small-align-center">';
				//display image
				if(!empty($testimonial_image_url)){
					$html .= '<div class="el-row nested">';
						$html .= '<div class="el-col-small-4 el-col-medium-2 el-col-small-offset-4 el-col-medium-offset-5">';
							$html .= '<div class="background-wrap small-aspect-1-1">';
								$html .= '<div class="background-image " style="background-image:url(' . $testimonial_image_url .');"></div>';
							$html .= '</div>';
						$html .= '</div>';
					$html .= '</div>';
				}
				
				//display title
				$html .= '<h2 class="title fat">' . $testimonial_title . '</h2>';
				//display content
				//var_dump($testimonial_excerpt);
				
				if(!empty($testimonial_excerpt)){
					$html .= '<p class="content">' . $testimonial_excerpt . '</p>';
				}else{

					if(!empty($testimonial_content)){
						$testimonial_content = wp_trim_words($testimonial_content, 20, '...');
						$html .= '<p class="content">' . $testimonial_content . '</p>';
					}
				}
				//display link
				//$html .= '<a class="button black" href="' . $testimonial_permalink . '" title="Find out more about this testimonial">Read More</a>';
			$html .= '</div>';
			//right quote
			$html .= '<div class="right-quote el-col-small-2 small-align-center">';
				$html .= '<div class="quote"></div>';
			$html .= '</div>';
			
			
		$html .= '</div>';
		

		return $html;
	}
	
	
	//given an ID of a portfolio, generate the markup for the testimonial slider
	public static function get_portfolio_testimonial_slider_html($post_id){
			
		$instance = self::getInstance();
		$html = '';
		
		//get portfolio 
		$post = get_post($post_id);
		if($post){
			
			$portfolio_single_testimonials = get_post_meta($post->ID, 'portfolio_single_testimonials', true);
			if(!empty($portfolio_single_testimonials)){
				$portfolio_single_testimonials = json_decode($portfolio_single_testimonials);
			}

			//output
			$html .= '<article class="testimonial-slider el-row">';
				$html .= '<div class="el-col-small-12 small-align-center">';
					$html .= '<h1 class="title fat">Testimonials</h1>';
				$html .= '</div>';
				//slider
				$html .= '<div class="flexslider slider fade">';
					$html .= '<div class="slides">';
					foreach($portfolio_single_testimonials as $testimonial_id){
						//generate the testimonial single html
						$html .= $instance::get_single_testimonial_slide_html($testimonial_id);
					}

					$html .= '</div>';
				$html .= '</div>';
			$html .= '</article>';
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
 $el_testimonials = el_testimonials::getInstance();
 


?>