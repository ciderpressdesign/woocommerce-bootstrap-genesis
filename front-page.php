<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 1/16/2016
 * Time: 2:21 PM
 */


//* Remove the entry title (requires HTML5 theme support)
add_action('genesis_after_header', 'rpp_slide_show');
//remove_action('genesis_loop', 'genesis_do_loop');
//add_action('genesis_loop', 'rpp_do_map_loop');
add_filter('genesis_post_title_text', 'gem_home_header');
add_action('genesis_after_content_sidebar_wrap', 'gem_home_featured_products');
add_action('genesis_after_content_sidebar_wrap', 'gem_home_call_to_action_banner');
add_action('genesis_after_content_sidebar_wrap', 'gem_product_categories');
add_action('genesis_after_content_sidebar_wrap', 'gem_featured_blog_post');
add_action('genesis_after_content_sidebar_wrap', 'gem_footer_testimonial');
add_action('genesis_before_post', 'gem_share_links');

function gem_share_links()
{
    echo "<div class='share_link'>";
    echo "Share";
    echo "</div>";
}

function rpp_slide_show()
{
    ?>
    <div class="home-slide-show container-fluid">
        <div id="welcome" class="row">
            <div id="welcome-carousel" class="carousel slide" data-interval=6000>
                <div class="carousel-inner" role="listbox">

                    <?php
                    // Slides ---------------------------------------------------------------------------------
                    $active = "active";

                    while (have_rows('slide')) : the_row();
                        $slide_image = wp_get_attachment_image_url(get_sub_field('slide_image'), 'full');

                        ?>
                        <div class="item <?php echo $active;
                        $active = ''; ?>">
                            <div class="slide-image">
                                <img class="img-responsive" src="<?php echo $slide_image ?>"/>
                            </div>
                            <div class="slide-content-container container">
                                <div class="slide-content  <?php echo strtolower(get_sub_field('slide_h_position')); ?>">
                                    <h2 class="slide-text"><?php echo get_sub_field('slide_header_1'); ?></h2>
                                    <h1 class="slide-text"><?php echo get_sub_field('slide_header_2'); ?></h1>
                                </div>
                            </div>

                        </div>

                        <?php

                    endwhile;
                    ?>
                    <!-- Controls -->
                    <a class="left carousel-control" href="#welcome-carousel" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#welcome-carousel" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
                <div class="container">

                    <div class="row">
                        <?php
                        //-------------------------------------INDICATORS
                        $active = "active";
                        $i = 0;
                        echo '<ol class="carousel-indicators">';
                        $rows = get_field("slide");
                        foreach ($rows as $row) {
                            echo '<li data-target="#welcome-carousel" data-slide-to="' . $i . '" class="' . $active . '"></li>';
                            $active = '';
                            $i++;
                        }
                        ?>


                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
}

function gem_home_header()
{

    return get_field('home-top-heading');

}

function gem_home_featured_products()
{
    global $post;

    ?>
    <div class="home-section home-featured-products container">
        <div class='row'>
            <div class="col-xs-12">
                <h2> <?php the_field('featured_products_header') ?>
                    <small> [<a href='<?php echo get_permalink(wc_get_page_id('shop')) ?>' class='view-all-link'> view
                            all </a>]
                    </small>
                </h2>
            </div>

        </div>

        <div class="row">
            <ul class="list-unstyled">

                <?php
                // check if the repeater field has rows of data
                if (have_rows('featured_products')):
                    // loop through the rows of data
                    while (have_rows('featured_products')) : the_row();
                        echo '<div class=" col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0">';
                        $type = get_sub_field('featured_type');

                        if ("product" == $type) {
                            $post_object = get_sub_field('select_featured_product');
                            $post = $post_object;

                            setup_postdata($post);
                            wc_get_template_part('content', 'product');

                            wp_reset_postdata();

                        } elseif ("category" == $type) {

                            $category = get_sub_field('select_featured_category');

                            wc_get_template('content-product_cat.php', array(
                                'category' => $category
                            ));
                        }
                        echo "</div>";
                    endwhile;

                else :

                    // no rows found

                endif;

                ?>

            </ul>
        </div>
    </div>
    <?php
}


function gem_home_call_to_action_banner()
{
    $headline = get_field('cta_headline');
    $subhead = get_field('cta_subhead');
    $button_text = get_field('cta_button_text');
    $button_link = get_field('cta_button_link');
    $background = get_field('cta_background_image');
    ?>
    <div class="home-section home-cta" style='background-image: url(<?php echo $background ?>)'>
        <div class="container">
            <div class='row'>
                <div class='cta clearfix'>
                    <div class='cta_content clearfix'>
                        <div class="col-sm-6 cta-col">
                            <div class="cta-headline"><?php echo $headline ?></div>
                        </div>
                        <div class="col-sm-3 cta-col">
                            <div class="cta-subhead"><?php echo $subhead ?></div>
                        </div>

                        <div class="col-sm-3 cta-col">
                            <a class='btn btn-default btn-block'
                               href='<?php echo $button_link ?>'><?php echo $button_text ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}


function gem_product_categories()
{
    $headline = get_field('featured_products_header');
    $woocommerce_shop_url = wc_get_page_permalink('shop');
    echo "<div class='home-section home-product-categories container'>";
    echo "<h2>$headline<small> [<a href='$woocommerce_shop_url' class='view-all-link'> view all </a>]</small></h2>";

    if (shortcode_exists('product_categories')) {
        global $shortcode_tags;
        $func = $shortcode_tags['product_categories'];
        echo call_user_func($func, array(
            'columns' => 4,
            'parent' => 0,
            'number' => 8

        ));
    }
    echo "</div>";
}

function gem_featured_blog_post()
{


    $recent_post = get_field('featured_blog_post');
    $recent_posts_headline = get_field('featured_blog_post_headline');
    if ($recent_post) :
        //override $post
        $post = $recent_post;
        setup_postdata($post);
        $image_arr = get_field('blog_post_image', $post->ID);
        $image = $image_arr['sizes']['shop_single'];
        ?>
        <div class="home-section home-recent-posts">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12"><h2><?php echo $recent_posts_headline ?></h2></div>
                    <div class="col-sm-6">
                        <img class="featured-blog-image" src="<?php echo $image ?>">
                    </div>
                    <div class="col-sm-6 featured-blog-post">
                        <h2><?php echo $post->post_title ?></h2>
                        <p><?php echo get_the_excerpt($post->ID) ?></p>
                        <a class="btn btn-default" href="<?php echo get_permalink($post->ID) ?>"> READ MORE</a>
                    </div>
            </div>
        </div>
        </div>
        <?php

        wp_reset_postdata();

    endif;
}

function gem_footer_testimonial()
{

    $args = array(
        'posts_per_page' => 1,
        'offset' => 0,
        'category' => '',
        'category_name' => '',
        'orderby' => 'rand',
        'order' => 'DESC',
        'include' => '',
        'exclude' => '',
        'meta_key' => '',
        'meta_value' => '',
        'post_type' => 'testimonial',
        'post_mime_type' => '',
        'post_parent' => '',
        'author' => '',
        'author_name' => '',
        'post_status' => 'publish',
        'suppress_filters' => true
    );


    ?>

    <div class="footer-testimonial">
        <div class="container">
            <?php
            $random_testimonials = get_posts($args);
            foreach ($random_testimonials as $post) : setup_postdata($post);
                ?>
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1">
                        <div class="text-center testimonial-quote"><?php echo $post->post_content ?></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <a class="btn btn-default text-center"
                           href="<?php echo get_post_type_archive_link('testimonial') ?>">More
                            Customer Stories</a>
                    </div>
                </div>

            <?php endforeach;
            wp_reset_postdata(); ?>
        </div>
    </div>
    <?php
}


genesis();
