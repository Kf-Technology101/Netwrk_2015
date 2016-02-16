var Topic_Group = {
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
    params:{
        zipcode:'',
        name:'',
        lat: '',
        lng:''
    },
    modal: '#modal_topic_group',
    modal_create: '#create_topic_group',
    tab_current: 'topic',
    init: function(){
        Topic_Group._onclickBack();
        Topic_Group.GetDataOnTab();
        Topic_Group.load_topic();
        Topic_Group.get_data_new_netwrk();
        Topic_Group.RedirectPostList();
        Topic_Group.scroll_bot();
        Topic_Group.OnClickSortBtn();
        Topic_Group.OnClickSelectFilter();
        Topic_Group.OnClickChangeTab();
        Topic_Group.eventClickMeetMobile();
        Topic_Group.OnClickCreateGroup();
    },

    initialize: function(city,params){
        if (isMobile) {
            Topic_Group.show_page_topic(city,params);
        }else {
            Topic_Group.OnShowModalPost();
            Topic_Group.close_modal();
            Topic_Group.show_modal_topic(city,params);
            Topic_Group._onclickBack();
            Topic_Group.OnClickBackdrop();
            Topic_Group.onNetwrkLogo();
            Topic_Group.OnClickCreateGroup();
        }
    },

    CustomScrollBar: function(){
        var parent;

        parent = $("#modal_topic_group").find('.modal-body');

        parent.mCustomScrollbar({
            theme:"dark",
            callbacks:{
                onTotalScroll: function(){
                    if (Topic_Group.list[Topic_Group.data.filter].status_paging == 1){
                        Topic_Group.load_topic_more();
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

        btn.on('click',function(e){
            btn.removeClass('active');
            parent.find('.tab').hide();
            Topic_Group.tab_current = $(e.currentTarget).attr('class');
            $(e.currentTarget).addClass('active');
            Topic_Group.GetDataOnTab();
        });
    },

    GetDataOnTab: function(){
        switch(Topic_Group.tab_current) {
            case 'feed':
                Topic_Group.ShowFeedPage();
                break;
            case 'topic':
                Topic_Group.ShowTopic_GroupPage();
                break;
            case 'groups':
                Topic_Group.ShowGroupsPage();
                break;
        }
    },

    ShowTopic_GroupPage: function(){
        var parent = $('#modal_topic,#show-topic');
        parent.find('#tab_topic').show();

        if(isMobile){
            parent.find('span.filter').removeClass('visible');
        }else{
            parent.find('.dropdown').removeClass('visible');
        }

        $('.create_topic').hide();
        $('#create_topic').show();
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

        $('.create_topic').hide();
        $('#create_topic').show();
    },

    ShowGroupsPage: function() {
        console.log("groups page");
        var parent = $('#modal_topic,#show-topic');
        parent.find('#tab_groups').show();
        parent.find('.dropdown').addClass('visible');
        $('.create_topic').hide();
        $('#create_group').show();
        parent.find('.dropdown').addClass('visible');
    },

    OnClickSortBtn: function(){
        var btn_parent = $('#show-topic').find('.sidebar .filter');

        btn_parent.unbind();
        btn_parent.on('click',function(){
            btn_parent.toggleClass('active');
            $('#show-topic').find('.filter_sort').toggleClass('active');
            $('#show-topic').find('.container').toggleClass('open');

            if($('#show-topic').find('.filter_sort').hasClass('active')){
                Topic_Group.filter_topic($(window));
            }
        });
    },
    RedirectPostList: function(){
        var parent = $('#item_list_'+Topic_Group.data.filter);
        parent.find('.item').unbind();
        parent.find('.item').on('click',function(e){
            var topic = $(e.currentTarget).attr('data-item');
            Ajax.update_view_topic({topic: topic}).then(function(){
                if(isMobile){
                    Post.RedirectPostPage(topic);
                }else{
                    $('#modal_topic').modal('hide');
                    Post.params.topic = topic;
                    Post.params.topic_name = $(e.currentTarget).find('.name_topic p').text();
                    Post.params.city = Topic_Group.data.city;
                    Post.params.city_name = Topic_Group.data.city_name;
                    Post.initialize();
                }
            });
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
            Topic_Group.params.zipcode = parent.attr('data-zipcode');
            Topic_Group.params.lat = parent.attr('data-lat');
            Topic_Group.params.lng = parent.attr('data-lng');
            Topic_Group.params.name = parent.attr('data-name');
        }
    },

    create_post: function(){
        var btn;
        if(isMobile){
            btn = $('#show-topic').find('.item .num_count');
            btn.unbind();
            btn.on('click',function(e){
                var topic_id = $(e.currentTarget).parents('.item').eq(0).attr('data-item');
                window.location.href = baseUrl + "/netwrk/post/create-post?city="+ Topic_Group.data.city +"&topic="+topic_id;
            });
        }else{
            btn = $('#modal_topic').find('.item .num_count');
            btn.unbind();
            btn.on('click',function(e){
                var target = $(e.currentTarget).parents('.item').eq(0),
                    topic_id = target.attr('data-item');
                    toptic_name = target.find('.name_topic p').text();
                $('#modal_topic').modal('hide');
                // Topic_Group.reset_modal();
                Create_Post.initialize(Topic_Group.data.city,topic_id,Topic_Group.data.city_name,toptic_name);
            });
        }
    },

    create_topic: function(){
        var btn;
        if(isMobile){
            btn = $('#show-topic').find('.create_topic');
            btn.unbind();
            btn.on('click',function(){
                if(Topic_Group.params.zipcode){
                    window.location.href = baseUrl + "/netwrk/topic/create-topic?city="+Topic_Group.data.city+"&zipcode="+Topic_Group.params.zipcode+"&name="+Topic_Group.params.name+"&lat="+Topic_Group.params.lat+"&lng="+Topic_Group.params.lng;
                }else{
                    window.location.href = baseUrl + "/netwrk/topic/create-topic?city="+Topic_Group.data.city;
                }
            });
        }else{
            btn = $('#modal_topic').find('.create_topic');
            btn.unbind();
            btn.on('click',function(){
                $('#modal_topic').modal('hide');
                Create_Topic_Group.initialize(Topic_Group.data.city,Topic_Group.data.city_name);
            });
        }
    },

    scroll_bot: function(){
        var self = this;
        var containt = $('.containt');
        if (isMobile) {
            $(window).scroll(function() {
                if( $(window).scrollTop() + $(window).height() == $(document).height() && self.list[self.data.filter].status_paging == 1 ) {
                    setTimeout(function(){
                        self.load_topic_more();
                    },300);
                }
            });
        }else{
            containt.scroll(function(e){
                var parent = $('#item_list_'+self.data.filter);
                var  hp = parent.height() + 20;
                if(containt.scrollTop() + containt.height() == hp && self.list[self.data.filter].status_paging == 1){
                    self.list[self.data.filter].status_paging = 0;
                    setTimeout(function(){
                        self.load_topic_more();
                    },300);
                }
            });
        }
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
        set_heigth_modal($('#modal_topic'),0);
        $('#modal_topic').modal({
            backdrop: true,
            keyboard: false
        });
    },


    OnShowModalPost: function(){
        $('#modal_topic').unbind('shown.bs.modal');
        $('#modal_topic').on('shown.bs.modal',function(e) {
            Topic_Group.load_topic_modal();
            Topic_Group.OnClickChangeTab();
            Group.LoadGroupModal();
        });
    },

    load_topic_modal: function(){
        var self = this;
        var parent = $('#item_list_'+self.data.filter);
        var cityname = $('#modal_topic').find('.title_page');
        var params = {'city': self.data.city,'zipcode': self.data.zipcode, 'filter': self.data.filter,'size': self.data.size,'page':1};

        parent.show();
        // sidebar.show();
        console.log("load topic");
        Ajax.show_topic(params).then(function(data){
            parent.scrollTop(0);
            self.list[self.data.filter].loaded = self.list[self.data.filter].paging ;
            self.getTemplate(parent,data);
            self.getTemplateModal(cityname,data);
            Topic_Group.CustomScrollBar();
            Topic_Group.filter_topic(parent);
            Topic_Group.GetDataOnTab();
        });
    },

    close_modal: function(){
        $('#modal_topic').unbind('hidden.bs.modal');
        $('#modal_topic').on('hidden.bs.modal',function(e) {
            $(e.currentTarget).unbind();
            Topic_Group.reset_modal();
            Map.get_data_marker();
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
        $(target[1]).addClass('active');

        Topic_Group.tab_current ='topic';
    },

    show_page_topic: function(city,params){
        if (params){
            window.location.href = "netwrk/topic/topic-page?city="+city+"&zipcode="+params.zipcode+"&name="+params.name+"&lat="+params.lat+"&lng="+params.lng;
        }else{
            window.location.href = "netwrk/topic/topic-page?city="+city;
        }
    },

    _onclickBack: function(){
        if(isMobile){
            $('#show-topic .back_page span, .box-navigation .btn_nav_map').unbind('click').click(function(){
                window.location.href = baseUrl;
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
        if($('#Topic_Group').attr('data-action') == 'topic-page'){
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
        Topic_Group.create_topic();
        Topic_Group.RedirectPostList();
        // this.create_post();
    },

    getTemplateModal: function(parent,data){
        var self = this;
        var json = $.parseJSON(data);
        var list_template = _.template($( "#city_name" ).html());
        var append_html = list_template({city: json.city});
        Topic_Group.data.city_name = json.city;
        parent.append(append_html);
    },

    eventClickMeetMobile: function(){
        var target = $('#btn_meet_mobile');
        target.unbind();
        target.on('click',function(){
            target.bind();
            window.location.href = baseUrl + "/netwrk/meet";
        });
    },

    OnClickCreateGroup: function(){
        var btn = $('#create_group');
        btn.unbind();
        btn.on('click',function(){
            if(isMobile){
                window.location.href = baseUrl + "/netwrk/post/create-post?city="+ Post.params.city +"&topic="+Post.params.topic;
            }else{
                $('#modal_topic').modal('hide');
                Create_Group.initialize(Topic_Group.data.city,Topic_Group.params.name,Topic_Group.data.city_name);
            }
        });

    },

    OnClickEditGroup: function() {
        console.log("click");
        $('.edit-group').each(function() {
            $(this).unbind("click").click(function() {
                $('#modal_topic').modal('hide');
                Create_Group.initialize(Topic_Group.data.city,Topic_Group.params.name,Topic_Group.data.city_name,$(this).data("id"));
            });
        });
    }
};
