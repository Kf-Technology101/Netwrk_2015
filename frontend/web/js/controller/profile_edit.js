var ProfileEdit = {
    data:{},
    params:{
        first_name: '',
        last_name: '',
        /*user_name: '',
        email: '',*/
        gender: '',
        zip: 0,
        dob: 0,
        marital_status: '',
        work: '',
        education: '',
        country: '',
        state: '',
        city: '',
        hobbies: '',
        about: '',
        lat:'',
        lng:''
    },
    num_len:true,
    zip: true,
    status_change:{
        first_name: false,
        last_name: false,
        /*user_name: false,
        email: false,*/
        gender: false,
        zip: false,
        dob: false,
        marital_status: false,
        work: false,
        education: false,
        country: false,
        state: false,
        city: false,
        hobbies: false,
        about: false,
        total:false
    },
    state: 'Indiana',
    country: 'United States',
    modal: '',
    slider:'#profile_edit_slider',
    slider_hidden: "-400px",
    isOpenProfileEditSlider: false,
    profileEdit: $('.form-profile-edit'),
    initialize: function(){
        if(isMobile) {
            Default.SetAvatarUserDropdown();
            ProfileEdit.modal = $('.profile-edit-page');
        } else {
            ProfileEdit.modal = $('#modal_profile_edit');
            ProfileEdit.ShowModalProfileEdit();
        }

        ProfileEdit.resetProfileEdit();
        ProfileEdit.onClickBack();
        ProfileEdit.getProfileEdit();
        ProfileEdit.setProfileDatePicker();
        ProfileEdit.OnChangeMaritalStatus();
        ProfileEdit.validateZipCode();
        ProfileEdit.onChangeInputs();
        ProfileEdit.onClickSave();
        ProfileEdit.setDefaultBtn();
    },
    initializeSlider: function() {
        ProfileEdit.modal = $(ProfileEdit.slider);
        ProfileEdit.showProfileEditSlider();
        ProfileEdit.onClickCloseSliderBtn();

        //initialize script after slider open
        ProfileEdit.resetProfileEdit();
        ProfileEdit.onClickBack();
        ProfileEdit.getProfileEdit();
        ProfileEdit.setProfileDatePicker();
        ProfileEdit.OnChangeMaritalStatus();
        ProfileEdit.validateZipCode();
        ProfileEdit.onChangeInputs();
        ProfileEdit.onClickSave();
        ProfileEdit.setDefaultBtn();
    },

    OnChangeMaritalStatus: function(){
        var gender = ProfileEdit.modal.find('.marital-status .dropdown-menu li');
        var input_marital_status = ProfileEdit.modal.find('#maritalStatus');

        gender.unbind();
        gender.on('click',function(e){
            var text = $(e.currentTarget).text();
            input_marital_status.val(text);

            // Marital status change
            if(text != ProfileEdit.data.marital_status){
                ProfileEdit.status_change.marital_status = true;
                ProfileEdit.OnTemplate();
            } else {
                ProfileEdit.status_change.marital_status = false;
                ProfileEdit.setDefaultBtn();
            }
        });
    },

    resetProfileEdit: function(){
        ProfileEdit.profileEdit.html('');
    },

    onClickBack: function(){
        var parent = ProfileEdit.modal.find('.back-page span');

        parent.unbind();
        parent.click(function(){
            if(isMobile){
                window.location.href = baseUrl+ "/netwrk/profile-info";
            } else {
                $('.modal').modal('hide');
                //ProfileInfo.initialize();
                User_Profile.initializeSlider();
            }
        });
    },

    getProfileEdit: function(){
        var self = this,
            profile_edit = $('#profile_edit');

        Ajax.getProfileBasicInfo().then(function(data){
            var json = $.parseJSON(data);
            ProfileEdit.data = json;

            if(ProfileEdit.data.status == 1){
                ProfileEdit.params.first_name = json.first_name;
                ProfileEdit.params.last_name = json.last_name;
                ProfileEdit.params.user_name = json.user_name;
                ProfileEdit.params.email = json.email;
                ProfileEdit.params.gender = json.gender;
                ProfileEdit.params.zip = json.zip;
                ProfileEdit.params.dob = json.dob;
                ProfileEdit.params.marital_status = json.marital_status;
                ProfileEdit.params.work = json.work;
                ProfileEdit.params.education = json.education;
                ProfileEdit.params.country = json.country;
                ProfileEdit.params.state = json.state;
                ProfileEdit.params.city = json.city;
                ProfileEdit.params.hobbies = json.hobbies;
                ProfileEdit.params.about = json.about;

                ProfileEdit.getTemplateProfileEdit(ProfileEdit.profileEdit,profile_edit);
                ProfileEdit.setGenderRadio();
            }
        });
    },

    getTemplateProfileEdit: function(parent,target,callback){
        var template = _.template(target.html());
        var append_html = template({data: ProfileEdit.data});
        parent.append(append_html);

        if(_.isFunction(callback)){
            callback();
        }
    },

    OnTemplate: function(){
        var self = this;
        self.checkStatusChange();
        self.onClickSave();
        self.onClickCancel();
    },

    checkStatusChange: function(){
        if(ProfileEdit.status_change.first_name || ProfileEdit.status_change.last_name || ProfileEdit.status_change.gender || ProfileEdit.status_change.marital_status || ProfileEdit.status_change.work || ProfileEdit.status_change.education || ProfileEdit.status_change.hobbies || ProfileEdit.status_change.about) {
            ProfileEdit.status_change.total = true;
        } else if(ProfileEdit.status_change.first_name == false && ProfileEdit.status_change.last_name == false && ProfileEdit.status_change.gender == false && ProfileEdit.status_change.marital_status == false && ProfileEdit.status_change.work == false && ProfileEdit.status_change.education == false && ProfileEdit.status_change.hobbies == false && ProfileEdit.status_change.about == false) {
            ProfileEdit.status_change.total = false;
        }

        /*if(ProfileEdit.status_change.first_name && ProfileEdit.status_change.last_name && ProfileEdit.status_change.gender && ProfileEdit.status_change.work && ProfileEdit.status_change.education && ProfileEdit.status_change.hobbies && ProfileEdit.status_change.about) {

        } else if(ProfileEdit.status_change.first_name == false && ProfileEdit.status_change.last_name == false && ProfileEdit.status_change.gender == false && ProfileEdit.status_change.work == false && ProfileEdit.status_change.education == false && ProfileEdit.status_change.hobbies == false && ProfileEdit.status_change.about == false) {
            ProfileEdit.status_change.total = false;
        }*/
    },

    onClickSave: function () {
        var btn = ProfileEdit.modal.find('.btn-control .save');

        btn.unbind();

        if(ProfileEdit.status_change.total) {
            btn.removeClass('disable');
            btn.on('click',function(){
                ProfileEdit.getDataUpLoad();
                Ajax.updateProfileEdit(ProfileEdit.params).then(function(data){
                    var json = $.parseJSON(data);

                    ProfileEdit.data = json;
                    ProfileEdit.setDefaultBtn();
                });
                ProfileEdit.setDefaultBtn();
            });
        } else {
            btn.addClass('disable');
        }
    },

    onClickCancel: function () {
        var btn = ProfileEdit.modal.find('.btn-control .cancel');

        btn.unbind();
        if(ProfileEdit.status_change.total){
            btn.removeClass('disable');
            btn.on('click',function(){
                ProfileEdit.initialize();
                ProfileEdit.setDefaultBtn();
            });
        } else {
            btn.addClass('disable');
        }
    },

    setDefaultBtn: function(){
        ProfileEdit.modal.find('.btn-control .cancel').addClass('disable');
        ProfileEdit.modal.find('.btn-control .save').addClass('disable');
    },

    setGenderRadio: function(){
        $.each($('input.input_radio', ProfileEdit.modal),function(i,e){
            if (ProfileEdit.params.gender == $(e).val()) {
                $(e).prop('checked', true);
            }

            $(e).unbind();
            $(e).on('click',function(){
                if (ProfileEdit.params.gender != $(e).val()) {
                    ProfileEdit.status_change.gender = true;
                } else if(ProfileEdit.params.gender == $(e).val()){
                    ProfileEdit.status_change.gender = false;
                }

                $(e).bind();
                ProfileEdit.params.gender = $(e).val();

                ProfileEdit.OnTemplate();
            })
        });
    },

    validateZipCode: function(){
        // Profile.apiZipcode();
        $('input.home_zip_code', ProfileEdit.modal).on('keyup',function(e){
            var zipcode_current = parseInt($('input.home_zip_code').val());
            if (zipcode_current > 9999 && ProfileEdit.params.zip == ProfileEdit.data.zip && !ProfileEdit.zip){
                ProfileEdit.params.zip = zipcode_current;
                ProfileEdit.apiZipcode(zipcode_current);
            }else if (zipcode_current > 9999 && ProfileEdit.params.zip != ProfileEdit.data.zip && !ProfileEdit.zip){
                ProfileEdit.params.zip = zipcode_current;
                ProfileEdit.apiZipcode(zipcode_current);
            }else if(zipcode_current < 9999){
                ProfileEdit.zip = false;
                ProfileEdit.invalidZip();
                ProfileEdit.status_change.zip = false;
                ProfileEdit.OnTemplate();
            }
        });
    },

    apiZipcode: function(zipcode){
        // var zipcode = ProfileEdit.params.zip;
        // var zipcode = 46601;
        ProfileEdit.zip = true;
        $.getJSON("http://api.zippopotam.us/us/"+zipcode ,function(data){
            //allow zipcode from united states only
            if (data.country == ProfileEdit.country){
                ProfileEdit.params.lat = data.places[0].latitude;
                ProfileEdit.params.lng = data.places[0].longitude;
                ProfileEdit.params.country = data.country;
                ProfileEdit.params.state = data.places[0].state;
                ProfileEdit.params.city = data.places[0]['place name'];

                // Replace values of country, state and city
                ProfileEdit.modal.find('#country').val(ProfileEdit.params.country);
                ProfileEdit.modal.find('#state').val(ProfileEdit.params.state);
                ProfileEdit.modal.find('#city').val(ProfileEdit.params.city);

                ProfileEdit.validZip();
                ProfileEdit.status_change.zip = true;
                ProfileEdit.OnTemplate();
            }else{
                ProfileEdit.invalidZip();
                ProfileEdit.status_change.zip = false;
                ProfileEdit.OnTemplate();
            }
        }).fail(function(jqXHR) {
            if (jqXHR.status == 404) {

                ProfileEdit.status_change.zip = false;
                ProfileEdit.OnTemplate();
                ProfileEdit.invalidZip();
            }
        });
    },

    invalidZip: function(){
        ProfileEdit.status_change.zip = false;

        var parent = $('input.home_zip_code', ProfileEdit.modal).parent();
        parent.addClass('alert_validate');
        if(parent.find('span').size() == 0){
            parent.append('<span>*Invalid zip code</span>');
        }
        ProfileEdit.OnTemplate();
    },

    validZip: function(){
        ProfileEdit.status_change.zip = true;
        var parent = $('input.home_zip_code', ProfileEdit.modal).parent();
        if(parent.hasClass('alert_validate')){
            parent.removeClass('alert_validate');
            parent.find('span').remove();
        }
    },

    setProfileDatePicker: function(){
        var dt = new Date();
        dt.setFullYear(new Date().getFullYear()-18);

        $('input.dob', ProfileEdit.modal).datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            viewMode: "years",
            endDate : dt
            // startDate: '-3d',
        });

        $('.datepicker-dropdown').css('top',75);

        $('input.dob', ProfileEdit.modal).on('changeDate',function(e){
            $(this).datepicker('hide');
            $(this).parent().removeClass('alert_validate');

        });

        $('input.dob', ProfileEdit.modal).on('change',function(e){
            ProfileEdit.checkDate(e.target.value);
        });
    },

    checkDate: function(value){
        if (isDate(value)) {
            $('input.dob', ProfileEdit.modal).parent().removeClass('alert_validate');
            ProfileEdit.status_change.dob = true;
            ProfileEdit.OnTemplate();
        }else{
            ProfileEdit.status_change.dob = false;
            ProfileEdit.OnTemplate();
            $('input.dob', ProfileEdit.modal).parent().addClass('alert_validate');
        }
    },

    onChangeInputs: function(){
        // First name change
        $('input[name="first_name"]',ProfileEdit.modal).on('keyup',function(){
            if($(this).val() != ProfileEdit.data.first_name){
                ProfileEdit.status_change.first_name = true;
                ProfileEdit.OnTemplate();
            }else{
                ProfileEdit.status_change.first_name = false;
                ProfileEdit.setDefaultBtn();
            }
        });

        // Last name change
        $('input[name="last_name"]',ProfileEdit.modal).on('keyup',function(){
            if($(this).val() != ProfileEdit.data.last_name){
                ProfileEdit.status_change.last_name = true;
                ProfileEdit.OnTemplate();
            }else{
                ProfileEdit.status_change.last_name = false;
                ProfileEdit.setDefaultBtn();
            }
        });

        // Gender change
        $('input[name="gender"]',ProfileEdit.modal).on('keyup',function(){
            if($(this).val() != ProfileEdit.data.gender){
                ProfileEdit.status_change.gender = true;
                ProfileEdit.OnTemplate();
            }else{
                ProfileEdit.status_change.gender = false;
                ProfileEdit.setDefaultBtn();
            }
        });

        // Work change
        $('input[name="work"]',ProfileEdit.modal).on('keyup',function(){
            if($(this).val() != ProfileEdit.data.work){
                ProfileEdit.status_change.work = true;
                ProfileEdit.OnTemplate();
            }else{
                ProfileEdit.status_change.work = false;
                ProfileEdit.setDefaultBtn();
            }
        });

        // Education change
        $('input[name="education"]',ProfileEdit.modal).on('keyup',function(){
            if($(this).val() != ProfileEdit.data.education){
                ProfileEdit.status_change.education = true;
                ProfileEdit.OnTemplate();
            }else{
                ProfileEdit.status_change.education = false;
                ProfileEdit.setDefaultBtn();
            }
        });

        // Hobbies change
        $('input[name="hobbies"]',ProfileEdit.modal).on('keyup',function(){
            if($(this).val() != ProfileEdit.data.hobbies){
                ProfileEdit.status_change.hobbies = true;
                ProfileEdit.OnTemplate();
            }else{
                ProfileEdit.status_change.hobbies = false;
                ProfileEdit.setDefaultBtn();
            }
        });

        // Bio changed
        $('textarea[name="about"]', ProfileEdit.modal).on('keyup',function(){
            if($(this).val() != ProfileEdit.data.about){
                ProfileEdit.status_change.about = true;
                ProfileEdit.OnTemplate();
            }else{
                ProfileEdit.status_change.about = false;
                ProfileEdit.setDefaultBtn();
            }
        });
    },

    getDataUpLoad: function() {
        var first_name = $('input[name="first_name"]',ProfileEdit.modal).val(),
            last_name = $('input[name="last_name"]',ProfileEdit.modal).val(),
            gender = $('input[name="gender"]',ProfileEdit.modal).val(),
            zip = $('input[name="zip"]',ProfileEdit.modal).val(),
            dob = $('input[name="dob"]',ProfileEdit.modal).val(),
            marital_status = $('input[name="marital_status"]',ProfileEdit.modal).val(),
            work = $('input[name="work"]',ProfileEdit.modal).val(),
            education = $('input[name="education"]',ProfileEdit.modal).val(),
            country = $('input[name="country"]',ProfileEdit.modal).val(),
            state = $('input[name="state"]',ProfileEdit.modal).val(),
            city = $('input[name="city"]',ProfileEdit.modal).val(),
            hobbies = $('input[name="hobbies"]',ProfileEdit.modal).val(),
            about = $('textarea[name="about"]',ProfileEdit.modal).val();

        ProfileEdit.params.first_name = first_name;
        ProfileEdit.params.last_name = last_name;
        /*ProfileEdit.params.user_name = ;
        ProfileEdit.params.email = ;*/
        ProfileEdit.params.gender = gender;
        ProfileEdit.params.zip = zip;
        ProfileEdit.params.dob = dob;
        ProfileEdit.params.marital_status = marital_status;
        ProfileEdit.params.work = work;
        ProfileEdit.params.education = education;
        ProfileEdit.params.country = country;
        ProfileEdit.params.state = state;
        ProfileEdit.params.city = city;
        ProfileEdit.params.hobbies = hobbies;
        ProfileEdit.params.about = about;
    },

    ShowModalProfileEdit: function(){
        var self = this;

        ProfileEdit.modal.modal({
            backdrop: true,
            keyboard: false
        });

        Common.CustomScrollBar(ProfileEdit.modal.find('.modal-body'));

        ProfileEdit.modal.on('hidden.bs.modal',function() {
            ProfileEdit.modal.modal('hide');
        });
        $('.modal-backdrop.in').click(function(e) {
            ProfileEdit.modal.modal('hide');
        });
    },
    showProfileEditSlider: function() {
        //display password settling slider on right side
        ProfileEdit.closeOtherSlider();
        if ($(ProfileEdit.slider).css('right') == ProfileEdit.slider_hidden) {
            $(ProfileEdit.slider).animate({
                "right": "0"
            }, 500);

            Common.CustomScrollBar($(ProfileEdit.slider));
            ProfileEdit.activeResponsivePasswordSettingSlider();
        } else {
            $(ProfileEdit.slider).animate({
                "right": ProfileEdit.slider_hidden
            }, 500);

            ProfileEdit.deactiveResponsivePasswordSettingSlider();
        }
    },
    activeResponsivePasswordSettingSlider: function() {
        var width = $( window ).width();
        $(".modal").addClass("responsive-profile-slider");
        if (width <= 1250) {
            $('#btn_meet').css('z-index', '1050');
        }

        ProfileEdit.isOpenProfileEditSlider = true;
        $('.box-navigation').css({'left': '', 'right' : '395px'});
        $('#btn_my_location').css({'left': '', 'right' : '335px'});
        $('#btn_meet').css({'left': '', 'right' : '335px'});
    },
    deactiveResponsivePasswordSettingSlider: function() {
        var width = $( window ).width();
        $(".modal").removeClass("responsive-profile-slider");

        if (width <= 1250) {
            $('#btn_meet').css('z-index', '10000');
        }

        ProfileEdit.isOpenProfileEditSlider = false;
        $('.box-navigation').css({'left': '', 'right' : '75px'});
        $('#btn_my_location').css({'left': '', 'right' : '15px'});
        $('#btn_meet').css({'left': '', 'right' : '15px'});
    },
    closeOtherSlider: function() {
        //close profile slider if it is already open
        if(User_Profile.params.isOpenProfileSlider) {
            User_Profile.initializeSlider();
        }
    },
    onClickCloseSliderBtn: function() {
        var context = ProfileEdit.slider;
        var target = $('.slider-close-btn', context);
        target.unbind();
        target.click(function() {
            console.log('in onClickCloseSliderBtn clicked');
            if(ProfileEdit.isOpenProfileEditSlider) {
                ProfileEdit.showProfileEditSlider();
            }
        });
    }
   /* onClickEditProfile: function(){
        var parent = ProfileInfo.modal.find('.edit-profile');

        parent.unbind();
        parent.click(function(){
            if(isMobile){
            } else {
                $('.modal').modal('hide');
                ProfileEdit.initialize();
            }
        });
    },

    getProfileBasicInfo: function(){
        var self = this,
            profile_basic_info = $('#profile_basic_info'),
            profile_bio = $('#profile_bio');

        Ajax.getProfileBasicInfo().then(function(data){
            var json = $.parseJSON(data);
            ProfileInfo.data = json;

            if(ProfileInfo.data.status == 1){
                ProfileInfo.params.email = json.email;
                ProfileInfo.params.gender = json.gender;
                ProfileInfo.params.zip = json.zip;
                ProfileInfo.params.dob = json.dob;
                ProfileInfo.params.marital_status = json.marital_status;
                ProfileInfo.params.work = json.work;
                ProfileInfo.params.education = json.education;
                ProfileInfo.params.hobbies = json.hobbies;
                ProfileInfo.params.about = json.about;

                ProfileInfo.getTemplateProfileBasicInfo(ProfileInfo.profileBasicInfo,profile_basic_info);
                ProfileInfo.getTemplateProfileBasicInfo(ProfileInfo.profileBio,profile_bio);
            }
        });
    },

    getTemplateProfileBasicInfo: function(parent,target,callback){
        var template = _.template(target.html());
        var append_html = template({data: ProfileInfo.data});
        parent.append(append_html);

        if(_.isFunction(callback)){
            callback();
        }
    },*/
};