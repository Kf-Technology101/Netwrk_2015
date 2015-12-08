var Login={
	params:{
		username:'',
		password:''
	},
	modal: '#login',
	page:'#page-login',
	parent: '',
	form_id:'#login-form',
	data_login:'',
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
		Login.OnChangeBtnLogin();
		Login.OnClickBtnLogin();
	},

	OnClickBtnLogin: function(){
		var btn = $(Login.parent).find('.btn-control');
		btn.unbind('click');

		btn.on('click',function(e){
			if(!btn.hasClass('disable')){
				if(isMobile){
					$(Login.parent).find(Login.form_id).submit();
				}else{
					Login.OnUserLogin();
				}
			}
		});
	},

	OnUserLogin: function(){
		Ajax.user_login(Login.form_id).then(function(data){
			var json = $.parseJSON(data);
			Login.data_login = json;
			if(json.status == 1){
				$(Login.parent).modal('hide');
			}else{
				Login.OnShowLoginErrors();
			}
		});
	},

	OnShowLoginErrors: function(){
		console.log()
	},

	OnChangeBtnLogin: function(){
		var input = $(Login.parent).find('#loginform-username,#loginform-password'),
			username = $(Login.parent).find('#loginform-username'),
			password = $(Login.parent).find('#loginform-password'),
			btn = $(Login.parent).find('.btn-control');

		input.unbind('keyup');
		input.on('keyup',function(){
			if(username.val() === "" || password.val() === ""){
				btn.addClass('disable');
			}else{
				btn.removeClass('disable');
			}
		});
	},

	OnClickSignUp: function(){
		var btn = $(Login.parent).find('.sign-up b');
		btn.unbind();
		btn.on('click',function(){
			$(Login.parent).modal('hide');
			Signup.initialize();
		});
	},

	ShowModalLogin: function(){
		$(Login.modal).modal({
			backdrop: true,
			keyboard: false
		})
	},

	OnShowModalLogin: function(){
        $(Login.modal).on('shown.bs.modal',function(e) {
        	$('.modal-backdrop.in').addClass('active');
        });
	},

	OnHideModalLogin: function(){
        $(Login.modal).on('hidden.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
        	// $('.modal-backdrop.in').removeClass('active');
        });
	},

    OnClickBackdrop: function(){
        $('.modal-backdrop.in').unbind();
        $('.modal-backdrop.in').on('click',function(e) {
            $(Login.parent).modal('hide');
        });
    },
};