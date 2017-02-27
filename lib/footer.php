<?php
/**
 * Custom Footer
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://www.recommendwp.com
 * @author       RecommendWP <www.recommendwp.com>
 * @copyright    Copyright (c) 2015, RecommendWP
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

 add_filter( 'genesis_footer_creds_text', 'bfg_footer_creds_text' );
function bfg_footer_creds_text()
{
   // $creds = get_theme_mod( 'creds', '[footer_copyright] &middot; <a href="http://recommendwp.com">RecommendWP</a> &middot; Built on the <a href="http://www.studiopress.com/themes/genesis" title="Genesis Framework">Genesis Framework</a>' );
    // $creds = $credits;
    return "";
}

remove_action('genesis_footer', 'genesis_footer_markup_open', 5);
remove_action('genesis_footer', 'genesis_do_footer');
remove_action('genesis_footer', 'genesis_footer_markup_close', 15);

add_action("genesis_after", "bfg_custom_footer", 9);

function bfg_custom_footer() {

    ?>
    <div class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-5">
                    <?php bfg_footer_menu("OUR PRODUCTS", "footer_1") ?>
                </div>
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-xs-6">
                            <?php bfg_footer_menu("ABOUT US", "footer_2") ?>
                        </div>
                        <div class="col-xs-6">
                            <?php bfg_footer_menu("", "footer_3") ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <?php social_links_block() ?>
                    <?php subscribe_and_save_block() ?>
                </div>
            </div>
        </div>
        <div class="info-links">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="telephone"><a href="tel:">888-GEM-SYND</a></div>
                    </div>
                    <div class="col-sm-3">
                        <div class="copyright">
                            &copy;2017 The Gem Syndicate
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 privacy-sitemap">
                        <div class="row">
                            <div class="col-xs-6 privacy">
                                <a href="<?php echo get_permalink(get_page_by_path('privacy')) ?>">privacy</a>
                            </div>
                            <div class="col-xs-6 site-map-link">
                                <a href="<?php echo get_permalink(get_page_by_path('site-map')) ?>">site map</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 shipping-terms">
                        <div class="row">
                            <div class="col-xs-6 shipping">
                                <a href="<?php echo get_permalink(get_page_by_path('shippingreturns')) ?>">shipping /
                                    returns</a>
                            </div>
                            <div class="col-xs-6 terms">
                                <a href="<?php echo get_permalink(get_page_by_path('terms-conditions')) ?>">terms &
                                    conditions</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

}

function bfg_footer_menu($header, $menu_name)
{
    ?>

    <nav class="<?php echo $menu_name ?>">
        <div class="footer-nav--header">
                <h4 class="widgettitle widget-title"><?php echo $header ?></h4>
            </div>
            <?php
            genesis_nav_menu(array(
                'theme_location' => $menu_name,
                'container' => 'div',
                'container_class' => 'wrap',
                'menu_class' => 'nav footer-nav--menu',
                'depth' => 1,
                'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
                'walker' => new wp_bootstrap_navwalker()
            ));

            ?>
    </nav>
    <?php
}

//Make the long menu wrap
function atg_menu_classes($classes, $item, $args) {
    if($args->theme_location == 'footer_1') {
        $classes[] = 'col-xs-6';
    }
     return $classes;
}

add_filter('nav_menu_css_class','atg_menu_classes',1,3);