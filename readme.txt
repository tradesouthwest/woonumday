=== WooNumDays ===
Contributors:  tradesouthwest
Donate link: https://paypal.me/tradesouthwest
Tags: woocommerce, woobookings
Requires at least: 3.8
Tested up to: 4.9
Requies Woocommerce plugin
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Plugin URI: http://themes.tradesouthwest.com/plugins/

Modify WooBookings to order product by number of days and not by dates.
== Description ==

Modify WooBookings to order product by number of days and not by dates. Adds Additional Fee price to the cart.

== Installation ==

This section describes how to install the plugin and get it working.
1. Upload `woonumdays.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. If user is Editor or above, they will see a Selection button in the Editor

== Frequently Asked Questions ==

Q.: Can I change the background colors of individual boxes?
A.: There is a chart in your admin page with the names of all the selectors. If you know CSS then you can use any CSS editor to add
your background colors to.

== Screenshots ==
 
== Upgrade Notice ==
ou can create a product object using the following function:

$product = wc_get_product( $post_id );

And after that you will be able to access to all product's data. All available methods can be found here, but the ones you need are:

$product->get_regular_price();
$product->get_sale_price();
$product->get_price();


n/a

== Changelog ==

= 1.0.0 =
* June 2018

== Notes ==


Have a wonderful time!
