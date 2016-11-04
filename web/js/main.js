(function ($) {
    $(document).ready(function () {

        $('body').on('submit', '#icaoForm', function (e) {
            e.preventDefault();
            var $form = $(this);
            $.ajax({
                url: $form.attr('action'),
                type: "post",
                data: $form.serialize(),
                dataType: "json",
                success: function (data) {
                    $.notify(data.message, data.status);
                    deleteMarkers();
                    if ('success' === data.status) {
                        for (var i = 0; i < data.notams.length; i++) {
                            addMarker(data.notams[i]);
                        }
                        fitBounds();
                    }
                }
            })
        });
    });

    var map;
    var markers = [];
    var image = '/images/warning-icon-th.png';

    function initMap() {
        uluru = {lat: 49.85, lng: 24.0166666667};
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 4,
            center: uluru
        });

        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
    }

    google.maps.event.addDomListener(window, 'load', initMap);

    function addMarker(location) {
        // console.log(location);
        var latLng = new google.maps.LatLng(location.lat, location.long);

        var content = '<p>' + location.desc + '</p>' +
            '<p> Latitude: ' + location.lat + '<br/>' +
            'Longtitude: ' + location.long + '</p>';

        var infowindow = new google.maps.InfoWindow({
            content: content
        });
        var marker = new google.maps.Marker({
            position: latLng,
            map: map,
            title: location.desc,
            icon: image
        });

        marker.addListener('click', function () {
            infowindow.open(map, marker);
        });
        markers.push(marker);
    }

    // Sets the map on all markers in the array.
    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }

    // Removes the markers from the map, but keeps them in the array.
    function clearMarkers() {
        setMapOnAll(null);
    }

    // Shows any markers currently in the array.
    function showMarkers() {
        setMapOnAll(map);
    }

    // Deletes all markers in the array by removing references to them.
    function deleteMarkers() {
        clearMarkers();
        markers = [];
    }

    function fitBounds() {
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].getPosition());
        }

        map.fitBounds(bounds);
    }
})(jQuery);
