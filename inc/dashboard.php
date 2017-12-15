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
	?>
	<div class="wrap about-wrap theme_info_wrapper">
		<h1><?php printf(esc_html__('Welcome to %1$1s - Version %2$2s', 'hotel-luxury'), $theme_data->Name, $theme_data->Version ); ?></h1>
		<div class="about-text"><?php echo $theme_data->Description ?></div>

		<h2 class="nav-tab-wrapper">
			<a href="?page=hotel_luxury" class="nav-tab<?php echo is_null($tab) ? ' nav-tab-active' : null; ?>"><?php echo $theme_data->Name; ?></a>
			<a href="?page=hotel_luxury&tab=demo-data-importer" class="nav-tab<?php echo $tab == 'demo-data-importer' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'One Click Demo Import', 'hotel-luxury' ); ?></span></a>
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
								<a href="#" target="_blank" class="button button-secondary"><?php esc_html_e('Online Documentation', 'hotel-luxury'); ?></a>
							</p>
						</div>
					</div>

					<div class="theme_info_right">
						<img src="<?php echo get_template_directory_uri(); ?>/screenshot.png" alt="Theme Screenshot" />
					</div>
				</div>
			</div>
		<?php } ?>


		<?php if ( $tab == 'demo-data-importer' ) { ?>
			<div class="demo-import-tab-content info-tab-content">

			</div>
		<?php } ?>



	</div> <!-- END .theme_info -->

	<?php
}
?>