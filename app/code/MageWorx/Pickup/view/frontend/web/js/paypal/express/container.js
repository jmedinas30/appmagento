/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
define(
    [
        'ko',
        'jquery',
        'Magento_Ui/js/modal/modal',
        'uiComponent',
        'uiRegistry',
        'jquery/ui',
        'jquery/jquery.cookie'
    ],
    function (ko, $, modal, Component, registry) {
        "use strict";
        var isLoading = ko.observable(false);

        return Component.extend({
            defaults: {
                template: 'MageWorx_Pickup/checkout/container',
                isVisible: false
            },

            locationId: ko.observable(false),

            isLocationSelected: function() {
                return registry.get('mageworx_location_id');
            },

            isPickupSelected: function() {
                return true;
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
                var locationBlock = jQuery('.location-info-block_' + this.locationId() + ' .mw-sl__stores__list__item__inner');
                if (!locationBlock.html()) {
                    locationBlock = jQuery('.location-info-block_' + this.locationId() + ' .mw-sl__store__info');
                }

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
                $('#locationPopup').modal('openModal');
            },
        });
    }
);
