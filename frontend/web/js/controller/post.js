var Post ={
	params:{
		filter:'post',
		city:'',
		city_name:'',
		topic:'',
		topic_name:'',
		size: 30,
		page: 1
	},
    list:{
        post:{
            paging:1,
            status_paging: 1,
            loaded: 0
        },
        view:{
            paging:1,
            status_paging: 1,
            loaded: 0
        },
        brilliant:{
            paging:1,
            status_paging: 1,
            loaded: 0
        }
    },
    feed:{
        paging:1,
        status_paging: 1,
        loaded: 0
    },
    modal: '#list_post',
    modal_create: '#create_post',
	tab_current:'post',
	slider: '#slider_list_post',
	initialize: function(){
		if(isMobile){
			Post.GetDefaultValue();
			Post.GetDataOnTab();
			Post.OnClickSortBtn();
			Post.FilterTabPost($('body'));
			Post.OnClickMeetIconMobile();
			Post.OnClickSelectFilter();
			Post.LazyLoading();
			Create_Post.initialize();
			Default.ShowNotificationOnChat();
			Post.getBrilliantCode();
		}else{
			// Post.ShowSideBar(Post.params.city_name,Post.params.topic_name);
			Post.ShowModalPost();
			Post.OnShowModalPost();
			Post.GetDataOnTab();
			Post.OnHideModal();
			Post.FilterTabPost($('.container_post'));
			Post.getNameTopic();
			Post.ShowMeetIcon();
			Post.CustomScrollBar();
			Post.OnClickBackdrop();
			Topic.displayPositionModal();
			Default.onCLickModal();
			Post.getBrilliantCode();
		}
		Post.getStreamData();
		Ajax.update_view_topic({topic: Post.params.topic});
		Post.OnclickBack();
		Post.OnclickCreate();
		Post.OnChangeTab();
		Post.OnNetwrkLogo();
	},

	getStreamTemplate: function(parent,data){
		var self = this;
		var list_template = _.template($("#stream_list" ).html());
		var append_html = list_template({streams: data});

		parent.html(append_html);
	},

	getStreamData: function(){
		var streamTrigger = $('#list_post, #slider_list_post').find('.stream-trigger');

		streamTrigger.unbind();
		streamTrigger.on('click',function(e){
			var ajaxCall = true,
				panelCall = false,
				streamCount = $(this).attr('data-count'),
				stream_type = $(this).attr('data-type'),
				streamWrapper = $(this).closest('.panel-post-stream-title').find('.stream-filters'),
				panelTrigger = $(this).closest('.panel-post-stream-title').find('.panel-trigger'),
				collapseId = $(this).closest('.panel-post-stream-title').find('.panel-trigger').attr('aria-controls'),
				parent = $('#'+collapseId).find('#streamWrapper');

			streamWrapper.find('.stream-trigger').each(function(){
				$(this).removeClass('active');
			});

			if($(this).hasClass('panel-trigger')){
				if($(this).hasClass('collapsed')){
					streamWrapper.find('.'+stream_type+'-stream').addClass('active');
				} else {
					ajaxCall = false;
				}
			} else {
				if($(this).closest('.panel-post-stream-title').find('.panel-trigger').hasClass('collapsed')){
					panelCall = true;
				}
				streamWrapper.find('.'+stream_type+'-stream').addClass('active');
			}

			if(streamCount > 0 && ajaxCall){
				var post_id = $(this).attr('data-post-id'),
					params = {'post_id': post_id, 'stream_type': stream_type};

				Ajax.getStreamByTopic(params).then(function(data){
					var json = $.parseJSON(data);
					Post.getStreamTemplate(parent,json.data);
					Post.OnClickChat();
					if(isMobile){
						var infomation = $('.panel-stream').find('.panel-stream-body .information');
						fix_width_post(infomation,122);
					}
				});
			} else {
				parent.html('<p class="no-data">There is no data available yet</p>');
			}

			if(panelCall){
				panelTrigger.removeClass('collapsed');
				$('#'+collapseId).addClass('in').css('height','auto');
			}
		});
	},

	OnClickChat: function(){
		var btn = $('#list_post,#slider_list_post').find('.post_chat')
				.add($('#list_post,#slider_list_post').find('.post_name'))
				.add($('#list_post,#slider_list_post').find('.post_massage'))
				.add($('#list_post,#slider_list_post').find('.show_more'))
				.add($('#list_post,#slider_list_post').find('.chat-trigger'));

		btn.unbind();
		btn.on('click',function(e){

			var item_post = $(e.currentTarget).closest('.item-post-panel-body').attr('data-item');
			if(isMobile){
				sessionStorage.scrollToMsg = 0;
				sessionStorage.feedbackTrigger = 0;

				// To scroll to particular message on popup chat
				if($(e.currentTarget).hasClass('jump-to')){
					sessionStorage.scrollToMsg = $(e.currentTarget).attr('data-id');
				}

				// To trigger feedback on popup chat
				if($(e.currentTarget).hasClass('respond-to')){
					sessionStorage.feedbackTrigger = $(e.currentTarget).attr('data-id');
				}

				PopupChat.RedirectChatPostPage(item_post, 1, 0);
			}else{
				PopupChat.scrollToMsg = 0;
				PopupChat.feedbackTrigger = 0;

				// To scroll to particular message on popup chat
				if($(e.currentTarget).hasClass('jump-to')){
					PopupChat.scrollToMsg = $(e.currentTarget).attr('data-id');
				}

				// To trigger feedback on popup chat
				if($(e.currentTarget).hasClass('respond-to')){
					PopupChat.feedbackTrigger = $(e.currentTarget).attr('data-id');
				}

				PopupChat.params.post = item_post;
				PopupChat.params.chat_type = $(e.currentTarget).closest('.item-post-panel-body').attr('data-chat-type');
				PopupChat.params.post_name = $(e.currentTarget).closest('.item-post-panel-body').find('.information .post_name').html();
				PopupChat.params.post_description = $(e.currentTarget).closest('.item-post-panel-body').find('.information .post_topic').html();
				ChatInbox.params.target_popup = $('.popup_chat_modal #popup-chat-'+PopupChat.params.post);
				PopupChat.initialize();
			}
		});
	},

	onClickMeet: function(){
		var btn = $('#list_post,#slider_list_post').find('.meet-trigger');

		btn.unbind();
		btn.on('click',function(e){
			var userId = $(this).attr('data-user-id');

			if(isGuest){
				if(isMobile){
					Login.RedirectLogin(window.location.href);
				}else{
					$('.modal').modal('hide');
					Login.modal_callback = Post;
					Login.initialize();
					return false
				}
			}

			/*Ajax.usermeet({user_id: userId }).then(function(res){
				$('.meet-'+userId).each(function() {
					$(this).addClass('hide');
				});

				if(!isMobile){
					ChatInbox.GetDataListChatPrivate();
				}
				window.ws.send("notify", {"sender": UserLogin, "receiver": userId, "room": -1, "message": ''});
			});*/
		});
	},

    OnClickBackdrop: function(){
        $('.modal-backdrop.in').unbind();
        $('.modal-backdrop.in').on('click',function(e) {
            $('#list_post').modal('hide');
        });
    },

    OnNetwrkLogo: function(){
        $('#list_post .title_page .title a').click(function(){
            $('#list_post').modal('hide');
        });
    },

    CustomScrollBar: function(){
		var parent = $("#list_post, #slider_list_post").find('.container_post');

		parent.mCustomScrollbar("scrollTo",$('#tab_post'));
		parent.css('height', $(window).height()-144);
		$('.post-feedback').find('.feedback-section').css('min-height', $(window).height()-144);
		parent.mCustomScrollbar({
            theme:"dark",
            callbacks:{
                onTotalScroll: function(){
                    if (Post.list[Post.params.filter].status_paging == 1 && Post.tab_current == 'post'){
                        Post.GetTabPost();
                    }else if(Post.tab_current == 'feed' && Post.feed.status_paging == 1){
                    	Post.feed.paging ++;
                        Post.LoadMoreFeed();
                    }
                }
            }
        });
    },

	OnClickSelectFilter: function(){
		var btn = $('#list_post .filter_sort .dropdown-toggle,.input-group-addon');

		btn.unbind();
		btn.on('click',function(){
			$('#list_post .dropdown.open .dropdown-toggle').dropdown('toggle');
			$('#list_post').find('[data-toggle="dropdown"]').parent().removeClass('open');
		});
	},

	OnClickSortBtn: function(){
		var btn_parent = $('#list_post').find('.sidebar .filter');

		btn_parent.unbind();
		btn_parent.on('click',function(){
			btn_parent.toggleClass('active');
			$('#list_post').find('.filter_sort').toggleClass('active');
			$('#list_post').find('.container_post').toggleClass('open');
		});
	},

	ShowMeetIcon: function(){
		var btn_parent = $('#list_post').find('#btn_meet');
		var btn_map = $('.map_content').find('#btn_meet');

		// btn_map.hide();
		set_position_btn($('#list_post'),btn_parent,130,100);
		set_position_btn_resize($('#list_post'),btn_parent,130,100);
		btn_parent.show();
		btn_parent.unbind();

		btn_parent.on('click',function(){
			$('#list_post').modal('hide');
			Meet.initialize();
		});

	},

	OnClickMeetIconMobile: function(){
		var btn = $('#btn_meet_mobile');

		btn.unbind();
		btn.on('click',function(){
			window.location.href = baseUrl + "/netwrk/meet";
		});
	},

	ShowSideBar: function(city,topic){
    	var sidebar = $('.map_content .sidebar');
        var city_name = "<span>"+ city +"</span> <i class='fa fa-angle-right'></i><span>"+ topic +"</span>";

        sidebar.find('.container').append(city_name);
        sidebar.show();
	},

	HideSideBar: function(){
		var sidebar = $('.map_content .sidebar');
		sidebar.hide();
		sidebar.find('.container').find('span,.fa').remove();
	},

	OnclickVote: function(){
		var parent = $('#list_post').find('.item-post-panel-body');
		var btn = parent.find('.icon_brillant');

		btn.unbind();
		btn.on('click',function(e){
			var post_id = $(e.currentTarget).attr('data-item');
			var target = $(e.currentTarget);
			Vote.target = e.currentTarget;
			Vote.post = post_id;
			Vote.initialize();
		});
	},

	ShowModalPost: function(){
		/*var parent = $('#list_post');
		set_heigth_modal($('#list_post'),0);
		parent.modal({
            backdrop: true,
            keyboard: false
        });*/

		if ($(Post.slider).css('left') != '0px') {
			$.when(Common.closeAllLeftSliders()).done(function () {
				$.when($(Post.slider).animate({
					"left": "0"
				}, 500)).done(function () {
					Post.GetDataOnTab();
					Post.getStreamData();
					Ajax.update_view_topic({topic: Post.params.topic});
					Post.OnclickBack();
					Post.OnNetwrkLogo();
				});
			});
		}
	},

	OnShowModalPost: function(){
        $('#list_post').on('shown.bs.modal',function(e) {
        	$(e.currentTarget).unbind();

            Post.GetDataOnTab();
        });
	},

	OnHideModal: function(){
        $('#list_post').on('hidden.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
        	// Post.HideSideBar()
            Post.ResetModal();
        });
	},

	ResetModal: function(){
		var name = $('#list_post, #slider_list_post').find('.header .title_page');
		var btn_map = $('.map_content').find('#btn_meet');

		name.find('span.title').remove();
		btn_map.show();
		var selecFilter = $('#list_post, #slider_list_post').find('.dropdown-menu li').first().text();
		$('#list_post, #slider_list_post').find('.tab').hide();
		$('#list_post, #slider_list_post').find('.dropdown-toggle').text(selecFilter);
		$('#list_post, #slider_list_post').find('.filter_sidebar td').removeClass('active');
		$('#list_post, #slider_list_post').find('.filter_sidebar .post').addClass('active');

		Post.ResetTabFeed();
	},

	getNameTopic: function(){
		var cityName = $('#list_post, #slider_list_post').find('.header .title_page');
		var name = $('#list_post, #slider_list_post').find('.sidebar .title_page');
		//var name = $('#list_post').find('.header .title_page');
		Ajax.get_topic(Post.params).then(function(data){
			var json = $.parseJSON(data);
			Post.getNameTemplate(name,json);
			Post.getCityNameTemplate(cityName,json);
		});
	},

    LazyLoading: function(){
        var self = this;
        var containt = $('.container_post');
        $(window).scroll(function() {
            if( $(window).scrollTop() + $(window).height() == $(document).height() && Post.list[Post.params.filter].status_paging == 1) {
                setTimeout(function(){
                	self.GetTabPost();
                },300);
            }else if(Post.feed.status_paging == 1 && $(window).scrollTop() + $(window).height() == $(document).height()){
                setTimeout(function(){
                    self.feed.paging ++;
                    self.LoadMoreFeed();
                },300);
            }
        });
    },

	GetDataOnTab: function(){
		switch(Post.tab_current) {
		    case 'feed':
		        Post.ShowFeedPage();
		        break;
		    case 'post':
		        Post.ShowPostPage();
		        break;
		}
	},

	ShowFeedPage: function(){

		var parent = $('#list_post');
		parent.find('#tab_feed').show();
		if(isMobile){
			parent.find('span.filter').addClass('visible');
			parent.find('span.filter').removeClass('active');
			parent.find('.filter_sort').removeClass('active');
			parent.find('.container_post').removeClass('open');
		}else{
			parent.find('.dropdown').addClass('visible');
		}
    	//disable btn create topic
        parent.find('.header .title_page').addClass('on-feed');
        parent.find('.header .create_post').addClass('on-feed');

		Post.LoadFeedModal();
	},

	LoadFeedModal: function(){
		var params = {'city': Post.params.city,'size': Post.params.size,'page':Post.feed.paging};
		var parent = $('#list_post').find('#tab_feed');
		Ajax.show_feed(params).then(function(res){
			Post.getTemplateFeed(parent,res);
			Post.getTemplateHistory(parent,res);
			Post.OnClickPostFeed();
	        Post.OnClickVoteFeed();
	        Post.OnClickTopicFeed();
	        Post.OnClickAvatarTopPostFeed();
            Post.OnClickAvatarTopFeed();
            Post.OnClickChatTopPostFeed();
		});
		if (isMobile) {
			LandingPage.FixWidthPostLanding();
		}
	},

	getTemplateHistory: function(parent,data){
		var json = $.parseJSON(data);
        var target = parent.find('.top-feed .top-feed-content');

        var list_template = _.template($( "#top_feed" ).html());
        var append_html = list_template({feed: json});

        target.append(append_html);
	},

    getTemplateFeed: function(parent,data){
        var json = $.parseJSON(data);
        parent.find('.no-data').hide();
        var list_template = _.template($( "#feed_list" ).html());
        var append_html = list_template({feed: json});
        parent.append(append_html);
    },

    LoadMoreFeed: function(){
    	var params = {'city': Post.params.city,'size': Post.params.size,'page':Post.feed.paging};
        var parent = $('#list_post').find('#tab_feed');
        Ajax.show_feed(params).then(function(data){
            Post.CheckPagingFeed(data);
            Post.getTemplateHistory(parent,data);
        });
    },

    CheckPagingFeed: function(data){
        var json = $.parseJSON(data);
        if(json.feed.length < Topic.data.size){
            Post.feed.status_paging = 0;
        }
    },

    OnClickAvatarTopPostFeed: function() {
        Topic.OnClickAvatarFeed($('.top-post').find('.top-post-content .post-row .avatar'));
    },

    OnClickAvatarTopFeed: function() {
        Topic.OnClickAvatarFeed($('.top-feed .top-feed-content').find('.feed-post .avatar-poster'));
    },

    OnClickAvatarFeed: function(target){
        var avatar = target;
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

    OnClickChatTopPostFeed: function(){
        var target = $(Post.modal).find('.top-post .action .chat');
        target.unbind();
        target.on('click',function(e){
                var post_id = $(e.currentTarget).parent().parent().attr('data-value'),
                    post_name = $(e.currentTarget).parent().parent().find('.post-title').text(),
                    post_content = $(e.currentTarget).parent().parent().find('.post-content').text();
            if(isMobile){
                sessionStorage.url = window.location.href;
                sessionStorage.feed_post = 1;
                PopupChat.RedirectChatPostPage(post_id, 1, 1);
            }else{
                PopupChat.params.post = post_id;
                PopupChat.params.chat_type = 1;
                PopupChat.params.post_name = post_name;
                PopupChat.params.post_description = post_content;
                PopupChat.initialize();
            }
        });
    },

    OnClickPostFeed: function() {
		var target = $(Post.modal).find('.top-post .post, .feed-row.feed-post .feed-content');
        target.unbind();
        target.on('click',function(e){
                var post_id = $(e.currentTarget).parent().attr('data-value'),
                    post_name = $(e.currentTarget).find('.post-title').text(),
                    post_content = $(e.currentTarget).find('.post-content').text();
            if(isMobile){
            	sessionStorage.url = window.location.href;
            	sessionStorage.feed_post = 1;
                PopupChat.RedirectChatPostPage(post_id, 1, 1);
            }else{
                PopupChat.params.post = post_id;
                PopupChat.params.chat_type = 1;
                PopupChat.params.post_name = post_name;
                PopupChat.params.post_description = post_content;
                PopupChat.initialize();
            }
        });
    },

    OnClickVoteFeed: function() {
        var target = $(Post.modal).find('.top-post .action .brilliant');
        target.unbind();
        target.on('click',function(e){
            var post_id = $(e.currentTarget).parent().parent().attr('data-value');
            Ajax.vote_post({post_id: post_id}).then(function(res){
                var json = $.parseJSON(res);
                $(e.currentTarget).text(json.data);
            });
        });
    },

    OnClickTopicFeed: function(){
        var target = $(Post.modal).find('.topic-row, .feed-row.feed-topic');

        target.unbind();
        target.on('click',function(e){
            var city_id = $(e.currentTarget).attr('data-city'),
                city_name = $(e.currentTarget).attr('data-city-name'),
                topic_id = $(e.currentTarget).attr('data-value'),
                topic_name = $(e.currentTarget).find('.topic-title').text();
            if(isMobile){
                Post.RedirectPostPage(topic_id);
            }else{
                $(Topic.modal).modal('hide');
                Post.params.topic = topic_id;
                Post.params.topic_name = topic_name;
                Post.params.city = city_id;
                Post.params.city_name = city_name;
                Post.tab_current = 'post';
                $(Post.modal).find('.filter_sidebar td').removeClass('active');
				$(Post.modal).find('.filter_sidebar .post').addClass('active');
                Post.initialize();
            }
        });

    },

	ShowPostPage: function(){
		var parent = $('#list_post')
				.add($('#slider_list_post'));
		if(isMobile){
			parent.find('span.filter').removeClass('visible');
		}else{
			parent.find('.dropdown').removeClass('visible');
		}
		$('.container_post .tab').hide();
		$('#tab_post').show();

    	//disable btn create post
        parent.find('.header .title_page').removeClass('on-feed');
        parent.find('.header .create_post').removeClass('on-feed');

		Post.ResetTabPost();
		Post.ResetTabFeed();
		Post.GetTabPost();
	},

	GetDefaultValue: function(){
		var parent = $('#list_post')
				.add($('#slider_list_post'));
		Post.params.topic = parent.data('topic');
		Post.params.city = parent.data('city');
	},

	OnclickBack: function(){
		var target = $('#list_post, #slider_list_post').find('.left-section')
				.add($('.box-navigation .btn_nav_map'));

		target.unbind();
		target.on('click',function () {
        	if(isMobile){
        		window.location.href = baseUrl + "/netwrk/topic/topic-page?city="+Post.params.city;
        	}else{
        		/*$('#list_post').modal('hide');
				Topic.initialize(Post.params.city);*/
				if ($(Post.slider).css('left') == '0px') {
					$.when($(Post.slider).animate({
						"left": "-400px"
					}, 500)).done(function(){
						Topic.initialize(Post.params.city);
					});
				}
        	}
        });
	},

	OnclickCreate: function(){
        var btn = $('#list_post').find('.create_post');
        btn.unbind();
        btn.on('click',function(){
	        if(isMobile){
                window.location.href = baseUrl + "/netwrk/post/create-post?city="+ Post.params.city +"&topic="+Post.params.topic;
	        }else{
	        	$('#list_post').modal('hide');
	        	Create_Post.initialize(Post.params.city,Post.params.topic,Post.params.city_name,Post.params.topic_name);
	        }
        });

	},

	FilterTabPost: function(body){
		var parent = $('#list_post #tab_post').find('#filter_'+Post.params.filter);
		parent.show();
		var selecFilter = $('#list_post').find('.dropdown-menu li');
		selecFilter.unbind('click');
		selecFilter.on('click',function(e){
			body.scrollTop(0);
			var name = $(e.currentTarget).text();
			$('#list_post').find('.dropdown-toggle').text(name);
			Post.params.filter = $(e.currentTarget).attr('data-value');
			Post.ShowPostPage();
		});
	},

	ResetTabPost: function(){
		var parent = $('#list_post #tab_post').find('#filter_'+Post.params.filter);
		$('#tab_post').find('.filter_page').hide();
		parent.find('.panel').remove();
		parent.find('.no-data').show();
		Post.params.page = 1;
		Post.list[Post.params.filter].status_paging = 1;
	},

	ResetTabFeed: function(){
        var parent = $('#list_post #tab_feed');
        parent.find('.top-post,.top-topic,.top-feed, .weather-feed-content, .job-feed-content').remove();
        parent.find('.no-data').show();
        Post.tab_current = 'post';
        Post.feed.paging = 1;
        Post.feed.status_paging = 1;
	},

	GetTabPost: function(){
		var parent = $('#tab_post').find('#filter_'+Post.params.filter);
		Ajax.get_post_by_topic(Post.params).then(function(data){
			var json = $.parseJSON(data);
			Post.checkStatus(json.data);
			if(json.status == 1 && json.data.length> 0){
				$('#tab_post').find('.filter_page').find('.panel').remove();
				parent.show();
				parent.find('.no-data').hide();
				Post.getTemplate(parent,json.data);
				Post.OnclickVote();
				Post.OnClickChat();
				Post.onClickMeet();
				// Display post filter popover
				Common.showHideInfoPopover('popover-post-filter', 'nw_popover_post_filter');
				// Display post feedback popover
				Common.showHideInfoPopover('popover-post-feedback', 'nw_popover_post_feedback');
				// Feedback related script calls
				Common.feedbackAllTriggers();
				if(isMobile){
					var infomation = $('.container_post').find('.item-post-panel-body .information');
					var wi_avatar = $($('.container_post').find('.item-post-panel-body')[0]).find('.users_avatar').width();
					fix_width_post(infomation,122);

					Topic.OnClickPostFeed();
				}
			}
		});
	},

	checkStatus: function(data){
		if(data.length == 0){
			Post.list[Post.params.filter].status_paging = 0;
		}else if(data.length < Post.params.size){
			Post.list[Post.params.filter].status_paging = 0;
		}else if(data.length == Post.params.size){
			Post.list[Post.params.filter].status_paging = 1;
			Post.params.page ++ ;
		}
	},

    OnChangeTab: function(){
        var target = $('#list_post').find('.filter_sidebar td');
        var self = this;
        target.on('click',function(e){
            var filter = $(e.currentTarget).attr('class');
            if(!$(e.currentTarget).hasClass('active')){
            	$('.tab').hide();
                $('#list_post').scrollTop(0);
                $('#list_post').find('#tab_'+filter).show();
                self.tab_current = filter;
                self.ChangeTabActive(target,$(e.currentTarget));
                self.GetDataOnTab();
                if(isMobile){
                	$(window).scrollTop(0);
		        }else{
		            $('#list_post').find('.container_post').mCustomScrollbar("scrollTo",0);
		        }
            }
        });
    },
    ChangeTabActive: function(target,parent){
        $.each(target,function(i,s){
            if($(s).hasClass('active')){
                $(s).removeClass('active');
                parent.addClass('active');
            }
        });
    },
    getTemplate: function(parent,data){
        var self = this;
        var list_template = _.template($("#post_list" ).html());
        var append_html = list_template({posts: data});

        parent.append(append_html);
        // Post.OnClickAvatarPostListDesktop();
    },

	getCityNameTemplate: function(parent,data){
		var self = this;
		var list_template = _.template($("#post_city_name" ).html());
		parent.html("");
		var append_html = list_template({name: data});

		parent.append(append_html);

		// Append city general post to header
		var json = data;
		var general_post = $('#list_post, #slider_list_post').find('.header').find('.right-section');
		var post_template = _.template($( "#post_general_post" ).html());
		var post_append_html = '';
		post_append_html = post_template({post_id: json.post_id, post_title: json.post_title, topic_title: json.topic_title});
		general_post.html(post_append_html);

		Topic.OnClickPostFeed();
	},

    getNameTemplate: function(parent,data){
        var self = this;
        var list_template = _.template($("#name_post_list" ).html());
		parent.html("");
        var append_html = list_template({name: data});

        parent.append(append_html);
    },

	RedirectPostPage: function(topic, isGroup){
		if (typeof isGroup == "undefined") isGroup = false;
		if (PopupChat.GetSearchParam(window.location.href)["previous-flag"]) {
			if (PopupChat.GetSearchParam(window.location.href)["previous-flag"] == 0) {
				window.location.href = baseUrl + "/netwrk/post?topic="+topic;
			} else {
				window.location.href = baseUrl+'/netwrk/chat-inbox/'+'?chat-type=1';
			}
		} else {
			window.location.href = document.referrer == baseUrl+"/netwrk/chat-inbox" ? document.referrer : baseUrl + "/netwrk/post?" + (isGroup ? "group" : "topic") + "="+topic;
		}
	},

	OnClickAvatarPostListDesktop: function(){
		var avatar = $(Post.modal, Post.slider).find('#tab_post .item-post-panel-body .users_avatar');
		avatar.unbind();
		avatar.on('click', function(e){
			var user_login = $(e.currentTarget).parent().attr('data-user'),
				user_view = $(e.currentTarget).attr('data-user-post');
			if(user_view != user_login){
				Meet.infoOf = user_view;
				//$('.modal').modal('hide');
				// $(Post.modal).modal('hide');
				//Meet.initialize();
				if ($(Post.slider).css('left') == '0px') {
					$.when($(Post.slider).animate({
						"left": "-400px"
					}, 500)).done(function(){
						Meet.initialize();
					});
				}
			}
		});
	},

	getBrilliantCode: function(brilliantCount) {
		var code = '';
		if (brilliantCount == undefined) {
			return ;
		}

		if (brilliantCount >= 200) {
			code = 'brilliant-yellow';
		} else if(brilliantCount >= 100 && brilliantCount < 200) {
			code = 'brilliant-yellow-green';
		} else if(brilliantCount >= 20 && brilliantCount < 100) {
			code = 'brilliant-green-blue';
		} else if(brilliantCount >= -20 && brilliantCount < 20) {
			code = 'brilliant-blue-violet';
		} else if(brilliantCount >= -100 && brilliantCount  < -20) {
			code = 'brilliant-blue-pink';
		} else if(brilliantCount < -100) {
			code = 'brilliant-pink';
		} else {
			code = 'disable';
		}
		return code;
	}
};
