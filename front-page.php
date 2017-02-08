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
add_action('genesis_after_entry_content','gem_home_featured_products');
add_action('genesis_after_entry_content','gem_home_call_to_action_banner');
add_action('genesis_after_entry_content','gem_product_categories');
add_action('genesis_after_entry_content','gem_featured_blog_post');
add_action('genesis_after_entry_content','gem_footer_testimonial');
add_action('genesis_before_loop','gem_share_links');

function gem_share_links(){
    echo "<div class='row'>";
    echo "<div class='col-xs-2 col-xs-offset-10'>";
    echo "<div class='share_links text-right'>";
        echo "Share";
    echo "</div>";
    echo "</div>";
}

function rpp_slide_show()
{
    ?>
    <div id="welcome" class="row">
    <div id="welcome-carousel" class="carousel slide"  data-interval=6000>
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
                    <div class="slide-content-container">
                        <div class="slide-content  <?php echo strtolower(get_sub_field('slide_h_position')); ?>">
                            <h2 class="slide-text"><?php echo get_sub_field('slide_header_1'); ?></h2>
                            <h1 class="slide-text"><?php echo get_sub_field('slide_header_2'); ?></h1>
                        </div>
                    </div>

                </div>


                <?php

            endwhile;
            echo '</div>';

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


    <?php
}

function gem_home_header() {

     return get_field('home-top-heading');

}

function gem_home_featured_products() {
    ?>

    <div class='home-featured-products row'>
        <h1>Featured Products<small> [<a href='#' class='view-all-link'> view all </a>]</small></h1>
    </div>

    <div class="row">

        <div class="container-fluid">
   <?php
    $ids = get_field('home_featured_products', false, false);
    var_dump($ids);
    foreach ($ids as $id){
        echo $id;
        echo do_shortcode("[product id=" . $id . "]");
    }
    ?>
    </div>

    </div>
    <?php
}

function gem_home_call_to_action_banner() {
    $headline = get_field('cta_headline');
    $subhead = get_field('cta_subhead');
    $button_text = get_field('cta_button_text');
    $button_link = get_field('cta_button_link');
    $background = get_field('cta_background_image');
    echo "<div class='home-cta'>";
    echo "<div class='row'>";
       echo "<div class='cta' style='background-image: url($background)'>";
                echo "<div class='cta_content'>";

        echo "<h1>$headline <small>$subhead</small> <a class='pull-right btn btn-default form-inline' href='$button_link'>$button_text</a></h1>";
      echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";


}


function gem_product_categories() {

    echo "<div class='home-product-categories'>";
    echo "<h1>Our Gem Stones<small> [<a href='#' class='view-all-link'> view all </a>]</small></h1>";

    if (shortcode_exists('product_categories')) {
        global $shortcode_tags;
        $func = $shortcode_tags['product_categories'];
        echo call_user_func( $func, array(
            'columns' => 4,
            'parent' => 0,
            'number' => 8

        ) );
    }
    echo "</div>";
}

function gem_featured_blog_post() {

$args = array(
    'numberposts' => 1,
    'offset' => 0,
    'category' => 0,
    'orderby' => 'post_date',
    'order' => 'DESC',
    'include' => '',
    'exclude' => '',
    'meta_key' => '',
    'meta_value' =>'',
    'post_type' => 'post',
    'post_status' => 'publish',
    'suppress_filters' => true
);

$recent_posts = wp_get_recent_posts( $args, ARRAY_A );

?>
    <div class="home-recent-posts">
        <div class="row">
            <h1>Learn More<small> [<a href='#' class='view-all-link'> view all </a>]</small></h1>
            <div class="col-xs-6">
                <img class="featured-blog-image" src="http://unsplash.it/600/350">
            </div>
            <div class="col-xs-6 featured-blog-post">
                <h1><?php echo $recent_posts[0]['post_title'] ?></h1>
                <p><?php the_excerpt($recent_posts[0]["ID"]) ?></p>
                <a class="btn btn-default" href="<?php echo get_permalink( get_option( 'page_for_posts' ) ) ?>"> READ MORE</a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4 col-xs-offset-7">
            </div>
        </div>
    </div>
<?php

    wp_reset_query();

}

function gem_footer_testimonial() {

    $args = array(
        'posts_per_page'   => 1,
        'offset'           => 0,
        'category'         => '',
        'category_name'    => '',
        'orderby'          => 'rand',
        'order'            => 'DESC',
        'include'          => '',
        'exclude'          => '',
        'meta_key'         => '',
        'meta_value'       => '',
        'post_type'        => 'testimonial',
        'post_mime_type'   => '',
        'post_parent'      => '',
        'author'	   => '',
        'author_name'	   => '',
        'post_status'      => 'publish',
        'suppress_filters' => true
	    );



    $random_testimonials = get_posts( $args );
    foreach ( $random_testimonials as $post ) : setup_postdata( $post );

    ?>

        <div class="footer-testimonial">
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1">
                    <h1 class="text-center"><?php echo $post->post_content ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="text-center">
                    <a class="btn btn-default text-center" href="#">More Customer Stories</a>
                </div>
            </div>

            <?php endforeach;
            wp_reset_postdata();?>
        </div>
<?php
}



genesis();
