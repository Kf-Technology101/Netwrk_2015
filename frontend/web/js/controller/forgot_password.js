var ForgotPass = {
    data:{},
    status_change:{
        email: false
    },
    initialize: function(){
        if(!isMobile){
            ForgotPass.showModalForgotPass();
        }
        ForgotPass.onChangeEmail();
        ForgotPass.onClickForgotNetwrkLogo();
        if(isMobile){
            // ForgotPass.hideHeaderFooter();
        }
    },

    showModalForgotPass: function(){
        var parent = $('#forgot-password');
        parent.modal({show: true});
    },

    onChangeEmail: function(){
        if(!isMobile){
            var target = $('#forgot-password').find('.modal-body input.email');
        } else {
            var target = $('#forgot-password').find('.container input.email');
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
            $('#forgot-password').find('.container .send-email').addClass('disable');
        } else {
            $('#forgot-password').find('.modal-body .send-email').addClass('disable');
        }
    },

    onTemplate: function(){
        var self = this;
        self.onClickSendEmail();
        
    },

    onClickSendEmail: function(){
        if(isMobile){
            var btn_send_email = $('#forgot-password').find('.container .send-email');
        } else {
            var btn_send_email = $('#forgot-password').find('.modal-body .send-email');
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
        var target = $('#forgot-password').find('.title img');
        target.unbind();
        target.on('click', function(){
            target.bind();            
            window.location.href = baseUrl; 
        });
    },

    hideHeaderFooter: function(){
        $('.navbar-fixed-top').hide();
        $('.navbar-fixed-bottom').hide();
    }
};