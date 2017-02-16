<?php
/*
 * Extended Blogs
 */

 
 class el_blogs extends el_content_type{
 
	private static $instance = null;
	
	//post type args
 	private $post_type_args = array(
		'post_type_name'		=> 'post',
		'post_type_single_name'		=> 'posttest',
		'post_type_plural_name'		=> 'Poststester',
		'labels'				=> array(
			'menu_name'				=> 'Posts'
		)
	);
	
	//meta boxes
	private $meta_box_args = array(
		array(
			'id'			=> 'post_metabox',
			'title'			=> 'Additional Information - For Blog Listing Page',
			'context'		=> 'normal',
			'args'			=> array(
				'description' => 'The following affects how your post will display when viewed in the post archive page'
			)
		),
		array(
			'id'			=> 'post_featured_metabox',
			'title'			=> 'Featured Post Information',
			'context'		=> 'side',
			'args'			=> array(
				'description' => 'Determine if your post will be featured (displayed in the \'featured\' sections)'
			)
		),
		
		
	);
	
	private $meta_field_args = array(
		array(
			'id'			=> 'post-fallback-color',
			'title'			=> 'Post Fallback Color',
			'description'	=> 'When a post doesn\'t have a featured image set, this solid color will be used in the background',
			'type'			=> 'color',
			'meta_box_id'	=> 'post_metabox',
			'args'			=> array(
				'default-color'	=> '#f89238'
			)
		),
		array(
			'id'			=> 'post-overlay-color',
			'title'			=> 'Post Overlay Color',
			'description'	=> 'Color superimposed over the featured image when viewing this post in the post archive (blog page)',
			'type'			=> 'color',
			'meta_box_id'	=> 'post_metabox'
		),
		array(
			'id'			=> 'post-text-color',
			'title'			=> 'Post Text Color',
			'description'	=> 'Text color for the text when it\' displayed on top of the featured image / fallback color',
			'type'			=> 'color',
			'meta_box_id'	=> 'post_metabox',
			'args'			=> array(
				'default-color'	=> '#ffffff'
			)
		),
		array(
			'id'			=> 'post_is_featured',
			'title'			=> 'Featured Post',
			'type'			=> 'radio',
			'meta_box_id'	=> 'post_featured_metabox',
			'args'			=> array(
				'options'		=> array(
					array(
						'id'	=> 'no',
						'title'	=> 'No'
					),
					array(
						'id'	=> 'yes',
						'title'	=> 'Yes'
					)
				)
			)
		)
	);
	
	//child constructor, add custom function here
 	public function __construct(){
 	
 		//call parent construct to set this up
 		parent::__construct(
 			$this->post_type_args,
 			$this->meta_box_args,
 			$this->meta_field_args
		);
		
		add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts_and_styles'));
		
		//hooks
		add_action('el_display_featured_posts', array($this, 'display_featured_posts'));  //display featured blogs 
		add_action('el_display_blog_listing', array($this,'display_post_listings')); //display a listing of all blogs
		
 	}
	
	public function enqueue_public_scripts_and_styles(){
		$directory = get_stylesheet_directory_uri() . '/inc/modules/blogs';
		wp_enqueue_script('blogs-public-scripts', $directory . '/js/blogs-public-scripts.js', array('jquery', 'theme-masonry-script'));	
	}
	
	//hook to display featured posts
	public function display_featured_posts(){
		
		$html = '';
		
		$instance = self::getInstance();
		
		$args = array(
			'posts_per_page'	=> -1,
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'orderby'			=> 'post_title',
			'meta_query'		=> array(
				array(
					'key'				=> 'post_is_featured',
					'value'				=> 'yes',
					'compare'			=> '='
				)
			)
		);

		$posts = get_posts($args);
		
		if($posts){
			$html .= '<article class="el-col-small-12 blog-listing equal-height-items featured small-margin-top-bottom-medium">';
				
				//determine if we have theme options set for the blog title and subtitle
				$blog_title = get_theme_mod('retail_blog_title');
				$blog_subtitle = get_theme_mod('retail_blog_subtitle');
				
				if(!empty($blog_title) || !empty($blog_subtitle)){
					$html .= '<div class="el-col-small-12 small-align-center">';
					if(!empty($blog_title)){
						$html .= '<h2 class="h1 big fat small-margin-top-bottom-none">' . $blog_title .'</h1>';
					}	
					if(!empty($blog_subtitle)){
						$html .= '<p class="small-margin-top-bottom-tiny">' . $blog_subtitle . '</p>';	
					}
					$html .= '</div>';
				}
			
				foreach($posts as $post){
					$html .= $instance::get_post_card_html($post->ID, 'featured');
				
				}
			$html .= '</article>';
		}
		
		echo $html;
	}

	
	
	//gets post terms (categories or tags) for easy display
	public static function get_post_term_links($term_name = 'category'){
		
		$instance = self::getInstance();
		
		$html = '';
		
		$term_args = array(
			'hide_empty'	=> true,
			'taxonomy'		=> $term_name,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
		);
		
		$terms = get_terms($term_args);
		
		if($terms){
			
			//Determine if we want these terms to be displayed (customizer > blogs > show categories on archive)
			$show_blog_categories = get_theme_mod('retail_blog_show_categories_on_archive');
			
			if($show_blog_categories){
				$html .= '<div class="term-list el-row  small-margin-bottom-medium">';
					$html .= '<div class="el-row inner">';
						
						$html .= '<div class="terms el-col-small-12 small-padding-top-small small-align-center">';
							$queried_object = get_queried_object();
						
							//display all link (optionally highlighting it)
							$class = '';
							if( is_home()){
								$class = 'active';
							}
							$post_index_page = get_permalink( get_option( 'page_for_posts' ) );
							$html .= '<div class="term inline-block small-margin-bottom-small small-margin-right-small">';
								$html .= '<a class="button no-border ' . $class .'" href="' . $post_index_page . '" title="All">All</a>';
							$html .= '</div>';
						
							
							foreach($terms as $term){
								$term_id = $term->term_id;
								$term_name = $term->name;
								$term_permalink = get_term_link($term);
								$term_count = $term->count;
								
								//highlight the current term if possible
								$class = '';
								if( ( is_a($queried_object,'WP_Term')) && ($term_id == $queried_object->term_taxonomy_id) ){
									$class = 'active';
								}
								
								$html .= '<div class="term inline-block small-margin-bottom-small small-margin-right-small">';
									$html .= '<a class="button no-border ' . $class .'" href="' . $term_permalink . '" title="' . $term_name . '">' . $term_name . '</a>';
								$html .= '</div>';
								
							}
						$html .= '</div>';
					$html .= '</div>';
				$html .= '</div>';
				
				
			}

		}
		
		return $html;
	}
	
	//action hook, displays a listing of posts using the blgo formatting (masonry)
	public static function display_post_listings(){
		
		$instance = self::getInstance();
		$html = '';
		
		$post_args = array(
			'post_type'		=> 'post',
			'post_status'	=> 'publish',
			'orderby'		=> 'date',
			'order'			=> 'DESC',
			'posts_per_page'=> 10
		);
		$posts = get_posts($post_args);

		if($posts){
			$html .= '<div class="el-row inner blog-listing small-margin-top-bottom-large">';

				//blog listing
				$html .= '<div class="masonry-elements">';
				foreach($posts as $post){
					$html .= $instance::get_post_card_html($post->ID, 'blog');
				}
				$html .= '</div>';
			$html .= '</div>';
		}
		
		echo $html;
	}
	
	//gets the HTML for a single post card. Adjusted output based on the type of layout we want
	public static function get_post_card_html($post_id, $style = 'blog'){
	
		$instance = self::getInstance();
	
		$html = '';
		
		$post = get_post($post_id);
		
		$post_title = apply_filters('post_title', $post->post_title);
		$post_excerpt = $post->post_excerpt; 
		$post_permalink = get_permalink($post_id);
		$post_author_id = $post->post_author;
		$post_author = get_user_by('id', $post_author_id);
		$post_author_name = $post_author->display_name;
		$post_author_permalink = get_author_posts_url($post_author_id);
		$post_author_gravatar_url = get_avatar_url($post_author_id, array('default' => 'gravatar_default', 'size' => 48));
		
		$post_background_image = has_post_thumbnail($post_id) ? wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'large', false)[0] : '';
		$post_background_color = get_post_meta($post_id, 'post-fallback-color', true);
		$post_overlay_color = get_post_meta($post_id, 'post-overlay-color', true);
		$post_text_color = get_post_meta($post_id, 'post-text-color', true);
		
		$post_date_raw = $post->post_date;
		$post_date = new DateTime($post_date_raw);

		
		//standard blog design (masonry)
		if($style == 'blog'){
		
			$html .= '<article class="masonry-item el-col-small-12 el-col-medium-6 small-margin-bottom-medium">';
				$html .= '<div class="blog">';
					
					//display background image / solid color
					if(!empty($post_background_image)){
						$html .= '<div class="background-image" style="background-image: url(' . $post_background_image . ');"></div>';
					}else{
						$html .= '<div class="background-color" style="background-color: ' . $post_background_color . '" ></div>';
					}
					
					//overlay color (if set)
					if(!empty($post_overlay_color)){
						$html .= '<div class="post-overlay" style="background-color: ' . $post_overlay_color . ';"></div>';
					}
					
					$html .= '<div class="post-info" style="color: ' . $post_text_color .';">';
						$html .= '<h3 class="post-date small">' . $post_date->format("dS M Y") . '</h3>';
						$html .= '<h1 class="title"><a href="' . $post_permalink  . '">' . $post_title . '</a></h1>';
						if(!empty($post_excerpt)){
							$html .= '<div class="excerpt small-margin-bottom-small">' . $post_excerpt . '</div>';
						}
						//HIDDEN for now
						//$html .= '<div class="author-info">';
						//	$html .= '<img class="author-image" src="' . $post_author_gravatar_url . '" alt="' . $post_author_name . '"/>';
						//	$html .= '<h3 class="author-name small">' . $post_author_name .'</h3>';
						//$html .= '</div>';
						
						$html .= '<a class="button white readmore small-margin-top-small" href="' . $post_permalink . '">Read More</a>';
						
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</article>';
		
		
		}
		//featured style card
		else if($style == 'featured'){
			$html .= '<article class="blog-wrap equal-height-item el-col-small-12 el-col-medium-4 small-margin-bottom-medium">';
				$html .= '<div class="blog">';
					
					
					//display background image / solid color
					$html .= '<a href="' . $post_permalink .'" title="' . $post_title . '">';
						if(!empty($post_background_image)){
							
							//$html .= '<div class="image-wrap ">';
								$html .= '<div class="background-wrap small-aspect-1-2 medium-aspect-3-4">';
									$html .= '<div class="background-image" style="background-image: url(' . $post_background_image . ');">';
								$html .= '</div>';
							//$html .= '</div>';
							
							//overlay color (if set)
							if(!empty($post_overlay_color)){
								$html .= '<div class="post-overlay" style="background-color: ' . $post_overlay_color . ';"></div>';
							}
							$html .= '</div>';
						}else{
							$html .= '<div class="background-color" style="background-color: ' . $post_background_color . '" ></div>';
						}
					$html .= '</a>';
					
					
					
					
					$html .= '<div class="post-info" style="">';
						$html .= '<h1 class="title small">';
							$html .= '<a href="' . $post_permalink .'" title="' . $post_title . '">' . $post_title . '</a>';
						$html .= '</h1>';
						
						$html .= '<h3 class="post-date small">' . $post_date->format("dS M Y") . '</h3>';
						$html .= '<div class="divider"></div>';
						if(!empty($post_excerpt)){
							$html .= '<div class="excerpt small-margin-top-bottom-small">' . $post_excerpt . '</div>';
						}
						
						$html .= '<a class="button gray readmore small small-margin-top-small" href="' . $post_permalink . '">Read More</a>';
						
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
$el_blogs = el_blogs::getInstance();

?>