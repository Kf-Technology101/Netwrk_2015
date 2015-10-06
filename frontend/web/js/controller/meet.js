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
    
    initialize: function() {  
        if(Meet.filter.active == 'meeting'){
            console.log('aaaa');
            Meet._init();
        }else if(Meet.filter.active == 'profile'){
            Profile.initialize();
        }else{
            Meet_setting.initialize();
        }    
    },

    _init: function(){
        if(isMobile){
            var currentTarget = $('#meeting_page'),
                container = $('.container_meet');

            container.find('.page').hide(); 
            $('.name_user').find('img').show();   
            $('#btn_meet_mobile').hide();
            $('#btn_discover_mobile').show();
            currentTarget.show();

            Meet.reset_page();
            Meet._onclickBack();
            Meet.GetUserMeet();
            Meet.changefilter(currentTarget);
        }else{
            var currentTarget = $('#meeting'),
                container = $('.container_meet');

            container.find('.page').hide();
            $('#btn_meet').hide();
            $('#btn_discover').show();
             currentTarget.show();

            Meet.reset_modal();
            Meet.changefilter(currentTarget);
            Meet.ShowModalMeet();

        }
    },

    changefilter: function(containt){
        var target = $('.filter_sidebar').find('td');
        var self = this;
        target.unbind();
        target.on('click',function(e){
            // target.bind();
            var filter = $(e.currentTarget).attr('class');
            if(!$(e.currentTarget).hasClass('active')){
                $("div[id^='item_list']").hide();
                containt.scrollTop(0);
                self.filter.active = $.trim(filter);

                self.change_button_active(target,$(e.currentTarget),containt);
                Meet.initialize();
                // self.load_topic_filter($(e.currentTarget),self.data.city,self.data.filter);
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

    showUserMeetMobile: function(){
        window.location.href = "netwrk/meet"; 
    },

    GetUserMeet: function(){
         Ajax.getUserMeeting().then(function(data){
            var json = $.parseJSON(data);
            console.log(json.data);
            if(json.data.length >0){
                Meet.user_list.len = json.data.length;
                Meet.json = json.data;
                Meet.showUserMeet();
                $('.control-btn').show();
            }else{
                console.log('No Data');
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
            target = $('#btn_discover');
            target.unbind();
            target.on('click',function(){
                // target.bind();
                self.reset_modal();
                // self._init();
                // location.href.reload ;
            });
    },

    ShowModalMeet: function(){
        var modal = $('#modal_meet'),
            self = this;
        if(!isMobile){
            modal.modal({
                backdrop: true,
                keyboard: false
            });   
        }
        Ajax.getUserMeeting().then(function(data){
            var json = $.parseJSON(data);
            self.user_list.len = json.data.length;
            if(self.user_list.len > 0){
                $('.control-btn').show();
                $('p.default').hide();
                self.json = json.data;
                self.showUserMeet();
            }else{
                $('.control-btn').hide();
                $('p.default').show();
            }
            
            $('#modal_meet').on('hidden.bs.modal',function() {
                self.reset_modal();
            });
            $('.modal-backdrop.in').click(function(e) {
                self.reset_modal();
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
        $('#btn_discover').hide();
        name.find('p.name').remove();
        name.find('span').remove();
        info.find('.user_item').remove();
        btn_next.removeClass('disable');
        btn_back.addClass('disable');
        self.user_list.vt = 0;
        self.user_list.num = 1;
        self.user_list.len = 0;  
        self.json = {};
        $('#modal_meet').modal('hide');
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

        if(isMobile){
            self.eventClickdiscoverMobile();

        }else{
            self.eventClickdiscover();
        }
        self.eventMeet();
        self.eventMet();
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
            user.show();
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
                data[self.user_list.vt].met = 1;
                btn_meet.hide();
                btn_met.show();
                Ajax.usermeet({user_id: data[self.user_list.vt].user_id }).then(function(res){
                    self.eventMet();
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
    }
}; 
