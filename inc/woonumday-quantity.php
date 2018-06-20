<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Display input on single product page
 * @return html
 */
function woonumday_custom_option()
{ 
global $woocommerce, $post;
    //$ppday = get_post_meta( $post->ID, 'woonumday_fee', true ); 
    $plchld = '';
    $wndtext = get_option('woonumday_options')['woonumday_wndproduct_field'];
    $wndwdth = get_option('woonumday_options')['woonumday_wndinwidth_field'];
    $value = isset( $_POST['_woonumday_custom_option'] ) 
    ? sanitize_text_field( $_POST['_woonumday_custom_option'] ) : '';
    
    printf( '<div class="aligncenter wnd"> 
            <label>%s </label>
            <input id="wnd_quantity"
                   class="input-text wndqty"
                   style="%s" 
                   type="number" 
                   name="_woonumday_custom_option" 
                   value="%s"
                   placeholder="" /> </div>', 
                   esc_html( $wndtext ), 
                   esc_attr( $wndwdth ), 
                   esc_attr( $value )
                   );
   
      
}
add_action( 'woocommerce_before_add_to_cart_button', 'woonumday_custom_option', 11 );

/**
 * Display price on single product page
 * @return html
 */
function woonumday_custom_option_label($product)
{ 
global $woocommerce, $product;
    $label = get_option('woonumday_options')['woonumday_cstitle_field']; 
    $ppday = get_post_meta( $product->get_id(), '_woonumday_fee', true ); 
    
    printf( '<div class="input-text wndfee">
            <label>%s </label>
            <span>%s</span></div>',
            esc_html( $label ), 
            esc_html( $ppday ) 
            ); 
} 
add_action( 'woocommerce_single_product_summary', 'woonumday_custom_option_label', 9 );

/**
 * Validate when adding to cart
 * @param bool $passed
 * @param int $product_id
 * @param int $quantity
 * @return bool
 */
function woonumday_add_to_cart_validation($passed, $product_id, $qty){
    //check for require entry set
    $wndnada = get_option( 'woonumday_options' )['woonumday_wndnada_field'];
    if( $wndnada != 1 ) {
       
        
      if( isset( $_POST['_woonumday_custom_option'] ) 
      && sanitize_text_field( $_POST['_woonumday_custom_option'] ) == '' ){
        $product = wc_get_product( $product_id );
        wc_add_notice( sprintf( __( 
        '%s cannot be added. You must add increment', 'woonumday' ), 
        $product->get_title() ), 'error' );
            
            return false;
      }
    } 
        return $passed;
 
}
add_filter( 'woocommerce_add_to_cart_validation', 'woonumday_add_to_cart_validation', 10, 3 );

/**
 * Add custom data to the cart item
 * @param array $cart_item
 * @param int $product_id
 * @return array
 */
function woonumday_add_cart_item_data( $cart_item, $product_id ){
 
    if( isset( $_POST['_woonumday_custom_option'] ) ) {
        $cart_item['woonumday_custom_option'] = sanitize_text_field( 
        $_POST['_woonumday_custom_option'] );
    }
 
    return $cart_item;
 
}
add_filter( 'woocommerce_add_cart_item_data', 'woonumday_add_cart_item_data', 10, 2 );

/**
 * Load cart data from session
 * @param  array $cart_item
 * @param  array $other_data
 * @return array
 */
function woonumday_get_cart_item_from_session( $cart_item, $values ) {
 
    if ( isset( $values['woonumday_custom_option'] ) ){
        $cart_item['woonumday_custom_option'] = 
        $values['woonumday_custom_option'];
    }
 
        return $cart_item;
}
add_filter( 'woocommerce_get_cart_item_from_session', 'woonumday_get_cart_item_from_session', 20, 2 );

/** 
 * calculate additional fees, add to item totals
 * set tax rate (universal between all wndfees)
 * TODO wc_numeric_decimals
 * @param array $cart_item_key
 * @param array $cart_item
 * @param string $subtotal
 
 */ 
add_action( 'woocommerce_before_calculate_totals', 'woonumday_update_lineitem_subtotal', 10, 3 );
 
function woonumday_update_lineitem_subtotal( $cart_item, $cart_item_key, $cart ) 
{
    global $woocommerce;
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;
        

    $cart_size = sizeof( WC()->cart->get_cart() );
    if ( $cart_size > 0 ) :  
        // option to allow prod price same as days fee
        $optqnty = get_option( 'woonumday_options' )['woonumday_wndmatch_field'] 
                 ? get_option( 'woonumday_options' )['woonumday_wndmatch_field'] : 0;
        $wndtaxx = get_option( 'woonumday_options' )['woonumday_wndtaxbase_field'] 
                 ? get_option( 'woonumday_options' )['woonumday_wndtaxbase_field'] : 'zero';
        
        //label in cart totals
        $wndttltext = get_option( 'woonumday_options' )['woonumday_cstitle_field'];
        $wnd_fee    = '';        // clean string
        $wnd_fee    = array();  // init array
        $found      = false;   // default
        foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) 
        { 
        /* Check for the wnd_fee Line Item in Woocommerce Cart */
        $_wnd_qnty = $cart_item['woonumday_custom_option'];
            if( '' != $_wnd_qnty && $_wnd_qnty > $optqnty ) 
            { $found = true; }

                if( $found == true)  
                { 

                $wnd_cost   = get_post_meta( $cart_item['product_id'], 
                                            '_woonumday_fee', true );
                $product_id = $cart_item['product_id'];
                $wnd_qnty   = $cart_item['woonumday_custom_option'];            
                $_fees      = $wnd_cost * $wnd_qnty;
                $wnd_fee[]  =  round($_fees, 2);             
                }  
                
        } //ends foreach loop
        
        $woocommerce->cart->add_fee( __($wndttltext, 'woonumday'), 
                                     array_sum($wnd_fee), 
                                     $wndtaxx 
                                    );
    endif; 
}

/**
 * Get item data to display in cart
 * @param  array $other_data
 * @param  array $cart_item
 * @return array
 */
function woonumday_get_item_data( $other_data, $cart_item ) {
$label = get_option('woonumday_options')['woonumday_csdescription_field']; 

    if ( isset( $cart_item['woonumday_custom_option'] ) )
    {
        
        $other_data[] = array(
            'name'  => esc_attr( $label ),
            'value' => sanitize_text_field( $cart_item['woonumday_custom_option'] )
        );
    }
        return $other_data;
}
add_filter( 'woocommerce_get_item_data', 'woonumday_get_item_data', 10, 2 );


/**
 * Add meta to order item
 * @param  int $item_id
 * @param  array $values
 * @return void
 */
function woonumday_add_order_item_meta( $item_id, $values ) {
 
    if ( ! empty( $values['woonumday_custom_option'] ) ) {
        woocommerce_add_order_item_meta( $item_id, 
        'woonumday_custom_option', $values['woonumday_custom_option'] );           
    }
}
add_action( 'woocommerce_add_order_item_meta', 'woonumday_add_order_item_meta', 10, 2 );

/** 
 * @_cart_item_price only displays, does not total
 * display ppd next to price
 * @param  string $title
 * @param  array $cart_item
 * @param  array $cart_item_key
 * @return array
 */ 
function woonumdays_render_priceperday_field( $title=null, $cart_item, $cart_item_key)
{
    $styls    = get_option('woonumday_options')['woonumday_wndinppd_field']; 
    $currency = get_woocommerce_currency_symbol();
    $ppday    = get_post_meta( $cart_item['product_id'], 
                               '_woonumday_fee', true 
                               );
    if( $cart_item_key && is_cart() ) 
    {
        echo $title. '<span class="variation wnddays" style="'. $styls .'"> 
        @/'. $currency .''. $ppday . '</span>';
        } else {
        echo $title;
    }
}
add_filter( 'woocommerce_cart_item_name', 'woonumdays_render_priceperday_field', 10, 3 );

/**
 * Show custom field in order overview
 * @param array $cart_item
 * @param array $order_item
 * @retur array
 */ 
function woonumday_order_item_product( $cart_item, $order_item ){
 
    if( isset( $order_item['woonumday_custom_option'] ) ){
        $cart_item_meta['woonumday_custom_option'] = 
        $order_item['woonumday_custom_option'];
    }
 
        return $cart_item;
}
add_filter( 'woocommerce_order_item_product', 'woonumday_order_item_product', 10, 2 );
 
/** 
 * Add the field to order emails 
 * @param array $keys 
 * @return array 
 */  
function woonumday_email_order_meta_fields( $fields ) { 
    $wndtext = get_option('woonumday_options')['woonumday_wndproduct_field'];
    $fields['woonumday_custom_field'] = esc_html( $wndtext ); 
    return $fields; 
} 
add_filter('woocommerce_email_order_meta_fields', 'woonumday_email_order_meta_fields'); 
