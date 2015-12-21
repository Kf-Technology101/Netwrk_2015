var ResetPass = {
    data:{},
    params:{
        key:'',
        newPassword:'',
        newPasswordConfirm:''
    },
    modal:'#reset-password',
    form_id:'#reset-form',
    status_change:{
        new_pass:false,
        confirm_pass:false
    },
    initialize: function(){
        if(isMobile){
            ResetPass.onClickResetNetwrkLogo();
        }else{
            ResetPass.OnShowModalResetPass();
            ResetPass.OnHideModalResetPass();
            ResetPass.showModalResetPass();
            ResetPass.OnValidateNewPasswordConfirm();
            ResetPass.CheckKeyValidate();
            ResetPass.OnEventEnterForm();
            ResetPass.OnSubmitReset();
        }
    },

    OnEventEnterForm: function(){
        var btn = $(ResetPass.modal).find('.reset');
        $(ResetPass.modal).find(ResetPass.form_id).keypress(function( event ) {
            if ( event.which == 13 ) {
                btn.trigger('click');
            }
        });
    },

    OnValidateNewPasswordConfirm: function(){
        var target = $(ResetPass.modal).find('.field-user-newpasswordconfirm');
            newpass = $(ResetPass.modal).find('input#user-newpassword').val();

        target.find('input').unbind();

        target.find('input').on('change',function(){
            var newpass = $(ResetPass.modal).find('input#user-newpassword').val();
            if(target.find('input').val() === newpass){
                target.addClass('has-success-disable');
            }else{
                target.removeClass('has-success-disable');
            }
        });

    },
    OnSubmitReset: function(){
        var btn = $(ResetPass.modal).find('.reset');
        btn.unbind();

        btn.on('click',function(){
            ResetPass.params.key = isResetPassword;
            ResetPass.params.newPassword = $(ResetPass.modal).find('input#user-newpassword').val();
            ResetPass.params.newPasswordConfirm = $(ResetPass.modal).find('input#user-newpasswordconfirm').val();
            Ajax.reset_password(ResetPass.params).then(function(data){
                var json = $.parseJSON(data);
                console.log(json.status);
                if(json.status == 1){
                    $(ResetPass.modal).modal('hide');
                    Login.initialize();
                }else{
                    ResetPass.OnShowError();
                }
            });
        });
    },

    CheckKeyValidate: function(){
        if(isInvalidKey){
            $(ResetPass.modal).find('form').hide();
            $(ResetPass.modal).find('.alert-danger').show();
        }
    },
    CheckSessionResetPassword: function(){
        if(isResetPassword){
            ResetPass.initialize();
        }
    },

    showModalResetPass: function(){
        var parent = $('#reset-password');
        parent.modal({show: true,keyboard:false});
    },

    onTemplate: function(){
        var self = this;
        self.onClickReset();
    },

    onChangeNewPass: function(){
        if(!isMobile){
            var target = $('#reset-password').find('.modal-body input.new-pass');
        } else {
            var target = $('#reset-password').find('.container input.new-pass');
        }
        target.on('keyup',function(){
            if(target.val() != ''){
                ResetPass.status_change.new_pass = true;
                ResetPass.onTemplate();
            }else{
                ResetPass.status_change.new_pass = false;
                ResetPass.setDefaultBtnReset();
            }

        });
    },

    onChangeConfirmPass: function(){
        if(!isMobile){
            var target = $('#reset-password').find('.modal-body input.confirm-pass');
        } else {
            var target = $('#reset-password').find('.container input.confirm-pass');
        }
        target.on('keyup',function(){
            if(target.val() != ''){
                ResetPass.status_change.confirm_pass = true;
                ResetPass.onTemplate();
            }else{
                ResetPass.status_change.confirm_pass = false;
                ResetPass.setDefaultBtnReset();
            }

        });
    },

    onClickReset: function(){
        if(isMobile){
            var btn_reset = $('.container').find('.reset');
        } else {
            var btn_reset = $('#reset-password').find('.modal-body .reset');
        }
        
        if(ResetPass.status_change.new_pass && ResetPass.status_change.confirm_pass){
            btn_reset.removeClass('disable');
            // btn_save.one('click',function(){
            //     Profile.getDataUpLoad();
            //     Ajax.update_profile(Profile.params).then(function(data){
            //         var json = $.parseJSON(data);
            //         Profile.data = json;
            //         Profile.set_default_btn();
            //     });
            // });
        }else{
            btn_reset.addClass('disable');
        }
        
    },

    setDefaultBtnReset: function(){
        if(isMobile){
            $('#reset-password').find('.container .reset').addClass('disable');
        } else {
            $('#reset-password').find('.modal-body .reset').addClass('disable');
        }
    },

    onClickResetNetwrkLogo: function(){
        var target = $('#reset-password').find('.title img');
        target.unbind();
        target.on('click', function(){
            target.bind();
            window.location.href = baseUrl;
        });
    },

    hideHeaderFooter: function(){
        $('.navbar-fixed-top').hide();
        $('.navbar-fixed-bottom').hide();
    },

    OnShowModalResetPass: function(){
        $(ResetPass.modal).on('shown.bs.modal',function(e) {
            $(ResetPass.modal).find('input')[1].focus();
            var target = $('.modal-backdrop.in');
            target.addClass('active');
            $('.menu_top').addClass('deactive');
            $('#btn_meet').addClass('deactive');
        });
    },
    OnHideModalResetPass: function(){
        $(ResetPass.modal).on('hidden.bs.modal',function(e) {
            var target = $('.modal-backdrop.in');
            target.addClass('active');
            $('.menu_top').removeClass('deactive');
            $('#btn_meet').removeClass('deactive');
        });
    },
};