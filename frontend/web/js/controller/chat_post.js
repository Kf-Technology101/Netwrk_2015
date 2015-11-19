var ChatPost = {
	modal:'#modal_chat_post',
	initialize: function(){
		if(isMobile){

		}else{
			ChatPost.OnShowModalChatPost();
			ChatPost.OnHideModalChatPost();
			ChatPost.ShowModalChatPost();
			ChatPost.CustomScrollBar();
			ChatPost.OnClickBackBtn($(ChatPost.modal));
			ChatPost.OnClickBackdrop();
		}
	},

	OnClickBackBtn: function(parent){
		var BackBtn = parent.find('.back_page');
		BackBtn.unbind();
		BackBtn.on('click',function(){
			if(isMobile){
				
			}else{
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

    CustomScrollBar: function(){
        $(ChatPost.modal).find('.modal-body').mCustomScrollbar({
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