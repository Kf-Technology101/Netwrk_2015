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
                Map.getMylocation(Map.map);
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
    }
};
