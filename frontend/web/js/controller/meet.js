var Meet ={
    params:{
        user_id: 1,
        gender: 'All',
        distance: '',
        age: ''
    },
    user_list: {
        vt: 0,
        num: 1,
        len: 0
    },
    filter:{
        active:'meeting',
    },
    json:{

    },
    modal: '#modal_meet',
    list: '#meetListing',
    height: 0,
    infoOf: 0,
    pid: 0,
    ez: 0,
    initialize: function() {
        if(isMobile){
            Meet._init();
            Default.SetAvatarUserDropdown();
        } else {
            if(Meet.filter.active === 'setting'){
                Meet_setting.initialize();
            }else if(Meet.filter.active === 'profile'){
                Profile.initialize();
            }else if(Meet.filter.active === 'meeting'){
                Meet._init();
            }
        }
    },

    // set height of mobile screen
    setHeightContainer: function() {
        var size = get_size_window();
        var h_navSearch = $('.navbar-mobile').height();
        var h_header = $('#show_meet').find('.sidebar').height();
        var btn_meet = $('#btn_meet_mobile').height()-10;
        var nav_message = $('#show_meet').find('.footer-btn').height();
        var nav_bottom = $('.navigation-wrapper').height() + 6;

        var wh = size[1] - h_navSearch - h_header - nav_bottom;
        $('#show_meet').find('.container_meet').css('height',wh);
    },

    _init: function(){
        //Common.ShowModalComeBack();
        //return;
        var post_id = Meet.getParameterByName('post_id'),
            user_view = Meet.getParameterByName('user_id'),
            from = Meet.getParameterByName('from');
        if(isMobile){
            var meetHeight = $(window).height()-105;
            $(Meet.list).css({'height' : meetHeight});

            Meet.onClickBack();

            /*if(post_id != "" && from != "" && from == "private") {
                Meet.GetUserMeetProfile(post_id);
            } else if (user_view != "" && from != "" && from == "discussion") {
                Meet.GetUserMeetProfileDiscussion(user_view);
            } else {*/
                Meet.GetUserMeet();
            /*}*/
        }else{
            var parent = $('#modal_meet'),
                currentTarget = parent.find('#meeting'),
                container = parent.find('.container_meet');
            container.find('.page').hide();
            Meet.reset_modal();
            currentTarget.show();

            Meet.changefilter(currentTarget);
            if(Meet.pid != 0){
                Meet.ShowUserMeetProfile(Meet.pid);
            }else if(Meet.ez != 0){
                Meet.ShowModalMeetProfile(Meet.ez);
            }else{
                Meet.ShowModalMeet();
            }
            Meet.eventClickdiscover();
            Meet.CustomScrollBar();
            Meet._onClickMeetBack();
            // $('#btn_meet').hide();
            $('.modal-footer').show();
            Topic.displayPositionModal();
        }
        Meet.CheckUserLogin();
    },

    CheckUserLogin:function(){
        var target = $('#modal_meet').find('td.setting, td.profile')
            .add($('#show_meet').find('.setting-menu li.profile, .setting-menu li.setting'));
        if(isGuest){
            target.addClass('no-login');
        }else{
            target.removeClass('no-login');
        }
    },

    CustomScrollBar: function(){
        var parent;

        parent = $("#modal_meet").find('.modal-body');

        parent.mCustomScrollbar({
            theme:"dark"
        });
    },

    changefilter: function(containt){
        var target = $('#modal_meet').find('.filter_sidebar td')
            .add($('#show_meet').find('.setting-menu li'));
        var self = this;
        target.unbind();
        target.on('click',function(e){
            // target.bind();
            if(!$(e.currentTarget).hasClass('no-login')){
                var filter = $(e.currentTarget).attr('class');
                if(isMobile){
                    if(e.currentTarget == 'meeting'){
                        $('#show_meet').find('.meet-nav-control').removeClass('hide');
                    } else {
                        $('#show_meet').find('.meet-nav-control').addClass('hide');
                    }

                    $('#show_meet').find("div[id^='item_list']").hide();
                    containt.scrollTop(0);
                    self.filter.active = $.trim(filter);

                    self.change_button_active(target,$(e.currentTarget),containt);
                    Meet.initialize();
                } else {
                    if(!$(e.currentTarget).hasClass('active')){
                        $('#modal_meet').find("div[id^='item_list']").hide();
                        containt.scrollTop(0);
                        self.filter.active = $.trim(filter);

                        self.change_button_active(target,$(e.currentTarget),containt);
                        Meet.initialize();
                        // self.load_topic_filter($(e.currentTarget),self.data.city,self.data.filter);
                    }
                }
            }

        });
    },
    change_button_active:function(target,parent,current){
        $.each(target,function(i,s){
            if($(s).hasClass('active')){
                $(s).removeClass('active');
                current.hide();
                parent.addClass('active');
                // Meet.initialize();
            }
        });
    },
    clear_data_filter: function(target){

    },

    _onclickBack: function(){
        $('.back_page img').click(function(){
            window.location.href = baseUrl;
        })
    },

    _onClickMeetBack: function(){
        $('#modal_meet .back_page span, .box-navigation .btn_nav_map').click(function(){
            $('#modal_meet').modal('hide');
        })
    },

    showUserMeetMobile: function(){
        //Common.ShowModalComeBack();
        window.location.href = baseUrl + "/netwrk/meet";
    },

    onClickBack: function(){
        if(isMobile){
            $('#meetListing .back-page').off('click').on('click', function(){
                window.location.href = baseUrl;
            })
        }
    },

    CustomScrollBarListing: function(){
        var parent = $(Meet.list).find('#meetListWrapper');
            parent.css('height', $(window).height()-145);

        if ($(parent).find("div[id^='mSCB']").length == 0) {
            $(parent).mCustomScrollbar({
                theme:"dark",
            });
        };
    },

    onClickMeetButton: function() {
        var btn = $(Meet.list).find('#meetListWrapper ul li').find('.btn-meet-trigger');
        btn.unbind();
        btn.on('click',function(){
            var meetBtn = $(this);
            var user_id = meetBtn.parent('.meet-button-wrapper').attr('data-user-id');
            var currentState = meetBtn.text();
            console.log(currentState);

            if(isGuest){
                Login.RedirectLogin(window.location.href);
            } else {
                Ajax.usermeet({user_id: user_id }).then(function(res){
                    if(currentState == 'Meet') {
                        meetBtn.text('Met');
                        meetBtn.addClass('btn-met');
                    } else {
                        meetBtn.text('Meet');
                        meetBtn.removeClass('btn-met');
                    }
                    window.ws.send("notify", {"sender": UserLogin, "receiver": user_id, "room": -1, "message": ''});
                });
            }
        });
    },

    GetUserMeet: function(){
        Ajax.getUserMeeting().then(function(data){
            var json = $.parseJSON(data);

            if(json.data.length >0) {
                $('p.no_data').hide();
                Meet.user_list.len = json.data.length;
                Meet.json = json.data;
                if(isMobile){
                    var list_template = _.template($("#meet_list").html());
                    var append_html = list_template({meet_list: Meet.json});
                    parent = $(Meet.list).find('#meetListWrapper ul');
                    parent.find('li').remove();
                    parent.append(append_html);
                    Meet.CustomScrollBarListing();
                    Meet.onClickMeetButton();
                } else {
                    Meet.showUserMeet();
                    $('.control-btn').show();
                }
            } else {
                $('p.no_data').show();
            }

            // $('#modal_meet').on('hidden.bs.modal',function() {
            //     self.reset_modal();
            // });
            // $('.modal-backdrop.in').click(function(e) {
            //     self.reset_modal();
            // });
        });
    },

    eventClickdiscoverMobile: function(){
        var target = $('#btn_discover_mobile');
        target.unbind();
        target.on('click',function(){
            target.bind();
            window.location.href = baseUrl;
            // Meet.reset_page();
            // Meet._init();
        });
    },

    eventClickdiscover: function(){
        var self = this,
            target = $('#modal_meet #btn_discover');
            target.unbind();
            target.on('click',function(){
                // target.bind();
                self.reset_modal();
                $('#modal_meet').modal('hide');
                // self._init();
                // location.href.reload ;
            });
    },

    ShowModalMeet: function(){
        var modal = $('#modal_meet'),
            self = this;

        Ajax.getUserMeeting().then(function(data){
            var json = $.parseJSON(data);
            self.user_list.len = json.data.length;

            if(self.user_list.len > 0){
                $('p.no_data').hide();
                $('.control-btn').show();
                $('p.default').hide();
                self.json = json.data;
                self.showUserMeet();
            }else{
                $('.control-btn').hide();
                $('p.default').show();
                $('p.no_data').show();
            }

            if(!isMobile){
                modal.modal({
                    backdrop: true,
                    keyboard: false
                });
                set_heigth_modal_meet($('#modal_meet'), 30, 645, 570);
                var meet_height = $('#modal_meet .modal-body').height();
                Meet.height = meet_height;
            }
            $('#modal_meet').on('hidden.bs.modal',function() {
                self.reset_modal();
                $('#modal_meet').modal('hide');
            });
            $('.modal-backdrop.in').click(function(e) {
                self.reset_modal();
                $('#modal_meet').modal('hide');
            });
        });
    },

    reset_modal: function(){
        var self = this,
            name = $('.name_user'),
            info = $('.user_list'),
            btn_next = $('.control-btn').find('.next'),
            btn_back = $('.control-btn').find('.back');

        $('#btn_meet').show();
        $('#modal_meet #btn_discover').hide();
        name.find('p.name').remove();
        name.find('span').remove();
        info.find('.user_item').remove();
        btn_next.removeClass('disable');
        btn_back.addClass('disable');
        self.user_list.vt = 0;
        self.user_list.num = 1;
        self.user_list.len = 0;
        self.json = {};
        Meet.filter.active = 'meeting';
        $('.control-btn').hide();
        $('#modal_meet').find('.sidebar td').removeClass('active');
        $('#modal_meet').find('.sidebar td').first().addClass('active');
        // $('#modal_meet').modal('hide');
    },

    reset_page: function(){
        var self = this,
            name = $('.name_user'),
            info = $('.user_list'),
            btn_next = $('.control-btn').find('.next'),
            btn_back = $('.control-btn').find('.back');

        // $('#btn_meet').show();
        // $('#btn_discover').hide();
        name.find('span').remove();
        name.find('p.name').remove();
        info.find('.user_item').remove();
        btn_next.removeClass('disable');
        btn_back.addClass('disable');
        $('.control-btn').hide();
        self.user_list.vt = 0;
        self.user_list.num = 1;
        self.user_list.len = 0;
        self.json = {};

    },

    onControlTemplate: function(){
        var self = this,
            data = self.json,
            len = self.user_list.len,
            btn_meet = $('.control-btn').find('.meet'),
            btn_met = $('.control-btn').find('.met'),
            btn_next = $('.control-btn').find('.next'),
            btn_back = $('.control-btn').find('.back');

        if (self.user_list.len == 1){
            btn_next.addClass('disable');
        }

        btn_next.unbind();
        btn_next.on('click',function(){
            btn_next.bind();
            if(self.user_list.vt != len - 1 ){

                btn_back.removeClass('disable');
                if(self.user_list.vt == len - 2 ){
                    btn_next.addClass('disable');
                }
                self.disableUser(self.user_list.vt);
                self.user_list.vt ++ ;

                self.showUser(self.user_list.vt);

                self.eventMeet();
            }
        });

        btn_back.unbind();
        btn_back.on('click',function(){
            btn_back.bind();
            if(self.user_list.vt != 0){

                if(self.user_list.vt == 1){
                    btn_back.addClass('disable');
                }

                btn_next.removeClass('disable');
                self.disableUser(self.user_list.vt);
                self.user_list.vt -- ;
                self.showUser(self.user_list.vt);
                self.eventMeet();
            }
        });


        self.eventMeet();
        self.eventMet();
        self.OnClickChatPost();
    },

    OnClickChatPost: function(){
        var target = $('#meeting_page,#modal_meet').find('.box-infomation .post .list-post span');
        target.unbind();
            target.on('click',function(e){
            var item_post = $(e.currentTarget).attr('data-item');
            if(isMobile){
                PopupChat.RedirectChatPostPage(item_post, 1, 0);
            }else{
                $('#modal_meet').modal('hide');
                Ajax.get_info_post(item_post).then(function(data){
                    if (data) {
                        data = $.parseJSON(data);
                        PopupChat.params.post = data.id;
                        PopupChat.params.chat_type = data.post_type;
                        PopupChat.params.post_name = data.title;
                        PopupChat.params.post_description = data.content;
                        PopupChat.initialize();
                    }
                });
            }
        });

    },

    disableUser: function(num){
        var user = $('.user_meet_'+num);
        user.removeClass('active');
        user.hide();
    },

    showUser: function(num){
        var user = $('.user_meet_'+num),
            self = this;
        if(user.length > 0){
            user.fadeIn('500');
            self.eventMeet();
            user.addClass('active');
        }else{
            self.user_list.num ++ ;
            self.showUserMeet();
        }

    },
    eventMeet: function(){
        var self = this,
            data = self.json;
            btn_meet = $('.control-btn').find('.meet'),
            btn_met = $('.control-btn').find('.met'),
            btn_next = $('.control-btn').find('.next'),
            btn_back = $('.control-btn').find('.back');

        if( data[self.user_list.vt].met == 1 ){
            btn_meet.hide();
            btn_met.show();
        }else{
            btn_meet.show();
            btn_met.hide();
            btn_meet.unbind();
            btn_meet.on('click',function(){
                if(isGuest){
                    if(isMobile){
                        Login.RedirectLogin(window.location.href);
                    }else{
                        $('.modal').modal('hide');
                        Login.modal_callback = Meet;
                        Login.initialize();
                        return false
                    }
                }
                for(i=0;i<data.length;i++)
                    if(data[i].user_id == data[self.user_list.vt].user_id){
                    data[i].met = 1;
                    btn_meet.hide();
                    btn_met.show();
                }
                Ajax.usermeet({user_id: data[self.user_list.vt].user_id }).then(function(res){
                    self.eventMet();
                    if(!isMobile){
                        ChatInbox.GetDataListChatPrivate();
                    }
                     window.ws.send("notify", {"sender": UserLogin, "receiver": data[self.user_list.vt].user_id, "room": -1, "message": ''});
                });
            });
        }
    },
    eventMet: function(){
        var self = this,
            data = self.json;
            btn_meet = $('.control-btn').find('.meet'),
            btn_met = $('.control-btn').find('.met');
        btn_met.unbind();
        btn_met.on('click',function(){
            data[self.user_list.vt].met = 0;
            btn_meet.show();
            btn_met.hide();
            Ajax.usermet({user_id: data[self.user_list.vt].user_id });
        });
    },
    showUserMeet: function(){
        var self = this;
        var name = $('.name_user'),
            info = $('.user_list');

        var vt = self.user_list.vt;
        var data = self.json[vt];
        self.getTemplateUserName(name,data,vt);
        self.getTemplateInfo(info,data,vt);
        self.onControlTemplate();
    },

    getTemplateUserName: function(parent,data,vt){
        var self = this;
        var template = _.template($( "#name_user" ).html());
        var append_html = template({user: data,vt: vt});

        parent.append(append_html);
    },

    getTemplateInfo: function(parent,data,vt){
        var self = this;
        var template = _.template($( "#list_user" ).html());
        var append_html = template({user: data.information ,vt: vt});

        parent.append(append_html);
    },

    getParameterByName: function(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    },

    GetUserMeetProfile: function(post_id){
        Ajax.get_user_met_profile(post_id).then(function(data){
            var json = $.parseJSON(data);
            if(json.data.length >0){
                $('p.no_data').hide();
                Meet.user_list.len = json.data.length;
                Meet.json = json.data;
                Meet.showUserMeet();
                $('.control-btn').show();
            }else{
                $('p.no_data').show();
            }
        });
    },

    ShowUserMeetProfile: function(post_id){
        var modal = $('#modal_meet'),
            self = this;

        Ajax.get_user_met_profile(post_id).then(function(data){
            var json = $.parseJSON(data);
            self.user_list.len = json.data.length;

            if(self.user_list.len > 0){
                $('p.no_data').hide();
                $('.control-btn').show();
                $('p.default').hide();
                self.json = json.data;
                self.showUserMeet();
            }else{
                $('.control-btn').hide();
                $('p.default').show();
                $('p.no_data').show();
            }

            if(!isMobile){
                modal.modal({
                    backdrop: true,
                    keyboard: false
                });
                set_heigth_modal_meet($('#modal_meet'), 30, 645, 570);
                var meet_height = $('#modal_meet .modal-body').height();
                Meet.height = meet_height;
            }
            $('#modal_meet').on('hidden.bs.modal',function() {
                self.reset_modal();
                $('#modal_meet').modal('hide');
            });
            $('.modal-backdrop.in').click(function(e) {
                self.reset_modal();
                $('#modal_meet').modal('hide');
            });
        });
    },

    GetUserMeetProfileDiscussion: function(user_view){
        Ajax.get_user_met_profile_discussion(user_view).then(function(data){
            var json = $.parseJSON(data);
            if(json.data.length >0){
                $('p.no_data').hide();
                Meet.user_list.len = json.data.length;
                Meet.json = json.data;
                Meet.showUserMeet();
                $('.control-btn').show();
            }else{
                $('p.no_data').show();
            }
        });
    },

    ShowModalMeetProfile: function(user_view){
        var modal = $('#modal_meet'),
            self = this;

        Ajax.get_user_met_profile_discussion(user_view).then(function(data){
            var json = $.parseJSON(data);
            self.user_list.len = json.data.length;

            if(self.user_list.len > 0){
                $('p.no_data').hide();
                $('.control-btn').show();
                $('p.default').hide();
                self.json = json.data;
                self.showUserMeet();
            }else{
                $('.control-btn').hide();
                $('p.default').show();
                $('p.no_data').show();
            }

            if(!isMobile){
                modal.modal({
                    backdrop: true,
                    keyboard: false
                });
                set_heigth_modal_meet($('#modal_meet'), 30, 645, 570);
                var meet_height = $('#modal_meet .modal-body').height();
                Meet.height = meet_height;
            }
            $('#modal_meet').on('hidden.bs.modal',function() {
                self.reset_modal();
                $('#modal_meet').modal('hide');
            });
            $('.modal-backdrop.in').click(function(e) {
                self.reset_modal();
                $('#modal_meet').modal('hide');
            });
        });
    }
};
