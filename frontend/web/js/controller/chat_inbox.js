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
		parent.mCustomScrollbar({
			theme:"dark"
		});
	},

	getTemplateChatInbox: function(parent,data){
        var self = this;
        var list_template = _.template($("#chat_inbox_list" ).html());
        var append_html = list_template({chat_inbox_list: data});
        parent.html("");
        parent.append(append_html);
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
                }, 1000);
            } else {
                chat_inbox.animate({
                    "right": "-400px"
                }, 1000);
            }

        });

    },

    OnClickChatPostDetail: function() {
    	var btn = $(ChatInbox.modal).find('#chat_dicussion li');
		btn.unbind();
		btn.on('click',function(e){

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
    }
}