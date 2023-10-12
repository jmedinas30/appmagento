var placeSearch, autocomplete, place;

var componentForm = {
    locality: 'long_name',
    administrative_area_level_1: 'long_name',
    country: 'long_name',
    postal_code: 'long_name'
};

function initAutocomplete() {
    var options = {
        types: ['(regions)']
    };

    autocomplete = new google.maps.places.Autocomplete(
        document.getElementById('mw-sl_search_text'), options);

    autocomplete.setFields(['address_component', 'geometry']);
    autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
    place = autocomplete.getPlace();

    if (place.geometry === undefined) {
        return;
    }

    resetFields();
    jQuery('#mw_locator_latitude').val(place.geometry.location.lat());
    jQuery('#mw_locator_longitude').val(place.geometry.location.lng());
    var placeName = '';

    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
            jQuery('.mw_' + addressType).val(place.address_components[i][componentForm[addressType]]);

            if (placeName) {
                placeName += ', ';
            }
            placeName += place.address_components[i][componentForm[addressType]];
        }
    }

    jQuery("#mw_location_current_location_info").innerHTML = placeName;
    marker.setPosition(place.geometry.location);
    map.setCenter(place.geometry.location);
}

function resetFields() {
    for (var component in componentForm) {
        jQuery('.mw_' + component).val('');
    }
}
