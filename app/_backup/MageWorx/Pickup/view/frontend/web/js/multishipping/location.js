/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
require([
        'jquery',
        'uiRegistry',
        'jquery/ui',
        'jquery/jquery.cookie'
    ],
    function ($, registry) {
        $(document).ready(function () {
            var addressIds = [];
            $('input').each(function() {
                if (this.name.includes('shipping_method')) {
                    var name = this.name.replace('[', '-');
                    name = name.replace(']', '');
                    addressIds.push(name);

                    if (this.value  == 'mageworxpickup_mageworxpickup' && $(this).is(':checked')) {
                        $('.' + name).show();
                    }
                }
            });

            addressIds.forEach(function (e) {
                var locationId = $.cookie(e);
                registry.set(e, locationId);

                if (locationId) {
                    $('.' + e +' .mw-sl__store__info').html(getStoreData(locationId));
                }
            });

            function getStoreData(locationId) {
                var locationBlock = $('.location-info-block_' + locationId + ' .mw-sl__stores__list__item__inner');
                if (!locationBlock.html()) {
                    locationBlock = $('.location-info-block_' + locationId + ' .mw-sl__store__info');
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
            }

            $(".item-content input:radio").click(function(e) {
                var name = e.toElement.name.replace('[', '-');
                name = name.replace(']', '');

                if (e.toElement.value == 'mageworxpickup_mageworxpickup') {
                    $('.' + name).show();
                } else {
                    $('.' + name).hide();
                }
            });
        })
    }
);
