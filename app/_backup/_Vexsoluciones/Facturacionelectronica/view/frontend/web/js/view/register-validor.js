define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Vexsoluciones_Facturacionelectronica/js/model/validator'
    ],
    function (Component, additionalValidators, facturacionValidor) {
        'use strict';
     
        additionalValidators.registerValidator(facturacionValidor);
     
        return Component.extend({});
    }
);