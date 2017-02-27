<?php

// Add HTML5 markup structure
add_theme_support('html5');

remove_theme_support('post-thumbnails');

// Remove structural Wraps
//remove_theme_support( 'genesis-structural-wraps' );

remove_theme_support('genesis-inpost-layouts');
remove_theme_support('genesis-archive-layouts');
// Remove item(s) from genesis admin screens
add_action('genesis_admin_before_metaboxes', 'bsg_remove_genesis_theme_metaboxes');

// Remove item(s) from genesis customizer
add_action('customize_register', 'bsg_remove_genesis_customizer_controls', 20);

/**
 * Remove selected Genesis metaboxes from the Theme Settings and SEO Settings pages.
 *
 * @param string $hook The unique pagehook for the Genesis settings page
 */

function bsg_remove_genesis_theme_metaboxes($hook)
{
    /** Theme Settings metaboxes */
    //remove_meta_box( 'genesis-theme-settings-version',  $hook, 'main' );
    //remove_meta_box( 'genesis-theme-settings-feeds',    $hook, 'main' );
    remove_meta_box('genesis-theme-settings-layout', $hook, 'main');
    remove_meta_box('genesis-theme-settings-header', $hook, 'main');
    //remove_meta_box( 'genesis-theme-settings-nav',      $hook, 'main' );
    //remove_meta_box( 'genesis-theme-settings-breadcrumb', $hook, 'main' );
    //remove_meta_box( 'genesis-theme-settings-comments', $hook, 'main' );
    //remove_meta_box( 'genesis-theme-settings-posts',    $hook, 'main' );
    //remove_meta_box( 'genesis-theme-settings-blogpage', $hook, 'main' );
    //remove_meta_box( 'genesis-theme-settings-scripts',  $hook, 'main' );

    /** SEO Settings metaboxes */
    //remove_meta_box( 'genesis-seo-settings-doctitle',   $hook, 'main' );
    //remove_meta_box( 'genesis-seo-settings-homepage',   $hook, 'main' );
    //remove_meta_box( 'genesis-seo-settings-dochead',    $hook, 'main' );
    //remove_meta_box( 'genesis-seo-settings-robots',     $hook, 'main' );
    //remove_meta_box( 'genesis-seo-settings-archives',   $hook, 'main' );
}


/**
 * Filter to remove selected Genesis Customizer Menu controls
 *
 * @param instance of WP_Customize_Manager $wp_customize
 */
function bsg_remove_genesis_customizer_controls($wp_customize)
{
    // remove Site Title/Logo: Dynamic Text or Image Logo option from Customizer
    $wp_customize->remove_control('blog_title');
    return $wp_customize;
}

//* Force full-width-content layout
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');


add_action('admin_menu', 'bfg_remove_dashboard_menus');
/**
 * Remove default admin dashboard menus.
 *
 * @since 2.0.0
 */
function bfg_remove_dashboard_menus()
{

    remove_menu_page('index.php'); // Dashboard tab
    //  remove_menu_page('edit.php'); // Posts
    //  remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');
    remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
    //  remove_menu_page('upload.php'); // Media
    //  remove_menu_page('edit.php?post_type=page'); // Pages
    remove_menu_page('edit-comments.php'); // Comments
    //  remove_menu_page('genesis'); // Genesis
    //  remove_menu_page('themes.php'); // Appearance
    //  remove_menu_page('plugins.php'); // Plugins
    //  remove_menu_page('users.php'); // Users
    //  remove_menu_page('tools.php'); // Tools
    //  remove_menu_page('options-general.php'); // Settings


    //  add_menu_page( 'Home Page', 'Home Page', 'edit_posts', 'post.php?post=10&action=edit', '', 'dashicons-admin-home', 5 );


}


add_filter('default_hidden_meta_boxes', 'bfg_hidden_meta_boxes', 2);
/**
 * Change which meta boxes are hidden by default on the post and page edit screens.
 *
 * @since 2.0.0
 */
function bfg_hidden_meta_boxes($hidden)
{

    global $current_screen;
    if ('post' === $current_screen->id) {
        $hidden = array('postexcerpt', 'trackbacksdiv', 'postcustom', 'commentstatusdiv', 'slugdiv', 'authordiv', 'ninja_forms_selector', 'postimagediv', 'woocommerce-product-images');
        // Other hideable post boxes: genesis_inpost_scripts_box, commentsdiv, categorydiv, tagsdiv, postimagediv
    } elseif ('product' === $current_screen->id) {
        $hidden = array('postexcerpt', 'trackbacksdiv', 'postcustom', 'commentstatusdiv', 'slugdiv', 'authordiv', 'ninja_forms_selector', 'postimagediv', 'woocommerce-product-images');
    } elseif ('page' === $current_screen->id) {
        $hidden = array('postcustom', 'commentstatusdiv', 'slugdiv', 'authordiv', 'postimagediv');
        // Other hideable post boxes: genesis_inpost_scripts_box, pageparentdiv
    }

    return $hidden;

}

// REMOVE POST META BOXES
function remove_my_post_metaboxes()
{
//    remove_meta_box( 'categorydiv','post','normal' ); // Categories Metabox
    remove_meta_box('tagsdiv-post_tag', 'post', 'normal'); // Tags Metabox
    remove_meta_box('tagsdiv-product_tag', 'product', 'side'); // Tags Metabox
    remove_meta_box('ninja_forms_selector', 'post', 'side'); // Tags Metabox
    remove_meta_box('ninja_forms_selector', 'page', 'side'); // Tags Metabox
    remove_meta_box('commentsdiv', 'post', 'side'); // Tags Metabox
    remove_meta_box('commentsdiv', 'page', 'side'); // Tags Metabox
    remove_meta_box('revisionsdiv', 'post', 'side'); // Tags Metabox
    remove_meta_box('revisionsdiv', 'page', 'side'); // Tags Metabox
    remove_meta_box('genesis_inpost_scripts_box', 'post', 'normal'); // Tags Metabox
    remove_meta_box('genesis_inpost_scripts_box', 'page', 'normal'); // Tags Metabox
    remove_meta_box('genesis_inpost_scripts_box', 'product', 'normal'); // Tags Metabox
    remove_meta_box('genesis_inpost_layout_box', 'post', 'normal'); // Tags Metabox
    remove_meta_box('genesis_inpost_layout_box', 'page', 'normal'); // Tags Metabox
    remove_meta_box('genesis_inpost_layout_box', 'product', 'normal'); // Tags Metabox
    remove_meta_box('woocommerce-product-images', 'product', 'side'); // Tags Metabox
    remove_meta_box('postimagediv', 'product', 'side'); // Tags Metabox
    remove_meta_box('postimagediv', 'post', 'side'); // Tags Metabox
    remove_meta_box('postimagediv', 'page', 'side'); // Tags Metabox
}

add_action('add_meta_boxes', 'remove_my_post_metaboxes', 50);


//remove_action('add_meta_boxes','ninja_forms_add_custom_box' );

//remove_post_type_support( 'post', 'editor' );

add_filter('wpseo_metabox_prio', 'gem_seo_metabox');

function gem_seo_metabox()
{
    return 'low';
}

;

function remove_woocommerce_admin_tabs($tabs)
{

    unset($tabs['general']);

//                unset($tabs['inventory']);

//                unset($tabs['shipping']);

    unset($tabs['linked_product']);

    unset($tabs['attribute']);

    unset($tabs['advanced']);

    return ($tabs);

}

add_filter('woocommerce_product_data_tabs', 'remove_woocommerce_admin_tabs', 10, 1);

add_filter('product_type_selector', 'cartible_product_type_selector', 10, 2);


/**
 * Remove product types we do not want to be shown.
 */
function cartible_product_type_selector($product_types)
{
    unset($product_types['grouped']);
    unset($product_types['external']);
    unset($product_types['variable']);

    return $product_types;
}

add_action('genesis_cpt_archives_settings_metaboxes', 'gem_remove_taxonomy_settings');
function gem_remove_taxonomy_settings($pagehook)
{
    // remove main settings
    remove_meta_box('genesis-cpt-archives-settings', $pagehook, 'main');

    // remove seo settings
    remove_meta_box('genesis-cpt-archives-seo-settings', $pagehook, 'main');

    // remove layout settings
    remove_meta_box('genesis-cpt-archives-layout-settings', $pagehook, 'main');

}


//* Modify breadcrumb arguments.
add_filter('genesis_breadcrumb_args', 'gem_breadcrumb_args');
function gem_breadcrumb_args($args)
{
    $args['home'] = 'Home';
    $args['sep'] = '';
    $args['list_sep'] = ', '; // Genesis 1.5 and later
    $args['prefix'] = '<div class="col-xs-12 gem-breadcrumb">';
    $args['suffix'] = '</div>';
    $args['heirarchial_attachments'] = true; // Genesis 1.5 and later
    $args['heirarchial_categories'] = true; // Genesis 1.5 and later
    $args['display'] = true;
    $args['labels']['prefix'] = '';
    $args['labels']['author'] = '';
    $args['labels']['category'] = ''; // Genesis 1.6 and later
    $args['labels']['tag'] = '';
    $args['labels']['date'] = '';
    $args['labels']['search'] = 'Search for ';
    $args['labels']['tax'] = '';
    $args['labels']['post_type'] = '';
    $args['labels']['404'] = 'Not found: '; // Genesis 1.5 and later
    return $args;
}
