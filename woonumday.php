<?php
/**
 * Plugin Name:       WooNumDay
 * Plugin URI:        http://themes.tradesouthwest.com/wordpress/plugins/
 * Description:       Using WooCommerce, adds number of days to checkout. Opens under Settings > WooNumDays.
 * Version:           1.0.0
 * Author:            Larry Judd
 * Author URI:        http://tradesouthwest.com
 * License:           Apache License, Version 2.0
 * License URI:       http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain:       woocommerce
 * Domain Path:       woocommerce
 * @wordpress-plugin  wpdb =
 * @link              http://tradesouthwest.com
 * @package           WooNumDays
 *
 *
 * =========================================================
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */


// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//activate/deactivate hooks
function woonumday_plugin_activation() {

  // Check for WooCommerce
  if (!class_exists('WooCommerce')) {
	echo('<div class="error">
	<p>This plugin requires that WooCommerce is installed and activated.</p>
	</div></div>');
	return;
  }
  //register_uninstall_hook( __FILE__, 'woonumday_uninstall' );
}

function woonumday_plugin_deactivation() {
    //woonumdays_deregister_shortcode() 
        return false;
}

//https://codex.wordpress.org/Shortcode_API
function woonumday_deregister_shortcode() {

    //remove_shortcode( 'woonumdays_view_orders' );
        return false;
}

//activate and deactivate registered
register_activation_hook( __FILE__, 'woonumday_plugin_activation');
register_deactivation_hook( __FILE__, 'woonumday_plugin_deactivation');

//enqueue script
function woonumday_addtosite_scripts() {
    // Register Scripts
    wp_register_script( 'woonumday-plugin', 
       plugins_url( 'lib/woonumday-plugin.js', __FILE__ ), 
       array( 'jquery' ), true );
    
    wp_enqueue_script( 'woonumbday-plugin' );
   
}
//add_action( 'wp_enqueue_scripts', 'woonumday_addtosite_scripts' );
//load admin scripts as well
//add_action( 'admin_init', 'woonumdays_addtosite_scripts' );

//include admin and public views
require ( plugin_dir_path( __FILE__ ) . 'inc/woonumday-adminpage.php' ); 
require ( plugin_dir_path( __FILE__ ) . 'inc/woonumday-addtocart.php' ); 

?>
