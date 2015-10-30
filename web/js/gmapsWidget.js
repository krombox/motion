var gmapsWidget = {
    mapSelector: '.map-canvas',
    lat: '49.58826699999999',
    lng: '34.551417000000015',
    zoom: 17,
    map: {},
    marker: {},
    
    init: function(){
        this.initMap();
        this.initAddressAutocomplete();
    },
    
    initMap: function(){
        $(gmapsWidget.mapSelector).each(function () {            
            gmapsWidget.map($(this));
        });                
    },
    
    map: function(element){
        var mapOptions = {
            center: new google.maps.LatLng(this.lat, this.lng),
            zoom: this.zoom 
        }
        
        var map = new google.maps.Map(element[0], mapOptions);
        gmapsWidget.map = map;
        this.addMarker();
    },
    
    addMarker: function(){        
        var marker = new google.maps.Marker({
            map: gmapsWidget.map,
            position: gmapsWidget.map.center,
            anchorPoint: new google.maps.Point(0, -29)
        });
        gmapsWidget.marker = marker;  
    },

    initAddressAutocomplete: function() {console.log('init');
        $('.gmaps').each(function () {
            gmapsWidget.addressAutocomplete($(this));
        });
    },        

    addressAutocomplete: function(div) {
        if (div.hasClass('started'))
            return;
        
        var formattedInput = $(div.find('[id$="_formatted"]'))[0];
        var autocomplete = new google.maps.places.Autocomplete(formattedInput, {types: ['geocode']});
        
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();    console.log(place);        
            if (!place.geometry) return;                            
            if (place.geometry.viewport) {
                gmapsWidget.map.fitBounds(place.geometry.viewport);
            } else {
                gmapsWidget.map.setCenter(place.geometry.location);
                gmapsWidget.map.setZoom(gmapsWidget.zoom);
            }
            gmapsWidget.marker.setPosition(place.geometry.location);
            div.find('[id$="_lat"]').val(place.geometry.location.lat());
            div.find('[id$="_lng"]').val(place.geometry.location.lng());
            div.find('[id$="_country"]').val(gmapsWidget.findComponent(place, 'country'));
            div.find('[id$="_state"]').val(gmapsWidget.findComponent(place, 'administrative_area_level_1'));
            div.find('[id$="_city"]').val(gmapsWidget.findComponent(place, 'locality') || gmapsWidget.findComponent(place, 'administrative_area_level_3'));
            div.find('[id$="_streetNumber"]').val(gmapsWidget.findComponent(place, 'street_number'));
            div.find('[id$="_street"]').val(gmapsWidget.findComponent(place, 'route'));
            div.find('[id$="_gId"]').val(place.id);
            setTimeout(function(){
                $(document).trigger('addressAutocomplete');
            },200);
        });
        div.addClass('started');
    },

    findComponent: function(result, type) {
        var component = _.find(result.address_components, function (component) {
            return _.include(component.types, type);
        });
        return component && component.short_name;
    }
};

$(document).ready(function(){
    gmapsWidget.init();
});
