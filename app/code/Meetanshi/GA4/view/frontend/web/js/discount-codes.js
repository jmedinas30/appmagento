define([
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/full-screen-loader',
    'jquery-ui-modules/widget'
], function ($, quote, fullScreenLoader) {
    'use strict';

    $.widget('mage.discountCode', {
        options: {
        },

        /** @inheritdoc */
        _create: function () {
            this.couponCode = $(this.options.couponCodeSelector);
            this.removeCoupon = $(this.options.removeCouponSelector);

            $(this.options.applyButton).on('click', $.proxy(function () {
                this.couponCode.attr('data-validate', '{required:true}');
                this.removeCoupon.attr('value', '0');
                $(this.element).validation().submit();

                var quoteId = quote.getQuoteId(),
                    enabledModule = window.checkoutConfig.ga4.enabledModule,
                    couponUrl = window.checkoutConfig.ga4.couponUrl;

                if (enabledModule) {
                    fullScreenLoader.startLoader();
                    $.ajax({
                        url: couponUrl,
                        type: 'GET',
                        data: quoteId,
                        success: function (response) {
                            fullScreenLoader.stopLoader();
                            jQuery('#coupon-ga4-m').remove();
                            jQuery('body').append('<script id="coupon-ga4-m">'+response.data+'</script>');
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            fullScreenLoader.stopLoader();
                            self.addError(thrownError);
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                }

            }, this));

            $(this.options.cancelButton).on('click', $.proxy(function () {
                this.couponCode.removeAttr('data-validate');
                this.removeCoupon.attr('value', '1');
                this.element.submit();

                jQuery('#coupon-ga4-m').remove();
            }, this));
        }
    });

    return $.mage.discountCode;
});
