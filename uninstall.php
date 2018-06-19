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
	'woonumday_cstitle_field',
	'woonumday_csdescription_field',
	'woonumday_wndinwidth_field',
	'woonumday_wndtaxbase_field'
);
foreach( $options as $option ) {
	delete_option( $option );
}
