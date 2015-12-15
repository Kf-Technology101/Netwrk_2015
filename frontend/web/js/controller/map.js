var Map ={
	params:{
		name:'',
		zipcode:'',
		lat:'',
		lng:''
	},
	latLng: '',
	markers:[],
	data_map:'',
	infowindow:[],
	zoomIn: false,
	incre: 1,
	map:'',
	zoom: 7,
	// center: new google.maps.LatLng(39.7662195,-86.441277),
	center:'',
	initialize: function() {
		var map_andiana	 = {
			center: Map.center,
			zoom: Map.zoom,
			// disableDoubleClickZoom: true,
			disableDefaultUI: true,
			streetViewControl: false,
			scrollwheel: false,
			mapTypeId:google.maps.MapTypeId.ROADMAP
		};
		var remove_poi = [
   			{
     			featureType: "poi",
 				stylers: [
      				{ visibility: "off" }
 				]
    		}
		];

		var styledMap = new google.maps.StyledMapType(remove_poi,{name: "Styled Map"});
		var map = new google.maps.Map(document.getElementById("googleMap"),map_andiana);
		map.setOptions({zoomControl: false,  styles: remove_poi}); // scrollwheel: false,
		// map.setOptions({zoomControl: false, disableDoubleClickZoom: true,styles: remove_poi});
		var tskey = "b26fdd409c" ;
		var imgMapType = new google.maps.ImageMapType({
				getTileUrl: function(coord, zoom){
					if(zoom!=12){
						return null;
					}
					if(zoom==12){
						var url = "http://storage.googleapis.com/zipmap/tiles/" + zoom + "/" + coord.x + "/" + coord.y + ".png" ;
						return url;
					}
					var server = coord.x % 6;
					var url = "http://ts" + server + ".usnaviguide.com/tileserver.pl?X=" + coord.x + "&Y=" + coord.y + "&Z=" + zoom + "&T=" + tskey + "&S=Z1001";
					return url;
				},
				tileSize: new google.maps.Size(256,256),
				opacity: .3,
				name: 'ziphybrid'
			});
		map.overlayMapTypes.push(imgMapType);

		Map.data_map = map;
		Map.min_max_zoom(map);
		// Map.eventOnclick(map);
		
		Map.eventZoom(map);
		Map.eventClickMyLocation(map);
		Map.show_marker(map);
		Map.showHeaderFooter();
	},

	main: function(){
	  	if (typeof google !== "undefined") {
	  		Map.center = new google.maps.LatLng(39.7662195,-86.441277);
    		google.maps.event.addDomListener(window, 'load', Map.initialize());
  		}
	},

	get_data_marker: function(){
		var map = Map.data_map;
		var current = map.getZoom();
		$('.indiana_marker').find("li[num-city]").remove();
		Map.deleteNetwrk(map);
		Map.show_marker(map).trigger('idle');
	},

	get_netwrk: function(){
		var cities = $('.indiana_marker').find("li[num-city]");
		var data= [];

		$.each(cities,function(i,e){
			var city =[];
			city.push($(e).text());
			city.push($(e).attr('lat'));
			city.push($(e).attr('lng'));
			city.push($(e).attr('num-city'));
			data.push(city);
		});

		return data;
	},

	deleteNetwrk: function(map) {
		$('.indiana_marker').find("li[num-city]").remove();
	  	Map.clearMarkers();
	 	Map.markers = [];
	},

	clearMarkers: function() {
	  Map.setMapOnAll(null);
	},

	setMapOnAll: function(map) {
		for (var i = 0; i < Map.markers.length; i++) {
			Map.markers[i].setMap(map);
		}
	},

	show_marker: function(map){
		// console.log('show marker');
	  	var marker,json,data_marker;

	 	var current_zoom = map.getZoom();

	 	if(current_zoom == 7){
	 		Ajax.get_marker_default().then(function(data){
	 			console.log('get marker default');
	 			data_marker = $.parseJSON(data);
	 		});
	 	}else if(current_zoom == 12){
	 		Ajax.get_marker_zoom().then(function(data){
	 			console.log('get marker zoom');
	 			data_marker = $.parseJSON(data);
	 		});
	 	}

		$.each(data_marker,function(i,e){
			// console.log('fetch elements in array marker');
			var img = './img/icon/map_icon_community_v_2.png';
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(e.lat, e.lng),
				map: map,
				icon: img,
				city_id: parseInt(e.id)
			});

            var infowindow = new google.maps.InfoWindow({
            	content: '',
            	city_id: e.id,
            	maxWidth: 350
            });

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function(){
					if(!isMobile){
						infowindow.close();
					}
					Topic.initialize(marker.city_id);
				};
			})(marker, i));

			if(!isMobile){
				var content = '<div id="iw-container" >' +
			                '<div class="iw-title"><span class="toppost">Top Post</span><a class="info_zipcode" data-city="'+ e.id +'" onclick="Map.eventOnClickZipcode('+e.id +')"><span class="zipcode">'+ e.zip_code + '</span></a></div>' +
			                '<div class="iw-content">' +
			                  '<div class="iw-subTitle">#'+e.post.name_post+'</div>' +
			                  '<p>'+e.post.content+'</p>'+
			                '</div>' +
			                '<div class="iw-bottom-gradient"></div>' +
			              '</div>';
			    infowindow.content = content;

	            Map.infowindow.push(infowindow);

				google.maps.event.addListener(marker, 'mouseover', function() {
					// infowindow.setContent(e[0]);
					infowindow.open(map, this);
					Map.onhoverInfoWindow(e.id,marker);
				});

				google.maps.event.addListener(marker, 'mouseout', function() {
					// infowindow.close();
				});

			  	google.maps.event.addListener(infowindow, 'domready', function() {

				  //   // Reference to the DIV that wraps the bottom of infowindow
				    var iwOuter = $('.gm-style-iw');

				  //    // Since this div is in a position prior to .gm-div style-iw.
				  //    // * We use jQuery and create a iwBackground variable,
				  //    // * and took advantage of the existing reference .gm-style-iw for the previous div with .prev().

				    var iwBackground = iwOuter.prev();
				    iwOuter.children(':nth-child(1)').css({'max-width' : '400px'});
				  // Removes background shadow DIV
				    iwBackground.children(':nth-child(2)').css({'display' : 'none'});

				  //   // Removes white background DIV
				    iwBackground.children(':nth-child(4)').css({'display' : 'none'});

				  //   // Moves the infowindow 115px to the right.
				    // iwOuter.parent().parent().css({left: '115px'});

				  //   // Moves the shadow of the arrow 76px to the left margin.
				    iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'top: 174px !important;left: 192px !important;'});

				  //   // Moves the arrow 76px to the left margin.
				    iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'top: 174px !important;left: 192px !important;'});

				  //   // Changes the desired tail shadow color.
				    iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': '#477499 0px 1px 0px 2px', 'z-index' : '1'});

				  //   // Reference to the div that groups the close button elements.
				    var iwCloseBtn = iwOuter.next();

				  //   // Apply the desired effect to the close button
				    iwCloseBtn.css({opacity: '0', right: '135px', top: '15px', border: '0px solid #477499', 'border-radius': '13px', 'box-shadow': '0 0 0px 2px #477499','display':'none'});

				  //   // If the content of infowindow not exceed the set maximum height, then the gradient is removed.
				  //   if($('.iw-content').height() < 140){
				  //     $('.iw-bottom-gradient').css({display: 'none'});
				  //   }

				  //   // The API automatically applies 0.7 opacity to the button after the mouseout event. This function reverses this event to the desired value.
				    iwCloseBtn.mouseout(function(){
				      $(this).css({opacity: '0'});
				    });
		  		});
			}

			Map.markers.push(marker);
		});

		if(map.getZoom() == 12) {
			map.addListener('idle', function(){
				// radarSearch --------------------------------------------
				var service = new google.maps.places.PlacesService(map);
				  service.radarSearch({
				  	bounds: map.getBounds(),
	    			keyword: 'university in indiana state',
	    			types: ['university']
				}, function(results, status){
					if (status === google.maps.places.PlacesServiceStatus.OK) {
						console.log('university');
						infowindow = new google.maps.InfoWindow();
					    for (var i = 0; i < results.length; i++) {
					      Map.createMarkerGov(service, results[i], map);
					     //  Map.getZipcodeAddress(results[i].vicinity);
					    	// console.log(results[i].vicinity);
					    }
					}
				});

				  var service2 = new google.maps.places.PlacesService(map);
				  service2.radarSearch({
				  	bounds: map.getBounds(),
	    			keyword: 'government in indiana state',
	    			types: ['local_government_office']
				}, function(results, status){
					if (status === google.maps.places.PlacesServiceStatus.OK) {
						console.log('government');
						infowindow = new google.maps.InfoWindow();
					    for (var i = 0; i < results.length; i++) {
					      Map.createMarker(service2, results[i], map);
					     //  Map.getZipcodeAddress(results[i].vicinity);
					     // console.log(results[i].vicinity);
					    }
					    console.log(results[0]);
					}
				});

				// nearbySearch ------------------------------------------------
				// var service = new google.maps.places.PlacesService(map);
				//   service.nearbySearch({
				//     location: {lat: 39.7662195, lng: -86.441277},
				//     radius: 1000,
				//     types: ['local_government_office']
				// }, function(results, status){
				// 	if (status === google.maps.places.PlacesServiceStatus.OK) {
				// 		infowindow = new google.maps.InfoWindow();
				// 	    for (var i = 0; i < results.length; i++) {
				// 	      Map.createMarkerGov(results[i], map);
				// 	      Map.getZipcodeAddress(results[i].vicinity);
				// 	    	console.log(results[i].vicinity);
				// 	    }
				// 	}
				// });

				// var service2 = new google.maps.places.PlacesService(map);
				// service2.nearbySearch({
				//     location: {lat: 39.7662195, lng: -86.441277},
				//     radius: 1000,
				//     types: ['university']
				// }, function(results, status){
				// 	if (status === google.maps.places.PlacesServiceStatus.OK) {
				// 		infowindow = new google.maps.InfoWindow();
				// 	    for (var i = 0; i < results.length; i++) {
				// 	    	Map.createMarker(results[i], map);
				// 	    	Map.getZipcodeAddress(results[i].vicinity);
				// 	    	console.log(results[i].vicinity);
				// 	    }
				// 	}
				// });
			});
	 	} else {
	 		google.maps.event.clearListeners(map, 'idle');
	 	}
	},

	callback: function(results, status, map) {
	  if (status === google.maps.places.PlacesServiceStatus.OK) {
	    for (var i = 0; i < results.length; i++) {
	      Map.createMarker(results[i], map);
	    }
	  }
	},

	createMarker: function(service, place, map) {
	  var placeLoc = place.geometry.location;
	  
	  var img = './img/icon/map_icon_university_v_2.png';
	  
	  var marker = new google.maps.Marker({
	    map: map,
	    position: place.geometry.location,
	    icon: img,
	    city_id: 1,
	  });

	  google.maps.event.addListener(marker, 'click', function() {
	    service.getDetails(place, function(result, status){
	  		infowindow.setContent(result.name);
	    	infowindow.open(map, marker);
	  	});
	  });
	  Map.markers.push(marker);
	},

	createMarkerGov: function(service, place, map) {
	  var placeLoc = place.geometry.location;
	  
	  var img = './img/icon/map_icon_government_v_2.png';
	  
	  var marker = new google.maps.Marker({
	    map: map,
	    position: place.geometry.location,
	    icon: img,
	    city_id: 1,
	    // icon: {
	    //   url: img,
	    //   // anchor: new google.maps.Point(10, 10),
	    //   // scaledSize: new google.maps.Size(27, 45)
	    // }
	  });

	  // google.maps.event.addListener(marker, 'click', function() {
	  // 	service.getDetails(place, function(result, status){
	  // 		infowindow.setContent(result.name);
	  //   	infowindow.open(map, marker);
	  // 	});
	  // });
	  google.maps.event.addListener(marker, 'click', (function(marker) {
				return function(){
					if(!isMobile){
						infowindow.close();
					}
					Topic.init(marker.city_id);
				};
			})(marker));
	  Map.markers.push(marker);
	},

	getZipcodeAddress: function(address){
        $.getJSON("https://maps.googleapis.com/maps/api/geocode/json?address="+address+" IN" ,function(data){
        	var len = data.results[0].address_components.length;
        	for(var i=0; i<len; i++) {
        		if(data.results[0].address_components[i].types[0] == 'postal_code') {
        			console.log(data.results[0].address_components[i].long_name);
        		}
        	}
            // if (data.places[0].state == "Indiana"){
            // 	Map.params.name = data.places[0]['place name'],
            // 	Map.params.zipcode = zipcode;
            // 	Map.params.lat = data.places[0].latitude;
            // 	Map.params.lng = data.places[0].longitude;
            // 	Create_Topic.params.zip_code = zipcode;
            // 	Create_Topic.params.lat = Map.latLng.lat();
            // 	Create_Topic.params.lng = Map.latLng.lng();
            // 	Create_Topic.params.netwrk_name = data.places[0]['place name'];

            // 	Ajax.check_zipcode_exist(Map.params).then(function(data){
            // 		var json = $.parseJSON(data);
            // 		if (json.status == 0){
            // 			Topic.init($.now(),Map.params);
            // 		}else{
            // 			Topic.init(json.city);
            // 		}
            // 	});
            // }
            // console.log(data.results[0].address_components.length);
        });
	},




	eventClickMyLocation: function(map){
		var btn = $('#btn_my_location');
		btn.unbind();
		btn.on('click',function(){
			if(isGuest){
			    navigator.geolocation.getCurrentPosition(function(position) {
					var pos = {
						lat: position.coords.latitude,
						lng: position.coords.longitude
					};
	      			map.setCenter(new google.maps.LatLng(pos.lat, pos.lng));
	      			map.setZoom(12);
			    });
			    // map.zoom = 12;
			}else{
				var zoom_current = map.getZoom();
				if (zoom_current == 7) {
					Map.smoothZoom(map, 13, zoom_current, true);
					Map.zoomIn = true;
				}

				Ajax.get_position_user().then(function(data){
					var json = $.parseJSON(data);
					map.setCenter(new google.maps.LatLng(json.lat, json.lng));
				});
			}
		});
	},

	eventZoom: function(map){
		var mode = true;
		map.addListener('dblclick', function(event){
		    if(!Map.zoomIn){
			    Map.smoothZoom(map, 12, map.getZoom() + 1, true);
			    Map.zoomIn = true;

			    // Map.incre = 1;
			    // if(map.getZoom() == 12) {
				    Map.deleteNetwrk(map);
					map.zoom = 12;
					Map.show_marker(map);
				// }
			} else {
				Map.smoothZoom(map, 7, map.getZoom(), false);
				Map.zoomIn = false;
				// Map.incre = 1;
				// if(map.getZoom() == 7) {
					Map.deleteNetwrk(map);
					map.zoom = 7;
					Map.show_marker(map);
				// }
			}
		});
	},

	smoothZoom: function(map, level, cnt, mode) {
		// console.log("level: " + level + " === cnt: " + cnt + " === mode: " + mode + " === incre: " + Map.incre);
		// If mode is zoom in
		if(mode == true) {
			if (cnt > level) {
				Map.incre = 1;
				console.log(cnt);
				return;
			}
			else {

				var z = google.maps.event.addListener(map, 'zoom_changed', function(event){
					google.maps.event.removeListener(z);
					Map.smoothZoom(map, level, cnt + Map.incre, true);
				});
				setTimeout(function(){map.setZoom(cnt)}, 150);
				if (Map.incre < 2) {
					Map.incre++;
				} else {
					Map.incre = 2;
				}
			}
		} else {
			if (cnt < level) {
				Map.incre = 1;
				return;
			}
			else {

				var z = google.maps.event.addListener(map, 'zoom_changed', function(event) {
					google.maps.event.removeListener(z);
					Map.smoothZoom(map, level, cnt - 1, false);
				});
				setTimeout(function(){map.setZoom(cnt)}, 110);
				if (Map.incre < 2)
					Map.incre++;
				else {
					Map.incre = 1;
				}
			}
		}
	},

	min_max_zoom: function(map){
	  	google.maps.event.addListenerOnce(map, "projection_changed", function(){
		    map.setMapTypeId(google.maps.MapTypeId.HYBRID);  //Changes the MapTypeId in short time.
		    Map.setZoomLimit(map, google.maps.MapTypeId.ROADMAP);
		    Map.setZoomLimit(map, google.maps.MapTypeId.HYBRID);
		    Map.setZoomLimit(map, google.maps.MapTypeId.SATELLITE);
		    Map.setZoomLimit(map, google.maps.MapTypeId.TERRAIN);
		    map.setMapTypeId(google.maps.MapTypeId.ROADMAP);  //Sets the MapTypeId to original.
  		});
	},

	setZoomLimit: function(map, mapTypeId){
	  	//Gets MapTypeRegistry
		var mapTypeRegistry = map.mapTypes;

		//Gets the specified MapType
		var mapType = mapTypeRegistry.get(mapTypeId);
		//Sets limits to MapType
		mapType.maxZoom = 12;  //It doesn't work with SATELLITE and HYBRID maptypes.
		mapType.minZoom = 7;
	},

	reset_data: function(){
		Map.params.name = null;
		Map.params.zipcode = null;
		Map.params.lat = null;
		Map.params.lng = null;
	},

	eventOnclick: function(map){
	  	google.maps.event.addListener(map, 'click', function(e) {
	  		Map.closeInfoWindow();
	  		Map.reset_data();
	  		if (map.getZoom() == 12 ){
				Map.geocoder(e.latLng);
			}
			Map.latLng = e.latLng;
		});
	},

	eventOnclickMarker: function(){

	},

	eventOnClickZipcode: function(city){
		console.log(Map.infowindow);
		$.each(Map.infowindow,function(i,e){
			e.close();
			Map.infowindow=[];
		});
		Topic.initialize(city);
	},

	onhoverInfoWindow: function(city,marker){
		$.each(Map.infowindow,function(i,e){
			if(e.open && e.city_id != city){
				e.close();
			}
		});
	},

	closeInfoWindow:function(){
		$.each(Map.infowindow,function(i,e){
			e.close();
		});
	},

	geocoder: function(data){
		var geo = new google.maps.Geocoder();

		geo.geocode({'latLng': data},function(value,status){
			if(status == google.maps.GeocoderStatus.OK){
				Map.get_zipcode(value[0].address_components);
			}
		});
	},

	get_zipcode: function(zipcode){
		$.each(zipcode,function(i,e){
			if(e.types[0] == 'postal_code'){
				Map.checkzipcode(e.long_name);
			}
		});
	},

	checkzipcode: function(zipcode){
        $.getJSON("http://api.zippopotam.us/us/"+zipcode ,function(data){
            if (data.places[0].state == "Indiana"){
            	Map.params.name = data.places[0]['place name'],
            	Map.params.zipcode = zipcode;
            	Map.params.lat = data.places[0].latitude;
            	Map.params.lng = data.places[0].longitude;
            	Create_Topic.params.zip_code = zipcode;
            	Create_Topic.params.lat = Map.latLng.lat();
            	Create_Topic.params.lng = Map.latLng.lng();
            	Create_Topic.params.netwrk_name = data.places[0]['place name'];

            	Ajax.check_zipcode_exist(Map.params).then(function(data){
            		var json = $.parseJSON(data);
            		if (json.status == 0){
            			Topic.initialize($.now(),Map.params);
            		}else{
            			Topic.initialize(json.city);
            		}
            	});
            }
        });
	},

	showHeaderFooter: function(){
        $('.navbar-fixed-top').show();
        $('.navbar-fixed-bottom').show();
    }
}