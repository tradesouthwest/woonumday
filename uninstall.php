<?php
/**
 * Delete our options when this plugin is deleted
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
global $wpdb;
// Delete options.
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'woonumday\_%';" );
$options = array(
	'woonumday_cstitle_field' /*
	'wc_show_weight_dimensions',
	'wc_show_weight_dimensions',
	'woocommerce_show_attributes_span',
	'woocommerce_show_attributes_hide_labels',
	'woocommerce_show_attributes_on_shop' */
);
foreach( $options as $option ) {
	delete_option( $option );
}
