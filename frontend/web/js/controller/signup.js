var Signup={
	modal: '#signup',
	page:'#page-signup',
	parent: '',
	validate:true,
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
		Signup.OnValidate();
		Signup.OnChangeGender();
	},

	OnValidate: function(){
		$(Signup.parent).find('.firstname');
	},

	ShowModal: function(){
		$(Signup.parent).modal({
			backdrop: true,
			keyboard: false
		})
	},

	OnChangeGender: function(){
		var gender = $(Signup.parent).find('.sex .dropdown-menu li');
		var input_gender = $(Signup.parent).find('input.gender');
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
            dateFormat: 'yy-mm-dd',
            viewMode: "years",
            endDate : dt
        });
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

	OnClickSignUp: function(){
		var btn = $(Login.parent).find('.sign-up b');
		btn.unbind();
		btn.on('click',function(){
			Signup.initialize();
			$(Login.parent).modal('hide');
		});
	},
    OnClickBackdrop: function(){
        $('.modal-backdrop.in').unbind();
        $('.modal-backdrop.in').on('click',function(e) {
            $(Signup.parent).modal('hide');
        });
    },
};