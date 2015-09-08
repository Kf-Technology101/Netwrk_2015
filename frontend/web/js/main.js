$(document).ready(function(){

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

  function _main(){
    google.maps.event.addDomListener(window, 'load', initialize);
    window_resize();
    _event_window_resize();
  }

  // run main();
  _main();

});
