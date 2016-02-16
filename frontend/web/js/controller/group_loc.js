var Group_Loc = {
    data:{
        filter: 'recent',
        filter_topic: 'recent',
        filter_post: 'recent',
        group_id: '',
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
    post_params: {
        group_back_name: '',
        group_back: ''
    },
    modal: '#modal_group',
    modal_create: '#create_topic',
    tab_current: 'topic',
    init: function() {
        Group_Loc._onclickBack();
        Group_Loc.GetDataOnTab();
        Group_Loc.load_topic();
        Group_Loc.get_data_new_netwrk();
        Group_Loc.RedirectPostList();
        Group_Loc.scroll_bot();
        Group_Loc.OnClickSortBtn();
        Group_Loc.OnClickSelectFilter();
        Group_Loc.OnClickChangeTab();
        Group_Loc.eventClickMeetMobile();
        Group_Loc.OnClickCreateGroup();
    },

    initialize: function(group_id,params){
        console.log(group_id);
        if (isMobile) {
            Group_Loc.show_page_topic(group_id,params);
        }else {
            Group_Loc.OnShowModalPost();
            Group_Loc.close_modal();
            Group_Loc.show_modal_topic(group_id,params);
            Group_Loc._onclickBack();
            Group_Loc.OnClickBackdrop();
            Group_Loc.onNetwrkLogo();
            Group_Loc.OnClickCreateGroup();
            Group_Loc.CreateTopicLoc();
            Group_Loc.CreatePostLoc();
            Group_Loc.TotalUsers();
        }
    },

    CustomScrollBar: function(){
        var parent;

        parent = $("#modal_group").find('.modal-body');

        parent.mCustomScrollbar({
            theme:"dark",
            callbacks:{
                onTotalScroll: function(){
                    if (Group_Loc.list[Group_Loc.data.filter].status_paging == 1){
                        Group_Loc.load_topic_more();
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
        var parent = $('#modal_group,#show-topic');
        var btn = parent.find('.filter_sidebar td');

        btn.on('click',function(e){
            btn.removeClass('active');
            parent.find('.tab').hide();
            Group_Loc.tab_current = $(e.currentTarget).attr('class');
            $(e.currentTarget).addClass('active');
            Group_Loc.GetDataOnTab();
        });
    },

    onclickBackFromTopics: function(){
        if (isMobile) {
            $('#modal_group .back_page span, .box-navigation .btn_nav_map').unbind("click").click(function() {
                window.location.href = baseUrl;
            });
        } else {
            $('#modal_group .back_page span, .box-navigation .btn_nav_map').unbind("click").click(function() {
                //show again list of groups
                $('#modal_group').hide();
                //Group_Loc.LoadGroupModal();
            });
        }
    },

    onclickBackFromPosts: function(){
        if (isMobile) {
            $('#modal_group .back_page span, .box-navigation .btn_nav_map').unbind("click").click(function() {
                window.location.href = baseUrl;
            });
        } else {
            $('#modal_group .back_page span, .box-navigation .btn_nav_map').unbind("click").click(function() {
                console.log("back posts");
                var parent = $('#item_topic_group_loc_list_' + Group.data.filter);
                Group_Loc.ShowTopics(parent, Group_Loc.post_params.group_back, Group_Loc.post_params.group_back_name);
            });
        }
    },

    GetDataOnTab: function(){
        //switch(Group_Loc.tab_current) {
        //    case 'groups':
                Group_Loc.ShowGroupsPage();
        //        break;
        //}
    },

    ShowTopicPage: function(){
        var parent = $('#modal_group,#show-topic');
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
        var parent = $('#modal_group,#show-topic');
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
        var parent = $('#modal_group'); //',#show-topic');
        parent.find('#tab_groups_loc').show();
        //parent.find('.dropdown').addClass('visible');
        //$('#create_topic_loc').hide();
        //$('#create_group_loc').show();
        //parent.find('.dropdown').addClass('visible');
    },

    OnClickSortBtn: function(){
        var btn_parent = $('#show-topic').find('.sidebar .filter');

        btn_parent.unbind();
        btn_parent.on('click',function(){
            btn_parent.toggleClass('active');
            $('#show-topic').find('.filter_sort').toggleClass('active');
            $('#show-topic').find('.container').toggleClass('open');

            if($('#show-topic').find('.filter_sort').hasClass('active')){
                Group_Loc.filter_topic($(window));
            }
        });
    },
    RedirectPostList: function() {
        var parent = $('#item_topic_group_loc_list_'+Group_Loc.data.filter);
        parent.find('.item').unbind();
        parent.find('.item').on('click',function(e){
            var topic = $(e.currentTarget).attr('data-item');
            Ajax.update_view_topic({topic: topic}).then(function(){
                if(isMobile){
                    Post.RedirectPostPage(topic);
                }else{
                    $('#modal_group').modal('hide');
                    Post.params.topic = topic;
                    Post.params.topic_name = $(e.currentTarget).find('.name_topic p').text();
                    Post.params.city = Group_Loc.data.city;
                    Post.params.city_name = Group_Loc.data.city_name;
                    Post.params.by_group = true;
                    Post.params.group_id = Group_Loc.data.id;
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
            $('#modal_group').modal('hide');
        });
    },

    get_data_new_netwrk: function(){
        var parent = $('#show-topic');

        if (parent.attr('data-zipcode')){
            Group_Loc.params.zipcode = parent.attr('data-zipcode');
            Group_Loc.params.lat = parent.attr('data-lat');
            Group_Loc.params.lng = parent.attr('data-lng');
            Group_Loc.params.name = parent.attr('data-name');
        }
    },

    create_post: function(){
        var btn;
        if(isMobile){
            btn = $('#show-topic').find('.item .num_count');
            btn.unbind();
            btn.on('click',function(e){
                var topic_id = $(e.currentTarget).parents('.item').eq(0).attr('data-item');
                window.location.href = baseUrl + "/netwrk/post/create-post?city="+ Group_Loc.data.city +"&topic="+topic_id;
            });
        }else{
            btn = $('#modal_group').find('.item .num_count');
            btn.unbind();
            btn.on('click',function(e){
                var target = $(e.currentTarget).parents('.item').eq(0),
                    topic_id = target.attr('data-item');
                    toptic_name = target.find('.name_topic p').text();
                $('#modal_group').modal('hide');
                // Group_Loc.reset_modal();
                Create_Post.initialize(Group_Loc.data.city,topic_id,Group_Loc.data.city_name,toptic_name);
            });
        }
    },

    create_topic: function(){
        var btn;
        if(isMobile){
            btn = $('#show-topic').find('.create_topic');
            btn.unbind();
            btn.on('click',function(){
                if(Group_Loc.params.zipcode){
                    window.location.href = baseUrl + "/netwrk/topic/create-topic?city="+Group_Loc.data.city+"&zipcode="+Group_Loc.params.zipcode+"&name="+Group_Loc.params.name+"&lat="+Group_Loc.params.lat+"&lng="+Group_Loc.params.lng;
                }else{
                    window.location.href = baseUrl + "/netwrk/topic/create-topic?city="+Group_Loc.data.city;
                }
            });
        }else{
            btn = $('#modal_group').find('.create_topic');
            btn.unbind();
            btn.on('click',function(){
                $('#modal_group').modal('hide');
                Create_Group.initialize(Group_Loc.data.city,Group_Loc.data.city_name);
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


    show_modal_topic: function(group_id,new_params) {
        var self = this;
        var parent = $('#item_list_'+self.data.filter);
        var sidebar = $('.map_content .sidebar');

        if (new_params) {
            self.data.zipcode = new_params.zipcode;
        }
        if (group_id) {
            self.data.group_id = group_id;
        }

        var params = {'group_id': self.data.group_id, 'filter': self.data.filter,'size': self.data.size,'page':1};

        parent.show();
        set_heigth_modal($('#modal_group'),0);
        $('#modal_group').modal({
            backdrop: true,
            keyboard: false
        });
    },


    OnShowModalPost: function(){
        $('#modal_group').unbind('shown.bs.modal').on('shown.bs.modal',function(e) {
            //Group_Loc.load_topic_modal();
            //Group_Loc.OnClickChangeTab();
            //Group_Loc.LoadGroupModal();
            $('#create_topic_loc').show();
            $('#create_group_loc').hide();
            var group = Group_Loc.data.group_id;
            var groupName = ""; //$(e.currentTarget).find('.name_group').text();
            var parent = $('#item_topic_group_loc_list_' + Group_Loc.data.filter);
            Group_Loc.ShowTopics(parent, group, groupName);
        });
    },

    LoadGroupModal: function(){
        var self = this;
        var parent = $('#item_group_loc_list_'+self.data.filter);
        //var cityname = $('#modal_topic').find('.title_page');
        var params = {'group_id': self.data.group_id,'filter': self.data.filter,'size': self.data.size,'page':1};

        parent.show();
        // sidebar.show();
        Ajax.show_groups(params).then(function(data){
            var json = $.parseJSON(data);
            console.log("deleting old");
            $("div[id^='item_group_loc_list'] .item").remove();
            if (json.data.length > 0) {
                parent.scrollTop(0);
                self.list[self.data.filter].loaded = self.list[self.data.filter].paging;
                Group_Loc.getTemplate(parent, json);
                //self.getTemplateModal(cityname, json);
                Topic.CustomScrollBar();
                //Topic.filter_topic(parent);
                Group_Loc.GetDataOnTab();

                Group_Loc.LoadGroupTopics();
            }
        });
    },

    LoadGroupTopics: function() {
        var parent = $('#item_group_loc_list_'+Group_Loc.data.filter);
        parent.find('.item').unbind();
        parent.find('.item').on('click',function(e) {
            var group = $(e.currentTarget).data('item');
            var groupName = $(e.currentTarget).find('.name_group').text();
            var parent = $('#item_topic_group_loc_list_' + Group_Loc.data.filter);
            Group_Loc.ShowTopics(parent, group, groupName);
        });
    },

    ShowTopics: function(parent, group, groupName) {

        if (typeof group == "undefined") group = Group_Loc.data.id;
        else Group_Loc.data.id = group;

        if (typeof groupName == "undefined") groupName = Group_Loc.data.name;
        else Group_Loc.data.name = groupName;

        var params = {
            //'city': self.data.city,
            //'zipcode': self.data.zipcode,
            'filter': Group_Loc.data.filter_topic,
            'size': Group_Loc.data.size,
            'page': 1,
            'group': group
        };

        parent.show();
        // sidebar.show();
        Ajax.show_topic(params).then(function (data) {
            var json = $.parseJSON(data);
            console.log("deleting old");
            $("div[id^='item_topic_group_loc_list'] .item").remove();
            $('#item_total_users_loc').hide();
            //if (json.data.length > 0) {
            parent.scrollTop(0);
            Group_Loc.list[Group.data.filter].loaded = Group.list[Group_Loc.data.filter].paging;
            Group_Loc.getTemplateTopicGroup(parent, json);
            //self.getTemplateModal(cityname, data);
            //Topic.CustomScrollBar();
            Group_Loc.filter_topic(parent);
            Group_Loc.GetDataOnTab();
            Group_Loc.post_params.group_back = group;
            Group_Loc.post_params.group_back_name = groupName;
            $('.topic_group_loc_name span').html(groupName);
            //}
        });
    },

    load_topic_modal: function(){
        var self = this;
        var parent = $('#item_list_'+self.data.filter);
        var cityname = $('#modal_group').find('.title_page');
        var params = {'group_id': self.data.group_id,'zipcode': self.data.zipcode, 'filter': self.data.filter,'size': self.data.size,'page':1};

        parent.show();
        // sidebar.show();
        console.log("load topic");
        Ajax.show_groups(params).then(function(data){
            parent.scrollTop(0);
            self.list[self.data.filter].loaded = self.list[self.data.filter].paging ;
            self.getTemplate(parent,data);
            //self.getTemplateModal(cityname,data);
            Group_Loc.CustomScrollBar();
            Group_Loc.filter_topic(parent);
            Group_Loc.GetDataOnTab();
        });
    },

    close_modal: function(){
        $('#modal_group').unbind('hidden.bs.modal');
        $('#modal_group').on('hidden.bs.modal',function(e) {
            $(e.currentTarget).unbind();
            Group_Loc.reset_modal();
            Map.get_data_marker();
        });
    },

    reset_modal: function(){
        var self = this;
        var parent = $("div[id^='item_list']");
        var target = $('#modal_group .filter_sidebar').find('td');
        var filter = ['post','view','recent'];
        var selecFilter = $('#modal_group').find('.dropdown-menu li').first().text();

        self.data.filter = 'recent';
        parent.hide();
        $('#modal_group').find('.dropdown-toggle').text(selecFilter);
        $('#modal_group').find('.title_page').empty();
        $('#modal_group').find('.tab').hide();
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

        Group_Loc.tab_current ='topic';
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
            $('#show-topic .back_page span, .box-navigation .btn_nav_map').click(function(){
                window.location.href = baseUrl;
            })
        }else{
            $('#modal_group .back_page span, .box-navigation .btn_nav_map').click(function(){
                console.log("back"); return;
                $('#modal_group').modal('hide');
            })
        }
    },

    onNetwrkLogo: function(){
        $('#modal_group .title_page .title a').click(function(){
            $('#modal_group').modal('hide');
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

    getTemplate: function(parent,json){
        var self = this;
        var list_template = _.template($("#group_list").html());
        var append_html = list_template({groups: json.data});

        parent.append(append_html);
        self.onTemplate(json);

        //Topic.OnClickEditGroup();
        //Topic.OnClickDeleteGroup();
    },

    onTemplate: function(json){
        var self = this;

        console.log("data: ", json.data);

        if(json.data.length == 0){
            $('#item_group_loc_list_'+self.data.filter).find('.no-data').show();
            self.list[self.data.filter].status_paging = 0;
        }else if(json.data.length < self.data.size && json.data.length > 0){
            $('#item_group_loc_list_'+self.data.filter).find('.no-data').hide();
            self.list[self.data.filter].status_paging = 0;
        }else{
            $('#item_group_loc_list_'+self.data.filter).find('.no-data').hide();
            self.list[self.data.filter].status_paging = 1;
        }
        $("div[id^='item_topic_group_loc_list']").hide();
        $("div[id^='group_loc_topic_post_filter']").hide();
        $('.topic_group_loc_top').hide();
        //Topic.create_topic();
        //Topic.RedirectPostList();
        // this.create_post();
    },

    getTemplateModal: function(parent,data){
        var self = this;
        var json = $.parseJSON(data);
        var list_template = _.template($( "#city_name" ).html());
        var append_html = list_template({city: json.city});
        Group_Loc.data.city_name = json.city;
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
        var btn = $('#create_group_loc');
        btn.unbind();
        btn.on('click',function(){
            if(isMobile){
                window.location.href = baseUrl + "/netwrk/post/create-post?city="+ Post.params.city +"&topic="+Post.params.topic;
            }else{
                $('#modal_group').modal('hide');
                Create_Group.initialize(Group_Loc.data.city,Group_Loc.params.name,Group_Loc.data.city_name,true);
            }
        });
    },

    OnClickEditGroup: function() {
        $('.edit-group').each(function() {
            $(this).unbind("click").click(function() {
                $('#modal_group').modal('hide');
                Create_Group.initialize(Group_Loc.data.city,Group_Loc.params.name,Group_Loc.data.city_name,$(this).data("id"));
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

    getTemplateTopicGroup: function(parent,json){
        var self = this;
        var list_template = _.template($("#topic_group_list").html());
        var append_html = list_template({topices: json.data});

        parent.append(append_html);
        self.onTemplateTopicGroup(json);

        //Topic.OnClickEditGroup();
    },

    onTemplateTopicGroup: function(json){
        var self = this;

        if(json.data.length == 0){
            $('#item_topic_group_loc_list_'+self.data.filter).find('.no-data').show();
            self.list[self.data.filter].status_paging = 0;
        }else if(json.data.length < self.data.size && json.data.length > 0){
            $('#item_topic_group_loc_list_'+self.data.filter).find('.no-data').hide();
            self.list[self.data.filter].status_paging = 0;
        }else{
            $('#item_topic_group_loc_list_'+self.data.filter).find('.no-data').hide();
            self.list[self.data.filter].status_paging = 1;
        }
        $("div[id^='item_group_loc_list']").hide();
        $("div[id^='group_loc_topic_post_filter']").hide();
        $('.topic_group_loc_top').show();
        $('#btn-create-topic-loc').show();
        $('#btn-create-post-loc').hide();
        //Topic.create_topic();
        Group_Loc.RedirectPostList();
        Group_Loc.onclickBackFromTopics();
        // this.create_post();
    },

    CreateTopicLoc: function() {
        $('#btn-create-topic-loc').eq(0).click(function() {
            $('#modal_group').modal('hide');
            Create_Topic.initialize(null, null, true, Group_Loc.data.id, "", true);
        });
    },

    CreatePostLoc: function() {
        $('#btn-create-post-loc').eq(0).click(function() {
            $('#modal_group').modal('hide');
            Create_Post.initialize(null, Group_Loc.post_params.topic, null, Group_Loc.post_params.topic_name, Group_Loc.data.id);
        });
    },

    TotalUsers: function() {
        var parent = $('#item_total_users_loc');
        $('.topic_group_loc_name button').eq(0).click(function() {
            Ajax.get_users(Group.data.id).then(function(data) {
                var json = $.parseJSON(data);
                $("div[id^='item_topic_group_loc_list']").hide();
                $("div[id^='group_loc_topic_post_filter']").hide();
                $('.topic_group_loc_top').hide();
                $('#item_total_users_loc').show().find('.no-data').hide();
                Group.getTemplateTotalUsers(parent, json);
                Group_Loc.onclickBackFromUsers();
            });
        });
    },

    onclickBackFromUsers: function() {
        if (isMobile) {
            $('#modal_group .back_page span, .box-navigation .btn_nav_map').unbind("click").click(function() {
                window.location.href = baseUrl;
            });
        } else {
            $('#modal_group .back_page span, .box-navigation .btn_nav_map').unbind("click").click(function() {
                var parent = $('#item_topic_group_loc_list_' + Group_Loc.data.filter);
                Group_Loc.ShowTopics(parent, Group_Loc.data.id, Group_Loc.data.name);
            });
        }
    },

    filter_topic: function(contain) {
        console.log('filter_topic');
        var target = $('#modal_group').find('.dropdown-menu li');
        var self = this;

        target.unbind();
        target.on('click',function(e) {
            var filter = $(e.currentTarget).attr('data-value');
            var parent = $('#item_topic_group_loc_list_' + filter);
            self.data.filter_topic = filter;
            var name = $(e.currentTarget).text();
            $("#modal_topic,#show-topic").find('.dropdown-toggle').text(name);
            contain.scrollTop(0);
            Group_Loc.ShowTopics(parent);
        });
    }

};
