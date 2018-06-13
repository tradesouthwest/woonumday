<?php 
/**
 * @package woonumday
 *
 */
defined( 'ABSPATH' ) or exit;
/**
 * 
@action: woocommerce_add_to_cart
    desc: When an item is added to the cart. 
    args: $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data

@action: woocommerce_add_order_item_meta
    desc: When a cart item is converted to an order item, save metadata to the order item
          using the function "wc_add_order_item_meta( $item_id, $key, $value )"
    args: item_id, $values, $cart_item_key
 * ***********************************************
 */ 
/**
 * *****************************************
 * Admin side numeric field functions
 *
 * ***************************************** */
// Add Number Field to admin General tab
add_action( 'woocommerce_product_options_general_product_data', 
            'woonumday_general_product_ppd_field' 
            );
function woonumday_general_product_ppd_field() 
{
    woocommerce_wp_text_input( array( 
        'id' => '_wnd_feeperday', 
        'name' => '_wnd_feeperday', 
        'label' => __( 'Rental Days Price', 'woocommerce' ), 
        'placeholder' => '', 
        'description' => __( 'Price per day.', 'woocommerce' ), 
        'type' => 'number', 
        'custom_attributes' => array( 'step' => 'any', 'min' => '0' ) 
        ) 
    );
}

/** 
 * Hook callback function to save custom fields 
 * 
 * @use update_post_meta
 */
add_action( 'woocommerce_process_product_meta', 'woonumday_save_product_ppd_field' ); 
function woonumday_save_product_ppd_field( $post_id ) 
{
    // Save Number Field
    $number_field = $_POST['_wnd_feeperday'];
    
    if( ! empty( $number_field ) ) {
        update_post_meta( $post_id, '_wnd_feeperday', esc_attr( $number_field ) );
    }

}

/**
 * Add calculated fees to cart
 *
 * Shows after subtotals
 * *******************************
 * Change $taxx param if needed
 * *******************************
 * @get_cart_item( string $item_key ) Returns a specific item in the cart.
 */


/**
 * *****************************************
 * Public facing numeric field functions
 *
 * ***************************************** */

/**
 * store the custom fields for numdays being added to cart into cart item data 
 * ( each cart item has their own data )
 * @hook woocommerce_add_cart_item_data
 */ 
add_filter('woocommerce_add_cart_item_data',  'woonumday_save_wnd_quantity', 10, 2 );
//add_action( 'woocommerce_add_cart_item_data', 'woonumday_save_wnd_feefordays' );
function woonumday_save_wnd_quantity( $cart_item_data, $product_id ) 
{
    // Save Number Field
   if (get_post_meta($product_id, '_wnd_quantity') && $_REQUEST['_wnd_quantity']){
					$cart_item_data['_wnd_quantity'] = $REQUEST['_wnd_quantity'];
					$cart_item_data['unique_key'] = md5($product_id . '_wnd_quantity');
      	      }
      	      return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data',  'woonumday_save_wnd_feefordayz', 10, 2 );
//add_action( 'woocommerce_add_cart_item_data', 'woonumday_save_wnd_feefordays' );
function woonumday_save_wnd_feefordayz( $cart_item_data, $product_id ) 
{
     // Save Number Field
    if( isset( $_REQUEST['_wnd_feefordays'] ) ) {
        $cart_item_data[ '_wnd_feefordays' ] = round($_REQUEST['_wnd_feefordays'], 2);
        /* make sure every add to cart action as unique line item */
        $cart_item_data['unique_key'] = md5( microtime().rand() );
    }
        return $cart_item_data;
} 
/**
 * display numdays field on product page
 * Numeric field
 */ 
add_action( 'woocommerce_before_add_to_cart_button', 'woonumday_show_quantity_onpage' ); 
function woonumday_show_quantity_onpage($product_id)
{     
global $woocommerce, $post;
$product_id = get_the_ID();
    //$hash = md5( microtime().rand() );
    $wnd_quantity   = '';
    $wnd_feeperday = '';
    $wnd_feefordays = '';
      
    //$wnd_quantity  = get_post_meta( $product_id, '_wnd_quantity', true );
    $wnd_feeperday  = get_post_meta( $product_id, '_wnd_feeperday', true ); 
    //$wnd_feefordays = get_post_meta( $product_id, '_wnd_feefordays', true );
     
     echo '<div class="wmd-quantity">
          <label for="color">Number of Days Renting?</label> 
                <input id="wnd_quantity" 
                class="input-text qty text" step="1" min="1" max="" 
                name="wnd_quantity" value="'. esc_attr( $wnd_quantity ) .'" title="Qty" size="4" pattern="[0-9]*"
                 inputmode="numeric" aria-labelledby="number of days renting" type="number"
                 style="width:67px;" onchange="ttlFeeValue();"> 
                  
                <input id="wnd_feeperday" 
                class="input-text qty text"
                name="wnd_quantity" value="'. esc_attr( $wnd_feeperday ) .'" title="Qty" 
                 type="text"
                 style="width:67px;">    
                 
                <input id="wnd_feefordays" 
                class="input-text qty text" 
                name="wnd_feefordays" value="" title="Qty" 
                 type="text"
                 style="width:67px;">  
                 <script type="text/javascript">
                 function ttlFeeValue() {
var textQnt = document.getElementById("wnd_quantity");  
var textOne = document.getElementById("wnd_feeperday");
var textTwo = document.getElementById("wnd_feefordays");
var textQuantity = textQnt.value;
var textOneValue = textOne.value;
var textTwoValue = textOneValue * textQuantity;
textTwo.value    = Number((textTwoValue).toFixed(2));
var textQnt = "";
var textOne = "";
var textTwo = "";

}</script>          
        </div>';
    
}


add_filter('woocommerce_add_cart_item', 'woonumday_add_quantity_tocart' );
function woonumday_add_quantity_tocart($cart_item, $cart_item_key)
{
		if ($cart_item['wnd_quantity'] === true){
			$cart_item['data']->price = 0;
		}
		return $cart_item;
}
/**
 * 
$woocommerce->cart->remove_cart_item($cart_key); 
add_filter('woocommerce_get_cart_item_from_session', array( $wnd_quantity, 'filter_session'), 10, 3);
*/


/**
 * show on cart
 * 
 * @hook woocommerce_get_cart_item
 */ 
function woonumdays_render_meta_cart_item( $title=null, $cart_item=null, $cart_item_key=null ) 
{
    
    $ndays_field =  $cart_item['_wnd_feefordays'];
    //woonumdays_render_ndays_field($item);

    if( $cart_item_key && is_cart() ) {
        return $title. '<div class="numberofdays-field">Fees '. $ndays_field .'</div>';
    }else {
        return $title;
    }
}
add_filter( 'woocommerce_cart_item_price', 'woonumdays_render_meta_cart_item', 10, 3 );


/**
 * display numdays field by qty
 */ 
function woonumdays_render_meta_on_cart_item( $title=null, $cart_item=null, $cart_item_key=null ) {
    
    $ndays_field =  $cart_item['_wnd_quantity'];
    //woonumdays_render_ndays_field($item);

    if( $cart_item_key && is_cart() ) {
        return $title. '<div class="numberofdays-field">For '. $ndays_field .' Days</div>';
    }else {
        return $title;
    }
}
add_filter( 'woocommerce_cart_item_quantity', 'woonumdays_render_meta_on_cart_item', 10, 3 );

/**
 * show on checkout 
 * 
 * @hook woocommerce_add_order_item_
 */ 
add_action( 'woocommerce_add_order_item_meta', 'woonumday_order_meta_handler', 1, 3 );
function woonumday_order_meta_handler( $item_id, $values, $cart_item_key ) 
{
    if( isset( $values['_wnd_quantity'] ) ) {
        wc_add_order_item_meta( $item_id, "_wnd_quantity", 
                                  $values['_wnd_quantity'] );
    }
}


//Add Custom Details as Order Line Items
add_action( 'woocommerce_checkout_create_order_line_item', 
            'wnd_add_custom_order_line_item_meta',10,4 );

function wnd_add_custom_order_line_item_meta($item, $cart_item_key, $values, $order)
{

    if(array_key_exists('wnd_feefordays', $values))
    {
        $item->add_meta_data('_wnd_feefordays',$values['wnd_feefordays']);
    }
} 
