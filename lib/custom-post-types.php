<?php
/**
 * Created by PhpStorm.
 * User: snowd
 * Date: 2/2/2017
 * Time: 4:11 PM
 */


function gem_register_testimonial() {

    /**
     * Post Type: Testimonials.
     */

    $labels = array(
        "name" => __( 'Testimonials', '' ),
        "singular_name" => __( 'Testimonial', '' ),
    );

    $args = array(
        "label" => __( 'Testimonials', '' ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "has_archive" => true,
        "show_in_menu" => true,
        "exclude_from_search" => true,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "testimonial", "with_front" => true ),
        "query_var" => true,
        "menu_icon" => "dashicons-heart",
        "supports" => array('title','editor'),
    );

    register_post_type( "testimonial", $args );
}

add_action( 'init', 'gem_register_testimonial' );


function testimonial_change_title_text( $title ){
    $screen = get_current_screen();

    if  ( 'testimonial' == $screen->post_type ) {
        $title = 'Customer Name';
    }

    return $title;
}

add_filter( 'enter_title_here', 'testimonial_change_title_text' );

function testimonial_wp_editor_settings( $settings, $editor_id ) {
    if ( $editor_id === 'content' && get_current_screen()->post_type === 'testimonial' ) {
        $settings['tinymce']   = false;
        $settings['quicktags'] = false;
        $settings['media_buttons'] = false;
    }

    return $settings;
}

add_filter( 'wp_editor_settings', 'testimonial_wp_editor_settings', 10, 2 );