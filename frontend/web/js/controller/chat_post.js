var ChatPost = {

	page:'#post_chat',
	modal:'#modal_chat_post',

	initialize: function(){
		if(isMobile){
			ChatPost.SetHeightContainerChat();
			ChatPost.CustomScrollBar($(ChatPost.page).find('.container_post_chat'));
			ChatPost.OnClickBackBtn($(ChatPost.page));
			fix_width_post($(ChatPost.page).find('.content_message'),$(ChatPost.page).find('.message')[0]);
		}else{
			ChatPost.OnShowModalChatPost();
			ChatPost.OnHideModalChatPost();
			ChatPost.ShowModalChatPost();
			ChatPost.CustomScrollBar($(ChatPost.modal).find('.modal-body'));
			ChatPost.OnClickBackBtn($(ChatPost.modal));
			ChatPost.OnClickBackdrop();
		}
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
				parent.modal('hide');
				Post.initialize();
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
        	console.log('Show Chat Post');
        });
	},
	OnHideModalChatPost: function(){
        $('#list_post').on('hidden.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
        	console.log('Hide Chat Post');
        });
	},
}