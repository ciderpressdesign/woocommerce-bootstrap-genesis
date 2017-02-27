<?php
/**
 * Created by PhpStorm.
 * User: snowd
 * Date: 2/26/2017
 * Time: 1:01 PM
 */


remove_action('genesis_entry_header', 'genesis_do_post_title');
remove_action('genesis_entry_content', 'genesis_do_post_content');
add_action('genesis_entry_content', 'gem_testimonial_loop');


function gem_testimonial_loop()
{
    ?>
    <div class="testimonial-block">
        <div class="row">
            <div class="col-xs-10 col-xs-offset-1">
                <div class="text-center testimonial-quote"><?php echo get_the_content() ?></div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 text-center">
                <strong>- <?php the_title() ?></strong>
            </div>
        </div>
    </div>

    <?php
}

genesis();