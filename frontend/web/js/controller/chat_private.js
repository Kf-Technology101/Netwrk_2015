var ChatPrivate = {
	params:{
		private:'',
		chat_type: '0',
	},
	url:'',
	page:'#private_chat',
	modal:'#modal_chat_private',
	parent: '',
	container: '',
	status_emoji: 1,
	text_message:'',
	message_type:1,
	msg_lenght: 0,
	temp_private: 0,
	initialize: function(){
		ChatPrivate.SetUrl();
		ChatPrivate.SetDataPrivateChat();
		ChatPrivate.OnClickBackBtn(ChatPrivate.parent);
		// ChatPrivate.WsConnect(ChatPrivate.container);
		ChatPrivate.OnWsChatPrivate();
		ChatPrivate.OnWsFilePrivate();
		ChatPrivate.HandleWsFilePrivate();
		ChatPrivate.GetListEmoji();
		ChatPrivate.HandleEmoji();
		ChatPrivate.OnclickLogin();
		if(isMobile){
			ChatPrivate.SetHeightContainerChat();
			ChatPrivate.OnClickMeetMobile();
			ChatPrivate.OnClickChatInboxBtnMobile();
			ChatInbox.HideMeetIconMobile();
			Default.ShowNotificationOnChat();
		}else{
			ChatPrivate.ShowChatBox();
			ChatPrivate.OnShowModalChatPrivate();
			ChatPrivate.ShowModalChatPrivate();
			ChatPrivate.OnHideModalChatPrivate();
			ChatPrivate.CustomScrollBar();
			ChatPrivate.OnClickBackdrop();
		}
	},

	ShowChatBox: function(){
		if(isGuest){
			$(ChatPrivate.parent).find('.send_message.login').removeClass('active');
			$(ChatPrivate.parent).find('.send_message.no-login').addClass('active');
		}else{
			$(ChatPrivate.parent).find('.send_message.no-login').removeClass('active');
			$(ChatPrivate.parent).find('.send_message.login').addClass('active');
		}
	},

	OnclickLogin: function(){
		var btn = $(ChatPrivate.parent).find('.send_message.no-login .input-group-addon');

		btn.unbind();
		btn.on('click',function(){
			if(isMobile){
				window.location.href = baseUrl + "/netwrk/user/login?url_callback="+ $(ChatPrivate.parent).find('.send_message').attr('data-url');
			}else{
				$(ChatPrivate.parent).modal('hide');
                Login.modal_callback = ChatPrivate;
                Login.initialize();
                return false;
			}
		});
	},
	GetListEmoji: function(){
		var data = Emoji.GetEmoji();
		var parent = $(ChatPrivate.parent).find('.emoji .dropdown-menu');
		var template = _.template($( "#list_emoji" ).html());
		var append_html = template({emoji: data});

		if(ChatPrivate.status_emoji == 1){
			parent.append(append_html);
			parent.mCustomScrollbar({
				theme:"dark"
			});
			ChatPrivate.ConvertEmoji();
		}

	},

	ConvertEmoji: function(){
		var strs  = $(ChatPrivate.parent).find('.emoji').find('.dropdown-menu li');
		$.each(strs,function(i,e){
			Emoji.Convert($(e));
			ChatPrivate.status_emoji = 0;
		});
	},

	HandleEmoji: function(){
		var btn = $(ChatPrivate.parent).find('.emoji').find('.dropdown-menu li');
		btn.unbind();
		btn.on('click',function(e){
			ChatPrivate.text_message = $(ChatPrivate.parent).find('.send_message textarea').val();
			ChatPrivate.text_message += $(e.currentTarget).attr('data-value') + ' ';
			$(ChatPrivate.parent).find('textarea').val(ChatPrivate.text_message);
			$(ChatPrivate.parent).find('textarea').focus();
		});
	},

	FetchEmojiOne: function(data){
		var messages = $(ChatPrivate.parent).find(ChatPrivate.container).find('.message .content_message .content');
		if(data.type === "fetch"){
			$.each(messages,function(i,e){
				Emoji.Convert($(e));
			});
		}else{
			Emoji.Convert(messages.last());
		}
	},

	SetUrl: function(){
		ChatPrivate.url = MainWs.url;
	},

	SetDataPrivateChat: function(){
		if(isMobile){
			ChatPrivate.params.private = $(ChatPrivate.page).attr('data-private');
			ChatPrivate.parent = ChatPrivate.page;
			ChatPrivate.container = '.container_private_chat';
		}else{
			ChatPrivate.parent = ChatPrivate.modal;
			ChatPrivate.container = '.container_private_chat';
		}
	},

	OnWsChatPrivate: function(){
		var btn = $(ChatPrivate.parent).find('.send');
		var formWsChat = $(ChatPrivate.parent).find('#msgForm');

		formWsChat.on("keydown", function(e){
			if (event.keyCode == 13 && !event.shiftKey) {
				e.preventDefault();
				ChatPrivate.OnWsSendDataPrivate(e.currentTarget);
			}
		});
		btn.unbind();
		btn.on("click", function(e){
			ChatPrivate.OnWsSendDataPrivate(e.currentTarget);
		});
	},

	OnWsSendDataPrivate: function(e) {
		var parent = $(e).parent();
		var val	 = parent.find("textarea").val();
		if(val != ""){
			window.ws.send("send", {"type": 1, "msg": val,"room": ChatPrivate.params.private,"user_id": UserLogin});
			window.ws.send("notify", {"sender": UserLogin, "receiver": -1,"room": ChatPrivate.params.private, "message": val});
			parent.find("textarea").val("");
			parent.find("textarea").focus();
		}
	},

	ScrollTopChat: function(){
		if(isMobile){
			$(ChatPrivate.parent).find(ChatPrivate.container).scrollTop($(ChatPrivate.parent).find(ChatPrivate.container)[0].scrollHeight);
		}else{
			$(ChatPrivate.parent).find('.modal-body').mCustomScrollbar("scrollTo",$(ChatPrivate.parent).find(ChatPrivate.container)[0].scrollHeight);
		}
	},

	OnWsFilePrivate: function(){
		var btn = $(ChatPrivate.parent).find('#file_btn');
		btn.unbind();
		btn.on("click", function(){
			var btn_input = $(ChatPrivate.parent).find('#file_upload');
			btn_input.click();
		});
	},

	HandleWsFilePrivate: function(){
		var parentChat = $(ChatPrivate.parent);
		var input_change = $(ChatPrivate.parent).find('#file_upload');
		input_change.unbind('change');
		input_change.change(function(){
			if(typeof input_change[0].files[0] != "undefined"){
				var size_file = input_change[0].files[0].size;
				var type_file = input_change[0].files[0].type;
				var array_type_support = [
						"image/png",
						"image/jpeg",
						"image/pjpeg",
						"image/gif",
						"text/plain",
						"application/msword",
						"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
						"application/excel",
						"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
						"application/vnd.ms-excel" ,
						"application/x-excel" ,
						"application/x-msexcel",
						"application/mspowerpoint",
						"application/powerpoint",
						"application/vnd.ms-powerpoint",
						"application/x-mspowerpoint",
						"application/vnd.openxmlformats-officedocument.presentationml.presentation",
						"application/pdf",
						"audio/mpeg3",
						"video/mpeg",
						"video/avi",
						"application/x-shockwave-flash",
						"audio/wav, audio/x-wav",
						"application/xml",
						"image/x-icon"
					]
				file = input_change[0].files[0];

				fd = new FormData();
				fd.append('file', file);
				if ((size_file > 12582912) || ($.inArray(type_file, array_type_support) === -1)) {
					alert("Uploaded file is not supported or it exceeds the allowable limit of 12MB.");
					input_change.val('');
				} else {
					$.ajax({
						xhr: function() {
							var xhr = new window.XMLHttpRequest();
							xhr.upload.addEventListener("progress", function(evt) {
								if(evt.lengthComputable) {
									var percentComplete = evt.loaded / evt.total;
									percentComplete = parseInt(percentComplete * 100);
									parentChat.find(".loading_image").css('display', 'block');
								}
							}, false);
							return xhr;
						},
						url:  baseUrl + "/netwrk/chat/upload",
						type: "POST",
						data: fd,
						processData: false,
						contentType: false,
						success: function(result) {
							var fileForm = $(ChatPrivate.parent).find('#msgForm');
							val  = fileForm.find("textarea").val();
							if(result != "" && result !== false){
								var result = $.parseJSON(result);
								ChatPrivate.ws.send("send", {"type" : result.type, "msg" : val, "file_name" : result.file_name,"room": ChatPrivate.params.private,"user_id": UserLogin});
								parentChat.find(".loading_image").css('display', 'none');
								fileForm.find("textarea").val('');
							}
						}
					});
				}

			}
		});
	},

	getMessageTemplate:function(data){
		var template = _.template($( "#message_chat" ).html());
		var append_html = template({msg: data,baseurl: baseUrl});

		$(ChatPrivate.parent).find(ChatPrivate.container).append(append_html);
		ChatPrivate.OnClickReceiverAvatar();
	},

	RedirectChatPrivatePage: function(PrivateId, chat_type, previous_flag, post_id){
		if (chat_type == 0) {
			window.location.href = baseUrl + "/netwrk/chat-private/?privateId="+ PrivateId +"&chat_type="+chat_type+"&previous-flag=" + previous_flag+'&postID='+post_id;
		} else {
			window.location.href = baseUrl + "/netwrk/chat/chat-private?privateId="+ PrivateId +"&chat_type="+chat_type+"&previous-flag=" + previous_flag;
		}
	},

	SetHeightContainerChat: function(){
		var size = get_size_window();
		var h_navSearch = $('.navbar-mobile').height();
		var h_header = $(ChatPrivate.page).find('.header').height();
		var btn_meet = $('#btn_meet_mobile').height()-40;
		var nav_message = $(ChatPrivate.page).find('.nav_input_message').height();
		var wh = size[1] - h_navSearch -h_header - btn_meet - nav_message;


		$(ChatPrivate.page).find('.container_private_chat').css('height',wh);
	},

	OnClickBackBtn: function(){

		var BackBtn = $(ChatPrivate.parent).find('.back_page').add($('.box-navigation .btn_nav_map'));
		BackBtn.unbind();
		BackBtn.on('click',function(){
			if(isMobile){
				window.location.href = baseUrl+'/netwrk/chat-inbox/'+'?chat-type=0';
			}else{
				Private.initialize();
				$(ChatPrivate.parent).modal('hide');
			}
		});
	},

	GetSearchParam: function(url) {
		var query_string = {};
		var query = url.substring(1);
		var vars = query.split("&");
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

	OnClickBackdrop: function(){
		$('.modal-backdrop.in').unbind();
		$('.modal-backdrop.in').on('click',function(e) {
			$(ChatPrivate.modal).modal('hide');
		});
	},

	CustomScrollBar: function(){
		var parent = $(ChatPrivate.modal).find('.modal-body');
		parent.mCustomScrollbar({
			theme:"dark"
		});
	},

	ShowModalChatPrivate: function(){
		var height_footer = $(ChatPrivate.modal).find('.modal-footer').height();
		set_container_chat_modal($(ChatPrivate.modal),height_footer);

		$(ChatPrivate.modal).modal({
			backdrop: true,
			keyboard: false
		});
	},

	OnShowModalChatPrivate: function(){
		$(ChatPrivate.modal).on('shown.bs.modal',function(e) {
			$(e.currentTarget).unbind();
			ChatPrivate.GetNameChatPrivate();
		});
	},

	OnHideModalChatPrivate: function(){
		$(ChatPrivate.modal).on('hidden.bs.modal',function(e) {
			$(e.currentTarget).unbind();
			ChatPrivate.ResetModalChatPrivate();
		});
	},

	ResetModalChatPrivate: function(){
		$(ChatPrivate.modal).find('.title_page .title').empty();
		$(ChatPrivate.modal).find(ChatPrivate.container).empty();
		ChatPrivate.ws.close();
		ChatPrivate.ws = null;
		ChatPrivate.temp_Private = 0;
	},

	GetNameChatPrivate: function(){
		var parent = $(ChatPrivate.parent).find('.title_page .title');
		Ajax.chat_Private_name(ChatPrivate.params).then(function(data){
			var json = $.parseJSON(data);
			ChatPrivate.getNameTemplate(parent,json)
		})
	},

	getNameTemplate: function(parent,data){
		var self = this;
		var list_template = _.template($("#ChatPrivate_name" ).html());
		var append_html = list_template({name: data});

		parent.append(append_html);
	},

	OnClickMeetMobile: function(){
		var target = $('.navbar-mobile').find('.menu_bottom #btn_meet_mobile');
		target.on('click', function(){
			window.location.href = baseUrl + '/netwrk/meet';
		});
	},

	OnClickChatInboxBtnMobile: function() {
		var target = $('#chat_inbox_btn_mobile');
        target.unbind();
        target.on('click',function(){
        	sessionStorage.url = window.location.href;
            ChatInbox.OnClickChatInboxMobile();
        });
		// ChatInbox.OnClickChatInboxBtnMobile();
	},

	OnClickReceiverAvatar: function(){
		var avatar = $('#private_chat .container_private_chat .message_receiver .user_thumbnail'),
			post_id = $('#private_chat').attr('data-private');
		avatar.unbind();
		avatar.on('click', function(){
			window.location.href = baseUrl + "/netwrk/meet?post_id=" + post_id + "&from=private";
		});
	},
}