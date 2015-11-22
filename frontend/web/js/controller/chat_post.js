var ChatPost = {
	params:{
		post:''
	},
	page:'#post_chat',
	modal:'#modal_chat_post',

	initialize: function(){
		if(isMobile){
			ChatPost.SetDataPostChat();
			ChatPost.SetHeightContainerChat();
			ChatPost.OnClickBackBtn($(ChatPost.page));
			ChatPost.WsConnect($(ChatPost.page).find('.container_post_chat'));
			ChatPost.OnWsChatPost();
			fix_width_post($(ChatPost.page).find('.content_message'),$($(ChatPost.page).find('.message')[0]).find('.user_thumbnail').width() + 50);
		}else{
			ChatPost.OnShowModalChatPost();
			ChatPost.ShowModalChatPost();
			ChatPost.OnHideModalChatPost();
			ChatPost.CustomScrollBar($(ChatPost.modal).find('.modal-body'));
			ChatPost.OnClickBackBtn($(ChatPost.modal));
			ChatPost.OnClickBackdrop();
		}
	},

	SetDataPostChat: function(){
		ChatPost.params.post = $(ChatPost.page).attr('data-post');
	},

	OnWsChatPost: function(){
		$(ChatPost.page).find('.nav_input_message .send').on("click", function(e){
			var parent = $(e.currentTarget).parent();
			var val	 = parent.find("textarea").val();
			if(val != ""){
				ChatPost.ws.send("send", {"msg": val},ChatPost.params.post);
				parent.find("textarea").val('');
			}
		});
	},

	ScrollTopChat: function(){
		if(isMobile){
			var parent = $(ChatPost.page);
		}else{
			var parent = $(ChatPost.modal);
		}
		parent.find('.container_post_chat').animate({
			scrollTop : parent.find('.container_post_chat')[0].scrollHeight
		});
	},

	WsConnect: function(parent){
		console.log(ChatPost.params.post);
		ChatPost.ws = $.websocket("ws://127.0.0.1:8888/?post="+ChatPost.params.post, {
			open: function() {
				console.log('open');
				ChatPost.ws.send("fetch");
			},
			close: function() {
				console.log('close');
			},
			events: {
				fetch: function(e) {
					console.log(e.data);
					$.each(e.data, function(i, elem){
						console.log(elem);
						// var json = $.parseJSON(e.data);
						ChatPost.getMessageTemplate(parent,elem);
					});
					ChatPost.ScrollTopChat();
				},
				onliners: function(e){
					$.each(e.data, function(i, elem){
						ChatPost.getMessageTemplate(parent);
					});
					ChatPost.ScrollTopChat();
				},
				single: function(e){
					console.log('single');
					var elem = e.data;
					$(".container_post_chat").append("<p>hahaha</p>");
					ChatPost.ScrollTopChat();
				}
			}
		});
	},

	getMessageTemplate:function(parent,data){
        var template = _.template($( "#message_chat" ).html());
        var append_html = template({msg: data});

        parent.append(append_html); 
	},

	RedirectChatPostPage: function(postId){
		console.log(postId);
		window.location.href = baseUrl + "/netwrk/chat/chat-post?post="+ postId ;
	},

	SetHeightContainerChat: function(){
		var size = get_size_window();
		var h_navSearch = $('.navbar-mobile').height();
		var h_header = $(ChatPost.page).find('.header').height();
		var btn_meet = $('#btn_meet_mobile').height();
		var nav_message = $(ChatPost.page).find('.nav_input_message').height();
		var wh = size[1] - h_navSearch -h_header - btn_meet - nav_message;


		$(ChatPost.page).find('.container_post_chat').css('height',wh);
	},

	OnClickBackBtn: function(parent){
		var BackBtn = parent.find('.back_page');
		BackBtn.unbind();
		BackBtn.on('click',function(){
			if(isMobile){
				Post.RedirectPostPage(parent.attr('data-topic'));
			}else{
				Post.initialize();
				parent.modal('hide');
			}
		});
	},

    OnClickBackdrop: function(){
        $('.modal-backdrop.in').unbind();
        $('.modal-backdrop.in').on('click',function(e) {
            $(ChatPost.modal).modal('hide');
        });
    },

    CustomScrollBar: function(parent){
        parent.mCustomScrollbar({
            theme:"dark"
        });
    },

	ShowModalChatPost: function(){
		var height_footer = $(ChatPost.modal).find('.modal-footer').height();
		set_heigth_modal($(ChatPost.modal),height_footer);

		$(ChatPost.modal).modal({
            backdrop: true,
            keyboard: false
        });
	},

	OnShowModalChatPost: function(){
        $(ChatPost.modal).on('shown.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
        	ChatPost.GetNameChatPost();
        });
	},
	OnHideModalChatPost: function(){
        $(ChatPost.modal).on('hidden.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
        	console.log('aaaaaa');
        	ChatPost.ResetModalChatPost();
        });
	},

	ResetModalChatPost: function(){
		console.log('bbbbb');
		$(ChatPost.modal).find('.title_page .title').empty();
	},

	GetNameChatPost: function(){
		var parent = $('#modal_chat_post').find('.title_page .title');
		Ajax.chat_post_name(ChatPost.params).then(function(data){
			var json = $.parseJSON(data);
			ChatPost.getNameTemplate(parent,json)
		})
	},

    getNameTemplate: function(parent,data){
        var self = this;
        var list_template = _.template($("#chatpost_name" ).html());
        var append_html = list_template({name: data});

        parent.append(append_html);
    },

}