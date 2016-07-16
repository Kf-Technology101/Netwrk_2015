var Default ={
    initialize: function() {
        var self = this;
        if(isMobile){
            self._eventClickMeetBtnMobile();
            self._eventClickChatInboxBtnMobile();
            Default.show_blue_dot();

            if (sessionStorage.is_topic_marker_in_map_center == 1) {
                Map.showTopicMarker(sessionStorage.topic_lat, sessionStorage.topic_lng, sessionStorage.topic_city_id);
            }
        }else{
            $('#btn_meet').show();
            self._eventClickMeetBtn();
            ChatInbox.OnClickChatInbox();
            ResetPass.CheckSessionResetPassword();
            Default.onCLickModal();
            Default.onClickNavigationIcon();
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
        } else {
            Default.HideNotificationOnChat();
            Default.ShowDefaultNotificationOnChat();
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
                if(isResetPassword){
                    ResetPass.initialize();
                }else if(isUserInvitation){
                    Signup.initialize('join');
                }else{
                    LandingPage.initialize();
                }
                //Comment page reload twice code
                /*sessionStorage.redirected = true;
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
                }*/
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
        if(!isGuest){
            Ajax.count_unread_message().then(function(data){
                var json = $.parseJSON(data), notify;
                if(isMobile) {
                    notify = $("#chat_inbox_btn_mobile, #chat_inbox_nav_btn_mobile").find('.notify');
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
        } else {
            Default.HideNotificationOnChat();
            Default.ShowDefaultNotificationOnChat();
        }
    },
    HideNotificationOnChat: function() {
        var notify = '';
        if(isMobile) {
            notify = $("#chat_inbox_btn_mobile, #chat_inbox_nav_btn_mobile").find('.notify');
        } else {
            notify = $("#chat_inbox_btn").find('.notify');
        }
        notify.html(0);
        notify.addClass('disable');
    },
    /* When Guest user comes site, then display default notification on chat button in navigation */
    ShowDefaultNotificationOnChat: function() {
        var notify = '',
            json = 1;
        if(isMobile) {
            notify = $("#chat_inbox_btn_mobile, #chat_inbox_nav_btn_mobile").find('.notify');
        } else {
            notify = $("#chat_inbox_btn").find('.notify');
        }
        notify.html(json);
        notify.removeClass('disable');
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
        if (UserLogin && typeof isCoverPageVisited !== 'undefined') {
            Ajax.get_user_profile().then(function(data){
                sessionStorage.UserInfo = data;
                data = $.parseJSON(data);
                var list_template = _.template($("#account_nav_dropdown" ).html());
                var append_html = list_template({user_info: data});
                $('#nav_wrapper #navProfileWrapper').remove();
                $("#nav_wrapper .btn").eq(0).after(append_html);
                //$('#nav_wrapper').find('#navProfileWrapper').html(append_html);
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

    onClickNavigationIcon: function () {
        var target = $('.landing-trigger');

        target.unbind();
        target.on('click',function(){
            var logoWrapper = $(this).closest('.logo_netwrk');
            if(logoWrapper.hasClass('logo-glow')) {
                // Call ajax to set cookie
                var params = {'object': 'nw_glow_logo'};
                Ajax.setGlowCookie(params).then(function (data) {
                    var json = $.parseJSON(data);
                    if(json.success == true){
                        // Remove glow wrapper class
                        logoWrapper.removeClass('logo-glow');
                        // Destroy popover
                        logoWrapper.popover('destroy');
                        // Display near button popover
                        Common.showHideInfoPopover('popover-near','nw_popover_near');
                    }
                });
            }

            var landingModal = $('#modal_landing_page');

            // Check if landing page modal open
            if ((landingModal.data('bs.modal') || {isShown: false}).isShown ) {
                // Hide landing page modal
                landingModal.modal('hide');
            } else {
                // Close other open modal
                $('.modal').modal('hide');
                // Show landing page modal
                landingModal.modal('show');
            }
        });
    },
    show_blue_dot: function() {
        if (isMobile) {
            var action = $('.wrap-mobile').attr('data-action');
            if(action == 'home') {
                sessionStorage.show_blue_dot = 0;
                console.log(Map.map.getZoom()+'in show blue dot and its home page'+sessionStorage.show_blue_dot);

                if (sessionStorage.show_blue_dot_zoom12 == 1) {
                    Map.getBrowserCurrentPosition(Map.map, 'near');
                    sessionStorage.show_blue_dot_zoom12 = 0;
                } else {
                    Map.getBrowserCurrentPosition(Map.map, 'build');
                }

            }
        }
    },
    /**
     * Show blue dot on zoom12 on mobile
     * @param map
     */
    getMylocation: function(map){
        Map.getBrowserCurrentPosition(map);
    },
};