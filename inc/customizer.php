<?php

//*customizer elements*/
function retail_motion_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
	//SECTIONS
	//service section
	$wp_customize->add_section('retail_services',
		array(
			'title'			=> 'Services',
			'description'	=> 'Outlines settings you can adjust for your services display',
			'priority'		=> 200
		)
	);
	//clients section
	$wp_customize->add_section('retail_clients',
		array(
			'title'			=> 'Clients',
			'description'	=> 'Outlines settings you can adjust for your clients display',
			'priority'		=> 210
		)
	);
	//Portfolios section
	$wp_customize->add_section('retail_portfolios',
		array(
			'title'			=> 'Portfolios',
			'description'	=> 'Outlines settings you can adjust for your portfolio display',
			'priority'		=> 220
		)
	); 
	//blogs section
	$wp_customize->add_section('retail_blogs',
		array(
			'title'			=> 'Blogs',
			'description'	=> 'Outlines settings you can adjust for your blogs / latest news sctions',
			'priority'		=> 230
		)
	); 
	//DISCONTINUED
	// //social media
	// $wp_customize->add_section('retail_social',
		// array(
			// 'title'			=> 'Social Media',
			// 'description'	=> 'Social media URLS',
			// 'priority'		=> 240
		// )
	// ); 
	
	
	//universal header options (including the default banner background and colours)
	$wp_customize->add_section('retail_header',
		array(
			'title'			=> 'Theme Header',
			'description'	=> 'Outlines settings that can be adjusted for the header',
			'priority'		=> 260
		)
	);
	
	
	//SETTINGS
	//Customer for 'services' elements
	$wp_customize->add_setting('retail_services_title', array('default'	=> ''));
	$wp_customize->add_setting('retail_services_subtitle', array('default' => ''));
	//settings for 'partners' elements
	$wp_customize->add_setting('retail_partners_title', array('default'	=> ''));
	$wp_customize->add_setting('retail_partners_subtitle', array('default' => ''));
	//settings for the 'portfolios' elements
	$wp_customize->add_setting('retail_portfolio_title', array('default' => ''));
	$wp_customize->add_setting('retail_portfolio_subtitle', array('default' => ''));
	$wp_customize->add_setting('retail_portfolio_display_type', array('default'	=> 'card'));
	//settings for the 'blogs' elements
	$wp_customize->add_setting('retail_blog_title', array('default'	=> ''));
	$wp_customize->add_setting('retail_blog_subtitle', array('default' => ''));
	$wp_customize->add_setting('retail_blog_show_categories_on_archive', array('default' => true));
	
	//DISCONTINUED
	// //settings for the 'retail social' elements
	// $wp_customize->add_setting('retail_social_facebook', array('default' => ''));
	// $wp_customize->add_setting('retail_social_twitter', array('default' => ''));
	// $wp_customize->add_setting('retail_social_instagram', array('default' => ''));
	// $wp_customize->add_setting('retail_social_youtube', array('default' => ''));
	// $wp_customize->add_setting('retail_social_linkedin', array('default' => ''));
	// $wp_customize->add_setting('retail_social_googleplus', array('default' => ''));
	// $wp_customize->add_setting('retail_social_show_blog_link', array('default' => true));
	
	
	//settingas for 'universal header' element
	$wp_customize->add_setting('retail_header_background_image', array('default' => ''));
	$wp_customize->add_setting('retail_header_background_color', array('default' => ''));
	$wp_customize->add_setting('retail_header_overlay_color', array('default' => ''));
	$wp_customize->add_setting('retail_header_overlay_opacity', array('default' => '0.5'));
	$wp_customize->add_setting('retail_header_text_color', array('default' => ''));
	$wp_customize->add_setting('retail_header_logo', array('default' => ''));
	$wp_customize->add_setting('retail_header_title', array('default' => ''));
	$wp_customize->add_setting('retail_header_subtitle', array('default' => ''));
	$wp_customize->add_setting('retail_header_video_url', array('default' => ''));
	
	
	
	
	//CONTROLS
	//service controls
	$wp_customize->add_control('retail_services_title', 
		array(
			'label'			=> 'Services Title',
			'description'	=> 'Title displayed above the services circles',
			'section'		=> 'retail_services',
			'type'			=> 'text'
		)
	);
	$wp_customize->add_control('retail_services_subtitle', 
		array(
			'label'			=> 'Services Subtitle',
			'description'	=> 'Text displayed below the service title, but above the service circles',
			'section'		=> 'retail_services',
			'type'			=> 'textarea'
		)
	);
	//clients controls
	$wp_customize->add_control('retail_partners_title', 
		array(
			'label'			=> 'Client Title',
			'description'	=> 'Title displayed when viewing the client profile cards on the website',
			'section'		=> 'retail_clients',
			'type'			=> 'text'
		)
	);
	$wp_customize->add_control('retail_partners_subtitle', 
		array(
			'label'			=> 'Client Subtitle',
			'description'	=> 'Content displayed below the title when viewing the client profile cards on the website',
			'section'		=> 'retail_clients',
			'type'			=> 'textarea'
		)
	);
	//Portfolios controls
	$wp_customize->add_control('retail_portfolio_title',
		array(
			'label'			=> 'Portfolio Title',
			'description'	=> 'Primary title displayed above your listing of portfolio cards',
			'section'		=> 'retail_portfolios',
			'type'			=> 'text'
		)
	);
	$wp_customize->add_control('retail_portfolio_subtitle',
		array(
			'label'			=> 'Portfolio Subtitle',
			'description'	=> 'Secondary text displayed above your listing of portfolio cards',
			'section'		=> 'retail_portfolios',
			'type'			=> 'textarea'
		)
	);
	$wp_customize->add_control('retail_portfolio_display_type', 
		array(
			'label'			=> 'Portfolio Display Type',
			'description'	=> 'Defines what your latest portfolio cards look like',
			'section'		=> 'retail_portfolios',
			'type'			=> 'radio',
			'choices'		=> array(
				'card'	=> 'card',
				'tile'	=> 'tile'
			)
		)
	);
	
	//Blogs 
	$wp_customize->add_control('retail_blog_title',
		array(
			'label'			=> 'Blog Title',
			'description'	=> 'Primary title displayed above your listing of latest posts',
			'section'		=> 'retail_blogs',
			'type'			=> 'text'
		)
	);
	$wp_customize->add_control('retail_blog_subtitle',
		array(
			'label'			=> 'Blog Subtitle',
			'description'	=> 'Secondary text displayed above your listing of latest posts',
			'section'		=> 'retail_blogs',
			'type'			=> 'textarea'
		)
	);
	
	
	$wp_customize->add_control('retail_blog_show_categories_on_archive',
		array(
			'description'	=> 'Enable to show an icon in your social media links that jumps your users directly to the blog',
			'section'		=> 'retail_blogs',
			'type'			=> 'checkbox',
			'label'			=> 'Show Categories on Archive',
			'description'	=> 'Determine if your post categories will appear on the blog page, displayed between the banner and posts'
		)
	);
	
	
	//DISCONTINUED
	//Social media controls
	// $wp_customize->add_control('retail_social_facebook',
		// array(
			// 'label'			=> 'Facebook URL',
			// 'section'		=> 'retail_social',
			// 'type'			=> 'url'
		// )
	// );
	// $wp_customize->add_control('retail_social_instagram',
		// array(
			// 'label'			=> 'Instagram URL',
			// 'section'		=> 'retail_social',
			// 'type'			=> 'url'
		// )
	// );
	// $wp_customize->add_control('retail_social_twitter',
		// array(
			// 'label'			=> 'Twitter URL',
			// 'section'		=> 'retail_social',
			// 'type'			=> 'url'
		// )
	// );
	// $wp_customize->add_control('retail_social_youtube',
		// array(
			// 'label'			=> 'YouTube URL',
			// 'section'		=> 'retail_social',
			// 'type'			=> 'url'
		// )
	// );
	// $wp_customize->add_control('retail_social_linkedin',
		// array(
			// 'label'			=> 'LinkedIn URL',
			// 'section'		=> 'retail_social',
			// 'type'			=> 'url'
		// )
	// );
	// $wp_customize->add_control('retail_social_googleplus',
		// array(
			// 'label'			=> 'Google+ URL',
			// 'section'		=> 'retail_social',
			// 'type'			=> 'url'
		// )
	// );
	// $wp_customize->add_control('retail_social_show_blog_link',
		// array(
			// 'description'	=> 'Enable to show an icon in your social media links that jumps your users directly to the blog',
			// 'section'		=> 'retail_social',
			// 'type'			=> 'checkbox',
			// 'label'			=> 'Show Blog Link'
		// )
	// );
	
	
	
	//Theme header controls
	$wp_customize->add_control( 
		new WP_Customize_Image_Control(
			$wp_customize,
			'retail_header_background_image',
			array(
				'label'			=> 'Header Background Image',
				'description'	=> 'Set the default backround image will be be used unless overridden on a page by page basis',
				'section'		=> 'retail_header'
			)
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'retail_header_background_color',
			array(
				'label'			=> 'Header Background Color',
				'description'	=> 'Set the default background color to be used in the header, useful if you don\'t have an image set',
				'section'		=> 'retail_header'
			)
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'retail_header_text_color',
			array(
				'label'			=> 'Header Text Color',
				'description'	=> 'Set the default text color to be used in the header, should contrast against the background color / image chosen',
				'section'		=> 'retail_header'
			)
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'retail_header_overlay_color',
			array(
				'label'			=> 'Header Overlay Colour',
				'description'	=> 'Set the overlay color placed on top of the background or image, useful for tinting the background to make text stand out',
				'section'		=> 'retail_header'
			)
		)
	);
	$wp_customize->add_control('retail_header_overlay_opacity',
			array(
				'label'			=> 'Header Overlay Colour',
				'description'	=> 'Set the overlay color placed on top of the background or image, useful for tinting the background to make text stand out',
				'section'		=> 'retail_header',
				'type'			=> 'select',
				'choices'		=> array(
					'0.0'		=> '0%',
					'0.1'	=> '10%',
					'0.2'	=> '20%',
					'0.3'	=> '30%',
					'0.4'	=> '40%',
					'0.5'	=> '50%',
					'0.6'	=> '60%',
					'0.7'	=> '70%',
					'0.8'	=> '80%',
					'0.9'	=> '90%',
					'1.0'	=> '100%'
				)
			)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control(
			$wp_customize,
			'retail_header_logo',
			array(
				'label'			=> 'Header Optional Logo',
				'description'	=> 'If you want a logo to display in the middle of the header, you can set that here. Best used with transparent PNG images',
				'section'		=> 'retail_header'
			)
		)
	);

	$wp_customize->add_control('retail_header_title',
		array(
			'label'			=> 'Header Title',
			'description'	=> 'Title displayed in the header',
			'section'		=> 'retail_header',
			'type'			=> 'text'
		)
	);
	$wp_customize->add_control('retail_header_subtitle',
		array(
			'label'			=> 'Header Subtitle',
			'description'	=> 'Subtitle displayed in the header',
			'section'		=> 'retail_header',
			'type'			=> 'textarea'
		)
	);
	$wp_customize->add_control('retail_header_video_url',
		array(
			'label'			=> 'Header Video URL',
			'description'	=> 'URL to the full video you want displayed in the header (iFrame Lightbox)',
			'section'		=> 'retail_header',
			'type'			=> 'url',
			'input_attrs'	=> array(
				'placeholder'	=> 'E.g https://www.youtube.com/watch?v=dQw4w9WgXcQ'
			)
		)
	);
	
	
	

	

	
	
	
	
}
add_action( 'customize_register', 'retail_motion_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function retail_motion_customize_preview_js() {
	wp_enqueue_script( 'retail_motion_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'retail_motion_customize_preview_js' );
