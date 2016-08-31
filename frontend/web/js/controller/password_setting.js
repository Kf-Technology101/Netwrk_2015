var Password_Setting = {
    data:'',
    modal:'',
    slider:'#password_setting_slider',
    slider_hidden: "-400px",
    isOpenPasswordSettingSlider: false,
    form_id:'#password_setting_form',
    validate:true,
    data_validate:'',
    initialize: function(){
        if(isMobile) {
            Default.SetAvatarUserDropdown();
            Password_Setting.modal = $('.profile-password-settings');
        } else {
            Password_Setting.modal = $('#modal_password_setting');
            Password_Setting.resetPage();
            Password_Setting.ShowModalPasswordSetting();
        }

        Password_Setting.onClickBack();
        Password_Setting.OnClickUpdate();
        Password_Setting.OnClickReset();
    },
    initializeSlider: function() {
        Password_Setting.modal = $('#modal_password_setting');
        Password_Setting.showPasswordSettingSlider();
        Password_Setting.onClickCloseSliderBtn();
        Password_Setting.OnClickReset();
        Password_Setting.OnClickUpdate();

    },
    onClickBack: function(){
        var parent = Password_Setting.modal.find('.back-page span');

        parent.unbind();
        parent.click(function(){
            if(isMobile){
                window.location.href = baseUrl+ "/netwrk/profile";
            } else {
                $('.modal').modal('hide');
                User_Profile.initialize();
            }
        });
    },

    resetPage: function() {
        $.each($(Password_Setting.form_id).find('.form-group'), function(){
            $(this).removeClass('has-error');
            $(this).find('.help-block').text('');
        });

        $.each($(Password_Setting.form_id).find('input'), function(){
            $(this).val('');
        });
    },

    ShowErrorValidate: function(validate){
        $.each(Password_Setting.data_validate.data,function(i,e){
            if(validate) {
                var target = $(Password_Setting.form_id).find('.field-'+ validate);
                if(validate == i) {
                    target.removeClass('has-success').addClass('has-error');
                    target.find('.help-block').text(e);
                    return false;
                }
            } else {
                var target = $(Password_Setting.form_id).find('.field-'+ i);
                target.removeClass('has-success').addClass('has-error');
                target.find('.help-block').text(e);
            }
        });
    },

    OnClickUpdate: function(){
        var btn = Password_Setting.modal.find('.update');

        btn.unbind();
        btn.on('click',function(){
            Ajax.passwordSetting($(Password_Setting.form_id)).then(function(data){
                Password_Setting.data_validate = $.parseJSON(data);

                if(Password_Setting.data_validate.status == 0) {
                    Password_Setting.data_validate = $.parseJSON(data);
                    Password_Setting.ShowErrorValidate();
                } else {
                    Password_Setting.resetPage();

                    var success_msg = $(Password_Setting.form_id).find('#password_setting_success');

                    success_msg.removeClass('hide').text(Password_Setting.data_validate.data);

                    setTimeout(function(){
                        success_msg.addClass('hide');
                    },3000);
                }
            });
        });
    },

    OnClickReset: function(){
        var btn = Password_Setting.modal.find('.reset');

        btn.unbind();
        btn.on('click',function(){
            Password_Setting.resetPage();
        });
    },

    ShowModalPasswordSetting: function(){
        var self = this;

        Password_Setting.modal.modal({
            backdrop: true,
            keyboard: false
        });

        Password_Setting.modal.on('hidden.bs.modal',function() {
            Password_Setting.modal.modal('hide');
        });
        $('.modal-backdrop.in').click(function(e) {
            Password_Setting.modal.modal('hide');
        });
    },
    showPasswordSettingSlider: function() {
      //display password settling slider on right side
        Password_Setting.closeOtherSlider();
        if ($(Password_Setting.slider).css('right') == Password_Setting.slider_hidden) {
            $(Password_Setting.slider).animate({
                "right": "0"
            }, 500);

            Common.CustomScrollBar($('#password_setting_slider'));
            Password_Setting.activeResponsivePasswordSettingSlider();
        } else {
            $(Password_Setting.slider).animate({
                "right": Password_Setting.slider_hidden
            }, 500);

            Password_Setting.deactiveResponsivePasswordSettingSlider();
        }
    },
    activeResponsivePasswordSettingSlider: function() {
        var width = $( window ).width();
        $(".modal").addClass("responsive-profile-slider");
        if (width <= 1250) {
            $('#btn_meet').css('z-index', '1050');
        }

        Password_Setting.isOpenPasswordSettingSlider = true;
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

        Password_Setting.isOpenPasswordSettingSlider = false;
        $('.box-navigation').css({'left': '', 'right' : '75px'});
        $('#btn_my_location').css({'left': '', 'right' : '15px'});
        $('#btn_meet').css({'left': '', 'right' : '15px'});
    },
    closeOtherSlider: function() {
        //close profile slider if it is already open
        if(User_Profile.params.isOpenProfileSlider) {
            User_Profile.onShowProfileSlider();
        }
    },
    onClickCloseSliderBtn: function() {
        var context = Password_Setting.slider;
        var target = $('.slider-close-btn', context);
        target.unbind();
        target.click(function() {
            console.log('slide close clicked');
            if(Password_Setting.isOpenPasswordSettingSlider) {
                Password_Setting.showPasswordSettingSlider();
            }
        });
    }
};