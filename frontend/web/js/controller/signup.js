var Signup={
	modal: '#signup',
	page:'#page-login',
	parent: '',
	initialize:function(){
		if(isMobile){
			Signup.parent = Signup.page;
			set_heigth_page_mobile($(Signup.parent));
		}else{
			Signup.parent = Signup.modal;
			Signup.OnShowModalLogin();
		}
	},

	OnShowModalLogin: function(){
        $(Signup.parent).on('shown.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
        });
	},

	OnHideModalLogin: function(){
        $(Signup.parent).on('hidden.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
        });
	},
};