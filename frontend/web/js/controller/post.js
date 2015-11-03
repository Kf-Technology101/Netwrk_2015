var Post ={
	params:{
		filter:'post',
		city:'',
		city_name:'',
		topic:'',
		topic_name:'',
		size: 12,
		page: 1
	},
    list:{
        post:{
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
		if(isMobile){
			Post.GetDefaultValue();
			Post.GetDataOnTab();
			Post.FilterTabPost($('body'));
			Create_Post.initialize();
		}else{
			Post.ShowModalPost();
			Post.OnShowModalPost();
			Post.GetDataOnTab();
			Post.OnHideModal();
			Post.FilterTabPost($('.container_post'));
			Post.getNameTopic();
		}	
		
		Post.OnclickBack();
		Post.OnclickCreate();
		Post.LazyLoading();
		Post.OnChangeTab();
	},

	ShowModalPost: function(){
		var parent = $('#list_post');

		parent.modal({
            backdrop: true,
            keyboard: false
        });
	},

	OnShowModalPost: function(){
        $('#list_post').on('shown.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
            Post.GetDataOnTab();
        });
	},

	OnHideModal: function(){
        $('#list_post').on('hidden.bs.modal',function(e) {
        	$(e.currentTarget).unbind();
            Post.ResetModal();
        });
	},

	ResetModal: function(){
		var name = $('#list_post').find('.header .title_page');
		name.find('span.title').remove();
	},

	getNameTopic: function(){
		var name = $('#list_post').find('.header .title_page');
		Ajax.get_topic(Post.params).then(function(data){
			Post.getNameTemplate(name,data);
		});
	},

    LazyLoading: function(){
        var self = this;
        var containt = $('.container_post');
        if (isMobile) {
            $(window).scroll(function() {   
                if( $(window).scrollTop() + $(window).height() == $(document).height() && Post.list[Post.params.filter].status_paging == 1) {
                    setTimeout(function(){
                    	self.GetTabPost();
                    },300);
                }
            });
        }else{
            containt.scroll(function(e){
                var parent = $('#filter_'+self.params.filter);
                var  hp = parent.height();
                if(containt.scrollTop() + containt.height() == hp && Post.list[Post.params.filter].status_paging == 1){
                    Post.list[Post.params.filter].status_paging = 0;
                    setTimeout(function(){
                        self.GetTabPost();
                    },300);
                }
            });
        }
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
		Post.ResetTabPost();
		Post.GetTabPost();
	},

	GetDefaultValue: function(){
		var parent = $('#list_post');
		Post.params.topic = parent.data('topic');
		Post.params.city = parent.data('city');
	},

	OnclickBack: function(){
        $('#list_post').find('.back_page img').click(function(){
        	if(isMobile){
        		window.location.href = baseUrl + "/netwrk/topic/topic-page?city="+Post.params.city; 
        	}else{
        		$('#list_post').modal('hide');
        		Topic.init(Post.params.city);
        	}
        })
	},

	OnclickCreate: function(){
        var btn = $('#list_post').find('.create_post');
        btn.unbind();
        btn.on('click',function(){
	        if(isMobile){
                window.location.href = baseUrl + "/netwrk/post/create-post?city="+ Post.params.city +"&topic="+Post.params.topic;
	        }else{
	        	$('#list_post').modal('hide');
	        	Create_Post.initialize(Post.params.city,Post.params.topic,Post.params.city_name,Post.params.topic_name);
	        }
        });

	},

	FilterTabPost: function(body){
		var parent = $('#tab_post').find('#filter_'+Post.params.filter);
		parent.show();
		$('#list_post').find('.dropdown select').change(function(e){
			body.scrollTop(0);
			Post.params.filter = $(e.currentTarget).val();
			Post.ShowPostPage();
		});
	},

	ResetTabPost: function(){
		var parent = $('#tab_post').find('#filter_'+Post.params.filter);
		$('#tab_post').find('.filter_page').hide();
		parent.find('.item_post').remove();
		parent.find('.no-data').show();
		Post.params.page = 1;
		Post.list[Post.params.filter].status_paging = 1;
	},

	GetTabPost: function(){
		var parent = $('#tab_post').find('#filter_'+Post.params.filter);
		
		Ajax.get_post_by_topic(Post.params).then(function(data){
			var json = $.parseJSON(data);
			Post.checkStatus(json.data);
			if(json.status == 1 && json.data.length> 0){
				parent.show();
				parent.find('.no-data').hide();
				Post.getTemplate(parent,json.data);
			}
		});
	},

	checkStatus: function(data){
		console.log(Post.list);
		if(data.length == 0){
			Post.list[Post.params.filter].status_paging = 0;
		}else if(data.length < 12){
			Post.list[Post.params.filter].status_paging = 0;
		}else if(data.length == 12){
			Post.list[Post.params.filter].status_paging = 1;
			Post.params.page ++ ;
		}
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
    },

    getNameTemplate: function(parent,data){
        var self = this;
        var list_template = _.template($("#name_post_list" ).html());
        var append_html = list_template({name: data});

        parent.append(append_html);
    },

	RedirectPostPage: function(city,topic){
		window.location.href = baseUrl + "/netwrk/post?city="+ city +"&topic="+topic;
	},	
};