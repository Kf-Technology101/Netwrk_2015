var LandingPage = {
	modal:'#modal_landing_page',
	mobile:'#ld_modal_landing_page',
	parent:'',
	data:'',
	initialize: function(){
		if(isMobile){
			console.log('aaa');
			LandingPage.parent = LandingPage.mobile;
			LandingPage.GetDataTopLanding();
			LandingPage.UnsetSession();
			LandingPage.FixWidthPostLanding();

		} else {
			LandingPage.parent = LandingPage.modal;
			LandingPage.OnShowModalLanding();
			LandingPage.OnHideModalLanding();
			LandingPage.show_landing_page();
			LandingPage.OnClickBackdrop();
			set_heigth_modal_meet($('#modal_landing_page'), 4);
		}
	},

	FixWidthPostLanding: function(){
		var target = $(".top-post-content").find('.post');
		fix_width_post(target,160);
	},
	SetSession: function(){
		sessionStorage.show_landing = 1;
	},

	UnsetSession: function(){
		sessionStorage.show_landing = 0;
		sessionStorage.url_landing = location.href;
	},

	redirect: function(){
		LandingPage.SetSession();
		window.location.href = baseUrl + "/netwrk/default/landing-page";
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

        $(LandingPage.parent).find('.wrapper-container').append(append_html);
        LandingPage.onTemplateLanding();
	},

	onTemplateLanding: function(){
		LandingPage.CustomScrollBar();
		LandingPage.OnClickCommunities();
	},

	OnClickCommunities: function(){
		var target = $(LandingPage.parent).find('.communities-row');

		target.unbind();
		target.on('click',function(e){
			var city_id = $(e.currentTarget).attr('data-city');
			alert(city_id);
			if(isMobile){
				Topic.initialize(city_id);
			}else{
				$(LandingPage.modal).modal('hide');
				Topic.initialize(city_id);
			}
		});
	},

	OnShowModalLanding:function(){
        $(LandingPage.parent).on('shown.bs.modal',function(e) {
        	LandingPage.SetSession();
        	LandingPage.GetDataTopLanding();
        });
	},

	OnHideModalLanding: function(){
        $(LandingPage.modal).on('hidden.bs.modal',function(e) {
        	LandingPage.ResetData();
        });
	},

	ResetData: function(){
		$(LandingPage.modal).find('.wrapper-container').remove();
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