<?php
/**
 * Created by PhpStorm.
 * User: snowd
 * Date: 2/26/2017
 * Time: 1:01 PM
 */

add_action('genesis_before_loop', function () {
    ?>

    <div class="panel-group faq-group" id="accordion" role="tablist" aria-multiselectable="true">
    <?php
});
add_action('genesis_after_loop', function () {
    echo "</div>";
});
remove_action('genesis_entry_header', 'genesis_do_post_title');
//remove_action('genesis_entry_content', 'genesis_do_post_content');
add_action('genesis_before_entry', function () {

    $element_id = "faq" . get_the_id();
    ?>
    <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="<?php echo 'heading' . $element_id ?>">
        <h4 class="panel-title">
            <a role="button" class="collapsed"
               data-toggle="collapse"
               data-parent="#accordion"
               href="#<?php echo $element_id ?>"
               aria-expanded="true" aria-controls="<?php echo $element_id ?>">
                <span class="faq-title"><?php the_title(); ?></span>
            </a>
        </h4>
    </div>
<div id="<?php echo $element_id ?>" class="panel-collapse collapse" role="tabpanel"
     aria-labelledby="<?php echo $element_id ?>">
        <div class="panel-body">
            <div class="faq-body">

    <?php
});

add_action('genesis_after_entry', function () {
    echo "</div></div></div></div>";
});

genesis();