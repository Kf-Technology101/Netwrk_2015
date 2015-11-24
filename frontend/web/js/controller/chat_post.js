var ChatPost = {
	params:{
		post:''
	},
	url:'',
	page:'#post_chat',
	modal:'#modal_chat_post',
	parent: '',
	container: '',
	initialize: function(){
		ChatPost.SetUrl();
		ChatPost.SetDataPostChat();
		ChatPost.OnClickBackBtn(ChatPost.parent);
		ChatPost.WsConnect(ChatPost.container);
		ChatPost.OnWsChatPost();

		if(isMobile){
			ChatPost.SetHeightContainerChat();
		}else{
			ChatPost.OnShowModalChatPost();
			ChatPost.ShowModalChatPost();
			ChatPost.OnHideModalChatPost();
			ChatPost.CustomScrollBar();
			ChatPost.OnClickBackdrop();
		}
	},

	SetUrl: function(){
		if(baseUrl === 'http://netwrk.rubyspace.net'){
			ChatPost.url = 'box.rubyspace.net';
		}else{
			ChatPost.url = "127.0.0.1";
		};

	},

	SetDataPostChat: function(){
		if(isMobile){
			ChatPost.params.post = $(ChatPost.page).attr('data-post');
			ChatPost.parent = ChatPost.page;
			ChatPost.container = '.container_post_chat';
		}else{
			ChatPost.parent = ChatPost.modal;
			ChatPost.container = '.container_post_chat';
		}
	},

	OnWsChatPost: function(){
		var btn = $(ChatPost.parent).find('.send');

		btn.unbind();
		btn.on("click", function(e){
			var parent = $(e.currentTarget).parent();
			var val	 = parent.find("textarea").val().replace(/\n\r?/g, '<br />');
			if(val != ""){
				ChatPost.ws.send("send", {"msg": val});
				parent.find("textarea").val('');
			}
		});
	},

	ScrollTopChat: function(){
		if(isMobile){
			$(ChatPost.parent).find(ChatPost.container).scrollTop($(ChatPost.parent).find(ChatPost.container)[0].scrollHeight);
		}else{
			$(ChatPost.parent).find('.modal-body').mCustomScrollbar("scrollTo","bottom");
		}

	},

	WsConnect: function(parent){
		ChatPost.ws = $.websocket("ws://"+ChatPost.url+":2311/?post="+ChatPost.params.post, {
			open: function() {
				console.log('open');
				// ChatPost.ws.send("fetch");
			},
			close: function() {
				console.log('close');
			},
			events: {
				fetch: function(e) {
					$.each(e.data, function(i, elem){
						ChatPost.getMessageTemplate(elem);
					});

					if(isMobile){
						fix_width_post($(ChatPost.parent).find('.content_message'),$($(ChatPost.parent).find('.message')[0]).find('.user_thumbnail').width() + 50);
					}
					ChatPost.ScrollTopChat();
				},
				onliners: function(e){
					$.each(e.data, function(i, elem){
						ChatPost.getMessageTemplate(elem);
					});
				},
				single: function(e){
					$.each(e.data, function(i, elem){
						ChatPost.getMessageTemplate(elem);
					});
					if(isMobile){
						fix_width_post($(ChatPost.parent).find('.content_message'),$($(ChatPost.parent).find('.message')[0]).find('.user_thumbnail').width() + 50);
					}
				}
			}
		});
	},

	getMessageTemplate:function(data){
        var template = _.template($( "#message_chat" ).html());
        var append_html = template({msg: data,baseurl: baseUrl});

        $(ChatPost.parent).find(ChatPost.container).append(append_html); 
        ChatPost.ScrollTopChat();
	},

	RedirectChatPostPage: function(postId){
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

	OnClickBackBtn: function(){

		var BackBtn = $(ChatPost.parent).find('.back_page');
		BackBtn.unbind();
		BackBtn.on('click',function(){
			if(isMobile){
				Post.RedirectPostPage($(ChatPost.parent).attr('data-topic'));
			}else{
				Post.initialize();
				$(ChatPost.parent).modal('hide');
			}
		});
	},

    OnClickBackdrop: function(){
        $('.modal-backdrop.in').unbind();
        $('.modal-backdrop.in').on('click',function(e) {
            $(ChatPost.modal).modal('hide');
        });
    },

    CustomScrollBar: function(){
    	var parent = $(ChatPost.modal).find('.modal-body');
        parent.mCustomScrollbar({
            theme:"dark"
        });
    },

	ShowModalChatPost: function(){
		var height_footer = $(ChatPost.modal).find('.modal-footer').height();
		set_container_chat_modal($(ChatPost.modal),height_footer);

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
        	ChatPost.ResetModalChatPost();
        });
	},

	ResetModalChatPost: function(){
		$(ChatPost.modal).find('.title_page .title').empty();
		$(ChatPost.modal).find(ChatPost.container).empty();
		ChatPost.ws.close();
		ChatPost.ws = null;
	},

	GetNameChatPost: function(){
		var parent = $(ChatPost.parent).find('.title_page .title');
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