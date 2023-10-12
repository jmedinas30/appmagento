define([
        "jquery", "uiRegistry"
    ], function ($, registry) {
        var LocationsModal = {
            data: 0,

            initModal: function (config, elemen) {
                var target = $(config.target);
                var url = config.dataUrl + 'store_locator/location/updatepopupcontent';
                var isShowMap = config.isShowMap;
                var googleMapsApiLibraryUrl = config.googleMapsApiLibraryUrl;
                var self = this;

                target.modal({
                    type: 'slide',
                    modalClass: 'mageworx-modal-location',
                    buttons: [],
                    opened: function () {
                        $('#locationPopup').css('display', 'block');
                    },
                    closed: function () {
                        $('#locationPopup').trigger('findAStoreUpdate');
                    }
                });
                var element = $(elemen);

                element.click(function () {
                    var addressId = $(config.addressId);
                    if (addressId.selector) {
                        $('.multi-locations').css('display', 'none');
                        $('#shipping_method-' + addressId.selector).css('display', 'block');
                    }
                    var dataFromRegistry = registry.get('store_locator_product_data');

                    //update popup content
                    if (dataFromRegistry && !arrayEquals(dataFromRegistry,self.data)) {
                        self.data = registry.get('store_locator_product_data');

                        $.ajax({
                            url: url,
                            type: 'POST',
                            isAjax: true,
                            dataType: 'html',
                            showLoader: true,
                            data: self.data,
                            success: function (xhr, status, errorThrown) {
                                $('#locationPopup').html(xhr);
                                target.modal('openModal');

                                if (isShowMap) {
                                    if (typeof google == 'undefined' || typeof google.maps == 'undefined') {
                                        loadGoogleMapsApiLibrary(googleMapsApiLibraryUrl, function() {
                                            $('#locationPopup').trigger('map_initialize');
                                        });
                                    } else {
                                        $('#locationPopup').trigger('map_initialize');
                                    }
                                }
                            },
                            error: function (xhr, status, errorThrown) {
                                console.log('There was an error loading stores popup.');
                                console.log(errorThrown);
                            }
                        });
                    } else {
                        target.modal('openModal');
                    }
                });


                function arrayEquals(a, b) {
                    return Array.isArray(a) &&
                        Array.isArray(b) &&
                        a.length === b.length &&
                        a.every((val, index) => val === b[index]);
                }

                function loadGoogleMapsApiLibrary(url, callback) {
                    $.ajax({
                        url: url,
                        dataType: 'script',
                        async: true,
                        success: callback,
                        error: function (xhr, status, errorThrown) {
                            console.log('There was an error loading Google Maps API library.');
                            console.log(errorThrown);
                        }
                    });
                }
            },
        };

        return {
            'locations-modal': LocationsModal.initModal
        };
    }
);
