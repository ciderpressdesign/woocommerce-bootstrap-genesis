<?php
/**
 * Created by PhpStorm.
 * User: snowd
 * Date: 1/23/2017
 * Time: 5:14 PM
 */

/**********************************
 *
 * Integrate WooCommerce with Genesis.
 *
 * Unhook WooCommerce wrappers and
 * Replace with Genesis wrappers.
 *
 * Reference Genesis file:
 * genesis/lib/framework.php
 *
 * @author AlphaBlossom / Tony Eppright
 * @link http://www.alphablossom.com
 *
 **********************************/


//* Declare WooCommerce Support
add_theme_support( 'woocommerce' );

// Add WooCommerce support for Genesis layouts (sidebar, full-width, etc) - Thank you Kelly Murray/David Wang
add_post_type_support( 'product', array( 'genesis-layouts', 'genesis-seo' ) );

// Unhook WooCommerce Sidebar - use Genesis Sidebars instead
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Unhook WooCommerce wrappers
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Hook new functions with Genesis wrappers
add_action( 'woocommerce_before_main_content', 'bfg_my_theme_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'bfg_my_theme_wrapper_end', 10 );

// Add opening wrapper before WooCommerce loop
function bfg_my_theme_wrapper_start() {

    do_action( 'genesis_before_content_sidebar_wrap' );
    genesis_markup( array(
        'html5' => '<div %s>',
        'xhtml' => '<div id="content-sidebar-wrap">',
        'context' => 'content-sidebar-wrap',
    ) );

    do_action( 'genesis_before_content' );
    genesis_markup( array(
        'html5' => '<main %s>',
        'xhtml' => '<div id="content" class="hfeed">',
        'context' => 'content',
    ) );
    do_action( 'genesis_before_loop' );

}

/* Remove archive titles */

//Removes Title and Description on Archive, Taxonomy, Category, Tag
remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );


/* Add closing wrapper after WooCommerce loop */
function bfg_my_theme_wrapper_end() {

    do_action( 'genesis_after_loop' );
    genesis_markup( array(
        'html5' => '</main>', //* end .content
        'xhtml' => '</div>', //* end #content
    ) );
    do_action( 'genesis_after_content' );

    echo '</div>'; //* end .content-sidebar-wrap or #content-sidebar-wrap
    do_action( 'genesis_after_content_sidebar_wrap' );

}

//Add Alphabetical sorting option to shop page / WC Product Settings
add_filter( 'woocommerce_get_catalog_ordering_args', 'sv_alphabetical_woocommerce_shop_ordering' );


function sv_alphabetical_woocommerce_shop_ordering( $sort_args ) {
    $orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

    if ( 'alphabetical' == $orderby_value ) {
        $sort_args['orderby'] = 'title';
        $sort_args['order'] = 'asc';
        $sort_args['meta_key'] = '';
    }

    return $sort_args;
}

function sv_custom_woocommerce_catalog_orderby( $sortby ) {
    $sortby['alphabetical'] = 'Sort by name: alphabetical';
    return $sortby;
}
add_filter( 'woocommerce_default_catalog_orderby_options', 'sv_custom_woocommerce_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'sv_custom_woocommerce_catalog_orderby' );


// Filter and Sort Widget Areas

 genesis_register_sidebar( array(
    'id'		=> 'filter-widgets',
    'name'		=> __( 'Filter Tab', 'gem' ),
    'description'	=> __( 'This is the widget area for the filter tab.', 'gem' ),
) );


genesis_register_sidebar( array(
    'id'		=> 'sort-widgets',
    'name'		=> __( 'Sort Tab', 'gem' ),
    'description'	=> __( 'This is the widget area for the sort tab.', 'gem' ),
) );




// Remove title from Taxonomy Archive and Move Title to Filter Container

add_action('woocommerce_before_main_content','gem_product_filters',20);
add_filter('woocommerce_show_page_title',function() {return false;});


function gem_product_filters()
{
    ?>

    <div class="row">
        <div class="filter-sort-tabs">
            <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

            <ul class="nav nav-tabs" role="tablist">

                <!--        Order is reversed because of float right -->

                <li role="presentation"><a href="#sortby" aria-controls="profile" role="tab" data-toggle="tab">Sort By
                        <span class="tab-close"></span></a></li>
                <li role="presentation" class="">
                    <a aria-controls="home" href="#filterby" role="tab" data-toggle="tab">Filter By <span
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


    <?php
}

//    Product Overlay


// For Shop Loop
//
    // Remove product link from shop loop
    remove_action('woocommerce_before_shop_loop_item','woocommerce_template_loop_product_link_open');
    remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_product_link_close');
    remove_action('woocommerce_before_shop_loop_item_title','woocommerce_template_loop_product_thumbnail');
    remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart');

    // Add link to shop loop
    add_action('woocommerce_before_shop_loop_item','gem_template_loop_product_element_open');
    add_action('woocommerce_after_shop_loop_item','gem_template_loop_product_element_close');

    // Add overlay to image
    add_action('woocommerce_before_shop_loop_item_title','gem_template_loop_product_thumbnail');



    function gem_template_loop_product_thumbnail() {
        ?>
        <div class="product_image">
            <div class="product_overlay">
                <div class="product_overlay_content col-xs-10 col-xs-offset-1">
                    <div class="">
                        <?php woocommerce_template_loop_add_to_cart( array(
                            'class' => 'product_type_simple add_to_cart_button ajax_add_to_cart'
                        ) ) ?>
                    </div>
                    <div class="">
                        <?php woocommerce_template_loop_product_link_open() ?>
                        <span class="glyphicon glyphicon-play circle-white"></span>
                        Details
                        <?php woocommerce_template_loop_product_link_close() ?>
                    </div>
                </div>
            </div>
            <?php woocommerce_template_loop_product_thumbnail(); ?>
        </div>

        <?php
    }



    function gem_template_loop_product_element_open() {
        echo "<div class='loop-product-block'>";
    }

    function gem_template_loop_product_element_close() {
        echo "</div>";
    }

    function gem_product_overlay() {
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


    // ADD BOOTSTRAP CLASSES TO WOOCOMMERCE FORMS

    //Form Fields


add_filter( 'woocommerce_form_field_args', 'gem_bs_woocommerce',1);

function gem_bs_woocommerce($args) {

    if($args['type'] != 'country' && $args['type'] != 'state') {
          $args['class'][] = 'form-group';
        $args['input_class'][] = 'form-control';
        $args['placeholder'] = $args['label'];
    //    var_dump($args);
    }


    return $args;

}
