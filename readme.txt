=== Integrate Fonepay in WooCommerce ===
Contributors: act360, prajwolshrestha
Tags: woocommerce, Fonepay, payment gateway
Requires at least: 5.0
Tested up to: 5.8.2
Stable tag: trunk
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Adds Fonepay as payment gateway in WooCommerce plugin.

== Description ==

= Add Fonepay gateway to WooCommerce =

This plugin adds Fonepay gateway to WooCommerce.

Please note that [WooCommerce](https://wordpress.org/plugins/woocommerce/) must be installed and active.

= Introduction =

Add Fonepay as a payment method in your WooCommerce store.

[Fonepay](https://fonepay.com/) is a Nepali Digital Payment Portal developed by fonepay. This means that if your store doesn't accept payment in NPR, you do not need this plugin.

The plugin Integrate Fonepay in WooCommerce was developed without any incentive from F1Soft Company. None of the developers of this plugin have any ties to F1Soft whatsoever.


= Installation =

Check out our installation guide and configuration of Integrate Fonepay in WooCommerce tab [Installation](https://wordpress.org/plugins/integrate-fonepay-in-woocommerce/#installation).


= Questions? =

You can answer your questions using:

* Our [FAQ](https://wordpress.org/plugins/integrate-fonepay-in-woocommerce/faq/) section.
* Creating a topic in the [WordPress support forum](https://wordpress.org/support/plugin/integrate-fonepay-in-woocommerce) (English only).

= Contribute =

You can contribute to the source code in our [GitHub](https://github.com/act360/fonepay-for-woocommerce) page.

== Installation ==

= Minimum Requirements =

* WordPress 5.0 or greater.
* WooCommerce 3.6 or greater.

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of Integrate Fonepay in WooCommerce, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "Integrate Fonepay in WooCommerce" and click Search Plugins. Once you’ve found our payment gateway plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =

The manual installation method involves downloading our Integrate Fonepay in WooCommerce plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).


= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.


== Frequently Asked Questions ==

= What is the plugin license? =

* This plugin is released under a GPL license.

= What is needed to use this plugin? =

* WordPress 5.0 or later.
* WooCommerce 3.6 or later.
* Merchant Code and Secret from Fonepay.

= Which countries does Fonepay accepts payment from? =

At the moment the Fonepay accepts payments only from Nepal.

Configure the plugin to receive payments only users who select Nepal in payment information during checkout.

= I don't see Fonepay payment option during checkout? =

You forgot to select the Nepal during registration at checkout. The Fonepay payment option works only with Nepal.

= The request was paid and got the status of "processing" and not as "complete", that is right? =

Yes, this is absolutely right and means that the plugin is working as it should.

All payment gateways in WooCommerce must change the order status to "processing" when the payment is confirmed and should never be changed alone to "complete" because the request should go only to the status "finished" after it has been delivered.

For downloadable products to WooCommerce default setting is to allow access only when the request has the status "completed", however in WooCommerce settings tab Products you can enable the option "Grant access to download the product after payment" and thus release download when the order status is as "processing."

= I have set correct Merchant Code & Secret but still get "Fonepay Verification Failure" error. =
From plugin version 1.1 onwards, there has been change in how the transaction is validated and verified. If you continue to get this error, please contact [Fonepay support](https://fonepay.com/contact-us).

= Where can I report bugs or contribute to the project? =

Bugs can be reported either in our support forum or preferably on the [WooCommerce Fonepay GitHub repository](https://github.com/act360/fonepay-for-woocommerce/issues). You can directly reach our developers at developers@act360.com.np

= Can I contribute to this plugin? =

Of course! Join in on our [GitHub repository](https://github.com/act360/fonepay-for-woocommerce)


== Credits ==
[eSewa plugin](https://wordpress.org/plugins/woocommerce-esewa/) and PayPal plugin in WooCommerce core


== Screenshots ==

1. Settings page.
2. Checkout page.

== Changelog ==

= 1.0.0 - 04-09-2020 =
* First release.

= 1.0.1 - 21-10-2020 =
* Fixed: Duplicate PRN issue by generating new order key for every payment initiation request
* Fixed: Data validation fail due hash mismatch caused by special characters in remarks

= 1.0.2 - 25-01-2021 =
* Added: Disabled directory listing of the plugin folder

= 1.1.0 - 06-12-2021 =
* Removed: Transaction verification via API call to Fonepay
* Added: Transaction verification using Data Validation Hash that is received after Fonepay redirects back to merchant website

= 1.1.1 - 18-01-2022 =
* Fixed - Argument missing error when creating note for payment failed case
