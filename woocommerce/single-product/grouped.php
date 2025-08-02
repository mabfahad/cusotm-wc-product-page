<?php
if ( $product && $product->is_type('grouped') ) :
    $children = $product->get_children();
    foreach ( $children as $index => $child_id ) {
        $child_product = wc_get_product( $child_id );
        ?>
        <div class="subscription">
            <label class="subscription-label">
                <div>
                    <input type="radio"
                           name="plan"
                           id="plan_<?php echo esc_attr( $child_id ); ?>"
                           data-grouped-id = <?php echo esc_attr( $child_id ); ?>
                           <?php checked( $index === 0 ); ?>
                           onclick="setPlan('single')">
                    <span class="plan-title"><?php echo esc_html( $child_product->get_name() ); ?></span>
                </div>

                <?php include locate_template( 'woocommerce/single-product/pricing.php' ); ?>
            </label>

            <?php include locate_template( 'woocommerce/single-product/variation.php' ); ?>
        </div>
        <?php
    }
endif;
