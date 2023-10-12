/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
define(
    [
        'ko',
        'Magento_Checkout/js/model/quote',
        'jquery',
        'Magento_Ui/js/modal/modal',
        'uiComponent',
        'uiRegistry',
        'jquery/ui',
        'jquery/jquery.cookie'
    ],
    function (ko, quote, $, modal, Component, registry) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'MageWorx_Pickup/checkout/container',
                isVisible: true
            },

            locationId: ko.observable(false),

            isLocationSelected: function() {
                return registry.get('mageworx_location_id');
            },

            isPickupSelected: function() {
                if (quote.shippingMethod()) {
                    return quote.shippingMethod().method_code == 'mageworxpickup';
                }

                return false;
            },

            /**
             * Invokes initialize method of parent class,
             * contains initialization logic
             */
            initialize: function () {
                this._super();
                var idFromCookie = $.cookie('mageworx_location_id');
                if (idFromCookie) {
                    this.locationId(idFromCookie);
                }
                registry.set('pickupContainer', this);
                registry.set('mageworx_location_id', this.locationId);

                this.initModal();

                return this;
            },

            /** @inheritdoc */
            initObservable: function () {
                this._super()
                    .observe('isVisible');

                return this;
            },

            initModal: function() {
                $('#locationPopup').modal({
                    type: 'slide',
                    modalClass: 'mageworx-modal-location',
                    buttons: [],
                    opened: function(){
                        $('#mw-store-locator-locations').css('display', 'block');
                    }
                });
            },

            getStoreData: function() {
                var locationBlock = $('.location-info-block_' + this.locationId()).closest('li');

                if (locationBlock) {
                    if (typeof locationBlock.html() == 'undefined') {
                        this.locationId(null);
                        return '';
                    } else {
                        return locationBlock.html();
                    }
                }

                return '';
            },

            openModal: function () {
                $('#locationPopup').trigger('map_initialize');
                $('#locationPopup').modal('openModal');
            },
        });
    }
);
