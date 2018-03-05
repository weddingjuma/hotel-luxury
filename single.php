<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Hotel_Luxury
 */

get_header(); ?>

    <?php do_action('hotel_luxury_page_before_content'); ?>


    <div id="primary" class="content-area row">
        <div class="content-wrapper col-md-8">
            <main id="main" class="site-main">

            <?php
            while ( have_posts() ) : the_post();

                get_template_part( 'template-parts/content', get_post_type() );

                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>
            </main><!-- #main -->
        </div>

	    <?php get_sidebar() ; ?>

    </div><!-- #primary -->
<?php

get_footer();
