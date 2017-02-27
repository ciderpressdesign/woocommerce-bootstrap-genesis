<?php
/**
 * Created by PhpStorm.
 * User: snowd
 * Date: 2/24/2017
 * Time: 1:59 PM
 */
// -------------------------------  ADMIN FACING STUFF -------------------------------
// REMOVE VIRTUAL AND DOWNLOADABLE OPTIONS
add_filter('product_type_options', 'remove_virtual_products');

function remove_virtual_products()
{
    return array();
}

// Remove product category admin fields

add_action('load-term.php', 'gem_woo_removehooks', 11);
add_action('load-edit-tags.php', 'gem_woo_removehooks', 11);

function gem_woo_removehooks()
{
//   Call custom function to remove hooks from Woocommerce Objects not assigned to variables

    remove_class_action('product_cat_edit_form_fields', 'WC_Admin_Taxonomies', 'edit_category_fields', 10);
    remove_class_action('product_cat_add_form_fields', 'WC_Admin_Taxonomies', 'add_category_fields');

    remove_action('product_cat_edit_form', 'genesis_taxonomy_archive_options', 10, 2);

}

remove_action('admin_init', 'genesis_add_taxonomy_seo_options');

