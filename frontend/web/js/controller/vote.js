var Vote ={
	target:'',
	post:'',
	initialize: function(){

        if(isGuest){
        	if(isMobile){
        		Vote.SetVote();
        	}else{
	        	$('.modal').modal('hide');
		        Login.modal_callback = Post;
		        Login.initialize();
		        return false;
        	}
        }else{
        	Vote.SetVote();
        }

	},

	SetVote: function(){
		Ajax.vote_post({post_id: Vote.post}).then(function(data){
			var json = $.parseJSON(data);

			if($(Vote.target).find('.count').hasClass('disable')){
				$(Vote.target).find('.count').removeClass('disable');
			}else{
				$(Vote.target).find('.count').addClass('disable');
			}

			$(Vote.target).find('.count').text(json.data);
		});
	}
}