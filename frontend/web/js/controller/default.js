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
        // Default.ShowLandingPage();
        if (isCoverPage){
            if (accepted) {
                $("body").css('background', 'f2f2f2');
                if(!isMobile){
                    if(isResetPassword){
                        ResetPass.initialize();
                    }else{
                        Default.ShowLandingPage();
                    }
                } else {
                    Default.ShowLandingPage();
                }
            } else {
                CoverPage.initialize();
            }           
        } else {
            if (!accepted) {
                window.location.href = baseUrl;
            } else {
                if (isMobile) {
                    Default.ShowLandingPage();
                } else {
                    LandingPage.initialize();
                }
            }
        }
        if(!isGuest){
            Default.ShowNotificationOnChat();
        }
    },

    UnsetLanding: function(){
        sessionStorage.show_landing = 0;
    },

    ShowLandingPage: function(){
        if(isMobile){
            if(!sessionStorage.show_landing || sessionStorage.show_landing == 0){
                LandingPage.redirect();
            }else if(sessionStorage.show_landing == 1){
                Default.UnsetLanding();
            }
            else if(sessionStorage.show_landing == 2 && location.href == baseUrl + "/netwrk/default/landing-page"){
                LandingPage.initialize();
            }else if(sessionStorage.show_landing == 2){
                LandingPage.redirect();
            }
        }else{
            if (isCoverPage) {
                if (sessionStorage.redirected) {
                    sessionStorage.removeItem('redirected');
                    if(isResetPassword){
                        ResetPass.initialize();
                    }else{
                        LandingPage.initialize();
                    }
                } else {
                    sessionStorage.redirected = true;
                    window.location.href = baseUrl;// + "/netwrk/default/home";
                }
            }
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
                sessionStorage.UserInfo = data;
                data = $.parseJSON(data);
                var list_template = _.template($("#user_info_dropdown" ).html());
                var append_html = list_template({user_info: data});
                $('#user_avatar_wrapper #user_avatar_dashboard').remove();
                $('#user_avatar_wrapper').append(append_html);
                Default.OnHoverAvatarDropdown();
            });
            if (isMobile) {
                var avatar = '#user_avatar_wrapper';
                var nav = '#user_avatar_wrapper ul';
                $(document).on('click', avatar, function(e){
                    e.stopPropagation();
                    $(nav).slideToggle('fast');
                    $(avatar).find('#user_avatar_dashboard').toggleClass('bg-blue');
                    $(nav+' li').removeClass('bg-blue');
                });

                $('body').on( "click", function(e) {
                    if ($(e.target).parents('#user_avatar_wrapper').length > 0) {
                        //do nothing
                    } else {
                        $(nav).hide();
                        $(avatar).find('#user_avatar_dashboard').removeClass('bg-blue');
                        $(nav+' li').removeClass('bg-blue');
                    }
                });

                $(document).on('click', nav+' li', function(e){
                    e.stopPropagation();
                    $(nav+' li').removeClass('bg-blue');
                    $(this).addClass('bg-blue');
                });

            }
        }
    },

    OnHoverAvatarDropdown: function() {
        var btn = $('#user_avatar_dashboard');
        btn.hover(
            function(){
                $(ChatInbox.modal).css('z-index','999');
            }
            , function(){
            $(ChatInbox.modal).css('z-index','9999');
        });
    },
};