<?php
/**
 * Created by PhpStorm.
 * User: snowd
 * Date: 2/24/2017
 * Time: 1:55 PM
 */


// For stuff that adjusts the Woocommerce loops that display multiple products

/* Remove default sorting -- we'll do it in JS */

remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);


/* Remove archive titles */

//Removes Title and Description on Archive, Taxonomy, Category, Tag
remove_action('genesis_before_loop', 'genesis_do_taxonomy_title_description', 15);

// No pagination--we'll do it with javascript
add_filter('loop_shop_per_page', function ($cols) {
    return -1;
});

//Add Alphabetical sorting option to shop page / WC Product Settings
add_filter('woocommerce_get_catalog_ordering_args', 'sv_alphabetical_woocommerce_shop_ordering');


function sv_alphabetical_woocommerce_shop_ordering($sort_args)
{
    $orderby_value = isset($_GET['orderby']) ? wc_clean($_GET['orderby']) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));

    if ('alphabetical' == $orderby_value) {
        $sort_args['orderby'] = 'title';
        $sort_args['order'] = 'asc';
        $sort_args['meta_key'] = '';
    }

    return $sort_args;
}

function sv_custom_woocommerce_catalog_orderby($sortby)
{
    $sortby['alphabetical'] = 'Sort by name: alphabetical';
    return $sortby;
}

add_filter('woocommerce_default_catalog_orderby_options', 'sv_custom_woocommerce_catalog_orderby');
add_filter('woocommerce_catalog_orderby', 'sv_custom_woocommerce_catalog_orderby');


// register Filter and Sort Widget Areas that will appear above product listings

genesis_register_sidebar(array(
    'id' => 'filter-widgets',
    'name' => __('Filter Tab', 'gem'),
    'description' => __('This is the widget area for the filter tab.', 'gem'),
));


genesis_register_sidebar(array(
    'id' => 'sort-widgets',
    'name' => __('Sort Tab', 'gem'),
    'description' => __('This is the widget area for the sort tab.', 'gem'),
));


// Remove title from Taxonomy Archive and Move Title to Filter Container

add_action('woocommerce_before_main_content', 'gem_product_filters', 20);
add_filter('woocommerce_show_page_title', '__return_false');


function gem_product_filters()
{
    // Don't show on single product or home page
    if (is_home() or is_product()) {
        return;
    }
    ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="filter-sort-tabs">
                <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

                <ul class="nav nav-tabs" role="tablist">

                    <!--        Order is reversed because of float right -->

                    <li role="presentation"><a href="#sortby" aria-controls="profile" role="tab" data-toggle="tab">Sort
                            By
                            <span class="tab-close"></span></a></li>
                    <li role="presentation" class="filter-by-tab">
                        <a aria-controls="home" href="#filterby" class="" role="tab" data-toggle="tab">Filter By <span
                                    class="tab-close"></span></a>
                    </li>
                </ul>
            </div>


            <!-- Tab panes -->
            <div class="tab-content filter-sort-content">
                <div role="tabpanel" class="tab-pane fade" id="filterby">
                    <?php
                    genesis_widget_area('filter-widgets', array(
                        'before' => '<div class="filter-widget"><div class="wrap">',
                        'after' => '</div></div>',
                    ));

                    ?>
                    <div class="filter-shapes">
                        <ul class="list-inline">
                            <?php
                            $images = get_field('shape_image', 'options');

                            foreach ($images as $image) {
                                // The "name" field actually stores the product term.
                                $term = $image['name'];
                                $image = $image['image'];
                                $term_filter_class = "." . $term->taxonomy . "-" . $term->slug;
                                if ($term->count and $image) {

                                    ?>
                                    <li class="filter-shapes--shape">
                                        <div href="" data-filter="<?php echo $term_filter_class ?>"
                                             class="filter-shapes--shape-link">

                                            <img src="<?php echo $image ?>" alt="" class="filter-shapes--shape--image">
                                            <p class="filter-shapes--shape--name"><?php echo $term->name ?></p>
                                        </div>
                                    </li>
                                    <?php
                                }
                            }


                            ?>


                        </ul>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="sortby">
                    <?php
                    genesis_widget_area('sort-widgets', array(
                        'before' => '<div class="sort-widget"><div class="wrap">',
                        'after' => '</div></div>',
                    ));
                    ?>
                </div>
            </div>

        </div>
    </div>

    <?php
}

//    ----------------------  Product Overlay


// For Shop Loop
//
// Remove product link from shop loop
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open');
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_product_link_close');
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail');
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');

// Add link to shop loop
add_action('woocommerce_before_shop_loop_item', 'gem_template_loop_product_element_open');
add_action('woocommerce_after_shop_loop_item', 'gem_template_loop_product_element_close');

// Add overlay to image
add_action('woocommerce_before_shop_loop_item_title', 'gem_template_loop_product_thumbnail');

// add link to the product title and price
add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open');
add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_product_link_close');

function gem_template_loop_product_thumbnail()
{
    ?>
    <div class="product_image">
        <div class="product_overlay">
            <div class="product_overlay_content col-xs-10 col-xs-offset-2">
                <div class="overlay--product-links">
                    <div class="overlay--product-link">
                        <img src="<?php echo get_theme_file_uri() ?>/images/plus.png">
                        <?php woocommerce_template_loop_add_to_cart(array(
                            'class' => 'product_type_simple add_to_cart_button ajax_add_to_cart'
                        )) ?>
                    </div>
                    <div class="overlay--product-link">
                        <img src="<?php echo get_theme_file_uri() ?>/images/play.png">
                        <?php woocommerce_template_loop_product_link_open() ?>
                        Details
                        <?php woocommerce_template_loop_product_link_close() ?>
                    </div>
                </div>
            </div>
        </div>

        <?php woocommerce_template_loop_product_link_open() ?>
        <?php woocommerce_template_loop_product_thumbnail(); ?>
        <?php woocommerce_template_loop_product_link_close() ?>
    </div>

    <?php
}


function gem_template_loop_product_element_open()
{
    echo "<div class='loop-product-block'>";
}

function gem_template_loop_product_element_close()
{
    echo "</div>";
}

function gem_product_overlay()
{
    ?>
    <div class="product_overlay">
        <div class="row">
            <?php woocommerce_template_loop_add_to_cart() ?>
        </div>
        <div class="row">
            <?php woocommerce_template_loop_product_link_open() ?>
            Details
            <?php woocommerce_template_loop_product_link_close() ?>
        </div>
    </div>


    <?php

}


