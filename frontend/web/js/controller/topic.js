var Topic = {
    data:{
        filter: 'recent',
        city: '',
        size: 30,
        city_name:'',
        zipcode:''

    },
    city: {
        id: '',
        zipcode: '',
        country: 'US'
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
        recent:{
            paging:1,
            status_paging: 1,
            loaded: 0
        }
    },
    feed:{
        paging:1,
        status_paging: 1,
        loaded: 0
    },
    params:{
        zipcode:'',
        name:'',
        lat: '',
        lng:''
    },
    modal: '#modal_topic',
    modal_create: '#create_topic',
    tab_current: 'feed',
    btn_nav_map: '.box-navigation .btn_nav_map',
    slider: '#slider_topic',
    init: function(){
        Topic._onclickBack();
        Topic.GetDataOnTab();
        Topic.load_topic();
        Topic.get_data_new_netwrk();
        Topic.RedirectPostList();
        Topic.scroll_bot();
        Topic.OnClickSortBtn();
        Topic.OnClickSelectFilter();
        Topic.OnClickChangeTab();
        Topic.eventClickMeetMobile();
        Topic.LoadFeedModal();
        Topic.OnClickCreateGroup();
        if(isMobile){
            //Favorite button initialize
            Topic.OnClickFavorite();
            Default.ShowNotificationOnChat();
            LandingPage.FixWidthPostLanding();
            Default.SetAvatarUserDropdown();
            Topic.onClickEditTopic();
        } else {
            Topic.displayPositionModal();
        }
        Topic.CheckTabCurrent();
    },

    CheckTabCurrent: function(){
        var parent = $('#modal_topic,#show-topic,#slider_topic');

        if(Topic.tab_current == 'feed'){
            var btn = parent.find('.filter_sidebar td.feed');
            btn.trigger('click');
        } else if(Topic.tab_current == 'topic'){
            var btn = parent.find('.filter_sidebar td.topic');
            btn.trigger('click');
        } else if(Topic.tab_current == 'groups'){
            var btn = parent.find('.filter_sidebar td.groups');
            btn.trigger('click');
        }
    },

    initialize: function(city,params){
        if (isMobile) {
            Topic.show_page_topic(city,params);
        }else {
            Topic.getCityById(city);
            Topic.OnShowModalPost();
            Topic.close_modal();
            Topic.show_modal_topic(city,params);
            Topic._onclickBack();
            Topic.OnClickBackdrop();
            Topic.onNetwrkLogo();
            Topic.CheckTabCurrent();
            Topic.OnClickCreateGroup();
            Default.onCLickModal();
            Topic.OnClickFavorite();

            //init tooltip on topic list
            Common.InitToolTip();
        }
    },

    CustomScrollBar: function(){
        var parent = $("#modal_topic").find('.modal-body')
                .add($('#slider_topic').find('.slider-body'));

        parent.mCustomScrollbar("scrollTo",$('#tab_feed'));
        parent.css('height', $(window).height()-110);
        parent.mCustomScrollbar({
            theme:"dark",
            callbacks:{
                onTotalScroll: function(){
                    if (Topic.list[Topic.data.filter].status_paging == 1 && Topic.tab_current == "topic"){
                        Topic.load_topic_more();
                    }else if(Topic.feed.status_paging == 1 &&Topic.tab_current == "feed"){
                        console.log('Load more topic feeds');
                        Topic.feed.paging ++;
                        Topic.LoadMoreFeed();
                    }
                }
            }
        });
    },

    OnClickSelectFilter: function(){
        var btn = $('#show-topic .filter_sort .dropdown-toggle,.input-group-addon');

        btn.unbind();
        btn.on('click',function(){
            $('#show-topic .dropdown.open .dropdown-toggle').dropdown('toggle');
            $('#show-topic').find('[data-toggle="dropdown"]').parent().removeClass('open');
        });
    },
    OnClickChangeTab: function(){
        var parent = $('#modal_topic,#show-topic');
        var btn = parent.find('.filter_sidebar td');

        btn.unbind();
        btn.on('click',function(e){
            btn.removeClass('active');
            parent.find('.tab').hide();
            Topic.tab_current = $(e.currentTarget).attr('class');
            $(e.currentTarget).addClass('active');
            Topic.GetDataOnTab();
            if(isMobile){
                $(window).scrollTop(0);
            }else{
                parent.find('.modal-body').mCustomScrollbar("scrollTo",0);
            }
        });
    },

    GetDataOnTab: function(){
        switch(Topic.tab_current) {
            case 'feed':
                Topic.ShowFeedPage();
                Topic.tab_current = 'feed';
                break;
            case 'topic':
                Topic.ShowTopicPage();
                break;
            case 'groups':
                Topic.ShowGroupsPage();
                break;
        }
    },

    ShowTopicPage: function(){
        var parent = $('#modal_topic,#show-topic');

        parent.find('#tab_topic').show();

        parent.find('.groups-dropdown').addClass('visible').css('display','none');
        parent.find('.topics-dropdown').removeClass('visible').css('display','table');
        parent.find('.tab-header-topic').removeClass('hidden');
        parent.find('.tab-header-group').addClass('hidden');

        $('.create_topic').show();
        $('#create_group').hide();

        //enable btn create topic
        parent.find('.header .title_page').removeClass('on-feed');
        parent.find('.header .create_topic').removeClass('on-feed');

        var parentFilter = $('#item_list_'+Topic.data.filter);
        Topic.filter_topic(parentFilter);
    },

    ShowFeedPage: function(){
        var parent = $('#modal_topic,#show-topic,#slider_topic');

        parent.find('#tab_feed').show();

        parent.find('.groups-dropdown').addClass('visible').css('display','inherit');
        parent.find('.topics-dropdown').addClass('visible').css('display','none');
        parent.find('.tab-header-topic').addClass('hidden');
        parent.find('.tab-header-group').addClass('hidden');

        parent.find('.filter').addClass('visible');
        parent.find('.container').removeClass('open');

        //disable btn create topic
        parent.find('.header .title_page').addClass('on-feed');
        parent.find('.header .create_topic').addClass('on-feed');
        parent.find('.header #create_group').addClass('on-feed');

        $('.create_topic').hide();
        $('#create_group').hide();
    },

    ShowGroupsPage: function() {
        var parent = $('#modal_topic,#show-topic');

        parent.find('#tab_groups').show();

        parent.find('.topics-dropdown').addClass('visible').css('display','none');
        parent.find('.groups-dropdown').removeClass('visible').css('display','inherit');
        parent.find('.tab-header-topic').addClass('hidden');
        parent.find('.tab-header-group').removeClass('hidden');

        $('.create_topic').hide();
        $('#create_group').show();

        //enable btn create topic
        parent.find('.header .title_page').removeClass('on-feed');
        parent.find('.header .create_topic').removeClass('on-feed');

        Group.initialize('tab');
    },

    OnClickSortBtn: function(){
        var btn_parent = $('#show-topic').find('.sidebar .filter');

        btn_parent.unbind();
        btn_parent.on('click',function(){
            btn_parent.toggleClass('active');
            $('#show-topic').find('.filter_sort').toggleClass('active');
            $('#show-topic').find('.container').toggleClass('open');

            if($('#show-topic').find('.filter_sort').hasClass('active')){
                Topic.filter_topic($(window));
            }
        });
    },
    RedirectPostList: function() {
        var parent = $('#item_list_'+Topic.data.filter);
        parent.find('.item .topic_post').unbind();
        parent.find('.item .topic_post').on('click',function(e){
            var topic = $(e.currentTarget).parent().data('item');
            if(isMobile){
                Post.RedirectPostPage(topic, false);
            }else{
                //hide all opened tooltips
                Common.HideTooTip();
                $('#modal_topic').modal('hide');
                Post.params.topic = topic;
                Post.params.topic_name = $(e.currentTarget).find('.name_topic p').text();
                Post.params.city = Topic.data.city;
                Post.params.city_name = Topic.data.city_name;
                Post.initialize();
            }
        });

        parent.find('.item .name_post a').unbind();
        parent.find('.item .name_post a').on('click',function(e){
            e.stopPropagation();
        })
    },

    OnClickBackdrop: function(){
        $('.modal-backdrop.in').unbind();
        $('.modal-backdrop.in').click(function(e) {
            $('#modal_topic').modal('hide');
        });
    },

    get_data_new_netwrk: function(){
        var parent = $('#show-topic');

        if (parent.attr('data-zipcode')){
            Topic.params.zipcode = parent.attr('data-zipcode');
            Topic.params.lat = parent.attr('data-lat');
            Topic.params.lng = parent.attr('data-lng');
            Topic.params.name = parent.attr('data-name');
        }
    },

    create_post: function(){
        var btn;
        if(isMobile){
            btn = $('#show-topic').find('.item .num_count');
            btn.unbind();
            btn.on('click',function(e){
                var topic_id = $(e.currentTarget).parents('.item').eq(0).attr('data-item');
                window.location.href = baseUrl + "/netwrk/post/create-post?city="+ Topic.data.city +"&topic="+topic_id;
            });
        }else{
            btn = $('#modal_topic').find('.item .num_count');
            btn.unbind();
            btn.on('click',function(e){
                var target = $(e.currentTarget).parents('.item').eq(0),
                    topic_id = target.attr('data-item');
                    toptic_name = target.find('.name_topic p').text();
                $('#modal_topic').modal('hide');
                // Topic.reset_modal();
                Create_Post.initialize(Topic.data.city,topic_id,Topic.data.city_name,toptic_name);
            });
        }
    },

    create_topic: function(){
        var btn;
        if(isMobile){
            btn = $('#show-topic').find('.create_topic');
            btn.unbind();
            btn.on('click',function(){
                if(Topic.params.zipcode){
                    window.location.href = baseUrl + "/netwrk/topic/create-topic?city="+Topic.data.city+"&zipcode="+Topic.params.zipcode+"&name="+Topic.params.name+"&lat="+Topic.params.lat+"&lng="+Topic.params.lng;
                }else{
                    window.location.href = baseUrl + "/netwrk/topic/create-topic?city="+Topic.data.city;
                }
            });
        }else{
            btn = $('#modal_topic').find('.create_topic');
            btn.unbind();
            btn.on('click',function(){
                $('#modal_topic').modal('hide');
                Create_Topic.initialize(Topic.data.city,Topic.data.city_name);
            });
        }
    },

    scroll_bot: function(){
        var self = this;
        var containt = $('.containt');
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() == $(document).height() && self.list[self.data.filter].status_paging == 1 && Topic.tab_current == "topic") {
                setTimeout(function(){
                    self.load_topic_more();
                },300);
            }else if(Topic.feed.status_paging == 1 && $(window).scrollTop() + $(window).height() == $(document).height()){
                setTimeout(function(){
                    Topic.feed.paging ++;
                    Topic.LoadMoreFeed();
                },300);
            }
        });
    },


    show_modal_topic: function(city,new_params){
        var self = this;
        var parent = $('#item_list_'+self.data.filter);
        var sidebar = $('.map_content .sidebar');

        if(new_params){
            self.data.zipcode = new_params.zipcode;
        }
        if(city){
            self.data.city = city;
        }

        var params = {'city': self.data.city,'zipcode': self.data.zipcode, 'filter': self.data.filter,'size': self.data.size,'page':1};

        parent.show();
        /*set_heigth_modal($('#modal_topic'),30);
        $('#modal_topic').modal({
            backdrop: true,
            keyboard: false
        });*/

        if ($(Topic.slider).css('left') != '0px') {
            $.when(Common.closeAllLeftSliders()).done(function () {
                $.when($(Topic.slider).animate({
                    "left": "0"
                }, 500)).done(function () {
                    Topic.OnShowModalPost();
                });
            });
        }
    },

    OnShowModalPost: function(){
        /*$('#modal_topic').unbind('shown.bs.modal');
        $('#modal_topic').on('shown.bs.modal',function(e) {
            Topic.LoadFeedModal();
            Topic.load_topic_modal();
            Topic.OnClickChangeTab();
            Topic.displayPositionModal();
            Group.LoadGroupModal();
        });*/
        if($(Topic.slider).css('left') == '0px') {
            Topic.LoadFeedModal();
            Topic.load_topic_modal();
            Topic.OnClickChangeTab();
            Topic.displayPositionModal();
            Group.LoadGroupModal();
        }
    },

    LoadFeedModal: function() {
        console.log('load feed modal');
        var self = this;
        var parent = $('#modal_topic,#show-topic,#slider_topic').find('#tab_feed');
        var cityname = $('#modal_topic,#slider_topic').find('.title_page');
        var params = {'city': self.data.city,'zipcode': self.data.zipcode, 'filter': self.data.filter,'size': self.data.size,'page':Topic.feed.paging};
        parent.show();
        Ajax.show_feed(params).then(function(data){
            console.log('In show_feed');
            if(!isMobile){
                self.getTemplateModal(cityname,data);
            }
            parent.scrollTop(0);
            Topic.getTemplateFeed(parent,data);
            Topic.getTemplateHistory(parent,data);
            Topic.GetDataOnTab();
            Topic.OnClickPostFeed();
            Topic.OnClickVoteFeed();
            Topic.OnClickTopicFeed();
            Topic.OnClickAvatarTopPostFeed();
            Topic.OnClickAvatarTopFeed();
            Topic.OnClickChatTopPostFeed();

            //todo: get weather data of that zipcode.
            //Topic.getZipWeatherData();
        });
    },

    LoadMoreFeed: function(){
        console.log('Load more feed topic');
        var params = {'city': Topic.data.city,'zipcode': Topic.data.zipcode, 'filter': Topic.data.filter,'size': Topic.data.size,'page':Topic.feed.paging};
        var parent = $('#modal_topic,#show-topic,#slider_topic').find('#tab_feed');
        Ajax.show_feed(params).then(function(data){
            Topic.CheckPagingFeed(data);
            Topic.getTemplateHistory(parent,data);
        });
    },

    CheckPagingFeed: function(data){
        var json = $.parseJSON(data);
        if(json.feed.length < Topic.data.size){
            Topic.feed.status_paging = 0;
        }
    },

    getTemplateHistory: function(parent,data){
        var json = $.parseJSON(data);
        var target = parent.find('.top-feed .top-feed-content');

        var list_template = _.template($( "#top_feed" ).html());
        var append_html = list_template({feed: json});

        target.append(append_html);
    },

    getTemplateFeed: function(parent,data){
        var json = $.parseJSON(data);
        if(json.top_post.length > 0 || json.top_topic.length > 0 || json.feed.length > 0 || (json.weather_feed != undefined || json.weather_feed.length > 0) || (json.job_feed != undefined || json.job_feed.length > 0)){
            parent.find('.no-data').hide();
            parent.html('');
            var list_template = _.template($( "#feed_list" ).html());
            var append_html = list_template({feed: json});
            parent.append(append_html);
        }
    },

    OnClickAvatarTopPostFeed: function() {
        Topic.OnClickAvatarFeed($('.top-post').find('.top-post-content .post-row .avatar'));
    },

    OnClickAvatarTopFeed: function() {
        Topic.OnClickAvatarFeed($('.top-feed .top-feed-content').find('.feed-post .avatar-poster'));
    },

    OnClickAvatarFeed: function(target){
        var avatar = target;
        avatar.unbind();
        avatar.on('click', function(e){
            var user_login = $(e.currentTarget).parent().attr('data-user');
            if(user_login != UserLogin){
                if(!isMobile){
                    Meet.pid = 0;
                    Meet.ez = user_login;
                    $('.modal').modal('hide');
                    Meet.initialize();
                } else {
                    window.location.href = baseUrl + "/netwrk/meet?user_id=" + user_login + "&from=discussion";
                }
            }
        });
    },

    OnClickChatTopPostFeed: function(){
        var target = $('#modal_topic,#show-topic,#slider_topic').find('.top-post .action .chat');
        target.unbind();
        target.on('click',function(e){
                var post_id = $(e.currentTarget).parent().parent().attr('data-value'),
                    post_name = $(e.currentTarget).parent().parent().find('.post-title').text(),
                    post_content = $(e.currentTarget).parent().parent().find('.post-content').text(),
                    topic_id = $(e.currentTarget).parent().parent().attr('data-topic_id'),
                    topic_title = $(e.currentTarget).parent().parent().attr('data-topic_title'),
                    city_zipcode= $(e.currentTarget).parent().parent().attr('data-city_zipcode'),
                    city_id= $(e.currentTarget).parent().parent().attr('data-city_id');

            if(isMobile){
                sessionStorage.url = window.location.href;
                sessionStorage.feed_topic = 1;
                PopupChat.RedirectChatPostPage(post_id, 1, 1);
            }else{
                PopupChat.params.post = post_id;
                PopupChat.params.chat_type = 1;
                PopupChat.params.post_name = post_name;
                PopupChat.params.post_description = post_content;
                PopupChat.params.topic_id = topic_id;
                PopupChat.params.city_name = city_zipcode;
                PopupChat.params.city = city_id;
                PopupChat.initialize();
            }
        });
    },

    OnClickPostFeed: function(){
        var target = $('#modal_topic,#slider_topic').find('.top-post .post')
            .add($('#modal_topic,#slider_topic').find('.feed-row.feed-post .feed-content'))
            .add($('#collapseFavoriteCommunities').find('.feed-row.feed-post .feed-content'))
            .add($('#show-topic').find('.feed-row.feed-post .feed-content'))
            .add($('.recentActivityPosts').find('.post'))
            .add($(LandingPage.netwrk_news).find('.tab-content .feed-row.feed-post .feed-content'))
            .add($('#areaNews').find('.feed-row.feed-post .feed-content'))
            .add($('#modal_topic').find('.right-section').find('.post-trigger'))
            .add($('#modal_topic,#slider_topic').find('.header').find('.right-section').find('.post-trigger'))
            .add($('#show-topic').find('.right-section').find('.post-trigger'))
            .add($('#list_post,#slider_list_post').find('.right-section').find('.post-trigger'));
        target.unbind();
        target.on('click',function(e){
                var post_id = $(e.currentTarget).parent().attr('data-value'),
                    post_name = $(e.currentTarget).find('.post-title').text(),
                    post_content = $(e.currentTarget).find('.post-content').text();
            if(isMobile){
                sessionStorage.url = window.location.href;
                sessionStorage.feed_topic = 1;
                PopupChat.RedirectChatPostPage(post_id, 1, 1);
            }else{
                // $(LandingPage.modal).modal('hide');
                PopupChat.params.post = post_id;
                PopupChat.params.chat_type = 1;
                PopupChat.params.post_name = post_name;
                PopupChat.params.post_description = post_content;
                console.log(PopupChat.params);
                PopupChat.initialize();
            }
        });
    },

    OnClickVoteFeed: function() {
        var target = $('#modal_topic,#show-topic,#slider_topic').find('.top-post .action .brilliant');
        target.unbind();
        target.on('click',function(e){
            var post_id = $(e.currentTarget).parent().parent().attr('data-value');
            Ajax.vote_post({post_id: post_id}).then(function(res){
                var json = $.parseJSON(res);
                $(e.currentTarget).text(json.data);
            });
        });
    },

    OnClickTopicFeed: function(){
        var target = $('#modal_topic,#slider_topic').find('.topic-row')
            .add($('#modal_topic,#slider_topic').find('.feed-row.feed-topic'))
            .add($('#show-topic').find('.feed-row.feed-topic'))
            .add($('#profileRecentTopic').find('.topic-trigger'))
            .add($('#collapseFavoriteCommunities').find('.feed-row.feed-topic'))
            //.add($('.popup-box').find('.popup-topic-trigger'))
            .add($(LandingPage.netwrk_news).find('.tab-content .feed-row.feed-topic'))
            .add($('#areaNews').find('.feed-row.feed-topic'));
        target.unbind();
        target.on('click',function(e){
            var city_id = $(e.currentTarget).attr('data-city'),
                city_name = $(e.currentTarget).attr('data-city-name'),
                topic_id = $(e.currentTarget).attr('data-value'),
                topic_name = $(e.currentTarget).find('.topic-title').text();
            if(isMobile){
                Post.RedirectPostPage(topic_id);
            }else{
                $('.modal').modal('hide');

                Post.params.topic = topic_id;
                Post.params.topic_name = topic_name;
                Post.params.city = city_id;
                Post.params.city_name = city_name;
                Post.initialize();
            }
        });

    },

    load_topic_modal: function(){
        var self = this;
        var parent = $('#item_list_'+self.data.filter);
        var cityname = $('#modal_topic,#slider_topic').find('.title_page');
        var favorite = $('#modal_topic,#slider_topic').find('.Favorite-btn-wrap');
        var params = {'city': self.data.city,'zipcode': self.data.zipcode, 'filter': self.data.filter,'size': self.data.size,'page':1};

        parent.show();
        // sidebar.show();
        Ajax.show_topic(params).then(function(data){
            parent.scrollTop(0);
            self.list[self.data.filter].loaded = self.list[self.data.filter].paging ;
            self.getTemplate(parent,data);
            self.getTemplateModal(cityname,data);
            self.getFavoriteTemplate(favorite, data);
            Topic.CustomScrollBar();
            Topic.filter_topic(parent);
            Topic.GetDataOnTab();
            Common.deleteTrigger();
        });
    },

    close_modal: function(){
        $('#modal_topic').unbind('hidden.bs.modal');
        $('#modal_topic').on('hidden.bs.modal',function(e) {
            $(e.currentTarget).unbind();
            Topic.reset_modal();
            //
            // Map.get_data_marker();

        });
    },

    reset_modal: function(){
        var self = this;
        var parent = $("div[id^='item_list']");
        var target = $('#modal_topic,#slider_topic').find('.filter_sidebar').find('td');
        var filter = ['post','view','recent'];
        var selecFilter = $('#modal_topic,#slider_topic').find('.dropdown-menu li').first().text();

        self.data.filter = 'recent';
        parent.hide();
        $('#modal_topic,#slider_topic').find('.dropdown-toggle').text(selecFilter);
        $('#modal_topic,#slider_topic').find('.title_page').empty();
        $('#modal_topic,#slider_topic').find('.tab').hide();
        // $('.map_content .sidebar').hide();
        // $('.map_content .sidebar .container').find('span').remove();
        $.each(filter,function(i,e){
            self.list[e].paging = 1;
            self.list[e].status_paging = 1;
            self.list[e].loaded = 0;
        });

        $.each(parent,function(i,e){
            $(e).find('.item').remove();
        });

        $.each(target,function(i,s){
            if($(s).hasClass('active')){
                $(s).removeClass('active');
            }
        });
        $(target[0]).addClass('active');
        Common.HideTooTip();
        Topic.ResetModalTabFeed();
    },

    ResetModalTabFeed: function(){
        var parent = $('#modal_topic,#slider_topic').find('#tab_feed');
        parent.find('.top-post,.top-topic,.top-feed, .weather-feed-content, .job-feed-content').remove();
        parent.find('.no-data').show();
        Topic.tab_current = 'feed';
        Topic.feed.paging = 1;
        Topic.feed.status_paging = 1;
    },

    show_page_topic: function(city, params){
        if(typeof params != "undefined") {
            if(typeof params.group != "undefined") {
                window.location.href = baseUrl + "/netwrk/topic/topic-page?city="+city+"&group="+params.group+"&name="+params.name+"&from="+params.from;
            } else if(typeof params.zipcode != "undefined") {
                window.location.href = baseUrl + "/netwrk/topic/topic-page?city="+city+"&zipcode="+params.zipcode+"&name="+params.name+"&lat="+params.lat+"&lng="+params.lng;
            }
        } else {
            window.location.href = baseUrl + "/netwrk/topic/topic-page?city="+city;
        }
    },

    _onclickBack: function(){
        if(isMobile){
            $('#show-topic .back_page span, .box-navigation .btn_nav_map').unbind('click').click(function(){
                sessionStorage.show_landing = 1;
                if($('.back_page').hasClass('back_help')){
                    window.location.href = baseUrl + "/netwrk/default/home?current=help";
                } else {
                    window.location.href = baseUrl + "/netwrk/default/home";
                }
            })
        }else{
            $('#modal_topic .back_page span, .box-navigation .btn_nav_map').unbind('click').click(function(){
                $('#modal_topic').modal('hide');
            })
        }
    },

    onNetwrkLogo: function(){
        $('#modal_topic .title_page .title a').click(function(){
            $('#modal_topic').modal('hide');
        });
    },

    load_topic: function(){
        var self = this;
        var city = $('#show-topic').data('city');
        var zipcode = $('#show-topic').data('zipcode');

        if(zipcode){
            self.data.zipcode = zipcode;
        }else{
            self.data.zipcode = '';
        }
        var params = {'city': city,'zipcode': self.data.zipcode, 'filter': self.data.filter,'size': self.data.size,'page':self.list[self.data.filter].paging};
        self.data.city = city;

        $(window).scrollTop(0);
        if($('#Topic').attr('data-action') == 'topic-page'){
            if(self.list[self.data.filter].status_paging == 1){
                Ajax.show_topic(params).then(function(data){
                    var parent = $('#show-topic').find('#item_list_'+self.data.filter);
                    self.list[self.data.filter].loaded = self.list[self.data.filter].paging ;
                    parent.show();
                    self.getTemplate(parent,data);
                    if(isMobile){
                        setTimeout(function(){
                            var favorite = $('#show-topic').find('#tab_feed').find('.Favorite-btn-wrap');
                            self.getFavoriteTemplate(favorite, data);
                        },100);
                    }
                });
            }
            console.log("loading groups!!!");
            Ajax.show_groups(params).then(function(data){
                var parent = $('#show-topic').find('#item_group_list_'+self.data.filter);
                self.list[self.data.filter].loaded = self.list[self.data.filter].paging ;
                parent.show();
                var json = $.parseJSON(data);
                Group.getTemplate(parent,json);
            });
        }
    },

    load_topic_more: function(){
        var self = this;
        self.list[self.data.filter].paging ++ ;
        var params = {'city': self.data.city, 'filter': self.data.filter,'size': self.data.size,'page':self.list[self.data.filter].paging};
        Ajax.show_topic(params).then(function(data){
            var parent = $('#item_list_'+self.data.filter);
            self.getTemplate(parent,data);
            Common.deleteTrigger();
        });
    },

    filter_topic: function(contain){
        var target = $('#modal_topic,#show-topic').find('.topics-dropdown .dropdown-menu li');
        var self = this;

        target.unbind();
        target.on('click',function(e){
            var filter = $(e.currentTarget).attr('data-value');
            var name = $(e.currentTarget).text();
            $("#modal_topic,#show-topic").find('.topics-dropdown .dropdown-toggle').text(name);
            $("#modal_topic,#show-topic").find("div[id^='item_list']").hide();
            contain.scrollTop(0);
            self.data.filter = filter;
            self.load_topic_filter($(e.currentTarget),self.data.city,self.data.filter);

        });
    },

    change_button_active: function(target,parent,city,filter){
        $.each(target,function(i,s){
            if($(s).hasClass('active')){
                $(s).removeClass('active');
                parent.addClass('active');
            }
        });
    },

    load_topic_filter: function(){
        var self = this;
        var parent = $('#modal_topic,#show-topic').find('#item_list_'+self.data.filter);
        var params = {'city': self.data.city, 'filter': self.data.filter,'size': self.data.size,'page':self.list[self.data.filter].paging};

        parent.show();

        if (self.list[self.data.filter].paging != self.list[self.data.filter].loaded){
            Ajax.show_topic(params).then(function(data){
                self.getTemplate(parent,data);
                self.list[self.data.filter].loaded = self.list[self.data.filter].paging ;
                Common.deleteTrigger();
            });
        }
    },

    getTemplate: function(parent,data){
        var self = this;
        var json = $.parseJSON(data);
        var list_template = _.template($( "#topic_list" ).html());
        var append_html = list_template({topices: json.data});

        parent.append(append_html);
        self.onTemplate(json);
    },

    onTemplate: function(json){
        var self = this;

        if(json.data.length == 0){
            $('#item_list_'+self.data.filter).find('.no-data').show();
            self.list[self.data.filter].status_paging = 0;
        }else if(json.data.length < self.data.size && json.data.length > 0){
            $('#item_list_'+self.data.filter).find('.no-data').hide();
            self.list[self.data.filter].status_paging = 0;
        }else{
            $('#item_list_'+self.data.filter).find('.no-data').hide();
            self.list[self.data.filter].status_paging = 1;
        }
        Topic.create_topic();
        Topic.RedirectPostList();
        Topic.onClickTopicMapMarker();
        Topic.onClickEditTopic();
        // this.create_post();
    },
    onClickEditTopic: function() {
        console.log('in onClickEditTopic');
        if(isMobile){
            var target = $('.edit-topic', '#show-topic');
            target.each(function () {
                $(this).unbind("click").click(function () {
                    var topic_id = $(this).attr('data-id'),
                        city_id = $(this).attr('data-city');
                    window.location.href = baseUrl + "/netwrk/topic/create-topic?city="+city_id+'&topic_id='+topic_id;
                });
            });
        }else {
            var target = $('.edit-topic', Topic.modal);

            target.each(function () {
                $(this).unbind("click").click(function () {
                    $('#modal_topic').modal('hide');
                    Common.HideTooTip();
                    Create_Topic.initialize($(this).data("city_id"), $(this).data("city_name"), null, null, null, null, $(this).data("id"));
                });
            });
        }
    },

    getTemplateModal: function(parent,data){
        var self = this;
        var json = $.parseJSON(data);
        var list_template = _.template($( "#city_name" ).html());
        var append_html = '';
        append_html = list_template({city: json.city, office_type: json.office_type});
        Topic.data.city_name = json.city;
        parent.html(append_html);

        // Append city general post to header
        var general_post = $('#modal_topic,#slider_topic').find('.header').find('.right-section');
        var post_template = _.template($( "#general_post" ).html());
        var post_append_html = '';
        post_append_html = post_template({post_id: json.post_id, post_title: json.post_title, topic_title: json.topic_title});
        general_post.html(post_append_html);

        Topic.OnClickPostFeed();
    },

    eventClickMeetMobile: function(){
        var target = $('#btn_meet_mobile');
        target.unbind();
        target.on('click',function(){
            target.bind();
            window.location.href = baseUrl + "/netwrk/meet";
        });
    },

    displayPositionModal: function(){
        var modal = $('.popup_chat_modal .popup-box');
        if(modal.length > 0){
            $('.popup_chat_modal .popup-box').css('z-index', '1050');
        }
    },
    OnClickFavorite: function(){
        if(isMobile) {
            var target = $('#show-topic').find('.btn-favorite').add($('#favoriteCommunities').find('.un-favorite-trigger'));
        } else {
            var target = $('#modal_topic,#slider_topic').find('.btn-favorite').add($('#favoriteCommunities').find('.un-favorite-trigger'));
        }

        target.unbind();
        target.on('click',function(){
            if(isGuest){
                if(isMobile) {
                    window.location.href = baseUrl + "/netwrk/user/login";
                }
                $('.modal').modal('hide');
                Login.initialize();
                return false;
            }
            var self = $(this),
            params = {
                'object_type': self.attr('data-object-type'),
                'object_id' : self.attr('data-object-id')
            };

            Ajax.favorite(params).then(function(data){
                var json = $.parseJSON(data);

                if(target[0].className == 'community-action pull-right un-favorite-trigger'){
                    self.closest('.community').remove();
                }else{
                    if(json.status == 'Followed')
                        target.find('.favorite-status').html('Joined');
                    else
                        target.find('.favorite-status').html('Join');
                }

                var cityId = json.data.city_id;

                if(json.status == 'Followed'){
                    Map.map.data.setStyle(function(feature) {
                        if(feature.f.type == 'visible' && feature.f.id != cityId) {
                            return /** @type {google.maps.Data.StyleOptions} */({
                                fillColor: '#ffffff',
                                fillOpacity: 0.0,
                                strokeColor: '#5888ac',
                                strokeWeight: 2
                            });
                        } else {
                            return /** @type {google.maps.Data.StyleOptions} */({
                                fillColor: '#5888ac',
                                fillOpacity: Map.fillOpacity,
                                strokeColor: '#5888ac',
                                strokeWeight: 2
                            });
                        }
                    });
                } else {
                    Map.map.data.setStyle(function(feature) {
                        if(feature.f.type == 'visible' || feature.f.id == cityId) {
                            return /** @type {google.maps.Data.StyleOptions} */({
                                fillColor: '#ffffff',
                                fillOpacity: 0.0,
                                strokeColor: '#5888ac',
                                strokeWeight: 2
                            });
                        } else {
                            return /** @type {google.maps.Data.StyleOptions} */({
                                fillColor: '#5888ac',
                                fillOpacity: Map.fillOpacity,
                                strokeColor: '#5888ac',
                                strokeWeight: 2
                            });
                        }
                    });
                }
                //reinitialize the map
                Map.initialize();
                console.log(json);
            });

        });
    },
    getFavoriteTemplate: function(parent,data){
        var json = $.parseJSON(data);

        var template = _.template($("#favorite_btn_template").html());
        var append_html = '';
        append_html = template({city: json.city, city_id: json.city_id, is_favorite: json.is_favorite});

        parent.html(append_html);

        Topic.OnClickFavorite();
    },

    OnClickCreateGroup: function() {
        var btn = $('#create_group');
        btn.unbind();
        btn.on('click',function(){
            if(isMobile){
                Common.ShowModalComeBack();
                return;
                window.location.href = baseUrl + "/netwrk/group/create-group?city="+ Topic.data.city;
            }else{
                $('#modal_topic').modal('hide');
                Create_Group.initialize(Topic.data.city,Topic.params.name,Topic.data.city_name);
            }
        });

    },

    OnClickEditGroup: function() {
        $('.edit-group').each(function() {
            $(this).unbind("click").click(function() {
                if(isMobile) {
                    window.location.href = baseUrl + "/netwrk/group/create-group?city="+ Topic.data.city +"&group_id="+$(this).data("id")+"";
                } else {
                    $('#modal_topic').modal('hide');
                    Common.HideTooTip();
                    Create_Group.initialize(Topic.data.city,Topic.params.name,Topic.data.city_name,$(this).data("id"));
                }
            });
        });
    },

    OnClickDeleteGroup: function() {
        $('.delete-group').each(function() {
            $(this).unbind("click").click(function() {
                var row = $(this).parent().parent().parent().parent();
                if (confirm("Are you sure you want to delete this group?")) {
                    Ajax.delete_group({
                        "id": $(this).data("id")
                    }).then(function(data) {
                        var json = $.parseJSON(data);
                        if (json.error) alert(json.error.message);
                        else row.remove();
                    });
                }
            });
        });
    },
    HideTabGroupHeader: function() {
        var parent = $('#modal_topic,#show-topic');
        parent.find('.tab-header-group').addClass('hidden');
    },
    getCityById: function(cityId) {
        var params = {'city_id': cityId};

        Ajax.get_city_by_id(params).then(function(data){
            var json = $.parseJSON(data);
            var lat = json.lat;
            var lng = json.lng;
            console.log(json);
            Topic.city.id = json.id;
            Topic.city.zipcode = json.zip_code;


            //set center the map using city lat and lng
            Map.SetMapCenter(lat, lng, Map.map.getZoom());
        });
    },
    getZipWeatherData: function(zipcode) {
        var parent = $('#modal_topic,#show-topic,#slider_topic').find('#tab_feed');

        zipcode = Topic.city.zipcode || '94040';
        var params = {'zip_code': zipcode, 'country': 'US'};
        //todo: fetch weather api data
        Ajax.getZipWeatherData(params).then(function(data){
            var json = $.parseJSON(data);
            console.log(json);
            Topic.getTemplateZipWeatherFeed(parent,json);
        });
    },
    getTemplateZipWeatherFeed: function(parent,data){
        var json = data;
        var target = parent.find('.top-feed .weather-feed-content');

        var list_template = _.template($("#weather-feed").html());
        var append_html = list_template({data: json});

        target.append(append_html);
    },
    /* on click of topic marker icon, display topic marker on map for that location */
    onClickTopicMapMarker: function() {
        var parent = $('#modal_topic, #show-topic,#slider_topic');
        var topicMarker = parent.find('#tab_topic').find('.topic-marker');

        topicMarker.unbind();
        topicMarker.on('click', function(){
            var lat = $(this).attr('data-lat'),
                lng = $(this).attr('data-lng'),
                city_id = $(this).attr('data-city_id');
            if(isMobile){
                setTimeout(function() {
                    sessionStorage.show_landing = 1;
                    sessionStorage.topic_lat = lat;
                    sessionStorage.topic_lng = lng;
                    sessionStorage.topic_city_id = city_id;
                    sessionStorage.is_topic_marker_in_map_center = 1;

                    window.location.href = baseUrl + "/netwrk/default/home";
                }, 500);
            }
            Map.showTopicMarker(lat, lng, city_id);
            parent.modal('hide');
        });
    }

};
