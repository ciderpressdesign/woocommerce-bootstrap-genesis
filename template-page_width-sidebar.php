<?php
/**
 * Created by PhpStorm.
 * User: snowd
 * Date: 2/24/2017
 * Time: 9:11 AM
 */

//* Template Name: Sidebar-Menu

//* Force content-sidebar layout
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar');

//move the sidebar to the top for graceful mobile response
remove_action('genesis_sidebar', 'genesis_do_sidebar');
remove_action('genesis_after_content', 'genesis_get_sidebar');
add_action('genesis_before_content', 'genesis_get_sidebar', 10);

// Duplicate the post title above the menu for mobile (better way to do this?)
add_action('genesis_before_sidebar_widget_area', 'genesis_do_post_title');

// move the Post title to inside the content so it can wrap around the image
remove_action('genesis_entry_header', 'genesis_do_post_title');
add_action('genesis_before_entry_content', 'genesis_do_post_title', 15);

// Hook in our sidebar
add_action('genesis_after_sidebar_widget_area', 'gem_sidebar_page_menu');

function gem_sidebar_page_menu()
{
    global $post;
    $menu_page_id = $post->ID;
    $ancestor = array_reverse(get_post_ancestors($menu_page_id));
    $menu_args = array(
        'authors' => '',
        'child_of' => isset($ancestor[0]) ? $ancestor[0] : $post->ID,
        'date_format' => get_option('date_format'),
        'depth' => 0,
        'echo' => 0,
        'exclude' => '',
        'include' => '',
        'link_after' => '',
        'link_before' => '',
        'post_type' => 'page',
        'post_status' => 'publish',
        'show_date' => '',
        'sort_column' => 'menu_order, post_title',
        'sort_order' => '',
        'title_li' => '',
        'walker' => new wp_bootstrap_pagewalker
    );

    $page_menu_list = wp_list_pages($menu_args);
    ?>

    <div class="sidebar-page row">
        <div class="sidebar-nav">
            <div class="navbar navbar-default" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#sidebar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="menu-toggle-arrow"></span>
                    </button>
                    <span class="visible-xs navbar-brand"><?php echo $post->post_title ?></span>
                </div>
                <div class="navbar-collapse collapse sidebar-navbar-collapse" id="sidebar-collapse">
                    <ul class="nav navbar-nav">
                        <?php echo $page_menu_list ?>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
    </div>


    <?php

}

add_action('genesis_before_entry_content', 'gem_page_image');

function gem_page_image()
{
    $image = get_field('page_image');
    echo "<div class='page-image'><img src='$image'></div>";
}

genesis();
