var Profile = {
    data:{},
    params:{
        age: 0,
        work: '',
        about: '',
        zipcode:'',
    },
    img:{
        image:''
    },
    initialize: function(){
        Profile.get_profile();
        Profile.setDatePicker();
    },

    setDatePicker: function(){
        var wh=
        $('input.age').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '-3d',
        });
        $('.datepicker-dropdown').css('top',75)
        $('input.age').on('changeDate',function(){
            $( "input.age" ).datepicker('hide');
        })
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
            Profile.params.zipcode = json.zip;

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
        about = $('textarea.about').val(),
        zipcode = $('input.zip_code').val();
        Profile.params.age = age;
        Profile.params.work = work;
        Profile.params.about = about;
        Profile.params.zipcode = zipcode;
        
    },

    getDataDefaultUpLoad: function(){
        $('input.age').val(Profile.params.age);
        $('input.work').val(Profile.params.work);
        $('input.zip_code').val(Profile.params.zipcode);
        $('textarea.about').val(Profile.params.about);
    },

    onclickcancel: function(){
        var btn_cancel = $('.btn-control').find('.cancel');
        btn_cancel.on('click',function(){
            Profile.getDataDefaultUpLoad();
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
        Profile.onbrowse();
        Profile.onCancel();
        Profile.onBackdrop();
    },

    onBackdrop: function(){
        $('#modal_change_avatar').on('hidden.bs.modal',function() {
            $('img.preview_image').attr('src','');
            $('img.preview_image').hide();
            $('.image-preview').find('p').show();
        });
    },

    onCancel: function(){
        var btn = $('.btn-control-modal').find('.cancel');
        btn.on('click',function(){
            $('#modal_change_avatar').modal('hide');
            $('img.preview_image').attr('src','');
            $('img.preview_image').hide();
            $('.image-preview').find('p').show();
        });
    },
    onbrowse: function(){
        var btn = $('#modal_change_avatar').find('.browse');
        btn.unbind();
        btn.on('click',function(){
            btn.bind();
            $('.input_image').val('').clone(true);
            
            $('.input_image')[0].click();

            $('.input_image').unbind();
            $('.input_image').change(function(e) {
                $('.input_image').bind();
                Profile.readURL(this);
            });
        });


    },

    readURL: function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            Profile.img.image = input.files;
            reader.onload = function (e) {
                Profile.getPreviewImage(e);
                Profile.onEventSaveImage();
            }

            reader.readAsDataURL(input.files[0]);
        }
    },

    getPreviewImage: function(e){
        var target = $('img.preview_image'),
            parent_text = $('.image-preview').find('p'),
            btn_control_save = $('.btn-control-modal').find('.save') ;

        btn_control_save.removeClass('disable');
        parent_text.hide();
        // target.attr('src','');
        target.show();
        target.attr('src', e.target.result);
    },

    onEventSaveImage:function(){
        var btn_save = $('.btn-control-modal').find('.save');

        if (!btn_save.hasClass('disable')) {
            btn_save.on('click',function(){
                $('#upload_image').unbind();
                $('#upload_image').on('submit',function( event ) {
                    event.preventDefault();
                    var formData = new FormData(this);

                    Ajax.upload_image(formData).then(function(data){
                        var json = $.parseJSON(data)
                        Profile.img.images = json.data_image;
                        Profile.reload_image_update();
                        
                    });
                
                });
                $('#upload_image').submit();
            });
        };
    },

    reload_image_update: function(){
        $('#modal_change_avatar').modal('hide');
        $('.user_avatar').find('img').attr('src',Profile.img.images);
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