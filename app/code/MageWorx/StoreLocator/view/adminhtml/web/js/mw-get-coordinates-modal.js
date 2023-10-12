define([
        "jquery", "Magento_Ui/js/modal/modal", 'mage/translate'
    ], function($) {
        var MwGetCoordinatesModal = {
            initModal: function(config, elemen) {
                var target = $(config.target);
                target.modal({
                    type: 'slide',
                    modalClass: 'mw-get-center-coordinates-modal',
                    title: $.mage.__('Select location from the map'),

                    opened: function(){
                        $("#mwGetCoordinatesModal").trigger('open_mw_map');
                        $("#mw_locator_city").val($("#mageworx_locations_general_default_city").val());
                        if ($("#mageworx_locations_general_default_lat").val()) {
                            $("#mw_locator_latitude").val($("#mageworx_locations_general_default_lat").val());
                        }
                        if ($("#mageworx_locations_general_default_lng").val()) {
                            $("#mw_locator_longitude").val($("#mageworx_locations_general_default_lng").val());
                        }
                        $("#mw_locator_region").val($("#mageworx_locations_general_default_region").val());
                        $("#mw_locator_country").val($("#mageworx_locations_general_default_country").val());
                        $('#mwGetCoordinatesModal').css('display', 'block');
                    },

                    closed: function(){
                        $("#mageworx_locations_general_default_city").val($("#mw_locator_city").val());
                        $("#mageworx_locations_general_default_lat").val($("#mw_locator_latitude").val());
                        $("#mageworx_locations_general_default_lng").val($("#mw_locator_longitude").val());
                        $("#mageworx_locations_general_default_region").val($("#mw_locator_region").val());
                        $("#mageworx_locations_general_default_country").val($("#mw_locator_country").val());
                        $("#mageworx_locations_general_default_scale").val($("#mw_locator_zoom").val());
                        $('#mwGetCoordinatesModal').css('display', 'none');
                        $('#mw_locator_selected_location').text($("#mw_locator_city").val() + ', ' + $("#mw_locator_region").val() + ', ' + $("#mw_locator_country").val());
                    },

                    buttons: [{
                        text: $.mage.__('Preview'),
                        attr: {},
                        class: 'action-primary',
                        click: function (event) {
                            $("#mwGetCoordinatesModal").trigger('show_map_preview');
                        }
                    }, {
                        text: $.mage.__('Select this location'),
                        attr: {},
                        class: 'action-primary',
                        click: function (event) {
                            this.closeModal(event);
                        }

                    }]
                });

                var element = $(elemen);
                element.click(function() {
                    target.modal('openModal');
                });
            },
        };

        return {
            'mw-get-coordinates-modal': MwGetCoordinatesModal.initModal
        };
    }
);
