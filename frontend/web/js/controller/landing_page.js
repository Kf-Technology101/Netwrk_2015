var LandingPage = {
	modal:'#modal_landing_page',
	parent:'#modal_landing_page',
	data:'',
	initialize: function(){
		if(isMobile){
			var target = $(".top-post-content").find('.post');
			fix_width_post(target,160);
		} else {
			LandingPage.OnShowModalLanding();
			LandingPage.OnHideModalLanding();
			LandingPage.show_landing_page();
			LandingPage.OnClickBackdrop();
			// LandingPage.CustomScrollBar();
			set_heigth_modal_meet($('#modal_landing_page'), 4);
		}
		LandingPage.SetSession();
	},

	SetSession: function(){
		sessionStorage.show_landing = true;
	},

	redirect: function(){

	},

	GetDataTopLanding: function(){
		Ajax.top_landing().then(function(res){
			LandingPage.data = $.parseJSON(res);
			LandingPage.GetTemplate();
		});
	},

	GetTemplate: function(){
		var list_template = _.template($( "#landing_page" ).html());
        var append_html = list_template({landing: LandingPage.data});

        $(LandingPage.parent).find('.modal-body').append(append_html);
        // self.onTemplate(json);
	},

	OnShowModalLanding:function(){
        $(LandingPage.parent).on('shown.bs.modal',function(e) {
        	LandingPage.GetDataTopLanding();
        });
	},

	OnHideModalLanding: function(){
        $(LandingPage.modal).on('hidden.bs.modal',function(e) {

        });
	},

	show_landing_page: function(){
		var parent = $('#modal_landing_page');
		// parent.show();
		parent.modal({
			backdrop: true,
			keyboard: false,
		});
	},

	OnClickBackdrop: function(){
        $('.modal-backdrop.in').unbind();
        $('.modal-backdrop.in').click(function(e) {
            $(LandingPage.modal).modal('hide');
        });
    },

    CustomScrollBar: function(){
        $(LandingPage.modal).find('.modal-body').mCustomScrollbar({
            theme:"dark"
        });
    },
};