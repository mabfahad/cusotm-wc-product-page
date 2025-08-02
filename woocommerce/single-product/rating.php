<?php
// Get average rating (float), e.g. 4.6
$average_rating = $product->get_average_rating();

// Get total review count, e.g. 2000
$review_count = $product->get_review_count();

// Generate fixed 5 stars visually — you can style them later with CSS
$stars_display = '★ ★ ★ ★ ★';

// Format review count if 1000+
$formatted_reviews = $review_count > 999 ? number_format($review_count) . '+' : $review_count;
?>

<p class="rating" data-rating="<?php echo esc_attr($average_rating); ?>" aria-label="">
    <span class="stars">★★★★★</span><span
            class="reveiw_details"><?php echo esc_html($average_rating) ?>(<?php echo esc_html($review_count); ?> reviews)</span>
</p>