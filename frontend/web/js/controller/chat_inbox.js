var ChatInbox = {
	parent: '',
	modal:'#chat_inbox',
	chat_inbox: '#chat_inbox',
	chat_inbox_mobile: '#chat_inbox_btn_mobile',
	private_chat: '#chat_private',
	discussion_chat: '#chat_discussion',
	params: {
		previous: ''
	},
	initialize: function(){
		if(isMobile){
			ChatInbox.getTemplateChatInboxMobile(ChatInbox.modal);
			ChatInbox.getTemplateChatPrivateMobile(ChatInbox.modal);
			ChatInbox.OnClickChatPostDetail();
			ChatInbox.OnClickMeetIconMobile();
			ChatInbox.HideMeetIconMobile();
			ChatInbox.OnClickChatInboxBtnMobile();
		} else {
			ChatInbox.OnShowListChatPost();
		}
		ChatInbox.OnClickHideCloseChatInboxBtn();
		ChatInbox.OnClickChatPrivateDetail();

	},
	OnShowListChatPost: function(){

		if ($(ChatInbox.chat_inbox).css('right') == '-400px') {
			ChatInbox.GetDataListChatPost();
			ChatInbox.GetDataListChatPrivate();
			$(ChatInbox.chat_inbox).animate({
				"right": "0"
			}, 500);
			ChatInbox.ActiveReponsiveChatInbox();
		} else {
			$(ChatInbox.chat_inbox).animate({
				"right": "-400px"
			}, 500);
			ChatInbox.DeactiveReponsiveChatInbox();
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

	getTemplateChatInbox: function(parent,data){
		var list_template = _.template($("#chat_inbox_list" ).html());
		var append_html = list_template({chat_inbox_list: data});
		// if ($(parent).find("div[id^='mSCB']").length == 1) {
		// 	parent.find("div[id^='mSCB']").html("");
		// 	parent.find("div[id^='mSCB']").append(append_html);
		// } else {
			parent.find('li').remove();
			parent.append(append_html);
		// }
		ChatInbox.CustomScrollBar();
		ChatInbox.OnClickChatPostDetail();
	},

	getTemplateChatPrivate: function(parent,data){
		var list_template = _.template($("#chat_inbox_list" ).html());
		var append_html = list_template({chat_inbox_list: data});
		parent.find('li').remove();
		parent.append(append_html);
		ChatInbox.CustomScrollBar();
		ChatInbox.OnClickChatPostDetail();
	},

	OnClickChatInbox: function() {
		var chat_inbox = $("#chat_inbox"),
			notify = $("#chat_inbox_btn").find('.notify');
		$("#chat_inbox_btn").on("click", function() {
			if(isGuest){
				Login.modal_callback = ChatInbox;
				Login.initialize();
				return false;
			}
			notify.html('0');
			notify.addClass('disable');
			ChatInbox.initialize();
		});

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
				ChatPost.RedirectChatPostPage(item_post, 1, 1);
			}else{
				ChatPost.params.post = item_post;
				if(ChatPost.temp_post != ChatPost.params.post){
					// $(Topic.modal).modal('hide');
					// $(ChatPost.modal).modal('hide');
					// $(Post.modal).modal('hide');
					// $(Topic.modal_create).modal('hide');
					// $(Post.modal_create).modal('hide');
					// $(Meet.modal).modal('hide');
					$('.modal').modal('hide');

					ChatPost.initialize();

					ChatPost.temp_post = ChatPost.params.post;
				}

			}
			});
	},

	OnClickChatPrivateDetail: function() {
		var btn = $(ChatInbox.modal).find('#chat_private li'),private_notify;
		btn.unbind();
		var userID = $(btn).find('.chat-post-id').attr('data-user');
		var postID=  $(btn).find('.chat-post-id').attr('data-post');
		btn.on("click", function(e) {
			var btn = $(this);
			if(isMobile){
				// Ajax.set_private_post(userID).then(function(data){
					// if (data) {
						ChatPrivate.RedirectChatPrivatePage(userID, 0, 1, postID);
					// };
				// });
			}else{
				ChatPrivate.params.private = userID;
				if(ChatPrivate.temp_private != ChatPrivate.params.private){
					$('.modal').modal('hide');
					ChatPrivate.initialize();
					ChatPrivate.temp_private = ChatPrivate.params.private;
				}
			}
			private_notify = $(e.currentTarget).find('.notify-chat-inbox');
			private_notify.html('0');
			private_notify.addClass('disable');
		});
	},

	ActiveReponsiveChatInbox: function() {
		var width = $( window ).width();
		if (width <= 1366) {
			$(".modal").addClass("responsive-chat-inbox");
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
		},

	DeactiveReponsiveChatInbox: function() {
		var width = $( window ).width();
		if (width <= 1366) {
			$(".modal").removeClass("responsive-chat-inbox");
		}

		//set zoom for re-init
		Map.zoom = Map.map.getZoom();
		Map.center = Map.map.getCenter();

		$('.map_content').animate({
			'width':'100%',
			'left': '', 'margin': 'auto'
		}, 500, 'swing', function(){ Map.initialize(); });

		$('#btn_meet').css({'left': '', 'right' : '15px'});
	},

	OnClickHideCloseChatInboxBtn: function() {
		var hide_chat_inbox_btn = "#hide_chat_inbox_btn";
		$(hide_chat_inbox_btn).unbind();
		if (isMobile) {
			$(hide_chat_inbox_btn).on("click", function() {
				Ajax.get_previous_page().then(function(data){
					window.location.href = data;
				});
			});
		} else {
			var chat_inbox = $("#chat_inbox");
			var parent = $(chat_inbox).find('#chat_discussion ul');
			$(hide_chat_inbox_btn).on("click", function() {
				chat_inbox.animate({
					"right": "-400px"
				}, 500);
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
				result = $.parseJSON(result);
				ChatInbox.getTemplateChatInbox(parent,result);
			}
		});
	},

	GetDataListChatPrivate: function() {
		var btn = $(ChatInbox.chat_inbox).find('.chat-private-btn');
		btn.unbind();
		btn.on('click', function() {
			var parent = $(ChatInbox.chat_inbox).find('#chat_private ul');
			$.ajax({
				url: baseUrl + "/netwrk/chat-private/get-chat-private-list",
				type: 'GET',
				data: {"user_id": UserLogin},
				processData: false,
				contentType: false,
				success: function(result) {
					console.log(result);
					result = $.parseJSON(result);
					ChatInbox.getTemplateChatPrivate(parent,result);
				}
			});
		});
	},

	OnClickChatInboxMobile: function() {
		if ( window.location.href == baseUrl + "/netwrk/chat-inbox") {
			Ajax.get_previous_page().then(function(data){
				window.location.href = data;
			});
		} else {
			window.location.href = baseUrl+ "/netwrk/chat-inbox";
		}
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
				ChatInbox.CustomScrollBarPrivate();
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

}