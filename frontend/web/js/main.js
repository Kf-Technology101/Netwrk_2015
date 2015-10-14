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
  $('#modal_meet #btn_discover').css({'top': hp - 30 ,'left': wp});
}

function set_position_btn(target){
  var menu_h = $('.menu_top').height();
  var size = get_size_window();
  var hp = size[1] - 100 - menu_h;
  var wp = size[0] - 100;

  target.css({'top': hp - 70 ,'left': wp});
  target.show();
}

function set_position_btn_resize(target){
  $(window).resize(function(){
    set_position_btn(target)
  });
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

function set_heigth_modal(target){
  console.log(target);
  var size = get_size_window();
  var wh = size[1] - 100;
  target.find('.modal-dialog').css('height',wh);
  target.find('.modal-content').css('height',wh);
  target.find('.modal-body').css('height',wh - 90);
 
  
  $(window).resize(function(){
    size = get_size_window();
    wh = size[1] - 100;
    target.find('.modal-dialog').css('height',wh);
    target.find('.modal-content').css('height',wh);
    target.find('.modal-body').css('height',wh -90);
  });
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

function ieVersion() {
    var ua = window.navigator.userAgent;
    if (ua.indexOf("Trident/7.0") > 0)
        return 11;
    else if (ua.indexOf("Trident/6.0") > 0)
        return 10;
    else if (ua.indexOf("Trident/5.0") > 0)
        return 9;
    else if (ua.indexOf("Edge") > 0)
        return 'Edge';
    else
        return 0;  // not IE9, 10 or 11
}

function isonIE(){
    var status = ieVersion();
    if(status == 0) {
        return false;
    }else if (status == 'Edge'){
        return true;
    }else{
        return true;
    }
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

function isDate(txtDate, separator) {
    var aoDate,           // needed for creating array and object
        ms,               // date in milliseconds
        month, day, year; // (integer) month, day and year
    // if separator is not defined then set '/'
    if (separator === undefined) {
        separator = '-';
    }
    // split input date to month, day and year
    aoDate = txtDate.split(separator);
    // array length should be exactly 3 (no more no less)
    if (aoDate.length !== 3) {
        return false;
    }
    // define month, day and year from array (expected format is m/d/yyyy)
    // subtraction will cast variables to integer implicitly
    month = aoDate[1] - 1; // because months in JS start from 0
    day = aoDate[2] - 0;
    year = aoDate[0] - 0;
    // test year range
    if (year < 1000 || year > 3000) {
        return false;
    }
    // convert input date to milliseconds
    ms = (new Date(year, month, day)).getTime();
    // initialize Date() object from milliseconds (reuse aoDate variable)
    aoDate = new Date();
    aoDate.setTime(ms);
    // compare input date and parts from Date() object
    // if difference exists then input date is not valid
    if (aoDate.getFullYear() !== year ||
        aoDate.getMonth() !== month ||
        aoDate.getDate() !== day) {
        return false;
    }
    // date is OK, return true
    return true;
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
      Create_Topic.initialize();
      break;
    case 'Meet':
      Page.initialize();
      break;
    case 'Setting':
      Profile.initialize();
      break;
    case 'Post':
      Create_Post.initialize();
      break;
    default:
      Default.initialize();
      break;
  }
}

function shuffle_array(array) {
  var currentIndex = array.length, temporaryValue, randomIndex ;

  // While there remain elements to shuffle...
  while (0 !== currentIndex) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;

    // And swap it with the current element.
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }

  return array;
}

$(document).ready(function(){
  _main();
  _addListenEventPage();
});
