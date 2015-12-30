var Post ={
	params:{
		filter:'post',
		city:'',
		city_name:'',
		topic:'',
		topic_name:'',
		size: 30,
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
    modal: '#list_post',
    modal_create: '#create_post',
	tab_current:'post',
	initialize: function(){
		if(isMobile){
			Post.GetDefaultValue();
			Post.GetDataOnTab();
			Post.OnClickSortBtn();
			Post.FilterTabPost($('body'));
			Post.OnClickMeetIconMobile();
			Post.OnClickSelectFilter();
			Post.LazyLoading();
			Create_Post.initialize();
		}else{
			// Post.ShowSideBar(Post.params.city_name,Post.params.topic_name);
			Post.ShowModalPost();
			Post.OnShowModalPost();
			Post.GetDataOnTab();
			Post.OnHideModal();
			Post.FilterTabPost($('.container_post'));
			Post.getNameTopic();
			Post.ShowMeetIcon();
			Post.CustomScrollBar();
			Post.OnClickBackdrop();
		}
		Post.OnclickBack();
		Post.OnclickCreate();
		Post.OnChangeTab();
		Post.OnNetwrkLogo();
	},

	OnClickChat: function(){
		var btn = $("#list_post .post_chat,.post_name");

		btn.unbind();
		btn.on('click',function(e){
			var item_post = $(e.currentTarget).parent().parent().attr('data-item');
			if(isMobile){
				ChatPost.RedirectChatPostPage(item_post, 1, 0);
			}else{
				$("#list_post").modal('hide');
				// ChatPost.params.post = item_post;
				// ChatPost.initialize();
                PopupChat.params.post = item_post;
                PopupChat.params.chat_type = $(e.currentTarget).parent().parent().attr('data-chat-type');
				PopupChat.params.post_name = $(e.currentTarget).parent().parent().find('.information .post_name').html();
				PopupChat.params.post_description = $(e.currentTarget).parent().parent().find('.information .post_massage').html();
                PopupChat.initialize();
			}
		});

		var btn_show_more = $("#list_post .show_more");
		btn_show_more.unbind();

		btn_show_more.on('click',function(e){
			var item_post = $(e.currentTarget).parent().parent().parent().attr('data-item');
			if(isMobile){
				ChatPost.RedirectChatPostPage(item_post, 1, 0);
			}else{
				$("#list_post").modal('hide');
                ChatPost.params.post = item_post;
                ChatPost.initialize();
			}
		});
	},

    OnClickBackdrop: function(){
        $('.modal-backdrop.in').unbind();
        $('.modal-backdrop.in').on('click',function(e) {
        	console.log('click backdrop post');
            $('#list_post').modal('hide');
        });
    },

    OnNetwrkLogo: function(){
        $('#list_post .title_page .title a').click(function(){
            $('#list_post').modal('hide');
        });
    },

    CustomScrollBar: function(){
        $("#list_post").find('.modal-body').mCustomScrollbar({
            theme:"dark",
            callbacks:{
                onTotalScroll: function(){
                    if (Post.list[Post.params.filter].status_paging == 1){
                        Post.GetTabPost();
                    }
                }
            }
        });
    },

	OnClickSelectFilter: function(){
		var btn = $('#list_post .filter_sort .dropdown-toggle,.input-group-addon');

		btn.unbind();
		btn.on('click',function(){
			$('#list_post .dropdown.open .dropdown-toggle').dropdown('toggle');
			$('#list_post').find('[data-toggle="dropdown"]').parent().removeClass('open');
		});
	},

	OnClickSortBtn: function(){
		var btn_parent = $('#list_post').find('.sidebar .filter');

		btn_parent.unbind();
		btn_parent.on('click',function(){
			btn_parent.toggleClass('active');
			$('#list_post').find('.filter_sort').toggleClass('active');
			$('#list_post').find('.container_post').toggleClass('open');
		});
	},

	ShowMeetIcon: function(){
		var btn_parent = $('#list_post').find('#btn_meet');
		var btn_map = $('.map_content').find('#btn_meet');

		// btn_map.hide();
		set_position_btn($('#list_post'),btn_parent,130,100);
		set_position_btn_resize($('#list_post'),btn_parent,130,100);
		btn_parent.show();
		btn_parent.unbind();

		btn_parent.on('click',function(){
			$('#list_post').modal('hide');
			Meet.initialize();
		});

	},

	OnClickMeetIconMobile: function(){
		var btn = $('#btn_meet_mobile');

		btn.unbind();
		btn.on('click',function(){
			window.location.href = baseUrl + "/netwrk/meet";
		});
	},

	ShowSideBar: function(city,topic){
    	var sidebar = $('.map_content .sidebar');
        var city_name = "<span>"+ city +"</span> <i class='fa fa-angle-right'></i><span>"+ topic +"</span>";

        sidebar.find('.container').append(city_name);
        sidebar.show();
	},

	HideSideBar: function(){
		var sidebar = $('.map_content .sidebar');
		sidebar.hide();
		sidebar.find('.container').find('span,.fa').remove();
	},

	OnclickVote: function(){
		var parent = $('#list_post').find('.item_post');
		var btn = parent.find('.icon_brillant');

		btn.unbind();
		btn.on('click',function(e){
			var post_id = $(e.currentTarget).attr('data-item');
			var target = $(e.currentTarget);
			Vote.SetVote(target,post_id);
		});
	},

	ShowModalPost: function(){
		var parent = $('#list_post');
		set_heigth_modal($('#list_post'),0);
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
        	// Post.HideSideBar()
            Post.ResetModal();
        });
	},

	ResetModal: function(){
		var name = $('#list_post').find('.header .title_page');
		var btn_map = $('.map_content').find('#btn_meet');

		name.find('span.title').remove();
		btn_map.show();
		Post.tab_current = "post";
		Post.params.filter = "post";
		var selecFilter = $('#list_post').find('.dropdown-menu li').first().text();
		$('#list_post').find('.tab').hide();
		$('#list_post').find('.dropdown-toggle').text(selecFilter);
		$('#list_post').find('.filter_sidebar td').removeClass('active');
		$('#list_post').find('.filter_sidebar .post').addClass('active');
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
		    case 'feed':
		        Post.ShowFeedPage();
		        break;
		    case 'post':
		        Post.ShowPostPage();
		        break;
		}
	},

	ShowFeedPage: function(){
		$('#list_post').find('#tab_feed').show();
		if(isMobile){
			$('#list_post').find('span.filter').addClass('visible');
			$('#list_post').find('span.filter').removeClass('active');
			$('#list_post').find('.filter_sort').removeClass('active');
			$('#list_post').find('.container_post').removeClass('open');
		}else{
			$('#list_post').find('.dropdown').addClass('visible');
		}
	},

	ShowPostPage: function(){
		if(isMobile){
			$('#list_post').find('span.filter').removeClass('visible');
		}else{
			$('#list_post').find('.dropdown').removeClass('visible');
		}
		$('#tab_post').show();
		Post.ResetTabPost();
		Post.GetTabPost();
	},

	GetDefaultValue: function(){
		var parent = $('#list_post');
		Post.params.topic = parent.data('topic');
		Post.params.city = parent.data('city');
	},

	OnclickBack: function(){
        $('#list_post').find('.back_page span').click(function(){
        	if(isMobile){
        		window.location.href = baseUrl + "/netwrk/topic/topic-page?city="+Post.params.city;
        	}else{
        		$('#list_post').modal('hide');
        		Topic.initialize(Post.params.city);
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
		var parent = $('#list_post #tab_post').find('#filter_'+Post.params.filter);
		parent.show();
		var selecFilter = $('#list_post').find('.dropdown-menu li');
		selecFilter.unbind('click');
		selecFilter.on('click',function(e){
			// console.log($(e.currentTarget).attr('data-value'));
			body.scrollTop(0);
			var name = $(e.currentTarget).text();
			$('#list_post').find('.dropdown-toggle').text(name);
			Post.params.filter = $(e.currentTarget).attr('data-value');
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
				Post.OnclickVote();
				Post.OnClickChat();
				if(isMobile){
					var infomation = $('.container_post').find('.item_post .information');
					var wi_avatar = $($('.container_post').find('.item_post')[0]).find('.users_avatar').width();
					fix_width_post(infomation,145);
				}
			}
		});
	},

	checkStatus: function(data){
		if(data.length == 0){
			Post.list[Post.params.filter].status_paging = 0;
		}else if(data.length < Post.params.size){
			Post.list[Post.params.filter].status_paging = 0;
		}else if(data.length == Post.params.size){
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
                $('#list_post').find('#tab_'+filter).show();
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
        // Post.OnClickAvatarPostListDesktop();
    },

    getNameTemplate: function(parent,data){
        var self = this;
        var list_template = _.template($("#name_post_list" ).html());
        $('#list_post').find('.header .title_page').html("");
        var append_html = list_template({name: data});

        parent.append(append_html);
    },

	RedirectPostPage: function(topic){
		if (ChatPost.GetSearchParam(window.location.href)["previous-flag"]) {
			if (ChatPost.GetSearchParam(window.location.href)["previous-flag"] == 0) {
				window.location.href = baseUrl + "/netwrk/post?topic="+topic;
			} else {
				window.location.href = baseUrl+'/netwrk/chat-inbox/'+'?chat-type=1';
			}
		} else {
			window.location.href = document.referrer == baseUrl+"/netwrk/chat-inbox" ? document.referrer : baseUrl + "/netwrk/post?topic="+topic;
		}
	},

	OnClickAvatarPostListDesktop: function(){
		var avatar = $(Post.modal).find('#tab_post .item_post .users_avatar');
		avatar.unbind();
		avatar.on('click', function(e){
			var user_login = $(e.currentTarget).parent().attr('data-user'),
				user_view = $(e.currentTarget).attr('data-user-post');
			if(user_view != user_login){
				Meet.infoOf = user_view;
				$('.modal').modal('hide');
				// $(Post.modal).modal('hide');
				Meet.initialize();
			}
		});
	}
};