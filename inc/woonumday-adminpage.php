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
        esc_html__( 'Text Boxes', 'woocommerce' ),
        'woonumday_options_section_cb',
        'woonumday_options'
    ); 
        // c.) settings 
    add_settings_field(
        'woonumday_cstitle_field',
        esc_attr__('Label for Checkout Field', 'woocommerce'),
        'woonumday_cstitle_field_cb',
        'woonumday_options',
        'woonumday_options_section',
        array( 
            'type'         => 'text',
            'option_group' => 'woonumday_options', 
            'name'         => 'woonumday_cstitle_field',
            'value'        => 
            esc_attr( get_option( 'woonumday_options' )['woonumday_cstitle_field'] ),
            'description'  => esc_html__( 'Shows below the last field in checkout.', 'woocommerce' )
        )
    );
    // c.) settings 
    add_settings_field(
        'woonumday_csdescription_field',
        esc_attr__('Admin Order Page Text', 'woocommerce'),
        'woonumday_csdescription_field_cb',
        'woonumday_options',
        'woonumday_options_section',
        array( 
            'type'         => 'text',
            'option_group' => 'woonumday_options', 
            'name'         => 'woonumday_csdescription_field',
            'value'        => 
            esc_attr( get_option( 'woonumday_options' )['woonumday_csdescription_field'] ),
            'description'  => esc_html__( 'Show just after last field.', 'woocommerce' )
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
        value="%4$s" class="regular-text" />
        <span>%5$s <b class="wntip" title="tip"> ? </b></span>',
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
        value="%4$s" class="regular-text" />
        <span>%5$s <b class="wntip" title="tip"> ? </b></span>',
        $args['type'],
        $args['option_group'],
        $args['name'],
        $args['value'],
        $args['description']
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
    if ( isset( $_GET['settings-updated'] ) ) {
    // add settings saved message with the class of "updated"
    add_settings_error( 'woonumday_messages', 'woonumday_message', 
                        esc_html__( 'Settings Saved', 'woocommerce' ), 'updated' );
    }
    // show error/update messages
    settings_errors( 'woonumday_messages' );
     
    ?>
    <div class="wrap wrap-woonumday-admin">
    
    <h1><span id="SlwOptions" class="dashicons dashicons-admin-tools"></span> 
    <?php echo esc_html( 'Admin' ); ?></h1>
         
    <form action="options.php" method="post">
    <?php //page=woonumday&tab=woonumday_options
        settings_fields(     'woonumday_options' );
        do_settings_sections( 'woonumday_options' ); 
        
        submit_button( 'Save Settings' ); 
 
    ?>
    </form>
    <h4>Instructions woonumday_options example serialized data</h4> 
<code>    a:4:{s:24:"woonumday_cstitle_field";s:22:"Number of Days Needed?";s:30:"woonumday_csdescription_field";s:22:"Number of days needed ";s:24:"woonumday_csadded_field";s:50:"Additional Fee may apply for extended days of use.";s:25:"woonumday_csamount_field";s:1:"5";} </code>
    </div>
<?php 
} 
