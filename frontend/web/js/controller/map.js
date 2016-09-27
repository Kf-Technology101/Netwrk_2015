	var Map ={
	  	params:{
		    name:'',
		    zipcode:'',
		    lat:'',
		    lng:''
	  	},
		getMaxMarker: true,
		displayBlueDot: true,
		displayUserLocationInfo: true,
	  	latLng: '',
	  	markers:[],
		topicMarkers: [],
		groupMarkers: [],
		lineMarkers: [],
	  	data_map:'',
	  	infowindow:[],
		infoWindowBlueDot:[],
	  	zoomIn: false,
	  	incre: 1,
	  	map:'',
	  	zoom: 16,
		markerZoom:14,
		// Blue dot info window content is in following file
		// @frontend/modules/netwrk/views/marker/blue_dot_post_content
		blueDotInfoWindowContent: $('#blueDotInfoWindow').html(),
	  	// center: new google.maps.LatLng(39.7662195,-86.441277),
	  	center:'',
	  	zoom7: [],
	  	zoom12: [],
		zoom13: [],
		fillOpacity: 0.3,
	  	timeout: '',
		timeoutZoom7: '',
		zoomBlueDot: 18,
		blueDotMarker: [],
		blueDotLocation: {
			lat: '',
			lon: '',
			nearByDefaultZoom: 12,
			blueMarkerZoom: 14,
			zoomInitial: 13,
			zoomMiddle: 16,
			zoomLast: 18,
			zipcode: '',
			timeout: '',
			community: ''
		},
		clickLocation: {
			lat: '',
			lon: '',
			zipCode: ''
		},
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
		setCreateInfoWindow: function() {
			// Blue dot info window content is in following file
			// @frontend/modules/netwrk/views/marker/blue_dot_post_content
			var createInfoWindowContent = $('#createInfoWindow').html();

			var infoWindow = new google.maps.InfoWindow({
				content: createInfoWindowContent
			});

			return infoWindow;
		},
		clickLocationCreateGroup: function(zipcode) {
			var lat = Map.clickLocation.lat;
			var lng = Map.clickLocation.lng;

			if(isMobile) {
				if(zipcode){
					//window.location.href = baseUrl + "/netwrk/topic/create-topic?city=null&zipcode="+zipcode+"&name=null&lat="+lat+"&lng="+lng+"&isCreateFromBlueDot=true";
					window.location.href = baseUrl + "/netwrk/group/create-group?city=null&zipcode="+zipcode+"&name=null&lat="+lat+"&lng="+lng+"&isCreateFromBlueDot=true";
				}
			} else {
				Create_Group.initialize(null, null, zipcode, null, true, lat, lng);
			}
		},
		clickLocationCreateTopic: function(zipcode) {
			var lat = Map.clickLocation.lat;
			var lng = Map.clickLocation.lng;

			Create_Topic.showCreateTopicModal(zipcode, lat, lng);
		},
	  	initialize: function() {

	  		if(isMobile){
				Map.zoom = 16;
				Map.markerZoom = 13;

				Common.initTextLoader();
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
				/*Ajax.get_marker_default().then(function(data){
					var data_marker = $.parseJSON(data);
					$.each(data_marker,function(i,e){
				      	Map.initializeMarker(e, null, 7);
			    	});
				});*/

				//Map.mapBoundaries(Map.map);
				Map.eventZoom(Map.map);
				Map.eventClickMyLocation(Map.map);
				if(User.location.lat && User.location.lng) {
					Map.showUserLocationMarker(Map.map);
				}
				Map.show_marker(Map.map);
				Map.showHeaderFooter();
				Map.mouseOutsideInfoWindow();
				//Map.showZipBoundaries();
				//Common.hideLoader();

				if(isMobile) {
					/*if (sessionStorage.show_blue_dot == 1) {
						Map.smoothZoom(Map.map, 14, 10, true);
						sessionStorage.show_blue_dot = 0;
						Map.getBrowserCurrentPosition(Map.map);
					}*/
				}
			});

		    // Map.insertLocalUniversity();
		    // Map.insertLocalGovernment();
			//Map.requestBlueDotOnMap(lat,lng,Map.map);
			Map.requestPosition(Map.map);
			Map.onClickMap();

			// New info window for click on map to create group and topic
			/*var createInfoWindow = Map.setCreateInfoWindow();

			google.maps.event.addListener(Map.map, 'click', function(event){
				$('.modal').modal('hide');

				// Set current position to info window
				createInfoWindow.setPosition(event.latLng);

				// Hide excess html content when info window ready
				google.maps.event.addListener(createInfoWindow, 'domready', function() {
					// Reference to the DIV that wraps the bottom of info window
					var iwOuter = $('.gm-style-iw');

					// Since this div is in a position prior to .gm-div style-iw.
					// We use jQuery and create a iwBackground variable,
					// and took advantage of the existing reference .gm-style-iw for the previous div with .prev().
					var iwBackground = iwOuter.prev();
					iwOuter.children(':nth-child(1)').css({'max-width' : '50px'});

					// Removes background shadow DIV
					iwBackground.children(':nth-child(2)').css({'display' : 'none'});

					// Removes white background DIV
					iwBackground.children(':nth-child(4)').css({'display' : 'none'});

					// Reference to the div that groups the close button elements.
					var iwCloseBtn = iwOuter.next();

					// Apply the desired effect to the close button
					iwCloseBtn.css({opacity: '0', right: '135px', top: '15px', border: '0px solid #477499', 'border-radius': '13px', 'box-shadow': '0 0 0px 2px #477499','display':'none'});
				});

				Map.clickLocation.lat = event.latLng.lat();
				Map.clickLocation.lng = event.latLng.lng();

				// Code to get zip code and city from latitude and longitude
				$.getJSON("https://maps.googleapis.com/maps/api/geocode/json?latlng="+Map.clickLocation.lat+','+Map.clickLocation.lng ,function(data) {
					var len = data.results[0].address_components.length,
						city = '';

					for (var i = 0; i < len; i++) {
						if (data.results[0].address_components[i].types[0] == 'postal_code') {
							var zip = data.results[0].address_components[i].long_name;
							Map.clickLocation.zipCode = zip;
						} else if (data.results[0].address_components[i].types[0] == 'locality') {
							city = data.results[0].address_components[i].long_name;
							$("#clickLocation span").eq(0).html(city);
						} else if (data.results[0].address_components[i].types[0] == 'administrative_area_level_2' && city == '') {
							city = data.results[0].address_components[i].long_name;
							$("#clickLocation span").eq(0).html(city);
						}
					}

					// Code to check if there is community in current zip code
					// If exist then only display option to create group and topic
					var params = {'zip_code':Map.clickLocation.zipCode};

					Ajax.getCommunitiesCountFromZip(params).then(function(data){
						var jsonData = $.parseJSON(data);

						if(jsonData.communities == 0){
							$('.ciw-container').find('#communityInfoCreate').addClass('hide');
							$('.ciw-container').find('#noCommunityCreate').removeClass('hide');
						} else {
							$('.ciw-container').find('#communityInfoCreate').removeClass('hide');
							$('.ciw-container').find('#noCommunityCreate').addClass('hide');
						}
					});
				});

				// Open info window
				createInfoWindow.open(Map.map);
			});*/

			if(sessionStorage.on_boarding == 1){
				// If user not have any lines and profile picture then display the modal
				setTimeout(function(){
					if(sessionStorage.show_profile_info == 1){
						sessionStorage.show_profile_info = 0;
						SocialSignup.initialize();
					} else {
						sessionStorage.on_boarding = 0;
						Login.showOnBoardingLines();
					}
				}, 500);
			}
			setTimeout(function() {
				Common.hideTextLoader();
			}, 500)
	  	},
		eventMapClick: function(event) {
			if(Map.displayBlueDot) {
				console.log( "Latitude: "+event.latLng.lat()+" "+", longitude: "+event.latLng.lng() );
				var lat = event.latLng.lat(),
					lng = event.latLng.lng();
				Map.requestBlueDotOnMap(lat, lng, Map.map);
			} else {
				Map.displayBlueDot = true;
			}
		},
		onClickMap: function() {
			//add map listener
			google.maps.event.addListener(Map.map, 'click', function(event) {
				Map.eventMapClick(event);
			});
			//on click of blue shaded area on map (geoJson data)
			Map.map.data.addListener('click', function(event) {
				Map.eventMapClick(event);
			});
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
				if(UserLogin) {
					var params = {'user_id': UserLogin};
					Ajax.getUserById(params).then(function(data){
						var json = $.parseJSON(data),
						 	newLat = json.data.lat,
						 	newLng = json.data.lng;
						if(newLat && newLng){
							Map.center = new google.maps.LatLng(newLat, newLng);
							if(isMobile) {
								sessionStorage.lat = newLat;
								sessionStorage.lng = newLng;
							}
							User.location.lat = newLat;
							User.location.lng = newLng;
						}else{
							Map.center = new google.maps.LatLng(lat, lng);
							if(isMobile) {
								sessionStorage.lat = lat;
								sessionStorage.lng = lng;
							}
							User.location.lat = lat;
							User.location.lng = lng;
						}
						google.maps.event.addDomListener(window, 'load', Map.initialize());
					});
				} else {
					if(lat && lng){
						Map.center = new google.maps.LatLng(lat, lng);
						if(isMobile) {
							sessionStorage.lat = lat;
							sessionStorage.lng = lng;
						}
						User.location.lat = lat;
						User.location.lng = lng;
					}else{
						Map.center = new google.maps.LatLng(39.7662195,-86.441277);
					}
					google.maps.event.addDomListener(window, 'load', Map.initialize());
				}
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

		showUserLocationMarker: function (map) {
			var lat = User.location.lat,
				lng = User.location.lng,
				showLocationInfo = true;

			var markerContent = "<div class='marker-user-location'></div>";
				markerContent += "<span class='marker-icon-user-location'><i class='fa fa-2x fa-circle'></i></span>";

			marker = new RichMarker({
				position: new google.maps.LatLng(lat,lng),
				map: map,
				content: markerContent
				//city_id: parseInt(e.id)
				// label: text_below
			});
			marker.setMap(map);

			if(userLocationInfo == 'false') {
				showLocationInfo = false;
				Map.displayUserLocationInfo = false;
			}
			else
				showLocationInfo = true;

			if(Map.displayUserLocationInfo)
				showLocationInfo = true;
			else
				showLocationInfo = false;

			if(showLocationInfo) {
				if (!typeof(userLocationInfoWindow) === "undefined"){
					userLocationInfoWindow.setMap(null);
				}

				$.getJSON("https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lng ,function(data) {
					var len = data.results[0].address_components.length;
					for (var i = 0; i < len; i++) {
						if (data.results[0].address_components[i].types[0] == 'postal_code') {
							var zip = data.results[0].address_components[i].long_name;
							User.location.zipCode = zip;
							Map.blueDotLocation.zipcode = zip;
						} else if (data.results[0].address_components[i].types[0] == 'locality') {
							var city = data.results[0].address_components[i].long_name;
							setTimeout(function(){
								$("#userLocation span").eq(0).html(city);
							},300);
						}
					}

					if(isGuest) {
						$('#userLocationInfoWindow').find('.join-content').removeClass('hide');
						$('#userLocationInfoWindow').find('.build-content').addClass('hide');
					} else {
						/*$('#userLocationInfoWindow').find('.join-content').addClass('hide');
						$('#userLocationInfoWindow').find('.build-content').removeClass('hide');*/

						var params = {'zip_code':Map.blueDotLocation.zipcode};

						Ajax.getBuildDetailFromZip(params).then(function(data){
							var buildData = $.parseJSON(data);

							if(buildData.user_follow == 'false'){
								Map.blueDotLocation.community = buildData.social_community;
								$('#userLocationInfoWindow').find('.build-content').addClass('hide');
								$('#userLocationInfoWindow').find('.join-content').removeClass('hide');
							} else {
								Map.blueDotLocation.community = '';
								$('#userLocationInfoWindow').find('.join-content').addClass('hide');
								$('#userLocationInfoWindow').find('.build-content').removeClass('hide');
							}
						});
					}

					userLocationInfoWindow = new google.maps.InfoWindow();
					var windowLatLng = new google.maps.LatLng(lat, lng);
					var content = $('#userLocationInfoWindow').html();

					userLocationInfoWindow.setOptions({
						maxWidth: 260,
						maxHeight: 100,
						content: content,
						position: windowLatLng,
					});

					userLocationInfoWindow.open(map);

					google.maps.event.addListener(userLocationInfoWindow, 'domready', function() {
						// Reposition user location marker so info window display on center
						$('.marker-user-location').parent().parent().parent().css({'left' : '-20px'});

						//   // Reference to the DIV that wraps the bottom of infowindow
						var iwOuter = $('#uiw-container').closest('.gm-style-iw');

						$('#uiw-container').css({'max-width' : '260px'});
						//    // Since this div is in a position prior to .gm-div style-iw.
						//    // * We use jQuery and create a iwBackground variable,
						//    // * and took advantage of the existing reference .gm-style-iw for the previous div with .prev().

						iwOuter.css({'max-width' : '260px', 'max-height' : '100px', 'z-index' : '999', 'box-shadow' : '2px 2px 2px'});

						var iwBackground = iwOuter.prev();
						iwOuter.children(':nth-child(1)').css({'max-width' : '260px'});

						// Removes background shadow DIV
						iwBackground.children(':nth-child(2)').css({'display' : 'none'});

						//   // Removes white background DIV
						iwBackground.children(':nth-child(4)').css({'display' : 'none'});

						//   // Moves the shadow of the arrow 76px to the left margin.
						iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'top: 174px !important;left: 192px !important;'});

						//   // Moves the arrow 76px to the left margin.
						iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'top: 174px !important;left: 192px !important;'});

						//   // Changes the desired tail shadow color.
						//iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': '#477499 0px 1px 0px 2px', 'z-index' : '1'});

						//   // Reference to the div that groups the close button elements.
						var iwCloseBtn = iwOuter.next();

						//   // Apply the desired effect to the close button
						iwCloseBtn.css({opacity: '0', right: '135px', top: '15px', border: '0px solid #477499', 'border-radius': '13px', 'box-shadow': '0 0 0px 2px #477499','display':'none'});

						//   // The API automatically applies 0.7 opacity to the button after the mouseout event. This function reverses this event to the desired value.
						iwCloseBtn.mouseout(function(){
							$(this).css({opacity: '0'});
						});
					});
				});
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

				for (var i = 0; i < Map.zoom7.length; i++) {
					var m = Map.zoom7[i];
					m.marker.setMap(map);
					Map.markers.push(m.marker);
				}
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

	  		text_below = "<span>" + ((e.office != null) ? e.office : e.name) + "</span>";

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

			if(e.office_type == 'university' || e.office_type == 'government') {
				var markerContent = "<div class='marker'></div>";
			} else {
				var markerContent = "<div class='glow-btn-wrapper'>";
					markerContent += "<div class='marker-home'>";
					markerContent += "<div class='btn-active'></div>";
					markerContent += "<div class='btn-inactive'></div></div>";
				markerContent += "<span class='marker-icon marker-home'><i class='fa fa-home'></i>";
			}

			if(e.office_type == 'university') {
				markerContent += "<span class='marker-icon marker-university'><i class='fa fa-graduation-cap'></i>";
			} else if(e.office_type == 'government') {
				markerContent += "<span class='marker-icon marker-government'><i class='fa fa-institution'></i>";
			}

			if(e.office_type == 'university' || e.office_type == 'government') {
				markerContent += "</span><div class=''></div>";
			}

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
	            /*var marker_template = _.template($( "#maker_popup" ).html());
	    		var content = marker_template({marker: e});*/

				var infowindow = new google.maps.InfoWindow({
					//content: content,
					city_id: e.id,
					maxWidth: 310,
					pixelOffset: new google.maps.Size(0,0)
				});

	            Map.infowindow.push(infowindow);

		        google.maps.event.addListener(marker, 'mouseover', function() {
			        // infowindow.setContent(e[0]);
					Map.mouseIn = false;
					clearTimeout(Map.timeout);

					if (!infowindow.getMap()) {
						var params = {'city_id': e.id};

						Ajax.get_marker_info(params).then(function (data) {
							var data_marker_info = $.parseJSON(data);

							$.each(data_marker_info, function (info_i, info_e) {

								var marker_template = _.template($("#maker_popup").html());
								var info_content = marker_template({marker: info_e});
								infowindow.setContent(info_content);
							});
						});

						infowindow.open(Map.map, marker);
						//todo: open only current popup
						var iw_container = $(".gm-style-iw").parent();
						iw_container.stop().hide();
						iw_container.fadeIn(400);

					}


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
					Map.displayBlueDot = false;
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
						marker: marker,
						label: label
					});
					Map.loadMapZoom7Label(0);
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
			var iw_container = $(".gm-style-iw").parent();
			iw_container.fadeOut(400);

			setTimeout(function(){
				for (var i=0;i < Map.infowindow.length;i++) {
					Map.infowindow[i].close();
				}
				/* remove blue dot markers */
				for (var i = 0; i < Map.infoWindowBlueDot.length; i++) {
					Map.infoWindowBlueDot[i].close();
				}
			}, 400);
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
				if(isGuest) {
					console.log('in click');
					if(isMobile) {
						window.location.href = baseUrl + "/netwrk/user/login";
					} else {
						$('.modal').modal('hide');
						Login.initialize();
					}
					return;
				}
				if(isMobile){
					Map.setBuildMode();
					if(window.location.href != baseUrl + "/netwrk/default/home"){
						sessionStorage.show_blue_dot = 1;
						sessionStorage.show_landing = 1;
						window.location.href = baseUrl + "/netwrk/default/home";
					} else {
						Map.getBrowserCurrentPosition(Map.map, 'build');
					}
				} else {
					Map.setBuildMode();
					console.log('in eventClickMyLocation');
					Map.getBrowserCurrentPosition(map);
				}
		    });
	  	},
		setBuildMode: function() {
			var target = $('.btn_my_location');
			target.addClass('active');
		},
		unsetBuildMode: function() {
			var target = $('.btn_my_location');
			target.removeClass('active');
		},
		getMyHomeLocation: function(map) {
			if(isMobile){
				Map.setBuildMode();
				if(window.location.href != baseUrl + "/netwrk/default/home"){
					sessionStorage.show_blue_dot = 1;
					sessionStorage.show_landing = 1;
					window.location.href = baseUrl + "/netwrk/default/home";
				} else {
					Map.getBrowserCurrentPosition(Map.map);
				}
			} else {
				Map.setBuildMode();
				Map.getBrowserCurrentPosition(map, 'build');
			}
		},

		getBrowserCurrentPosition: function(map, calledFrom) {
			console.log('in getBrowserCurrentPosition');
			navigator.geolocation.getCurrentPosition(
				function(position) {
					var pos = {
						lat: position.coords.latitude,
						lng: position.coords.longitude
					};
					var zoom_current = map.getZoom();

					switch(calledFrom) {
						//on click Near button in navigation always goes to Zoom12.
						case 'near':
							console.log('in switch near');
							if(zoom_current < Map.blueDotLocation.nearByDefaultZoom) {
								Map.smoothZoom(map, Map.blueDotLocation.nearByDefaultZoom, zoom_current, true);
								map.zoom = Map.blueDotLocation.nearByDefaultZoom;
							} else {
								Map.smoothZoom(map, Map.blueDotLocation.nearByDefaultZoom, zoom_current, false);
								map.zoom = Map.blueDotLocation.nearByDefaultZoom;
							}
							break;
						//On click of Build, should always goes to zoom 16 and blue dot popup will open.
						case 'build':
							console.log('in switch build');
							Map.smoothZoom(map, Map.blueDotLocation.zoomMiddle, zoom_current, true);
							map.zoom = Map.blueDotLocation.zoomMiddle;
							break;
						//Default browser zoom is 16
						default:
							console.log('in switch default');

							Map.smoothZoom(map, Map.blueDotLocation.zoomMiddle, zoom_current, true);
							map.zoom = Map.blueDotLocation.zoomMiddle;
							break
					}

					var currentZoom = Map.map.getZoom();
					Map.showHideBlueDotZoomInfo(currentZoom);
					Map.requestBlueDotOnMap(pos.lat, pos.lng, map);
					setTimeout(function() {
						Map.map.setCenter(new google.maps.LatLng(pos.lat, pos.lng));
					}, 200);
				},
				function(error){
					console.log(error);
					switch(error.code)
					{
						case error.PERMISSION_DENIED:
							Map.getMyLocation(map, calledFrom);
							break;

						case error.POSITION_UNAVAILABLE:
							Map.getMyLocation(map, calledFrom);
							break;

						case error.TIMEOUT:
							/*alert('Geo location timeout');*/
							console.log('Geo location timeout');
							break;

						default:
							/*alert('Geo location unknown error');*/
							console.log('Geo location unknown error');
							break;
					}
				}, {
					enableHighAccuracy: false,
					timeout : 50000
				}
			);
		},

		getMyLocation: function(map, calledFrom){
			if(UserLogin){
				Ajax.get_position_user().then(function(data){
					var json = $.parseJSON(data),
						lat = json.lat,
						lng = json.lng;

					if (lat != null || lng != null ) {
						if(lat == 0 && lng == 0) {
							/*Map.getBrowserCurrentPosition(map);*/
						} else {
							var zoom_current = map.getZoom();

							switch(calledFrom) {
								//on click Near button in navigation always goes to Zoom12.
								case 'near':
									console.log('in getMyLocation switch near');
									if(zoom_current < Map.blueDotLocation.nearByDefaultZoom) {
										Map.smoothZoom(map, Map.blueDotLocation.nearByDefaultZoom, zoom_current, true);
										map.zoom = Map.blueDotLocation.nearByDefaultZoom;
									} else {
										Map.smoothZoom(map, Map.blueDotLocation.nearByDefaultZoom, zoom_current, false);
										map.zoom = Map.blueDotLocation.nearByDefaultZoom;
									}
									break;
								//On click of Build, should always goes to zoom 16 and blue dot popup will open.
								case 'build':
									console.log('in getMyLocation switch build');
									Map.smoothZoom(map, Map.blueDotLocation.zoomMiddle, zoom_current, true);
									map.zoom = Map.blueDotLocation.zoomMiddle;
									break;
								//Default browser zoom is 16
								default:
									console.log('in getMyLocation switch default');

									Map.smoothZoom(map, Map.blueDotLocation.zoomMiddle, zoom_current, true);
									map.zoom = Map.blueDotLocation.zoomMiddle;
									break
							}

							var currentZoom = Map.map.getZoom();
							Map.showHideBlueDotZoomInfo(currentZoom);
							Map.requestBlueDotOnMap(lat, lng, map);
							setTimeout(function() {
								Map.map.setCenter(new google.maps.LatLng(lat, lng));
							}, 200);
						}
					}
				});
			}
		},

		getCurrentZipDiscussions: function(){
			var params = {'zip_code':Map.blueDotLocation.zipcode};

			Ajax.getBrilliantPostsFromZip(params).then(function(data){
				var data_posts = $.parseJSON(data);

				if(data_posts.communities == 0){
					$('.cgm-container').find('#communityInfo').addClass('hide');
					$('.cgm-container').find('.discussion-title').addClass('hide');
					$('.cgm-container').find('#noCommunity').removeClass('hide');
					$('.cgm-container').find('.location-details-wrapper').removeClass('text-left').addClass('text-center');
				} else {
					$('.cgm-container').find('#communityInfo').removeClass('hide');
					$('.cgm-container').find('.discussion-title').removeClass('hide');
					$('.cgm-container').find('#noCommunity').addClass('hide');
					$('.cgm-container').find('.location-details-wrapper').removeClass('text-center').addClass('text-left');
				}

				var marker_template = _.template($("#blue_dot_maker_posts").html());
				var post_content = marker_template({marker: data_posts});

				$("#discussionWrapper").html(post_content);
				Map.onClickBlueDotTopPost();
			});
		},

		getCurrentZipBuildDetail: function(){
			var params = {'zip_code':Map.blueDotLocation.zipcode};

			Ajax.getBuildDetailFromZip(params).then(function(data){
				var buildData = $.parseJSON(data);

				if(buildData.communities == 0){
					$('.cgm-container').find('#communityInfo').addClass('hide');
					$('.cgm-container').find('.discussion-title').addClass('hide');
					$('.cgm-container').find('#noCommunity').removeClass('hide');
					$('.cgm-container').find('.location-details-wrapper').removeClass('text-left').addClass('text-center');
				} else {
					$('.cgm-container').find('#communityInfo').removeClass('hide');
					$('.cgm-container').find('.discussion-title').removeClass('hide');
					$('.cgm-container').find('#noCommunity').addClass('hide');
					$('.cgm-container').find('.location-details-wrapper').removeClass('text-center').addClass('text-left');
					if(buildData.user_follow == 'false'){
						Map.blueDotLocation.community = buildData.social_community;
						$('.cgm-container').find('#actionBuildCommunity').addClass('hide');
						$('.cgm-container').find('#actionJoinCommunity').removeClass('hide');
					} else {
						Map.blueDotLocation.community = '';
						$('.cgm-container').find('#actionJoinCommunity').addClass('hide');
						$('.cgm-container').find('#actionBuildCommunity').removeClass('hide');
					}
				}
			});
		},

		findCurrentZip: function(lat, lng) {
			$.getJSON("https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lng ,function(data) {
				var len = data.results[0].address_components.length;
				var city = '',
					locality = '',
					sublocality = '';

				for (var i = 0; i < len; i++) {
					if (data.results[0].address_components[i].types[0] == 'postal_code') {
						// console.log(data);
						var zip = data.results[0].address_components[i].long_name;
						/*console.log("cmzip: ", $("#cm-zip").html());
						$("#cm-zip span").eq(0).html(zip);*/
						Map.blueDotLocation.zipcode = zip;
						$('#create-location-group').attr('data-zipcode', zip);

						/*if(zip != zipCode) {
							// Get boundaries data from zip
							var params = {'zip_code' : zip};
							Ajax.getSingleZipBoundaries(params).then(function(jsonData){
								// Remove shaded area of blue dots previous location
								Map.map.data.forEach(function(feature) {
									if(feature.f.type != 'selected' && feature.f.type != 'Followed'){
										Map.map.data.remove(feature);
									}
								});

								var out = $.parseJSON(jsonData);

								for (var key in out) {
									if (out.hasOwnProperty(key)) {
										// Add map data
										Map.map.data.addGeoJson(out[key]);

										// Style map data
										Map.map.data.setStyle({
											fillColor: '#5888ac',
											fillOpacity: Map.fillOpacity,
											strokeColor: '#5888ac',
											strokeWeight: 2
										});
									}
								}
							});
						}*/
					} else if (data.results[0].address_components[i].types[0] == 'locality') {
						locality = data.results[0].address_components[i].long_name;

					} else if (data.results[0].address_components[i].types[1] == 'sublocality') {
						sublocality = data.results[0].address_components[i].long_name;
					}
				}
				if(locality != ''){
					city = locality;
				} else if(sublocality != '') {
					city = sublocality;
				}

				setTimeout(function(){
					$("#blueDotLocation span").eq(0).html(city);
				},300);
				Map.getCurrentZipBuildDetail();
			});
		},

		requestPositionFunction: function(map) {
			if (map.getZoom() != Map.zoom) {
				if (typeof Map.center_marker != "undefined" && Map.center_marker != null) {
					Map.center_marker.setMap(null);
				}
				return;
			}
			//Todo: currently the following functionality temporary commented. It asks to user for his location.
			//Map.show_marker_group_loc(map);
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function(position) {

					map.setCenter(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));

					if (Map.center_marker != null) Map.center_marker.setMap(null);

					Map.requestBlueDotOnMap(position.coords.latitude, position.coords.longitude, map, 'location');

					//display blue dot on map from lat and lon.
					/*var infowindow = Map.showBlueDot(position.coords.latitude, position.coords.longitude, map);

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

					console.log(map.getZoom());*/

				});
			} else {
				console.log("Geolocation is not supported by this browser.");
			}
			console.log('in req postion');
		},

		requestBlueDotOnMap: function(lat, lng, map, from) {
			// Close modal
			$('.modal').modal('hide');
			/*if (map.getZoom() != Map.blueDotLocation.zoomInitial) {
				if (typeof Map.center_marker != "undefined" && Map.center_marker != null) {
					Map.center_marker.setMap(null);
				}
				return;
			}*/
			//Map.show_marker_group_loc(map);
			if (lat != 'undefined' && lng != 'undefined') {
				console.log('in if blue dot');
				//map.setCenter(new google.maps.LatLng(lat, lng));
				Map.removeBlueDot();
				//if (Map.center_marker != null) Map.center_marker.setMap(null);

				//display blue dot on map from lat and lon.
				var blueDotInfoWindow = Map.showBlueDot(lat, lng, map, from);

				Map.infoWindowBlueDot.push(blueDotInfoWindow);

				google.maps.event.addListener(blueDotInfoWindow, 'domready', function() {
					//   // Reference to the DIV that wraps the bottom of infowindow
					var iwOuter = $('.gm-style-iw');
					var iwOuter = $('#iw-container').closest('.gm-style-iw');

					//    // Since this div is in a position prior to .gm-div style-iw.
					//    // * We use jQuery and create a iwBackground variable,
					//    // * and took advantage of the existing reference .gm-style-iw for the previous div with .prev().

					iwOuter.css({'max-width' : '250px', 'z-index' : '999', 'box-shadow' : '2px 2px 2px'});

					var iwBackground = iwOuter.prev();
					iwOuter.children(':nth-child(1)').css({'max-width' : '240px'});
					//*/ Removes background shadow DIV
					iwBackground.children(':nth-child(2)').css({'display' : 'none'});

					//   // Removes white background DIV
					iwBackground.children(':nth-child(4)').css({'display' : 'none'});

					//   // Reference to the div that groups the close button elements.
					var iwCloseBtn = iwOuter.next();

					//   // Apply the desired effect to the close button
					iwCloseBtn.css({opacity: '0', right: '135px', top: '15px', border: '0px solid #477499', 'border-radius': '13px', 'box-shadow': '0 0 0px 2px #477499','display':'none'});
				});

				//remove old listener in map
				google.maps.event.clearListeners(Map.center_marker, 'mouseover');
				google.maps.event.addListener(Map.center_marker, 'mouseover', function() {
					// infowindow.setContent(e[0]);
					blueDotInfoWindow.open(map, this);
					//Map.findCurrentZip(Map.center_marker.getPosition().lat(), Map.center_marker.getPosition().lng());
					var lat = parseFloat(Map.center_marker.getPosition().lat());
					var lng = parseFloat(Map.center_marker.getPosition().lng());
					var dec = (lat - Math.floor(lat)) * 60;
					var latGrad = Math.round(lat) + "&deg; " + Math.round(dec) + "' " + Math.round((dec - Math.floor(dec)) * 60) + "''";
					var dec2 = (lng - Math.floor(lng)) * 60;
					var lngGrad = Math.round(lng) + "&deg; " + Math.round(dec2) + "' " + Math.round((dec2 - Math.floor(dec2)) * 60) + "''";
					$('#cm-coords').html(latGrad + "<br>" + lngGrad);
				});

				google.maps.event.addListener(Map.center_marker, 'mouseout', function() {
					Map.timeout = setTimeout(function(){
						Map.closeAllInfoWindows();
					}, 400);
				});

				//remove old listener in map
				google.maps.event.clearListeners(Map.center_marker, 'click');
				google.maps.event.addListener(Map.center_marker, 'click', (function() {
					return function(){
						var current_zoom = Map.map.getZoom();
						if(current_zoom <= 15){
							if(!isMobile) {
								// Close blue dot info window
								Map.closeAllInfoWindows();
							}
						} else if(current_zoom == Map.blueDotLocation.zoomMiddle) {

							//Go to zoom level 18. and shoe blue dot on map
							Map.zoomMap(Map.center_marker.getPosition().lat(),Map.center_marker.getPosition().lng(), Map.blueDotLocation.zoomLast, Map.map)
						} else if(current_zoom == Map.blueDotLocation.zoomLast) {
							//Go to zoom level 16. and shoe blue dot on map
							Map.zoomMap(Map.center_marker.getPosition().lat(),Map.center_marker.getPosition().lng(), Map.blueDotLocation.zoomMiddle, Map.map)
						} else {
							//Go to zoom level 16. and shoe blue dot on map
							Map.zoomMap(Map.center_marker.getPosition().lat(),Map.center_marker.getPosition().lng(), Map.blueDotLocation.zoomMiddle, Map.map)
						}

						if(isMobile){
							if(current_zoom <= 15){
								if (!blueDotInfoWindow.getMap()) {
									blueDotInfoWindow.open(Map.map, Map.center_marker);
								} else {
									blueDotInfoWindow.close();
								}
							} else {
								blueDotInfoWindow.close();
								blueDotInfoWindow.open(Map.map, Map.center_marker);
							}
							/*if (!blueDotInfoWindow.getMap()) {
							 blueDotInfoWindow.open(Map.map, Map.center_marker);
							 } else {
							 blueDotInfoWindow.close();
							 }*/
						} else {
							blueDotInfoWindow.close();
						}
					};
				})(Map.center_marker));

				google.maps.event.clearListeners(Map.center_marker, 'dblclick');
				google.maps.event.addListener(Map.center_marker, 'dblclick', (function() {
					return function(){
						if(Map.map.getZoom() != Map.blueDotLocation.zoomLast) {
							console.log('in click zoom 18');

							Map.smoothZoom(map, Map.blueDotLocation.zoomLast, Map.map.getZoom(), true);
							map.zoom = Map.blueDotLocation.zoomLast;

							if(isMobile){
								setTimeout(function() {
									Map.map.setCenter(new google.maps.LatLng(Map.center_marker.getPosition().lat(), Map.center_marker.getPosition().lng()));
								}, 200);
							} else {
								blueDotInfoWindow.close();
								Map.closeAllInfoWindows();
								Map.loadBlueDotMarker(Map.map);
								setTimeout(function() {
									Map.map.setCenter(new google.maps.LatLng(Map.center_marker.getPosition().lat(), Map.center_marker.getPosition().lng()));
								}, 200);
							}

						} else {
							console.log('In map dblclick Map.center_marker');
						}
					};
				})(Map.center_marker));

				//remove old listener in map
				//google.maps.event.clearListeners(Map.center_marker, 'dragstart');
				google.maps.event.addListener(Map.center_marker, 'dragstart', function() {
					console.log("stopping", Map.requestPosTimeout);
					if (Map.requestPosTimeout != null) clearTimeout(Map.requestPosTimeout);
				});


				blueDotInfoWindow.open(Map.map, Map.center_marker);
				//find current zip from lat and lng set to Map.blueDotLocation.zipcode
				Map.findCurrentZip(lat, lng);
			}

		},
		/* Common code for displaying blue dot on map using blueDotmarker array */
		loadBlueDotMarker: function(map) {
			var currentZoom = map.getZoom();

			for (var i = 0; i < Map.blueDotMarker.length; i++) {
				var m = Map.blueDotMarker[i];
				m.marker.setMap(map);
				//Map.markers.push(m.marker);

				console.log('in loadBlueDotMarker');
			}
		},

		onClickBlueDotTopPost: function(){
			var parent = $('.discussion-wrapper').find('.top-post-brilliant .name-post');
			parent.unbind();
			parent.on('click',function(){
				if(isMobile){
					var item_post = $(this).attr('data-value');
					PopupChat.RedirectChatPostPage(item_post, 1, 1);
				} else {
					PopupChat.params.post = $(this).attr('data-value');
					PopupChat.params.chat_type = $(this).attr('data-type');
					PopupChat.params.post_name = $(this).attr('data-name');
					PopupChat.params.post_description = $(this).attr('data-content');
					ChatInbox.params.target_popup = $('.popup_chat_modal #popup-chat-'+PopupChat.params.post);
					PopupChat.initialize();
				}
			});
		},

		setBlueDotInfoWindow: function() {
			// Blue dot info window content is in following file
			// @frontend/modules/netwrk/views/marker/blue_dot_post_content
			var blueDotInfoWindowContent = $('#blueDotInfoWindow').html();

			var infowindow = new google.maps.InfoWindow({
				maxWidth: 240,
				content: blueDotInfoWindowContent
			});

			return infowindow;
		},
		removeBlueDot: function() {
			if (Map.center_marker != null) {
				Map.center_marker.setMap(null);
			}
		},
		showBlueDot: function(lat, lng, map, from) {
			var img = '/img/icon/pale-blue-line-icon-dot.png',
				dragImg = '/img/icon/pale-blue-line-icon-dot-bg.png';

			if(from == 'location'){
				var img = '/img/icon/pale-blue-location-icon-dot.png',
					dragImg = '/img/icon/pale-blue-location-icon-dot-bg.png';
			}

			var marker = new google.maps.Marker({
				position: new google.maps.LatLng(lat, lng),
				icon: img,
				draggable: true
				//city_id: parseInt(e.id)
			});

			Map.center_marker = marker;

			//google.maps.event.clearListeners(marker, 'dragstart');
			google.maps.event.addListener(marker, 'dragstart', function(e) {
				console.log('in dragstart');
				marker.setIcon(dragImg);
				$("#blueDotLocation span").eq(0).html('Requesting...');
				google.maps.event.clearListeners(Map.center_marker, 'mouseout');
			});

			//google.maps.event.clearListeners(marker, 'dragend');
			google.maps.event.addListener(marker, 'dragend', function(e) {
				console.log('in dragend');
				marker.setIcon(img);
				Map.findCurrentZip(marker.getPosition().lat(),
					marker.getPosition().lng());

				google.maps.event.addListener(Map.center_marker, 'mouseout', function() {
					Map.timeout = setTimeout(function(){
						Map.closeAllInfoWindows();
					}, 400);
				});
			});

			Map.blueDotMarker = [];
			Map.blueDotMarker.push({
				marker: marker
			});

			//Display blue dot markers from blueDotMarker array.
			Map.loadBlueDotMarker(map);

			Map.blueDotLocation.lat = marker.getPosition().lat();
			Map.blueDotLocation.lon = marker.getPosition().lng();

			console.log(Map.blueDotLocation.lat+', '+Map.blueDotLocation.lon);

			var infowindow = Map.setBlueDotInfoWindow();

			if(!isMobile) {
				infowindow.close();
			}

			return infowindow;
		},
		showTopicFromZipcode: function(zipcode) {
			//todo: get city (social) from zipcode and init the topic modal
			var zipcode = zipcode || '';
			if(zipcode) {
				var params = {'zip_code':zipcode, 'office_type': 'social'};
				Ajax.get_city_by_zipcode(params).then(function(data){
					var json = $.parseJSON(data);
					var socialCityId = '';
					$.each(json, function(i, city){
						//get city which have office type as 'social'
						if(city.office.toLowerCase() == 'social') {
							socialCityId = city.id;
						}
					});
					console.log(socialCityId);
					setTimeout(function(){
						if(socialCityId) {
							Topic.initialize(socialCityId);
						}
					}, 100);
				});
			}
		},
		showHideBlueDotZoomInfo: function(zoom){
			// Show hide click to cancel line
			if(zoom <= 15){
				$('.cgm-container').find('.zoom-cancel').removeClass('hide');
			} else {
				$('.cgm-container').find('.zoom-cancel').addClass('hide');
			}

			// Show hide double click line and create buttons
			/*if(zoom >= Map.blueDotLocation.zoomMiddle) {
				$('.cgm-container').find('.create-section-wrapper').removeClass('hide');
				$('.cgm-container').find('.double-click').addClass('hide');
			} else {
				$('.cgm-container').find('.create-section-wrapper').addClass('hide');
				$('.cgm-container').find('.double-click').removeClass('hide');
			}*/

			// Show hide click to zoom and build line
			if(zoom >= Map.blueDotLocation.zoomMiddle && zoom < Map.blueDotLocation.zoomLast) {
				$('.cgm-container').find('.zoom-info').removeClass('hide');
			} else {
				$('.cgm-container').find('.zoom-info').addClass('hide');
			}
		},
		zoomMap: function(lat, lng, zoom, map){
			zoom = zoom || Map.blueDotLocation.zoomInitial;
			if (zoom && zoom != 'undefined') {
				Map.showHideBlueDotZoomInfo(zoom);
				Map.map.setZoom(zoom);
			}
			map.setCenter(new google.maps.LatLng(lat, lng));
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
			}, 4000);
			/*Map.requestPositionFunction(map);
			/*google.maps.event.addListener(map, 'bounds_changed', function() {
				console.log(map.getZoom());
				Map.requestPositionFunction(map);
			});*/

			google.maps.event.addListener(map, 'dragstart', function(){
				Map.getMaxMarker = false;
			});

			google.maps.event.addListener(map, 'dragend', function(){
				if($('#btnCenterLocation').hasClass('hide')){
					$('#btnCenterLocation').removeClass('hide');
					$('#btn_meet').css({'right' : '70px'})
				}
				Map.removeBlueDot();
				Map.getMaxMarker = true;
			});


			google.maps.event.addListener(map, 'idle', function(){
				var currentZoom = map.getZoom();

				var bounds = Map.map.getBounds(),
						ne = bounds.getNorthEast(),
						sw = bounds.getSouthWest();

				var params = {'swLat':sw.lat(), 'swLng':sw.lng(), 'neLat': ne.lat(), 'neLng':ne.lng()};

				if(currentZoom >= Map.markerZoom ) {
					setTimeout(function () {
						if (Map.getMaxMarker == true) {
							/*Ajax.get_marker_zoom(params).then(function (data) {
								var data_marker = $.parseJSON(data);
								$.each(data_marker, function (i, e) {
									Map.initializeMarker(e, null, Map.markerZoom);
								});
							});*/
						}
					}, 250);
					
					Map.mouseOutsideInfoWindow();
					//Map.deleteNetwrk(map);
					Map.loadMapLabel(0);
					for (var i = 0; i < Map.zoom12.length; i++) {
						var m = Map.zoom12[i];
						m.marker.setMap(map);
						Map.markers.push(m.marker);
					}
				}

				/*if(currentZoom >= Map.zoom){
					Ajax.getVisibleZipBoundaries(params).then(function(jsonData){
						var out = $.parseJSON(jsonData);

						for (var key in out) {
							if (out.hasOwnProperty(key)) {
								Map.map.data.addGeoJson(out[key]);

								Map.map.data.setStyle(function(feature) {
									if(feature.H.type == 'selected' || feature.H.type == 'Followed') {
										return /!** @type {google.maps.Data.StyleOptions} *!/({
											fillColor: '#5888ac',
											fillOpacity: Map.fillOpacity,
											strokeColor: '#5888ac',
											strokeWeight: 2
										});
									} else {
										return /!** @type {google.maps.Data.StyleOptions} *!/({
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

										//Map.createZipLabelMarker(cid,name_of_place,zipCode,zipLat,zipLng);
									}
								} else {
									Map.clearZipLabelMarker();
								}
							}
						}
					});
				} else {
					map.data.forEach(function(feature) {
						if(feature.H.type != 'selected' && feature.H.type != 'Followed'){
							map.data.remove(feature);
						}
					});
				}*/

				if(currentZoom >= map.zoom) {
					//Map.show_marker_group_loc(map, params);
					//Map.show_marker_topic_loc(map, params);
					Map.show_marker_line_loc(map, params);
				} else {
					Map.clearTopicMarkers();
				}
			});
		},

		CreateLocationGroup: function(zipcode) {
			var lat = Map.center_marker.getPosition().lat();
			var lng = Map.center_marker.getPosition().lng();

			if(isMobile) {
				if(zipcode){
					//window.location.href = baseUrl + "/netwrk/topic/create-topic?city=null&zipcode="+zipcode+"&name=null&lat="+lat+"&lng="+lng+"&isCreateFromBlueDot=true";
					window.location.href = baseUrl + "/netwrk/group/create-group?city=null&zipcode="+zipcode+"&name=null&lat="+lat+"&lng="+lng+"&isCreateFromBlueDot=true";
				}
			} else {
				Create_Group.initialize(null, null, zipcode, null, true, lat, lng);
			}
		},
		CreateLocationTopic: function(zipcode) {
			var lat = Map.center_marker.getPosition().lat();
			var lng = Map.center_marker.getPosition().lng();

			Create_Topic.showCreateTopicModal(zipcode, lat, lng);
		},
		CreateLocationPost: function(zipcode) {
			var lat = Map.center_marker.getPosition().lat();
			var lng = Map.center_marker.getPosition().lng();

			Create_Post.showCreatePostModal(zipcode, lat, lng);
		},
		joinCommunity: function(community, from) {
			if(isGuest){
				if(isMobile){
					window.location.href = baseUrl + "/netwrk/user/login?url_callback="+baseUrl;
				} else {
					Login.initialize();
				}
				return false;
			}

			params = {
				'object_type': 'city',
				'object_id' : community
			};

			Ajax.favorite(params).then(function(data){
				Map.blueDotLocation.community = '';
				if(from == 'user-location'){
					Map.initialize();
				} else {
					$('.cgm-container').find('#actionJoinCommunity').addClass('hide');
					$('.cgm-container').find('#actionBuildCommunity').removeClass('hide');
				}
				Default.getUserFavorites();
			});
		},
		CreateUserLocationPost: function() {
			Map.closeUserLocationInfoWindow();

			var zipCode = User.location.zipCode;
			var lat = User.location.lat;
			var lng = User.location.lng;

			Create_Post.showCreatePostModal(zipCode, lat, lng);
		},
		closeUserLocationInfoWindow: function() {
			Map.displayUserLocationInfo = false;
			userLocationInfoWindow.close();
			Ajax.setUserLocationInfoCookie().then(function(data){
				//console.log(data);
				//window.location.href = baseUrl; //+ "/netwrk/default/home";
			});
		},

		show_marker_group_loc: function(map,params) {
			var marker,
				json,
				data_marker;
				//groupId = (typeof groupId != "undefined") ? groupId : null;

			Map.clearGroupMarkers();
			Ajax.get_marker_groups_loc(params).then(function(data){
				console.log('get marker group loc');
				data_marker = $.parseJSON(data);
				var currentZoom = map.getZoom();
				//console.log(data_marker);
				$.each(data_marker,function(i,e){
					/*var img = '/img/icon/map_icon_community_v_2.png';

					marker = new google.maps.Marker({
						position: new google.maps.LatLng(e.lat, e.lng),
						map: map,
						icon: img,
						group_id: parseInt(e.id)
					});*/

					//topic marker should be small in zoom 13 to 16. And big in zoom 16 - 18.
					if (currentZoom >= 13 && currentZoom < 16 ) {
						var markerContent = "<div class='marker marker-topic-sm'></div>"+
							"<span class='marker-icon marker-group-icon'>"+
							"</span><div class=''></div>";
					} else if (currentZoom >= 16 && currentZoom <= 18 ){
						var markerContent = "<div class='marker marker-group'></div>"+
						 "<span class='marker-icon marker-group-icon'><i class='fa fa-lg fa-users'></i>"+
						 "</span><div class=''></div>";
					} else {
						markerContent = '';
					}
					/*var markerContent = "<div class='marker marker-group'></div>"+
										"<span class='marker-icon marker-social'><i class='fa fa-lg fa-users'></i>"+
										"</span><div class='marker-shadow'></div>";*/

					marker = new RichMarker({
						position: new google.maps.LatLng(e.lat, e.lng),
						map: map,
						content: markerContent,
						group_id: parseInt(e.id)
					});

					//console.log("marker", marker);
					if(currentZoom >= 16) {
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
					}


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

					Map.groupMarkers.push({
						marker: marker
						//label: label
					});
					//Map.groupMarkers.push(marker);
				});
			});
		},
		clearTopicMarkers: function() {
			for (var i = 0; i < Map.topicMarkers.length; i++) {
				//Map.topicMarkers[i].setMap(null);
				var m = Map.topicMarkers[i];
				if(typeof m.marker != 'undefined' || m.marker != null) {
					m.marker.setMap(null);
				}
			}

			for (i=0; i<Map.topicMarkers.length; i++) {
				m = Map.topicMarkers[i];
				if(typeof m.label != 'undefined' || m.label != null) {
					m.label.setMap(null);
				}
			}
			Map.topicMarkers = [];
		},
		clearGroupMarkers: function() {
			for (var i = 0; i < Map.groupMarkers.length; i++) {
				//Map.topicMarkers[i].setMap(null);
				var m = Map.groupMarkers[i];
				if(typeof m.marker != 'undefined' || m.marker != null) {
					m.marker.setMap(null);
				}
			}

			/*for (i=0; i<Map.groupMarkers.length; i++) {
				m = Map.groupMarkers[i];
				if(typeof m.label != 'undefined' || m.label != null) {
					m.label.setMap(null);
				}
			}*/
			Map.groupMarkers = [];
		},
		show_marker_topic_loc: function(map, params) {
			var marker,json,data_marker;

			//clear the topic markers and its lable
			Map.clearTopicMarkers();
			Ajax.get_marker_topic_loc(params).then(function(data){
				console.log('get marker topic loc');
				data_marker = $.parseJSON(data);
				var currentZoom = map.getZoom();

				//console.log(data_marker);
				$.each(data_marker,function(i,e){

					//topic marker should be small in zoom 13 to 16. And big in zoom 16 - 18.
					if (currentZoom >= 13 && currentZoom < 16 ) {
						var markerContent = "<div class='marker marker-topic-sm'></div>"+
							"<span class='marker-icon marker-topic-icon'>"+
							"</span><div class=''></div>";
					} else if (currentZoom >= 16 && currentZoom <= 18 ){
						var markerContent = "<div class='marker marker-topic'></div>"+
							"<span class='marker-icon marker-topic-icon'><i class='fa fa-align-justify'></i>"+
							"</span><div class=''></div>";
					} else {
						markerContent = '';
					}

					//display topic markers on map
					marker = new RichMarker({
						position: new google.maps.LatLng(e.lat, e.lng),
						map: map,
						content: markerContent,
						city_id: parseInt(e.city_id),
						topic_id: parseInt(e.id),
						topic_name: e.title
						/*draggable: true*/
					});

					if(currentZoom >= 16) {
						google.maps.event.addListener(marker, 'click', (function(marker, i) {
							return function(){
								//Open post modal
								console.log('topic =>'+marker.topic_id);
								var topic_id = marker.topic_id;
								console.log(marker.topic_id);
								if(isMobile){
									Post.RedirectPostPage(topic_id, false);
								}else{
									Post.params.topic = topic_id;
									Post.params.topic_name = marker.topic_name;
									Post.params.city = marker.city_id;
									Post.params.city_name = marker.city_name;
									Post.initialize();
								}
								if(!isMobile){
									if (typeof infowindow != "undefined") {
										infowindow.close();
									}
								}
							};
						})(marker, i));

						var text_below;

						text_below = "<span>" + e.zip_code + " " + ((e.office != null) ? e.office : e.city_name) + "</span>";
						text_below += "<br>" + e.title;

						if(e.topic && e.topic.length > 0 && e.trending_hashtag.length > 0) {
							text_below += "<br>#" + e.trending_hashtag[0].hashtag_name;
						}

						var label = new Label({
							map: map,
							text: text_below,
							cid: parseInt(e.id)
						});

						label.bindTo('position', marker, 'position');
					}

					Map.topicMarkers.push({
						marker: marker,
						label: label
					});
					//Map.topicMarkers.push(marker);
				});


			});
		},
		clearLineMarkers: function() {
			for (var i = 0; i < Map.lineMarkers.length; i++) {
				var m = Map.lineMarkers[i];
				if(typeof m.marker != 'undefined' || m.marker != null) {
					m.marker.setMap(null);
				}
			}

			for (i=0; i<Map.lineMarkers.length; i++) {
				m = Map.lineMarkers[i];
				if(typeof m.label != 'undefined' || m.label != null) {
					m.label.setMap(null);
				}
			}
			Map.lineMarkers = [];
		},
		show_marker_line_loc: function(map, params) {
			var marker,json,data_marker;

			//clear the topic markers and its lable
			Map.clearLineMarkers();
			Ajax.get_marker_line_loc(params).then(function(data){
				console.log('get marker line loc');
				data_marker = $.parseJSON(data);
				var currentZoom = map.getZoom();

				//console.log(data_marker);
				$.each(data_marker,function(i,e){
					var markerContent;
					if(e.expire_at == null){
						markerContent = "<div class='marker marker-line'></div>"+
								"<span class='marker-icon marker-line-icon'><i class='fa fa-comment'></i>"+
								"</span><div class=''></div>";
					} else {
						markerContent = "<div class='marker marker-line marker-timed-line'></div>"+
								"<span class='marker-icon marker-line-icon'><i class='fa fa-comment'></i>"+
								"</span><div class=''></div>";
					}


					//display topic markers on map
					marker = new RichMarker({
						position: new google.maps.LatLng(e.lat, e.lng),
						map: map,
						content: markerContent,
						city_id: parseInt(e.city_id),

						topic_id: parseInt(e.topic_id),
						topic_name: e.topic_title,

						post_id: parseInt(e.post_id),
						post_name: e.post_title,
						post_type: e.post_type,
						post_content: e.post_content,
						/*draggable: true*/
					});

					google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function(){
							Map.displayBlueDot = false;
							var post_id = marker.post_id,
								post_name = marker.post_name,
								post_content = marker.post_content,
								post_type = marker.post_type;
							if(isMobile){
								sessionStorage.url = window.location.href;
								sessionStorage.feed_topic = 1;
								PopupChat.RedirectChatPostPage(post_id, 1, 1);
							}else{
								PopupChat.params.post = post_id;
								PopupChat.params.chat_type = post_type;
								PopupChat.params.post_name = post_name;
								PopupChat.params.post_description = post_content;

								console.log(PopupChat.params);
								PopupChat.initialize();
							}
						};
					})(marker, i));

					var text_below;

					text_below = "<span class='line-title'>" + e.post_title + "</span>";

					var label = new Label({
						map: map,
						text: text_below,
						linelable: ''
					});

					label.bindTo('position', marker, 'position');

					Map.lineMarkers.push({
						marker: marker,
						label: label
					});
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
				Map.getMaxMarker = true;
				var data_marker;
				var currentZoom = map.getZoom();
				console.log(currentZoom);
				Map.showHideBlueDotZoomInfo(currentZoom);

				// Recreate info window, so it will always display updated contentajax
				var blueDotInfoWindow = Map.setBlueDotInfoWindow();
				Map.infoWindowBlueDot = [];
				Map.infoWindowBlueDot.push(blueDotInfoWindow);

				if(isMobile){
				    //sessionStorage.map_zoom = currentZoom;
				}
				if(currentZoom == 18){
	    			Map.map.setOptions({zoomControl: false, scrollwheel: true, styles: null});
	    		} else {
	    			var remove_poi = Map.remove_poi;
				    Map.map.setOptions({zoomControl: false, scrollwheel: true, styles: remove_poi});
	    		}

	    		if (currentZoom == Map.markerZoom && Map.markers.length <= 10) { //currentZoom desktop == 14, mobile == 13
	    			Map.deleteNetwrk(map);
				    Map.loadMapLabel(0);
					Map.loadMapZoom7Label(0);
					for (var i = 0; i < Map.zoom7.length; i++) {
						var m = Map.zoom7[i];
						m.marker.setMap(map);
						Map.markers.push(m.marker);
					}
					for (var i = 0; i < Map.zoom12.length; i++) {
						var m = Map.zoom12[i];
						m.marker.setMap(map);
					    Map.markers.push(m.marker);
				    }
				} else if(currentZoom == Map.zoom ){ //currentZoom desktop == 13, mobile == 12
					Map.deleteNetwrk(map);
					Map.hideMapLabel();
					Map.loadMapZoom7Label(0);
					for (var i = 0; i < Map.zoom7.length; i++) {
						var m = Map.zoom7[i];
						m.marker.setMap(map);
						Map.markers.push(m.marker);
					}


					for (var i = 0; i < Map.zoom13.length; i++) {
						var m = Map.zoom13[i];
						m.marker.setMap(map);
						Map.markers.push(m.marker);
					}
				} else if(currentZoom < Map.zoom ){ //currentZoom desktop < 13, mobile < 12
					Map.deleteNetwrk(map);
					Map.hideMapLabel();
					Map.hideMapZoom7Label();
					Map.clearZipLabelMarker();

					for (var i = 0; i < Map.zoom7.length; i++) {
						var m = Map.zoom7[i];
						m.marker.setMap(map);
						Map.markers.push(m.marker);
					}
				} else if (currentZoom == 11 && Map.markers.length > 10) { //currentZoom == 11
	    			Map.deleteNetwrk(map);
					Map.hideMapLabel();
					Map.hideMapZoom7Label();
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
		loadMapZoom7Label: function(offset) {
			var endOffset = offset + 200;
			if (endOffset > Map.zoom7.length) {
				endOffset = Map.zoom7.length;
			}
			var m = {};
			for (i=offset; i<endOffset; i++) {
				m = Map.zoom7[i];
				m.label.setMap(Map.map);
			}
			if (endOffset<Map.zoom7.length) {
				Map.timeoutZoom7 = setTimeout(Map.loadMapZoom7Label, 200, endOffset);
			}
		},
	  	hideMapLabel: function() {
	  		clearTimeout(Map.timeout);
	  		for (i=0; i<Map.zoom12.length; i++) {
	  			m = Map.zoom12[i];
  				m.label.setMap(null);
	  		}
	  	},
		hideMapZoom7Label: function() {
			clearTimeout(Map.timeoutZoom7);
			for (i=0; i<Map.zoom7.length; i++) {
				m = Map.zoom7[i];
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
		    mapType.minZoom = 10;
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

								//Map.createZipLabelMarker(cid,name_of_place,zipCode,zipLat,zipLng);
							}
						} else {
							Map.clearZipLabelMarker();
						}
					}
				}
			});
		},
		showTopicMarker: function(lat, lng, city_id) {
			var zoom18 = 18;

			Map.zoomMap(lat,lng,zoom18,Map.map);
			if(isMobile) {
				sessionStorage.topic_lat = null;
				sessionStorage.topic_lng = null;
				sessionStorage.topic_city_id = null;
				sessionStorage.is_topic_marker_in_map_center = 0;
			}
		}
	};