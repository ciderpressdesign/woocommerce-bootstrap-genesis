<?php

function send_to_console($debug_output)
{

    $cleaned_string = '';
    if (!is_string($debug_output))
        $debug_output = print_r($debug_output, true);

    $str_len = strlen($debug_output);
    for ($i = 0; $i < $str_len; $i++) {
        $cleaned_string .= '\\x' . sprintf('%02x', ord(substr($debug_output, $i, 1)));
    }
    $javascript_ouput = "<script>console.log('Debug Info: " . $cleaned_string . "');</script>";
    echo $javascript_ouput;
}

/**
 * Remove Class Filter Without Access to Class Object
 *
 * In order to use the core WordPress remove_filter() on a filter added with the callback
 * to a class, you either have to have access to that class object, or it has to be a call
 * to a static method.  This method allows you to remove filters with a callback to a class
 * you don't have access to.
 *
 * Works with WordPress 1.2+ (4.7+ support added 9-19-2016)
 *
 * @param string $tag Filter to remove
 * @param string $class_name Class name for the filter's callback
 * @param string $method_name Method name for the filter's callback
 * @param int $priority Priority of the filter (default 10)
 *
 * @return bool Whether the function is removed.
 */
function remove_class_filter($tag, $class_name = '', $method_name = '', $priority = 10)
{
    global $wp_filter;
    // Check that filter actually exists first
    if (!isset($wp_filter[$tag])) return FALSE;
    /**
     * If filter config is an object, means we're using WordPress 4.7+ and the config is no longer
     * a simple array, rather it is an object that implements the ArrayAccess interface.
     *
     * To be backwards compatible, we set $callbacks equal to the correct array as a reference (so $wp_filter is updated)
     *
     * @see https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/
     */
    if (is_object($wp_filter[$tag]) && isset($wp_filter[$tag]->callbacks)) {
        $callbacks = &$wp_filter[$tag]->callbacks;
    } else {
        $callbacks = &$wp_filter[$tag];
    }
    // Exit if there aren't any callbacks for specified priority
    if (!isset($callbacks[$priority]) || empty($callbacks[$priority])) return FALSE;
    // Loop through each filter for the specified priority, looking for our class & method
    foreach ((array)$callbacks[$priority] as $filter_id => $filter) {
        // Filter should always be an array - array( $this, 'method' ), if not goto next
        if (!isset($filter['function']) || !is_array($filter['function'])) continue;
        // If first value in array is not an object, it can't be a class
        if (!is_object($filter['function'][0])) continue;
        // Method doesn't match the one we're looking for, goto next
        if ($filter['function'][1] !== $method_name) continue;
        // Method matched, now let's check the Class
        if (get_class($filter['function'][0]) === $class_name) {
            // Now let's remove it from the array
            unset($callbacks[$priority][$filter_id]);
            // and if it was the only filter in that priority, unset that priority
            if (empty($callbacks[$priority])) unset($callbacks[$priority]);
            // and if the only filter for that tag, set the tag to an empty array
            if (empty($callbacks)) $callbacks = array();
            // If using WordPress older than 4.7
            if (!is_object($wp_filter[$tag])) {
                // Remove this filter from merged_filters, which specifies if filters have been sorted
                unset($GLOBALS['merged_filters'][$tag]);
            }
            return TRUE;
        }
    }
    return FALSE;
}

/**
 * Remove Class Action Without Access to Class Object
 *
 * In order to use the core WordPress remove_action() on an action added with the callback
 * to a class, you either have to have access to that class object, or it has to be a call
 * to a static method.  This method allows you to remove actions with a callback to a class
 * you don't have access to.
 *
 * Works with WordPress 1.2+
 *
 * Supported for WordPress 4.7+ added on September 19, 2016
 *
 *
 * @param string $tag Action to remove
 * @param string $class_name Class name for the action's callback
 * @param string $method_name Method name for the action's callback
 * @param int $priority Priority of the action (default 10)
 *
 * @return bool               Whether the function is removed.
 */
function remove_class_action($tag, $class_name = '', $method_name = '', $priority = 10)
{
    remove_class_filter($tag, $class_name, $method_name, $priority);
}

add_action('contextual_help', 'wptuts_screen_help', 10, 3);
function wptuts_screen_help($contextual_help, $screen_id, $screen)
{

    // The add_help_tab function for screen was introduced in WordPress 3.3.
    if (!method_exists($screen, 'add_help_tab'))
        return $contextual_help;

    global $hook_suffix;

    // List screen properties
    $variables = '<ul style="width:50%;float:left;"> <strong>Screen variables </strong>'
        . sprintf('<li> Screen id : %s</li>', $screen_id)
        . sprintf('<li> Screen base : %s</li>', $screen->base)
        . sprintf('<li>Parent base : %s</li>', $screen->parent_base)
        . sprintf('<li> Parent file : %s</li>', $screen->parent_file)
        . sprintf('<li> Hook suffix : %s</li>', $hook_suffix)
        . '</ul>';

    // Append global $hook_suffix to the hook stems
    $hooks = array(
        "load-$hook_suffix",
        "admin_print_styles-$hook_suffix",
        "admin_print_scripts-$hook_suffix",
        "admin_head-$hook_suffix",
        "admin_footer-$hook_suffix"
    );

    // If add_meta_boxes or add_meta_boxes_{screen_id} is used, list these too
    if (did_action('add_meta_boxes_' . $screen_id))
        $hooks[] = 'add_meta_boxes_' . $screen_id;

    if (did_action('add_meta_boxes'))
        $hooks[] = 'add_meta_boxes';

    // Get List HTML for the hooks
    $hooks = '<ul style="width:50%;float:left;"> <strong>Hooks </strong> <li>' . implode('</li><li>', $hooks) . '</li></ul>';

    // Combine $variables list with $hooks list.
    $help_content = $variables . $hooks;

    // Add help panel
    $screen->add_help_tab(array(
        'id' => 'wptuts-screen-help',
        'title' => 'Screen Information',
        'content' => $help_content,
    ));

    return $contextual_help;
}