<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Hotel_Luxury
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function hotel_luxury_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( is_front_page() ) {
		$page_id = get_option( 'page_on_front' );
	} elseif ( is_home() ) {
		$page_id = get_option( 'page_for_posts' );
	} elseif ( is_shop() ) {
		$page_id = get_option( 'woocommerce_shop_page_id' );
	} else {
		$page_id = get_the_ID();
	}

	if ( is_page() || is_home() || is_shop() ) {
		$hide_title_bar = get_post_meta( $page_id, '_hide_title_bar', true );
	} else {
		$hide_title_bar = 1;
	}

	$show_titlebar_on_post = esc_attr( get_theme_mod('show_titlebar_on_post', 1) ) ;
	$show_titlebar_on_event = esc_attr( get_theme_mod('show_titlebar_on_event', 1) ) ;
	$show_titlebar_on_product = esc_attr( get_theme_mod('show_titlebar_on_product', 1) ) ;

    $show = '';

    if ( is_singular('tribe_events') || hotel_luxury_is_event() == true ) {
        $show = ( $show_titlebar_on_event == 1 ) ? true : false;
    }
    elseif ( is_product()  || is_product_category() || is_product_tag() ) {
	    $show = ( $show_titlebar_on_product == 1 ) ? true : false;
    }
    elseif ( is_single() ) {
		$show = ( $show_titlebar_on_post == 1 ) ? true : false;
	}


	if ( !$hide_title_bar || $show == true ) {
		$classes[] = 'has-titlebar';
    } else {
		$classes[] = 'no-titlebar';
    }

	$page_layout = esc_attr( get_theme_mod('page_layout', 'right') );

	if ( $page_layout == 'left' ) {
		$classes[] = 'left-sidebar-wrapper';
	} else {
		$classes[] = 'right-sidebar-wrapper';
	}

	return $classes;
}
add_filter( 'body_class', 'hotel_luxury_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function hotel_luxury_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'hotel_luxury_pingback_header' );


add_action( 'tgmpa_register', 'hotel_luxury_register_required_plugins' );
function hotel_luxury_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		array(
			'name'      => 'Elementor Page Builder',
			'slug'      => 'elementor',
			'required'  => true,
		),

		array(
			'name'      => 'The Events Calendar',
			'slug'      => 'the-events-calendar',
			'required'  => true,
		),

		array(
			'name'      => 'One Click Demo Import',
			'slug'      => 'one-click-demo-import',
			'required'  => false,
		)
	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'hotel-luxury',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.


	);

	tgmpa( $plugins, $config );
}


/**
 * Frontpage slider
 */
add_action( 'hotel_luxury_main_slider', 'hotel_luxury_get_featured_posts' );
function hotel_luxury_get_featured_posts(){
	if ( is_admin() ) {
		return ;
	}

	$tags = esc_attr( get_theme_mod('slide_tag_name', 'slider') ) ;
	if ( empty( $tags ) ) {
		return;
	}

	$num_post = absint( get_theme_mod( 'slide_number_post', 3 ) );
	$featured_content_args = array(
		'post_type' => 'post',
		'meta_key' => '_thumbnail_id',
		'tag' => $tags,
		'order' => 'DESC',
		'orderby' => 'date',
		'posts_per_page' => $num_post,
	);

	$f_query = new WP_Query( $featured_content_args );

	if ( $f_query ) {
		echo '<div class="slider-outer-wrapper"><div id="main_slider" class="owl-carousel owl-theme">';
		while ( $f_query->have_posts() ): $f_query->the_post();
			$thumbnail_url = wp_get_attachment_image_src( get_post_thumbnail_id( $f_query->ID ), 'full' , true );
			?>
			<div class="item">
				<img src="<?php echo $thumbnail_url[0] ?>" alt="" />
				<div class="carousel-caption intro-caption">
					<div class="slide-info">
						<div class="slide-content">
                            <h1 class="slider-title"><?php  the_title() ?></h1>
                            <div class="inline">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
							<?php the_excerpt() ?>
                        </div>
					</div>
				</div>
			</div>
			<?php
		endwhile;
		echo '</div></div>';
	}

}



if ( ! function_exists( 'hotel_luxury_display_page_title' ) ) {
	/**
	 * Display page title
	 */
    function hotel_luxury_display_page_title() {

	    if ( is_front_page() ) {
		    $page_id = get_option( 'page_on_front' );
        } elseif ( is_home() ) {
		    $page_id = get_option( 'page_for_posts' );
        } else {
		    $page_id = get_the_ID();
        }
	    $hide_page_title = get_post_meta( $page_id, '_hide_page_title', true );

	    if ( !$hide_page_title && ( !is_home() || !is_single() )  ) {
		    ?>
            <div class="row">
                <div class="col-md-12">
                    <?php if ( hotel_luxury_is_event() == true ) {} else { ?>
                    <div class="page-title-wrapper">
                        <h1 class="page-title left"><?php single_post_title(); ?> </h1>
                        <div class="clear"></div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php

	    }
    }
}
add_action( 'hotel_luxury_page_before_content', 'hotel_luxury_display_page_title' );


if ( ! function_exists( 'hotel_luxury_display_title_bar' ) ) {
	/**
	 * Display title bar
	 */
    function hotel_luxury_display_title_bar() {

        $header_image = get_header_image();

	    if ( is_front_page() ) {
		    $page_id = get_option( 'page_on_front' );
	    } elseif ( is_home() ) {
		    $page_id = get_option( 'page_for_posts' );
	    }  elseif ( is_shop() ){
		    $page_id = get_option( 'woocommerce_shop_page_id' );
        }
	    else {
		    $page_id = get_the_ID();
	    }

	    if ( is_page() || is_home() || is_shop() ) {
		    $hide_title_bar = get_post_meta( $page_id, '_hide_title_bar', true );
	    } else {
		    $hide_title_bar = 1;
        }

	    $show_cover = get_post_meta( $page_id, '_cover', true );
	    $title_bar_image = wp_get_attachment_image_src( get_post_thumbnail_id( $page_id ), 'full' );
	    if ( $show_cover && $title_bar_image ) {
	        $css = 'style="background-image: url('. $title_bar_image[0] .')"';
        } else {
	        if ( ! empty($header_image) ) {
		        $css = 'style="background-image: url('. $header_image .')"';
            } else {
		        $css = '';
            }
        }

	    $show_main_slider = esc_attr( get_theme_mod('show_main_slider', 1) ) ;
	    $show_titlebar_on_post = esc_attr( get_theme_mod('show_titlebar_on_post', 1) ) ;
	    $show_titlebar_on_event = esc_attr( get_theme_mod('show_titlebar_on_event', 1) ) ;
	    $show_titlebar_on_product = esc_attr( get_theme_mod('show_titlebar_on_product', 1) ) ;

	    if ( $show_main_slider == 1 &&  is_front_page()  ) {
		    do_action('hotel_luxury_main_slider');
	    } else {

		    $show = '';
		    if ( is_singular('post') ) {
			    $titlebar_title = esc_attr( get_theme_mod('blog_title', '') ) ;
			    $titlebar_subtitle = esc_attr( get_theme_mod('blog_sub_title', '') ) ;
			    $show = ( $show_titlebar_on_post == 1 ) ? true : false;
            }

            elseif ( is_singular('tribe_events')  || hotel_luxury_is_event() == true ) {
	            $titlebar_title = esc_attr( get_theme_mod('eventbar_title', '') ) ;
	            $titlebar_subtitle = esc_attr( get_theme_mod('event_sub_title', '') ) ;
	            $show = ( $show_titlebar_on_event == 1 ) ? true : false;
            }

            elseif ( is_product()  || is_product_category() || is_product_tag() ) {
			    $titlebar_title = esc_attr( get_theme_mod('product_title', '') ) ;
			    $titlebar_subtitle = esc_attr( get_theme_mod('product_sub_title', '') ) ;
			    $show = ( $show_titlebar_on_product == 1 ) ? true : false;
		    }

            else {
			    $titlebar_title = get_post_meta( $page_id, '_titlebar_title', true);
			    $titlebar_subtitle = get_post_meta( $page_id, '_titlebar_subtitle', true);
            }


		    if ( $show == true || $hide_title_bar != '1'  ) {
			    ?>
                <div class="titlebar-outer-wrapper" <?php echo $css; ?> >
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <?php if ( $titlebar_title ) : ?>
                                <div class="titlebar-title">
                                    <h1><?php echo $titlebar_title ?></h1>
                                    <div class="titlebar-decs"><?php echo  $titlebar_subtitle; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="shadow-box"></div>
                </div>
			    <?php
		    }
	    }
    }
}
add_action( 'hotel_luxury_before_main_content', 'hotel_luxury_display_title_bar' );



add_filter( 'woocommerce_show_page_title' , 'hotel_luxury_hide_shop_page_title' );
/**
 * Removes the "shop" title on the main shop page
 */
function hotel_luxury_hide_shop_page_title() {
	return false;
}


/**
 * Footer Copyright
 */
function hotel_luxury_footer_info() {
	echo sprintf( esc_html__( 'Copyright &copy; %1$s %2$s - %3$s theme by FilaThemes', 'hotel-luxury' ), date( 'Y' ), '<a href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo( 'name', 'display' ) ).'">'.esc_html( get_bloginfo( 'name', 'display' ) ).'</a>', '<a target="_blank" href="http://filathemes.com/hotel-luxury">Hotel Luxury</a>' );
}
add_action( 'hotel_luxury_footer_copyright', 'hotel_luxury_footer_info' );