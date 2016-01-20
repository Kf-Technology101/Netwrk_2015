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
			
			LandingPage.OnClickMeetLandingMobile();
		} else {
			LandingPage.parent = LandingPage.modal;
			LandingPage.OnShowModalLanding();
			LandingPage.OnHideModalLanding();
			LandingPage.show_landing_page();
			LandingPage.OnClickBackdrop();
			LandingPage.OnClickMeetLandingDesktop();
			// set_heigth_modal_meet($('#modal_landing_page'), 4);
		}
	},

	FixWidthPostLanding: function(){
		var target = $(".top-post-content").find('.post');
		fix_width_post(target,160);
		console.log('in fix post landing');
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
		LandingPage.OnClickAvatarLanding();
		LandingPage.OnClickNetwrk();
		LandingPage.OnClickTopic();
		LandingPage.OnClickPost();
		LandingPage.OnClickMyCommunities();
		LandingPage.OnClickExplore();
		LandingPage.OnClickVote();
		LandingPage.OnClickChat();
		if(isMobile){
			LandingPage.FixWidthPostLanding();
		}
	},

	OnClickChat: function(){
		var target = $(LandingPage.parent).find('.top-post .action .chat');
		target.unbind();
		target.on('click',function(e){
			console.log($(e.currentTarget));
				var post_id = $(e.currentTarget).parent().parent().attr('data-value'),
					post_name = $(e.currentTarget).parent().parent().find('.post-title').text(),
					post_content = $(e.currentTarget).parent().parent().find('.post-content').text();
			if(isMobile){
				PopupChat.RedirectChatPostPage(post_id, 1, 1);
			}else{
				$(LandingPage.modal).modal('hide');
				PopupChat.params.post = post_id;
                PopupChat.params.chat_type = 1;
                PopupChat.params.post_name = post_name;
                PopupChat.params.post_description = post_content;
                PopupChat.initialize();
			}
		});
	},

	OnClickVote: function(){
		var target = $(LandingPage.parent).find('.top-post .action .brilliant');
		target.unbind();

		target.on('click',function(e){
			var post_id = $(e.currentTarget).parent().parent().attr('data-value');
   			Ajax.vote_post({post_id: post_id}).then(function(res){
   				var json = $.parseJSON(res);
   				$(e.currentTarget).text(json.data);
   			});
		});
	},

	OnClickExplore: function(){
		var target = $(LandingPage.parent).find('.btn-explore');
		target.unbind();
		target.on('click',function(e){
			if(isMobile){
				window.location.href = baseUrl;
			}else{
				$(LandingPage.parent).modal('hide');
			}
		});
	},

	OnClickMyCommunities: function(){
		var target = $(LandingPage.parent).find('.btn-my-community');
		target.unbind();
		target.on('click',function(e){
			$(LandingPage.parent).modal('hide');
			LandingPage.ShowMyCommunities();
		});
	},

	ShowMyCommunities: function(){
		if(isGuest){
			if(isMobile){
				Login.RedirectLogin()
			}else{
				Login.initialize();
			}
		}else{
			var infoUser = $.parseJSON(sessionStorage.UserInfo);
			if(isMobile){
				Topic.initialize(infoUser.city_id);
			}else{
				$(LandingPage.modal).modal('hide');
				Topic.initialize(infoUser.city_id);
			}
		}
	},

	OnClickPost: function(){
		var target = $(LandingPage.parent).find('.top-post .post');
		target.unbind();
		target.on('click',function(e){
			console.log($(e.currentTarget));
				var post_id = $(e.currentTarget).parent().attr('data-value'),
					post_name = $(e.currentTarget).find('.post-title').text(),
					post_content = $(e.currentTarget).find('.post-content').text();
			if(isMobile){
				PopupChat.RedirectChatPostPage(post_id, 1, 1);
			}else{
				$(LandingPage.modal).modal('hide');
				PopupChat.params.post = post_id;
                PopupChat.params.chat_type = 1;
                PopupChat.params.post_name = post_name;
                PopupChat.params.post_description = post_content;
                PopupChat.initialize();
			}
		});
	},

	OnClickTopic: function(){
		var target = $(LandingPage.parent).find('.topic-row');

		target.unbind();
		target.on('click',function(e){
			console.log($(e.currentTarget));
			var city_id = $(e.currentTarget).attr('data-city'),
				city_name = $(e.currentTarget).attr('data-city-name'),
				topic_id = $(e.currentTarget).attr('data-value'),
				topic_name = $(e.currentTarget).find('.topic-title').text();
			if(isMobile){
				Post.RedirectPostPage(topic_id);
			}else{
				$(LandingPage.modal).modal('hide');
                Post.params.topic = topic_id;
                Post.params.topic_name = topic_name;
                Post.params.city = city_id;
                Post.params.city_name = city_name;
                Post.initialize();
			}
		});

	},

	OnClickNetwrk: function(){
		var target = $(LandingPage.parent).find('.communities-row');

		target.unbind();
		target.on('click',function(e){
			console.log($(e.currentTarget));
			var city_id = $(e.currentTarget).attr('data-city');
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