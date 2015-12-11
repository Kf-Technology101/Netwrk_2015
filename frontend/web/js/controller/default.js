var Default ={
    initialize: function() {
        var self = this;
        if(isMobile){
            self._eventClickMeetBtnMobile();
        }else{
            $('#btn_meet').show();
            self._eventClickMeetBtn();
            ChatInbox.initialize();
            Default.forgotPass(); // test function
            Default.resetPass(); // test function
            // ForgotPass.initialize();
        }
    },

    forgotPass: function(){
        var target = $('.navbar-collapse').find('.forgot_pass');
        target.on('click', function(){
            ForgotPass.initialize();
        });
    },

    resetPass: function(){
        var target = $('.navbar-collapse').find('.reset_pass');
        target.on('click', function(){
            ResetPass.initialize();
        });
    },

    getMarkerDefault: function(){
        var parent = $('.indiana_marker');
        // Ajax.get_marker_default().then(function(data){
        //     Default.getTemplate(parent,data);
        // });
    },

    getMarkerZoom: function(){
        var parent = $('.indiana_marker');
        // Ajax.get_marker_zoom().then(function(data){
        //     Default.getTemplate(parent,data);
        // });

    },

    _eventClickMeetBtn: function() {
        var target = $('#btn_meet'),
            self = this;

        target.on('click',function(){
            $('.modal').modal('hide');
            Meet.initialize();
        });
    },

    _eventClickMeetBtnMobile: function(){
        var target = $('#btn_meet_mobile');

        target.on('click',function(){
            Meet.showUserMeetMobile();
            console.log('sgshgdhsgdsd');
        });
    },

    getTemplate: function(parent,data){
        var self = this;
        var json = $.parseJSON(data);
        var list_template = _.template($("#netwrk_place").html());
        var append_html = list_template({cities: json});

        parent.append(append_html);
    },
};