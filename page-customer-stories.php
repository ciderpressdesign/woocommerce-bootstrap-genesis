<?php
/**
 * Created by PhpStorm.
 * User: snowd
 * Date: 2/2/2017
 * Time: 4:47 PM
 */

//remove_action('genesis_loop','genesis_do_loop');
add_action('genesis_after_loop','gem_testimonial_custom_loop');

function gem_testimonial_custom_loop()
{
    $args = array(
      'post_type' => 'testimonial'
    );

    // The Query
    $the_query = new WP_Query($args);

// The Loop
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
            ?>
            <div class="row">
                <div class="col-xs-8 col-xs-offset-2">
                <blockquote>
                    <p><?php the_content() ?></p>
                    <footer class="text-center">
                        <?php the_title() ?>
                    </footer>
                </blockquote>
                </div>
            </div>

            <?php
        }
        /* Restore original Post Data */
        wp_reset_postdata();
    } else {
        // no posts found
    }
}


genesis();
