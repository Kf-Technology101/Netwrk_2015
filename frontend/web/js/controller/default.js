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
            Default.onCLickModal();
        }
        Default.SetAvatarUserDropdown();
        if(!isGuest){
            Default.ShowNotificationOnChat();
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
            sessionStorage.url = window.location.href;
            ChatInbox.OnClickChatInboxMobile();
            // Ajax.set_previous_page(window.location.href).then(function(data){
            // });
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

    ShowNotificationOnChat: function(){
        Ajax.count_unread_message().then(function(data){
            var json = $.parseJSON(data), notify;
            if(isMobile) {
                notify = $("#chat_inbox_btn_mobile").find('.notify');
            } else {
                notify = $("#chat_inbox_btn").find('.notify');
            }
            if (json > 0){
                notify.html(json);
                notify.removeClass('disable');
            } else {
                notify.html(0);
                notify.addClass('disable');
            }
        });
    },

    onCLickModal: function(){
        var modal = $('.modal');
        modal.on('click', function(e) {
            $('.popup_chat_modal .popup-box').css('z-index', '1050');
        });
    },

    displayPopupOnTop: function(){
        var modal = $('.in');
        if(modal.length > 0){
            $("#popup-chat-" + PopupChat.params.post).css('z-index', '10500');
        }
    },

    SetAvatarUserDropdown: function() {
        if (UserLogin) {
            Ajax.get_user_profile().then(function(data){
                data = $.parseJSON(data);
                var list_template = _.template($("#user_info_dropdown" ).html());
                var append_html = list_template({user_info: data});
                $('#user_avatar_wrapper #user_avatar_dashboard').remove();
                $('#user_avatar_wrapper').append(append_html);
            });
            if (isMobile) {
                var btn = '#user_avatar_dashboard';
                // $(document).on('click', btn, function(){
                //     if ($(btn).find('ul').css('display') == 'none') {
                //         $(btn).find('ul').css({'display': 'block', 'visibility': 'visible', 'opacity': 1});
                //     } else {
                //         $(btn).find('ul').css({'display': 'none', 'visibility': 'hidden', 'opacity': 0});
                //     }
                // });
            }
        }
    },
};