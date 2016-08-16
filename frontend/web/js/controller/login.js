var Login={
	params:{
		username:'',
		password:''
	},
	modal: '#login',
	page:'#page-login',
	parent: '',
	form_id:'#login-form',
	on_boarding_id: '#modalOnBoarding',
	modal_callback:'',
	data_login:'',
	profile_picture: true,
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
	},

	showOnBoardingLines: function () {
		Ajax.getOnBoardDetails().then(function(data){
			var jsonData = $.parseJSON(data),
					boardingModal = $(Login.on_boarding_id);

			Login.profile_picture = jsonData.profilePicture;

			if(jsonData.wsCount == 0 && jsonData.topPosts.length > 0){
				var parent = boardingModal.find('.modal-body').find('.lines-wrapper ul'),
						lines_template = _.template($( "#boarding_lines" ).html()),
						append_html = lines_template({top_post: jsonData.topPosts});

				parent.append(append_html);

				Common.eventOnBoardingLineClick();
				Common.eventOnBoardingSaveLines();
				Login.ShowModalOnBoarding();
			} else if(Login.profile_picture == false) {
				Login.showProfilePicture();
				Login.ShowModalOnBoarding();
			}
		});
	},
	showSocialSignupProfileInfoModal: function() {
		var signupProfileInfoModal = $('#social_signup_profile_info');

		signupProfileInfoModal.unbind();
		$(signupProfileInfoModal).modal({
			backdrop: true,
			keyboard: false
		});


		var params = {'user_id': UserLogin};
		Ajax.getUserById(params).then(function(data){
			var json = $.parseJSON(data),
					gender = json.data.gender,
					fname = json.data.first_name,
					lname = json.data.last_name,
					email = json.data.email,
					zipcode = json.data.zip_code,
					day = json.data.day,
					month = json.data.month,
					year = json.data.year;

			signupProfileInfoModal.find('#profile-zip_code').val(zipcode);
			signupProfileInfoModal.find('#profile-first_name').val(fname);
			signupProfileInfoModal.find('#profile-last_name').val(lname);
			signupProfileInfoModal.find('#user-email').val(email);
			signupProfileInfoModal.find('#profile-zip_code').val(zipcode);
			signupProfileInfoModal.find('#profile-day').val(day);
			signupProfileInfoModal.find('#profile-month').val(month);
			signupProfileInfoModal.find('#profile-year').val(year);
			signupProfileInfoModal.find('#profile-gender').val(gender.ucfirst());
		});

		Login.onHideSocialSignupProfileInfoModal();
	},

	onHideSocialSignupProfileInfoModal: function() {
		//todo check session var
		var signupProfileInfoModal = $('#social_signup_profile_info');
		signupProfileInfoModal.unbind();
		$(signupProfileInfoModal).on('hidden.bs.modal',function(e) {
			console.log('in hide');
			$(e.currentTarget).unbind();
			Login.showOnBoardingLines();
		});
	},
	showProfilePicture: function () {
		var boardingModal = $(Login.on_boarding_id);
		boardingModal.find('.select-lines').addClass('hidden');
		boardingModal.find('.select-picture').removeClass('hidden');
		Login.onBoardingProfileUpload();
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
    },

	ShowModalOnBoarding: function(){
		$(Login.on_boarding_id).modal({
			backdrop: true,
			keyboard: false
		});

		$(Login.on_boarding_id).find('.modal-body').mCustomScrollbar({
			theme:"dark"
		});

		$('.modal-backdrop.in').last().addClass('active');

		/*$('.modal-backdrop.in').click(function(e) {
			alert('Clicked on backdrop');
		});*/
	},

	onBoardingProfileUpload: function(){
		Login.onBrowse();
	},

	onBrowse: function(){
		var btn = $(Login.on_boarding_id).find('.browse');
		btn.unbind();
		btn.on('click',function(e){
			$('.preview_img').find('img').remove();
			$('.preview_img_ie').find('img').remove();
			$('.image-preview').find('p').show();
			$('#input_image')[0].click();

			$('#input_image').unbind();
			$('#input_image').change(function(e) {
				Login.handleFiles(this.files);
			});

		});
	},

	handleFiles: function(files){
		// var target = $('img.preview_image');
		var img = new Image(),
				parent_text = $('.image-preview').find('p'),
				btn_control_save = $('.profile-picture-btn-control').find('.save');

		if(files.length > 0){
			img.src = window.URL.createObjectURL(files[0]);

			img.onload = function() {
				window.URL.revokeObjectURL(this.src);
				Login.onEventSaveImage();
			};

			btn_control_save.removeClass('disable');
			parent_text.hide();

			if (isonIE()){
				$('.preview_img_ie').append(img);

			}else{
				$('.preview_img').addClass('active');
				$('.preview_img').append(img);
			}
			Login.showImageOnIE();
		}
	},

	onEventSaveImage:function(){
		var btn_save = $('.profile-picture-btn-control').find('.save');

		if (!btn_save.hasClass('disable')) {
			btn_save.on('click',function(){
				$('#upload_image').unbind();
				$('#upload_image').on('submit',function( event ) {
					event.preventDefault();
					var formData = new FormData(this);

					Ajax.uploadProfileImage(formData).then(function(data){
						var json = $.parseJSON(data);
						Login.profile_picture = true;
						$(Login.on_boarding_id).modal('hide');
					});

				});
				$('#upload_image').submit();
			});
		};
	},

	showImageOnIE: function(img){
		var target = $('.preview_img_ie').find('img'),
			w = $('.preview_img_ie').find('img').attr('width'),
			h = $('.preview_img_ie').find('img').attr('height');
	}
};