<?php
/**
 * Hotel Luxury Theme Customizer
 *
 * @package Hotel_Luxury
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function hotel_luxury_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'hotel_luxury_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'hotel_luxury_customize_partial_blogdescription',
		) );
	}

	// Load custom controls
	require get_template_directory() . '/inc/customizer-controls.php';

	$wp_customize->add_panel( 'theme_options', array(
		'title' => esc_html__( 'Theme Options', 'hotel-luxury' )
	));




	// Newsletter
	$wp_customize->add_section( 'newsletter', array(
		'title' => esc_html__( 'Newsletter', 'hotel-luxury' ),
		'panel' => 'theme_options'
	));

	// Disable Newsletter
	$wp_customize->add_setting( 'newsletter_disable',
		array(
			'sanitize_callback' => 'hotel_luxury_checkbox_sanitize',
			'default'           => '',
		)
	);
	$wp_customize->add_control( 'newsletter_disable',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__('Hide Newsletter?', 'hotel-luxury'),
			'section'     => 'newsletter',
			'description' => esc_html__('Check this box to hide footer newsletter form.', 'hotel-luxury')
		)
	);

	$wp_customize->add_setting( 'newsletter_form_shortcode', array(
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => esc_html__( 'Sign up to receive Special Offers', 'hotel-luxury' )
	) );

	$wp_customize->add_control( 'newsletter_form_shortcode',
		array(
			'label'       => esc_html__('MailChimp Form Shortcode', 'hotel-luxury'),
			'section'     => 'newsletter',
			'description' => ''
		)
	);



	$wp_customize->add_setting( 'primary_color', array(
		'default' 			     => '#bca474',
		'sanitize_callback'		 => 'sanitize_hex_color_no_hash'
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_color', array(
		'label' 				 => esc_html__( 'Primary  Color', 'hotel-luxury' ),
		'section' 				 => 'colors',
	) ) );

	/* Footer settings */
	$wp_customize->add_section('footer', array(
		'title' => esc_html__('Footer', 'hotel-luxury'),
		'panel' => 'theme_options',
		'priority' => 120
	));

	// Footer BG Color
	$wp_customize->add_setting( 'footer_bg_color', array(
		'default' 			     =>  '#202020',
		'sanitize_callback'		 => 'sanitize_hex_color_no_hash'
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_bg_color', array(
		'label' 				 => esc_html__( 'Footer Background Color', 'hotel-luxury' ),
		'section' 				 => 'footer',
	) ) );

	$wp_customize->add_setting( 'footer_text_color', array(
		'default' 			     => '#666',
		'sanitize_callback'		 => 'sanitize_hex_color_no_hash'
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_text_color', array(
		'label' 				 => esc_html__( 'Footer Text Color', 'hotel-luxury' ),
		'section' 				 => 'footer',
	) ) );

	$wp_customize->add_setting( 'footer_copyright_color', array(
		'default' 			     =>  '#000',
		'sanitize_callback'		 => 'sanitize_hex_color_no_hash'
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_copyright_color', array(
		'label' 				 => esc_html__( 'Copyright Background Color', 'hotel-luxury' ),
		'section' 				 => 'footer',
	) ) );

	$wp_customize->add_setting( 'copyright_text_color', array(
		'default' 			     => '#666',
		'sanitize_callback'		 => 'sanitize_hex_color_no_hash'
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'copyright_text_color', array(
		'label' 				 => esc_html__( 'Copyright Text Color', 'hotel-luxury' ),
		'section' 				 => 'footer',
	) ) );



	// if is free :
	$wp_customize->add_setting( 'footer_credit', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control(
		new Hotel_Luxury_Group_Settings_Heading_Control(
			$wp_customize,
			'footer_credit',
			array(
				'label'      => esc_html__( 'Footer Settings', 'hotel-luxury' ),
				'description' => sprintf( esc_html__( 'Upgrade to %1$s to change footer copyright.', 'hotel-luxury' ), '<a href="#">'.esc_html__( 'PRO version', 'hotel-luxury' ).'</a>' ),
				'section'    => 'footer',
				'type'    => 'group_heading_message',
			)
		)
	);


	// slider for home page
	$wp_customize->add_section( 'main_slider', array(
		'title' => esc_html__( 'Home Slider', 'hotel-luxury' ),
		'panel' => 'theme_options'
	));

	$wp_customize->add_setting( 'show_main_slider', array(
		'sanitize_callback' => 'hotel_luxury_checkbox_sanitize',
		'default'           => '0'
	) );

	$wp_customize->add_control( 'show_main_slider',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__('Show Slider on Home page', 'hotel-luxury'),
			'section'     => 'main_slider',
			'description' => ''
		)
	);

	$wp_customize->add_setting( 'slide_tag_name', array(
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => 'slider'
	) );
	$wp_customize->add_control( 'slide_tag_name',
		array(
			'label'       => esc_html__('Tag name', 'hotel-luxury'),
			'section'     => 'main_slider',
			'description' => esc_html__('Easily feature all posts with the "slider" tag or a tag of your choice.', 'hotel-luxury')
		)
	);
	$wp_customize->add_setting( 'slide_number_post', array(
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => '3'
	) );
	$wp_customize->add_control( 'slide_number_post',
		array(
			'label'       => esc_html__('Number post to show', 'hotel-luxury'),
			'section'     => 'main_slider'
		)
	);

	// Event
	$wp_customize->add_section( 'event', array(
		'title' => esc_html__( 'Event Title', 'hotel-luxury' ),
		'panel' => 'theme_options'
	));
	$wp_customize->add_setting( 'eventbar_title', array(
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => esc_html__( 'Events', 'hotel-luxury' )
	) );
	$wp_customize->add_control( 'eventbar_title',
		array(
			'label'       => esc_html__('Main Title', 'hotel-luxury'),
			'section'     => 'event',
			'description' => ''
		)
	);
	// Checkbox Sanitize
	function hotel_luxury_checkbox_sanitize( $input ) {
		if ( $input == 1 ) {
			return 1;
		} else {
			return '';
		}
	}

	// Color Sanitize
	function hotel_luxury_color_sanitize( $color ) {
		if ( $unhashed = sanitize_hex_color_no_hash( $color ))
			return '#' . $unhashed;
		return $color;
	}



}
add_action( 'customize_register', 'hotel_luxury_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function hotel_luxury_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function hotel_luxury_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function hotel_luxury_customize_preview_js() {
	wp_enqueue_script( 'hotel-luxury-customizer', get_template_directory_uri() . '/assets//js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'hotel_luxury_customize_preview_js' );
