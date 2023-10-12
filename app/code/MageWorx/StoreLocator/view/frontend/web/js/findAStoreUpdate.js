define(["jquery", "uiRegistry"], function ($, registry) {
    "use strict";
    var findAStoreUpdate = {
        configData: {},
        target: null,

        _create: function (config, element) {
            $(document).ready(function () {
                findAStoreUpdate.configData = config;
                findAStoreUpdate.target = config.target;

                findAStoreUpdate.updateFindAStore(findAStoreUpdate.target);

                $('.swatch-opt, .swatch-opt-' + findAStoreUpdate.configData.product).on('click', function (event) {
                    findAStoreUpdate.target = event.target;
                    findAStoreUpdate.updateFindAStore();
                });

                $('.swatch-select').on('change', function (event) {
                    findAStoreUpdate.target = event.target;
                    findAStoreUpdate.updateFindAStore();
                });

                $('.super-attribute-select').on('change', function (event) {
                    findAStoreUpdate.target = event.target;
                    findAStoreUpdate.updateFindAStore();
                });

                $(document).on('findAStoreUpdate', function () {
                    findAStoreUpdate.updateFindAStore();
                });
            });
        },

        updateFindAStore () {
            var self = this;
            var swatch = $(this.target).closest('.swatch-opt, .swatch-opt-' + this.configData.product);
            if (!this.checkSelectedItems(swatch)) {
                return;
            }
            var data = this.getSelectedOptions(swatch);

            $.ajax({
                url: self.configData.dataUrl + 'store_locator/location/updatefindastore',
                type: 'POST',
                isAjax: true,
                dataType: 'html',
                data: data,
                success: function (xhr, status, errorThrown) {
                    $('#find_a_store').html(xhr);
                    if ($('#showLocationButtonPlace').length > 0) {
                        $('.show-location-text').show();
                    } else {
                        $('.show-location-text').hide();
                    }

                    registry.set('store_locator_product_data', data);
                },
                error: function (xhr, status, errorThrown) {
                    console.log('There was an error loading stores.');
                    console.log(errorThrown);
                }
            });
        },

        checkSelectedItems (swatch) {
            var result = true;
            $.each(swatch.find('.swatch-attribute'), function (i, e) {
                var option = $(e).attr('option-selected');
                if (typeof (option) === "undefined") {
                    option = $(e).attr('data-option-selected');
                }
                if (typeof (option) === "undefined") {
                    result = false;
                }
            });

            return result;
        },
        getSelectedOptions(swatch) {
            var data = swatch.closest('form').serializeArray();

            if (!data.length) {
                var superAttribute = function (name, value) {
                    this.name = name;
                    this.value = value;
                };

                data.push(new superAttribute('product', this.configData.product));

                $.each(swatch.find('.swatch-attribute'), function (i, e) {
                    var name = 'super_attribute[' + $(e).attr('attribute-id') + ']';
                    var value = $(e).attr('option-selected');
                    var obj = new superAttribute(name, value);

                    data.push(obj);
                });

                $.each($('.super-attribute-select'), function (i, e) {
                    var name = $(e).attr('name');
                    var value = $(e).val();
                    var obj = new superAttribute(name, value);

                    data.push(obj);
                });
            }

            return data;
        }
    };
    return {
        'findAStoreUpdate': findAStoreUpdate._create
    };

});
