var CoverPage = {
	accepted: false,
	initialize: function(){
		CoverPage.hiddenMenuTop();
		CoverPage.onClickKey();
		if(isMobile){
			CoverPage.hiddenMobileFooter();
		}
	},

	hiddenMenuTop: function(){
		var target = $(".navbar-fixed-top");
		target.addClass('hidden');
	},

	hiddenMobileFooter: function(){
		var target = $(".navbar-fixed-bottom");
		target.addClass('hidden');
	},

	onClickKey: function(){
		var target = $("#cover-page").find(".input-group-addon");
		target.unbind();
		target.on("click", function(){

			var zcode = $("#cover-page").find("#cv-password");
			var res = /^\d{5}(-\d{4})?$/.test(zcode.val());
			
			if (res) {
				sessionStorage.accepted = true;
				if (isMobile){
					window.location.href = baseUrl + "/netwrk/default/home";
				} else {
					//
				}
			} else {
				var error = $("#cover-page").find(".error");
				error.removeClass("hidden");
			}
		});
	},
}