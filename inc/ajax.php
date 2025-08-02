<?php
add_action('wp_ajax_wc_add_to_cart_ajax', 'wc_add_to_cart_ajax');
add_action('wp_ajax_nopriv_wc_add_to_cart_ajax', 'wc_add_to_cart_ajax');

function wc_add_to_cart_ajax() {
//    echo "<pre>";print_r($_POST);echo "</pre>";exit();
    check_ajax_referer('wc_custom_nonce', 'nonce');


    $variation_id   = isset($_POST['variation']) ? absint($_POST['variation']) : 0;
    $product_id     = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $quantity       = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;

    $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);

    if ($added) {
        // Return updated fragments for cart
//        WC_AJAX::get_refreshed_fragments();
        wp_send_json_success(
            [
                'status' => 200
            ]
        );
    } else {
        wp_send_json_error(['status' => 503]);
    }

    wp_die();
}