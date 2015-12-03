var Signup={
	modal: '#signup',
	page:'#page-signup',
	parent: '',
	initialize:function(){
		if(isMobile){
			$('body').css('background','#fff');
			Signup.parent = Signup.page;
		}else{
			Signup.parent = Signup.modal;
			Signup.OnShowModalLogin();
			Signup.OnHideModalLogin();
		}
		Signup.OnShowDatePicker();
		Signup.OnChangeGender();
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

		$(Signup.parent).find('.age').datepicker({
            dateFormat: 'yy-mm-dd',
            viewMode: "years",
            endDate : dt
        });
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