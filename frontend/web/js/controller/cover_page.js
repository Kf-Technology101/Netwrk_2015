var CoverPage = {
	accepted: false,
	btn: '',
	err: '',
	zcode: '',
	parent: '#cover-page',
	params:{
		text:''
	},
	result:'.cover-result-search',
	result_data:'',
	initialize: function(){
		var height = $(window).height();
		$('#cover-page').css('height',height);
		Common.hideTextLoader();
		CoverPage.getObject();
		CoverPage.hiddenMenuTop();
		CoverPage.onClickKey();
		CoverPage.onEnterZipcode();
		CoverPage.hiddenError();
		CoverPage.OnKeyPress();
		CoverPage.onClickShareLocation();
		if(isMobile){
			$("body").css('background', '#fff');
			$("body").find('#btn_my_location').addClass('hide');
			CoverPage.hiddenMobileFooter();
			CoverPage.hiddenMobileNavigation();
		}

		// Display cover page input
		/*var coverImg = $(CoverPage.parent).find('#coverImg');
		if (coverImg.prop('complete')) {
			$('#inputGroup').removeClass('hide');
		} else {
			coverImg.load(function(){
				$('#inputGroup').removeClass('hide');
			});
		}*/
	},

	OnKeyPress: function(){
		var target = $(CoverPage.parent).find('.cover-input');

		target.unbind('keyup');
		target.on('keyup',function(e){
			var len = $(e.currentTarget).val().length;
			CoverPage.params.text = $(e.currentTarget).val();
			if(len > 1){
				CoverPage.ShowCoverResult()
			}else{
				CoverPage.HideCoverResult()
			}
		});
	},

	ShowCoverResult: function(){
		Ajax.cover_search(CoverPage.params).then(function(res){
			CoverPage.result_data = $.parseJSON(res);
			CoverPage.ResetCoverResult();
			CoverPage.GetCoverTemplate();
			$(CoverPage.result).show();
		});
	},

	HideCoverResult: function(){
		var target = $(CoverPage.parent).find('.cover-input');
		$(CoverPage.result).hide();
		CoverPage.ResetCoverResult();
	},

	ResetCoverResult: function(){
		$(CoverPage.result).find('.result').empty();
	},

	onClickResult: function(){
		var target = $(CoverPage.result).find('.title-result');

		target.unbind('click');
		target.on('click',function(e){
			e.preventDefault();
			e.stopPropagation();

			$('#cv-location').val($(e.currentTarget).attr('data-value'));
			$(CoverPage.result).hide();
		});
	},

	GetCoverTemplate: function(){
		var list_template = _.template($("#cover_result" ).html());
		var append_html = list_template({result: CoverPage.result_data});

		$(CoverPage.result).find('.result').append(append_html);
		CoverPage.onClickResult();
	},

	getObject: function(){
		CoverPage.btn = $(CoverPage.parent).find(".input-group-addon");
		CoverPage.err = $(CoverPage.parent).find(".error");
		CoverPage.zcode = $(CoverPage.parent).find("#cv-location");
	},

	hiddenMenuTop: function(){
		var target = $(".navbar-fixed-top");
		target.addClass('hidden');
	},

	hiddenMobileFooter: function(){
		var target = $(".navbar-fixed-bottom");
		target.addClass('hidden');
	},

	hiddenMobileNavigation: function(){
		var target = $(".navigation-wrapper");
		target.addClass('hidden');
	},

	onClickKey: function(){
		var target = CoverPage.btn;
		target.unbind();
		target.on("click", function(e){
			var zcode = CoverPage.zcode;
			console.log(zcode.val());
			if(!isNaN(zcode.val())) {
				var res = /^\d{5}(-\d{4})?$/.test(zcode.val());
				CoverPage.checkZipCode(zcode.val());
			} else {
				CoverPage.checkCity(zcode.val());
			}
			e.preventDefault();
		});
	},

	hiddenError: function(){
		var zcode = CoverPage.zcode;
		var target = CoverPage.err;
		zcode.on("input focusout", function(){
			target.addClass('hidden');
		});
		
	},

	onEnterZipcode: function(){
        var btn = CoverPage.btn;
        var zcode = CoverPage.zcode;
        // btn.unbind();
        zcode.keypress(function( event ) {
            if ( event.which == 13 ) {
                btn.trigger('click');
            }
        });
    },

	showError: function(){
		var error = CoverPage.err;
		var zcode = CoverPage.zcode;
		zcode.val(null);
		error.removeClass("hidden");
	},

	checkZipCode: function(zipcode){
		//var arr = [46037,'46037',44115,'44115',46040,'46040'];

		//if(jQuery.inArray( zipcode, arr ) > -1){
			var url = '';

			Common.initTextLoader();

			if (location.protocol.indexOf('https') >= 0){
				url = "https://api.zippopotam.us/us/"+zipcode;
			} else {
				url = "http://api.zippopotam.us/us/"+zipcode;
			}

			$.getJSON(url,function(data){
				var params = data;
				Ajax.set_cover_cookie(params).then(function(data){
					//console.log(data);
					//sessionStorage.cover_input = 1;
					window.location.href = baseUrl + "/netwrk/chat-inbox?current=area_news";
				});
			}).fail(function(jqXHR) {
				CoverPage.showError();
			});
			Common.initTextLoader();
		/*} else {
			CoverPage.showError();
		}*/
	},

	checkCity: function(zipcode){
		$.getJSON("https://maps.googleapis.com/maps/api/geocode/json?address="+zipcode ,function(data){
			var params = data,
				state = '',
				stateAbbr = '',
				country = '';

			if(typeof params.results[0] != 'undefined') {
				var len = params.results[0].address_components.length;

				for (var i = 0; i < len; i++) {
					if (params.results[0].address_components[i].types[0] == 'country') {
						country = data.results[0].address_components[i].short_name;
					}

					if (params.results[0].address_components[i].types[0] == 'administrative_area_level_1') {
						state = data.results[0].address_components[i].long_name;
						stateAbbr = data.results[0].address_components[i].short_name;
					}
				}

				if (country == 'US') {
					var postParams = {
						'post code': '',
						'country': 'United States',
						'country abbreviation': 'US',
						'places': [{
							'place name': params.results[0].address_components[0].long_name,
							'state': state,
							'state abbreviation': stateAbbr,
							'latitude': params.results[0].geometry.location.lat,
							'longitude': params.results[0].geometry.location.lng
						}]
					};

					var lat = params.results[0].geometry.location.lat,
						lng = params.results[0].geometry.location.lng;
					CoverPage.findCurrentZip(lat, lng);
					/*Ajax.set_cover_cookie(postParams).then(function(data){
						sessionStorage.cover_input = 1;
						window.location.href = baseUrl; //+ "/netwrk/default/home";
					});*/
				} else {
					CoverPage.showError();
				}
			} else {
				CoverPage.showError();
			}
		}).fail(function(jqXHR) {
			CoverPage.showError();
		});
	},
	onClickShareLocation: function () {
		var btn = $('.share-location-btn', CoverPage.parent);
		btn.unbind();
		btn.on('click',function(){
			navigator.geolocation.getCurrentPosition(
				function(position) {
					var pos = {
						lat: position.coords.latitude,
						lng: position.coords.longitude
					};
					console.log(pos);
					CoverPage.findCurrentZip(pos.lat, pos.lng);
					//on local test
					/*var tempLat = 39.9559,
						tempLng = -85.9601;
					CoverPage.findCurrentZip(tempLat, tempLng);*/
				},
				function(error) {
					console.log(error);
					var callback = CoverPage.hideShareLocationButton;
					CoverPage.handle_geolocation_error(error, callback);
				}, {
					enableHighAccuracy: false,
					timeout : 50000
				}
			);
		});
	},
	findCurrentZip: function(lat, lng) {
		$.getJSON("https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+','+lng ,function(data) {
			var params = data,
				state = '',
				stateAbbr = '',
				country = '',
				zip = '',
				city = '';

			if(typeof params.results[0] != 'undefined') {
				var len = params.results[0].address_components.length;

				for (var i = 0; i < len; i++) {
					if (params.results[0].address_components[i].types[0] == 'country') {
						country = data.results[0].address_components[i].short_name;
					}

					if (params.results[0].address_components[i].types[0] == 'administrative_area_level_1') {
						state = data.results[0].address_components[i].long_name;
						stateAbbr = data.results[0].address_components[i].short_name;
					}

					if (data.results[0].address_components[i].types[0] == 'postal_code') {
						zip = data.results[0].address_components[i].long_name;
					}

					if (data.results[0].address_components[i].types[0] == 'locality') {
						city = data.results[0].address_components[i].long_name;
					}
				}

				if (country == 'US') {
					var postParams = {
						'post code': zip,
						'country': 'United States',
						'country abbreviation': country,
						'places': [{
							'place name': city,
							'state': state,
							'state abbreviation': stateAbbr,
							'latitude': params.results[0].geometry.location.lat,
							'longitude': params.results[0].geometry.location.lng
						}]
					};

					console.log(postParams);
					Ajax.set_cover_cookie(postParams).then(function(data){
						window.location.href = baseUrl + "/netwrk/chat-inbox?current=area_news";
					});
				} else {
					CoverPage.showError();
				}
			} else {
				CoverPage.showError();
			}
		}).fail(function(jqXHR) {
			CoverPage.showError();
		});
	},
	handle_geolocation_error: function(error, callback) {
		switch(error.code)
		{
			case error.PERMISSION_DENIED:
				console.log('Geo location PERMISSION_DENIED');
				if($.isFunction(callback)){
					callback();
				}
				break;

			case error.POSITION_UNAVAILABLE:
				console.log('Geo location POSITION_UNAVAILABLE');
				if($.isFunction(callback)){
					callback();
				}
				break;

			case error.TIMEOUT:
				/*alert('Geo location timeout');*/
				console.log('Geo location timeout');
				if($.isFunction(callback)){
					callback();
				}
				break;

			default:
				/*alert('Geo location unknown error');*/
				console.log('Geo location unknown error');
				if($.isFunction(callback)){
					callback();
				}
				break;
		}
	},
	hideShareLocationButton: function() {
		var btn = $('.share-location-btn', CoverPage.parent);
		btn.hide();
		$('.or-text', CoverPage.parent).hide();
		$('#inputGroup', CoverPage.parent).removeClass('hide');
		$('#cv-location').focus();
	}
}