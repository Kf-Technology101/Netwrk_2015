var ProfileInfo = {
    data:{},
    params:{
        email: '',
        gender: '',
        zip: 0,
        dob: 0,
        marital_status: 'Single',
        work: '',
        education: '',
        hobbies: '',
        bio: '',
        lat:'',
        lng:''
    },
    num_len:true,
    zipcode: true,
    status_change:{
        age:true,
        zipcode: true,
        work: false,
        about:false,
        total:false
    },
    state: 'Indiana',
    modal: '',
    slider:'#profile_info_slider',
    slider_hidden: "-400px",
    isOpenProfileInfoSlider: false,
    profileBasicInfo: $('.profile-basic-info'),
    profileBio : $('.profile-bio'),

    initialize: function(){
        if(isMobile) {
            Default.SetAvatarUserDropdown();
            ProfileInfo.modal = $('.profile-info-page');
        } else {
            ProfileInfo.modal = $('#modal_profile_info');
            ProfileInfo.ShowModalProfileInfo();
        }

        ProfileInfo.resetProfileInfo();
        ProfileInfo.onClickBack();
        ProfileInfo.onClickEditProfile();
        ProfileInfo.getProfileBasicInfo();
    },
    initializeSlider: function() {
        ProfileInfo.modal = $('#profile_info_slider');
        ProfileInfo.resetProfileInfo();
        ProfileInfo.onClickBack();
        ProfileInfo.showProfileInfoSlider();
        ProfileInfo.onClickCloseSliderBtn();
        ProfileInfo.getProfileBasicInfo();
    },
    resetProfileInfo: function(){
        ProfileInfo.profileBasicInfo.html('');
        ProfileInfo.profileBio.html('');
    },

    onClickBack: function(){
        var parent = ProfileInfo.modal.find('.back-page span');

        parent.unbind();
        parent.click(function(){
            if(isMobile){
                window.location.href = baseUrl+ "/netwrk/profile";
            } else {
                $('.modal').modal('hide');
                //User_Profile.initialize();
                User_Profile.initializeSlider();
            }
        });
    },

    onClickEditProfile: function(){
        var parent = ProfileInfo.modal.find('.edit-profile');

        parent.unbind();
        parent.click(function(){
            if(isMobile){
                window.location.href = baseUrl+ "/netwrk/profile-edit";
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
                ProfileInfo.params.bio = json.bio;

                ProfileInfo.getTemplateProfileBasicInfo(ProfileInfo.profileBasicInfo,profile_basic_info);
                ProfileInfo.getTemplateProfileBasicInfo(ProfileInfo.profileBio,profile_bio);
            }
        });
    },

    getTemplateProfileBasicInfo: function(parent,target,callback){
        parent.html('');
        var template = _.template(target.html());
        var append_html = template({data: ProfileInfo.data});
        parent.append(append_html);

        if(_.isFunction(callback)){
            callback();
        }
    },

    ShowModalProfileInfo: function(){
        var self = this;

        ProfileInfo.modal.modal({
            backdrop: true,
            keyboard: false
        });

        Common.CustomScrollBar(ProfileInfo.modal.find('.modal-body'));

        ProfileInfo.modal.on('hidden.bs.modal',function() {
            ProfileInfo.modal.modal('hide');
        });
        $('.modal-backdrop.in').click(function(e) {
            ProfileInfo.modal.modal('hide');
        });
    },
    showProfileInfoSlider: function() {
        //display password settling slider on right side
        ProfileInfo.closeOtherSlider();
        if ($(ProfileInfo.slider).css('right') == ProfileInfo.slider_hidden) {
            $(ProfileInfo.slider).animate({
                "right": "0"
            }, 500);

            Common.CustomScrollBar($('#password_setting_slider'));
            ProfileInfo.activeResponsivePasswordSettingSlider();
        } else {
            $(ProfileInfo.slider).animate({
                "right": ProfileInfo.slider_hidden
            }, 500);

            ProfileInfo.deactiveResponsivePasswordSettingSlider();
        }
    },
    activeResponsivePasswordSettingSlider: function() {
        var width = $( window ).width();
        $(".modal").addClass("responsive-profile-slider");
        if (width <= 1250) {
            $('#btn_meet').css('z-index', '1050');
        }

        ProfileInfo.isOpenProfileInfoSlider = true;
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

        ProfileInfo.isOpenProfileInfoSlider = false;
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
        var context = ProfileInfo.slider;
        var target = $('.slider-close-btn', context);
        target.unbind();
        target.click(function() {
            if(ProfileInfo.isOpenProfileInfoSlider) {
                ProfileInfo.showProfileInfoSlider();
            }
        });
    }

};