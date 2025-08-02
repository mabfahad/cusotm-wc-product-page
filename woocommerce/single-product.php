<?php
/**
 * Custom Single Product Page Template
 *
 * Place this file in your theme's folder as single-product.php
 * or woocommerce/single-product.php
 */

defined('ABSPATH') || exit;

get_header(); ?>
    <div class="woocommerce-product-details__container">

        <?php
        /**
         * Hook: woocommerce_before_main_content.
         *
         * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
         * @hooked woocommerce_breadcrumb - 20
         */
        do_action('woocommerce_before_main_content');
        ?>

        <main id="main" class="site-main ">
            <div class="container">
                <div class="grid">
                    <?php
                        global $product;
                        include( locate_template( 'woocommerce/single-product/gallery.php' ) );
                    ?>

                    <div class="product-box">
                        <h1><?php echo esc_html($product->get_title()); ?></h1>
                        <?php
                            include( locate_template( 'woocommerce/single-product/rating.php' ) );
                        ?>

                        <p class="desc">
                            <?php echo esc_html($product->get_description()); ?>
                        </p>

                        <div class="badge">Recommended</div>
                        <div class="grouped">
                            <?php include( locate_template( 'woocommerce/single-product/grouped.php' ) ); ?>
                        </div>
                        <?php wp_nonce_field('wc_custom_nonce', 'wc_custom_nonce_field');?>
                        <button class="add-to-cart">Add to Cart</button>
                    </div>
                </div>
            </div>
        </main>


    </div>

<?php get_footer(); ?>