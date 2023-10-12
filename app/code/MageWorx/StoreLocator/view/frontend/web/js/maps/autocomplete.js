var autocomplete, place, autofill = true, autocompleteService, placeService;


var componentForm = {
    locality: 'long_name',
    administrative_area_level_1: 'long_name',
    sublocality_level_1: 'long_name',
    country: 'short_name',
    postal_code: 'long_name'
};

function initAutocomplete(restriction) {
    if (restriction === undefined) {
        var options = {
            types: ['(regions)']
        };
    } else {
        var options = {
            types: ['(regions)'],
            componentRestrictions: restriction
        };
    }

    autocomplete = new google.maps.places.Autocomplete(
        document.getElementById('mw-sl_search_text'), options);

    autocompleteService = new google.maps.places.AutocompleteService(),
        placeService = new google.maps.places.PlacesService(document.createElement('div'));

    autocomplete.setFields(['address_component', 'geometry']);
    autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
    place = autocomplete.getPlace();
    fillInAddressByPlace(place);
   }

function fillInAddressByPlace(place) {
    if (place.geometry === undefined) {
        return;
    }

    resetFields();
    document.getElementById('mw-sl__lat').value = place.geometry.location.lat();
    document.getElementById('mw-sl__lng').value = place.geometry.location.lng();
    var placeName = '';

    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
            document.getElementById('mw_' + addressType).value = place.address_components[i][componentForm[addressType]];

            if (placeName) {
                placeName += ', ';
            }
            placeName += place.address_components[i][componentForm[addressType]];
        }
    }

    document.getElementById("mw_location_current_location_info").innerHTML = placeName;

    jQuery(".mw-sl__search__current-location").removeClass('mw-sl__search__current-location__loaded');

    autofill = false;
}

function resetFields() {
    for (var component in componentForm) {
        document.getElementById('mw_' + component).value = '';
    }
}
