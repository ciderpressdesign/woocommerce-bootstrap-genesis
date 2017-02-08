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
function bfg_footer_creds_text( $creds ) {
   // $creds = get_theme_mod( 'creds', '[footer_copyright] &middot; <a href="http://recommendwp.com">RecommendWP</a> &middot; Built on the <a href="http://www.studiopress.com/themes/genesis" title="Genesis Framework">Genesis Framework</a>' );
    // $creds = $credits;
    $creds = "";
    return $creds;
}




add_action("genesis_footer","bfg_custom_footer",9);

function bfg_custom_footer() {

    ?>
    <div class="row">
        <div class="col-sm-4">
            <?php bfg_footer_menu("OUR PRODUCTS","footer_1") ?>
        </div>
        <div class="col-sm-2">
            <?php bfg_footer_menu("ABOUT US","footer_2") ?>
        </div>
        <div class="col-sm-3">
            <?php bfg_footer_menu("","footer_3") ?>
        </div>
        <div class="col-sm-3">
            <?php subscribe_and_save_block() ?>
        </div>
    </div>
    <div class="row">
             <div class="col-md-2">
                 <a href="tel:" >888-GEM-SYND</a>
             </div>
             <div class="col-md-3">
                &copy;2017 The Gem Syndicate
             </div>
             <div class="col-md-1">
                 <a href="" >privacy</a>
             </div>
             <div class="col-md-1">
                   <a href="" >site map</a>
             </div>
             <div class="col-md-2">
                 <a href="" >shipping/returns</a>
             </div>
             <div class="col-md-2">
                  <a href="" >terms & conditions</a>
             </div>
    </div>
<?php

}

function bfg_footer_menu($header,$menu_name) {
    ?>

    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <h4 class="widgettitle widget-title"><?php echo $header ?></h4>
            </div>
            <?php
            genesis_nav_menu( array(
                'theme_location' => $menu_name,
                'container'       => 'div',
                'container_class' => 'wrap',
                'menu_class'     => 'nav navbar-nav',
                'depth'           => 1,
                'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
                'walker' => new wp_bootstrap_navwalker()
            ) );

            ?>
        </div>
    </nav>
    <?php
}

function atg_menu_classes($classes, $item, $args) {
    if($args->theme_location == 'footer_1') {
        $classes[] = 'col-xs-6';
    }
     return $classes;
}

add_filter('nav_menu_css_class','atg_menu_classes',1,3);