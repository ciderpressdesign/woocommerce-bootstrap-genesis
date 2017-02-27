<?php

//* Template Name: Sitemap

//* The blog page loop logic is located in lib/structure/loops.php



//* Remove the default Genesis loop
//remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_entry_content', 'rpp_sitemap_loop');


function rpp_sitemap_loop() {
    ?>
    <ul>
    <li><a href="<?php echo esc_url( site_url() ); ?>"><?php esc_html_e( 'Home', 'textdomain' ); ?></a></li>

    <?php
    wp_nav_menu(	array(
        'theme_location'  => '',
        'menu'            => 'main',
        'container'       => 'div',
        'container_class' => '',
        'container_id'    => '',
        'menu_class'      => 'menu',
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '%3$s',
        'depth'           => 0,
        'walker'          => ''));

    wp_nav_menu(	array(
        'theme_location'  => '',
        'menu'            => 'Top Menu',
        'container'       => 'div',
        'container_class' => '',
        'container_id'    => '',
        'menu_class'      => 'menu',
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '%3$s',
        'depth'           => 0,
        'walker'          => ''));


    ?>



    <li><a href="<?php echo esc_url( get_permalink(204) ); ?>"><?php esc_html_e( 'Privacy Policy', 'textdomain' ); ?></a></li>
    <li><a href="<?php echo esc_url( the_permalink() ); ?>"><?php esc_html_e( 'Sitemap', 'textdomain' ); ?></a></li>

    <?php
}

genesis();
