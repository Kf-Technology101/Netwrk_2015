var ChatInbox = {
	parent: '',
	modal:'#chat_inbox',
	initialize: function(){
		if(isMobile){
		} else {
			ChatInbox.OnClickChatInbox();
		}
	},

	CustomScrollBar: function(){
		var parent = $(ChatInbox.modal).find('#chat_dicussion ul');
		if ($(parent).find("#mCSB_1_container").length == 0) {
			$(parent).mCustomScrollbar({
				theme:"dark",
			});
		};
	},

	getTemplateChatInbox: function(parent,data){
		var list_template = _.template($("#chat_inbox_list" ).html());
		var append_html = list_template({chat_inbox_list: data});
		if ($(parent).find("#mCSB_1_container").length == 1) {
			parent.find("#mCSB_1_container").html("");
			parent.find("#mCSB_1_container").append(append_html);
		} else {
			parent.append(append_html);
		}
		ChatInbox.CustomScrollBar();
		ChatInbox.OnClickChatPostDetail();
	},

	OnClickChatInbox: function() {
		var chat_inbox = $("#chat_inbox");
		var parent = $(chat_inbox).find('#chat_dicussion ul');
		$("#chat_inbox_btn").on("click", function() {
			if (chat_inbox.css('right') == '-400px') {
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
				chat_inbox.animate({
					"right": "0"
				}, 500);

				ChatInbox.ActiveReponsiveChatInbox();
			} else {
				chat_inbox.animate({
					"right": "-400px"
				}, 500);
				ChatInbox.DeactiveReponsiveChatInbox();
			}

		});

	},

	OnClickChatPostDetail: function() {
		var btn = $(ChatInbox.modal).find('#chat_dicussion li');
		btn.unbind();
		btn.on('click',function(e){
			var btn = $(this);
			Post.params.topic = btn.find("input[name='topic_id']").val();
			Post.params.topic_name = btn.find("input[name='topic_name']").val();
			Post.params.city = btn.find("input[name='city_id']").val();
			Post.params.city_name = btn.find("input[name='city_name']").val();
			var item_post = $(e.currentTarget).find('.chat-post-id').attr('data-post');
			if(isMobile){
				ChatPost.RedirectChatPostPage(item_post);
			}else{
				ChatPost.params.post = item_post;
				if(ChatPost.temp_post != ChatPost.params.post){
					$(ChatPost.modal).modal('hide');
					ChatPost.initialize();
					ChatPost.temp_post = ChatPost.params.post;
				}

			}
		});
	},

	ActiveReponsiveChatInbox: function() {
		var width = $( window ).width();
		if (width <= 1366) {
			$("#modal_topic,  #list_post, #modal_chat_post, #create_topic, #create_post").addClass("responsive-chat-inbox");
		}
	},

	DeactiveReponsiveChatInbox: function() {
		var width = $( window ).width();
		if (width <= 1366) {
			$("#modal_topic,  #list_post, #modal_chat_post, #create_topic, #create_post").removeClass("responsive-chat-inbox");
		}
	}
}