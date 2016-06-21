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
		Common.hideLoader();
		CoverPage.getObject();
		CoverPage.hiddenMenuTop();
		CoverPage.onClickKey();
		CoverPage.onEnterZipcode();
		CoverPage.hiddenError();
		//CoverPage.OnKeyPress();
		if(isMobile){
			$("body").css('background', '#fff');
			$("body").find('#btn_my_location').addClass('hide');
			CoverPage.hiddenMobileFooter();
			CoverPage.hiddenMobileNavigation();
		}

		// Display cover page input
		var coverImg = $(CoverPage.parent).find('#coverImg');
		if (coverImg.prop('complete')) {
			$('#inputGroup').removeClass('hide');
		} else {
			coverImg.load(function(){
				$('#inputGroup').removeClass('hide');
			});
		}
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

			$('#cv-password').val($(e.currentTarget).attr('data-value'));
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
		CoverPage.zcode = $(CoverPage.parent).find("#cv-password");
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

			if(!isNaN(zcode.val())) {
				// var res = /^\d{5}(-\d{4})?$/.test(zcode.val());
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
		$.getJSON("http://api.zippopotam.us/us/"+zipcode ,function(data){
			var params = data;
			Ajax.set_cover_cookie(params).then(function(data){
				window.location.href = baseUrl; //+ "/netwrk/default/home";
			});
		}).fail(function(jqXHR) {
			CoverPage.showError();
		});
	},

	checkCity: function(zipcode){
		$.getJSON("http://maps.googleapis.com/maps/api/geocode/json?address="+zipcode ,function(data){
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

					Ajax.set_cover_cookie(postParams).then(function(data){
						window.location.href = baseUrl; //+ "/netwrk/default/home";
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
	}
}