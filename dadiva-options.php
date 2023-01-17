<?php

/*
*       Plugin Name: Dadiva Custom Options by Category by mrkpr0d
*       Description: Add custom option (like framed canvas) to specific product category
*/

add_action( 'woocommerce_before_single_product', 'add_dadiva_options', 15 );
function add_dadiva_options() {
    if ( ! is_singular('product') ) {
        return;
    }
    $options = get_option( 'dadiva-options' );
    $product_category = ! empty( $options['product_category'] ) ? $options['product_category'] : '';
    global $product;
    if ( has_term( $product_category, 'product_cat', $product->get_id() ) ) {
        // Add the frame options section here
        ?>

        <div class="dadiva-options">
            <h2>Marco</h2>
            <p>Seleccione la opción de dadiva deseada:</p>
            <select name="dadiva" id="dadiva">
                <option value="none">Sin Marco</option>
                <option value="enmarcado">Enmarcado</option>
            </select>
        </div>

        <?php
    }
}


add_action( 'woocommerce_before_add_to_cart_form', 'add_dadiva_image' );
function add_dadiva_image() {
    if ( ! is_singular('product') ) {
        return;
    }    
    global $product;
    $dadiva = get_post_meta( $product->get_id(), '_dadiva', true );
    if ( $dadiva ) {
        // Add the dadiva image here
    }
}

add_action( 'wp_footer', 'add_dadiva_js' );
function add_dadiva_js() {
    if ( ! is_singular('product') ) {
        return;
    }
    global $product;
    if ( has_term( 'custom-category', 'product_cat', $product->get_id() ) ) {
        // Add the JavaScript for updating the price here
    }
}

add_filter( 'woocommerce_add_cart_item_data', 'add_dadiva_to_cart', 10, 2 );
function add_dadiva_to_cart( $cart_item_data, $product_id ) {
    if ( ! is_singular('product') ) {
        return;
    }
    $dadiva = $_POST['dadiva'];
    if ( ! empty( $dadiva ) ) {
        $cart_item_data['dadiva'] = $dadiva;
    }
    return $cart_item_data;
}

add_action( 'woocommerce_add_order_item_meta', 'add_dadiva_to_order', 10, 2 );
function add_dadiva_to_order( $item_id, $cart_item_data ) {
    if ( ! is_product() ) {
        return;
    }
    if ( ! empty( $cart_item_data['dadiva'] ) ) {
        wc_add_order_item_meta( $item_id, 'dadiva', $cart_item_data['dadiva'] );
    }
}


/*
 *
 *  BACK OFFICE MENU
 * 
 */

add_action( 'admin_menu', 'register_custom_menu_page' );
function register_custom_menu_page() {
    add_menu_page(
        'Dadiva',
        'Dadiva Store',
        'manage_options',
        'dadiva-options',
        'render_custom_menu_page',
        'dashicons-cover-image',
        6
    );
} 

function render_custom_menu_page() {
    echo '<h1>Dadiva Options</h1>';
    echo '<p>Desde aqui podremos configurar opciones como el enmarcado del cuadro.</p>';
}

function register_settings() {
    register_setting( 'dadiva-options', 'product_category' );
}

add_action( 'admin_init', 'add_settings_field');
function add_settings_field() {
    add_settings_field(
        'product_category',
        'Product Category',
        'render_product_category_field',
        'dadiva-options',
        'dadiva-options'
    );
}


function render_product_category_field() {
    $options = get_option( 'dadiva-options' );
    $product_category = ! empty( $options['product_category'] ) ? $options['product_category'] : '';
    echo '<input type="text" name="dadiva-options[product_category]" value="' . esc_attr( $product_category ) . '" />';
}



add_action( 'woocommerce_add_to_cart', 'save_custom_meta_data', 10, 2 );
function save_custom_meta_data( $cart_item_key, $product_id ) {
    if ( isset( $_POST['dadiva-options'] ) ) {
        WC()->cart->add_meta_data( 'frame_option', sanitize_text_field( $_POST['frame_option'] ), $cart_item_key );
    }
}

?>