define(
    [
        'Magento_Payment/js/view/payment/cc-form',
        'jquery',
        'Magento_Checkout/js/model/quote',
        'Magento_Payment/js/model/credit-card-validation/validator'
    ],
    function (Component, $, quote) {
        'use strict';

        var times = 1;



        return Component.extend({
            defaults: {
                template: 'Vexsoluciones_Pagoefectivo/payment/cuotealo-form'
            },

            getCode: function() {
                return 'cuotealo_pay';
            },

            isActive: function() {
                return true;
            },

            validate: function() {
                var $form = $('#' + this.getCode() + '-form');
                return $form.validation() && $form.validation('isValid');
            },

            getData: function() {
                return {
                    'method': this.item.method,
                    'additional_data': {
                        "tipo_documento":jQuery("#tipo_cuotealo").val(),
                        "documento":jQuery("#documento_cuotealo").val()
                    }
                };
            },

            preparePayment: function()
            {
                return true;
            },

            getEmail: function () {
                if(quote.guestEmail) {
                    return quote.guestEmail;
                } else {
                    return window.checkoutConfig.customerData.email;
                }
            },

            retryPlaceOrder: function()
            {
                this.isPlaceOrderActionAllowed(true);
            }
        });
    }
);
