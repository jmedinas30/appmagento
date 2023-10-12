define([
    'uiRegistry',
    'mage/translate',
    'Magento_Checkout/js/model/quote'
], function (registry, $t, quote) {
    'use strict';

    var mixin = {

        /**
         *
         * @returns {*}
         */
        validateShippingInformation: function () {

            var result = this._super();

            if (result) {
                var locationId = registry.get('mageworx_location_id');

                if (quote.shippingMethod().method_code == 'mageworxpickup') {
                    if (!locationId() ) {
                        this.errorValidationMessage(
                            $t('The store for pickup is missing. Select the store and try again.')
                        );
                        result = false;
                    }
                    else {
                        if (!availableLocations.includes(locationId())) {
                            this.errorValidationMessage(
                                $t('Selected store isn\'t available. Select the other store and try again.')
                            );
                            result = false;
                        }
                    }
                }
            }


            return result;
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
