var ChatInbox = {
	parent: '',
	modal:'#chat_inbox',
	chat_inbox: '#chat_inbox',
	chat_inbox_mobile: '#chat_inbox_btn_mobile',
	private_chat: '#chat_private',
	discussion_chat: '#chat_discussion',
	list_chat_post_right_hidden: "-400px",
	params: {
		previous: '',
		target_popup: '',
		IsOpenChatBox: false,
	},
	onClickChat: 0,
	initialize: function(){
		if(isMobile){
			ChatInbox.getTemplateChatInboxMobile(ChatInbox.modal);
			ChatInbox.getTemplateChatPrivateMobile(ChatInbox.modal);
			ChatInbox.OnClickChatPostDetail();
			ChatInbox.OnClickMeetIconMobile();
			ChatInbox.HideMeetIconMobile();
			ChatInbox.OnClickChatInboxBtnMobile();
			ChatInbox.CheckBackFromChat();
			Default.SetAvatarUserDropdown();
		} else {
			// if(ChatInbox.onClickChat == 1){
			// 	Ajax.change_chat_show_message().then(function(data){});
			// }
			ChatInbox.OnShowListChatPost();
		}
		ChatInbox.OnClickHideCloseChatInboxBtn();
		ChatInbox.OnClickChatPrivateDetail();
		Default.ShowNotificationOnChat();
	},

	OnShowListChatPost: function(){
		if ($(ChatInbox.chat_inbox).css('right') == ChatInbox.list_chat_post_right_hidden) {
			ChatInbox.GetDataListChatPost();
			ChatInbox.GetDataListChatPrivate();
			$(ChatInbox.chat_inbox).animate({
				"right": "0"
			}, 500);

            $('.popup-box').each(function(index){
                var right_now = parseInt($(this).css('right'),10) + 315;
                $(this).animate({
                    'right': right_now
                }, 500);
            });

			ChatInbox.ActiveReponsiveChatInbox();
			ChatInbox.onClickChat = 1;
		} else {
			$(ChatInbox.chat_inbox).animate({
				"right": ChatInbox.list_chat_post_right_hidden
			}, 500);

            $('.popup-box').each(function(index){
                var right_now = parseInt($(this).css('right'),10) - 315;
                $(this).animate({
                    'right': right_now
                }, 500);
            });

			ChatInbox.DeactiveReponsiveChatInbox();
			ChatInbox.onClickChat = 0;
		}
	},

	CustomScrollBar: function(){
		var parent = $(ChatInbox.modal).find('#chat_discussion #container_ul_chat_list');
		parent.css('height', $(window).height()-100);
		if ($(parent).find("div[id^='mSCB']").length == 0) {
			$(parent).mCustomScrollbar({
				theme:"dark",
			});
		};
	},

	CustomScrollBarPrivate: function(){
		var parent = $(ChatInbox.modal).find('#chat_private #container_ul_chat_list');
		parent.css('height', $(window).height()-100);
		if ($(parent).find("div[id^='mSCB']").length == 0) {
			$(parent).mCustomScrollbar({
				theme:"dark",
			});
		};
	},

	getTemplateChatInbox: function(parent,data, user_id){
		if (user_id == UserLogin) {
			var list_template = _.template($("#chat_inbox_list" ).html());
			var append_html = list_template({chat_inbox_list: data});
			parent.find('li').remove();
			parent.append(append_html);
			ChatInbox.CustomScrollBar();
			ChatInbox.OnClickChatPostDetail();
		}
	},

	getTemplateChatPrivate: function(parent,data, user_id){
		if (user_id == UserLogin) {
			if ($("#chat_private_list" ).length > 0) {
				var list_template = _.template($("#chat_private_list" ).html());
				var append_html = list_template({chat_private_list: data});
				parent.find('li').remove();
				parent.append(append_html);
				for (var i =0;i< data.length; i++) {
					if(data[i].class_first_met==0) {
						parent.find('li .chat-post-id[data-post='+data[i].post_id+'] .title-description-user .description-chat-inbox').addClass('match-description');
					}
				};
				ChatInbox.CustomScrollBarPrivate();
				ChatInbox.OnClickChatPrivateDetail();
				ChatInbox.CountMessageUnread();
			}

		}
	},

	OnClickChatInbox: function() {
		var chat_inbox = $("#chat_inbox"),
		notify = $("#chat_inbox_btn").find('.notify');
		$("#chat_inbox_btn").on("click", function() {
			if(isGuest){
				$('.modal').modal('hide');
				Login.modal_callback = ChatInbox;
				Login.initialize();
				return false;
			}
			// notify.html('0');
			// notify.addClass('disable');
			ChatInbox.initialize();
		});
		console.log(ChatInbox.onClickChat);
	},

	OnClickChatPostDetail: function() {
		var btn = $(ChatInbox.modal).find('#chat_discussion li');
		btn.unbind();
		btn.on('click',function(e){
			var btn = $(this);
			Post.params.topic = btn.find("input[name='topic_id']").val();
			Post.params.topic_name = btn.find("input[name='topic_name']").val();
			Post.params.city = btn.find("input[name='city_id']").val();
			Post.params.city_name = btn.find("input[name='city_name']").val();
			Topic.data.city = btn.find("input[name='city_id']").val();
			Topic.data.city_name = btn.find("input[name='city_name']").val();
			Topic.params.city = btn.find("input[name='city_id']").val();
			var item_post = $(e.currentTarget).find('.chat-post-id').attr('data-post');
			if(isMobile){
				PopupChat.RedirectChatPostPage(item_post, 1, 1);
			}else{
				PopupChat.params.post = item_post;
				PopupChat.params.chat_type = $(e.currentTarget).find('.chat-post-id').attr('data-chat-type');
				PopupChat.params.post_name = $(e.currentTarget).find('.chat-post-id .title-chat-inbox').html();
				PopupChat.params.post_description = $(e.currentTarget).find('.chat-post-id .description-chat-inbox').html();

				ChatInbox.params.target_popup = $('.popup_chat_modal #popup-chat-'+PopupChat.params.post);
				PopupChat.initialize();
				Default.displayPopupOnTop();
				PopupChat.MoveMeetButton();
			}
		});
	},

	OnClickChatPrivateDetail: function() {
		var btn = $(ChatInbox.modal).find('#chat_private li'),private_notify;
		btn.unbind();
		btn.on("click", function(e) {
			var btn = $(this);
			var userID = $(btn).find('.chat-post-id').attr('data-user');
			var postID=  $(btn).find('.chat-post-id').attr('data-post');
			ChatInbox.ChangeStatusUnreadMsg(userID);
			Default.ShowNotificationOnChat();
			if(isMobile){
				PopupChat.RedirectChatPostPage(postID, 0, 0);
			}else{
                PopupChat.params.post = postID;
                PopupChat.params.chat_type = $(e.currentTarget).find('.chat-post-id').attr('data-chat-type');
                PopupChat.params.post_name = $(e.currentTarget).find('.chat-post-id .title-chat-inbox').html();
				PopupChat.params.post_avatar = $(e.currentTarget).find('.img_avatar').attr('src');
				ChatInbox.params.target_popup = $('.popup_chat_modal #popup-chat-'+PopupChat.params.post);
				PopupChat.initialize();
			}
			private_notify = $(e.currentTarget).find('.notify-chat-inbox');
			private_notify.html('0');
			private_notify.addClass('disable');
			Default.displayPopupOnTop();
			PopupChat.MoveMeetButton();
		});
	},

	ActiveReponsiveChatInbox: function() {
		var width = $( window ).width();
		$(".modal").addClass("responsive-chat-inbox");
		if (width <= 1250) {
			$('#btn_meet').css('z-index', '1050');
		}

		//set zoom for re-init
		Map.zoom = Map.map.getZoom();
		Map.center = Map.map.getCenter();
		var width_map = width -320;
		$('.map_content').animate({
			'width':width_map+'px',
			'left': 0, 'margin': 0
		}, 500, 'swing', function(){ Map.initialize(); });
		$('#btn_meet').css({'left': '', 'right' : '15px'});
		ChatInbox.params.IsOpenChatBox = true;
		ChatInbox.WindowResize(width_map);
	},

	DeactiveReponsiveChatInbox: function() {
		var width = $( window ).width();
		// if (width <= 1366) {
			$(".modal").removeClass("responsive-chat-inbox");
		// }
		if (width <= 1250) {
			$('#btn_meet').css('z-index', '10000');
		}

		//set zoom for re-init
		Map.zoom = Map.map.getZoom();
		Map.center = Map.map.getCenter();

		$('.map_content').animate({
			'width':'100%',
			'left': '', 'margin': 'auto'
		}, 500, 'swing', function(){ Map.initialize(); });
		ChatInbox.params.IsOpenChatBox = false;
		$('#btn_meet').css({'left': '', 'right' : '15px'});
	},

	OnClickHideCloseChatInboxBtn: function() {
		var hide_chat_inbox_btn = "#hide_chat_inbox_btn";
		$(hide_chat_inbox_btn).unbind();
		if (isMobile) {
			$(hide_chat_inbox_btn).on("click", function() {
				var previous_link = sessionStorage.url != '' ? sessionStorage.url : baseUrl ;
				sessionStorage.url = baseUrl;
				sessionStorage.show_landing = 1;
				window.location.href = previous_link;
			});
		} else {
			var chat_inbox = $("#chat_inbox");
			var parent = $(chat_inbox).find('#chat_discussion ul');
			$(hide_chat_inbox_btn).on("click", function() {
				chat_inbox.animate({
					"right": ChatInbox.list_chat_post_right_hidden
				}, 500);

                $('.popup-box').each(function(index){
                    var right_now = parseInt($(this).css('right'),10) - 315;
                    $(this).animate({
                        'right': right_now
                    }, 500);
                });

				ChatInbox.DeactiveReponsiveChatInbox();
			});
		}
	},

	GetDataListChatPost: function() {
		var parent = $(ChatInbox.chat_inbox).find('#chat_discussion ul');
		$.ajax({
			url: baseUrl + "/netwrk/post/get-chat-inbox",
			type: 'POST',
			data: null,
			processData: false,
			contentType: false,
			success: function(result) {
				if (result) {
					result = $.parseJSON(result);
					ChatInbox.getTemplateChatInbox(parent,result, UserLogin);
				}
			}
		});
	},

	GetDataListChatPrivate: function() {
		var parent = $(ChatInbox.chat_inbox).find('#chat_private ul');
		$.ajax({
			url: baseUrl + "/netwrk/chat-private/get-chat-private-list",
			type: 'GET',
			data: {"user_id": UserLogin},
			processData: false,
			contentType: false,
			success: function(result) {
				if(result){
					result = $.parseJSON(result);
					ChatInbox.getTemplateChatPrivate(parent,result, UserLogin);
				}
			}
		});
	},

	getTemplateChatInboxMobile: function(parent) {
		if (isMobile) {
			Ajax.list_chat_post().then(function(data){
				if (data) {
					data = $.parseJSON(data);
				}

				var list_template = _.template($("#chat_inbox_list" ).html());
				var append_html = list_template({chat_inbox_list: data});
				parent = $(parent).find('#chat_discussion ul');
				parent.find('li').remove();
				parent.append(append_html);
				ChatInbox.CustomScrollBar();
			});
		};
	},

	getTemplateChatPrivateMobile: function(parent) {
		if (isMobile) {
			Ajax.get_chat_private_list().then(function(data){
				if (data) {
					data = $.parseJSON(data);
				}
				var list_template = _.template($("#chat_private_list" ).html());
				var append_html = list_template({chat_private_list: data});
				parent = $(parent).find('#chat_private ul');
				parent.find('li').remove();
				parent.append(append_html);
				for(i=0; i < data.length; i++) {
					if(data[i].class_first_met == 0) {
						parent.find('li .chat-post-id[data-post='+data[i].post_id+'] .title-description-user .description-chat-inbox').addClass('match-description');
					}
				}
				ChatInbox.CustomScrollBarPrivate();
				ChatInbox.CountMessageUnread();
			});
		};
	},

	OnClickMeetIconMobile: function() {
		var btn = $('#btn_meet_mobile');
		btn.unbind();
		btn.on('click',function(){
			window.location.href = baseUrl + "/netwrk/meet";
			});
	},

	OnClickChatInboxMobile: function() {
		if ( window.location.href == baseUrl + "/netwrk/chat-inbox") {
			window.location.href = sessionStorage.url;
		} else {
			window.location.href = baseUrl+ "/netwrk/chat-inbox";
		}
	},

	OnClickChatInboxBtnMobile: function(previous_link) {
		var target = $('#chat_inbox_btn_mobile');
		target.unbind();
		target.on('click',function(){
			ChatInbox.OnClickChatInboxMobile();
		});
	},

	HideMeetIconMobile: function() {
		$('#btn_meet_mobile').hide();
	},

	GetSearchParam: function(url) {
		var query_string = {};
		var query = url.substring(1);
		var vars = query.split("?");
		for (var i=0;i<vars.length;i++) {
			var pair = vars[i].split("=");
			        // If first entry with this name
			        if (typeof query_string[pair[0]] === "undefined") {
			        	query_string[pair[0]] = decodeURIComponent(pair[1]);
			        // If second entry with this name
			    } else if (typeof query_string[pair[0]] === "string") {
			    	var arr = [ query_string[pair[0]],decodeURIComponent(pair[1]) ];
			    	query_string[pair[0]] = arr;
			        // If third or later entry with this name
			    } else {
			    	query_string[pair[0]].push(decodeURIComponent(pair[1]));
			    }
			}
		return query_string;
	},

	CheckBackFromChat: function() {
		var referrer = ChatInbox.GetSearchParam(window.location.href)["chat-type"];
		if(referrer) {
			if (referrer == 0) {
				$(ChatInbox.modal).find('.chat-dicussions-btn').removeClass('active');
				$(ChatInbox.modal).find(ChatInbox.discussion_chat).removeClass('active');

				$(ChatInbox.modal).find('.chat-private-btn').addClass('active');
				$(ChatInbox.modal).find(ChatInbox.private_chat).addClass('active');
			} else {
				$(ChatInbox.modal).find('.chat-dicussions-btn').addClass('active');
				$(ChatInbox.modal).find(ChatInbox.discussion_chat).addClass('active');

				$(ChatInbox.modal).find('.chat-private-btn').removeClass('active');
				$(ChatInbox.modal).find(ChatInbox.private_chat).removeClass('active');
			}
		}
	},

	CountMessageUnread: function(){
		$(ChatInbox.modal).find('#chat_private li .chat-post-id').each(function(){
			var sender = $(this).attr('data-user');
			Ajax.count_unread_msg_from_user(sender).then(function(data){
				var json = $.parseJSON(data),
					row = $(ChatInbox.modal).find('#chat_private li .chat-post-id[data-user=' + sender + '] .notify-chat-inbox');
				if (json > 0){
					row.html(json);
					row.removeClass('disable');
				} else {
					row.html(0);
					row.addClass('disable');
				}
			});
		});
	},

	ChangeStatusUnreadMsg: function(sender){
		Ajax.update_notification_status(sender).then(function(data){
			var notify = $(ChatInbox.modal).find('#chat_private li .chat-post-id[data-user='+ sender +'] .notify-chat-inbox');
			notify.html(0);
			notify.addClass('disable');
		});
	},

	// Change width height map, position meet button when user open chat on post
	WindowResize: function(width_map) {
		$(window).resize(function(){
			ChatInbox.SetSizeMap(ChatInbox.GetSizeWindow()[0], ChatInbox.GetSizeWindow()[1]);
			PopupChat.MoveMeetButton();
		});
	},

	GetSizeWindow: function (){
		return [$(window).width(),$(window).height()];
	},

	SetSizeMap: function (w,h){
		var menu_h = $('.menu_top').height();
		$('#googleMap').css({'height': h - menu_h,'min-height': h -menu_h});
		if (ChatInbox.params.IsOpenChatBox == true) {
			w = w - 320;
		}
		$('.map_content').css({'height': h - menu_h,'width': w});
	},
}