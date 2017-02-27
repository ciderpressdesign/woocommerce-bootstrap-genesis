<?php
/**
 * Created by PhpStorm.
 * User: snowd
 * Date: 2/26/2017
 * Time: 1:01 PM
 */
add_action('genesis_after_loop', 'gem_faq_loop');


function gem_faq_loop()
{
    $args = array(
        'posts_per_page' => -1,
        'offset' => 0,
        'category' => '',
        'category_name' => '',
        'orderby' => 'date',
        'order' => 'DESC',
        'include' => '',
        'exclude' => '',
        'meta_key' => '',
        'meta_value' => '',
        'post_type' => 'faq',
        'post_mime_type' => '',
        'post_parent' => '',
        'author' => '',
        'author_name' => '',
        'post_status' => 'publish',
        'suppress_filters' => true
    );
    $faqs = get_posts($args);

    echo '<div class="panel-group faq-group" id="accordion" role="tablist" aria-multiselectable="true">';

    foreach ($faqs as $post) : setup_postdata($post);
        {

            $element_id = "faq" . $post->ID;
            ?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="<?php echo 'heading' . $element_id ?>">
                    <h4 class="panel-title">
                        <a role="button" class="collapsed"
                           data-toggle="collapse"
                           data-parent="#accordion"
                           href="#<?php echo $element_id ?>"
                           aria-expanded="true" aria-controls="<?php echo $element_id ?>">
                            <span class="faq-title"><?php echo $post->post_title ?></span>
                        </a>
                    </h4>
                </div>
                <div id="<?php echo $element_id ?>" class="panel-collapse collapse" role="tabpanel"
                     aria-labelledby="<?php echo $element_id ?>">
                    <div class="panel-body">
                        <div class="faq-body">
                            <p>
                                <?php echo $post->post_content ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    endforeach;
    echo "</div>";
    wp_reset_postdata();
}

genesis();
