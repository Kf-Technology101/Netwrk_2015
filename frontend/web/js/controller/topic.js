var Topic = {
    data:{
        filter: 'recent',
        city: '',
        size: 30,
        city_name:'',
        zipcode:''

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
        lng:'',
    },
    modal: '#modal_topic',
    modal_create: '#create_topic',
    tab_current: 'feed',
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
        if(isMobile){
            Default.ShowNotificationOnChat();
            LandingPage.FixWidthPostLanding();
        } else {
            Topic.displayPositionModal();
        }
        Topic.CheckTabCurrent();
    },

    CheckTabCurrent: function(){
        if(sessionStorage.topic_tab_current == 'feed'){
            var parent = $('#modal_topic,#show-topic');
            var btn = parent.find('.filter_sidebar td.feed');
            btn.trigger('click');
            sessionStorage.topic_tab_current = 'topic';
        }
    },

    initialize: function(city,params){
        if (isMobile) {
            Topic.show_page_topic(city,params);
        }else {
            Topic.OnShowModalPost();
            Topic.close_modal();
            Topic.show_modal_topic(city,params);
            Topic._onclickBack();
            Topic.OnClickBackdrop();
            Topic.onNetwrkLogo();
            Topic.CheckTabCurrent();
            Default.onCLickModal();
        }
    },

    CustomScrollBar: function(){
        var parent;

        parent = $("#modal_topic").find('.modal-body');

        parent.mCustomScrollbar({
            theme:"dark",
            callbacks:{
                onTotalScroll: function(){
                    if (Topic.list[Topic.data.filter].status_paging == 1 && Topic.tab_current == "topic"){
                        Topic.load_topic_more();
                    }else if(Topic.feed.status_paging == 1 &&Topic.tab_current == "feed"){
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
                Topic.tab_current = 'topic';
                break;
        }
    },

    ShowTopicPage: function(){
        var parent = $('#modal_topic,#show-topic');
        parent.find('#tab_topic').show();

        if(isMobile){
            parent.find('span.filter').removeClass('visible');
        }else{
            parent.find('.dropdown').removeClass('visible');
        }

        //enable btn create topic
        parent.find('.header .title_page').removeClass('on-feed');
        parent.find('.header .create_topic').removeClass('on-feed');
    },

    ShowFeedPage: function(){
        var parent = $('#modal_topic,#show-topic');
        if(isMobile){
            parent.find('span.filter').addClass('visible');
            parent.find('span.filter').removeClass('active');
            parent.find('.filter_sort').removeClass('active');
        }else{
            parent.find('.dropdown').addClass('visible');
        }
        parent.find('#tab_feed').show();
        parent.find('.filter').addClass('visible');
        parent.find('.container').removeClass('open');

        //disable btn create topic
        parent.find('.header .title_page').addClass('on-feed');
        parent.find('.header .create_topic').addClass('on-feed');
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
    RedirectPostList: function(){
        var parent = $('#item_list_'+Topic.data.filter);
        parent.find('.item').unbind();
        parent.find('.item').on('click',function(e){
            var topic = $(e.currentTarget).attr('data-item');
            if(isMobile){
                Post.RedirectPostPage(topic);
            }else{
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
            console.log('click backdrop');
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
        console.log(new_params);

        if(new_params){
            self.data.zipcode = new_params.zipcode;
            self.data.name = new_params.name;
        }
        if(city){
            self.data.city = city;
        }

        var params = {'city': self.data.city,'zipcode': self.data.zipcode, 'name': self.data.name, 'filter': self.data.filter,'size': self.data.size,'page':1};

        parent.show();
        set_heigth_modal($('#modal_topic'),0);
        $('#modal_topic').modal({
            backdrop: true,
            keyboard: false
        });
    },

    OnShowModalPost: function(){
        $('#modal_topic').unbind('shown.bs.modal');
        $('#modal_topic').on('shown.bs.modal',function(e) {
            Topic.LoadFeedModal();
            Topic.load_topic_modal();
            Topic.OnClickChangeTab();
            Topic.displayPositionModal();
        });
    },

    LoadFeedModal: function() {
        var self = this;
        var parent = $('#modal_topic,#show-topic').find('#tab_feed');
        var cityname = $('#modal_topic').find('.title_page');
        var params = {'city': self.data.city,'zipcode': self.data.zipcode, 'filter': self.data.filter,'size': self.data.size,'page':Topic.feed.paging};
        parent.show();
        Ajax.show_feed(params).then(function(data){
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
        });
    },

    LoadMoreFeed: function(){
        var params = {'city': Topic.data.city,'zipcode': Topic.data.zipcode, 'filter': Topic.data.filter,'size': Topic.data.size,'page':Topic.feed.paging};
        var parent = $('#modal_topic,#show-topic').find('#tab_feed');
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
        if(json.feed.length > 0){
            parent.find('.no-data').hide();
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
        var target = $('#modal_topic,#show-topic').find('.top-post .action .chat');
        target.unbind();
        target.on('click',function(e){
                var post_id = $(e.currentTarget).parent().parent().attr('data-value'),
                    post_name = $(e.currentTarget).parent().parent().find('.post-title').text(),
                    post_content = $(e.currentTarget).parent().parent().find('.post-content').text();
            if(isMobile){
                sessionStorage.url = window.location.href;
                sessionStorage.feed_topic = 1;
                PopupChat.RedirectChatPostPage(post_id, 1, 1);
            }else{
                PopupChat.params.post = post_id;
                PopupChat.params.chat_type = 1;
                PopupChat.params.post_name = post_name;
                PopupChat.params.post_description = post_content;
                PopupChat.initialize();
            }
        });
    },

    OnClickPostFeed: function(){
        var target = $('#modal_topic,#show-topic').find('.top-post .post, .feed-row.feed-post .feed-content');
        target.unbind();
        target.on('click',function(e){
            console.log($(e.currentTarget));
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
                PopupChat.initialize();
            }
        });
    },

    OnClickVoteFeed: function() {
        var target = $('#modal_topic,#show-topic').find('.top-post .action .brilliant');
        target.unbind();
        target.on('click',function(e){
            var post_id = $(e.currentTarget).parent().parent().attr('data-value');
            Ajax.vote_post({post_id: post_id}).then(function(res){
                var json = $.parseJSON(res);
                console.log($(e.currentTarget));
                $(e.currentTarget).text(json.data);
            });
        });
    },

    OnClickTopicFeed: function(){
        var target = $('#modal_topic,#show-topic').find('.topic-row, .feed-row.feed-topic');

        target.unbind();
        target.on('click',function(e){
            console.log($(e.currentTarget));
            var city_id = $(e.currentTarget).attr('data-city'),
                city_name = $(e.currentTarget).attr('data-city-name'),
                topic_id = $(e.currentTarget).attr('data-value'),
                topic_name = $(e.currentTarget).find('.topic-title').text();
            if(isMobile){
                Post.RedirectPostPage(topic_id);
            }else{
                $(Topic.modal).modal('hide');
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
        var cityname = $('#modal_topic').find('.title_page');
        var params = {'city': self.data.city,'zipcode': self.data.zipcode, 'filter': self.data.filter,'size': self.data.size,'page':1};

        parent.show();
        // sidebar.show();
        Ajax.show_topic(params).then(function(data){
            parent.scrollTop(0);
            self.list[self.data.filter].loaded = self.list[self.data.filter].paging ;
            self.getTemplate(parent,data);
            self.getTemplateModal(cityname,data);
            Topic.CustomScrollBar();
            Topic.filter_topic(parent);
            Topic.GetDataOnTab();
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
        var target = $('#modal_topic .filter_sidebar').find('td');
        var filter = ['post','view','recent'];
        var selecFilter = $('#modal_topic').find('.dropdown-menu li').first().text();

        self.data.filter = 'recent';
        parent.hide();
        $('#modal_topic').find('.dropdown-toggle').text(selecFilter);
        $('#modal_topic').find('.title_page').empty();
        $('#modal_topic').find('.tab').hide();
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

        Topic.ResetModalTabFeed();
    },

    ResetModalTabFeed: function(){
        var parent = $('#modal_topic').find('#tab_feed');
        parent.find('.top-post,.top-topic,.top-feed').remove();
        parent.find('.no-data').show();
        Topic.tab_current = 'feed';
        Topic.feed.paging = 1;
        Topic.feed.status_paging = 1;
    },

    show_page_topic: function(city,params){
        if (params){
            window.location.href = baseUrl + "/netwrk/topic/topic-page?city="+city+"&zipcode="+params.zipcode+"&name="+params.name+"&lat="+params.lat+"&lng="+params.lng;
        }else{
            window.location.href = baseUrl + "/netwrk/topic/topic-page?city="+city;
        }
    },

    _onclickBack: function(){
        if(isMobile){
            $('#show-topic .back_page span').click(function(){
                sessionStorage.show_landing = 1;
                if($('.back_page').hasClass('back_help')){
                    window.location.href = baseUrl + "/netwrk/default/home?current=help";
                } else {
                    window.location.href = baseUrl + "/netwrk/default/home";
                }
            })
        }else{
            $('#modal_topic .back_page span').click(function(){
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
                });
            }
        }
    },

    load_topic_more: function(){
        var self = this;
        self.list[self.data.filter].paging ++ ;
        var params = {'city': self.data.city, 'filter': self.data.filter,'size': self.data.size,'page':self.list[self.data.filter].paging};
        Ajax.show_topic(params).then(function(data){
            var parent = $('#item_list_'+self.data.filter);
            self.getTemplate(parent,data);
        });
    },

    filter_topic: function(contain){
        console.log('filter_topic');
        var target = $('#modal_topic,#show-topic').find('.dropdown-menu li');
        var self = this;

        target.unbind();
        target.on('click',function(e){
            var filter = $(e.currentTarget).attr('data-value');
            var name = $(e.currentTarget).text();
            $("#modal_topic,#show-topic").find('.dropdown-toggle').text(name);
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
        // this.create_post();
    },

    getTemplateModal: function(parent,data){
        var self = this;
        var json = $.parseJSON(data);
        var list_template = _.template($( "#city_name" ).html());
        var append_html = '';
        if (Topic.data.name) {
            append_html = list_template({city: Topic.data.name});
        } else
            append_html = list_template({city: json.city});
        parent.html(append_html);
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
    }
};
