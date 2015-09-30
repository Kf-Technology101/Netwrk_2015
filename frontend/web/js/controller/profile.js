var Profile = {
    data:{},
    params:{
        age: 0,
        work: '',
        about: '',
    },
    initialize: function(){
        Profile.get_profile();
    },

    get_profile: function(){
        var self = this,
            user_setting = $('#user_setting'),
            user_data = $('#user_info');
        Ajax.userprofile().then(function(data){
            var json = $.parseJSON(data);
 
            Profile.data = json;

            Profile.params.age = json.age;
            Profile.params.work = json.work;
            Profile.params.about = json.about;

            if(Profile.data.status == 1){
                Profile.getTemplateUserInfo(user_setting,user_data,function(){
                    Profile.OnTemplate();
                });
            }
        });
    },

    OnTemplate: function(){
        var self = this;
        self.onclicksave();
        self.onclickcancel();
        self.edit_avatar();
    },

    onclicksave: function(){
        var btn_save = $('.btn-control').find('.save');
        btn_save.on('click',function(){
            Profile.getDataUpLoad();
            Ajax.update_profile(Profile.params).then(function(data){
                console.log('update complete');
            });
        });
    },

    getDataUpLoad: function(){
        
        var age = $('input.age').val(),
        work = $('input.work').val(),
        about = $('textarea.about').val();
        Profile.params.age = age;
        Profile.params.work = work;
        Profile.params.about = about;
        
    },

    getDataDefaultUpLoad: function(){
        $('input.age').val(Profile.params.age);
        $('input.work').val(Profile.params.work);
        $('textarea.about').val(Profile.params.about);
    },

    onclickcancel: function(){
        var btn_cancel = $('.btn-control').find('.cancel');
        btn_cancel.on('click',function(){
            Profile.showModalPhoto();
        });
    },
    showModalPhoto: function(){
        var target = $('#modal_change_avatar');

        target.modal('show');
    },
    edit_avatar: function(){
        var btn = $('.change_avatar').find('.fa');

        btn.on('click',function(){
            $('#modal_change_avatar').modal({
                backdrop: true,
                keyboard: false
            });
            Profile.onchangeModalUpload();
        });
    },

    onchangeModalUpload: function(){
        Profile.onclickbrowse();
    },

    onclickbrowse: function(){
        var btn = $('#modal_change_avatar').find('.browse');

        btn.on('click',function(){
            $('input.input_image').click();
        });
    },
    getTemplateTitle: function(){

    },

    getTemplateUserInfo: function(parent,target,callback){
        var template = _.template(target.html());
        var append_html = template({data: Profile.data});
        parent.append(append_html); 

        if(_.isFunction(callback)){
            callback();
        }
    },

    eventOnTemplate: function(){

    }
};