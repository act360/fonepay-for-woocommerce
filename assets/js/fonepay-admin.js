jQuery( function( $ ) {
	'use strict';

	/**
	 * Object to handle fonepay admin functions.
	 */
	var wc_fonepay_admin = {
		isTestMode: function() {
			return $( '#woocommerce_fonepay_testmode' ).is( ':checked' );
		},

		/**
		 * Initialize.
		 */
		init: function() {
			$( document.body ).on( 'change', '#woocommerce_fonepay_testmode', function() {
				var test_merchant_code = $( '#woocommerce_fonepay_sandbox_merchant_code' ).parents( 'tr' ).eq( 0 ),
					test_merchant_secret = $( '#woocommerce_fonepay_sandbox_merchant_secret' ).parents( 'tr' ).eq( 0 ),
					live_merchant_code = $( '#woocommerce_fonepay_merchant_code' ).parents( 'tr' ).eq( 0 ),
					live_merchant_secret = $( '#woocommerce_fonepay_merchant_secret' ).parents( 'tr' ).eq( 0 );

				if ( $( this ).is( ':checked' ) ) {
					live_merchant_code.hide();
					live_merchant_secret.hide();
					test_merchant_code.show();
					test_merchant_secret.show();
				} else {
					test_merchant_code.hide();
					test_merchant_secret.hide();
					live_merchant_code.show();
					live_merchant_secret.show();
				}
			} );

			$( '#woocommerce_fonepay_testmode' ).change();
		}
	};

	wc_fonepay_admin.init();
});
