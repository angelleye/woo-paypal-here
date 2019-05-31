=== PayPal Here for WooCommerce ===
Contributors: angelleye
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QGCKWPXUEKRPW
Tags: woocommerce, paypal, paypal here, credit card, swipe, pos, point of sale
Requires at least: 3.0.1
Tested up to: 5.2.1
Stable tag: 0.5.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Bring WooCommerce and PayPal Here together!

== Description ==

= Introduction =

Process WooCommerce order payments using the PayPal Here app.

 * Process pending WooCommerce orders from PayPal Here.
 * Create new orders using WooCommerce data from mobile device and process with PayPal Here.

= BETA Release =
This plugin is currently being released in BETA in order to gather feedback and continue to make improvements for a 1.0 final release.  The current functionality has been thoroughly tested, but we will be making design and general user experience improvements for the final release.  We welcome any feedback you can offer to make this the perfect PayPal Here for WooCommerce solution!

= Quality Control =
Payment processing can't go wrong.  It's as simple as that.  Our certified PayPal engineers have developed and thoroughly tested this plugin on the PayPal sandbox (test) servers to ensure your customers don't have problems paying you.

= Seamless PayPal Here Integration =
All WooCommerce data is available within the web-based PayPal Here provided by the plugin.  Orders can be created fresh and processed from within the PayPal Here app, or pending WooCommerce orders can be loaded and processed in the PayPal Here app.

= Get Involved =
Developers can contribute to the source code on the [PayPal Here for WooCommerce GitHub repository](https://github.com/angelleye/woo-paypal-here).

== Installation ==

= Minimum Requirements =

* WooCommerce 3.0 or higher

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your web browser. To do an automatic install of PayPal Here for WooCommerce, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type PayPal Here for WooCommerce and click Search Plugins. Once you've found our plugin you can view details about it such as the the rating and description. Most importantly, of course, you can install it by simply clicking Install Now.

= Manual Installation =

1. Unzip the files and upload the folder into your plugins folder (/wp-content/plugins/) overwriting older versions if they exist
2. Activate the plugin in your WordPress admin area.

= Usage =

1. Open the settings page for WooCommerce and click the "Checkout" tab
2. Click on the sub-item for PayPal Here.
3. Click to generate the WooCommerce API keys, and configure settings accordingly.
4. Load the web-based app by scanning the QR code from the PayPal Here settings page or an individual order.
5. Click "Send to PayPal Here" to process the order payment using the PayPal Here app.

= Updating =

Automatic updates should work great for you.  As always, though, we recommend backing up your site prior to making any updates just to be sure nothing goes wrong.

== Screenshots ==

1. Dashboard for the PayPal Here for WooCommerce web app.
2. WooCommerce pending orders list displayed in the web app.
3. Order screen for the web app with total prepared, and button to Send to PayPal Here.
4. Order details sent to the PayPal Here app with credit card reader available as well as additional payment methods.
5. PayPal Here successful payment notification and receipt option screen.
6. Dashboard for the PayPal Here for WooCommerce web app with the successful payment message displayed.
7. WooCommerce product list displayed in web app for adding items to an order.
8. Adjust quantity and select attributes from WooCommerce when adding an item to an order using the web app.
9. Adjust shipping on an order using WooCommerce rules, percentage based, or flat rate options.
10. Add a coupon code using codes from WooCommerce or percentage / flat-rate options.
11. PayPal Here for WooCommerce Settings.
12. WooCommerce order screen with PayPal Here QR code ready to scan, which loads the order in the web app for processing with PayPal Here.
13. WooCommerce order payment details after payment is processed via PayPal Here.

== Frequently Asked Questions ==

= How does this thing work? =

The plugin creates a web-based app (ie. www.domain.com/paypal-here) where you can view WooCommerce pending orders or create new orders using WooCommerce product data.  When the order is ready you push "Send to PayPal Here".  The PayPal Here app will load on your device with the order data populated and ready for payment.

From here you can process it using credit card, PayPal invoice, cash, etc. just like you would any other PayPal Here transaction.  The WooCommerce order status will udpate accordingly when the payment is processed with the PayPal Here app.

== Changelog ==

= 0.5.1 - 05.31.2019 =
* Tweak - Updates WordPress and WooCommerce version compatibility.

= 0.5.0 - 05.31.2019 =
* Feature - Adds AE notification system. ([PHWOO-54](https://github.com/angelleye/woo-paypal-here/pull/73))

= 0.4.0 - 04.04.2019 =
* Feature - Adds AE Updater compatibility for future notices and automated updates. ([PHWOO-53](https://github.com/angelleye/woo-paypal-here/pull/72))

= 0.3.0 - 09.14.2018 =
* Tweak - Adds shipping by default when user is logged in. ([PHFW-1](https://github.com/angelleye/woo-paypal-here/pull/69))
* Fix - Resolves a problem with scanning order QR code from mobile device. ([PHFW-14](https://github.com/angelleye/woo-paypal-here/pull/70))


= 0.2.0 - 06.20.2018 =
* Tweak - Data sanitization adjustments to ensure GDPR compliance. ([PHFW-4](https://github.com/angelleye/woo-paypal-here/pull/67))
* Fix - Removes redundant success message on web app dashboard after completed payment. ([PHFW-6](https://github.com/angelleye/woo-paypal-here/pull/64))

= 0.1.0 =
Initial BETA release.