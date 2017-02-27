<?php

/**
 * Class Name: wp_bootstrap_navwalker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
class wp_bootstrap_pagewalker extends Walker_Page
{

    /**
     * @see Walker::start_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int $depth Depth of page. Used for padding.
     */
    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul role=\"menu\" class=\" dropdown-menu\">\n";
    }

    /**
     * @see Walker::start_el()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param int $current_page Menu item ID.
     * @param object $args
     */
    public function start_el(&$output, $page, $depth = 0, $args = array(), $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $css_class = array('page_item', 'page-item-' . $page->ID, 'menu-item', 'menu-item-' . $page->ID);
        $has_caret = '';
        $current_page = $page->ID;
        if (isset($args['pages_with_children'][$page->ID])) {
            $css_class[] = 'page_item_has_children';
            $css_class[] = 'dropdown';
            $has_caret = '<span class="caret">';
        }

        // If item has_children add atts to a.
        if (isset($args['pages_with_children'][$page->ID])) {
            $atts['href'] = '#';
            $atts['data-toggle'] = 'dropdown';
            $atts['class'] = 'dropdown-toggle';
            $atts['aria-haspopup'] = 'true';
        } else {
            $atts['href'] = (get_permalink($page->ID)) ? get_permalink($page->ID) : '';
        }


        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        if (!empty($current_page)) {
            $_current_page = get_post($current_page);
            if ($_current_page && in_array($page->ID, $_current_page->ancestors)) {
                $css_class[] = 'current-menu-ancestor';
                $css_class[] = 'current_page_ancestor';
            }
            if ($_current_page->ID == $id) {
                $css_class[] = 'current-menu-item';
                $css_class[] = 'active';
                $css_class[] = 'current_page_item';
            } elseif ($_current_page && $page->ID == $_current_page->post_parent) {
                $css_class[] = 'current-menu-parent';
                $css_class[] = 'current_page_parent';
            }
        } elseif ($page->ID == get_option('page_for_posts')) {
            $css_class[] = 'current-menu-parent';
            $css_class[] = 'current_page_parent';
        }

        /**
         * Filter the list of CSS classes to include with each page item in the list.
         *
         * @since 2.8.0
         *
         * @see wp_list_pages()
         *
         * @param array $css_class An array of CSS classes to be applied
         *                             to each list item.
         * @param WP_Post $page Page data object.
         * @param int $depth Depth of page, used for padding.
         * @param array $args An array of arguments.
         * @param int $current_page ID of the current page.
         */
        $css_classes = implode(' ', apply_filters('page_css_class', $css_class, $page, $depth, $args, $current_page));

        if ('' === $page->post_title) {
            /* translators: %d: ID of a post */
            $page->post_title = sprintf(__('#%d (no title)'), $page->ID);
        }

        $args['link_before'] = empty($args['link_before']) ? '' : $args['link_before'];
        $args['link_after'] = empty($args['link_after']) ? '' : $args['link_after'];


        /** This filter is documented in wp-includes/post-template.php */
        $output .= $indent . sprintf(
                '<li class="%s"><a %s>%s%s%s%s</a>',
                $css_classes,
                $attributes,
                //	get_permalink( $page->ID ),
                $args['link_before'],
                apply_filters('the_title', $page->post_title, $page->ID),
                $args['link_after'],
                $has_caret
            );


        if (!empty($args['show_date'])) {
            if ('modified' == $args['show_date']) {
                $time = $page->post_modified;
            } else {
                $time = $page->post_date;
            }

            $date_format = empty($args['date_format']) ? '' : $args['date_format'];
            $output .= " " . mysql2date($date_format, $time);
        }
    }


    /**
     * Traverse elements to create list from elements.
     *
     * Display one element if the element doesn't have any children otherwise,
     * display the element and its children. Will only traverse up to the max
     * depth and no ignore elements under that depth.
     *
     * This method shouldn't be called directly, use the walk() method instead.
     *
     * @see Walker::start_el()
     * @since 2.5.0
     *
     * @param object $element Data object
     * @param array $children_elements List of elements to continue traversing.
     * @param int $max_depth Max depth to traverse.
     * @param int $depth Depth of current element.
     * @param array $args
     * @param string $output Passed by reference. Used to append additional content.
     * @return null Null on failure with no changes to parameters.
     */
    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output)
    {
        if (!$element)
            return;
        $id_field = $this->db_fields['id'];

        // Display this element.
        if (is_object($args[0]))
            $args[0]->has_children = !empty($children_elements[$element->$id_field]);

        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }

//	/**
//	 * Menu Fallback
//	 * =============
//	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
//	 * and a manu has not been assigned to the theme location in the WordPress
//	 * menu manager the function with display nothing to a non-logged in user,
//	 * and will add a link to the WordPress menu manager if logged in as an admin.
//	 *
//	 * @param array $args passed from the wp_nav_menu function.
//	 *
//	 */
//	public static function fallback( $args ) {
//		if ( current_user_can( 'manage_options' ) ) {
//
//			extract( $args );
//
//			$fb_output = null;
//
//			if ( $container ) {
//				$fb_output = '<' . $container;
//
//				if ( $container_id )
//					$fb_output .= ' id="' . $container_id . '"';
//
//				if ( $container_class )
//					$fb_output .= ' class="' . $container_class . '"';
//
//				$fb_output .= '>';
//			}
//
//			$fb_output .= '<ul';
//
//			if ( $menu_id )
//				$fb_output .= ' id="' . $menu_id . '"';
//
//			if ( $menu_class )
//				$fb_output .= ' class="' . $menu_class . '"';
//
//			$fb_output .= '>';
//			$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
//			$fb_output .= '</ul>';
//
//			if ( $container )
//				$fb_output .= '</' . $container . '>';
//
//			echo $fb_output;
//		}
//	}
}
