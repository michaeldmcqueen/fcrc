=== Plugin Name ===
Contributors: bycrik
Tags: woocommerce, tiered pricing, dynamic price, price, wholesale
Requires at least: 4.2
Tested up to: 5.3
Requires PHP: 5.6
Stable tag: 2.3.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Allows you to set price for a certain quantity of product. Shows quantity pricing table. Supports displaying table in a tooltip.

== Description ==

Simple WooCommerce plugin, which allows you to set different prices for different quantities of a product.

Wholesale for WooCommerce.

Features:

*   Set a certain price for certain quantity of product
*   Set a certain price for certain quantity of variation
*   Display pricing table at the product page (supports different places)
*   Display table in tooltip near product price (or variation price)
*   Customization

Premium features:

*   Set percentage discount
*   Display percentage table
*   Show lowest or range of price at the catalog
*   Show tier price in the cart as a discount
*   Show total price
*   Clickable rows
*   Summary table
*   Minimum table quantity

== Screenshots ==

1. Price table below the buy button
2. Create price rules
3. Settings
4. Display in tooltip
5. Price table below product summary
6. Create price rules for variation
7. Advanced settings
8. Discount in price
9. Price at the catalog
10. Percentage table
11. Summary table

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/dynamic-price-table` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the WooCommerce->Settings Name screen to configure the plugin

After installing plugin set up your own settings

== Frequently Asked Questions ==

= What is the import format =

"quantity:price,quantity:price"
For example:
"10:20,20:18"

== Changelog ==

= 1.1 =
* Fix bug with comma as thousand separators.
* Minor updates

= 2.0 =
* Fix bugs
* JS updating prices at product page
* Tooltip border
* Premium version

= 2.0.2 =
* Fix JS calculation prices
* Remove table from variation tier tables

= 2.1 =
* Support WooCommerce Taxes
* Do not show table head if column texts are blank
* Fix Updater
* Fix little issues

= 2.1.1 =
* Fixes
* Premium variable catalog prices

= 2.1.2 =
* Fixes
* Trial mode

= 2.2.0 =
* Added Import\Export tiered pricing
* Clickable quantity rows (Premium)
* Fix with some themes
* Fix mini-cart issue

= 2.2.1 =
* Fixed bugs
* Added total price feature

= 2.2.3 =
* Fixed bugs
* Added hooks

= 2.3.0 =
* Fix critical bug

= 2.3.1 =
* Fix jQuery issue

= 2.3.2 =
* Fix upgrading

= 2.3.3 =
* Fix taxes issue
* Added ability to calculate tiered price based on all variations
* Added ability to set bulk rules for variable product
* Added support minimum quantity in PREMIUM version
* Added summary table in PREMIUM version
* minor fixes
* Fixes for the popular themes

= 2.3.4 =
* Fix ajax issues
* Fix assets issues