var Post ={
	params:{
		filter:'post',
		city:'',
		topic:'',
		size: 12,
		page: 1
	},
    list:{
        comment:{
            paging:1,
            status_paging: 1,
            loaded: 0
        },
        view:{
            paging:1,
            status_paging: 1,
            loaded: 0
        },
        brilliant:{
            paging:1,
            status_paging: 1,
            loaded: 0
        }
    },
	tab_current:'feed',
	initialize: function(){

		if(isMobile && $('#list_post').size() > 0){
			Post.GetDefaultValue();
			Post.GetDataOnTab();

			Post.OnChangeTab();
			Post.OnclickBack();
			Post.OnclickCreate();
		}else{

		}
		Create_Post.initialize();
	},

	GetDataOnTab: function(){
		switch(Post.tab_current) {
		    case 'post':
		        Post.ShowPostPage();
		        break;
		    default:
		        Post.ShowFeedPage();
		}
	},

	ShowFeedPage: function(){
		$('#tab_feed').show();
		$('#list_post').find('.dropdown').addClass('visible');
	},

	ShowPostPage: function(){

		$('#tab_post').show();
		$('#list_post').find('.dropdown').removeClass('visible');
		Post.GetTabPost();
	},

	GetDefaultValue: function(){
		var parent = $('#list_post');
		Post.params.topic = parent.data('topic');
		Post.params.city = parent.data('city');
	},

	OnclickBack: function(){
        $('#list_post').find('.back_page img').click(function(){
            window.location.href = baseUrl + "/netwrk/topic/topic-page?city="+Post.params.city; 
        })
	},

	OnclickCreate: function(){
        var btn;
        if(isMobile){
            btn = $('#list_post').find('.create_post');
            btn.unbind();
            btn.on('click',function(e){
                // var topic_id = $(e.currentTarget).parents('.item').eq(0).attr('data-item');
                window.location.href = baseUrl + "/netwrk/post/create-post?city="+ Post.params.city +"&topic="+Post.params.topic;
            });
        }else{
            // btn = $('#modal_topic').find('.item .num_count');
            // btn.unbind();
            // btn.on('click',function(e){
            //     var target = $(e.currentTarget).parents('.item').eq(0),
            //         topic_id = target.attr('data-item');
            //         toptic_name = target.find('.name_topic p').text();
            //     $('#modal_topic').modal('hide');
            //     // Topic.reset_modal();
            //     Create_Post.initialize(Topic.data.city,topic_id,Topic.data.city_name,toptic_name);
            // });
        }
	},

	GetTabPost: function(){
		var parent = $('#filter_post');
		Ajax.get_post_by_topic(Post.params).then(function(data){
			var json = $.parseJSON(data);
			if(json.status == 1){
				console.log(json.data);
				Post.getTemplate(parent,json.data);
			}
		});
	},

    OnChangeTab: function(){
        var target = $('#list_post').find('.filter_sidebar td');
        var self = this;
        target.on('click',function(e){
            var filter = $(e.currentTarget).attr('class');
            if(!$(e.currentTarget).hasClass('active')){
            	$('.tab').hide();
                $('#list_post').scrollTop(0);
                self.tab_current = filter;
                self.ChangeTabActive(target,$(e.currentTarget));
                self.GetDataOnTab();
            }
        });
    },
    ChangeTabActive: function(target,parent){
        $.each(target,function(i,s){
            if($(s).hasClass('active')){
                $(s).removeClass('active');
                parent.addClass('active');
            }
        });
    },
    getTemplate: function(parent,data){
        var self = this;
        var list_template = _.template($("#post_list" ).html());
        var append_html = list_template({posts: data});

        parent.append(append_html);
        // self.onTemplate(json); 
    },
	RedirectPostPage: function(city,topic){
		window.location.href = baseUrl + "/netwrk/post?city="+ city +"&topic="+topic;
	},	
};