var Search = {
	parent:'.box-search',
	result:'.result-search',
	params:{
		text:'',
		lat:'',
		lng:''
	},
	result_data:'',
	select:0,
	initialize: function(){
		Search.CheckUserLocation();
		if(isMobile){

		}else{
			Search.CustomScrollBar();
		}
		// Search.FixHeightPopupSearch();
		Search.OnKeypress();
	},

	FixHeightPopupSearch: function(){
		var screen_device = get_size_window();
		var local_height = $(Search.result).find('#local').height(),
			global_height = $(Search.result).find('#global').height(),
			notice = $(Search.result).find('.notice').height() + 20,
			no_result = $(Search.result).find('.no-result.all').height(),
			total_search = local_height + global_height + notice + no_result;

		console.log(total_search);
		// if(screen_device[1] <= total_search){
		// 	console.log('M');
		// 	if(isMobile){
		// 		$(Search.result).css({'height': screen_device[1] - 135});
		// 	}else{
		// 		$(Search.result).css({'height': screen_device[1] - 74});
		// 	}
		// }else{
		// 	console.log('S');
		// 	if(isMobile){
		// 		$(Search.result).css({'height': total_search - 100});
		// 	}else{
		// 		$(Search.result).css({'height': total_search - 80});
		// 	}
		// }
		if(isMobile){
			if(screen_device[1] - total_search > 120){
				$(Search.result).css({'height': total_search})
			}else{
				$(Search.result).css({'height': screen_device[1] - 135})
			}
		}else{
			if(total_search >= screen_device[1]){
				$(Search.result).css({'height': screen_device[1] - 80})
			}else{
				$(Search.result).css({'height': total_search})
			}
		}
	},

	RedirectOnResult: function(){
		Search.FixHeightPopupSearch();
		Search.OnClickNetwrkResult();
		Search.OnClickPostResult()
		Search.OnClickTopicResult();
		Search.OnBlurResult();
	},

	OnClickNetwrkResult: function(){
		var target = $(Search.result).find('.netwrk-result .netwrk-item');

		target.unbind();
		target.on('click',function(e){
			var rs = $(e.currentTarget);

            Search.HideResultSearch();
            $('.modal').modal('hide');
        	Topic.initialize(rs.attr('data-netwrk'));
		});
	},

	OnClickPostResult: function(){
		var target = $(Search.result).find('.post-result .post-item');

		target.unbind();
		target.on('click',function(e){
			var rs = $(e.currentTarget);
			ChatPost.params.post = rs.attr('data-post');

            Search.HideResultSearch();
            if(isMobile){
            	ChatPost.RedirectChatPostPage(ChatPost.params.post,1,0);
            }else{
            	// ChatPost.initialize();
            	$('.modal').modal('hide');
            	PopupChat.params.post = rs.attr('data-post');
                PopupChat.initialize();
            }

		});
	},

	OnClickTopicResult: function(){
		var target = $(Search.result).find('.topic-result .topic-item');

		target.unbind();
		target.on('click',function(e){
			var rs = $(e.currentTarget);
			Post.params.topic = rs.attr('data-topic');
            Post.params.topic_name = rs.find('.topic-name').text();
            Post.params.city = rs.attr('data-city-id');
            Post.params.city_name = rs.attr('data-city-name');

            Topic.data.city = rs.attr('data-city-id');
            Topic.data.city_name = rs.attr('data-city-name');
            Search.HideResultSearch();
            if(isMobile){
        		Post.RedirectPostPage(Post.params.topic);
            }else{
            	$('.modal').modal('hide');
            	Post.initialize();
        	}
		});
	},

	CheckUserLocation: function(){
		if(isGuest){
	        navigator.geolocation.getCurrentPosition(function(position) {
	        	Search.params.lat = position.coords.latitude;
	        	Search.params.lng = position.coords.longitude;
	        });
		}
	},

	OnBlurResult: function(){
		var target = $('body');
		target.unbind('click');

		target.on('click',function(e){
			if(!$(e.target.parentElement).hasClass('item-result')){
				Search.HideResultSearch();
			}
		});
	},

	OnKeypress: function(){
		var target = $(Search.parent).find('.input-search');

		target.unbind('keyup');
		target.on('keyup',function(e){
			var len = $(e.currentTarget).val().length;
			Search.params.text = $(e.currentTarget).val();
			if(len > 1){
				Search.ShowResultSearch()
			}else{
				Search.HideResultSearch()
			}
		});
	},

	ShowResultSearch: function(){
		Ajax.global_search(Search.params).then(function(res){
			Search.result_data = $.parseJSON(res);
			Search.ResetResultSearch();
			Search.GetTemplateSearch();
			$(Search.result).show();
		});
	},

	HideResultSearch: function(){
		var target = $(Search.parent).find('.input-search');
		$(Search.result).hide();
		Search.ResetResultSearch();
	},

	ResetResultSearch: function(){
		$(Search.result).find('.result').empty();
	},

	GetTemplateSearch: function(){
		console.log(Search.result_data);
		var list_template = _.template($("#list_result" ).html());
		var append_html = list_template({result: Search.result_data});

		$(Search.result).find('.result').append(append_html);
		setTimeout(function(){
			Search.RedirectOnResult();
		}, 500);
	},

	CustomScrollBar: function(){
		var parent = $(Search.result);
		parent.mCustomScrollbar({
			theme:"dark"
		});
	},

};