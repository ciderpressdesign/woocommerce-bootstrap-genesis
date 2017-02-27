<?php
/**
 * Created by PhpStorm.
 * User: snowd
 * Date: 2/2/2017
 * Time: 1:03 PM
 */


function social_links_block()
{
    ?>

    <div class="social">
        <div class="social--links btn-group">

            <?php
            // check if the repeater field has rows of data
            if (have_rows('social_link', 'options')):

                // loop through the rows of data
                while (have_rows('social_link', 'options')) : the_row();

                    // display a sub field value
                    $social_network_name = strtolower(get_sub_field('name'));
                    $image = get_sub_field('icon');
                    $social_network_icon = $image['sizes']['gem-icon'];
                    $social_network_link = get_sub_field('link');
                    ?>
                    <a class="btn <?php echo $social_network_name ?>" href="<?php echo $social_network_link ?>">
                        <img src="<?php echo $social_network_icon ?>">
                    </a>
                    <?php

                endwhile;

            else :

                // no rows found

            endif;
            ?>

        </div>
    </div>
    <?php
}

function subscribe_and_save_block()
{
    ?>
    <div class="subscribe-block">
        <h4>Subscribe & Save</h4>
        <form>
            <div class="form-group">
                <input type="text" name="" id="subscribe-name" class="form-control" placeholder="NAME">
            </div>
            <div class="form-group">
                <input type="text" name="" id="subscribe-email" placeholder="EMAIL" class="form-control">
            </div>
            <button type="submit" class="btn btn-sm btn-default pull-right">SUBSCRIBE</button>
        </form>
    </div>


    <?php
}

function info_link_block()
{
    ?>
    <div class="info-links">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="telephone"><a href="tel:">888-GEM-SYND</a></div>
                </div>
                <div class="col-sm-3">
                    <div class="copyright">
                        &copy;2017 The Gem Syndicate
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3 privacy-sitemap">
                    <div class="row">
                        <div class="col-xs-6 privacy">
                            <a href="">privacy</a>
                        </div>
                        <div class="col-xs-6 site-map-link">
                            <a href="">site map</a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3 shipping-terms">
                    <div class="row">
                        <div class="col-xs-6 shipping">
                            <a href="">shipping / returns</a>
                        </div>
                        <div class="col-xs-6 terms">
                            <a href="">terms & conditions</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
}
