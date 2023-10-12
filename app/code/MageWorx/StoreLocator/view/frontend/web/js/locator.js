require(["jquery", "uiRegistry"], function ($, registry) {
    $(document).ready(function () {
        var $filterButton = $('.mw-sl__icon--filter'),
            $detailsButton = $('.mw-sl__store__details'),
            $closeButton = $('.mw-sl__icon--close'),
            $storeFilters = $('.mw-sl__stores__filters'),
            $filterItem = $('.mw-sl__stores__filters__item'),
            $applyButton = $('.mw-sl__apply__submit'),
            $clearAllButton = $('.mw-sl__clear_all__submit'),
            $listItem = $('.mw-sl__stores__list__item'),
            $searchForm = $('.mw-sl__search'),
            $editLocationButton = $('.mw-sl__search-select-action--edit-location');

        $editLocationButton.on('click', function() {
            autofill = true;
            $searchForm.addClass('mw-sl__search--choose-location');
            jQuery('.mw-sl__stores').css('height', '330px');
            jQuery('.mw-sl__content--map-right .mw-sl__stores').css('height', '400px');
        });

        $currentPlace = $('.mw-sl__stores__current');

        $filterButton.on('click', function() {
            if ($storeFilters.hasClass('is-active')) {
                $storeFilters.removeClass('is-active');
            } else {
                $storeFilters.addClass('is-active');
            }
            $('.mw-sl__stores__details').removeClass('is-active');
        });

        /* Working hours table for filter_on_map and left_side_of_map layouts*/
        $('.mw-sl__stores').on('click', '.mw-sl__stores__details__hours__table', function(e) {
            $(e.currentTarget).toggleClass('is-active');
        });
        /* Working hours table for other layouts*/
        $('.mw-sl__stores__wrapper').on('click', '.mw-sl__stores__details__hours__table', function(e) {
            $(e.currentTarget).toggleClass('is-active');
        });

        $(document).on('click', '.mw-sl__store__select', function(event) {
            selectStore(event);
            $('.mageworx-modal-location .modal-header .action-close').trigger( "click" );
        });

        $applyButton.on('click', function() {
            //filter by places
            $(event.target).trigger('set_map_center_on_filter_item');
            $storeFilters.removeClass('is-active');
            var code = $("#mw-sl__stores__filters").val();

            if (code == 'all_stores') {
                $listItem.addClass('mw-store-locator-active-place');
            } else {
                $currentPlace.css('display', 'none');
                $('.mw-sl__stores__current_' + code).css('display', 'block');
                $listItem.removeClass('mw-store-locator-active-place');
                $('.location-info_' + code).toggleClass('mw-store-locator-active-place');
            }

            //filter by attributes
            $('.mw-sl__stores__filters__item input').each( function()   {
                if ($(this).prop("checked")) {
                    var attributeCode = $(this).prop('id');
                    $('.location-attr_' + attributeCode + '_false').removeClass('mw-store-locator-active-place');
                }
            });

            if ($('.mw-store-locator-active-place').length) {
                $('.mw-sl__no-stores').hide();
            } else {
                $('.mw-sl__no-stores').show();
            }
        });

        $clearAllButton.on('click', function() {
            $('.mw-sl__stores__filters__item input').prop("checked", false);
            $("#mw-sl__stores__filters").val('all_stores');
        });

        $detailsButton.on('click', function(event) {
            var code = event.target.id;
            if (!code) {
                code = $(event.target).closest('div').attr('id');
            }

            $(event.target).trigger('set_map_center_on_location');
            showLocationDetails(code);
        });

        $closeButton.on('click', function(event) {
            $('.mw-sl__stores__details').removeClass('is-active');
            $('.location-header').removeClass('is-active');
            $('#location-header').toggleClass('is-active');
        });

        if (typeof mwLocations !== 'undefined' && isWorkingHoursEnabled) {
            setTimeout(function(){
                showWorkingHours();
            }, 2000);
        }
    });

    function showLocationDetails(code) {
        if ($('#location-details_' + code).length < 1) {
            var currentPage =  $('.current_page_type').attr('id');
            var linkUrl = BASE_URL + 'store_locator/location/locationdetail';
            $.ajax({
                url: linkUrl + '?_=' + new Date().getTime() + '&id=' + code
                    + '&current_page=' + currentPage,
                type: 'POST',
                isAjax: true,
                dataType: 'html',
                showLoader: true,
                data: mwLocations,
                success: function (xhr, status, errorThrown) {
                    if ($('#location_details').length > 0) {
                        $(xhr).appendTo('#location_details');
                    } else {
                        $(xhr).appendTo('#location_details_' + code);
                    }
                    $('#location-details_' + code).toggleClass('is-active');
                    $('#location-header').toggleClass('is-active');
                    $('#location-header_' + code).toggleClass('is-active');
                },
                error: function (xhr, status, errorThrown) {
                    console.log('There was an error loading stores\' data.');
                    console.log(errorThrown);
                }
            });
        } else {
            $('#location-details_' + code).toggleClass('is-active');
            $('#location-header').toggleClass('is-active');
            $('#location-header_' + code).toggleClass('is-active');
        }
    }

    function showWorkingHours() {
        $.ajax({
            url: BASE_URL + 'store_locator/location/workinghours?_=' + new Date().getTime(),
            type: 'POST',
            isAjax: true,
            dataType: 'json',
            data: mwLocations,
            success: function (xhr, status, errorThrown) {
                var items = [];
                $.each(xhr, function (locationCode, val) {
                    if (typeof(val['isOpen']) !== 'undefined' && typeof(val['info']) !== 'undefined') {
                        if (val['isOpen']) {
                            $('.store__info_' + locationCode).addClass('mw-sl__store__info__open');
                            $('.store__info_wh_' + locationCode).addClass('mw-sl__store__info__open');
                            $('.store__info_wh_' + locationCode).text(val['info']);
                        } else {
                            $('.store__info_' + locationCode).addClass('mw-sl__store__info__closed');
                            $('.store__info_wh_' + locationCode).addClass('mw-sl__store__info__closed');
                            $('.store__info_wh_' + locationCode).text(val['info']);
                        }
                    }
                });
            },
            error: function (xhr, status, errorThrown) {
                console.log('There was an error loading stores\' working hours.');
            }
        });
    }

    function selectStore(event) {
        var code = event.target.id;
        if (!code) {
            code = $(event.target).closest('div').attr('id');
        }

        if ($('.mw-store-locator-multilocations').length > 0) {
            var addressId = $(event.target).closest('.multi-locations').attr('id');

            $('.' + addressId +' .mw-sl__store__info').html(
                $(event.target).closest('.mw-sl__stores__list__item__inner').html()
            );
            document.cookie = addressId + "=" + (code || "") + "; expires=604800; path=/";
            registry.set(addressId, code);
        } else {
            var codeRegistry = registry.get('mageworx_location_id');
            codeRegistry(code);
            document.cookie = 'mageworx_location_id' + "=" + (code || "") + "; expires=604800; path=/";
        }
    }
});
