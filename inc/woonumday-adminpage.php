<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    woonumday
 * @subpackage /inc
 * @author     Larry Judd <tradesouthwest@gmail.com>
 * TODO add a field in the order table (admin side)
 */
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'woonumday_add_options_page' ); 
add_action( 'admin_init', 'woonumday_register_admin_options' ); 

//create an options page
function woonumday_add_options_page() 
{
   add_submenu_page(
       'options-general.php',
        esc_html__( 'Woonumday', 'woocommerce' ),
        esc_html__( 'WooNumDay', 'woocommerce' ),
        'manage_options',
        'woonumday',
        'woonumday_options_page',
        'dashicons-admin-tools' 
    );
}   
 
/** a.) Register new settings
 *  $option_group (page), $option_name, $sanitize_callback
 *  --------
 ** b.) Add sections
 *  $id, $title, $callback, $page
 *  --------
 ** c.) Add fields 
 *  $id, $title, $callback, $page, $section, $args = array() 
 *  --------
 ** d.) Options Form Rendering. action="options.php"
 *
 */

// a.) register all settings groups
function woonumday_register_admin_options() 
{
    //options pg
    register_setting( 'woonumday_options', 'woonumday_options' );
     

/**
 * b1.) options section
 */        
    add_settings_section(
        'woonumday_options_section',
        esc_html__( 'Configuration and Settings', 'woocommerce' ),
        'woonumday_options_section_cb',
        'woonumday_options'
    ); 
        // c.) settings 
    add_settings_field(
        'woonumday_cstitle_field',
        esc_attr__('Label for Checkout Field', 'woonumday'),
        'woonumday_cstitle_field_cb',
        'woonumday_options',
        'woonumday_options_section',
        array( 
            'type'         => 'text',
            'option_group' => 'woonumday_options', 
            'name'         => 'woonumday_cstitle_field',
            'value'        => 
            esc_attr( get_option( 'woonumday_options' )['woonumday_cstitle_field'] ),
            'description'  => esc_html__( 'Shows below the last field in checkout.', 'woonumday' )
        )
    );
    // c.) settings 
    add_settings_field(
        'woonumday_csdescription_field',
        esc_attr__('Order Page Text', 'woonumday'),
        'woonumday_csdescription_field_cb',
        'woonumday_options',
        'woonumday_options_section',
        array( 
            'type'         => 'text',
            'option_group' => 'woonumday_options', 
            'name'         => 'woonumday_csdescription_field',
            'value'        => 
            esc_attr( get_option( 'woonumday_options' )['woonumday_csdescription_field'] ),
            'description'  => esc_html__( 'Show below product name in cart.', 'woonumday' )
        )
    );
    // d.) settings 
    add_settings_field(
        'woonumday_wndproduct_field',
        esc_attr__('Display Increment', 'woonumday'),
        'woonumday_wndproduct_field_cb',
        'woonumday_options',
        'woonumday_options_section',
        array( 
            'type'         => 'text',
            'option_group' => 'woonumday_options', 
            'name'         => 'woonumday_wndproduct_field',
            'value'        => 
            esc_attr( get_option( 'woonumday_options' )['woonumday_wndproduct_field'] ),
            'description'  => esc_html__( 'Text to display on the product page', 'woonumday' ),
            'tip'  => esc_attr__( 'Text will show above the add-to-cart button to the left of the quantity field. Could be Days, Hours, Tours....', 'woonumday' )
        )
    );    
    // d.) settings 
    add_settings_field(
        'woonumday_wndinwidth_field',
        esc_attr__('Width or style of Input', 'woonumday'),
        'woonumday_wndinwidth_field_cb',
        'woonumday_options',
        'woonumday_options_section',
        array( 
            'type'         => 'text',
            'option_group' => 'woonumday_options', 
            'name'         => 'woonumday_wndinwidth_field',
            'value'        => 
            esc_attr( ( empty( get_option( 'woonumday_options' )['woonumday_wndinwidth_field'] ) ) )
            ? 'width:67px' : get_option( 'woonumday_options' )['woonumday_wndinwidth_field'],
            'description'  => esc_html__( 'Add inline styles. Default is width:67px', 'woonumday' ),
            'tip'  => esc_attr__( 'You my add a attribute like float right or margin-left etc. Just write like it is after style="". ', 'woonumday' )
        )
    );
    // d.) settings 
    add_settings_field(
        'woonumday_wndinppd_field',
        esc_attr__('Styles for price per day', 'woonumday'),
        'woonumday_wndinppd_field_cb',
        'woonumday_options',
        'woonumday_options_section',
        array( 
            'type'         => 'text',
            'option_group' => 'woonumday_options', 
            'name'         => 'woonumday_wndinppd_field',
            'value'        => 
            esc_attr( ( empty( get_option( 'woonumday_options' )['woonumday_wndinppd_field'] ) ) )
            ? 'font-size:90%;opacity:.9;position:relative;top:1em' : get_option( 'woonumday_options' )['woonumday_wndinppd_field'],
            'description'  => esc_html__( 'Add inline styles to @/$ next to per day in cart. class~.variation.wnddays', 'woonumday' ),
            'tip'  => esc_attr__( 'You my add a attribute like float right or margin-left etc. Just write like it is after style="". ', 'woonumday' )
        )
    );    
    // d.) settings 
    add_settings_field(
        'woonumday_wndtaxbase_field',
        esc_attr__('Tax Options', 'woonumday'),
        'woonumday_wndtaxbase_field_cb',
        'woonumday_options',
        'woonumday_options_section',
         array(
            'type' => 'select',
            'option_group' => 'woonumday_options', 
            'name'         => 'woonumday_wndtaxbase_field',
            'value'        => esc_attr( 
                              get_option( 'woonumday_options' )['woonumday_wndtaxbase_field'] ),
            'options'      => array(
                                  "standard" => "Standard", 
                                  "reduced" => "Reduced", 
                                  "zero" => "Zero" ),
            'description'  => esc_html__( 'This adjust the Additional Fee tax rate only 
                                           - not the product tax rate.', 'woonumday' ),
            'tip'  => esc_attr__( 'Choices are: standard | reduced | zero 
                                  See Woocommerce Settings to set taxes', 'woonumday' )
        )
    );
    // c.)1. settings checkbox 
    add_settings_field(
        'woonumday_wndnada_field',
        esc_attr__('Activate Zero Entry', 'woonumday'),
       'woonumday_wndnada_field_cb',
        'woonumday_options',
        'woonumday_options_section',
        array( 
            'type'        => 'checkbox',
            'option_name' => 'woonumday_options', 
            'name'        => 'woonumday_wndnada_field',
            'label_for'   => 'woonumday_wndnada_field',
            'value'       => 
                get_option('woonumday_options')['woonumday_wndnada_field'],
            'description' => esc_html__( 
                'Check to allow Add-To-Cart without selecting increments.', 'woonumday' ),
            'checked'     => esc_attr( checked( 1, 
                get_option('woonumday_options')['woonumday_wndnada_field'], 
                false ) ),
            'tip'         => esc_attr__( 'Checking will override the default of requiring customers to select a length of time/increment.', 'wordness' ) 
            )
    ); 
} 

/** 
 * name for 'branding' field
 * @since 1.0.0
 */
function woonumday_cstitle_field_cb($args)
{  
   printf(
        '<input type="%1$s" name="%2$s[%3$s]" id="%2$s-%3$s" 
        value="%4$s" class="regular-text" /><br>
        <span class="wndspan">%5$s <b class="wntip" title="tip"> ? </b></span>',
        $args['type'],
        $args['option_group'],
        $args['name'],
        $args['value'],
        $args['description']
    );
}

/** 
 * name for 'branding' field
 * @since 1.0.0
 */
function woonumday_csdescription_field_cb($args)
{  
   printf(
        '<input type="%1$s" name="%2$s[%3$s]" id="%2$s-%3$s" 
        value="%4$s" class="regular-text" /><br>
        <span class="wndspan">%5$s <b class="wntip" title="tip"> ? </b></span>',
        $args['type'],
        $args['option_group'],
        $args['name'],
        $args['value'],
        $args['description']
    );
}
/** 
 * name for 'branding' field
 * @since 1.0.0
 */
function woonumday_wndproduct_field_cb($args)
{  
   printf(
        '<input type="%1$s" name="%2$s[%3$s]" id="%2$s-%3$s" 
        value="%4$s" class="regular-text" /><br>
        <span class="wndspan">%5$s <b class="wntip" title="%6$s"> ? </b></span>',
        $args['type'],
        $args['option_group'],
        $args['name'],
        $args['value'],
        $args['description'],
        $args['tip']
    );
}
/** 
 * name for 'branding' field
 * @since 1.0.0
 */
function woonumday_wndinwidth_field_cb($args)
{  
   printf(
        '<input type="%1$s" name="%2$s[%3$s]" id="%2$s-%3$s" 
        value="%4$s" class="regular-text" /><br>
        <span class="wndspan">%5$s <b class="wntip" title="%6$s"> ? </b></span>',
        $args['type'],
        $args['option_group'],
        $args['name'],
        $args['value'],
        $args['description'],
        $args['tip']
    );
}
/** 
 * inline style field
 * @since 1.0.0
 */
function woonumday_wndinppd_field_cb($args)
{  
   printf(
        '<input type="%1$s" name="%2$s[%3$s]" id="%2$s-%3$s" 
        value="%4$s" class="regular-text" /><br>
        <span class="wndspan">%5$s <b class="wntip" title="%6$s"> ? </b></span>',
        $args['type'],
        $args['option_group'],
        $args['name'],
        $args['value'],
        $args['description'],
        $args['tip']
    );
}
/** 
 * 'select' field
 * @since 1.0.0
 */
function woonumday_wndtaxbase_field_cb($args)
{  
  print('<label for="woonumday_wndtaxbase_field">');
    if( ! empty ( $args['options'] && is_array( $args['options'] ) ) )
    { 
    $options_markup = '';
    $value = $args['value'];
        foreach( $args['options'] as $key => $label )
        {
            $options_markup .= sprintf( '<option value="%s" %s>%s</option>', 
            $key, selected( $value, $key, false ), $label );
        }
        printf( '<select name="%1$s[%2$s]" id="%1$s-%2$s">%3$s</select>',  
        $args['option_group'],
        $args['name'],
        $options_markup );
    }
        $tip = $args['tip'];
        print('<b class="wntip" title="' . esc_attr($tip) . '"> ? </b></label>'); 
}
/** 
 * switch for 'allow zero qnty' field
 * @since 1.0.1
 * @input type checkbox
 */
function woonumday_wndnada_field_cb($args)
{ 
     printf(
        '<label for="%2$s-%3$s">
        <input type="hidden" name="%2$s[%3$s]" value="0">
        <input type="%1$s" name="%2$s[%3$s]" id="%4$s" value="1"  
        class="regular-text" %7$s > %6$s </label>
        <b class="wntip" title="%8$s"> ? </b>',
        $args['type'],
        $args['option_name'],
        $args['name'],
        $args['label_for'],        
        $args['value'],
        $args['description'],
        $args['checked'],
        $args['tip']
    );    
}   

/**
 ** Section Callbacks
 *  $id, $title, $callback, $page
 */
// section heading cb
function woonumday_options_section_cb()
{    
print( '<hr>' );
} 


// d.) render admin page
function woonumday_options_page() 
{
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) return;
    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    ?>
    <div class="wrap wrap-woonumday-admin">
    
    <h1><span id="SlwOptions" class="dashicons dashicons-admin-tools"></span> 
    <?php echo esc_html( 'Woo Number of Days plugin Options' ); ?></h1>
         
    <form action="options.php" method="post">
    <?php //page=woonumday&tab=woonumday_options
        settings_fields(     'woonumday_options' );
        do_settings_sections( 'woonumday_options' ); 
        
        submit_button( 'Save Settings' ); 

    ?>
    </form>
    <?php ob_start();
    echo '
    <h3>'. esc_html__( 'Help and Information', 'woonumday' ) .'</h3>
    <div class="accordion"><h2> + </h2><div class="accordion-content">
    <dl>
    <dt>'. esc_html__( '1. Label for Checkout Field', 'woonumday' ) .'</dt>
    <dd>'. esc_html__( ' also appears on the customers order invoice and email.', 'woonumday' ) .'</dd>
    <dt>'. esc_html__( '2. Admin Order Text', 'woonumday' ) .'</dt>
    <dd>'. esc_html__( 'What the administrator text will be on the Orders page or Woocommerce Orders.', 'woonumday' ) .'</dd>
    <dt>'. esc_html__( '3. Display Increment', 'woonumday' ) .'</dt>
    <dd>'. esc_html__( 'Text to display on the product page. Text will show above the add-to-cart button to the left of the quantity field. Could be Days, Hours, Tours....', 'woonumday' ) .'</dd>
    <dt>'. esc_html__( '4. Style', 'woonumday' ) .'</dt>
    <dd>'. esc_html__( 'Styling should be done as if you were writing style properties between the inline style element.', 'woonumday' ) .'</dd>
    <dd>'. esc_html__( 'Leaving the field blank will also allow you to add your own styles externally. The selectors for this field are class .quantity.wnd wraps the label and the input, which is id wnd_quantity.', 'woonumday' ) .'</dd>
    <dt>'. esc_html__( '5. Tax Options', 'woonumday' ) .'</dt>
    <dd>'. esc_html__( 'This adjust the Additional Fee tax rate only - not the product tax rate.', 'woonumday' ) .'</dd>
    <dd>'. esc_html__( 'Choices are: standard | reduced | zero. See Woocommerce Settings to set taxes', 'woonumday' ) .'</dd>
    <dt>'. esc_html__( '6. Zero Entry', 'woonumday' ) .'</dt>
    <dd>'. esc_html__( 'Check box to allow customers to press the Add-To-Cart button WITHOUT requiring them to select a duration from the woonumday quantity field', 'woonumday' ) .'</dd>
    </dl>
    <p>&nbsp;</p>
    <span class="accordion-close"> - </span>
    </div></div><div class="clear"></div>';
    $htmls = ob_get_clean();
    echo $htmls;
    ?>
   
    <h4><?php esc_html_e( 'woonumday_options example serialized data', 'woonumday' ); ?></h4>
    <code>a:4:{s:24:"woonumday_cstitle_field";s:22:"Number of Days Needed?";s:30:"woonumday_csdescription_field";s:22:"Number of days needed ";s:24:"woonumday_csadded_field";s:50:"Additional Fee may apply for extended days of use.";s:25:"woonumday_csamount_field";s:1:"5";}</code>
    </div>
<?php 
} 
