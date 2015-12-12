var ResetPass = {
    data:{},
    status_change:{
        new_pass:false,
        confirm_pass:false
    },
    initialize: function(){
        if(!isMobile){
            ResetPass.showModalResetPass();
        }
        ResetPass.onChangeNewPass();
        ResetPass.onChangeConfirmPass();
        ResetPass.onClickResetNetwrkLogo();
        if(isMobile){
            // ResetPass.hideHeaderFooter();
        }
    },

    showModalResetPass: function(){
        var parent = $('#reset-password');
        parent.modal({show: true});
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
    }
};