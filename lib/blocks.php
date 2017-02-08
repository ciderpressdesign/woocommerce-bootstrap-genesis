<?php
/**
 * Created by PhpStorm.
 * User: snowd
 * Date: 2/2/2017
 * Time: 1:03 PM
 */


function subscribe_and_save_block() {
    ?>

    <div class="social-and-subscribe">
        <div class="row">
                <div class="social btn-group btn-group-justified">

                    <?php $social_links = get_fields('option');

                    if( $social_links ) {
                        foreach ($social_links as $field_name => $value) {
                            if (!is_empty($value)) {
                                // get_field_object( $field_name, $post_id, $options )
                                // - $value has already been loaded for us, no point to load it again in the get_field_object function
                                $field = get_field_object($field_name, false, array('load_value' => false));

                                ?>
                                    <a class="btn btn-info <?php $field_name ?>" href="<?php echo $value ?>">
                                        <?php echo substr($field_name, 0, 1) ?>
                                    </a>
                                <?php
                            }
                        }
                    }

                    ?>

                </div>
        </div>

            <div class="row">
                <h4>Subscribe & Save</h4>
            </div>
            <div class="row">
                <form>
                    <div class="form-group">
                        <input type="text" name="" id="subscribe-name" class="form-control" placeholder="NAME">
                    </div>
                    <div class="form-group">
                        <input type="text" name="" id="subscribe-email" placeholder="EMAIL" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-default pull-right">SUBSCRIBE</button>
                </form>
            </div>
        </div>


<?php
}