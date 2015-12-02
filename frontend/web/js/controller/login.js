var Login={
	modal: '#login',
	page:'#page-login',
	parent: '',
	initialize:function(){
		if(isMobile){
			Login.parent = Login.page;
			set_heigth_page_mobile($(Login.parent));
		}else{
			Login.parent = Login.modal;
			Login.OnShowModalLogin();
		}
	},

	OnShowModalLogin: function(){
        $('#login').on('shown.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
        });
	},

	OnHideModalLogin: function(){
        $('#list_post').on('hidden.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
        });
	},
};