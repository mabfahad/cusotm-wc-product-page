<?php
/**
 * Enhanced Twenty Twenty-Five Child Theme functions.php
 * Includes custom product fields for subscription options and flavors
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue parent and child theme styles
 */
function twentytwentyfive_child_enqueue_styles() {
    // Enqueue parent theme style
    wp_enqueue_style('twentytwentyfive-parent-style', get_template_directory_uri() . '/style.css');

    // Enqueue child theme style
    wp_enqueue_style('twentytwentyfive-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('twentytwentyfive-parent-style'),
        wp_get_theme()->get('Version')
    );

    // Enqueue custom WooCommerce styles
    if (class_exists('WooCommerce')) {
        wp_enqueue_style('twentytwentyfive-woocommerce-custom',
            get_stylesheet_directory_uri() . '/assets/css/woocommerce-custom.css',
            array('twentytwentyfive-child-style'),
            wp_get_theme()->get('Version')
        );

        // Enqueue custom JavaScript for enhanced functionality
        wp_enqueue_script('twentytwentyfive-woocommerce-custom',
            get_stylesheet_directory_uri() . '/assets/js/woocommerce-custom.js',
            array('jquery', 'wc-cart-fragments'),
            wp_get_theme()->get('Version'),
            true
        );

        // Localize script for AJAX
        wp_localize_script('twentytwentyfive-woocommerce-custom', 'wc_custom_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wc_custom_nonce'),
            'loading_text' => __('Loading...', 'twentytwentyfive-child'),
            'error_text' => __('Something went wrong. Please try again.', 'twentytwentyfive-child')
        ));
    }
}
add_action('wp_enqueue_scripts', 'twentytwentyfive_child_enqueue_styles');

add_filter( 'template_include', 'force_single_product_template' );
function force_single_product_template( $template ) {
    if ( is_singular( 'product' ) ) {
        $custom_template = locate_template( 'woocommerce/single-product.php' );
        if ( $custom_template ) {
            return $custom_template;
        }
    }
    return $template;
}

// Show checkbox in variation settings using custom HTML
add_action( 'woocommerce_variation_options', function( $loop, $variation_data, $variation ) {
    $is_best_seller = get_post_meta( $variation->ID, 'is_best_seller', true );
    echo '<div class="form-row form-row-full">';
    echo '<label><input type="checkbox" name="is_best_seller[' . $loop . ']" value="yes" ' . checked($is_best_seller, 'yes', false) . '> Best Seller?</label>';
    echo '<span class="description">Mark this variation as a best-seller</span>';
    echo '</div>';
}, 10, 3 );

// Save checkbox value
add_action( 'woocommerce_save_product_variation', function( $variation_id, $i ) {
    // Check if the checkbox was submitted and has a value
    $value = isset( $_POST['is_best_seller'][$i] ) && $_POST['is_best_seller'][$i] === 'yes' ? 'yes' : 'no';
    update_post_meta( $variation_id, 'is_best_seller', $value );
}, 10, 2 );

// Optional: Display best seller badge on frontend
add_filter( 'woocommerce_available_variation', function( $variation_data, $product, $variation ) {
    $is_best_seller = get_post_meta( $variation->ID, 'is_best_seller', true );
    $variation_data['is_best_seller'] = $is_best_seller === 'yes';
    return $variation_data;
}, 10, 3 );

add_action('add_meta_boxes', 'add_whats_included_metabox');
function add_whats_included_metabox() {
    add_meta_box(
        'whats_included_metabox',
        __('What’s Included', 'your-textdomain'),
        'render_whats_included_metabox',
        'product',
        'normal',
        'default'
    );
}

function render_whats_included_metabox($post) {
    $content = get_post_meta($post->ID, '_whats_included_content', true);

    wp_nonce_field('save_whats_included_metabox', 'whats_included_nonce');

    wp_editor(
        $content,
        'whats_included_editor',
        array(
            'textarea_name' => '_whats_included_content',
            'media_buttons' => true,
            'textarea_rows' => 10,
        )
    );
}

add_action('save_post_product', 'save_whats_included_metabox_data');
function save_whats_included_metabox_data($post_id) {
    if (!isset($_POST['whats_included_nonce']) || !wp_verify_nonce($_POST['whats_included_nonce'], 'save_whats_included_metabox')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['_whats_included_content'])) {
        update_post_meta($post_id, '_whats_included_content', wp_kses_post($_POST['_whats_included_content']));
    }
}

//Ajax
require_once get_stylesheet_directory() . '/inc/ajax.php';