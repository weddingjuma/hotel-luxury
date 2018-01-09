<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Hotel_Luxury
 */

?>

        </div><!-- .main-wrapper -->

        <?php
        $newsletter_disable = esc_attr( get_theme_mod('newsletter_disable', '1') );
        $newsletter_mailchimp = esc_url( get_theme_mod('newsletter_mailchimp_form_url') ) ;
        if ( $newsletter_disable != '1' ) :
        ?>
        <div class="subscribe_section row">
            <form id="subscribe_form" method="post" action="<?php if ($newsletter_mailchimp != '') echo $newsletter_mailchimp; ?>">
                <label for="email_subscribe"><?php echo get_theme_mod( 'newsletter_title','Sign up to receive Special Offers') ?>&nbsp;&nbsp;</label>
                <input type="text" placeholder="<?php esc_attr_e('Enter your e-mail address', 'hotel-luxury') ?>" value="" name="email_subscribe" id="email_subscribe" class="subs_email_input">
                <input type="submit" class="btn btn_default" value="<?php esc_attr_e('Subscribe', 'hotel-luxury') ?>" name="">
            </form>
        </div>
        <?php endif; ?>
	</div><!-- #content -->


    <footer id="colophon" class="site-footer footer-outer-wrapper">
        <div class="footer-wrapper container">
            <div class="footer-columns row">
                <div class="col-md-3 columns">
	                <?php if ( is_active_sidebar( 'footer-1' ) ) {
                        dynamic_sidebar('footer-1');
	                }
                    ?>
                </div>
                <div class="col-md-3 columns">
	                <?php if ( is_active_sidebar( 'footer-2' ) ) {
		                dynamic_sidebar('footer-2');
	                }
	                ?>
                </div>
                <div class="col-md-3 columns">
	                <?php if ( is_active_sidebar( 'footer-3' ) ) {
		                dynamic_sidebar('footer-3');
	                }
	                ?>
                </div>
                <div class="col-md-3 columns">
	                <?php if ( is_active_sidebar( 'footer-4' ) ) {
		                dynamic_sidebar('footer-4');
	                }
	                ?>
                </div>

            </div>
        </div> <!-- END .footer-wrapper -->

        <div class="footer-copyright-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="footer-copyright">
                            <div class="copy-left pull-left">
				                <?php do_action( 'hotel_luxury_footer_copyright' ); ?>
                            </div>

			                <?php if ( has_nav_menu( 'social' ) ) { ?>
                                <div class="social-links pull-right">
					                <?php wp_nav_menu( array( 'theme_location' => 'social', 'menu_class' => 'footer-social', 'link_before' => '<span class="screen-reader-text">',  'link_after'   => '</span>') ) ; ?>
                                </div>
			                <?php } ?>

                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </footer> <!-- END .footer-outer-wrapper -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
