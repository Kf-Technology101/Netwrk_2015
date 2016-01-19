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
			LandingPage.OnClickMeetLandingMobile();
		} else {
			LandingPage.parent = LandingPage.modal;
			LandingPage.OnShowModalLanding();
			LandingPage.OnHideModalLanding();
			LandingPage.show_landing_page();
			LandingPage.OnClickBackdrop();
			LandingPage.OnClickMeetLandingDesktop();
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
		LandingPage.OnClickAvatarLanding();
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

    OnClickAvatarLanding: function(){
    	var avatar = $('.top-post').find('.top-post-content .post-row .avatar');
		avatar.unbind();
		avatar.on('click', function(e){
			var user_login = $(e.currentTarget).parent().attr('data-user');
			if(user_login != UserLogin){
				if(!isMobile){
					Meet.pid = 0;
	                Meet.ez = user_login;
					$('.modal').modal('hide');
					Meet.initialize();
				} else {
					window.location.href = baseUrl + "/netwrk/meet?user_id=" + user_login + "&from=discussion";
				}
			}
		});
    },

    OnClickMeetLandingDesktop: function(){
    	var meet = $(LandingPage.modal).find('.modal-footer .btn-meet');
		meet.unbind();
		meet.on('click', function(e){
			Meet.pid = 0;
            Meet.ez = 0;
			$('.modal').modal('hide');
			Meet.initialize();
		});
    },

    OnClickMeetLandingMobile: function(){
    	var meet = $(LandingPage.mobile).find('.ld-modal-footer .btn-meet');
		meet.unbind();
		meet.on('click', function(e){
			window.location.href = baseUrl + "/netwrk/meet";
		});
    },
};