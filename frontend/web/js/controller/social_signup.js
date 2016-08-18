var SocialSignup ={
	modal: '#social_signup_profile_info',
	page:'#social-page-signup',
	parent: '',
	form_id:'#social-register-form',
	validate:true,
	state: 'Indiana',
	country: 'United States',
	zipcode: false,
	lat: 0,
	lng: 0,
	data_validate:'',
	initialize:function(){
		if(isMobile){
			Default.hideHeaderFooter();
			$('body').addClass('no-login');
			SocialSignup.parent = SocialSignup.page;
		}
		SocialSignup.parent = SocialSignup.modal;
		SocialSignup.OnShowModalSignUp();
		SocialSignup.OnHideModalSignUp();
		SocialSignup.ShowModal();
		SocialSignup.OnClickBackdrop();
		SocialSignup.OnClickSubmitForm();
		SocialSignup.validateZipcode();

	},

	OnClickSubmitForm: function(){
		var btn = $(SocialSignup.parent).find('.btn-control');

		btn.unbind();
		btn.on('click',function(){
			Ajax.update_profile_info($(SocialSignup.form_id)).then(function(data){
				console.log($.parseJSON(data));
				SocialSignup.data_validate = $.parseJSON(data);
				if(SocialSignup.data_validate.status == 0){
					SocialSignup.ShowErrorValidate();
				}else{
					if(isMobile) {
						window.location.href = baseUrl;
					} else {
						$(SocialSignup.modal).modal('hide');
						sessionStorage.on_boarding = 1;
						Login.showOnBoardingLines();
						$('.menu_top').removeClass('deactive');
						$('#btn_meet').removeClass('deactive');
					}
				}
			});
		});
	},

	ShowModal: function(){
		$(SocialSignup.parent).modal({
			backdrop: true,
			keyboard: false
		});

		/*$(SocialSignup.modal).find('.modal-body').mCustomScrollbar({
			theme:"dark"
		});*/
	},


	validateZipcode: function(){
		var target = $(SocialSignup.parent).find('#profile-zip_code');

		target.unbind('change');

		target.on('change',function(e){
			SocialSignup.CheckZipcode($(e.currentTarget).val()) ;
		})
	},

	CheckZipcode: function(zipcode){
		var val = parseInt(zipcode,10);
		var message ;

		if(val > 9999 && val < 99999){
			SocialSignup.apiZipcode(val);
		}else if(val ==""){
			message = "Zip Code is invalid";
			SocialSignup.OnShowZipcodeErrors(message);
			SocialSignup.zipcode = 0;
			return false;
		}
		else{
			message = "Zip Code is invalid";
			SocialSignup.OnShowZipcodeErrors(message);
			SocialSignup.zipcode = 0;
			return false;
		}
	},

    apiZipcode: function(zipcode){
    	var message;
        $.getJSON("http://maps.googleapis.com/maps/api/geocode/json?address="+zipcode ,function(data){
        	var address = data.results[0].address_components;
        	var geometry = data.results[0].geometry.location;
        	$.each(address,function(i,e){
				//allow zipcode from united states only
        		if(e.types[0] == 'country' && e.long_name == SocialSignup.country){
        			SocialSignup.zipcode = 1;
	            	SocialSignup.lat = geometry.lat;
	            	SocialSignup.lng = geometry.lng;

					console.log('zipcode lat / lng = '+ SocialSignup.lat+'/'+SocialSignup.lng);
					SocialSignup.OnShowZipcodeValid();
	            	return false;
        		}else{
        			message = "Zip Code is invalid";
	            	SocialSignup.zipcode = 0;
	            	SocialSignup.OnShowZipcodeErrors(message);
        		}
        	});
        });
    },

    OnShowZipcodeValid:function(){
    	var target = $(SocialSignup.parent).find('.zip .form-group');
    	target.removeClass('has-error').addClass('has-success');
    	target.find('.help-block').text('');
    	SocialSignup.AddLatLngUser();
    },

    AddLatLngUser: function(){
    	var latIn = $(SocialSignup.parent).find('#profile-lat'),
    		lngIn = $(SocialSignup.parent).find('#profile-lng');

    	latIn.val(SocialSignup.lat);
    	lngIn.val(SocialSignup.lng);
    },

	OnShowZipcodeErrors: function(message){
		var target = $(SocialSignup.parent).find('.zip .form-group');
		target.removeClass('has-success').addClass('has-error');
		target.find('.help-block').text(message);
	},

	OnShowModalSignUp: function(){
        $(SocialSignup.modal).on('shown.bs.modal',function(e) {
        	$(SocialSignup.parent).find('input')[1].focus();
        	$(e.currentTarget).unbind();
        	$('.modal-backdrop.in').addClass('active');
        	$('.menu_top').addClass('deactive');
        	$('#btn_meet').addClass('deactive');

			SocialSignup.updateForm();
        });
	},

	updateForm: function() {
		//get user info and fill it in profile details form
		signupProfileInfoModal = $(SocialSignup.modal);
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
			signupProfileInfoModal.find('#profile-gender').val(gender);
		});
	},

	OnHideModalSignUp: function(){
        $(SocialSignup.modal).on('hidden.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
        	$('.menu_top').removeClass('deactive');
        	$('#btn_meet').removeClass('deactive');
        	$(SocialSignup.modal).find(SocialSignup,form_id)[0].reset();
        });
	},

    OnClickBackdrop: function(){
        $('.modal-backdrop.in').unbind();
        $('.modal-backdrop.in').on('click',function(e) {
            $(SocialSignup.parent).modal('hide');
			$('.menu_top').removeClass('deactive');
			$('#btn_meet').removeClass('deactive');
        });
    }
};