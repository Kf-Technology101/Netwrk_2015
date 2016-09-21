var User ={
	location:{
		zipCode: '',
		lat: '',
		lng: ''
	},
	initialize: function(){
		Login.initialize();
		Signup.initialize();
		// ForgotPass.initialize();
		ResetPass.initialize();
	}
}