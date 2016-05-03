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
	  	zoom: 13,
		markerZoom:14,
	  	// center: new google.maps.LatLng(39.7662195,-86.441277),
	  	center:'',
	  	zoom7: [],
	  	zoom12: [],
		zoom13: [],
		fillOpacity: 0.3,
	  	timeout: '',
		zoomBlueDot: 18,
		mouseIn : false,
		remove_poi : [
			{
				stylers: [
					{ hue: "#0078ff" },
					{ saturation: -20 }
				]
			},{
				featureType: "road",
				elementType: "labels",
				stylers: [
					{ visibility: "off" }
				]
			},{
				featureType: "road",
				elementType: "geometry",
				stylers: [
					{ lightness: 100 }
				]
			},{
				featureType: "poi",
				stylers: [
					{ visibility: "off" }
				]
			},{
				featureType: "administrative",
				elementType: "geometry.stroke",
				stylers: [
					{ color: "#5888ac" }
				]
			}
		],
	  	initialize: function() {

	  		if(isMobile){
				Map.zoom = 12;
				Map.markerZoom = 13;

				Common.initLoader();
		  		if(sessionStorage.map_zoom){
		  			Map.zoom = parseInt(sessionStorage.map_zoom);
		  		} else {
		  			sessionStorage.map_zoom = Map.zoom;
		  		}
		  		if(sessionStorage.lat && sessionStorage.lng){
		  			Map.center = new google.maps.LatLng(sessionStorage.lat, sessionStorage.lng);
		  		} else {
		  			sessionStorage.lat = Map.center.lat();
		  			sessionStorage.lng = Map.center.lng();
		  		}
		  	}
		    var map_andiana  = {
		      center: Map.center,
		      zoom: Map.zoom,
		      // disableDoubleClickZoom: true,
		      disableDefaultUI: true,
		      streetViewControl: false,
		      scrollwheel: true,
		      mapTypeId:google.maps.MapTypeId.ROADMAP
		    };
		    var remove_poi = Map.remove_poi;

		    var styledMap = new google.maps.StyledMapType(remove_poi,{name: "Styled Map"});
		    Map.map = new google.maps.Map(document.getElementById("googleMap"),map_andiana);
		    if(isMobile){
		    	if(sessionStorage.map_zoom && sessionStorage.map_zoom < 18){
		    		Map.map.setOptions({zoomControl: false, scrollwheel: true, styles: remove_poi});
		    	} else {
		    		 Map.map.setOptions({zoomControl: false, scrollwheel: true, styles: null});
		    	}
		    }else{
		    	Map.map.setOptions({zoomControl: false, scrollwheel: true, styles: remove_poi});
		    }

		    Map.data_map = Map.map;
		    Map.min_max_zoom(Map.map);
		    // Map.eventOnclick(map);
		    google.maps.event.addListenerOnce(Map.map, 'idle', function(){
				Ajax.get_marker_default().then(function(data){
					var data_marker = $.parseJSON(data);
					$.each(data_marker,function(i,e){
				      	Map.initializeMarker(e, null, 7);
			    	});
				});

				//Map.mapBoundaries(Map.map);
				Map.eventZoom(Map.map);
				Map.eventClickMyLocation(Map.map);
				Map.show_marker(Map.map);
				Map.showHeaderFooter();
				Map.mouseOutsideInfoWindow();
				Map.showZipBoundaries();
				Common.hideLoader();
			});
		    // Map.insertLocalUniversity();
		    // Map.insertLocalGovernment();
			Map.requestPosition(Map.map);
	  	},

	  	mapBoundaries:function(map){
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
	  	},

	  	main: function(){
	      	if (typeof google !== "undefined") {
				if(lat && lng){
					Map.center = new google.maps.LatLng(lat, lng);
 				}else{
					Map.center = new google.maps.LatLng(39.7662195,-86.441277);
				}
	        	google.maps.event.addDomListener(window, 'load', Map.initialize());
	      	}
	  	},

	  	get_data_marker: function(){
		    var map = Map.data_map;
		    var current = map.getZoom();
		    $('.indiana_marker').find("li[num-city]").remove();
		    Map.deleteNetwrk(map);
		    Map.show_marker(map);//.trigger('idle');
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
		    var json,data_marker;
		    var current_zoom = map.getZoom();

		    if (current_zoom < Map.zoom) {
			    data_marker = Map.zoom7;
		    } else if (current_zoom = Map.zoom) {
				data_marker = Map.zoom13;
				Map.loadMapLabel(0);
			} else if (current_zoom >= Map.markerZoom) {
			    data_marker = Map.zoom12;
				Map.loadMapLabel(0);
		    }

	    	$.each(data_marker,function(i,e){
		      	e.marker.setMap(Map.map);
		      	Map.markers.push(e.marker);
	    	});

	    	// Please don't delete code below
			//
			// if (map.getZoom() == 12) {
			// 	 map.addListener('idle', function(){
			//         // radarSearch --------------------------------------------
			// 		var contentSearch = {
			// 			bounds: map.getBounds(),
			// 			keyword: 'school',
			// 			types: ['school','university']
			// 		};
			// 		Map.placeSearch(contentSearch, 'uni', 50);
            //
			//         var contentSearch = {
			//        		bounds: map.getBounds(),
			//         	keyword: 'Town Hall', // City Government Hall/Center/Town hall
			//         	types: ['local_government_office', 'courthouse', 'city_hall']
			//         };
			//         Map.placeSearch(contentSearch, 'gov', 50);
			//     });
			// } else {
			//     google.maps.event.clearListeners(map, 'idle');
			// }
	  	},

	  	placeSearch: function(contentSearch, type, timeDeplay){
	  		var service = new google.maps.places.PlacesService(Map.map);
			service.radarSearch(contentSearch, function(results, status){
				if (status === google.maps.places.PlacesServiceStatus.OK) {
					infowindow = new google.maps.InfoWindow();
					for (var i = 0; i < results.length; i++) {
						setTimeout(Map.getZipcodeAddress(service, results[i], Map.map, type, results[i].geometry.location.lat(), results[i].geometry.location.lng(), results[i].place_id), timeDeplay);
					}
				}
			});
		},
	  	//Custom arrow hover popup
	  	CustomArrowPopup: function(){
	  		var iwOuter = $('.gm-style-iw');
	  		var iwBackground = iwOuter.prev();
	        var arrow = iwBackground.children(':nth-child(3)');
	        var arrow_left = arrow.children(':nth-child(1)');
	        var arrow_right = arrow.children(':nth-child(2)');

	        iwBackground.children(':nth-child(1)').css({'top': 340}).hide();
	        arrow_left.find('div').css({'top':14,'left': 11,'height': 10, 'width': 5});
	        arrow_right.find('div').css({'top':14,'left': 0,'height': 10, 'width': 5});
	  	},

	  	initializeMarker: function(e, map, currentZoom){
	  		var text_below, marker;

	  		text_below = "<span>" + e.zip_code + " " + ((e.office != null) ? e.office : e.name) + "</span>";

	      	if(e.topic && e.topic.length > 0 && e.trending_hashtag.length > 0) {
	      		text_below += "<br>" + e.topic[0].name + "<br>#" + e.trending_hashtag[0].hashtag_name;
	      	}

			/*marker = new google.maps.Marker({
				position: new google.maps.LatLng(e.lat, e.lng),
				map: map,
				icon: baseUrl + e.mapicon,
				city_id: parseInt(e.id)
				// label: text_below
			});*/

			var markerContent = "<div class='marker'></div>";

			if(e.office_type == 'university') {
				markerContent += "<span class='marker-icon marker-university'><i class='fa fa-lg fa-graduation-cap'></i>";
			} else if(e.office_type == 'government') {
				markerContent += "<span class='marker-icon marker-government'><i class='fa fa-lg fa-institution'></i>";
			} else {
				markerContent += "<span class='marker-icon marker-social'><i class='fa fa-lg fa-users'></i>";
			}

			markerContent += "</span><div class='marker-shadow'></div>";

	      	marker = new RichMarker({
		        position: new google.maps.LatLng(e.lat, e.lng),
		        map: map,
				content: markerContent,
		        city_id: parseInt(e.id)
		        // label: text_below
	      	});

	      	var label = new Label({
		       map: map,
		       text: text_below,
		       cid: parseInt(e.id)
		    });

		    label.bindTo('position', marker, 'position');

	      	if(!isMobile){
	            var marker_template = _.template($( "#maker_popup" ).html());
	    		var content = marker_template({marker: e});

				var infowindow = new google.maps.InfoWindow({
					content: content,
					city_id: e.id,
					maxWidth: 310,
					pixelOffset: new google.maps.Size(0,0),
				});

	            Map.infowindow.push(infowindow);

		        google.maps.event.addListener(marker, 'mouseover', function() {
			        // infowindow.setContent(e[0]);
					Map.mouseIn = false;
			        clearTimeout(Map.timeout);
			        infowindow.open(Map.map, this);

					var iw_container = $(".gm-style-iw").parent();
					iw_container.stop().hide();
					iw_container.fadeIn(800);

			        Map.onhoverInfoWindow(e.id,marker);
			        Map.OnEventInfoWindow(e);
		        });

		        google.maps.event.addListener(marker, 'mouseout', function() {
		        	Map.timeout = setTimeout(function(){
						Map.closeAllInfoWindows();
					}, 400);
		        });

	          	google.maps.event.addListener(infowindow, 'domready', function() {
		        	// Reference to the DIV that wraps the bottom of infowindow
		            var iwOuter = $('.gm-style-iw');
					// Since this div is in a position prior to .gm-div style-iw.
					// * We use jQuery and create a iwBackground variable,
					// * and took advantage of the existing reference .gm-style-iw for the previous div with .prev().
					iwOuter.parent().css({'max-width': 310});
		            var iwBackground = iwOuter.prev();
		            iwOuter.children(':nth-child(1)').css({'max-width' : '310px'});
		        	// Removes background shadow DIV
		            iwBackground.children(':nth-child(2)').css({'display' : 'none'});
		        	// Removes white background DIV
		            iwBackground.children(':nth-child(4)').css({'display' : 'none'});
		            //Custom arrow hover popup
		            Map.CustomArrowPopup();
		        	// Changes the desired tail shadow color.
		            iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': '#eee 0px 0px 0px 0px', 'z-index' : '2'});
		        	// Reference to the div that groups the close button elements.
		            var iwCloseBtn = iwOuter.next();
		            iwCloseBtn.hide();

		            var post = $("#iw-container .iw-content .iw-subTitle .post-title");
		            post.unbind();
		         	post.click(function(ev){
		            	var post_id = e.post.post_id;
			            if(post_id != -1) {
				            Post.params.city = e.id;
				            Post.params.city_name = e.name;
				            Post.params.topic = e.post.topic_id;
	                        PopupChat.params.post = post_id;
	                        PopupChat.initialize();
			            }
	          		});
	          	});
	      	}

			google.maps.event.addListener(marker, 'click', (function(marker){
		        return function(){
		        	sessionStorage.lat = marker.position.lat();
		        	sessionStorage.lng = marker.position.lng();
		        	if(!isMobile){
		            	Map.timeout = setTimeout(function(){infowindow.close();}, 3000);
		          	}
		          	Topic.initialize(marker.city_id);

					if(UserLogin) {
						//create log: city view by user
						var params = {'type': 'city','event': 'CITY_VIEW', 'user_id': UserLogin, 'city_id': marker.city_id};
						Log.create(params);
					}
		        };
		    })(marker));

			if (map == null) {
				if (currentZoom < Map.zoom ) {
					Map.zoom7.push({
						marker: marker
						// label: label
					});
				}else {
					Map.zoom12.push({
						marker: marker,
						label: label
					});
				}
			}
			else {
				Map.markers.push(marker);
			}
	  	},
		mouseInsideInfoWindow: function() {
			clearTimeout(Map.timeout);
			Map.mouseIn = true;
		},
		mouseOutsideInfoWindow: function() {
			if(Map.mouseIn) {
				Map.closeAllInfoWindows();
				Map.mouseIn = false;
			}
		},
		closeAllInfoWindows: function() {
			for (var i=0;i < Map.infowindow.length;i++) {
				Map.infowindow[i].close();
			}
		},
	  	CustomArrowPopup: function(){
	  		var iwOuter = $('.gm-style-iw');
	  		var iwBackground = iwOuter.prev();
	        var arrow = iwBackground.children(':nth-child(3)');
	        var arrow_left = arrow.children(':nth-child(1)');
	        var arrow_right = arrow.children(':nth-child(2)');

	        iwBackground.children(':nth-child(1)').css({'top': 340}).hide();
	        arrow_left.find('div').css({'top':14,'left': 11,'height': 10, 'width': 5});
	        arrow_right.find('div').css({'top':14,'left': 0,'height': 10, 'width': 5});
	  	},

	  	update_marker: function(city){
		    var marker, json, data_marker, text_below;
		    var markerSize = {
				x: 32,
				y: 60
			};

			$(".cid-"+city).remove();
			for(i=0; i<Map.markers.length; i++){
				if(Map.markers[i].city_id == city){
					Map.markers[i].setMap(null);
				}
			}

	      	Ajax.get_marker_update(city).then(function(data){
		        data_marker = $.parseJSON(data);
	      	});

	      	Map.initializeMarker(data_marker, Map.map, Map.map.getZoom());
	  	},
	  	// Begin code for get university and government place
	  	// Please don't delete it
	  	checkPlaceZipcode: function(zipcode, place_name, place, service, map, type, map_data){
	    	var params = {'zipcode':zipcode, 'place_name':place_name, 'type': type};
	    	Ajax.place_check_zipcode_exist(params).then(function(data){
		        var json = $.parseJSON(data);
		        if (json.status == 0){
					var len = map_data.length;

					for(var i=0; i<len; i++) {
						if(map_data[i].types[0] == 'administrative_area_level_1') {
							var state = map_data[i].long_name;
							var stateAbbr = map_data[i].short_name;
						}

						if(map_data[i].types[0] == 'administrative_area_level_2') {
							var str = map_data[i].long_name;
							var county = str.replace(' County', '');
						}
					}

		        	Map.placeSave(zipcode, json.city_name, place.geometry.location.lat(), place.geometry.location.lng(), place_name, type, place, map, service, state, stateAbbr, county);
		        }else{
		        }
	      	});
	  	},

	  	placeSave: function(zipcode, netwrk_name, lat, lng, office, type, place, map, service, state, stateAbbr, county){
		    var params;
		    if(type == 'gov'){
		    	params = {'zip_code':zipcode, 'netwrk_name':netwrk_name, 'lat':lat, 'lng':lng, 'office':office, 'office_type':'government', 'state' : state, 'stateAbbr' : stateAbbr, 'county' : county};
		    } else {
		    	params = {'zip_code':zipcode, 'netwrk_name':netwrk_name, 'lat':lat, 'lng':lng, 'office':office, 'office_type':'university', 'state' : state, 'stateAbbr' : stateAbbr, 'county' : county};
		    }
	    	Ajax.new_place(params).then(function(data){
				var js = $.parseJSON(data);
				if (type == 'gov') {
					Map.createMarker(service, place, map, zipcode, office, js, type);
				} else {
					Map.createMarker(service, place, map, zipcode, office, js, type);
				}
	    	});
	  	},

	  	createMarker: function(service, place, map, zipcode, name_of_place, cid, type) {
		    var placeLoc = place.geometry.location;
		    var img;

		    if (type == 'gov') {
				img = './img/icon/map_icon_government_v_2.png';
			} else {
				img = './img/icon/map_icon_university_v_2.png';
			}
		    
		    var marker = new google.maps.Marker({
				map: map,
				position: place.geometry.location,
				icon: img,
				city_id: cid,
				place_name: name_of_place,
	    	});

	      	google.maps.event.addListener(marker, 'click', (function(marker) {
				return function(){
					if(!isMobile){
						Map.timeout = setTimeout(function(){infowindow.close();}, 3000);
					}
					Topic.init(marker.city_id);
				};
		    })(marker));

	      	var content = '<div id="iw-container" >' +
	                '<div class="iw-title"><span class="toppost">Top Post</span><a class="info_zipcode" data-city="'+ cid +'" onclick="Map.eventOnClickZipcode('+ cid +')"><span class="zipcode">'+ zipcode + '</span></a></div>' +
	                '<div class="iw-content">' +
	                  '<div class="iw-subTitle">#'+''+'</div>' +
	                  '<p>'+''+'</p>'+
	                '</div>' +
	                '<div class="iw-bottom-gradient"></div>' +
	              '</div>';

			var infowindow = new google.maps.InfoWindow({
				content: content,
				city_id: cid,
				maxWidth: 350
			});

	        Map.infowindow.push(infowindow);

		    google.maps.event.addListener(marker, 'mouseover', function() {
				infowindow.open(map, this);
				Map.onhoverInfoWindow(cid,marker);
		    });

		    google.maps.event.addListener(marker, 'mouseout', function() {
		    	Map.timeout = setTimeout(function(){infowindow.close();}, 3000);
		    });

			google.maps.event.addListener(infowindow, 'domready', function() {
				var iwOuter = $('.gm-style-iw');

				var iwBackground = iwOuter.prev();
				iwOuter.children(':nth-child(1)').css({'max-width' : '400px'});
				// Removes background shadow DIV
				iwBackground.children(':nth-child(2)').css({'display' : 'none'});

				//   // Removes white background DIV
				iwBackground.children(':nth-child(4)').css({'display' : 'none'});

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

				//   // The API automatically applies 0.7 opacity to the button after the mouseout event. This function reverses this event to the desired value.
				iwCloseBtn.mouseout(function(){
				  $(this).css({opacity: '0'});
				});
			});

	      	Map.markers.push(marker);
	  	},

	  	getZipcodeAddress: function(service, place, map, type, lat, lng, name_of_place){
			$.getJSON("https://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + ',' + lng, function (data) {
				if(typeof data.results[0] != 'undefined') {
					var len = data.results[0].address_components.length;
					var map_data = data.results[0].address_components;
					for (var i = 0; i < len; i++) {
						if (data.results[0].address_components[i].types[0] == 'postal_code') {
							var zip = data.results[0].address_components[i].long_name;
							service.getDetails(place, function (_place, status) {
								if (status === google.maps.places.PlacesServiceStatus.OK) {
									Map.checkPlaceZipcode(zip, _place.name, place, service, map, type, map_data);
								}
							});
						}
					}
				}
			});
	  	},
	  	// End code for get university and government place
	  	OnEventInfoWindow: function(e){
	  		Map.OnCreateFirstTopic(e);
	  		Map.OnEventTopTopic(e);
	  		Map.OnEventTopPost(e);
	  	},

	  	OnEventTopPost: function(e){
	  		var parent = $('.container-popup').find('.top-post-trending .item-post .name-post');
	  		parent.unbind();
	  		parent.on('click',function(d){
	            PopupChat.params.post = e.post.post_id;
	            PopupChat.params.chat_type = e.post.post_type;
	            PopupChat.params.post_name = e.post.name_post;
	            PopupChat.params.post_description = e.post.content;
	            ChatInbox.params.target_popup = $('.popup_chat_modal #popup-chat-'+PopupChat.params.post);
	            PopupChat.initialize();
	  		});
	  	},

	  	OnEventTopTopic: function(e){
	  		var parent = $('.container-popup').find('.top-topic .name-topic');
	  		// Topic.initialize(city);
	  		parent.unbind();
	  		parent.on('click',function(d){
	  			var topic = e.topic[$(d.currentTarget).attr('data-index')];
	            Post.params.topic = topic.id;
	            Post.params.topic_name = topic.name;
	            Post.params.city = topic.city;
	            Post.params.city_name = topic.city_name;
	            Post.initialize();
	  		});
	  	},

	  	OnCreateFirstTopic: function(e){
	  		var parent = $('.container-popup').find('.create-topic');

	  		parent.unbind();
	  		parent.on('click',function(){
	  			if(isGuest){
	  				Login.modal_callback = Create_Topic;
	  				Create_Topic.params.city = e.id;
	  				Create_Topic.params.city_name = e.zip_code;
	  				Create_Topic.initialize();
	  			}else{
	  				Create_Topic.initialize(e.id,e.zip_code);
	  			}
	  		});
	  	},

	  	eventClickMyLocation: function(map){
		    var btn = $('#btn_my_location');
		    btn.unbind();
		    btn.on('click',function(){
		      	if (isGuest) {
			        navigator.geolocation.getCurrentPosition(function(position) {
			        var pos = {
			            lat: position.coords.latitude,
			            lng: position.coords.longitude
			        	};
			            map.setCenter(new google.maps.LatLng(pos.lat, pos.lng));
			            if(map.getZoom() < Map.markerZoom) {
			            	map.setZoom(Map.markerZoom);
			        	}else{
			        		map.setZoom(18);
			        	}
			        });
		      	} else {
			        var zoom_current = map.getZoom();
			        if (zoom_current < Map.markerZoom) {
				        Map.smoothZoom(map, Map.markerZoom, zoom_current, true);
				        map.zoom = Map.markerZoom;
			        }else{
			        	Map.smoothZoom(map, 18, zoom_current, true);
				        map.zoom = 18;
			        }

			        Ajax.get_position_user().then(function(data){
				        var json = $.parseJSON(data);
				        map.setCenter(new google.maps.LatLng(json.lat, json.lng));
			        });
		      	}
		    });
	  	},

		findCurrentZip: function(lat, lng) {
			$.getJSON("https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lng ,function(data) {
				var len = data.results[0].address_components.length;
				for (var i = 0; i < len; i++) {
					if (data.results[0].address_components[i].types[0] == 'postal_code') {
						// console.log(data);
						var zip = data.results[0].address_components[i].long_name;
						console.log("cmzip: ", $("#cm-zip").html());
						$("#cm-zip span").eq(0).html(zip);
					}
				}
			});
		},

		requestPositionFunction: function(map) {
			if (map.getZoom() != Map.zoomBlueDot) {
				if (typeof Map.center_marker != "undefined" && Map.center_marker != null) {
					Map.center_marker.setMap(null);
				}
				return;
			}
			Map.show_marker_group_loc(map);
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function(position) {

					map.setCenter(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));

					if (Map.center_marker != null) Map.center_marker.setMap(null);

					var img = '/img/icon/pale-blue-dot.png';
					Map.center_marker = new google.maps.Marker({
						position: new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
						map: map,
						icon: img,
						draggable: true
						//city_id: parseInt(e.id)
					});

					google.maps.event.addListener(Map.center_marker, 'dragstart', function(e) {
						Map.center_marker.setIcon('/img/icon/pale-blue-dot-bg.png');
					});

					google.maps.event.addListener(Map.center_marker, 'dragend', function(e) {
						Map.center_marker.setIcon('/img/icon/pale-blue-dot.png');
						Map.findCurrentZip(Map.center_marker.getPosition().lat(),
							Map.center_marker.getPosition().lng());
					});



					var content = '<div id="iw-container" class="cgm-container" >' +
						'<div class="iw-content">' +
						/*'<div class="iw-subTitle" id="cm-coords"></div>' +*/
						/*'<div class="iw-subTitle" id="cm-zip">Zip: <span>requesting...</span></div>' +*/
						'<div class="iw-subTitle"><span class="post-title">' +
							'<a id="create-location-group" class="a-create-group" href="javascript:" onclick="Map.CreateLocationGroup();"><h4>Have a local</h4></a>' +
						'</span></div>' +
						'</div>' +
						'<div class="iw-bottom-gradient"></div>' +
						'</div>';

					var infowindow = new google.maps.InfoWindow({
						content: content
					});

					Map.infowindow.push(infowindow);

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

						//   // Reference to the div that groups the close button elements.
						var iwCloseBtn = iwOuter.next();

						//   // Apply the desired effect to the close button
						iwCloseBtn.css({opacity: '0', right: '135px', top: '15px', border: '0px solid #477499', 'border-radius': '13px', 'box-shadow': '0 0 0px 2px #477499','display':'none'});
					});

					google.maps.event.addListener(Map.center_marker, 'mouseover', function() {
						// infowindow.setContent(e[0]);
						Map.findCurrentZip(position.coords.latitude, position.coords.longitude);
						infowindow.open(map, this);
						var lat = parseFloat(Map.center_marker.getPosition().lat());
						var lng = parseFloat(Map.center_marker.getPosition().lng());
						var dec = (lat - Math.floor(lat)) * 60;
						var latGrad = Math.round(lat) + "&deg; " + Math.round(dec) + "' " + Math.round((dec - Math.floor(dec)) * 60) + "''";
						var dec2 = (lng - Math.floor(lng)) * 60;
						var lngGrad = Math.round(lng) + "&deg; " + Math.round(dec2) + "' " + Math.round((dec2 - Math.floor(dec2)) * 60) + "''";
						$('#cm-coords').html(latGrad + "<br>" + lngGrad);
					});

					google.maps.event.addListener(Map.center_marker, 'click', (function() {
						return function(){
							if(!isMobile){
								infowindow.close();
							}
						};
					})(Map.center_marker));

					google.maps.event.addListener(Map.center_marker, 'dragstart', function() {
						console.log("stopping", Map.requestPosTimeout);
						if (Map.requestPosTimeout != null) clearTimeout(Map.requestPosTimeout);
					});

					//Map.markers.push(marker);
					console.log("Latitude: " + position.coords.latitude, "Longitude: " + position.coords.longitude);

					console.log(map.getZoom());

				});
			} else {
				console.log("Geolocation is not supported by this browser.");
			}
		},

		createZipLabelMarker: function(cid,name_of_place,zipCode,zipLat,zipLng) {
			var markerContent = "<div class='marker-zip-code'>"+zipCode+"</div>";

			var marker = new RichMarker({
				map: Map.map,
				position: new google.maps.LatLng(zipLat, zipLng),
				content: markerContent,
				city_id: parseInt(cid),
				place_name: name_of_place,
				zIndex: 9999
			});

			/*var marker = new google.maps.Marker({
				map: Map.map,
				position: new google.maps.LatLng(zipLat, zipLng),
				icon: 'http://dummyimage.com/50x30/5888ac/ffffff&text='+zipCode,
				city_id: parseInt(cid),
				place_name: name_of_place,
				zIndex: 9999
			});*/

			google.maps.event.addListener(marker, 'click', (function(marker){
				return function(){
					sessionStorage.lat = marker.position.lat();
					sessionStorage.lng = marker.position.lng();

					Topic.initialize(marker.city_id);

					if(UserLogin) {
						//create log: city view by user
						var params = {'type': 'city','event': 'CITY_VIEW', 'user_id': UserLogin, 'city_id': marker.city_id};
						Log.create(params);
					}
				};
			})(marker));

			Map.zoom13.push({
				marker: marker
			});
		},

		clearZipLabelMarker: function(){
			// Remove the zip code label markers
			for (var i = 0; i < Map.zoom13.length; i++) {
				var m = Map.zoom13[i];
				m.marker.setMap(null);
			}
		},

		requestPosition: function(map) {
			Map.requestPosTimeout = setTimeout(function() {
				Map.requestPositionFunction(map);
			}, 30000);
			Map.requestPositionFunction(map);
			google.maps.event.addListener(map, 'bounds_changed', function() {
				console.log(map.getZoom());
				Map.requestPositionFunction(map);
			});

			google.maps.event.addListener(map, 'idle', function(){
				var currentZoom = map.getZoom();

				var bounds = Map.map.getBounds(),
						ne = bounds.getNorthEast(),
						sw = bounds.getSouthWest();

				var params = {'swLat':sw.lat(), 'swLng':sw.lng(), 'neLat': ne.lat(), 'neLng':ne.lng()};

				if(currentZoom >= Map.markerZoom ){
					Ajax.get_marker_zoom(params).then(function(data){
						var data_marker = $.parseJSON(data);
						$.each(data_marker,function(i,e){
							Map.initializeMarker(e, null, Map.markerZoom);
						});
					});

					Map.mouseOutsideInfoWindow();
					//Map.deleteNetwrk(map);
					Map.loadMapLabel(0);
					for (var i = 0; i < Map.zoom12.length; i++) {
						var m = Map.zoom12[i];
						m.marker.setMap(map);
						Map.markers.push(m.marker);
					}
				}

				if(currentZoom >= Map.zoom){
					Ajax.getVisibleZipBoundaries(params).then(function(jsonData){
						var out = $.parseJSON(jsonData);

						for (var key in out) {
							if (out.hasOwnProperty(key)) {
								Map.map.data.addGeoJson(out[key]);

								Map.map.data.setStyle(function(feature) {
									if(feature.H.type == 'selected' || feature.H.type == 'Followed') {
										return /** @type {google.maps.Data.StyleOptions} */({
											fillColor: '#5888ac',
											fillOpacity: Map.fillOpacity,
											strokeColor: '#5888ac',
											strokeWeight: 2
										});
									} else {
										return /** @type {google.maps.Data.StyleOptions} */({
											fillColor: '#5888ac',
											fillOpacity: 0.0,
											strokeColor: '#5888ac',
											strokeWeight: 2
										});
									}
								});

								if(currentZoom == Map.zoom){
									for(var featureKey in out[key].features) {
										var cid = out[key].features[featureKey].properties.id,
												name_of_place = out[key].features[featureKey].properties.city,
												zipCode = out[key].features[featureKey].properties.zipCode,
												zipLat = out[key].features[featureKey].properties.lat,
												zipLng = out[key].features[featureKey].properties.lng;

										Map.createZipLabelMarker(cid,name_of_place,zipCode,zipLat,zipLng);
									}
								} else {
									Map.clearZipLabelMarker();
								}
							}
						}
					});
				} else {
					map.data.forEach(function(feature) {
						if(feature.H.type != 'selected'){
							map.data.remove(feature);
						}
					});
				}
			});
		},

		CreateLocationGroup: function() {
			var lat = Map.center_marker.getPosition().lat();
			var lng = Map.center_marker.getPosition().lng();
			Create_Group.initialize(null, null, null, null, true, lat, lng);
		},

		show_marker_group_loc: function(map, groupId) {
			var marker,json,data_marker;

			Ajax.get_marker_groups_loc(typeof groupId != "undefined" ? groupId : null).then(function(data){
				console.log('get marker group loc');
				data_marker = $.parseJSON(data);
				//console.log(data_marker);
				$.each(data_marker,function(i,e){
					/*var img = '/img/icon/map_icon_community_v_2.png';

					marker = new google.maps.Marker({
						position: new google.maps.LatLng(e.lat, e.lng),
						map: map,
						icon: img,
						group_id: parseInt(e.id)
					});*/

					var markerContent = "<div class='marker marker-group'></div>"+
										"<span class='marker-icon marker-social'><i class='fa fa-lg fa-users'></i>"+
										"</span><div class='marker-shadow'></div>";

					marker = new RichMarker({
						position: new google.maps.LatLng(e.lat, e.lng),
						map: map,
						content: markerContent,
						group_id: parseInt(e.id)
					});

					//console.log("marker", marker);
					google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function(){
							console.log(marker.group_id);
							Group_Loc.initialize(marker.group_id);
							if(!isMobile){
								if (typeof infowindow != "undefined") {
									infowindow.close();
								}
							}
						};
					})(marker, i));

					if (!isMobile && 1==0){
						var content = '<div id="iw-container" >' +
							'<div class="iw-title"><span class="toppost">Top Post</span><a class="info_zipcode" data-city="'+ e.id +'" onclick="Map.eventOnClickZipcode('+e.id +')"><span class="zipcode">'+ e.zip_code + '</span></a></div>' +
							'<div class="iw-content">' +
							'<div class="iw-subTitle"><span class="post-title">#'+e.post.name_post+'</span></div>' +
							'<p>'+e.post.content+'</p>'+
							'</div>' +
							'<div class="iw-bottom-gradient"></div>' +
							'</div>';

						var infowindow = new google.maps.InfoWindow({
							content: content,
							group_id: e.id,
							maxWidth: 350
						});

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

							var post = $("#iw-container .iw-content .iw-subTitle .post-title");
							post.unbind();
							post.click(function(ev){
								var post_id = e.post.post_id;
								if(post_id != -1) {
									//Post.params.city = e.id;
									//Post.params.city_name = e.name;
									Post.params.topic = e.post.topic_id;
									ChatPost.params.post = post_id;
									ChatPost.initialize();
								}
							});
						});
					}
					Map.markers.push(marker);
				});
			});
		},

	  	eventZoom: function(map){
		    var mode = true;
		    map.addListener('dblclick', function(event){
				if(map.getZoom() == 7 || (map.getZoom() > 7 && map.getZoom() < Map.markerZoom)){
					Map.smoothZoom(map, Map.markerZoom, map.getZoom() + 1, true);
					map.zoom = Map.markerZoom;
					if(isMobile){
						sessionStorage.map_zoom = Map.markerZoom;
					}
				} else if(map.getZoom() == Map.markerZoom || (map.getZoom() > Map.markerZoom && map.getZoom() < 18)){
					Map.smoothZoom(map, 18, map.getZoom() + 1, true);
					map.zoom = 18;
					if(isMobile){
					    sessionStorage.map_zoom = 18;
					}
				}
			});

			map.addListener('zoom_changed', function(){
				var data_marker;
				var currentZoom = map.getZoom();
				if(isMobile){
				    sessionStorage.map_zoom = currentZoom;
				}
				if(currentZoom == 18){
	    			Map.map.setOptions({zoomControl: false, scrollwheel: true, styles: null});
	    		} else {
	    			var remove_poi = Map.remove_poi;
				    Map.map.setOptions({zoomControl: false, scrollwheel: true, styles: remove_poi});
	    		}
	    		if (currentZoom == Map.markerZoom && Map.markers.length <= 10) {
	    			Map.deleteNetwrk(map);
				    Map.loadMapLabel(0);
					for (var i = 0; i < Map.zoom12.length; i++) {
						var m = Map.zoom12[i];
						m.marker.setMap(map);
					    Map.markers.push(m.marker);
				    }
				} else if(currentZoom == Map.zoom ){
					Map.deleteNetwrk(map);
					Map.hideMapLabel();
					for (var i = 0; i < Map.zoom13.length; i++) {
						var m = Map.zoom13[i];
						m.marker.setMap(map);
						Map.markers.push(m.marker);
					}
				} else if(currentZoom < Map.zoom ){
					Map.deleteNetwrk(map);
					Map.hideMapLabel();
					Map.clearZipLabelMarker();

					for (var i = 0; i < Map.zoom7.length; i++) {
						var m = Map.zoom7[i];
						m.marker.setMap(map);
						Map.markers.push(m.marker);
					}
				} else if (currentZoom == 11 && Map.markers.length > 10) {
	    			Map.deleteNetwrk(map);
					Map.hideMapLabel();		
					for (var i = 0; i < Map.zoom7.length; i++) {
						var m = Map.zoom7[i];	
						m.marker.setMap(map);
					    Map.markers.push(m.marker);
				    }
	    		}
			});
	  	},

	  	loadMapLabel: function(offset) {
	  		var endOffset = offset + 200;
	  		if (endOffset > Map.zoom12.length) {
	  			endOffset = Map.zoom12.length;
	  		}
	  		var m = {};
	  		for (i=offset; i<endOffset; i++) {
	  			m = Map.zoom12[i];
  				m.label.setMap(Map.map);
	  		}
	  		if (endOffset<Map.zoom12.length) {
	  			Map.timeout = setTimeout(Map.loadMapLabel, 200, endOffset);
	  		}
	  	},

	  	hideMapLabel: function() {
	  		clearTimeout(Map.timeout);
	  		for (i=0; i<Map.zoom12.length; i++) {
	  			m = Map.zoom12[i];
  				m.label.setMap(null);
	  		}
	  	},

	  	smoothZoom: function(map, level, cnt, mode) {
		    // If mode is zoom in
		    if(mode == true) {
			    if (cnt > level) {
			        Map.incre = 1;
			        return;
			    } else {
			        var z = google.maps.event.addListener(map, 'zoom_changed', function(event){
			          google.maps.event.removeListener(z);
			          Map.smoothZoom(map, level, cnt + 1, true);          
			        });
		        	setTimeout(function(){map.setZoom(cnt)}, 50);
					// if (Map.incre < 2) {
					// 	Map.incre++;
					// } else {
					// 	Map.incre = 2;
					// }
		      	}
		    } else {
			    if (cnt < level) {
			        Map.incre = 1;
			        return;
			    } else {
			        var z = google.maps.event.addListener(map, 'zoom_changed', function(event) {
			        	google.maps.event.removeListener(z);
			         	Map.smoothZoom(map, level, cnt - 1, false);
			        });
		        	setTimeout(function(){map.setZoom(cnt)}, 110);
					// if (Map.incre < 2)
					// 	Map.incre++;
					// else {
					// 	Map.incre = 1;
					// }
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
		    mapType.maxZoom = 18;  //It doesn't work with SATELLITE and HYBRID maptypes.
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
				if (map.getZoom() == Map.markerZoom ){
					Map.geocoder(e.latLng);
				}
				Map.latLng = e.latLng;
			});
	  	},

		eventOnclickMarker: function(){
		},

	  	eventOnClickZipcode: function(city){
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
	    },

	    insertLocalUniversity: function(){
	    	Ajax.insert_local_university().then(function(data){
	    	});
	    },

	    insertLocalGovernment: function(){
	    	Ajax.insert_local_government().then(function(data){
	    	});
	    },

		/* Set map position center using lat and lng do zoom */
		SetMapCenter: function(lat,lng,zoom) {
			zoom = zoom || Map.zoom;
			lat = lat || '39.7662195';
			lng = lng || '-86.441277';
			if (zoom && zoom != 'undefined') {
				Map.map.setZoom(zoom);
			}
			Map.map.setCenter(new google.maps.LatLng(lat, lng));
		},

		showZipBoundaries: function() {
			var params = {};
			Ajax.getZipBoundaries(params).then(function(jsonData){
				var out = $.parseJSON(jsonData);

				for (var key in out) {
					if (out.hasOwnProperty(key)) {
						//console.log(out[key]);
						Map.map.data.addGeoJson(out[key]);

						//styled map
						Map.map.data.setStyle({
							fillColor: '#5888ac',
							fillOpacity: Map.fillOpacity,
							strokeColor: '#5888ac',
							strokeWeight: 2
						});


						if(Map.map.getZoom() == Map.zoom){
							for(var featureKey in out[key].features) {
								var cid = out[key].features[featureKey].properties.id,
										name_of_place = out[key].features[featureKey].properties.city,
										zipCode = out[key].features[featureKey].properties.zipCode,
										zipLat = out[key].features[featureKey].properties.lat,
										zipLng = out[key].features[featureKey].properties.lng;

								Map.createZipLabelMarker(cid,name_of_place,zipCode,zipLat,zipLng);
							}
						} else {
							Map.clearZipLabelMarker();
						}
					}
				}
			});
		}
	};