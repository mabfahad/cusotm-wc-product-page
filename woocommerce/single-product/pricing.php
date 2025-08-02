<span class="price"><?php echo get_woocommerce_currency_symbol() . wp_kses_post(esc_html($child_product->get_price())); ?>
    <?php if ($child_product->get_sale_price() != "") : ?>
        <del>
            <?php echo get_woocommerce_currency_symbol(); ?>
            <?php echo esc_html($child_product->get_sale_price()); ?>
        </del>
    <?php endif; ?>
</span>