<?php
/**
 * Created by PhpStorm.
 * User: snowd
 * Date: 2/23/2017
 * Time: 8:08 PM
 */


remove_action('genesis_entry_footer', 'genesis_post_meta');
remove_action('genesis_entry_header', 'genesis_post_info', 12);
//add_action('genesis_entry_header', 'genesis_post_info',3);

add_action('genesis_before_entry', 'gem_blog_post_info', 3);

function gem_blog_post_info()
{
    global $post;
    if ('post' == $post->post_type) {


        $month = get_the_date('M', $post);
        $day = get_the_date('j', $post);
        $year = get_the_date('Y', $post);
        ?>
        <div class="col-xs-2 col-sm-2 col-md-1 col-no-gutter entry-date-col">
            <div class="entry_date">
                <div class='month'><?php echo $month ?></div>
                <div class='day'><?php echo $day ?></div>
                <div class='year'><?php echo $year ?></div>
            </div>
        </div>
        <?php
    }
}

add_action('genesis_entry_header', 'gem_blog_post_image', 2);

function gem_blog_post_image()
{
    $image = get_field('blog_post_image');
    $post_image = $image['sizes']['product-lg'];
    echo "<img src='$post_image'>";
}


