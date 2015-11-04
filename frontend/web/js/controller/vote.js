var Vote ={

	SetVote: function(target,post){
		if(target.find('.count').hasClass('disable')){
			Ajax.vote_post({post_id: post}).then(function(data){
				var json = $.parseJSON(data);
				target.find('.count').removeClass('disable');
				target.find('.count').text(json.data);
			});
		}
	}
}