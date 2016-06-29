/**
 * All Global functions required to whole site.
 */
var Common = {
    contexts : {
        'boxNavigation': '.box-navigation',
        'btnExplore': '.btn-explore',
        'btnExploreLocation': '.btn-explore-location',
        'chatInboxNavBtnMobile': '#chat_inbox_nav_btn_mobile',
        'btnNavMeetMobile' : '#btn_nav_meet_mobile',
        'loginTrigger' : '.login-trigger'
    },
    params: {
        'loaderIntervalId': ''
    },
    initialize: function() {
        Common.console();
        Common.onWindowUnload();
        Common.eventClickExploreLocation();
        //init the nav chat inbox for mobile
        Common.eventClickChatInboxBtnMobile();
        //init nav meet btn on mobile
        Common.eventClickMeetBtnMobile();
        //init nav login btn. open login modal box on desktop
        Common.eventLoginTrigger();
        Common._eventClickProfileNavMenu();
        Common.onClickMapButton();

        Common.deleteTrigger();

        // Feedback related script calls
        Common.feedbackAllTriggers();

        if(isMobile){
            Map.eventClickMyLocation(Map.map);
        }
    },

    console: function(){
        if(ENV == 'prod'){
            console.log = function(){}
        }
    },
    /* On clicking map btn in nav, it will redirect to default home on mobile */
    eventClickExplore: function(){
        var target = $(Common.contexts.btnExplore, Common.contexts.boxNavigation);
        target.unbind();
        target.on('click',function(e){
            if(isMobile){
                sessionStorage.show_landing = 1;
                window.location.href = baseUrl + "/netwrk/default/home";
            }
        });
    },
    eventClickExploreLocation: function(){
        var target = $(Common.contexts.btnExploreLocation, Common.contexts.boxNavigation);
        target.unbind();
        target.on('click',function(e){
            if(isMobile){
                sessionStorage.show_landing = 1;
                sessionStorage.show_blue_dot = 1;
                window.location.href = baseUrl + "/netwrk/default/home";
            }
        });
    },
    eventClickChatInboxBtnMobile: function() {
        var target = $(Common.contexts.chatInboxNavBtnMobile, Common.contexts.boxNavigation);
        target.unbind();
        target.on('click',function(){
            sessionStorage.url = window.location.href;
            Common.OnClickChatInboxMobile();
        });
    },
    OnClickChatInboxMobile: function() {
        if ( window.location.href == baseUrl + "/netwrk/chat-inbox") {
            window.location.href = sessionStorage.url;
        } else {
            window.location.href = baseUrl+ "/netwrk/chat-inbox";
        }
    },
    eventClickMeetBtnMobile: function(){
        var target = $(Common.contexts.btnNavMeetMobile, Common.contexts.boxNavigation);

        target.unbind();
        target.on('click',function(){
            Meet.showUserMeetMobile();
        });
    },
    eventLoginTrigger: function() {
        var target = $(Common.contexts.loginTrigger, Common.contexts.boxNavigation);
        target.unbind();
        target.on("click", function() {
            if(isGuest){
                $('.modal').modal('hide');
                Login.initialize();
            }
        });
    },
    _eventClickProfileNavMenu: function() {
        var target = $('.profile-trigger'),
            self = this;

        target.unbind();
        target.on('click',function(){
            if (isMobile) {
                window.location.href = baseUrl + "/netwrk/profile";
            } else {
                $('.modal').modal('hide');
                User_Profile.initialize();
            }

        });
    },
    CustomScrollBar: function(taget,options){
        options = (options) ? options : {
            theme:"dark"
        };

        taget.mCustomScrollbar(options);
    },
    InitToolTip: function() {
        $('[data-toggle="tooltip"]').tooltip();
    },
    HideTooTip: function() {
        $('.tooltip').hide();
    },
    initLoader: function() {
        console.log('in initLoader');
        $('.loader-wrap').removeClass('hide');
    },
    hideLoader: function() {
        //clear the loader setIntervalId to stop loader animation.
        $('.loader-wrap').addClass('hide');
        console.log('in hideLoader');
    },
    onWindowUnload: function() {
        $(window).on('beforeunload', function(){
            $('.bootbox').css('opacity',' 0.1');
            $('.bootbox').css('visibility','hidden');
        });
        $(window).on('unload', function(){
            $('.bootbox').css('opacity',' 0.1');
            $('.bootbox').css('visibility','hidden');
        });
    },
    onClickMapButton: function() {
        console.log('in onClickMapButton');
        var target = $('.btn_nav_map_location', Common.contexts.boxNavigation);
        target.unbind();
        target.on('click', function () {
            //hide all opened modal
            $('.modal').modal('hide');

            if (isGuest) {
                Map.getBrowserCurrentPosition(Map.map);
            } else {
                Map.getMyLocation(Map.map);
            }
        });
    },

    deleteTrigger: function() {
        var target = $('.delete-trigger');

        target.unbind();
        target.on('click',function(){
            var self = $(this),
                object = self.attr('data-object'),
                id = self.attr('data-id'),
                section = self.attr('data-section'),
                confirmModal = $('#confirmationBox');

            confirmModal.modal({
                keyboard: false,
                show: true
            });

            confirmModal.find('#btnYes').off('click').on('click', function(){
                if(object == 'post') {
                    Ajax.deletePost({
                        'id': id
                    }).then(function(data) {
                        var json = $.parseJSON(data);
                        if (json.error){
                            confirmModal.find('.alert-danger').removeClass('hidden').html(json.message);
                            setTimeout(function(){
                                confirmModal.find('.alert-danger').addClass('hidden');
                                confirmModal.modal('hide');
                            }, 500);
                        } else {
                            confirmModal.modal('hide');
                            if(section == 'profile'){
                                self.closest('.col-xs-12').parent().remove();
                            }
                        }
                    });
                } else if(object == 'topic') {
                    Ajax.deleteTopic({
                        'id': id
                    }).then(function(data) {
                        var json = $.parseJSON(data);
                        if (json.error){
                            confirmModal.find('.alert-danger').removeClass('hidden').html(json.message);
                            setTimeout(function(){
                                confirmModal.find('.alert-danger').addClass('hidden');
                                confirmModal.modal('hide');
                            }, 500);
                        } else {
                            confirmModal.modal('hide');
                            if(section == 'profile'){
                                self.closest('.col-xs-12').parent().remove();
                            } else if(section == 'community'){
                                self.closest('.topic-actions').parent().remove();
                            }
                        }
                    });
                } else if(object == 'group') {
                    Ajax.delete_group({
                        'id': id
                    }).then(function(data) {
                        var json = $.parseJSON(data);
                        if (json.error){
                            confirmModal.find('.alert-danger').removeClass('hidden').html(json.message);
                            setTimeout(function(){
                                confirmModal.find('.alert-danger').addClass('hidden');
                                confirmModal.modal('hide');
                            }, 500);
                        } else {
                            confirmModal.modal('hide');
                            if(section == 'profile'){
                                self.closest('.col-xs-12').parent().remove();
                            } else if(section == 'community'){
                                self.closest('.group-actions').parent().remove();
                            }
                        }
                    });
                }
            });
        });
    },

    feedbackAllTriggers: function(){
        Common.feedbackTrigger();
        Common.feedbackCloseTrigger();
        Common.feedbackLoginTrigger();
        Common.feedbackOptionTrigger();
    },

    feedbackTrigger: function(){
        var target = $('.feedback-trigger');

        target.unbind();
        target.on('click',function(){
            var object = $(this).data('object'),
                id = $(this).data('id'),
                parent = $(this).data('parent'),
                feedbackSection = $(parent).find('.feedback-section');

            feedbackSection.removeClass('hide');
            feedbackSection.find('.feedback-content')
                .data('parent',parent)
                .data('object',object)
                .data('id',id);
        });
    },
    feedbackCloseTrigger: function () {
        var target = $('.feedback-close-trigger');

        target.unbind();
        target.on('click',function(){
           $(this).closest('.feedback-section').addClass('hide');
        });
    },
    feedbackAfterLogin: function () {
        $('.feedback-list a').each(function(){
            $(this).removeClass('login-trigger').addClass('feedback-option-trigger');
        });
        Common.feedbackOptionTrigger();
    },
    feedbackLoginTrigger: function() {
        var target = $('.login-trigger', '.feedback-content');
        target.unbind();
        target.on("click", function() {
            if(isGuest){
                if(isMobile){
                    var url = window.location.href;
                    window.location.href = baseUrl + "/netwrk/user/login?url_callback="+url;
                }else{
                    $('.modal').modal('hide');
                    Login.modal_callback = Common.feedbackAfterLogin();
                    Login.initialize();
                    return false;
                }
            }
        });
    },
    feedbackOptionTrigger: function(){
        var target = $('.feedback-option-trigger');

        target.unbind();
        target.on('click',function(){
            var option = $(this).data('option'),
                point = $(this).data('point'),
                feedbackContent = $(this).closest('.feedback-content'),
                parent = $(feedbackContent).data('parent'),
                object = $(feedbackContent).data('object'),
                id = $(feedbackContent).data('id'),
                feedbackSection = $(parent).find('.feedback-section');

            var params = {'object': object,'id': id, 'option': option, 'point': point};

            Ajax.postFeedback(params).then(function(data){
                var json = $.parseJSON(data);

                if(json.success == 'true'){
                    feedbackSection.addClass('hide');
                    feedbackSection.find('.feedback-content')
                        .data('parent','')
                        .data('object','')
                        .data('id','');
                }
            });
        });
    },

};
