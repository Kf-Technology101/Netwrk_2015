var Group = {
    data:{
        id: null,
        name: null,
        filter: 'recent',
        filter_topic: 'recent',
        filter_post: 'recent',
        city: '',
        size: 30,
        city_name:'',
        zipcode:'',
        topic_id:null,
        topic_name:null
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
        filter:'post',
        city:'',
        city_name:'',
        topic:'',
        topic_name:'',
        size: 30,
        page: 1,
        group_back: '',
        group_back_name: ''
    },
    modal: '#modal_topic',
    modal_create: '#create_topic',
    tab_current: 'topic',
    init: function(){
        //Group._onclickBack();
        /*Group.GetDataOnTab();
        Group.load_topic();
        Group.get_data_new_netwrk();
        Group.RedirectPostList();
        Group.scroll_bot();
        Group.OnClickSortBtn();
        Group.OnClickSelectFilter();
        Group.OnClickChangeTab();
        Group.eventClickMeetMobile();
        Group.OnClickCreateGroup();*/
        Group.OnShowModalGroup();
    },

    initialize: function(from){
        if(from == 'tab'){
            if(isMobile){
                var parentFilter = $('#item_list_'+Group.data.filter);
                Group.filter_group(parentFilter);
            }
        }
        if (!isMobile) {
            //Group._onclickBack();
            /*Group.OnShowModalPost();
            Group.close_modal();
            Group.OnClickBackdrop();
            Group.onNetwrkLogo();
            Group.OnClickCreateGroup();*/
            //Group.show_modal_group(city,params);
            Group.OnShowModalGroup();
            Common.InitToolTip();
            //Group.filter_group();
        }

        Group.CreateTopic();
        Group.CreatePost();
        Group.TotalUsers();
    },

    onclickBackFromTopics: function(){
        if (isMobile) {
            $('#show-topic .back_page span').unbind("click").click(function() {
                Group.LoadGroupModal();
            });
        } else {
            $('#modal_topic .back_page span').unbind("click").click(function() {
                //show again list of groups
                Group.LoadGroupModal();
            });
        }
    },

    onclickBackFromPosts: function(){
        if (isMobile) {
            $('#modal_topic .back_page span').unbind("click").click(function() {
                window.location.href = baseUrl;
            });
        } else {
            $('#modal_topic .back_page span').unbind("click").click(function() {
                var parent = $('#item_topic_group_list_' + Group.data.filter);
                Group.ShowTopics(parent, Group.post_params.group_back, Group.post_params.group_back_name);

                //Hide the group tab header from topic modal.
                Topic.HideTabGroupHeader();
            });
        }
    },

    onclickBackFromUsers: function() {
        if (isMobile) {
            $('#show-topic .back_page span').unbind("click").click(function() {
                var parent = $('#item_topic_group_list_' + Group.data.filter);
                Group.ShowTopics(parent, Group.data.id, Group.data.name);

                //Hide the group tab header from topic modal.
                Topic.HideTabGroupHeader();
            });
        } else {
            $('#modal_topic .back_page span').unbind("click").click(function() {
                var parent = $('#item_topic_group_list_' + Group.data.filter);
                Group.ShowTopics(parent, Group.data.id, Group.data.name);

                //Hide the group tab header from topic modal.
                Topic.HideTabGroupHeader();
            });
        }
    },

    show_modal_group: function(city,new_params){
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
        set_heigth_modal($('#modal_group'),0);
        $('#modal_group').modal({
            backdrop: true,
            keyboard: false
        });
    },

    OnShowModalGroup: function() {
        $('#create_group_modal').unbind('shown.bs.modal').on('shown.bs.modal',function(e) {
            Group.LoadGroupModal();
            //Topic.OnClickChangeTab();
        });
    },

    getTemplate: function(parent,json){
        var self = this;
        var list_template = _.template($("#group_list").html());
        var append_html = list_template({groups: json.data});

        parent.html('');
        parent.append(append_html);
        self.onTemplate(json);

        Topic.OnClickEditGroup();
        Topic.OnClickDeleteGroup();
    },

    getTemplateTotalUsers: function(parent,json){
        var self = this;
        var list_template = _.template($("#total_users").html());
        var append_html = list_template({ joined: json.joined, invited: json.invited });

        parent.html(append_html);
        self.onTemplateTotalUsers(json);
    },

    onTemplateTotalUsers: function(json){
        var self = this;

        $('#item_group_list_'+self.data.filter).find('.no-data').hide();
        self.list[self.data.filter].status_paging = 1;
        $("div[id^='item_topic_group_list']").hide();
        $("div[id^='group_topic_post_filter']").hide();
        $('.topic_group_top').hide();

        console.log("joined:", json);
        if (typeof json.joined != "undefined" && json.joined.length > 0) $('.users-joined .no-data').hide();
        else $('.users-joined .no-data').show();
        if (typeof json.invited != "undefined" && json.invited.length > 0) $('.users-invited .no-data').hide();
        else $('.users-invited .no-data').show();

        //Topic.create_topic();
        //Topic.RedirectPostList();
        // this.create_post();
    },

    getTemplateModal: function(parent,json){
        return;
        var self = this;
        var list_template = _.template($( "#city_name" ).html());
        var append_html = list_template({city: json.city});
        Topic.data.city_name = json.city;
        parent.append(append_html);

        Topic.OnClickEditGroup();
    },

    onTemplate: function(json){
        var self = this;

        if(json.data.length == 0){
            $('#item_group_list_'+self.data.filter).find('.no-data').show();
            self.list[self.data.filter].status_paging = 0;
        }else if(json.data.length < self.data.size && json.data.length > 0){
            $('#item_group_list_'+self.data.filter).find('.no-data').hide();
            self.list[self.data.filter].status_paging = 0;
        }else{
            $('#item_group_list_'+self.data.filter).find('.no-data').hide();
            self.list[self.data.filter].status_paging = 1;
        }
        $("div[id^='item_topic_group_list']").hide();
        $("div[id^='group_topic_post_filter']").hide();
        $('.topic_group_top').hide();
        //Topic.create_topic();
        //Topic.RedirectPostList();
        // this.create_post();
        Group.LoadGroupTopics();
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
            $('#item_topic_group_list_'+self.data.filter_topic).find('.no-data').show();
            self.list[self.data.filter_topic].status_paging = 0;
        }else if(json.data.length < self.data.size && json.data.length > 0){
            $('#item_topic_group_list_'+self.data.filter_topic).find('.no-data').hide();
            self.list[self.data.filter_topic].status_paging = 0;
        }else{
            $('#item_topic_group_list_'+self.data.filter_topic).find('.no-data').hide();
            self.list[self.data.filter_topic].status_paging = 1;
        }
        $("div[id^='item_group_list']").hide();
        $("div[id^='group_topic_post_filter']").hide();
        $('.topic_group_top').show();
        $('#btn-create-topic').show();
        $('#btn-create-post').hide();
        //Topic.create_topic();
        Group.RedirectPostList();
        Group.onclickBackFromTopics();
        // this.create_post();
    },

    RedirectPostList: function() {
        var parent = $('#item_topic_group_list_'+Group.data.filter_topic);
        parent.find('.item').unbind().on('click',function(e){
            var topic = $(e.currentTarget).data('item');
            Ajax.update_view_topic({topic: topic}).then(function(){
                if(isMobile){
                    Post.RedirectPostPage(topic, true);
                }else{
                    //$('#modal_topic').modal('hide');
                    Group.post_params.topic = topic;
                    Group.post_params.topic_name = $(e.currentTarget).find('.name_topic p').text();
                    Group.post_params.city = Topic.data.city;
                    Group.post_params.city_name = Topic.data.city_name;
                    Group.GetTabPost();
                }
                Group.onclickBackFromPosts();
                Group.OnClickChat();
            });

            //Hide the group tab header from topic modal.
            Topic.HideTabGroupHeader();
        });

        parent.find('.item .name_post a').unbind();
        parent.find('.item .name_post a').on('click',function(e){
            e.stopPropagation();
        });
    },

    GetTabPost: function() {
        var parent = $('#group_topic_post_filter_' + Group.post_params.filter);

        Ajax.get_post_by_topic(Group.post_params).then(function(data){
            var json = $.parseJSON(data);
            //Post.checkStatus(json.data);
            if(json.status == 1 && json.data.length> 0){
                parent.show();
                parent.find('.no-data').hide();
                Group.getTemplatePost(parent,json.data);
                /* todo
                Post.OnclickVote();
                Post.OnClickChat();
                if(isMobile){
                    var infomation = $('.container_post').find('.item_post .information');
                    var wi_avatar = $($('.container_post').find('.item_post')[0]).find('.users_avatar').width();
                    fix_width_post(infomation,145);
                }*/
            }
        });
    },

    getTemplatePost: function(parent,data){
        var self = this;
        var list_template = _.template($("#post_topic_group_list" ).html());
        var append_html = list_template({posts: data});
        parent.html(append_html);
        $("div[id^='item_topic_group_list']").hide();
        $("div[id^='item_group_list']").hide();
        $('.topic_group_top').show();
        $('#btn-create-topic').hide();
        $('#btn-create-post').show();
    },

    LoadGroupModal: function(){
        var self = this;
        var parent = $('#item_group_list_'+self.data.filter);
        var cityname = $('#modal_topic').find('.title_page');
        var params = {'city': Topic.data.city,'zipcode': Topic.data.zipcode, 'filter': Topic.data.filter,'size': Topic.data.size,'page':1};

        parent.show();
        // sidebar.show();
        Ajax.show_groups(params).then(function(data){
            var json = $.parseJSON(data);
            console.log("deleting old");
            $("div[id^='item_group_list'] .item").remove();
            parent.scrollTop(0);
            self.list[self.data.filter].loaded = self.list[self.data.filter].paging;
            self.getTemplate(parent, json);
            self.getTemplateModal(cityname, json);
            Topic.CustomScrollBar();
            Group.filter_group(parent);
            Topic.GetDataOnTab();
            Common.deleteTrigger();
        });
    },

    LoadGroupTopics: function() {
        var parent = $('#item_group_list_'+Group.data.filter);
        parent.find('.item .group_loc_post').unbind();
        parent.find('.item .group_loc_post').on('click',function(e) {
            var group = $(e.currentTarget).parent('.item').data('item');
            var groupName = $(e.currentTarget).find('.name_group').text();
            var parent = $('#item_topic_group_list_' + Group.data.filter);
            Group.ShowTopics(parent, group, groupName);
            $('#modal_topic').find(".dropdown").removeClass('visible');
            $('#modal_topic .sidebar').find('.dropdown').addClass('visible');

            //Hide the group tab header from topic modal.
            Topic.HideTabGroupHeader();
        });
    },

    ShowTopics: function(parent, group, groupName) {
        if (typeof group == "undefined") group = Group.data.id;
        else Group.data.id = group;

        if (typeof groupName == "undefined") groupName = Group.data.name;
        else Group.data.name = groupName;

        var cityname = $('#modal_topic').find('.title_page');
        var params = {
            //'city': self.data.city,
            //'zipcode': self.data.zipcode,
            'filter': Group.data.filter_topic,
            'size': Group.data.size,
            'page': 1,
            'group': group
        };

        parent.show();
        // sidebar.show();
        Ajax.show_topic(params).then(function (data) {
            var json = $.parseJSON(data);
            console.log("deleting old");
            $("div[id^='item_topic_group_list'] .item").remove();
            $('#item_total_users').hide();
            if (json.data.length > 0) {
                parent.find('.no-data').hide();
            }
            parent.scrollTop(0);
            Group.list[Group.data.filter].loaded = Group.list[Group.data.filter].paging;
            Group.getTemplateTopicGroup(parent, json);
            //self.getTemplateModal(cityname, data);
            Topic.CustomScrollBar();
            Group.filter_topic(parent);
            /*Topic.GetDataOnTab();*/
            Group.post_params.group_back = group;
            Group.post_params.group_back_name = groupName;
            $('.topic_group_name span').html(groupName);
        });
    },

    CreateTopic: function() {
        var btn = $('#btn-create-topic');

        console.log("create topic!!! ", btn);

        btn.eq(0).off().on('click', function() {
            if(isMobile) {
                window.location.href = baseUrl + "/netwrk/topic/create-topic?city="+Topic.data.city+"&group="+Group.data.id;
            } else {
                $('#modal_topic').modal('hide');
                Create_Topic.initialize(Topic.data.city, Topic.data.city_name, true, Group.data.id);
            }
        });
    },

    CreatePost: function() {
        $('#btn-create-post').eq(0).click(function() {
            $('#modal_topic').modal('hide');
            Create_Post.initialize(Topic.data.city, Group.post_params.topic, Topic.data.city_name, Group.post_params.topic_name, Group.data.id);
        });
    },

    TotalUsers: function() {
        var parent = $('#item_total_users');
        $('.topic_group_name button').eq(0).unbind("click").click(function() {
            Ajax.get_users(Group.data.id).then(function(data) {
                var json = $.parseJSON(data);
                $("div[id^='item_topic_group_list']").hide();
                $("div[id^='group_topic_post_filter']").hide();
                $('.topic_group_top').hide();
                $('#item_total_users').show().find('.no-data').hide();
                Group.getTemplateTotalUsers(parent, json);
                Group.onclickBackFromUsers();

                if(isMobile){
                    // Hide Create Group button and filter option when Total Users section open
                    $('#create_group').hide();
                    $('#show-topic').find('.header .title_page').addClass('on-feed');
                    $('#show-topic').find('span.filter').addClass('visible');
                }
            });
            //Hide the group tab header from topic modal.
            Topic.HideTabGroupHeader();
        });
    },

    OnClickChat: function(){
        var btn = $(".post_chat");

        btn.unbind();
        btn.on('click',function(e){
            var item_post = $(e.currentTarget).parent().parent().attr('data-item');
            if(isMobile){
                PopupChat.RedirectChatPostPage(item_post, 1, 0);
            }else{
                $("#modal_topic").modal('hide');
                /*ChatPost.params.post = item_post;
                ChatPost.initialize();*/
                PopupChat.params.post = item_post;
                PopupChat.params.chat_type = $(e.currentTarget).parent().parent().attr('data-chat-type');
                PopupChat.params.post_name = $(e.currentTarget).parent().parent().find('.information .post_name').html();
                PopupChat.params.post_description = $(e.currentTarget).parent().parent().find('.information .post_massage').html();
                ChatInbox.params.target_popup = $('.popup_chat_modal #popup-chat-'+PopupChat.params.post);
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

    filter_group: function(contain) {
        console.log('filter_group');
        var target = $('#modal_topic,#show-topic').find('.groups-dropdown .dropdown-menu li');
        var self = this;

        target.unbind();
        target.on('click',function(e){
            var filter = $(e.currentTarget).attr('data-value');
            var name = $(e.currentTarget).text();
            $("#modal_topic,#show-topic").find('.groups-dropdown .dropdown-toggle').text(name);
            $("#modal_topic,#show-topic").find("div[id^='item_group_list']").hide();
            contain.scrollTop(0);
            Group.data.filter = filter;
            Group.load_groups_filter($(e.currentTarget),Topic.data.city,self.data.filter);

        });
    },

    load_groups_filter: function() {
        var self = this;
        var parent = $('#modal_topic,#show-topic').find('#item_group_list_'+self.data.filter);
        var params = {'city': Topic.data.city, 'filter': self.data.filter,'size': self.data.size,'page':self.list[self.data.filter].paging};

        parent.show();

        if (self.list[self.data.filter].paging != self.list[self.data.filter].loaded){
            Ajax.show_groups(params).then(function(data){
                var json = $.parseJSON(data);
                self.getTemplate(parent, json);
                self.list[self.data.filter].loaded = self.list[self.data.filter].paging ;
                Common.deleteTrigger();
            });
        }
    },

    filter_topic: function(contain) {
        console.log('filter_topic');
        var target = $('#modal_topic,#show-topic').find('.group-topics-dropdown .dropdown-menu li');
        var self = this;

        target.unbind();
        target.on('click',function(e){
            var filter = $(e.currentTarget).attr('data-value');
            var parent = $('#item_topic_group_list_' + filter);
            self.data.filter_topic = filter;
            var name = $(e.currentTarget).text();
            $("#modal_topic,#show-topic").find('.group-topics-dropdown .dropdown-toggle').text(name);
            contain.scrollTop(0);
            Group.ShowTopics(parent);
        });
    }
};
