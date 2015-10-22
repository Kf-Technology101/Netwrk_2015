var Map ={
	params:{
		name:'',
		zip_code:'',
		lat:'',
		lng:''
	},
	latLng: '',
	markers:[],
	data_map:'',
	infowindow:[],
	initialize: function() {
		var map_andiana = {
			center:new google.maps.LatLng(39.7662195,-86.441277),
			zoom: 7,
			disableDoubleClickZoom: true,
			disableDefaultUI: true,
			streetViewControl: false,
			mapTypeId:google.maps.MapTypeId.ROADMAP,
		};

		var map = new google.maps.Map(document.getElementById("googleMap"),map_andiana);
		map.setOptions({zoomControl: false, disableDoubleClickZoom: true});
		
		Map.data_map = map;
		Default.getMarkerDefault();
		Map.show_marker(map);
		Map.min_max_zoom(map);
		Map.eventOnclick(map);
		
		Map.eventZoom(map);
	},

	main: function(){
	  	if (typeof google !== "undefined") {
    		google.maps.event.addDomListener(window, 'load', Map.initialize());
  		}
	},

	get_data_marker: function(){
		var map = Map.data_map;
		// console.log('adasd');
		var current = map.getZoom();
		$('.indiana_marker').find("li[num-city]").remove();
		Map.deleteNetwrk(map);
		if(current == 7){
			Default.getMarkerDefault();
		}else if(current == 12){
			Default.getMarkerZoom();
		}
		Map.show_marker(map);
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
	  	var marker,json;

	 	var data_marker = Map.get_netwrk();

		$.each(data_marker,function(i,e){


			marker = new google.maps.Marker({
				position: new google.maps.LatLng(e[1], e[2]),
				map: map,
				city_id: parseInt(e[3])
			});

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function(){
					if(!isMobile){
						infowindow.close();
					}
					Topic.init(marker.city_id);
				};
			})(marker, i));

			if(!isMobile){
				Ajax.get_top_post({city_id: e[3]}).then(function(data){
	 				json = $.parseJSON(data); 
				});
				var content = '<div id="iw-container" >' +
			                '<div class="iw-title"><span class="toppost">Top Post</span><a class="info_zipcode" data-city="'+ json.city_id +'" onclick="Map.eventOnClickZipcode('+json.city_id +')"><span class="zipcode">'+ json.zipcode + '</span></a></div>' +
			                '<div class="iw-content">' +
			                  '<div class="iw-subTitle">#'+json.name_post+'</div>' +
			                  '<p>'+json.content+'</p>'+
			                '</div>' +
			                '<div class="iw-bottom-gradient"></div>' +
			              '</div>';
	            var infowindow = new google.maps.InfoWindow({
	            	content: content,
	            	city_id: json.city_id,
	            	maxWidth: 350
	            });
	            
	            Map.infowindow.push(infowindow);

				google.maps.event.addListener(marker, 'mouseover', function(d) {
					// infowindow.setContent(e[0]);
					infowindow.open(map, this);
					Map.onhoverInfoWindow(e[3],marker);
				});

				google.maps.event.addListener(marker, 'mouseout', function() {
					// infowindow.close();
				});
			  	google.maps.event.addListener(infowindow, 'domready', function() {

				    // Reference to the DIV that wraps the bottom of infowindow
				    var iwOuter = $('.gm-style-iw');

				    /* Since this div is in a position prior to .gm-div style-iw.
				     * We use jQuery and create a iwBackground variable,
				     * and took advantage of the existing reference .gm-style-iw for the previous div with .prev().
				    */
				    var iwBackground = iwOuter.prev();

				    // Removes background shadow DIV
				    iwBackground.children(':nth-child(2)').css({'display' : 'none'});

				    // Removes white background DIV
				    iwBackground.children(':nth-child(4)').css({'display' : 'none'});

				    // Moves the infowindow 115px to the right.
				    iwOuter.parent().parent().css({left: '115px'});

				    // Moves the shadow of the arrow 76px to the left margin.
				    iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'top: 174px !important;left: 76px !important;'});

				    // Moves the arrow 76px to the left margin.
				    iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'top: 174px !important;left: 76px !important;'});

				    // Changes the desired tail shadow color.
				    iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': '#477499 0px 1px 0px 2px', 'z-index' : '1'});

				    // Reference to the div that groups the close button elements.
				    var iwCloseBtn = iwOuter.next();

				    // Apply the desired effect to the close button
				    iwCloseBtn.css({opacity: '0', right: '135px', top: '15px', border: '0px solid #477499', 'border-radius': '13px', 'box-shadow': '0 0 0px 2px #477499'});

				    // If the content of infowindow not exceed the set maximum height, then the gradient is removed.
				    if($('.iw-content').height() < 140){
				      $('.iw-bottom-gradient').css({display: 'none'});
				    }

				    // The API automatically applies 0.7 opacity to the button after the mouseout event. This function reverses this event to the desired value.
				    iwCloseBtn.mouseout(function(){
				      $(this).css({opacity: '0'});
				    });
		  		});
			}

			Map.markers.push(marker);
		});
		
	},
	eventZoom: function(map){
		var zoom_current = 7;
	  	map.addListener('zoom_changed', function() {
	  		Map.deleteNetwrk(map);
			if(zoom_current == 7){
				map.zoom = 12;
				zoom_current = 12;
				Default.getMarkerZoom();
				Map.show_marker(map);
			}else if (zoom_current == 12){
				map.zoom = 7;
				zoom_current = 7;
				Default.getMarkerDefault();
				Map.show_marker(map);
			}
		});
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
		Map.params.zip_code = null;
		Map.params.lat = null;
		Map.params.lng = null;
	},

	eventOnclick: function(map){
	  	google.maps.event.addListener(map, 'click', function(e) {
	  		console.log(e);
	  		Map.closeInfoWindow();
	  		Map.reset_data();
			Map.geocoder(e.latLng);
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
		Topic.init(city);
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
			console.log(value);
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
            	Map.params.zipcode = zipcode;
            	Create_Topic.params.zip_code = zipcode;
            	Create_Topic.params.lat = Map.latLng.lat();
            	Create_Topic.params.lng = Map.latLng.lng();
            	Create_Topic.params.netwrk_name = data.places[0]['place name'];

            	Ajax.check_zipcode_exist(Map.params).then(function(data){
            		var json = $.parseJSON(data);
            		if (json.status == 0){
            			Topic.init($.now());
            		}else{
            			Topic.init(json.city);
            		}
            	});
            }
        });
	},
}