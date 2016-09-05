var LandingPage = {
	modal:'#modal_landing_page',
	mobile:'#ld_modal_landing_page',
	modal_welcome : '#modal_landing_welcome',
	modal_channel_welcome : '#modal_landing_channel_welcome',
	netwrk_news: '#netwrkNews',
	parent:'',
	data:'',
	check_landing: 0,
	initialize: function(){
		if(isMobile){
			LandingPage.parent = LandingPage.mobile;
			//LandingPage.GetDataTopLanding();
			LandingPage.SetUrl();
			LandingPage.OnClickMeetLandingMobile();
			$('.navbar-fixed-bottom').hide();
			if(welcomePage == 'true') {
				LandingPage.OnHideModalWelcome();
				LandingPage.OnClickBackdropWelcome();
				LandingPage.showLandingWelcome();
			}
		} else {
			LandingPage.parent = LandingPage.modal;
			LandingPage.OnShowModalLanding();
			LandingPage.OnShowHideModalLanding();
			if(welcomePage == 'true') {
				LandingPage.OnHideModalWelcome();
				LandingPage.OnClickBackdropWelcome();
				LandingPage.showLandingWelcome();
			} else {
				//LandingPage.show_landing_page();
				// Added timeout and updated landing modal show code, so map load quickly
				if (isGuest == '') {
					setTimeout(function () {
						console.log('Show feed called');
						//LandingPage.GetDataTopLanding();
					}, 500);
				} else {
					setTimeout(function () {
						console.log('Show feed called');
						//LandingPage.GetDataTopLanding();
					}, 800);
				}
			}

			LandingPage.OnClickBackdrop();
			LandingPage.OnClickMeetLandingDesktop();
			set_heigth_modal_meet($('#modal_landing_page'), 0, 550, 430);
		}
		LandingPage.switchSearchText();
	},

	switchSearchText: function(){
		var target = $('.box-search').find('.input-search');
		setTimeout(function(){
			target.attr('placeholder','What are you into?')
		},6000);
	},

	FixWidthPostLanding: function(){
		var target = $(".top-post-content").find('.post');
		fix_width_post(target,146);
	},

	SetSession: function(){
		sessionStorage.show_landing = 2;
	},

	SetUrl: function(){
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
			if(!isMobile) {
				//Check if on boarding modal is not open then only show landing modal
				if(!sessionStorage.on_boarding || sessionStorage.on_boarding == 0){
					if(isLogoGlow == false) {
						//LandingPage.show_landing_page();
					}
				}

				if(sessionStorage.netwrk_news == 1){
					sessionStorage.netwrk_news = 0;
					LandingPage.show_landing_page();
				}
			} else {
				$(LandingPage.netwrk_news).animate({
					"left": "0px"
				}, 500);
				$('#netwrkNavigation').animate({
					"left": "-200px"
				}, 500);
				$(ChatInbox.chat_inbox).animate({
					"left": ChatInbox.list_chat_post_right_hidden
				}, 500);
			}
		});
	},

	GetTemplate: function(){
		/*var list_template = _.template($( "#landing_page" ).html());
        var append_html = list_template({landing: LandingPage.data});

        $(LandingPage.parent).find('.wrapper-container').append(append_html);*/

		/*var header_template = _.template($( "#landing_header" ).html());
		var header_html = header_template({landing: LandingPage.data.hq_post});

		$(LandingPage.parent).find('#headerButtonWrapper').html(header_html);*/

		// Netwrk news starts here
		var list_template = _.template($( "#netwrk_news" ).html());
		var append_html = list_template({landing: LandingPage.data});

		$(LandingPage.netwrk_news).find('.content-wrapper').append(append_html);

		var header_template = _.template($( "#netwrk_header" ).html());
		var header_html = header_template({landing: LandingPage.data.hq_post});

		$(LandingPage.netwrk_news).find('.header-wrapper').html(header_html);
		// Netwrk news ends here

		LandingPage.onTemplateLanding();
	},

	onTemplateLanding: function(){
		LandingPage.OnClickAreaTalk();
		LandingPage.CustomScrollBar();
		LandingPage.OnClickAvatarLanding();
		LandingPage.OnClickNetwrk();
		LandingPage.OnClickTopic();
		LandingPage.OnClickPost();
		LandingPage.OnClickMyCommunities();
		LandingPage.OnClickExplore();
		LandingPage.OnClickVote();
		LandingPage.OnClickChat();
		LandingPage.onClickHelpLandingDesktop();

		// Initialize click on topic name
		Topic.OnClickTopicFeed();

		// Initialize click on post name
		Topic.OnClickPostFeed();

		if(isMobile){
			LandingPage.FixWidthPostLanding();
		}
		LandingPage.onNetwrkLogo();
	},

	OnClickAreaTalk: function(){
		var target = $(LandingPage.parent).find('.btn-area-talk')
				.add($(LandingPage.netwrk_news).find('.btn-area-talk'));
		target.unbind();
		target.on('click',function(e){
			var post_id = $(e.currentTarget).attr('data-value'),
				post_name = $(e.currentTarget).attr('data-title'),
				post_content = $(e.currentTarget).attr('data-content');
			if(isMobile){
				sessionStorage.landing_post = 1;
				sessionStorage.welcome_channel = 1;
				PopupChat.RedirectChatPostPage(post_id, 1, 1);
			}else{
				// Display channel welcome modal
				LandingPage.showLandingChannelWelcome();
				PopupChat.params.post = post_id;
				PopupChat.params.chat_type = 1;
				PopupChat.params.post_name = post_name;
				PopupChat.params.post_description = post_content;
				PopupChat.initialize();
			}
		});
	},

	OnClickChat: function(){
		var target = $(LandingPage.parent).find('.top-post .action .chat')
				.add($(LandingPage.netwrk_news).find('#mostActive .action .chat'))
				.add($(ChatInbox.chat_inbox).find('#most_active_tab').find('.post-row  .action .chat'));
		target.unbind();
		target.on('click',function(e){
				var post_id = $(e.currentTarget).parent().parent().attr('data-value'),
					post_name = $(e.currentTarget).parent().parent().find('.post-title').text(),
					post_content = $(e.currentTarget).parent().parent().find('.post-content').text();
			if(isMobile){
				sessionStorage.landing_post = 1;
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
		var target = $(LandingPage.parent).find('.top-post .action .brilliant')
				.add($(LandingPage.netwrk_news).find('#mostActive .action .brilliant'));
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
				sessionStorage.show_landing = 1;
				window.location.href = baseUrl + "/netwrk/default/home";
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
			sessionStorage.topic_tab_current = 'feed';
		}
	},

	OnClickPost: function(){
		var target = $(LandingPage.parent).find('.top-post .post')
				.add($(LandingPage.netwrk_news).find('#mostActive .post'))
				.add($(ChatInbox.chat_inbox).find('#most_active_tab').find('.post-row .post'));
		target.unbind();
		target.on('click',function(e){
				var post_id = $(e.currentTarget).parent().attr('data-value'),
					post_name = $(e.currentTarget).find('.post-title').text(),
					post_content = $(e.currentTarget).find('.post-content').text();
			if(isMobile){
				sessionStorage.landing_post = 1;
				PopupChat.RedirectChatPostPage(post_id, 1, 1);
			}else{
				// $(LandingPage.modal).modal('hide');
				PopupChat.params.post = post_id;
                PopupChat.params.chat_type = 1;
                PopupChat.params.post_name = post_name;
                PopupChat.params.post_description = post_content;
                PopupChat.initialize();
			}
		});
	},

	OnClickTopic: function(){
		var target = $(LandingPage.parent).find('.topic-row')
				.add($(LandingPage.netwrk_news).find('.topic-row'));

		target.unbind();
		target.on('click',function(e){
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
		var target = $(LandingPage.parent).find('.communities-row')
				.add($(LandingPage.netwrk_news).find('.communities-row'));

		target.unbind();
		target.on('click',function(e){
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
        	//LandingPage.GetDataTopLanding();
			$('.logo_netwrk > a').addClass('landing-shown');
        });
        // on Click logo when modal was shown
        $('.logo_netwrk > a').on('click', function(evt){
			if(isMobile) {
	        	evt.preventDefault();
				return false;
			} else {
	        	evt.preventDefault();
	        	var _this = $(this);
	        	if (_this.hasClass('landing-shown')) {
	        		_this.removeClass('landing-shown');
	        		$(LandingPage.modal).modal('hide');
					$(LandingPage.modal).trigger('off');
	        		return false;
	        	} else {
					$(LandingPage.modal).modal('show');
	        		return false;
	        	}
			}
        })
	},

	OnShowHideModalLanding: function(){
		$(LandingPage.modal).on('shown.bs.modal',function(e) {
			// Display near button popover
			Common.showHideInfoPopover('popover-near', 'nw_popover_near');
		});
        $(LandingPage.modal).on('hidden.bs.modal',function(e) {
        	// LandingPage.ResetData();
        	$('.logo_netwrk > a').removeClass('landing-shown');
			// hide near button popover
			$('.popover-near').popover('hide');
        });
	},

	ResetData: function(){
		$(LandingPage.modal).find('.wrapper-container').remove();
	},

	showLandingWelcome: function(){
		var parent = $(LandingPage.modal_welcome);
		// parent.show();
		parent.modal({
			backdrop: true,
			keyboard: false
		});

		setTimeout(function(){
			parent.modal('hide');
		},4000);
	},

	OnHideModalWelcome: function(){
		$(LandingPage.modal_welcome).on('hidden.bs.modal',function(e) {
			Ajax.set_welcome_cookie().then(function(data){
				if(isMobile) {
					// Display chat inbox
					// ChatInbox.OnClickChatInboxMobile();
				} else {
					//LandingPage.show_landing_page();
					//LandingPage.GetDataTopLanding();
					// Display chat inbox
					//ChatInbox.initialize();
					Common.showHideInfoPopover('popover-logo', 'nw_popover_logo');
				}

				if(sessionStorage.cover_input == 1){
					sessionStorage.cover_input = 0;
					sessionStorage.netwrk_news = 1;
					// Show netwrk news section open
					LandingPage.GetDataTopLanding();
					// Zoom map to 16
					Map.smoothZoom(Map.map, 16, 12, true);
				} else {
					//todo: show area slider
					Common.showAreaSlider();
				}
			});

		});
	},

	showLandingChannelWelcome: function(){
		$(LandingPage.modal).modal('hide');
		var parent = $(LandingPage.modal_channel_welcome);
		// parent.show();
		parent.modal({
			backdrop: true,
			keyboard: false
		});
	},

	OnClickBackdropWelcome: function(){
		$('.modal-backdrop.in').unbind();
		$('.modal-backdrop.in').click(function(e) {
			$(LandingPage.modal_welcome).modal('hide');
		});
	},

	show_landing_page: function(){
		/*var parent = $('#modal_landing_page');
		// parent.show();
		parent.modal({
			backdrop: true,
			keyboard: false,
		});*/

		$(LandingPage.netwrk_news).animate({
			"left": "0px"
		}, 500);
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

		// Netwrk news starts here
		var parent = $(LandingPage.netwrk_news).find('.content-wrapper');
		parent.css('height', $(window).height()-110);
		if ($(parent).find("div[id^='mSCB']").length == 0) {
			$(parent).mCustomScrollbar({
				theme:"dark",
			});
		};
		// Netwrk news ends here
    },

    OnClickAvatarLanding: function(){
    	var avatar = $('.top-post').find('.top-post-content .post-row .avatar')
				.add($('#collapseFavoriteCommunities').find('.feed-post .avatar-poster'))
				.add($(LandingPage.netwrk_news).find('.post-row .avatar'))
				.add($(LandingPage.netwrk_news).find('.feed-post .avatar-poster'));
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

    onNetwrkLogo: function(){
        $('#modal_landing_page .modal-header .header a').click(function(){
            $('#modal_landing_page').modal('hide');
        });
    },

    /**
     * Listen event `click` on Help Button of Landing Page
     *
     * @return boolean
     */
    onClickHelpLandingDesktop: function() {
    	// listening event
    	$('.landing-btn.btn-help').on('click', function(evt){
    		// prevent other binding
    		evt.preventDefault();
	    		// begin loading pop-up
	    		// define location
	    		Ajax.get_marker_help().then(function(data){
    				if(!isMobile) {
	    				$(LandingPage.modal).modal('hide');
	    			}
		    		var _location = $.parseJSON(data);
		    		if(isMobile) {
		    			sessionStorage.map_zoom = 18;
		    			sessionStorage.lat = _location.lat;
		    			sessionStorage.lng = _location.lng;
					    setTimeout(function(){
		    				window.location.href = baseUrl + "/netwrk/topic/topic-page?city=" + _location.id + '&current=help';
					    }, 500);
		    		} else {
			    		var _map  = Map.map;
			    		var _zoom = 18;
			    		_map.setCenter(new google.maps.LatLng(_location.lat, _location.lng));
					    _map.setZoom(_zoom);
					    setTimeout(function(){
					    	Topic.data.name = 'Netwrk hq';
					    	Topic.initialize(_location.id, {name: 'Netwrk hq'});}, 500);
					    Map.initializeMarker(_location, _map, _zoom);
					}
	    		});
    	});
    }
};
