<?php
/**
 * Navigation
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://www.recommendwp.com
 * @author       RecommendWP <www.recommendwp.com>
 * @copyright    Copyright (c) 2015, RecommendWP
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */

if (class_exists('UberMenuStandard')) {
    return;
}

// remove primary & secondary nav from default position
remove_action('genesis_after_header', 'genesis_do_nav');
//add_action( 'genesis_before', 'genesis_do_nav' );

remove_action('genesis_after_header', 'genesis_do_subnav');
//add_action( 'genesis_before_footer', 'genesis_do_subnav',9 );

// filter menu args for bootstrap walker and other settings
add_filter('wp_nav_menu_args', 'bfg_nav_menu_args_filter');

// add bootstrap markup around the nav
//add_filter( 'wp_nav_menu', 'bfg_nav_menu_markup_filter', 10, 2 );


add_action('genesis_before', 'off_canvas_menu');

function off_canvas_menu()
{
    ?>
    <div id="sidr" class="offcanvas" xmlns="http://www.w3.org/1999/html">
        <div class="sidebar-menu">
            <nav class="">
                <div class="center-block">
                    <!--           Close Button            -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12"><a class="sidr-close-link sidebar-menu--section pull-right"
                                                      href="#sidr">
                                    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/close.png">
                                </a>
                            </div>
                        </div>
                        <!--           Logo                  -->
                        <div class="row">
                            <div class="col-xs-10 col-xs-offset-1">
                                <div class="sidebar-menu--section">
                                    <?php if (!is_front_page()) : ?>
                                        <a href="<?php echo (!is_front_page()) ? get_site_url() : ""; ?>">
                                            <img src="<?php echo get_stylesheet_directory_uri() ?>/images/logo-light.png">
                                        </a>
                                    <?php else: ?>

                                        <img src="<?php echo get_stylesheet_directory_uri() ?>/images/logo-light.png">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!--           Search                  -->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="sidebar-menu--search sidebar-menu--section">
                                    <?php echo genesis_search_form() ?>
                                </div>
                            </div>
                        </div>
                        <!--           Account and Cart Links                  -->
                        <div class="row">
                            <div class="col-xs-12">
                                <ul class="sidebar-menu--section sidebar-menu--shop-links list-inline">
                                    <li class="sidebar-account-link">
                                        <?php bfg_account_link() ?>
                                    </li>
                                    <li class="sidebar-cart-link">
                                        <?php bfg_cart_link() ?>
                                    </li>
                                </ul>
                            </div>
                            <!--              Shop Category Menu                     -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="sidebar-menu--category sidebar-menu--section">
                                        <div class="sidebar-menu--category-menu-header">
                                            Shop
                                        </div>
                                        <div class="sidebar-menu--category-menu">
                                            <?php genesis_do_nav() ?>
                                        </div>
                                    </div>
                                </div>
                        </div>
                            <!--           Other Menu                 -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="sidebar-menu--other-menu sidebar-menu--section">
                                        <?php
                                        wp_nav_menu(array(
                                            'menu' => 'Other',
                                            'container' => 'div',
                                            'container_class' => 'wrap',
                                            'menu_class' => 'nav',
                                            'depth' => 1,
                                            'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
                                            'walker' => new wp_bootstrap_navwalker()
                                        )); ?>
                                    </div>
                                </div>
                        </div>
                    </div>
            </nav>
            <!--           Subscribe and Save           -->
            <div class="container-fluid">
                <?php social_links_block() ?>
            </div>
            <!--           Info Links                  -->
            <div class="sidebar-menu--info-links sidebar-menu--section">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="telephone"><a href="tel:">888-GEM-SYND</a></div>
                        </div>
                        <div class="col-xs-12">
                            <div class="copyright">
                                &copy;2017 The Gem Syndicate
                            </div>
                        </div>
                        <div class="col-xs-12 privacy-sitemap">
                            <div class="row">
                                <a class="privacy" href="">privacy</a>
                                <a class="site-map-link" href="">site map</a>
                            </div>
                        </div>
                        <div class="col-xs-12 shipping-terms">
                            <div class="row">
                                <a class="shipping" href="">shipping / returns</a>
                                <a class="terms" href="">terms</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php

}

function bfg_cart_link()
{
    //if (sizeof(WC()->cart->get_cart()) != 0) :
    ?>
    <a href="<?php echo wc_get_cart_url(); ?>">
        <span class="cart-link--text">MY CART</span>
        <span class="cart_contents_count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    </a>
    <?php
    //endif;
}

function bfg_account_link()
{
    ?>
    <a class="account-link" href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>"
       title="<?php _e('My Account', ''); ?>">
        <?php _e('MY ACCOUNT', ''); ?>
    </a>

    <?php
}

add_action('genesis_before_header', 'bfg_add_overlay');

function bfg_add_overlay()
{
    ?>
    <div class="site-overlay sidr-close-link">
    </div>
    <?php
}

add_action('genesis_header', 'bfg_top_bar');

function bfg_top_bar()
{

    ?>
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="row">
        <div class="navbar-header col-xs-4">
            <?php echo apply_filters('bfg_navbar_brand', bfg_navbar_brand_markup()); ?>
        </div>
        <div class="nav-links col-xs-8">

            <ul class="list-inline pull-right">
                <li class="account-link hidden-sm">
                    <?php bfg_account_link() ?>
                </li>
                <li class="cart-link">
                    <?php bfg_cart_link() ?>
                </li>
                <li>
                    <div class="search-expanding hidden-xs">
                        <?php echo genesis_search_form() ?>
                    </div>
                </li>
                <li class="sidebar-menu-link">
                    <a class="menu-link" href="#sidr">
                        <span class="dashicons dashicons-menu"></span>
                    </a>
                </li>
            </ul>

        </div>
    </div>
    <?php
}


function bfg_nav_menu_args_filter($args)
{

    require_once(BFG_THEME_MODULES . 'wp_bootstrap_navwalker.php');

    $navalign = get_theme_mod('navalign', false);

    if ('primary' === $args['theme_location']) {
        $args['menu_class'] = 'nav' . $navalign;
        $args['fallback_cb'] = 'wp_bootstrap_navwalker::fallback';
        $args['walker'] = new wp_bootstrap_navwalker();
    }
    return $args;
}


function bfg_nav_menu_markup_filter($html, $args)
{
    // only add additional Bootstrap markup to
    // primary and secondary nav locations
    if ('primary' !== $args->theme_location) {
        return $html;
    }

    $output .= genesis_html5() ? "<nav>" : "<div>";
    // $output .= genesis_html5() ? "<nav class=\"collapse navbar-collapse\" id=\"{$data_target}\">" : "<div class=\"collapse navbar-collapse\" id=\"{$data_target}\">";
    $output .= $html;

    if (get_theme_mod('navextra', false)) {
        $output .= apply_filters('bfg_navbar_content', bfg_navbar_content_markup());
    }
    $output .= genesis_html5() ? '</nav>' : '</div>'; // .collapse .navbar-collapse

    return $output;
}


function bfg_navbar_brand_markup()
{
    // Disable link if on home page
    $brand_tag = (is_front_page()) ? 'div' : 'a';

    // Display navbar brand on small displays
    $output = '<' . $brand_tag . ' ';
    $output .= 'class="navbar-brand" id="logo" title="' . esc_attr(get_bloginfo('description')) . '" href="' . esc_url(home_url('/')) . '">';
    $output .= '</' . $brand_tag . '>';


    return $output;
}

//* Navigation Extras
function bfg_navbar_content_markup()
{
    $url = get_home_url();

    $choices = get_theme_mod('select', false);
    switch ($choices) {
        case 'search':
        default:
            $output = '<form method="get" class="navbar-form navbar-right" action="' . $url . '" role="search">';
            $output .= '<div class="form-group">';
            $output .= '<input class="form-control" name="s" placeholder="Search" type="text">';
            $output .= '</div>';
            $output .= '<button class="btn btn-default" value="Search" type="submit">Submit</button>';
            $output .= '</form>';
            break;
        case 'date':
            $output = '<p class="navbar-text navbar-right">';
            $output .= date_i18n(get_option('date_format'));
            $output .= '</p>';
            break;
    }

    return $output;
}

//* Filter primary navigation output to match Bootstrap markup
// @link http://wordpress.stackexchange.com/questions/58377/using-a-filter-to-modify-genesis-wp-nav-menu/58394#58394
add_filter('genesis_do_nav', 'bfg_override_do_nav', 10, 3);
function bfg_override_do_nav($nav_output, $nav, $args)
{
    // return the modified result
    return sprintf('%1$s', $nav);

}


/* Add inline style to provide dynamic background to navbar */
function gem_add_header_image_style()
{
    global $post;
    // First try current object for banner image
    if (get_field('banner_image')) {

        $header_image = get_field('banner_image');
    } elseif (get_field('banner_image', 'product_category')) {
        //no post banner? try category banner
        $header_image = get_field('banner_image', 'product_category');
    } else {
        // Use default from theme options
        $header_image = get_field('banner_image', 'options');
    }

    $header_background_style = " .home .site-header.active, .site-header, .site-header.active { background-image: url($header_image); } ";

    wp_add_inline_style('app-css', $header_background_style);
}

add_action('wp_enqueue_scripts', 'gem_add_header_image_style', 20);


