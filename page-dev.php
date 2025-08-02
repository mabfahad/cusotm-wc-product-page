<?php
/*
 * Template Name: Developer
 */

function add_manual_review_to_product( $product_id ) {
    $commentdata = array(
        'comment_post_ID'      => $product_id,
        'comment_author'       => 'John Doe',
        'comment_author_email' => 'john@example.com',
        'comment_content'      => 'Great product! Highly recommend.',
        'comment_type'         => 'review',
        'comment_approved'     => 1,
        'user_id'              => 0,
    );

    $comment_id = wp_insert_comment( $commentdata );

    if ( $comment_id ) {
        // Add rating meta (1–5 integer only)
        add_comment_meta( $comment_id, 'rating', 5, true );

        // Recalculate average rating
//        wc_update_product_rating( $product_id );
    }
    return $comment_id;
}

// Example usage:
$comment_id = add_manual_review_to_product(61); // Replace 123 with your product ID

add_comment_meta( $comment_id, 'rating', 3 );
