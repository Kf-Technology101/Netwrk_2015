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
        Common.onShowAllModals();

        // Feedback related script calls
        Common.feedbackAllTriggers();

        if(isMobile){
            Map.eventClickMyLocation(Map.map);
            // Display near button popover
            Common.showHideInfoPopover('popover-near', 'nw_popover_near');

            Common.onClickSearchIcon();
        }

        if(typeof welcomePage !== 'undefined' && welcomePage == 'false'){
            // Display netwrk logo info popover
            Common.showHideInfoPopover('popover-logo', 'nw_popover_logo');
        }

        Common.clickCenterLocation();
    },

    console: function(){
        if(ENV == 'prod'){
            console.log = function(){}
        }
    },

    showHideInfoPopover: function(popoverWrapperClass, cookieName) {
        var popoverWrapper = $('.'+popoverWrapperClass);
        if(typeof popoverWrapper.attr('data-content') != 'undefined'){
            popoverWrapper.popover('show');

            var popoverClose = $('.popover').find('.popover-close-trigger');
            popoverClose.unbind();
            popoverClose.on('click', function(){
                var cookieName = $(this).attr('data-cookie'),
                    popoverWrapperClass = $(this).attr('data-wrapper'),
                    popoverWrapper = $('.'+popoverWrapperClass);
                $(this).parents('.popover').popover('destroy');
                var params = {'object': cookieName};
                Ajax.setGlowCookie(params).then(function (data) {
                    var json = $.parseJSON(data);
                    if (json.success == true) {
                        // Remove class and content
                        popoverWrapper.removeClass(popoverWrapperClass)
                            .attr('data-content', '');

                        if(!isMobile) {
                            if(cookieName == 'nw_popover_chat_topic_title'){
                                var popupChatHtml = $("#popup_chat").html();
                                popupChatHtml = popupChatHtml.replace('<%= popoverChatTopicTitle %>', '');
                                $("#popup_chat").html(popupChatHtml);
                            }
                            else if(cookieName == 'nw_popover_post_filter'){
                                var popupChatHtml = $("#post_list").html();
                                popupChatHtml = popupChatHtml.replace('<%= popoverClassPostFilter %>', '');
                                $("#post_list").html(popupChatHtml);
                            }
                            else if(cookieName == 'nw_popover_post_feedback'){
                                var popupChatHtml = $("#post_list").html();
                                popupChatHtml = popupChatHtml.replace('<%= popoverClassPostFeedback %>', '');
                                $("#post_list").html(popupChatHtml);
                            }
                        }
                    }
                });
            });
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
        var target = $(Common.contexts.btnExploreLocation, Common.contexts.boxNavigation)
            .add($(Common.contexts.btnExploreLocation));
        target.unbind();
        target.on('click',function(e){
            if(isMobile){
                /*var btnWrapper = $(this).closest('.btn-nav-map');
                if(btnWrapper.hasClass('glow-btn-wrapper')) {
                    // Call ajax to set cookie
                    var params = {'object': 'nw_glow_near_btn'};
                    Ajax.setGlowCookie(params).then(function (data) {
                        var json = $.parseJSON(data);
                        if(json.success == true){
                            // Remove glow wrapper class
                            btnWrapper.removeClass('glow-btn-wrapper');
                        }
                    });
                }*/

                sessionStorage.show_landing = 1;
                sessionStorage.show_blue_dot = 1;
                sessionStorage.show_blue_dot_zoom12 = 1;
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
        var target = $(Common.contexts.loginTrigger, Common.contexts.boxNavigation)
            .add($(Common.contexts.loginTrigger));
        target.unbind();
        target.on("click", function() {
            if (isGuest) {
                if(isMobile){
                    var url = window.location.href;
                    window.location.href = baseUrl + "/netwrk/user/login?url_callback="+url;
                } else {
                    $('.modal').modal('hide');
                    Login.initialize();
                }
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
                User_Profile.initializeSlider();
                /*User_Profile.initialize();*/
            }
        });
    },
    CustomScrollBar: function(target,options){
        options = (options) ? options : {
            theme:"dark"
        };

        target.mCustomScrollbar(options);
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
            var btnWrapper = $(this).closest('.btn-nav-map');
            if(btnWrapper.hasClass('glow-btn-wrapper')) {
                // Call ajax to set cookie
                var params = {'object': 'nw_glow_near_btn'};
                Ajax.setGlowCookie(params).then(function (data) {
                    var json = $.parseJSON(data);
                    if(json.success == true){
                        // Remove glow wrapper class
                        btnWrapper.removeClass('glow-btn-wrapper');
                    }
                });
            }

            //hide all opened modal
            $('.modal').modal('hide');
            Map.getBrowserCurrentPosition(Map.map, 'near');
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

    onShowAllModals: function() {
        $('.modal').on('shown.bs.modal',function(e) {
            //$(e.currentTarget).unbind();
            console.log('in shown.bs.modal');
            Map.unsetBuildMode();
        });
    },

    feedbackAllTriggers: function(){
        Common.feedbackTrigger();
        Common.feedbackCloseTrigger();
        Common.feedbackOptionLoginTrigger();
        Common.feedbackOptionTrigger();
    },
    feedbackTrigger: function(){
        var target = $('.feedback-trigger');

        target.unbind();
        target.on('click',function(){
            if($(this).hasClass('login-trigger')) {
                var modal = $(this).attr('data-modal');
                if(isGuest){
                    if(isMobile){
                        Login.RedirectLogin(window.location.href);
                    }else{
                        $('.modal').modal('hide');
                        if(typeof modal !== 'undefined' && modal == 'Post'){
                            Login.modal_callback = Post;
                        }
                        Login.initialize();
                        return false;
                    }
                }
            } else {
                var object = $(this).attr('data-object'),
                    id = $(this).attr('data-id'),
                    parent = $(this).attr('data-parent'),
                    feedbackSection = $(parent).find('.feedback-section');

                feedbackSection.removeClass('hide');
                feedbackSection.find('.feedback-content')
                    .attr('data-parent',parent)
                    .attr('data-object',object)
                    .attr('data-id',id);
            }
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
    feedbackOptionLoginTrigger: function() {
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
            var option = $(this).attr('data-option'),
                point = $(this).attr('data-point'),
                feedbackContent = $(this).closest('.feedback-content'),
                parent = $(feedbackContent).attr('data-parent'),
                object = $(feedbackContent).attr('data-object'),
                id = $(feedbackContent).attr('data-id'),
                feedbackSection = $(parent).find('.feedback-section'),
                feedbackAlert = $(parent).find('.feedback-alert');

            var params = {'object': object,'id': id, 'option': option, 'point': point};

            Ajax.postFeedback(params).then(function(data){
                var json = $.parseJSON(data);

                if(json.success == 'true'){
                    var alertClass = json.msgClass,
                        alertText = json.msg;

                    feedbackSection.addClass('hide');
                    feedbackSection.find('.feedback-content')
                        .attr('data-parent','')
                        .attr('data-object','')
                        .attr('data-id','');

                    var feedbackAlertChild = '<div id="feedbackAlert" class="alert '+alertClass+'">'+alertText+'</div>';

                    feedbackAlert.html(feedbackAlertChild).removeClass('hide');

                    if(json.feedbackPoints >= 0){
                        $(parent).find('#heading'+id).find('a').removeClass('collapsed');
                        $(parent).find('#collapse'+id).addClass('in');
                    } else {
                        $(parent).find('#heading'+id).find('a').addClass('collapsed');
                        $(parent).find('#collapse'+id).removeClass('in');
                    }

                    setTimeout(function(){
                        feedbackAlert.html('').addClass('hide');
                    }, 1500);
                }
            });
        });
    },

    ShowModalComeBack: function(){
        var modal = $('#comeBackLater');

        modal.modal({
            backdrop: true,
            keyboard: false
        });

        $('.modal-backdrop.in').click(function(e) {
            self.reset_modal();
            $('#comeBackLater').modal('hide');
        });
    },

    eventOnBoardingLineClick: function(){
        var boardingModalId = '#modalOnBoarding';
        var target = $('.line-list-item', boardingModalId);
        target.unbind();
        target.on('click',function(e){
            var selectedLines = parseInt($(boardingModalId).find('.selected-lines').val());

            if($(this).find('.line-check-selected').hasClass('hide')){
                $(this).addClass('selected');
                $(this).find('.line-check-selected').removeClass('hide');
                $(boardingModalId).find('.selected-lines').val(selectedLines+1);
            } else {
                $(this).removeClass('selected');
                $(this).find('.line-check-selected').addClass('hide');
                $(boardingModalId).find('.selected-lines').val(selectedLines-1);
            }

            selectedLines = parseInt($(boardingModalId).find('.selected-lines').val());

            if(selectedLines == 0){
                $(boardingModalId).find('.btn-save-lines').addClass('disabled').removeClass('btn-save-active');
            } else {
                $(boardingModalId).find('.btn-save-lines').removeClass('disabled').addClass('btn-save-active');
            }
        });
    },

    eventOnBoardingSaveLines: function(){
        var boardingModal = $(Login.on_boarding_id),
            target = $('.btn-save-lines', Login.on_boarding_id);

        target.unbind();
        target.on('click',function(e){
            var parent = boardingModal.find('.modal-body').find('.lines-wrapper ul');
            var selectedLines = [];

            parent.find('li.selected').each(function(ele){
                var postId = $(this).attr('data-post-id');
                selectedLines.push(postId);
            });

            var params = {'posts':selectedLines};

            Ajax.saveOnBoardingLines(params).then(function(data){
                var jsonData = $.parseJSON(data);
                if (jsonData.success == true){
                    if(Login.profile_picture == false){
                        Login.showProfilePicture();
                    } else {
                        boardingModal.modal('hide');
                        if(Login.isCommunityJoined == false) {
                            Login.showJoinHomeModal();
                        }
                    }
                }
            });
        });
    },

    closeAllLeftSliders: function(){
        $('.left-slider').each(function(){
            $(this).animate({
                "left": "-500px"
            }, 200);
        });
    },

    clickCenterLocation: function(map){
        var btn = $('#btnCenterLocation');
        btn.unbind();
        btn.on('click',function(){
            if(User.location.lat && User.location.lng) {
                Map.initialize();
            }
        });
    },

    showAreaSlider: function() {
        console.log('show area slider');

        var target = '#areaNews',
            contexts = '';

        if($(target).css('left') == '0px'){
            if(isMobile){
                var hideLeft = '-100%';
            } else {
                var hideLeft = '-400px';
            }
            $(target).animate({
                "left": hideLeft
            }, 500);
        } else {
            $(target).animate({
                "left": "0px"
            }, 500);
        }

        if(isMobile){
            var targetHeight = $(window).height()-55;
            var tabHeight = targetHeight - 40;
            $(target).css({'height' : targetHeight});
            $(target).find('.tab-wrapper').css({'height' : tabHeight, 'max-height' : tabHeight});
        }

        Common.CustomScrollBar($(target));
        Common.onShowAreaSlider();
    },
    onShowAreaSlider: function() {
        var cityParams = {'zip_code':zipCode, 'office_type': 'social'};
        console.log(cityParams);
        Ajax.get_city_by_zipcode(cityParams).then(function(data) {
            var json = $.parseJSON(data);
            var cityId = json[0].id;
            console.log(cityId);
            if(json.length > 0) {
                Common.getAreaFeeds(cityId);
            }
        });
    },
    getAreaFeeds: function(cityId) {
        var parent = $('#areaNews').find('#area_tab_feed'),
            cityname = $('#areaNews').find('.title_page');

        parent.show();
        var params = {'city': cityId,'zipcode': null, 'filter': 'recent','size': 30,'page':1};
        Ajax.show_feed(params).then(function(data){
            console.log('In show_feed');
            parent.html('');
            parent.scrollTop(0);
            Common.getTemplateModal(cityname,data);
            Common.getTemplateFeed(parent,data);
            Common.getTemplateHistory(parent,data);
            Topic.OnClickPostFeed();
            Topic.OnClickTopicFeed();
            Common.onClickHideAreaButton();
        });
    },
    getTemplateModal: function(parent,data){
        var self = this;
        var json = $.parseJSON(data);

        var list_template = _.template($( "#city_name" ).html());
        var append_html = '';
        append_html = list_template({city: json.city, office_type: json.office_type});
        parent.html(append_html);
    },
    getTemplateFeed: function(parent,data){
        var json = $.parseJSON(data);
        if(json.feed.length > 0){
            parent.find('.no-data').hide();
            var list_template = _.template($( "#area_feed_list" ).html());
            var append_html = list_template({feed: json});
            parent.append(append_html);
        }
    },
    getTemplateHistory: function(parent,data){
        var json = $.parseJSON(data);
        var target = parent.find('.top-feed .top-feed-content');

        var list_template = _.template($( "#area_top_feed" ).html());
        var append_html = list_template({feed: json});

        target.append(append_html);
    },
    onClickHideAreaButton: function() {
        var target = '#areaNews';
        var hide_area_feeds_btn = "#hide_area_feeds_btn";
        if(isMobile){
            var hideLeft = '-100%';
        } else {
            var hideLeft = '-400px';
        }
        $(hide_area_feeds_btn, target).unbind();
        $(hide_area_feeds_btn, target).on("click", function() {
            $(target).animate({
                "left": hideLeft
            }, 500);
        });
    },

    closeAllLeftSliders: function(){
        $('.left-slider').each(function(){
            $(this).animate({
                "left": "-500px"
            }, 200);
        });
    },

    clickCenterLocation: function(map){
        var btn = $('#btnCenterLocation');
        btn.unbind();
        btn.on('click',function(){
            if(User.location.lat && User.location.lng) {
                if(!isMobile){
                    Map.initialize();
                }
            }
        });
    },

    onClickSearchIcon: function() {
        var target = $('#mobileSearchBox');
        var btn = $('.search-trigger');
        var closeBtn = $('.close-search-trigger');

        btn.unbind();
        btn.on('click',function(){
            target.css({'right' : '5px'});
            $('.search-overlay').removeClass('hide');
        });

        closeBtn.unbind();
        closeBtn.on('click',function(){
            target.css({'right' : '-100%'});
            $('.search-overlay').addClass('hide');
        });
    }
};
