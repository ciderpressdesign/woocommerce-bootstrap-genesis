<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     2.6.3
 */

if (!defined('ABSPATH')) {
    exit;
}

global $post, $product;

// extract image Id's from custom repeater

?>
<div class="col-sm-7">
    <div class="gem-product-images">
        <?php
        /* --------------------   ACF Repeater For product_gallery  ----------------*/

        // check if the repeater field has rows of data
        if (have_rows('product_gallery')):
            echo '<ul class="product-slides bxslider">';
            // loop through the rows of data
            while (have_rows('product_gallery')) : the_row();
                $image_object = get_sub_field('image');
                ?>
                <li><img class="slide-product-image"
                         src="<?php echo $image_object['url'] ?>"
                         data-zoom-image="<?php echo $image_object['sizes']['product-lg'] ?>"
                         alt=""></li>
                <?php

            endwhile;

            echo '</ul>';

        else :

            // no rows found

            echo apply_filters('woocommerce_single_product_image_html', sprintf(
                '<img src="%s" id="primary-product-image" alt="%s" />',
                wc_placeholder_img_src(), __('Placeholder', 'woocommerce')),
                $post->ID);

        endif;
        ?>
        <?php
        do_action('woocommerce_product_thumbnails');
        ?>
    </div>
</div>


