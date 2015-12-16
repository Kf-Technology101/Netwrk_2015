var ForgotPass = {
    data:'',
    modal:'#forgot-password',
    form_id:'#forgot-form',
    status_change:{
        email: false
    },
    email_valid: false,
    initialize: function(){
        if(isMobile){
            Default.hideHeaderFooter();
            ForgotPass.onClickForgotNetwrkLogo();
        }else{
            ForgotPass.OnShowModalForgotPass();
            ForgotPass.OnHideModalForgotPass();
            ForgotPass.showModalForgotPass();
            // ForgotPass.OnAfterValidateForm();
            // ForgotPass.OnBeforeSubmitForm();
            ForgotPass.onClickSubmit();
        }
        // ForgotPass.onChangeEmail();
        ForgotPass.OnEventEnterForm();
    },

    OnEventEnterForm: function(){
        var btn = $(ForgotPass.modal).find('.send-email');
        $(ForgotPass.modal).find(ForgotPass.form_id).keypress(function( event ) {
            if ( event.which == 13 ) {
                btn.trigger('click');
            }
        });
    },

    onClickSubmit: function(){
        var btn = $(ForgotPass.modal).find('.send-email');

        btn.unbind();
        btn.on('click',function(){
            $(ForgotPass.form_id).submit(function(e){
                e.preventDefault();
            });

            Ajax.forgot_password(ForgotPass.form_id).then(function(res){
                ForgotPass.data = $.parseJSON(res);
                console.log(ForgotPass.data);
                if(ForgotPass.data.status == 1){
                    $(ForgotPass.modal).find('.modal-body').hide();
                    $(ForgotPass.modal).find('.alert.alert-success').show();
                    $(ForgotPass.modal).find('.alert.alert-success p').text(ForgotPass.data.message);
                }else{
                    ForgotPass.OnShowError();
                }
            });
        });
    },

    OnShowError: function(){
        var target = $(ForgotPass.modal).find('.form-group');
            target.removeClass('has-success').addClass('has-error');
            target.find('.help-block').text(ForgotPass.data.data.email);
    },

    showModalForgotPass: function(){
        var parent = $(ForgotPass.modal);
        parent.modal({show: true,keyboard:false});
    },

    onChangeEmail: function(){
        if(isMobile){
            var target = $(ForgotPass.modal).find('.container input.email');
        } else {
            var target = $(ForgotPass.modal).find('.modal-body input.email');
        }
        target.on('keyup',function(){
            if(target.val() != ''){
                ForgotPass.status_change.email = true;
                ForgotPass.onTemplate();
            }else{
                ForgotPass.status_change.email = false;
                ForgotPass.setDefaultBtnSendEmail();
            }

        });
    },

    setDefaultBtnSendEmail: function(){
        if(isMobile){
            $(ForgotPass.modal).find('.container .send-email').addClass('disable');
        } else {
            $(ForgotPass.modal).find('.modal-body .send-email').addClass('disable');
        }
    },

    onTemplate: function(){
        var self = this;
        self.onClickSendEmail();
    },

    onClickSendEmail: function(){
        if(isMobile){
            var btn_send_email = $(ForgotPass.modal).find('.container .send-email');
        } else {
            var btn_send_email = $(ForgotPass.modal).find('.modal-body .send-email');
        }
        if(ForgotPass.status_change.email){
            btn_send_email.removeClass('disable');
            // btn_save.one('click',function(){
            //     Profile.getDataUpLoad();
            //     Ajax.update_profile(Profile.params).then(function(data){
            //         var json = $.parseJSON(data);
            //         Profile.data = json;
            //         Profile.set_default_btn();
            //     });
            // });
        }else{
            btn_send_email.addClass('disable');
        }

    },

    onClickForgotNetwrkLogo: function(){
        var target = $(ForgotPass.modal).find('.title img');
        target.unbind();
        target.on('click', function(){
            target.bind();
            window.location.href = baseUrl;
        });
    },



    OnShowModalForgotPass: function(){
        $(ForgotPass.modal).on('shown.bs.modal',function(e) {
            var target = $('.modal-backdrop.in');
            target.addClass('active');
            $('.menu_top').addClass('deactive');
            $('#btn_meet').addClass('deactive');
        });
    },

    OnHideModalForgotPass: function(){
        $(ForgotPass.modal).on('hidden.bs.modal',function(e) {
            var target = $('.modal-backdrop.in');
            target.addClass('active');
            $('.menu_top').removeClass('deactive');
            $('#btn_meet').removeClass('deactive');
        });
    },
};