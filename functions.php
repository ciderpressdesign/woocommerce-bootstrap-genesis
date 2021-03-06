<?php
/**
 * Functions
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://www.recommendwp.com
 * @author       RecommendWP <www.recommendwp.com>
 * @copyright    Copyright (c) 2015, RecommendWP
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

add_action( 'genesis_setup', 'bfg_childtheme_setup', 15 );

function bfg_childtheme_setup() {
	// Start the engine
	include_once( get_template_directory() . '/lib/init.php' );

	// Child theme (do not remove)
    define('BFG_THEME_NAME', 'GemSyndicate');
    define('BFG_THEME_URL', '');
	define( 'BFG_THEME_LIB', CHILD_DIR . '/lib/' );
	define( 'BFG_THEME_LIB_URL', CHILD_URL . '/lib/' );
	define( 'BFG_THEME_IMAGES', CHILD_URL . '/images/' );
	define( 'BFG_THEME_JS', CHILD_URL . '/assets/js/' );
	define( 'BFG_THEME_CSS', CHILD_URL . '/assets/css/' );
	define( 'BFG_THEME_MODULES', CHILD_DIR . '/lib/modules/' );

	// Cleanup WP Head
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'start_post_rel_link' );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );

	// Add HTML5 markup structure
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	//* Unregister secondary navigation menu
	add_theme_support( 'genesis-menus', array(
	    'primary' => __( 'Primary Navigation Menu', 'genesis' ),
        'footer_1' => __( 'Footer 1 Navigation Menu', 'genesis' ),
        'footer_2' => __( 'Footer 2 Navigation Menu', 'genesis' ),
        'footer_3' => __( 'Footer 3 Navigation Menu', 'genesis' )
        )
    );

	// Add viewport meta tag for mobile browsers
	add_theme_support( 'genesis-responsive-viewport' );

	// Add support for 3-column footer widgets
	//add_theme_support( 'genesis-footer-widgets', 4 );

	// Structural Wraps
	add_theme_support( 'genesis-structural-wraps', array(
		'header',
        'content-sidebar-wrap',
//		'footer-widgets',
//		'footer',
//		'home-featured'
	) );

	// WooCommerce Support
//	add_theme_support( 'genesis-connect-woocommerce' );

	// Remove unneeded widget areas
	unregister_sidebar( 'header-right' );

	// Move Sidebar Secondary After Content
	remove_action( 'genesis_after_content_sidebar_wrap', 'genesis_get_sidebar_alt' );
	add_action( 'genesis_after_content', 'genesis_get_sidebar_alt' );

	// Remove Gallery CSS
	add_filter( 'use_default_gallery_style', '__return_false' );

	// Add Shortcode Functionality to Text Widgets
	add_filter( 'widget_text', 'shortcode_unautop' );
	add_filter( 'widget_text', 'do_shortcode' );


	// Add Accessibility support
	add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );
//
//	// TGM Plugin Activation
//	require_once( BFG_THEME_MODULES . 'class-tgm-plugin-activation.php' );
//
//	//* Kirki Helper
//	foreach ( glob( dirname( __FILE__ ) . '/lib/modules/kirki-helpers/*.php' ) as $file ) {
//		require_once $file;
//	}


// Include php files from lib folder
// @link https://gist.github.com/theandystratton/5924570
    foreach ( glob( dirname( __FILE__ ) . '/lib/*.php' ) as $file ) {
        include $file;
    }


}

