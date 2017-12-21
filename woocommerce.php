<?php
/**
 * The template for displaying all pages WooCommerce page.
 *
 * @package Hotel_Luxury
 */

get_header();
?>

	<div class="row">
		<div class="col-md-12">
				<div class="page-title-wrapper">
					<h1 class="page-title left"><?php woocommerce_page_title(); ?> </h1>
					<div class="clear"></div>
				</div>
		</div>
	</div>

	<div id="primary" class="content-area row">
		<div class="content-wrapper  columns">
			<main id="main" class="site-main">

				<?php woocommerce_content(); ?>

			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

<?php
get_footer();
