var Vote ={

	SetVote: function(target,post){
		Ajax.vote_post({post_id: post}).then(function(data){
			var json = $.parseJSON(data);

			if(target.find('.count').hasClass('disable')){
				target.find('.count').removeClass('disable');
			}else{
				target.find('.count').addClass('disable');
			}
			
			target.find('.count').text(json.data);
		});
	}
}