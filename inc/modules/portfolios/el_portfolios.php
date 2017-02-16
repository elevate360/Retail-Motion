<?php
/*
 * Portfolios
 * Showcases portfolios / projects that have been completed.
 */

 class el_portfolios extends el_content_type{
 	
	private static $instance = null;
	
	//post type args
 	private $post_type_args = array(
		'post_type_name'		=> 'portfolio',
		'post_type_single_name'	=> 'portfolio',
		'post_type_plural_name'	=> 'portfolios',
		'labels'				=> array(
			'menu_name'				=> 'Portfolios'
		),
		'args'					=> array(
			'menu_icon'				=> 'dashicons-index-card',
			'supports'				=> array('title', 'excerpt')
		)
	);
	
	private $taxonomy_args = array(
		array(
			'taxonomy_name'			=> 'portfolio_category',
			'taxonomy_single_name'	=> 'Portfolio Category',
			'taxonomy_plural_name'	=> 'Portfolio Categories',
			'labels' 	=> array(
				'menu_name'  	=> 'Categories'
			),
			'args'		=> array(
				'hierarchical'	=> true
			)
		)
	);
	
	//meta boxes (several here to separate functionality)
	private $meta_box_args = array(
		array(
			'id'			=> 'portfolio_archive_box',
			'title'			=> 'Portfolio Archive Information',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'These settings are used to format your porfolio when it\'s displayed on the archive / listing page. The first card will display the project logo, title and categories. Additional photos will be displayed afterwards (based on the archive type used below)'
			)
		),
		array(
			'id'			=> 'portfolio_fullpage_content_box',
			'title'			=> 'Portfolio Full Page Content',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'Use the below editor when you want to display a single column of content on your single portfolio page'
			)
		),
		
		
		array(
			'id'			=> 'portfolio_single_universal_box',
			'title'			=> 'Portfolio Single - Universal',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'Universal options and settings relating to the single portfolio page'
			)
		),
		array(
			'id'			=> 'portfolio_single_brief_box',
			'title'			=> 'Portfolio Single - The Brief',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'Basic information about the portfolio. Displayed at the top of the single portfolio page'
			)
		),
		array(
			'id'			=> 'portfolio_single_insight_box',
			'title'			=> 'Portfolio Single - The Insight',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'Combination of image on the left and associated text on the right. Displayed under \'The Brief\' section'
			)
		),
		array(
			'id'			=> 'portfolio_single_solution_box',
			'title'			=> 'Portfolio Single - The Solution',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'Series of info cards, displaying title and text summary. Displayed under \'The Insight\' section'
			)
		),
		array(
			'id'			=> 'portfolio_single_gallery_box',
			'title'			=> 'Portfolio Single - Gallery Slider',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'Displays an optional image slider to showcase images from the project. Displayed under \'The Solution\' section'
			)
		),
		array(
			'id'			=> 'portfolio_single_testimonail_box',
			'title'			=> 'Portfolio Single - Testimonials',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'Displays an optional rotating slider of testimonials related to this project. Displayed near the bottom of the page'
			)
		),
		array(
			'id'			=> 'portfolio_card_box',
			'title'			=> 'Portfolio Card Information',
			'context'		=> 'side',
			'args'			=> array(
				'description' => 'This information is used when your portfolio is being displayed as a quick portfolio card (Often seen at the bottom of pages / accessed via a widget)'
			)
		),
	
	);
	
	//meta field args
	private $meta_fields_args = array(
		//archive fields
		array(
			'id'			=> 'portfolio_archive_type',
			'title'			=> 'Portfolio Archive Type',
			'description'	=> 'Select the layout style used when on the archive page. This affects how your images are outputted',
			'type'			=> 'select',
			'meta_box_id'	=> 'portfolio_archive_box',
			'args'			=> array(
				'options'		=> array(
					array(
						'id'	=> 'style_a',
						'title' => 'Style A: (1/3 + 2/3) + (2/3 + 1/3)'
					),
					array(
						'id'	=> 'style_b',
						'title'	=> 'Style B: (1/3 + 2/3) + (1/2 + 1/2)'
					),
					array(
						'id'	=> 'style_c',
						'title' => 'Style C: (1/3 + 1/3 + 1/3)'
					)
				)
			)
		),
		array(
			'id'			=> 'portfolio_archive_logo',
			'title'			=> 'Portfolio Archive (First Card Logo)',
			'description'	=> 'Logo (preferably white) that will be displayed on the first card when viewing this portfolio on the listing / archive page',
			'type'			=> 'upload-image',
			'meta_box_id'	=> 'portfolio_archive_box'
		),
		array(
			'id'			=> 'portfolio_archive_title',
			'title'			=> 'Portfolio Archive (First Card Title)',
			'description'	=> 'Title that will be displayed on the first card when viewing this portfolio on the listing / archive page',
			'type'			=> 'text',
			'meta_box_id'	=> 'portfolio_archive_box'
		),
		array(
			'id'			=> 'portfolio_archive_background_color',
			'title'			=> 'Portfolio Archive (First Card Background Color)',
			'description'	=> 'Background color for the first card when viewing this portfolio on the listing / archive page',
			'type'			=> 'color',
			'meta_box_id'	=> 'portfolio_archive_box',
			'args'			=> array(
				'default-color'	=> '#f28d00'
			)
		),
		array(
			'id'			=> 'portfolio_archive_text_color',
			'title'			=> 'Portfolio Archive (First Card Text Color)',
			'description'	=> 'Text color for the first card when viewing this portfolio on the listing / archive page',
			'type'			=> 'color',
			'meta_box_id'	=> 'portfolio_archive_box',
			'args'			=> array(
				'default-color'	=> '#fff'
			)
		),
		array(
			'id'			=> 'portfolio_archive_images',
			'title'			=> 'Portfolio Archive Images (Displayed after first card)',
			'description'	=> 'Series of images that will be displayed when viewing this portfolio on the listing / archive page',
			'type'			=> 'upload-multi-image',
			'meta_box_id'	=> 'portfolio_archive_box'
		),
		array(
			'id'			=> 'portfolio_archive_order',
			'title'			=> 'Portfolio Archive Order',
			'description'	=> 'Relation of this portfolio to other portfolios when view on the listing / archive page (lower numbers come first)',
			'type'			=> 'number',
			'meta_box_id'	=> 'portfolio_archive_box'
		),
		//Full page content fields
		array(
			'id'			=> 'portfolio_full_page_content',
			'title'			=> 'Content',
			'description'	=> 'Add your full page content here to display near the top of the page ',
			'type'			=> 'editor',
			'meta_box_id'	=> 'portfolio_fullpage_content_box'	
		),
		//universal fields
		array(
			'id'			=> 'portfolio_single_background_image',
			'title'			=> 'Portfolio Background Image',
			'description'	=> 'Background image used when viewing the single portfolio page. Use a subtle repeating image for optimum effect',
			'type'			=> 'upload-image',
			'meta_box_id'	=> 'portfolio_single_universal_box'
		),
		//The brief fields
		array(
			'id'			=> 'portfolio_single_brief_content',
			'title'			=> 'Content',
			'description'	=> 'Main content for this section',
			'type'			=> 'editor',
			'meta_box_id'	=> 'portfolio_single_brief_box'
		),
		//The Insight fields
		array(
			'id'			=> 'portfolio_single_insight_image',
			'title'			=> 'Image',
			'description'	=> 'Image displayed for this section (displayed on the left)',
			'type'			=> 'upload-image',
			'meta_box_id'	=> 'portfolio_single_insight_box'
		),
		array(
			'id'			=> 'portfolio_single_insight_content',
			'title'			=> 'Content',
			'description'	=> 'Main content for this section',
			'type'			=> 'editor',
			'meta_box_id'	=> 'portfolio_single_insight_box'
		),
		//The solution fields
		array(
			'id'			=> 'portfolio_single_solution_content',
			'title'			=> 'Content',
			'description'	=> 'Summary content, displayed below this sections title',
			'type'			=> 'editor',
			'meta_box_id'	=> 'portfolio_single_solution_box'
		),
		array(
			'id'			=> 'portfolio_single_solution_services',
			'title'			=> 'Applicable Services',
			'description'	=> 'Select the services that were applicable to this portfolio. The title and content will be automatically pulled in',
			'type'			=> 'related-posts',
			'meta_box_id'	=> 'portfolio_single_solution_box',
			'args'			=> array(
				'related_post_type_name'	=> 'services',
				'order_by_taxonomies'		=> true
			)
		),
		//Galllery
		array(
			'id'			=> 'portfolio_single_gallery_images',
			'title'			=> 'Gallery Images',
			'description'	=> 'Images that will be turned into a simple gallery slider when viewing the single portfolio page',
			'type'			=> 'upload-multi-image',
			'meta_box_id'	=> 'portfolio_single_gallery_box'
		
		),
		//Testimonials
		array(
			'id'			=> 'portfolio_single_testimonials',
			'title'			=> 'Related Testimonials',
			'description'	=> 'Select testimonials that are applicable to this portfolio. These will be displayed as a rotator when viewing the single page',
			'type'			=> 'related-posts',
			'meta_box_id'	=> 'portfolio_single_testimonail_box',
			'args'			=> array(
				'related_post_type_name'	=> 'testimonial'
			)
		),
		//Portfolio card fields
		array(
			'id'			=> 'portfolio_card_image',
			'title'			=> 'Portfolio Card Background Image',
			'description'	=> 'Background image used when this portfolio is being displayed as a quick link card',
			'type'			=> 'upload-image',
			'meta_box_id'	=> 'portfolio_card_box'
		),
		array(
			'id'			=> 'portfolio_card_excerpt',
			'title'			=> 'Portfolio Card Excerpt',
			'description'	=> 'Texted displayed on top of the portfolio when it\'s displayed in it\'s quick link card form',
			'type'			=> 'textarea',
			'meta_box_id'	=> 'portfolio_card_box'
		),
		
		
		
		
	
	);
	

	
	public function __construct(){
		 
		 //call parent constrcutor 
		 parent::__construct(
		 	$this->post_type_args,
		 	$this->meta_box_args,
		 	$this->meta_fields_args,
		 	$this->taxonomy_args
		 );
		 
		 //Main listing action
		 add_action('el_display_portfolio_listing', array($this, 'el_display_portfolio_listing'));
		 add_action('el_display_portfolio_summary_content', array($this, 'el_display_portfolio_summary_content'));
		 //Actions for displaying sections for a portfolio
		 add_action('el_display_portfolio_brief', array($this, 'el_display_portfolio_brief'), 10, 1);
		 add_action('el_display_portfolio_insight', array($this, 'el_display_portfolio_insight'), 10, 1);
		 add_action('el_display_portfolio_solution', array($this, 'el_display_portfolio_solution'), 10, 1);
		 add_action('el_display_portfolio_gallery_slider', array($this, 'el_display_portfolio_gallery_slider'), 10, 1);
		 add_action('el_display_portfolio_testimonial_slider', array($this, 'el_display_portfolio_testimonial_slider'), 10, 1);
		 //Action for displaying a tiled grid of portfolio items, generally at the bottom of pages
		 add_action('el_display_portfolio_tiles', array($this, 'el_display_portfolio_tiles'), 10, 1);
	}
	
	
	//Outputs the main summary content at the top of the portolio (used when we just need a single column of content)
	public static function el_display_portfolio_summary_content($post){
		$instance = self::getInstance();
		$html = '';
		
		$portfolio_full_page_content = get_post_meta($post->ID,'portfolio_full_page_content', true);
		if(!empty($portfolio_full_page_content)){
				
			$html .= '<div class="entry-content animation-container el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-align-center small-margin-bottom-medium ">';
				$html .= '<div class="content">' . apply_filters('the_content', $portfolio_full_page_content) . '</div>';
			$html .= '</div>';
		}
		
		echo $html;
	}
	
	//outputs 'the brief' section for a portfolio
	public static function el_display_portfolio_brief($post){
		$instance = self::getInstance();
		$html = '';
		
		$portfolio_single_brief_content = get_post_meta($post->ID, 'portfolio_single_brief_content', true);
		
		//output
		if(!empty($portfolio_single_brief_content)){
			
			$html .= '<div class="portfolio-brief entry-content  animation-container el-col-small-12 el-col-medium-8 el-col-medium-offset-2 small-align-center small-margin-bottom-medium ">';
				$html .= '<h1 class="fat">The Brief </h1>';
				if(!empty($portfolio_single_brief_content)){
					$html .= '<div class="content">' . apply_filters('the_content', $portfolio_single_brief_content) . '</div>';
				}
			$html .= '</div>';
		}
		
		echo $html;
	}
	
	//outputs 'the insight' section for a portfolio.
	public static function el_display_portfolio_insight($post){
		$instance = self::getInstance();
		$html = '';
		
		$portfolio_single_insight_content = get_post_meta($post->ID, 'portfolio_single_insight_content', true);
		$portfolio_single_insight_image = get_post_meta($post->ID, 'portfolio_single_insight_image', true);
		$image_url = '';
		if(!empty($portfolio_single_insight_image)){
			$post_attachment = get_post($portfolio_single_insight_image);
			if($post_attachment->post_mime_type == 'image/svg+xml'){
				$image_url = $post_attachment->guid;
			}else{
				$image_url = wp_get_attachment_image_src($portfolio_single_insight_image, 'large', false)[0];
			}
		}
		
	
		//display only if we have this section completed
		if(!empty($portfolio_single_insight_content) || !empty($portfolio_single_insight_image)){
		
			$style = (!empty($image_url)) ? 'background-image: url(' . $image_url . ');' : '';
			$html .= '<div class="portfolio-insight animation-container entry-content el-col-small-12  small-margin-bottom-medium medium-padding-top-bottom-xx-large" style="' . $style .'">';

				$html .= '<div class="content el-row nested">';
					$html .= '<div class="inner el-col-small-12 el-col-medium-6 el-col-medium-offset-6">';
						$html .= '<h1 class="title fat">The Insight </h1>';
						if(!empty($portfolio_single_insight_content)){
							$html .= apply_filters('the_content', $portfolio_single_insight_content);
						}
					$html .= '</div>';
				$html .= '</div>';
		
			$html .= '</div>';
		}
		
		echo $html;
	}
	
	//outputs 'the solution' section for a portfolio (displays services cards)
	public static function el_display_portfolio_solution($post){
		$instance = self::getInstance();
		$html = '';
		
	
		$portfolio_single_solution_content = get_post_meta($post->ID, 'portfolio_single_solution_content', true);
		$portfolio_single_solution_services = get_post_meta($post->ID, 'portfolio_single_solution_services', true);
		if(!empty($portfolio_single_solution_services)){
			$portfolio_single_solution_services = json_decode($portfolio_single_solution_services);
		}
		
		//output only if we have content
		if(!empty($portfolio_single_solution_content) || !empty($portfolio_single_solution_services)){
		
			$html .= '<div class="portfolio-solutions animation-container entry-content el-col-small-12 small-align-center small-margin-bottom-medium">';
				//header section
				$html .= '<div class="el-row">';
					$html .= '<h1 class="fat el-col-small-12 el-col-medium-8 el-col-medium-offset-2"> The Solution </h1>';
					if(!empty($portfolio_single_solution_content)){
						$html .= '<div class="el-col-small-12 el-col-medium-8 el-col-medium-offset-2">';
							$html .= apply_filters('the_content', $portfolio_single_solution_content);
						$html .= '</div>';
					}
				$html .= '</div>';
			
			
				//get related services as blocks
				if(!empty($portfolio_single_solution_services)){
					$html .= '<div class="el-row services-cards equal-height-items animation-container">'; 
					
					$services_class = el_services::getInstance();
					
					foreach($portfolio_single_solution_services as $service_id){
						
						$html .= $services_class::get_single_service_card_html($service_id);
					}
					$html .= '</div>';
				}
			
			
			$html .= '</div>';
			
		
			echo $html;

		}
	}
	
	//outputs gallery slider for the portfolio
	//Displayed as a 'coursel' style slider, displaying 2-4 images
	public static function el_display_portfolio_gallery_slider($post){
			
		$instance = self::getInstance();
		$html = '';
		
		$portfolio_single_gallery_images = get_post_meta($post->ID, 'portfolio_single_gallery_images', true);
		if(!empty($portfolio_single_gallery_images)){
			$portfolio_single_gallery_images = json_decode($portfolio_single_gallery_images);
		}
		
		if(!empty($portfolio_single_gallery_images)){
			$html .= '<article class="portfolio-gallery animation-container el-col-small-12 small-margin-top-bottom-medium">';
				//title
				$html .= '<div class="el-col-small-12 small-align-center">';
					$html .= '<h1 class="fat">Gallery</h1>';
				$html .= '</div>';
				//slider 
				$html .= '<div class="flexslider slider">';
					$html .= '<div class="slides">';
					foreach($portfolio_single_gallery_images as $image_id){
						$image_url = wp_get_attachment_image_src($image_id, 'large', false)[0];
						//each image slide
						$html .= '<div class="gallery-single slide">';
							$html .= '<div class="inner small-aspect-1-2 medium-aspect-1-2">';
									$html .= '<div class="background-image" style="background-image: url(' . $image_url . ');"></div>';
							$html .= '</div>';
						$html .= '</div>';
					}
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</article>';
		}
		
		
		echo $html;
	}
	
	//outputs testimonial slier for a given portfolio item
	//used `el_testimonials` class to pull in testimonial information
	public static function el_display_portfolio_testimonial_slider($post){
			
		$instance = self::getInstance();
		$html = '';
		
		//see if we have testimonials for portfolio, if so get markup
		$portfolio_single_testimonials = get_post_meta($post->ID, 'portfolio_single_testimonials', true);
		if(!empty($portfolio_single_testimonials)){
			
			//get instance of the testimonial class
			$el_testimonials = el_testimonials::getInstance();
			//get the HTML markup for the slider 
			$html .= $el_testimonials::get_portfolio_testimonial_slider_html($post->ID);
		}
		
		echo $html;
	}
	
	
	//outputs quick links to the top X number of portfolio showcases. Displayed on various pages
	public static function el_display_portfolio_tiles($optional_args = array()){
			
		$instance = self::getInstance();
		
		$html = '';
		
		$post_args = array(
			'posts_per_page'	=> 6,
			'post_type'			=> $instance->post_type_args['post_type_name'],
			'post_status'		=> 'publish',
			'orderby'			=> 'post_date',
			'order'				=> 'DESC'
		);
		
		//update default values if needed
		if(!empty($optional_args)){
			foreach($optional_args as $key => $value){
				if(array_key_exists($key, $post_args)){
					$post_args[$key] = $value;
				}
			}
		}
		$posts = get_posts($post_args);
		if($posts){
			
			//determine what style (display type)will be used
			$display_type = '';
			if(isset($optional_args['display_type'])){
				$display_type = $optional_args['display_type'];
			}else{
				$display_type = get_theme_mod('retail_portfolio_display_type');
			}
			
			//dictates wrapper style
			if($display_type == 'tile'){
				$html .= '<article class="portfolio-tiles animation-container equal-height-items el-col-small-12  small-margin-top-bottom-medium">';
			}else if($display_type == 'card'){
				$html .= '<article class="portfolio-cards animation-container equal-height-items el-col-small-12   small-margin-top-bottom-medium">';
			}		
				//output a header section before listing (based on either passed args or theme setting defaults)
				$portfolio_title = get_theme_mod('retail_portfolio_title');
				$portfolio_subtitle = get_theme_mod('retail_portfolio_subtitle');

				if(isset($optional_args['title']) || isset($optional_args['subtitle'])){
					$html .= '<div class="el-col-small-12 small-align-center">';
					//title
					if(isset($optional_args['title']) && !empty($optional_args['title'])){
						$html .= '<h2 class="h1 big fat small-margin-top-bottom-none">' . $optional_args['title'] . '</h2>';
					}
					//subtitle
					if(isset($optional_args['subtitle']) && !empty($optional_args['subtitle'])){
						$html .= '<h3 class="">' . $optional_args['subtitle'] . '</h3>';
					}
					$html .= '</div>';
				}//check for mod defaults
				else if(!empty($portfolio_title) || !empty($portfolio_subtitle)){
					$html .= '<div class="el-col-small-12 small-align-center">';
					if(!empty($portfolio_title)){
						$html .= '<h2 class="h1 big fat small-margin-top-bottom-none">' . $portfolio_title . '</h2>';
					}
					if(!empty($portfolio_subtitle)){
						$html .= '<h3 class="">' . $portfolio_subtitle . '</h3>';
					}
					$html .= '</div>';
				}
				
					foreach($posts as $post){
						$post_title = apply_filters('the_title', $post->post_title);
						$post_permalink = get_permalink($post->ID);
						$post_card_image = get_post_meta($post->ID,'portfolio_card_image', true);
						$image_url = '';
						$post_attachment = get_post($post_card_image);
						if($post_attachment->post_mime_type == 'image/svg+xml'){
							$image_url = $post_attachment->guid;
						}else{
							$image_url = wp_get_attachment_image_src($post_card_image, 'medium', false)[0];
						}
						$post_card_excerpt = get_post_meta($post->ID,'portfolio_card_excerpt', true);
						$post_excerpt = $post->post_excerpt; 


						//var_dump($display_type);
						//output display as a tile (with content displayed on top of a background image)
						if($display_type == 'tile'){
							$html .= '<div class="portfolio portfolio-tile equal-height-item el-col-small-12 el-col-medium-4 small-margin-bottom-medium">';
								$html .= '<div class="inner small-padding-top-bottom-large small-align-center">';
									$html .= '<a href="' . $post_permalink . '" title="' . $post_title . '">';
										//background image
										if(!empty($post_card_image)){
											$html .= '<div class="background-image" style="background-image: url(' . $image_url .');"></div>';
										}
										//background overlay
										$html .= '<div class="overlay"></div>';
									
										$html .= '<h2 class="title fat inline-block small-margin-top-bottom-none">' . $post_title . '</h2>';
										if(!empty($post_card_excerpt)){
											$html .= '<p class="excerpt">' . $post_card_excerpt . '</p>';
										}else{
											if(!empty($post_excerpt)){
												$html .= '<p class="excerpt">' . $post_excerpt . '</p>';
											}
										}
									$html .= '</a>';
									//readmore
									//$html .= '<a class="button" href="' . $post_permalink . '" title="' . $post_title . '">Read More</a>';
									
								$html .= '</div>';
							$html .= '</div>';
							
						}

						//output as a card (with an image followed by content below)
						else if($display_type == 'card'){
							
							$html .= '<div class="portfolio equal-height-item portfolio-card el-col-small-12 el-col-medium-4 small-margin-bottom-medium">';
								$html .= '<div class="inner">';
								
									$html .= '<a href="' . $post_permalink . '">';
										//background image
										if(!empty($post_card_image)){
											$html .= '<div class="background-wrap small-aspect-1-2 medium-aspect-3-4">';
												$html .= '<div class="background-image" style="background-image: url(' . $image_url .');"></div>';
											$html .= '</div>';
										}
										//background overlay
										$html .= '<div class="overlay"></div>';
									
										$html .= '<div class="post-info">';
											$html .= '<h1 class="title small">' . $post_title . '</h1>';
											if(!empty($post_excerpt)){
												$html .= '<div class="excerpt small-margin-bottom-small">' . $post_excerpt . '</div>';
											}
											$html .= '<span class="button orange readmore small-margin-top-small">Read More</span>';
											
										$html .= '</div>';
									$html .= '</a>';
									
								$html .= '</div>';
							$html .= '</div>';
						}
						

					}
				$html .= '</article>';
		}
		
		
		echo $html;
		
		
	}
	
	//displays a listing of portfolios, using their listing format. Used on the portfolio archive pages
	public static function el_display_portfolio_listing(){
			
		$instance = self::getInstance();	
		$html = '';
		
		$post_args = array(
			'post_type'			=> $instance->post_type_args['post_type_name'],
			'posts_per_page'	=> -1,
			'orderby'			=> 'meta_value',
			'order'				=> 'ASC',
			'meta_key'			=> 'portfolio_archive_order',
			'post_status'		=> 'publish'
		);
		$posts = get_posts($post_args);
		
		if($posts){
			$html .= '<div class="portfolio-listing equal-height-items el-row nested inner margin-small-top-bottom-medium">';
			foreach($posts as $post){
				$html .= $instance::get_portfolio_listing_html($post);
			}
			$html .= '</div>';
		}
		
		
		
		echo $html;
		
	}
	
	//gets the HTML for the portfolio listing (displays all portfolios like an archive page)
	public static function get_portfolio_listing_html($post){
			
	
		$instance = self::getInstance();
		$html = '';
		
		$post_id = $post->ID;
		$post_title = apply_filters('the_title', $post->post_title);
		$post_content = apply_filters('the_content', $post->post_content);
		$post_permalink = get_permalink($post_id);
		$post_portfolio_images = get_post_meta($post_id, 'portfolio_archive_images', true);
		if(!empty($post_portfolio_images)){
			$post_portfolio_images = json_decode($post_portfolio_images);
		}
		$post_excerpt = $post->post_excerpt;
		
		
		$post_portfolio_background_color = get_post_meta($post_id, 'portfolio_archive_background_color', true);
		$post_portfolio_text_color = get_post_meta($post_id, 'portfolio_archive_text_color', true);
		$post_portfolio_logo = get_post_meta($post_id, 'portfolio_archive_logo', true);
		$post_portfolio_title = get_post_meta($post_id, 'portfolio_archive_title', true);
		$post_portfolio_type = get_post_meta($post_id, 'portfolio_archive_type', true);
		$post_portfolio_categories = wp_get_post_terms($post_id, 'portfolio_category');
		
		//output
		
		$html .= '<article class="portfolio animation-container equal-height-items el-col-small-12 small-margin-top-bottom-medium ' . $post_portfolio_type .'">';
		//title, button and excerpt
		$html .= '<section class="el-row small-margin-bottom-small">';
			if(!empty($post_title)){
				$html .= '<h1 class="el-col-small-12 el-col-medium-8 small-margin-top-bottom-none">' . $post_title . '</h1>';
			}
			//$html .= '<div class="el-col-small-12 el-col-medium-4 small-align-left medium-align-right">';
			//	$html .= '<a class="button orange" href="' . $post_permalink  .'">View Portfolio</a>';
			//$html .= '</div>';
			if(!empty($post_excerpt)){
				$html .= '<p class="el-col-12">' . $post_excerpt . '</p>';
			}
		$html .= '</section>';
		
		//Loop through all portfolio images
		if(!empty($post_portfolio_images)){
			
			$counter = 1;
			foreach($post_portfolio_images as $image_id){
						
				//print first row, which conists of the first block 
				if($counter == 1){
					$html .= '<div class="el-row nested row">';
						
						$html .= '<div class="fragment equal-height-item small-align-center primary small-margin-bottom-large">';
							$style = 'background-color: ' . $post_portfolio_background_color .'; color: ' . $post_portfolio_text_color . ';';
							$html .= '<div class="content-wrap" style="' . $style . '">';
								$html .= '<a href="' . $post_permalink  .'">';
									//get logo image
									if(!empty($post_portfolio_logo)){
										$image_url = '';
										$post_attachment = get_post($post_portfolio_logo);
										if($post_attachment->post_mime_type == 'image/svg+xml'){
											$image_url = $post_attachment->guid;
										}else{
											$image_url = wp_get_attachment_image_src($post_portfolio_logo, 'medium', false)[0];
										}
										
										$html .= '<img class="logo" src="' . $image_url . '"/>';
										
									}
									if(!empty($post_portfolio_title)){
										$html .= '<h2 class="title">' . $post_portfolio_title . '</h2>';
									}
									if(!empty($post_portfolio_categories)){
										$html .= '<div class="categories">';
										foreach($post_portfolio_categories as $category){
											$html .= '<div class="small-margin-right-small inline-block">' . $category->name . '</div>';
										}
										$html .= '</div>';
									}
								$html .= '</a>';
							$html .= '</div>';
						$html .= '</div>';
					//no end div here
				}
				//style a (make a new row of 2)
				if(($counter % 2) == 0 && ($post_portfolio_type == 'style_a')){
					$html .= '</div>';
					$html .= '<div class="el-row nested row">';
				}
				//style b (new row of 2)
				if(($counter % 2) == 0 && ($post_portfolio_type == 'style_b')){
					$html .= '</div>';
					$html .= '<div class="el-row nested row">';
				}
				//style c (new row of 3)
				if(($counter % 3) == 0 && ($post_portfolio_type == 'style_c')){
					$html .= '</div>';
					$html .= '<div class="el-row nested row">';
				}
				
				
				//Image
				$image_url = '';
				$post_attachment = get_post($image_id);
				if($post_attachment->post_mime_type == 'image/svg+xml'){
					$image_url = $post_attachment->guid;
				}else{
					$image_url = wp_get_attachment_image_src($image_id, 'large', false)[0];
				}

				$html .= '<div class="fragment equal-height-item small-margin-bottom-medium">';
					$html  .= '<a href="' . $post_permalink . '">';
					
						$html .= '<div class="background-wrap content-wrap">';
							$html .= '<div class="background-image" style="background-image: url(' . $image_url .');"></div>';
						$html .= '</div>';
					$html .= '</a>';
					
				$html .= '</div>';	
						
				$counter++;		
			}
		}
		$html .= '</article>';
			
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
 $el_portfolios = el_portfolios::getInstance();
 


?>