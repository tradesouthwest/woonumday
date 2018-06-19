<?php 
/**
 * @package woonumday
 *
 */
defined( 'ABSPATH' ) or exit;

/**
 * *****************************************
 * Post meta and Cart meta
 * 
 * **************************************** */
// Add Number Field to admin General tab
add_action( 'woocommerce_product_options_general_product_data', 
        'woonumdays_general_product_data_field' );
function woonumdays_general_product_data_field() 
{
    woocommerce_wp_text_input( array( 
        'id'    => '_woonumday_fee', 
        'name'    => '_woonumday_fee', 
        'label'     => __( 'Rental Days Fee', 'woonumday' ), 
        'placeholder' => '', 
        'description'   => __( 'Price per day.', 'woonumday' ), 
        'type'            => 'number', 
        'custom_attributes' => array( 'step' => 'any', 
                                      'min' => '0' ) 
        ) );

}

/**
 * Save the whales... I mean save post meta
 * Hook callback functions to save custom fields 
 *
 * meta_id[26117] post_id[1696] meta_key[_woonumday_fee] meta_value[int]
 * TODO option for use_product_price or use_woonumday_price_field
 * https://www.ibenic.com/how-to-add-woocommerce-custom-product-fields/
 */
 
add_action( 'woocommerce_process_product_meta', 'woonumday_save_woonumday_fee' );
 
function woonumday_save_woonumday_fee( $post_id ) 
{
//global $product;

    $custom_field_value = isset( $_POST['_woonumday_fee'] ) 
                               ? $_POST['_woonumday_fee'] : '';
    $custom_field_clean = sanitize_text_field( $custom_field_value );
    $product = wc_get_product( $post_id );
    $product->update_meta_data( '_woonumday_fee', $custom_field_clean );
    $product->save();

}

//add_action( 'woocommerce_before_calculate_totals', 'woonumday_add_custom_price', 10, 2 );
//could use 
//round( wc_add_number_precision_deep( $cart_item['data']->get_price() ) * $cart_item['quantity'] )
function woonumday_add_custom_price( $cart_object, $product_id ) {

  
}

/**
 * Add dialog to Order in Admin page
 * Adds at end of order details 
 * 
 * @uses woocommerce_admin_order_data_after_shipping_address
 */ 
add_action( 'woocommerce_admin_order_data_after_shipping_address',
        'woonumday_checkout_field_display_admin_order_meta', 10, 1 );
function woonumday_checkout_field_display_admin_order_meta($order)
{   
    $label = get_option('woonumday_options')['woonumday_csdescription_field'];
    echo '<p style="color:green"><strong>'. $label .':</strong> 
    <span style="font-size:106.78%"> ' . get_post_meta( $order->get_id(), 
    '_woonumdays_fee', true ) . '</span></p>';
}

/*
add_filter('add_to_cart_custom_fragments', 'woocommerce_header_add_to_cart_custom_fragment');
function woocommerce_header_add_to_cart_custom_fragment( $cart_fragments ) {
                global $woocommerce;
                ob_start();
                ?>
                <a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View   cart', 'woothemes'); ?>"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?></a>
                <?php
                $cart_fragments['a.cart-contents'] = ob_get_clean();
                return $cart_fragments;
}
*/
