/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'ko',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/resource-url-manager',
    'mage/storage',
    'Magento_Checkout/js/model/payment-service',
    'Magento_Checkout/js/model/payment/method-converter',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Checkout/js/model/full-screen-loader',
    'Magento_Checkout/js/action/select-billing-address',
    'Magento_Checkout/js/model/shipping-save-processor/payload-extender'
], function (
    ko,
    quote,
    resourceUrlManager,
    storage,
    paymentService,
    methodConverter,
    errorProcessor,
    fullScreenLoader,
    selectBillingAddressAction,
    payloadExtender
) {
    'use strict';

    return {
        /**
         * @return {jQuery.Deferred}
         */
        saveShippingInformation: function () {
            var payload;

            if (!quote.billingAddress() && quote.shippingAddress().canUseForBilling()) {
                selectBillingAddressAction(quote.shippingAddress());
            }

            var coordenadas = '';
            var dia = '';
            var hora = '';
            if(jQuery("#coordenadas-mapa").length > 0) {
                coordenadas = jQuery("#coordenadas-mapa").val();
            }
            if(jQuery("#select-dia-delivery").length > 0) {
                dia = jQuery("#select-dia-delivery").val();
            }
            if(jQuery("#select-hora-delivery").length > 0) {
                hora = jQuery("#select-hora-delivery").val();
            }

            payload = {
                addressInformation: {
                    'shipping_address': quote.shippingAddress(),
                    'billing_address': quote.billingAddress(),
                    'shipping_method_code': quote.shippingMethod()['method_code'],
                    'shipping_carrier_code': quote.shippingMethod()['carrier_code'],
                    'extension_attributes': {
                        vexcoordenadas : coordenadas,
                        vexdiaprogramado : dia,
                        vexhoraprogramado : hora
                    }
                }
            };

            payloadExtender(payload);

            fullScreenLoader.startLoader();

            return storage.post(
                resourceUrlManager.getUrlForSetShippingInformation(quote),
                JSON.stringify(payload)
            ).done(
                function (response) {
                    quote.setTotals(response.totals);
                    paymentService.setPaymentMethods(methodConverter(response['payment_methods']));
                    fullScreenLoader.stopLoader();
                }
            ).fail(
                function (response) {
                    errorProcessor.process(response);
                    fullScreenLoader.stopLoader();
                }
            );
        }
    };
});
