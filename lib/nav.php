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

if ( class_exists( 'UberMenuStandard' ) ) {
    return;
}

// remove primary & secondary nav from default position
remove_action( 'genesis_after_header', 'genesis_do_nav' );
//add_action( 'genesis_before', 'genesis_do_nav' );

remove_action('genesis_after_header','genesis_do_subnav');
//add_action( 'genesis_before_footer', 'genesis_do_subnav',9 );

// filter menu args for bootstrap walker and other settings
add_filter( 'wp_nav_menu_args', 'bfg_nav_menu_args_filter' );

// add bootstrap markup around the nav
//add_filter( 'wp_nav_menu', 'bfg_nav_menu_markup_filter', 10, 2 );


add_action('genesis_before','off_canvas_menu');

function off_canvas_menu() {
    ?>
    <div id="sidr" class="offcanvas" xmlns="http://www.w3.org/1999/html">
        <div class="sidebar-nav">
            <div class="container-fluid">
                <nav class="navbar navbar-inverse">
                    <div class="center-block">
                        <!--            <div class="navbar-header">-->

                        <div class="row">
                            <a class="sidr-close-link pull-right" href="#sidr"><span
                                    class="dashicons dashicons-no"></span></a>
                        </div>
                        <div class="row">
                            <?php
                            // only include blog name and description in the nav
                            // if it is the primary nav location
                            echo apply_filters('bfg_navbar_brand', bfg_navbar_brand_markup());
                            ?>
                        </div>
                        <?php echo genesis_search_form() ?>
                        <ul class="nav navbar-nav list-inline">
                            <li class="sidebar-account-link">
                                <?php bfg_account_link() ?>
                            </li>
                            <li class="sidebar-cart-link">
                                <?php bfg_cart_link() ?>
                            </li>
                        </ul>
                        <!--            </div>-->
                        <div class="sidebar-category-menu">
                            <?php genesis_do_nav() ?>
                        </div>
                        <div class="sidebar-other-menu">
                            <?php
                            wp_nav_menu(array(
                                'menu' => 'Other',
                                'container' => 'div',
                                'container_class' => 'wrap',
                                'menu_class' => 'nav navbar-nav',
                                'depth' => 1,
                                'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
                                'walker' => new wp_bootstrap_navwalker()
                            )); ?>
                        </div>


                    </div>
                </nav>
                <div class="container-fluid">
                    <?php subscribe_and_save_block() ?>
                </div>
            </div>
        </div>
    </div>

    <?php

}

function bfg_cart_link() {
    //if (sizeof(WC()->cart->get_cart()) != 0) :
    ?>
    <a href="<?php echo wc_get_cart_url(); ?>">
        MY CART
        <span class="dashicons dashicons-cart"></span>
        <span class="cart_contents_count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    </a>
    <?php
    //endif;
}

function bfg_account_link() {
    ?>
    <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"
       title="<?php _e('My Account',''); ?>">
        <?php _e('MY ACCOUNT',''); ?>
    </a>

    <?php
}

add_action('genesis_before_header','bfg_add_overlay');

function bfg_add_overlay() {
  ?>
    <div class="site-overlay sidr-close-link">
    </div>
  <?php
}

add_action('genesis_header','bfg_top_bar');

function bfg_top_bar() {

    ?>
    <!-- Brand and toggle get grouped for better mobile display -->

    <div class="navbar-header">
    <?php echo apply_filters( 'bfg_navbar_brand', bfg_navbar_brand_markup() ); ?>
<!--        <a class="menu-link navbar-toggle collapsed" href="#sidr">-->
<!--            <span class="sr-only">Toggle navigation</span>-->
<!--            <span class="icon-bar"></span>-->
<!--            <span class="icon-bar"></span>-->
<!--            <span class="icon-bar"></span>-->
<!--        </a>-->
    </div>
    <div class=""nav-links">

        <ul class="nav navbar-nav navbar-right">
            <li class="account-link">
                <?php bfg_account_link() ?>
            </li>
            <li class="cart-link">
                <?php bfg_cart_link() ?>
            </li>
            <li>
                <div class="search-expanding">
                    <?php echo genesis_search_form() ?>
<!--                <form method="get" class="search-form navbar-form navbar-right" action="%s" role="search">-->
<!--                    <div class="form-group">-->
<!--                        <label class="sr-only sr-only-focusable" for="bfg-search-form">%s</label>-->
<!--                        <div class="input-group">-->
<!--                            <input type="search" class="search-field form-control" id="bfg-search-form" name="s" %s="%s">-->
<!--                            <span class="input-group-btn">-->
<!--                    <button type="submit" class="search-submit btn btn-default" disabled>-->
<!--                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>-->
<!--                        <span class="sr-only">%s</span>-->
<!--                    </button>-->
<!--                </span>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </form>-->
                </div>
            </li>
            <li class="sidebar-menu-link">
                <a class="menu-link" href="#sidr">
                    <span class="dashicons dashicons-menu"></span>
                </a>
            </li>
        </ul>

    </div>

    <?php
}


function bfg_nav_menu_args_filter( $args ) {

    require_once( BFG_THEME_MODULES . 'wp_bootstrap_navwalker.php' );

    $navalign = get_theme_mod( 'navalign', false );
    
    if ( 'primary' === $args['theme_location'] ) {
        $args['menu_class'] = 'nav navbar-nav ' . $navalign;
        $args['fallback_cb'] = 'wp_bootstrap_navwalker::fallback';
        $args['walker'] = new wp_bootstrap_navwalker();
    }
    return $args;
}


function bfg_nav_menu_markup_filter( $html, $args ) {
    // only add additional Bootstrap markup to
    // primary and secondary nav locations
    if ( 'primary'   !== $args->theme_location ) {
        return $html;
    }

     $output .= genesis_html5() ? "<nav>" : "<div>";
   // $output .= genesis_html5() ? "<nav class=\"collapse navbar-collapse\" id=\"{$data_target}\">" : "<div class=\"collapse navbar-collapse\" id=\"{$data_target}\">";
    $output .= $html;

    if ( get_theme_mod( 'navextra', false ) ) {
        $output .= apply_filters( 'bfg_navbar_content', bfg_navbar_content_markup() );
    }
    $output .= genesis_html5() ? '</nav>' : '</div>'; // .collapse .navbar-collapse

    return $output;
}


//function bfg_nav_menu_markup_filter( $html, $args ) {
//    // only add additional Bootstrap markup to
//    // primary and secondary nav locations
//    if ( 'primary'   !== $args->theme_location ) {
//        return $html;
//    }
//
//    $output = '';
//
//     // only include blog name and description in the nav
//    // if it is the primary nav location
//    if ( 'primary' === $args->theme_location ) {
//        $output .= apply_filters( 'bfg_navbar_brand', bfg_navbar_brand_markup() );
//    }
//    $output .= $html;
//
//    if ( get_theme_mod( 'navextra', false ) ) {
//        $output .= apply_filters( 'bfg_navbar_content', bfg_navbar_content_markup() );
//    }
//    $output .= genesis_html5() ? '</nav>' : '</div>'; // .collapse .navbar-collapse
//
//    return $output;
//}

function bfg_navbar_brand_markup() {
    // Display navbar brand on small displays 
    $output = '<a class="navbar-brand" id="logo" title="'.esc_attr( get_bloginfo( 'description' ) ).'" href="'.esc_url( home_url( '/' ) ).'">';
    
    // $output .= apply_filters( 'bfg_nav_brand_args', get_bloginfo( 'name' ) );
    $output .= get_theme_mod( 'logo', false ) ? '<img src="'.get_theme_mod( 'logo' ).'" alt="'.esc_attr( get_bloginfo( 'description' ) ).'" />' : get_bloginfo( 'name' );

    $output .= '</a>';

    return $output;
}

//* Navigation Extras
function bfg_navbar_content_markup() {
    $url = get_home_url();
    
    $choices = get_theme_mod( 'select', false );
    switch( $choices ) {
        case 'search':
        default:
            $output = '<form method="get" class="navbar-form navbar-right" action="' .  $url . '" role="search">';
            $output .= '<div class="form-group">';
            $output .= '<input class="form-control" name="s" placeholder="Search" type="text">';
            $output .= '</div>';
            $output .= '<button class="btn btn-default" value="Search" type="submit">Submit</button>';
            $output .= '</form>';
            break;
        case 'date': 
            $output = '<p class="navbar-text navbar-right">';
            $output .= date_i18n( get_option( 'date_format' ) );
            $output .= '</p>';
            break;
    }

	return $output;
}

//* Filter primary navigation output to match Bootstrap markup
// @link http://wordpress.stackexchange.com/questions/58377/using-a-filter-to-modify-genesis-wp-nav-menu/58394#58394
add_filter( 'genesis_do_nav', 'bfg_override_do_nav', 10, 3 );
function bfg_override_do_nav($nav_output, $nav, $args) {
    // return the modified result
    return sprintf( '%1$s', $nav );

}