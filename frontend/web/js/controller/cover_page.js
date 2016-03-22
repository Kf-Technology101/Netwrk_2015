var CoverPage = {
	accepted: false,
	btn: '',
	err: '',
	zcode: '',
	initialize: function(){
		CoverPage.getObject();
		CoverPage.hiddenMenuTop();
		CoverPage.onClickKey();
		CoverPage.onEnterZipcode();
		CoverPage.hiddenError();
		if(isMobile){
			$("body").css('background', '#fff');
			CoverPage.hiddenMobileFooter();
			CoverPage.hiddenMobileNavigation();
		}
	},

	getObject: function(){
		CoverPage.btn = $("#cover-page").find(".input-group-addon");
		CoverPage.err = $("#cover-page").find(".error");
		CoverPage.zcode = $("#cover-page").find("#cv-password");
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
			// var res = /^\d{5}(-\d{4})?$/.test(zcode.val());
			CoverPage.checkzipcode(zcode.val());
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

    checkzipcode: function(zipcode){
		$.getJSON("http://api.zippopotam.us/us/"+zipcode ,function(data){
			var params = data;
			Ajax.set_cover_cookie(params).then(function(data){
				window.location.href = baseUrl; //+ "/netwrk/default/home";
			});
			//sessionStorage.accepted = true;
			// if (isMobile){
				// window.location.href = baseUrl + "/netwrk/default/home";
			// } else {
				// window.location.href = baseUrl + "/netwrk/default/home";
			// }
		}).fail(function(jqXHR) {
			var error = CoverPage.err;
			var zcode = CoverPage.zcode;
			zcode.val(null);
			error.removeClass("hidden");
		});
	}
}