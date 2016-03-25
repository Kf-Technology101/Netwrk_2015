function get_size_window(){
  return [$(window).width(),$(window).height()];
}

function set_size_map(w,h){
  //var menu_h = $('.menu_top').height();
  //$('#googleMap').css({'height': h - menu_h,'min-height': h -menu_h});
  //$('.map_content').css({'height': h - menu_h,'width': w});
  $('#googleMap').css({'height': h,'min-height': h});
}

function set_position_btn_meet(w,h){
  var menu_h = $('.menu_top').height();
  var hp = h - 100 - menu_h;
  var wp = w - (w - 15);
  $('#btn_meet').css({'bottom': 20,'right': wp});
  $('#modal_meet #btn_discover').css({'top': hp - 30 ,'right': wp});
}

function set_position_btn(parent,target,paddingTop,paddingRight){
  var menu_h = $('.menu_top').height();
  var size = get_size_window();
  var hp = size[1] - paddingTop - menu_h;

  var width_p = parent.width();
  var width_w = (size[0] - width_p)/2 + width_p - paddingRight;

  target.css({'top': hp ,'left': width_w});
  target.show();
}

function fix_width_post(target,width){
  var size = get_size_window();
  var wp = size[0] - width;

  target.css({'width': wp});
  target.find('p').css({'width': wp});

}

function fix_width_chat_post(target,width){
  var size = get_size_window();
  var wp = size[0] - width;

  target.css({'width': wp});

}
function set_position_btn_resize(parent,target,paddingTop,paddingRight){
  $(window).resize(function(){
    set_position_btn(parent,target,paddingTop,paddingRight)
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

function set_container_chat_modal(target,height_footer){
  var size = get_size_window();
  var wh = size[1] - height_footer - 100;
  target.find('.modal-body').css({'max-height':wh - 120,'height': wh-120});
}

function set_heigth_modal(target,height_footer){
  var size = get_size_window();
  var wh = size[1] - height_footer - 100;
  target.find('.modal-body').css({'max-height':wh - 120});
  $('.modal').css({'bottom': size[1] - (wh + 70)});//590
}

function set_heigth_modal_meet(target,height_footer, limit_width, range_width){
  var size = get_size_window();
  var wh = size[1] - height_footer - 100;
  if(wh - 120 > range_width){
    wh = limit_width;
  }
  target.find('.modal-body').css({'height':wh - 120});
  $('.modal').css({'bottom': size[1] - range_width});
}

function set_heigth_page_mobile(target){
  var menutop = $('.menu_top').height();
  var menubot = $('.menu_bottom').height();
  target.css({'height': $(window).height() - menutop - menubot});
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
	window_resize();
	_event_window_resize();
	Map.main();
}

function _addListenEventPage(){
	var page = this.show_page();
    console.log(page);
	var Page = page !== 'Chat-inbox' ? eval(page) : page;
	switch(page){
	case 'Topic':
		Topic.init();
		Create_Topic.initialize();
		break;
	case 'Meet':
		Page.initialize();
		break;
	case 'Setting':
		Profile.initialize();
		break;
	case 'Post':
    Post.initialize();
		break;
  case 'User':
    User.initialize();
    break;
  case 'Chat':
    PopupChat.initialize();
    break;
  case 'Chat-inbox':
    ChatInbox.initialize();
    break;
  case 'Profile':
    User_Profile.initialize();
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

function CustomScrollBar(){

  if(isMobile){

  }else{
    $(".modal").find('.modal-body').mCustomScrollbar({
        theme:"dark"
    });
  }

}

function homePage(){
    var target = $('.option_logo_netwrk a');
    var isLanding = $('.wrap-mobile').data('action');
    if (isLanding == 'landing-page') {
        console.log('if');
      target.on('click', function(e){
          e.preventDefault();
          sessionStorage.show_landing = 1;
          sessionStorage.map_zoom = 7;
          window.location.href = baseUrl + '/netwrk/default/home';
      });
    } else {
        console.log('else');
      target.on('click', function(e){
          e.preventDefault();
          sessionStorage.show_landing = 0;
          window.location.href = baseUrl + '/netwrk/default/home';
      });
    }
}

$(document).ready(function(){
  _main();
  MainWs.initialize();
  _addListenEventPage();
  Emoji.initialize();
  Search.initialize();
  Common.initialize();
  if(isMobile){
    homePage();
  }
});
