<?php
/**
 * Created by PhpStorm.
 * User: snowd
 * Date: 2/22/2017
 * Time: 2:49 PM
 */
// ------------------  Set the featured image to the ACF cropped thumbnail
// ------------------- save so we don't have to rewrite too much Woocommerce templating
function acf_set_term_image($value, $post_id, $field)
{
    //convert post_id to term_idj
    $term_id = intval($_POST['tag_ID']);
    $value_decoded = json_decode(stripslashes($value));
    update_term_meta($term_id, 'thumbnail_id', $value_decoded->cropped_image);
    $_POST['product_cat_thumbnail_id'] = $value_decoded->cropped_image;
    return $value;
}

add_filter('acf/update_value/name=product_category_image', 'acf_set_term_image', 99, 3);


// ------------------  Set the featured image to the first image in Product Gallery on
// ------------------- save so we don't have to rewrite too much Woocommerce templating
function acf_set_featured_image($value, $post_id, $field)
{
    // Act only on Product Gallery
    if ($field['name'] != 'product_gallery') {
        return $value;
    }
    // ACF image crop plugin stores images in an array of  slash escaped JSON Strings -- decode it
    $image_field = $field['sub_field'][0]['key'];
    $value_decoded = json_decode(stripslashes($value[0][$image_field]));
    set_post_thumbnail($post_id, $value_decoded->cropped_image);
    return $value;
}

add_filter('acf/update_value/type=repeater', 'acf_set_featured_image', 10, 3);

// PLACEHOLDER IMAGES
// Store the last used category image size to avoid loading full image placeholder
// @todo learn how to do this w/o using globals

add_filter('subcategory_archive_thumbnail_size', function ($image_size) {
    global $last_cat_image_size;
    $last_cat_image_size = $image_size;
    return $image_size;
});


add_action('woocommerce_placeholder_img_src', 'gem_placeholder_src');

function gem_placeholder_src($image_url)
{
    global $last_cat_image_size;
    global $post;

    if (get_field('default_product_image', 'options')) {
        $image_arr = get_field('default_product_image', 'options');  // change this to the URL to your custom placeholder
        $image_url = $image_arr['url'];
        if ($last_cat_image_size) {
            $image_url = $image_arr['sizes'][$last_cat_image_size];
            $last_cat_image_size = '';
        }
    }

    return $image_url;

}

//  ---------------------------- Use default image from category, then from options page
add_filter('woocommerce_placeholder_img', 'gem_custom_woocommerce_placeholder', 10, 3);
/**
 * Function to return new placeholder image URL.
 */
function gem_custom_woocommerce_placeholder($image_url, $size, $dimensions)
{
    global $post;

    $image_arr = "";
    // if we're in a post, overwrite with the category specific one.
    if ($post) {

        $categories = wc_get_product_cat_ids($post->ID);
        if ($categories) {
            if (get_field('product_category_image', 'term_' . $categories[0])) {
                $image_arr = get_field('product_category_image', 'term_' . $categories[0]);
            }
        }
    }

    // First set to the global default image
    if (!($image_arr) and get_field('default_product_image', 'options')) {
        $image_arr = get_field('default_product_image', 'options');  // change this to the URL to your custom placeholder
    }
    $image_url = '<img src="' . $image_arr['sizes'][$size] . '" alt="' . esc_attr__('Placeholder', 'woocommerce') . '" width="' . esc_attr($dimensions['width']) . '" class="woocommerce-placeholder wp-post-image" height="' . esc_attr($dimensions['height']) . '" />';

    return $image_url;
}


// Cart display doesn't track post or product when getting the placeholder image, so we have to filter once it is returned instead
add_filter('woocommerce_cart_item_thumbnail', 'gem_cart_category_placeholder', 2, 3);

function gem_cart_category_placeholder($image, $cart_item, $cart_item_key)
{
    $product_obj = $cart_item['data'];

    //If the post has a thumbnail, return it
    $post_thumbnail = get_the_post_thumbnail($product_obj->post);
    if ($post_thumbnail) {
        return $post_thumbnail;
    }

    // If the category has an image, use that one instead
    $categories = wc_get_product_cat_ids($product_obj->post->ID);
    if ($categories) {
        if (get_field('product_category_image', 'term_' . $categories[0])) {
            $image_url = get_field('product_category_image', 'term_' . $categories[0]);
            return '<img src="' . $image_url['url'] . '" alt="Placeholder" width="240" class="woocommerce-placeholder wp-post-image" height="160" />';
        }
    }

    // Otherwise, use the default image
    return $image;

}


