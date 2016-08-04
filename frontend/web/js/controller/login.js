var Login={
	params:{
		username:'',
		password:''
	},
	modal: '#login',
	page:'#page-login',
	parent: '',
	form_id:'#login-form',
	modal_callback:'',
	data_login:'',
	initialize:function(){
		if(isMobile){
			$('body').addClass('no-login');
			Login.parent = Login.page;
		}else{
			Login.parent = Login.modal;
			Login.OnShowModalLogin();
			Login.OnHideModalLogin();
			Login.ShowModalLogin();
			Login.OnClickBackdrop();
			Login.OnClickSignUp();
			Login.OnForgotPassword();
		}
		// Login.OnChangeBtnLogin();
		Login.OnClickBtnLogin();
		Login.OnEventEnterForm();
	},

	OnEventEnterForm: function(){
		var btn = $(Login.parent).find('.btn-control');
		$(Login.parent).find(Login.form_id).keypress(function( event ) {
			if ( event.which == 13 ) {
				btn.trigger('click');
			}
		});
	},

	OnForgotPassword: function(){
		var btn = $(Login.parent).find('.forgot-password');
		btn.unbind();

		btn.on('click',function(){
			$(Login.parent).modal('hide');
			ForgotPass.initialize();

		})
		// $(Login.parent).modal('hide');
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
				isGuest = '';
				UserLogin = json.data;
				$(Login.parent).modal('hide');
				Login.OnCallBackAfterLogin();
			}else{
				Login.OnShowLoginErrors();
			}
		});
	},

	OnCallBackAfterLogin: function(){
		if(Login.modal_callback || isResetPassword){
			setTimeout(function(){
				if(Login.modal_callback) {
					Login.modal_callback.initialize();
				}
				Login.ShowNotificationOnChat();
				Default.SetAvatarUserDropdown();
				PopupChat.ShowChatBox(PopupChat.params.post);
			}, 500)
		} else {
			setTimeout(function(){
				Login.ShowNotificationOnChat();
				Default.SetAvatarUserDropdown();
				PopupChat.ShowChatBox(PopupChat.params.post);
			}, 500)
		}
		//reload the map, highlight users home location, favorite zipcode
		Map.main();
		Map.initialize();

		// If user not have any lines then display the modal
		setTimeout(function(){
			Ajax.getOnBoardDetails().then(function(data){
				var jsonData = $.parseJSON(data);
				console.log(jsonData);
				if(jsonData.wsCount == 0){
					if(jsonData.topPosts.length > 0){
						var boardingModal = $('#modalOnBoarding');
						var parent = boardingModal.find('.modal-body').find('.lines-wrapper ul');

						var lines_template = _.template($( "#boarding_lines" ).html());
						var append_html = lines_template({top_post: jsonData.topPosts});
						parent.append(append_html);

						Common.eventOnBoardingLineClick();
						Common.eventOnBoardingSaveLines();

						boardingModal.find('.modal-body').mCustomScrollbar({
							theme:"dark"
						});

						// parent.show();
						boardingModal.modal({
							backdrop: true,
							keyboard: false
						});
					}
				}
			});
		}, 600);
	},

	OnShowLoginErrors: function(){
		$.each(Login.data_login.data,function(i,e){
			var target = $(Login.parent).find('.' + i + ' .form-group');
			target.removeClass('has-success').addClass('has-error');
			target.find('.help-block').text(e[0]);
		});
	},

	OnChangeBtnLogin: function(){
		var input = $(Login.parent).find('#loginform-username,#loginform-password'),
			username = $(Login.parent).find('#loginform-username'),
			password = $(Login.parent).find('#loginform-password'),
			btn = $(Login.parent).find('.btn-control');
		input.unbind('keyup');
		input.on('change keyup',function(){
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
		});

		$(Login.modal).find('.modal-body').mCustomScrollbar({
			theme:"dark"
		});
	},

	OnShowModalLogin: function(){
        $(Login.modal).on('shown.bs.modal',function(e) {
        	$(Login.modal).find('input')[1].focus();
        	$('.modal-backdrop.in').addClass('active');
        	$('.menu_top').addClass('deactive');
        	$('#btn_meet').addClass('deactive');
			setTimeout(function() {
				$('#modal_landing_page').modal('hide');
			}, 800);
        });
	},

	OnHideModalLogin: function(){
        $(Login.modal).on('hidden.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
        	$('.menu_top').removeClass('deactive');
        	$('#btn_meet').removeClass('deactive');
        });
	},

    OnClickBackdrop: function(){
        $('.modal-backdrop.in').unbind();
        $('.modal-backdrop.in').on('click',function(e) {
            $(Login.parent).modal('hide');
        });
    },

    ShowNotificationOnChat: function(){
    	Ajax.count_unread_message().then(function(data){
    		var json = $.parseJSON(data),
    			notify = $("#chat_inbox_btn").find('.notify');
    		if (json > 0){
				notify.html(json);
				notify.removeClass('disable');
			}
    	});
    },
    RedirectLogin: function(url_callback){
    	window.location.href =  baseUrl+"/netwrk/user/login?url_callback="+url_callback;
    }
};