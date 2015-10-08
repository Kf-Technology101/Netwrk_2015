var Profile = {
    data:{},
    params:{
        age: 0,
        work: '',
        about: '',
        zipcode:0,
        lat:'',
        lng:''
    },
    img:{
        image:''
    },
    zipcode: false,
    status_change:{
        age:true,
        zipcode: true,
        work: false,
        about:false,
        total:false
    },
    state: 'Indiana',
    initialize: function(){
        console.log(Profile.status_change);
        Profile.reset_page();
        Profile.get_profile();
        Profile.setDatePicker();
        Profile.validateZipcode();
        Profile.onChangeWork();
        Profile.onChangeAbout();
        Profile.eventClickSave();
    },

    eventClickSave: function(){
        var btn_save = $('.btn-control').find('.save');
        btn_save.on('click',function(){
            if(!btn_save.hasClass('disable')){
                Profile.getDataUpLoad();
                Ajax.update_profile(Profile.params).then(function(data){
                    var json = $.parseJSON(data);
                    Profile.data = json;
                    Profile.set_default_btn();
                });
            }
        });
    },

    validateZipcode: function(){
        // Profile.apiZipcode();
        $('input.zip_code').on('keyup',function(e){
            console.log(Profile.params.zipcode);
            var zipcode_current = parseInt($('input.zip_code').val());
            if (zipcode_current > 9999 && zipcode_current != null ){
                if(zipcode_current != Profile.params.zipcode && !Profile.zipcode){
                    // Profile.params.zipcode = zipcode_current;
                    Profile.zipcode = true;
                    Profile.apiZipcode(zipcode_current);
                    if(Profile.status_change.zipcode){
                        Profile.invalidZip(); 
                    }
                }else if(zipcode_current == Profile.params.zipcode && !Profile.zipcode){
                   Profile.validZip(); 
                }
            }else{
                Profile.zipcode = false;
                Profile.invalidZip();
            }            
        });
    },

    apiZipcode: function(zipcode){
        // var zipcode = Profile.params.zipcode;
        // var zipcode = 46601;
        $.getJSON("http://api.zippopotam.us/us/"+zipcode ,function(data){
            if (data.places[0].state == Profile.state){
                Profile.params.lat = data.places[0].latitude;
                Profile.params.lng = data.places[0].longitude;
                Profile.params.zipcode = zipcode;
                Profile.validZip();

                if(zipcode != Profile.data.zip){
                    Profile.status_change.zipcode = true;
                    Profile.OnTemplate();

                }else{
                    Profile.set_default_btn();
                }
                Profile.zipcode = false;
            }else{
                Profile.invalidZip();
            }
        }).fail(function(jqXHR) {
            if (jqXHR.status == 404) {
                Profile.invalidZip();                
            }            
        });
    },

    invalidZip: function(){
        Profile.status_change.zipcode = false;

        var parent = $('input.zip_code').parent();
        parent.addClass('alert_validate');
        if(parent.find('span').size() == 0){
            parent.append('<span>*Invalid zip code</span>');
        }
        Profile.OnTemplate();
    },

    validZip: function(){
        Profile.status_change.zipcode = true;
        var parent = $('input.zip_code').parent();
        if(parent.hasClass('alert_validate')){
            parent.removeClass('alert_validate');
            parent.find('span').remove();
        }
    },

    setDatePicker: function(){
        $('input.birthday').datepicker({
            dateFormat: 'yy-mm-dd',
            // startDate: '-3d',
        });

        $('.datepicker-dropdown').css('top',75)

        $('input.birthday').on('changeDate',function(e){
            $( "input.birthday" ).datepicker('hide');
            $('input.birthday').parent().removeClass('alert_validate');
        });

        $('input.birthday').on('change',function(e){
            Profile.checkDate(e.target.value);
        });
    },

    checkDate: function(value){
        if (isDate(value)) {
            $('input.birthday').parent().removeClass('alert_validate');
            Profile.status_change.age = true;
            Profile.OnTemplate();
        }else{
            Profile.status_change.age = false;
            Profile.OnTemplate();
            $('input.birthday').parent().addClass('alert_validate');        
        }
    },

    get_profile: function(){
        var self = this,
            container = $('.container_meet'),
            user_setting = $('#user_setting'),
            user_data = $('#user_info'),
            user_name_data = $('.name_user'),
            user_name_current = $("#user_name_current");

        $('.log_out').show();
        user_name_data.find('img').show();
        container.find('.page').hide();
        user_setting.show();
        Ajax.userprofile().then(function(data){
            var json = $.parseJSON(data);
            Profile.data = json;

            Profile.params.age = json.age;
            Profile.params.work = json.work;
            Profile.params.about = json.about;
            Profile.params.zipcode = json.zip;

            if(Profile.data.status == 1){
                Profile.getTemplateUserInfo(user_setting,user_data);
                Profile.getTemplateTitle(user_name_data,user_name_current);
                Profile.edit_avatar();
            }
        });
    },

    reset_page: function(){
        var target = $('#user_setting');

        $('.log_out').hide();
        $('.name_user').find('span').remove();
        $('.name_user').find('p.name').remove();
        $('.name_user').find('p.default').hide();
        target.find('.user_avatar').remove();
        target.find('.user_information').remove();
        target.find('.btn-control').remove();
    },

    OnTemplate: function(){
        var self = this;
        self.check_status_change();
        self.onclicksave();
        self.onclickcancel();
        
    },

    check_status_change: function(){

        if(Profile.status_change.age && Profile.status_change.zipcode || (Profile.status_change.work || Profile.status_change.about) ){
            Profile.status_change.total = true;
        }else {
            Profile.status_change.total = false;
        }
        console.log(Profile.status_change);
    },

    onclicksave: function(){
        var btn_save = $('.btn-control').find('.save');
        if(Profile.status_change.total){
            btn_save.removeClass('disable');
            // btn_save.one('click',function(){
            //     Profile.getDataUpLoad();
            //     Ajax.update_profile(Profile.params).then(function(data){
            //         var json = $.parseJSON(data);
            //         Profile.data = json;
            //         Profile.set_default_btn();
            //     });
            // });
        }else{
            btn_save.addClass('disable');
        }
        
    },

    getDataUpLoad: function(){
        
        var age = $('input.birthday').val(),
        work = $('input.work').val(),
        about = $('textarea.about').val(),
        zipcode = $('input.zip_code').val();
        Profile.params.age = age;
        Profile.params.work = work;
        Profile.params.about = about;
        Profile.params.zipcode = zipcode;
        
    },

    getDataDefaultUpLoad: function(){
        $('input.birthday').val(Profile.data.age);
        $('input.work').val(Profile.data.work);
        $('input.zip_code').val(Profile.data.zip);
        $('textarea.about').val(Profile.data.about);
        Profile.validZip();
    },

    onclickcancel: function(){
        var btn_cancel = $('.btn-control').find('.cancel');
        
        btn_cancel.removeClass('disable');
        btn_cancel.unbind();
        btn_cancel.on('click',function(){
            Profile.getDataDefaultUpLoad();
            Profile.set_default_btn();
        });
    },

    set_default_btn: function(){
        $('#user_setting').find('.btn-control .cancel').addClass('disable');
        $('#user_setting').find('.btn-control .save').addClass('disable');
    },

    showModalPhoto: function(){
        var target = $('#modal_change_avatar');

        target.modal('show');

    },
    edit_avatar: function(){
        var btn = $('.change_avatar .fa');
        btn.on('click',function(){
            $('#modal_change_avatar').modal({
                backdrop: true,
                keyboard: false
            });
            Profile.onchangeModalUpload();
        });
    },

    onchangeModalUpload: function(){
        $('.modal-backdrop.in').last().addClass('active');
        Profile.onbrowse();
        Profile.onCancel();
        Profile.onBackdrop();
    },

    onBackdrop: function(){
        $('#modal_change_avatar').on('hidden.bs.modal',function() {
            $('img.preview_image').attr('src','');
            $('img.preview_image').hide();
            $('.image-preview').find('p').show();
            $('.btn-control-modal').find('.save').addClass('disable');
            $('.preview_img').removeClass('active');
        });
    },

    onCancel: function(){
        var btn = $('.btn-control-modal').find('.cancel');
        btn.on('click',function(){
            $('#modal_change_avatar').modal('hide');
            $('img.preview_image').attr('src','');
            $('img.preview_image').hide();
            $('.image-preview').find('p').show();
            $('.btn-control-modal').find('.save').addClass('disable');
            $('.preview_img').removeClass('active');
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

    onChangeWork: function(){
        $('input.work').on('keyup',function(){
            if($('input.work').val() != Profile.data.work){
                Profile.status_change.work = true;
                Profile.OnTemplate();
            }else{
                Profile.status_change.work = false;
                Profile.set_default_btn();
            }

        });
    },

    onChangeAbout: function(){
        $('textarea.about').on('keyup',function(){
            if($('textarea.about').val() != Profile.data.about){
                Profile.status_change.about = true;
                Profile.OnTemplate();
            }else{
                Profile.status_change.about = false;
                Profile.set_default_btn();
            }

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
        $('.preview_img').addClass('active');
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

    getTemplateTitle: function(parent,target,callback){
        var template = _.template(target.html());
        var append_html = template({data: Profile.data});
        parent.append(append_html); 

        if(_.isFunction(callback)){
            callback();
        }
    },

    getTemplateUserInfo: function(parent,target,callback){
        var template = _.template(target.html());
        var append_html = template({data: Profile.data});
        parent.append(append_html); 

        if(_.isFunction(callback)){
            callback();
        }
    },

};