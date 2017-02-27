<?php
/**
 * Created by PhpStorm.
 * User: snowd
 * Date: 2/24/2017
 * Time: 1:51 PM
 */
// actions and filters for the single product display page

/// --------------------------------- Single Product Template stuff


// Remove product tabs
add_filter('woocommerce_product_tabs', 'woo_remove_product_tabs', 98);

function woo_remove_product_tabs($tabs)
{

    unset($tabs['description']);        // Remove the description tab
    unset($tabs['reviews']);            // Remove the reviews tab
    unset($tabs['additional_information']);    // Remove the additional information tab

    return $tabs;

}

// Move SKU above price
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 9);


add_action('woocommerce_single_product_summary', 'gem_single_product_shipping_details_link', 25);

function gem_single_product_shipping_details_link()
{
    ?>
    <a class="single-product-meta--shipping-link" href="">Shipping Details</a>
    <?php
}

add_action('woocommerce_single_product_summary', 'gem_summary_wrap_open', 2);

function gem_summary_wrap_open()
{
    echo "<div class='entry-summary--top'>";
}

add_action('woocommerce_single_product_summary', 'gem_summary_wrap_close', 55);

function gem_summary_wrap_close()
{
    echo "</div>";
}

add_action('woocommerce_before_single_product_summary', 'gem_mobile_summary', 15);

function gem_mobile_summary()
{
    echo "<div class='mobile-summary-top'>";
    woocommerce_template_single_title();
    woocommerce_template_single_meta();
    woocommerce_template_single_price();
    gem_single_product_shipping_details_link();
    woocommerce_template_single_excerpt();
    woocommerce_template_single_add_to_cart();
    echo "</div>";
}


// Create a table from the attributes (stored in ACF fields)
add_action('woocommerce_single_product_summary', 'gem_product_attributes_table', 60);

function gem_product_attributes_table()
{
    $fields = get_field_objects();
    if ($fields) {
        echo "<div class='product-attribute-table'>";
        echo "<div class='row'>";
        echo "<div class='col-xs-10 col-xs-offset-2 col-sm-12 col-sm-offset-0'>";

        foreach ($fields as $field) {
            // Reset value
            // Get the right value from each field type
            $type = $field['type'];

            if ($field['value']) {
                if ('text' == $type) {
                    $value = $field['value'];
                } elseif ('taxonomy' == $type) {
                    $value = $field['value']->name;
                } else {
                    //if the field is the wrong type (e.g. image)
                    continue;
                }
            } else {
                //if we don't have a value, go to the next field.
                continue;
            }
            ?>
            <div class="row">
                <div class="col-xs-6 col-sm-3 product-attribute-table--label">
                    <?php echo $field['label'] ?>
                </div>
                <div class="col-xs-6 col-sm-9 product--attribute-table--value">
                    <?php echo $value ?>
                </div>
            </div>
            <?php
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }

}

// Add the description back in but only if we have content
add_action('woocommerce_after_single_product_summary', 'gem_single_product_content');

function gem_single_product_content()
{
    $heading = esc_html(apply_filters('woocommerce_product_description_heading', __('Product Description', 'woocommerce')));
    $content = get_the_content();
    ?>
    <div class="col-xs-12">

        <?php if ($heading and $content): ?>
            <h2><?php echo $heading; ?></h2>
        <?php endif; ?>

        <?php the_content(); ?>
    </div>
    <?php
}


// Add a disclaimer at the bottom
add_action('woocommerce_after_single_product_summary', 'gem_product_disclaimer', 30);

function gem_product_disclaimer()
{
    ?>
    <div class="col-xs-12">
        <div class="disclaimer">
            <h3><?php the_field('disclaimer_title', 'options') ?></h3>
            <p><?php the_field('disclaimer_text', 'options') ?></p>
        </div>
    </div>
    <?php
}
