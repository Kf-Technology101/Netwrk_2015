$(document).ready(function(){
  function get_city(){
    var cities = $('.indiana_marker').find("li[num-city]");
    var data=[];
    $.each(cities,function(i,e){
      var city =[];
      city.push($(e).text());
      city.push($(e).attr('lat'));
      city.push($(e).attr('lng'));
      data.push(city);
    });
    return data;
  }

  function initialize() {
    var map_andiana = {
      center:new google.maps.LatLng(39.7662195,-86.441277),
      zoom: 7,
      mapTypeId:google.maps.MapTypeId.ROADMAP
    };

    var infowindow = new google.maps.InfoWindow();
    var map = new google.maps.Map(document.getElementById("googleMap"),map_andiana);
    var marker;

    $.each(get_city(),function(i,e){
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(e[1], e[2]),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(e[0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    });
  }

  google.maps.event.addDomListener(window, 'load', initialize);
});
