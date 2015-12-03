var Login={
	modal: '#login',
	page:'#page-login',
	parent: '',
	initialize:function(){
		if(isMobile){
			$('body').css('background','#fff');
			Login.parent = Login.page;
		}else{
			Login.parent = Login.modal;
			Login.OnShowModalLogin();
			Login.ShowModalLogin();
			Login.OnClickBackdrop();
			Login.OnClickSignUp();
		}
	},

	ShowModalLogin: function(){
		$(Login.parent).modal({
			backdrop: true,
			keyboard: false
		})
	},

	OnShowModalLogin: function(){
        $('#login').on('shown.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
        	$('.modal-backdrop.in').addClass('active');
        });
	},

	OnHideModalLogin: function(){
        $('#list_post').on('hidden.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
        	// $('.modal-backdrop.in').removeClass('active');
        });
	},

    OnClickBackdrop: function(){
        $('.modal-backdrop.in').unbind();
        $('.modal-backdrop.in').on('click',function(e) {
        	console.log('aaaa');
            $(Login.parent).modal('hide');
        });
    },
};