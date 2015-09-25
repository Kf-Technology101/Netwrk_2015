var Meet ={
    params:{
        user_id: 0,
        gender: 'All',
        distance: '',
        age: ''
    },
    user_list: {
        vt: 0,
        num: 1,
        len: 0
    },
    json:{},
    
    initialize: function() {
        var self = this;

        self.ShowModalMeet();
        
    },

    eventClickdiscover: function(){
        var self = this,
            target = $('#btn_discover');
        target.unbind();
        target.on('click',function(){
            target.bind();
            self.reset_modal();
            self.ShowModalMeet();
            console.log('asdasd');
        });
    },

    ShowModalMeet: function(){
        var modal = $('#modal_meet'),
            self = this;
         
        modal.modal({
            backdrop: true,
            keyboard: false
        });   
        $('#btn_meet').hide();
        $('#btn_discover').show();

        Ajax.getUserMeeting(self.params).then(function(data){
            var json = $.parseJSON(data);
            self.user_list.len = json.data.length;
            self.json = json;
            self.showUserMeet(json);

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
        name.find('p').remove();
        info.find('.user_item').remove();
        btn_next.removeClass('disable');
        btn_back.addClass('disable');
        self.user_list.vt = 0;
        self.user_list.num = 1;
        self.user_list.len = 0;  
        self.json = {};
        $('#modal_meet').modal('hide');
    },

    onControlTemplate: function(){
        var self = this,
            data = self.json.data,
            len = self.user_list.len,
            btn_meet = $('.control-btn').find('.meet'),
            btn_met = $('.control-btn').find('.met'),
            btn_next = $('.control-btn').find('.next'),
            btn_back = $('.control-btn').find('.back');

        
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
        self.eventClickdiscover();
    },

    disableUser: function(num){
        var user = $('.user_meet_'+num);
        user.hide();
    },

    showUser: function(num){
        var user = $('.user_meet_'+num),
            self = this;
        console.log(num);
        if(user.length > 0){
            user.show();
            self.eventMeet();
        }else{

            self.user_list.num ++ ;
            self.showUserMeet();
        }
       
    },
    eventMeet: function(){
        var self = this,
            data = self.json.data;
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
                    console.log(data[self.user_list.vt].user_id);
                    self.eventMet();
                });
            });
        }
    },
    eventMet: function(){
        var self = this,
            data = self.json.data;
            btn_meet = $('.control-btn').find('.meet'),
            btn_met = $('.control-btn').find('.met');
        btn_met.unbind();
        btn_met.on('click',function(){
            data[self.user_list.vt].met = 0;
            btn_meet.show();
            btn_met.hide();
            Ajax.usermet({user_id: data[self.user_list.vt].user_id }).then(function(res){
                console.log(data[self.user_list.vt].user_id);
            });
        });
    },
    showUserMeet: function(){
        var self = this;
        var name = $('.name_user'),
            info = $('.user_list');
        var vt = self.user_list.vt;
        var data = self.json.data[vt];

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