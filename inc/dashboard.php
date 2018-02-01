<?php
/**
 * Add theme dashboard page
 */
add_action('admin_menu', 'hotel_luxury_theme_info');
function hotel_luxury_theme_info() {
	$theme_data = wp_get_theme();
	add_theme_page( sprintf( esc_html__( '%s Dashboard', 'hotel-luxury' ), $theme_data->Name ), sprintf( esc_html__('%s Theme', 'hotel-luxury'), $theme_data->Name), 'edit_theme_options', 'hotel_luxury', 'hotel_luxury_theme_info_page');
}


if ( ! function_exists( 'hotel_luxury_admin_scripts' ) ) :
	/**
	 * Enqueue scripts for admin page only: Theme info page
	 */
	function hotel_luxury_admin_scripts( $hook ) {
		if ( $hook === 'widgets.php' || $hook === 'appearance_page_hotel_luxury'  ) {
			wp_enqueue_style('hotel_luxury-admin-css', get_template_directory_uri() . '/assets/css/admin.css');
		}
	}
endif;
add_action('admin_enqueue_scripts', 'hotel_luxury_admin_scripts');


function hotel_luxury_theme_info_page() {
	$theme_data = wp_get_theme();

	// Check for current viewing tab
	$tab = null;
	if ( isset( $_GET['tab'] ) ) {
		$tab = $_GET['tab'];
	} else {
		$tab = null;
	}

	if ( version_compare(PHP_VERSION, '5.4.0') < 0 ) {
		?>
        <div class="warning notice notice-warning notice-alt is-dismissible" style="display: block !important;">
            <p><strong><?php esc_html_e('The Hotel Luxury theme requires PHP version 5.4 or greater.', 'hotel-luxury'); ?></strong></p>
        </div>
		<?php
	}
	?>
	<div class="wrap about-wrap theme_info_wrapper">
		<h1><?php printf(esc_html__('Welcome to %1$1s - Version %2$2s', 'hotel-luxury'), $theme_data->Name, $theme_data->Version ); ?></h1>
		<div class="about-text"><?php echo $theme_data->Description ?></div>


		<h2 class="nav-tab-wrapper">
			<a href="?page=hotel_luxury" class="nav-tab<?php echo is_null($tab) ? ' nav-tab-active' : null; ?>"><?php echo $theme_data->Name; ?></a>
		</h2>

		<?php if ( is_null($tab) ) { ?>
			<div class="theme_info info-tab-content">
				<div class="theme_info_column clearfix">
					<div class="theme_info_left">

						<div class="theme_link">
							<h3><?php esc_html_e( 'Theme Customizer', 'hotel-luxury' ); ?></h3>
							<p class="about"><?php printf(esc_html__('%s supports the Theme Customizer for all theme settings. Click "Customize" to start customize your site.', 'hotel-luxury'), $theme_data->Name); ?></p>
							<p>
								<a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary"><?php esc_html_e('Start Customize', 'hotel-luxury'); ?></a>
							</p>
						</div>
						<div class="theme_link">
							<h3><?php esc_html_e( 'Theme Documentation', 'hotel-luxury' ); ?></h3>
							<p class="about"><?php printf(esc_html__('Need any help to setup and configure %s? Please have a look at our documentations instructions.', 'hotel-luxury'), $theme_data->Name); ?></p>
							<p>
								<a href="http://filathemes.com/hotel-luxury/docs/" target="_blank" class="button button-secondary"><?php esc_html_e('Online Documentation', 'hotel-luxury'); ?></a>
							</p>
						</div>
					</div>

					<div class="theme_info_right">
						<img src="<?php echo get_template_directory_uri(); ?>/screenshot.png" alt="<?php esc_html_e( 'Theme Screenshot', 'hotel-luxury' ); ?>" />
					</div>
				</div>
			</div>
		<?php } ?>


	</div> <!-- END .theme_info -->

	<?php
}


function hotel_luxury_admin_notice(){
	if ( version_compare(PHP_VERSION, '5.4.0') < 0 ) {
	    ?>
        <div class="warning notice notice-warning notice-alt is-dismissible">
            <p><strong><?php esc_html_e('The Hotel Luxury theme require PHP version 5.4 or greater.', 'hotel-luxury'); ?></strong></p>
        </div>
        <?php
	}
}

function hotel_luxury_one_activation_admin_notice(){
	global $pagenow;
	if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
		add_action( 'admin_notices', 'hotel_luxury_admin_notice' );
	}
}
/* activation notice */
add_action( 'load-themes.php',  'hotel_luxury_one_activation_admin_notice'  );


?>