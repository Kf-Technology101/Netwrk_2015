var LandingPage = {
	initialize: function(){
		if(isMobile){
			var target = $(".top-post-content").find('.post');
			fix_width_post(target,160);
		} else {
			LandingPage.show_landing_page();
			LandingPage.OnClickBackdrop();
			LandingPage.CustomScrollBar();
			set_heigth_modal_meet($('#modal_landing_page'), 4);
		}
	},

	show_landing_page: function(){
		var parent = $('#modal_landing_page');
		parent.show();
		parent.modal({
			backdrop: true,
			keyboard: false,
		});
	},

	OnClickBackdrop: function(){
        $('.modal-backdrop.in').unbind();
        $('.modal-backdrop.in').click(function(e) {
            $('#modal_landing_page').modal('hide');
        });
    },

    CustomScrollBar: function(){
        $('#modal_landing_page').find('.modal-body').mCustomScrollbar({
            theme:"dark"
        });
    },
};