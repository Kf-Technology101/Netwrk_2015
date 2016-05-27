/*
 * jQuery Web Sockets Plugin v0.0.1
 * http://code.google.com/p/jquery-websocket/
 *
 * This document is licensed as free software under the terms of the
 * MIT License: http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright (c) 2010 by shootaroo (Shotaro Tsubouchi).
 */
(function($){
$.extend({
	websocketSettings: {
		open: function(){},
		close: function(){},
		message: function(){},
		options: {},
		events: {}
	},
	websocket: function(url, s) {

		var self = this;

		var ws;
		if ("WebSocket" in window) {
		  // Chrome, MSIE, newer Firefox
		  	if (url == "undefined") {
		  		url = $('ws_uri').val();
			}
		  ws = new WebSocket(url);
		} else if ("MozWebSocket" in window) {
		  // older versions of Firefox prefix the WebSocket object
		  ws = new MozWebSocket(url);
		} else {
		  if (onclose !== undefined) {
		     return;
		  } else {
		  	return;
		  }
		}

		ws.onopen = function(event) {
			Common.hideLoader();
			clearInterval(timeout);
		}

		ws.onclose = function(event) {
			Common.initLoader();
			/*bootbox.dialog({
			  message: '<i class="fa fa-refresh fa-spin"></i> You were disconnected from Chat Server. Waiting to reconnect...',
			  closeButton: false
			});*/
			var timeout = setInterval(function(){
				ws = new WebSocket(url);
            }, 1500);
		}

		$.websocketSettings = $.extend($.websocketSettings, s);
		$(ws).bind('message', $.websocketSettings.message)
			.bind('message', function(e){
				var m = $.parseJSON(e.originalEvent.data);
				var h = $.websocketSettings.events[m.type];
				if (h) h.call(this, m);
			});
		ws._send = ws.send;
		ws.send = function(type, data) {
			var m = {type: type};
			m = $.extend(true, m, $.extend(true, {}, $.websocketSettings.options, m));
			if (data) m['data'] = data;
			return this._send(JSON.stringify(m));
		}
		$(window).unload(function(){ ws.close(); ws = null });
		return ws;
	}
});
})(jQuery);