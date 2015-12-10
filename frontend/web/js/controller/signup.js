var Signup={
	modal: '#signup',
	page:'#page-signup',
	parent: '',
	form_id:'#register-form',
	validate:true,
	state: 'Indiana',
	zipcode: false,
	initialize:function(){
		console.log('signup');
		if(isMobile){
			$('body').css('background','#fff');
			Signup.parent = Signup.page;
		}else{
			Signup.parent = Signup.modal;
			Signup.OnShowModalSignUp();
			Signup.OnHideModalSignUp();
			Signup.ShowModal();
			Signup.OnClickBackdrop();
		}
		Signup.OnShowDatePicker();
		Signup.OnChangeGender();
		Signup.validateZipcode();
		Signup.OnClickSubmitSignUp();
	},

	OnClickSubmitSignUp: function(){
		var btn = $(Signup.parent).find('.btn-control.sign-up');
		btn.unbind();
		$(Signup.parent).on('afterValidate',Signup.form_id,function(e,data,error){
			console.log(data);
			if(!Signup.zipcode){
				var zip = $(Signup.parent).find('#profile-zip_code').val();
				Signup.CheckZipcode(zip);
			}
		});

	},

	ShowModal: function(){
		$(Signup.parent).modal({
			backdrop: true,
			keyboard: false
		})
	},

	OnChangeGender: function(){
		var gender = $(Signup.parent).find('.sex .dropdown-menu li');
		var input_gender = $(Signup.parent).find('#profile-gender');

		gender.unbind();
		gender.on('click',function(e){
			var text = $(e.currentTarget).text();
			input_gender.val(text);
		});
	},

	OnShowDatePicker: function(){
		var dt = new Date();
		dt.setFullYear(new Date().getFullYear()-18);

		$(Signup.parent).find('.age input').datepicker({
			autoclose: true,
            format: 'yyyy-mm-dd',
            viewMode: "years",
            endDate : dt
        });
	},

	validateZipcode: function(){
		var target = $(Signup.parent).find('#profile-zip_code');

		target.unbind('keyup');
		target.on('blur change keyup',function(e){
			Signup.CheckZipcode($(e.currentTarget).val()) ;
		})
	},

	CheckZipcode: function(zipcode){
		var val = zipcode;
		var message ;

		if(val > 9999 && val < 99999){
			Signup.apiZipcode(val);
		}else if(val ==""){
			message = "Zipcode cannot be blank";
			Signup.OnShowZipcodeErrors(message);
			Signup.zipcode = 0;
		}
		else{
			message = "Zipcode can not more than 5 characte or less ";
			Signup.OnShowZipcodeErrors(message);
			Signup.zipcode = 0;
		}
	},

    apiZipcode: function(zipcode){
    	var message;
        $.getJSON("http://api.zippopotam.us/us/"+zipcode ,function(data){
            if (data.places[0].state == Signup.state){
            	Signup.zipcode = 1;
            	Signup.OnShowZipcodeValid();
            }else{
            	message = " Zipcode not in state Indiana";
            	Signup.zipcode = 0;
            	Signup.OnShowZipcodeErrors(message);
            }
        }).fail(function(jqXHR) {
        	message = " Zipcode invalid";
        	Signup.zipcode = 0;
        	Signup.OnShowZipcodeErrors(message);
        });
    },

    OnShowZipcodeValid:function(){
    	var target = $(Signup.parent).find('.zipcode .form-group');
    	target.removeClass('has-error').addClass('has-success');
    	target.find('.help-block').text('');
    },

	OnShowZipcodeErrors: function(message){
		var target = $(Signup.parent).find('.zipcode .form-group');
		target.removeClass('has-success').addClass('has-error');
		target.find('.help-block').text(message);
	},

	OnShowModalSignUp: function(){
        $(Signup.parent).on('shown.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
        	$('.modal-backdrop.in').addClass('active');
        });
	},

	OnHideModalSignUp: function(){
        $(Signup.modal).on('hidden.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
        });
	},

	OnClickLogin: function(){
		var btn = $(Login.parent).find('.sign-in b');
		btn.unbind();
		btn.on('click',function(){
			// Signup.initialize();
			// $(Login.parent).modal('hide');
		});
	},
    OnClickBackdrop: function(){
        $('.modal-backdrop.in').unbind();
        $('.modal-backdrop.in').on('click',function(e) {
            $(Signup.parent).modal('hide');
        });
    },
};