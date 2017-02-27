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
add_theme_support('woocommerce');

// Add WooCommerce support for Genesis layouts (sidebar, full-width, etc) - Thank you Kelly Murray/David Wang
add_post_type_support('product', array('genesis-layouts', 'genesis-seo'));

// Unhook WooCommerce Sidebar - use Genesis Sidebars instead
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// Unhook WooCommerce wrappers
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

// Hook new functions with Genesis wrappers
add_action('woocommerce_before_main_content', 'bfg_my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'bfg_my_theme_wrapper_end', 10);

// Remove woocommerce breadcrumbs
add_action('init', 'gem_remove_wc_breadcrumbs');
function gem_remove_wc_breadcrumbs()
{
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
}

// Add opening wrapper before WooCommerce loop
function bfg_my_theme_wrapper_start()
{

    do_action('genesis_before_content_sidebar_wrap');
    genesis_markup(array(
        'html5' => '<div %s>',
        'xhtml' => '<div id="content-sidebar-wrap">',
        'context' => 'content-sidebar-wrap',
    ));

    do_action('genesis_before_content');
    genesis_markup(array(
        'html5' => '<main %s>',
        'xhtml' => '<div id="content" class="hfeed">',
        'context' => 'content',
    ));
    do_action('genesis_before_loop');

}


/* Add closing wrapper after WooCommerce loop */
function bfg_my_theme_wrapper_end()
{

    do_action('genesis_after_loop');
    genesis_markup(array(
        'html5' => '</main>', //* end .content
        'xhtml' => '</div>', //* end #content
    ));
    do_action('genesis_after_content');

    echo '</div>'; //* end .content-sidebar-wrap or #content-sidebar-wrap
    do_action('genesis_after_content_sidebar_wrap');

}

// ADD BOOTSTRAP CLASSES TO WOOCOMMERCE FORMS
//Form Fields

add_filter('woocommerce_form_field_args', 'gem_bs_woocommerce', 1);

function gem_bs_woocommerce($args)
{

    if ($args['type'] != 'country' && $args['type'] != 'state') {
        $args['class'][] = 'form-group';
        $args['input_class'][] = 'form-control';
        $args['placeholder'] = $args['label'];
        //    var_dump($args);
    }


    return $args;

}

// Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
function woocommerce_header_add_to_cart_fragment($fragments)
{
    ob_start();
    ?>
    <span class="cart_contents_count updated"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    <?php

    $fragments['.cart_contents_count'] = ob_get_clean();

    return $fragments;
}

// Remove each style one by one
add_filter('woocommerce_enqueue_styles', 'gem_dequeue_styles');
function gem_dequeue_styles($enqueue_styles)
{
//	unset( $enqueue_styles['woocommerce-general'] );	// Remove the gloss
    unset($enqueue_styles['woocommerce-layout']);        // Remove the layout
    unset($enqueue_styles['woocommerce-smallscreen']);    // Remove the smallscreen optimisation
    return $enqueue_styles;
}

