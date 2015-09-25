function get_size_window(){
  return [$(window).width(),$(window).height()];
}

function set_size_map(w,h){
  var menu_h = $('.menu_top').height();
  $('#googleMap').css('height',h - menu_h);
}

function set_position_btn_meet(w,h){
  var menu_h = $('.menu_top').height();
  var hp = h - 100 - menu_h;
  var wp = w - 100;
  
  $('#btn_meet').css({'top': hp,'left': wp});
  $('#btn_discover').css({'top': hp - 10 ,'left': wp});
}

function _event_window_resize(){
  $(window).resize(function(){
    window_resize();
  });
}

function window_resize(){
  var size = get_size_window();
  set_size_map(size[0],size[1]);
  set_position_btn_meet(size[0],size[1]);
}

function get_city(){
  var cities = $('.indiana_marker').find("li[num-city]");
  var data=[];
  $.each(cities,function(i,e){
    var city =[];
    city.push($(e).text());
    city.push($(e).attr('lat'));
    city.push($(e).attr('lng'));
    city.push($(e).attr('num-city'));
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

  var map = new google.maps.Map(document.getElementById("googleMap"),map_andiana);

  show_marker(map);
  min_max_zoom(map);
}

function show_marker(map){
  var marker,infowindow = new google.maps.InfoWindow();

  $.each(get_city(),function(i,e){
    marker = new google.maps.Marker({
      position: new google.maps.LatLng(e[1], e[2]),
      map: map,
      city_id: parseInt(e[3])
    });

    google.maps.event.addListener(marker, 'click', (function(marker, i) {
      return function(){
        Topic.init(marker.city_id);
      };
    })(marker, i));

    if (!isMobile) {
      google.maps.event.addListener(marker, 'mouseover', function(marker, i) {
        infowindow.setContent(e[0]);
        infowindow.open(map, this);
      });

      google.maps.event.addListener(marker, 'mouseout', function() {
        infowindow.close();
      });
    }
       
    
  });
}

function min_max_zoom(map){
  google.maps.event.addListenerOnce(map, "projection_changed", function(){
    map.setMapTypeId(google.maps.MapTypeId.HYBRID);  //Changes the MapTypeId in short time.
    setZoomLimit(map, google.maps.MapTypeId.ROADMAP);
    setZoomLimit(map, google.maps.MapTypeId.HYBRID);
    setZoomLimit(map, google.maps.MapTypeId.SATELLITE);
    setZoomLimit(map, google.maps.MapTypeId.TERRAIN);
    map.setMapTypeId(google.maps.MapTypeId.ROADMAP);  //Sets the MapTypeId to original.
  });
}

function setZoomLimit(map, mapTypeId){
  //Gets MapTypeRegistry
  var mapTypeRegistry = map.mapTypes;
  
  //Gets the specified MapType
  var mapType = mapTypeRegistry.get(mapTypeId);
  //Sets limits to MapType
  mapType.maxZoom = 9;  //It doesn't work with SATELLITE and HYBRID maptypes.
  mapType.minZoom = 7;
}

function show_page(){
  var page;
  if (isMobile) {
    page = $('.wrap-mobile').attr('id');
  } else {
    page = $('wrap').attr('id');
  }
  return page;
}

function _main(){
  if (typeof google !== "undefined") {
    google.maps.event.addDomListener(window, 'load', initialize);
  }
  window_resize();
  _event_window_resize();
}

function _addListenEventPage(){
  var page = this.show_page();
  var Page = eval(page);
  switch(page){
    case 'Topic':
      Page.initialize();
      break;
    default:
      Default.initialize();
  }
}

$(document).ready(function(){
  _main();
  _addListenEventPage();
});
