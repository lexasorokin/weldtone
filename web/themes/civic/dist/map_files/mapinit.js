
var map_zmap_2064583368;
var geocoder;
var default_polygon;
var in_service_area = false;
var isDraggable = !('ontouchstart' in document.documentElement);
var markers = [];

function initServiceMap() {

  var customMapType = new google.maps.StyledMapType(
          [
            {
              "featureType": "water",
              "elementType": "geometry.fill",
              "stylers": [{"color": "#c3c3c3"}]
            },
            {
              "featureType": "transit",
              "stylers": [
                {
                  "color": "#808080"
                },
                {
                  "visibility": "off"
                }]
            },
            {
              "featureType": "road.highway",
              "elementType": "geometry.stroke",
              "stylers": [
                {
                  "visibility": "off"
                }]
            },
            {
              "featureType": "road.highway",
              "elementType": "geometry.fill",
              "stylers": [
                {
                  "color": "#989898"
                }]
            },
            {
              "featureType": "road.local",
              "elementType": "geometry.fill",
              "stylers": [
                {
                  "visibility": "on"
                },
                {
                  "color": "#d5d5d5"
                },
                {
                  "weight": 1.8
                }]
            },
            {
              "featureType": "road.local",
              "elementType": "geometry.stroke",
              "stylers": [
                {
                  "visibility": "off"
                },
                {
                  "color": "#d7d7d7"
                }]
            },
            {
              "featureType": "poi",
              "elementType": "geometry.fill",
              "stylers": [
                {
                  "visibility": "on"
                },
                {
                  "color": "#ebebeb"
                }]
            },
            {
              "featureType": "administrative",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#a7a7a7"
                }]
            },
            {
              "featureType": "road.arterial",
              "elementType": "geometry.fill",
              "stylers": [
                {
                  "color": "#ffffff"
                }]
            },
            {
              "featureType": "road.arterial",
              "elementType": "geometry.fill",
              "stylers": [
                {
                  "color": "#ffffff"
                }]
            },
            {
              "featureType": "landscape",
              "elementType": "geometry.fill",
              "stylers": [
                {
                  "visibility": "on"
                },
                {
                  "color": "#e3e3e3"
                }]
            },
            {
              "featureType": "road",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#333333"
                }]
            },
            {
              "featureType": "administrative",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "visibility": "on"
                },
                {
                  "color": "#333333"
                }]
            },
            {
              "featureType": "poi",
              "elementType": "labels.icon",
              "stylers": [
                {
                  "visibility": "off"
                }]
            },
            {
              "featureType": "poi",
              "elementType": "labels",
              "stylers": [
                {
                  "visibility": "off"
                }]
            },
            {
              "featureType": "road.arterial",
              "elementType": "geometry.stroke",
              "stylers": [
                {
                  "color": "#d6d6d6"
                }]
            },
            {
              "featureType": "road",
              "elementType": "labels.icon",
              "stylers": [
                {
                  "visibility": "on"
                },
                {
                  "hue": "#000000"
                }, {
                  "saturation": "-100"
                }]
            },
            {

            },
            {
              "featureType": "poi",
              "elementType": "geometry.fill",
              "stylers": [
                {
                  "visibility": "off"
                }]
            }]

          , {
            name: 'Mesa Water Style'
          });

  var customMapTypeId = 'custom_style';

  map_zmap_2064583368 = new google.maps.Map(document.getElementById('zmap_2064583368'), {
    zoom: 13,
    center: {lat: 33.659625, lng: -117.919042},
    scrollwheel: 0,
    zoomControl: 1,
    scaleControl: 0,
    mapTypeControl: 0,
    streetViewControl: 0,
    rotateControl: 0,
    fullscreenControl: 1,
    draggable: isDraggable,
    mapTypeControlOptions: {
      mapTypeIds: [google.maps.MapTypeId.ROADMAP, customMapTypeId]
    }

  });

  map_zmap_2064583368.mapTypes.set(customMapTypeId, customMapType);
  map_zmap_2064583368.setMapTypeId(customMapTypeId);

  google.maps.event.addDomListener(window, 'resize', function () {
    var center = map_zmap_2064583368.getCenter();
    google.maps.event.trigger(map_zmap_2064583368, "resize");
    map_zmap_2064583368.setCenter(center);
  });




  window.areas = [];

  var elems = [];


  if (typeof (drupalSettings.interactive_map_elements) != 'undefined') {

    var current_index = 0;
    for (var elem_index in drupalSettings.interactive_map_elements) {

      var elem = {};

      elems.push(elem);

      var outer_points = [];
      var default_points = drupalSettings.interactive_map_elements[elem_index].polygon_points;
      var split_string = default_points.split(" ");

      for (var i = 0; i < split_string.length; i++) {

        var point = split_string[i].split(",");
        outer_points.push({"lat": parseFloat(point[1]), "lng": parseFloat(point[0])});

      }

      var inner_points = [];

      elems[current_index].area_polygon = new google.maps.Polygon({
        paths: [outer_points, inner_points],
        strokeColor: drupalSettings.interactive_map_elements[elem_index].stroke_color,
        strokeOpacity: 1,
        strokeWeight: 2,
        fillColor: drupalSettings.interactive_map_elements[elem_index].fill_color,
        fillOpacity: 0.3,
        map: map_zmap_2064583368});


      // var lbl_lat = parseFloat(33.633); //marker 1 hardcode
      //  var lbl_lng = parseFloat(-117.94);

      var lbl_lat = elems[current_index].area_polygon.my_getBounds().getCenter().lat();
      var lbl_lng = elems[current_index].area_polygon.my_getBounds().getCenter().lng();



      if (drupalSettings.interactive_map_elements[elem_index].marker_lat) {
        lbl_lat = parseFloat(drupalSettings.interactive_map_elements[elem_index].marker_lat);
      }

      if (drupalSettings.interactive_map_elements[elem_index].marker_lon) {
        lbl_lng = parseFloat(drupalSettings.interactive_map_elements[elem_index].marker_lon);
      }

      overlay = new CustomMarker(
              new google.maps.LatLng(lbl_lat, lbl_lng),
              map_zmap_2064583368,
              {
                marker_id: drupalSettings.interactive_map_elements[elem_index].marker_id,
                division: drupalSettings.interactive_map_elements[elem_index].division_name,
                contact_name: drupalSettings.interactive_map_elements[elem_index].contact_name,
                label_direction: drupalSettings.interactive_map_elements[elem_index].label_direction.toLowerCase(),
                contact_image: drupalSettings.interactive_map_elements[elem_index].image_url
              }
      );

      //attachPolygonInfoWindow(area_1_polygon, '<div style="padding:10px;background-color:#FF3300;">TEST</div>');

      window.areas.push(elems[current_index].area_polygon);
      current_index++;
    }

  }

}

google.maps.Polygon.prototype.my_getBounds = function () {
  var bounds = new google.maps.LatLngBounds();
  this.getPath().forEach(function (element, index) {
    bounds.extend(element)
  });
  return bounds;
}

function geocodeAddress(event, method) {

  var press = event.which || event.keyCode;

  if (press != 13 && method != 'button') {
    return false;
  }

  var address = jQuery('#zservicearea_search').val();

  if (address.length < 6) {

    console.log('Please enter a complete address.');
    alert('Please enter a complete address.');
    return false;

  }

  geocoder = new google.maps.Geocoder();

  //console.log(address);



  var bl = new google.maps.LatLng(33.61637252120485, -118.02042514682375);
  var tr = new google.maps.LatLng(33.707996151391356, -117.7874006395674);

  var gcbounds = new google.maps.LatLngBounds(bl, tr);

  geocoder.geocode(
          {
            address: address,
            bounds: gcbounds,

          }, function (results, status) {

    //console.log(results);
    //console.log(status);

    deleteMarkers(null);

    if (status === google.maps.GeocoderStatus.OK) {

      jQuery('#zservicearea_search').blur();

      var in_service_area = 0;

      for (a = 0; a < window.areas.length; a++) {

        if (in_service_area = google.maps.geometry.poly.containsLocation(results[0].geometry.location, window.areas[a])) {
          break;
        }

      }



      if (in_service_area) {

        jQuery('#zservice-notification').empty();
        jQuery('#zservice-notification').append(drupalSettings.interactive_map.success_message);

        map_zmap_2064583368.setCenter(results[0].geometry.location);

        var marker = new google.maps.Marker({
          map: map_zmap_2064583368,
          position: results[0].geometry.location
        });

        markers.push(marker);

        jQuery('#zservicearea-map-notification').fadeIn();

        recenter(results[0].geometry.location, 0, jQuery('#zservice-notification').height());

      } else {

        jQuery('#zservice-notification').empty();
        jQuery('#zservice-notification').append(drupalSettings.interactive_map.success_message);

        jQuery('#zservicearea-map-notification').fadeIn();

      }



    } else {

      alert('Geocode was not successful for the following reason: ' + status);

    }

  });
}

function recenter(latlng, offsetx, offsety) {

  var point1 = map_zmap_2064583368.getProjection().fromLatLngToPoint(
          (latlng instanceof google.maps.LatLng) ? latlng : map_zmap_2064583368.getCenter()
          );

  var point2 = new google.maps.Point(
          ((typeof (offsetx) == 'number' ? offsetx : 0) / Math.pow(2, map_zmap_2064583368.getZoom())) || 0,
          ((typeof (offsety) == 'number' ? offsety : 0) / Math.pow(2, map_zmap_2064583368.getZoom())) || 0
          );

  map_zmap_2064583368.setCenter(map_zmap_2064583368.getProjection().fromPointToLatLng(new google.maps.Point(point1.x - point2.x, point1.y + point2.y)));

}

function deleteMarkers(map) {

  for (var i = 0; i < markers.length; i++) {

    markers[i].setMap(map);

  }

  markers = [];

}


jQuery(document).ready(function () {

  jQuery('#zservice-notification-close').click(function () {

    jQuery('#zservicearea-map-notification').fadeOut();

  });

});

google.maps.event.addDomListener(window, 'load', initServiceMap);