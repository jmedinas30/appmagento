define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'pagoefectivo_pay',
                component: 'Vexsoluciones_Pagoefectivo/js/view/payment/method-renderer/pagoefectivo-method'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);