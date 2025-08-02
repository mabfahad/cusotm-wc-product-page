<?php
if ($child_product && $child_product->is_type('variable')) :
$variation_ids = $child_product->get_children();
$flavor_options = [];

foreach ($variation_ids as $variation_id) {
    $variation = wc_get_product($variation_id);
    $attributes = $variation->get_attributes();

    // Ensure 'pa_flavor' exists and collect its values
    if (isset($attributes['pa_flavor'])) {
        $flavor_slug = $attributes['pa_flavor'];
        $term = get_term_by('slug', $flavor_slug, 'pa_flavor');
        if ($term) {
            $flavor_name = $term->name;
            $flavor_options[$flavor_slug] = [
                'name' => $flavor_name,
                'image' => $variation->get_image_id() ? wp_get_attachment_image_url($variation->get_image_id(), 'medium') : esc_url(wc_placeholder_img_src()),
                'is_best_seller' => get_post_meta($variation_id, 'is_best_seller', true),
                'product_id' => $variation_id,
            ];
        }
    }
}
?>
<div id="singleDrinkSections">
    <div class="flavor-section">
        <p>Choose <?php echo wc_attribute_label('pa_flavor'); ?></p>
        <div class="flavor-options">
            <?php
            $first = true;
            foreach ($flavor_options as $slug => $data) :
                if ($data['name'] == 'chocolate' && $index == 0) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                ?>
                <label>
                    <div class="options-group">
                        <input type="radio" name="flavor-single" data-variation-id="<?php echo $data['product_id']; ?>"
                               value="<?php echo esc_attr($slug); ?>" <?php echo $checked; ?>>
                        <span><?php echo esc_html($data['name']); ?></span>
                    </div>
                    <img src="<?php echo esc_url($data['image']); ?>"
                         alt="">
                    <?php if (!empty($data['is_best_seller']) && $data['is_best_seller'] === 'yes') : ?>
                        <span class="tags">BEST-SELLER</span>
                    <?php endif; ?>
                </label>
                <?php
                $first = false;
            endforeach;
            ?>
        </div>
    </div>

    <!-- Keep your includes section as-is -->
    <div class="includes">
        <?php
        $whats_included = get_post_meta(get_the_ID(), '_whats_included_content', true);
        if ($whats_included) {
            echo '<p class="includes-p">What’s Included:</p>';
            echo '<div class="whats-included">';
            echo wp_kses_post(wpautop($whats_included));
            echo '</div>';
        }
        ?>


    </div>
</div>
<?php endif; ?>