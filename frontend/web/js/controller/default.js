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

        if(typeof isCoverPageVisited !== 'undefined'){
            if (isAccepted) {
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
            if(typeof isAccepted === 'undefined'){
                CoverPage.initialize();
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
            if (isCoverPageVisited) {
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
        var target = $('#btn_meet, #btn_nav_meet'),
            self = this;

        target.on('click',function(){
            $('.modal').modal('hide');
            Meet.initialize();

        });
    },

    _eventClickMeetBtnMobile: function(){
        var target = $('#btn_meet_mobile, #btn_nav_meet_mobile');

        target.on('click',function(){
            Meet.showUserMeetMobile();
        });
    },

    _eventClickChatInboxBtnMobile: function() {
        var target = $('#chat_inbox_btn_mobile, #chat_inbox_nav_btn_mobile');
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
        if (UserLogin && isCoverPageVisited) {
            Ajax.get_user_profile().then(function(data){
                sessionStorage.UserInfo = data;
                data = $.parseJSON(data);
                var list_template = _.template($("#account_nav_dropdown" ).html());
                var append_html = list_template({user_info: data});
                $('#nav_wrapper #account_nav_wrapper').remove();
                $('#nav_wrapper').append(append_html);
                Common._eventClickProfileNavMenu();
            });
            //Hide the sign in button from nav
            $(Common.contexts.loginTrigger, Common.contexts.boxNavigation).hide();
        }
    },

    OnHoverAvatarDropdown: function() {
        var btn = $('#account_nav_profile');
        btn.hover(
            function(){
                $(ChatInbox.modal).css('z-index','999');
            }
            , function(){
                $(ChatInbox.modal).css('z-index','9999');
            });
    },
};