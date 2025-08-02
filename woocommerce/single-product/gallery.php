<div class="product-gallery">
    <div class="main-image">
        <?php
        // Main product image ID and URL (large size)
        $main_image_id = $product->get_image_id();
        $main_image_url = $main_image_id ? wp_get_attachment_image_url($main_image_id, 'large') : wc_placeholder_img_src();

        // Collect all images URLs for dots/thumbnails (main image + gallery images)
        $all_images = array();

        // Add main image URL if exists
        if ($main_image_id) {
            $all_images[] = $main_image_url;
        }

        // Get gallery image IDs
        $attachment_ids = $product->get_gallery_image_ids();

        // Add gallery images URLs (large size)
        foreach ($attachment_ids as $attachment_id) {
            $large_url = wp_get_attachment_image_url($attachment_id, 'large');
            if ($large_url) {
                $all_images[] = $large_url;
            }
        }
        ?>

        <img id="featured" src="<?php echo esc_url($main_image_url); ?>"
             alt="<?php echo esc_attr(get_post_meta($main_image_id, '_wp_attachment_image_alt', true) ?: $product->get_name()); ?>">

        <!-- Controls below main image -->
        <div class="main-image-controls">
            <button class="nav prev">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                    <path d="M13.8437 6.41642H3.9803L8.5108 1.88592L7.35994 0.743164L0.876221 7.22688L7.35994 13.7106L8.5027 12.5678L3.9803 8.03735H13.8437V6.41642Z"
                          fill="#322D2D"/>
                </svg>
            </button>
            <div class="dots">
                <?php
                foreach ($all_images as $idx => $img_url) {
                    $active = ($idx === 0) ? 'active' : '';
                    echo '<span class="dot ' . esc_attr($active) . '" data-index="' . esc_attr($idx) . '"></span>';
                }
                ?>
            </div>
            <button class="nav next">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.188324 6.41642H10.0517L5.52118 1.88592L6.67204 0.743164L13.1558 7.22688L6.67204 13.7106L5.52929 12.5678L10.0517 8.03735H0.188324V6.41642Z"
                          fill="#322D2D"/>
                </svg>
            </button>
        </div>
    </div>
    <div class="thumbnails">
        <?php
        // Main image thumbnail
        if ($main_image_id) {
            echo '<img src="' . esc_url(wp_get_attachment_image_url($main_image_id, 'thumbnail')) . '" class="thumb active" data-large="' . esc_url($main_image_url) . '">';
        } else {
            // Fallback placeholder thumbnail
            echo '<img src="' . esc_url(wc_placeholder_img_src()) . '" class="thumb active" data-large="' . esc_url(wc_placeholder_img_src()) . '">';
        }

        // Gallery image thumbnails
        foreach ($attachment_ids as $attachment_id) {
            $thumb_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
            $large_url = wp_get_attachment_image_url($attachment_id, 'large');
            if ($thumb_url && $large_url) {
                echo '<img src="' . esc_url($thumb_url) . '" data-large="' . esc_url($large_url) . '" class="thumb">';
            }
        }
        ?>
    </div>
</div>