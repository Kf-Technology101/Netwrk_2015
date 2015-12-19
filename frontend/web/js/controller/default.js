var Default ={
    initialize: function() {
        var self = this;
        if(isMobile){
            self._eventClickMeetBtnMobile();
            self._eventClickChatInboxBtnMobile();

        }else{
            $('#btn_meet').show();
            self._eventClickMeetBtn();
            ChatInbox.OnClickChatInbox();
            ResetPass.CheckSessionResetPassword();
        }
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
        });
    },

    _eventClickChatInboxBtnMobile: function() {
        var target = $('#chat_inbox_btn_mobile');
        target.unbind();
        target.on('click',function(){
            Ajax.set_previous_page(window.location.href).then(function(data){
                ChatInbox.OnClickChatInboxMobile();
            });
        });
    },

    getTemplate: function(parent,data){
        var self = this;
        var json = $.parseJSON(data);
        var list_template = _.template($("#netwrk_place").html());
        var append_html = list_template({cities: json});

        parent.append(append_html);
    },
    hideHeaderFooter: function(){
        $('.navbar-fixed-top').hide();
        $('.navbar-fixed-bottom').hide();
    },
};