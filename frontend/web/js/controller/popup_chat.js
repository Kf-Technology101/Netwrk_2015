var PopupChat = {
    params:{
        post:'',
        chat_type: 1,
        post_name: '',
        post_description: '',
        post_avatar: '',
        previous_flag: '',
        topic_id: '',
        city_name: '',
        city: '',
    },
    scrollToMsg: 0,
    total_popups: 0,
    max_total_popups: 4,
    popup_chat_class: '.popup-box.chat-popup',
    inactive_color: "#5888ac",
    active_color: "#5da5d8",
    popups: [],
    url:'',
    page:'#post_chat',
    modal:'.popup_chat_modal',
    parent: '',
    container: '',
    status_emoji: 1,
    text_message:'',
    message_type:1,
    msg_lenght: 0,
    temp_post: 0,
    close_status: 0,
    initialize: function() {
        PopupChat.SetUrl();
        PopupChat.SetDataChat(true);
        if(isMobile){
            PopupChat.SetHeightContainerChat();
            PopupChat.OnClickChatInboxBtnMobile();
            ChatInbox.HideMeetIconMobile();
            PopupChat.OnClickBackBtn();
            Default.ShowNotificationOnChat();
            Default.SetAvatarUserDropdown();
            if(sessionStorage.welcome_channel == 1) {
                sessionStorage.welcome_channel = 0;
                // Display channel welcome modal
                LandingPage.showLandingChannelWelcome();
            }
            PopupChat.onClickBreadcrumbTopic();
        }else{
            if (ChatInbox.params.target_popup.length == 0 || ChatInbox.params.target_popup.css('display') == 'none') {
                PopupChat.OnclickLogin();
                PopupChat.RegisterPopup();
                PopupChat.ChangeStylePopupChat();
                PopupChat.OnWsChat();
                PopupChat.OnWsFile();
                PopupChat.GetListEmoji();
                PopupChat.HandleEmoji();
                PopupChat.CustomScrollBar();
                PopupChat.ShowChatBox(PopupChat.params.post);
                PopupChat.ShowPopupChatWhenModalDisplay();
                PopupChat.OnClickMinimizeBtn();
            } else {
                var target_popup_chat= $("#popup-chat-" + PopupChat.params.post);
                $("#popup-chat-" + PopupChat.params.post + " .popup-head").css("background-color", PopupChat.active_color);
                target_popup_chat.find('.send').css("background-color", PopupChat.active_color);
                setTimeout(function(){
                    target_popup_chat.find('textarea').focus()
                }, 1);
                target_popup_chat.css('height', '330px');
                target_popup_chat.find('.nav_input_message').css('display', 'block');
                if (target_popup_chat.data('data-chat-type') == 1) {
                    target_popup_chat.find('.minimize-btn').css('bottom', '10px');
                } else {
                    target_popup_chat.find('.minimize-btn').css('bottom', '5px');
                }
            }
        }
        PopupChat.FetchDataChat();
        PopupChat.UpdateViewPost();
    },

    onClickBreadcrumbTopic: function(){
        var target = $('.chat-box').find('.chat-topic-trigger');
        target.unbind();
        target.on('click',function(e){
            var topic_id = $(e.currentTarget).attr('data-value');
            if(isMobile){
                window.location.href = baseUrl + "/netwrk/post?topic="+topic_id;
            }
        });
    },

    UpdateViewPost: function(){
        if(!isGuest){
            Ajax.update_view_post(PopupChat.params).then(function(){
            });
        }
    },

    Remove: function(array, from, to) {
        var rest = array.slice((to || from) + 1 || array.length);
        array.length = from < 0 ? array.length + from : from;
        return array.push.apply(array, rest);
    },

    ClosePopup: function(id) {
        for(var i = 0; i < PopupChat.popups.length; i++)
        {
            if(id == PopupChat.popups[i])
            {
                PopupChat.Remove(PopupChat.popups, i);
                document.getElementById('popup-chat-' + id).style.display = "none";
                PopupChat.CalculatePopups();
                PopupChat.MoveMeetButton();

                return;
            }
        }
    },

    DisplayPopups: function() {
        var right = 430,
            popup_rights = [];


        if ($(ChatInbox.chat_inbox).css('right') == ChatInbox.list_chat_post_right_hidden) {
            right = 15;
        }

        $('.popup-box').each(function(){
            var parseRight = parseInt($(this).css('right'));
            if (!isNaN(parseRight)) {
                popup_rights.push(parseRight);
            }
        });

        min_popup_right = Math.min.apply(Math, popup_rights);

        if (min_popup_right < right) {
            right = min_popup_right;
        }

        var i = 0;
        for(i; i < PopupChat.total_popups; i++)
        {
            if(PopupChat.popups[i] != undefined)
            {
                var element = document.getElementById('popup-chat-' + PopupChat.popups[i]);
                element.style.right = right + "px";
                right = right + 280;
                element.style.display = "block";
            }
        }

        for (var j = (PopupChat.popups.length - i)-1; j >= 0; j--) {
            var element = document.getElementById('popup-chat-' + PopupChat.popups[j]);
            element.style.display = "none";
            PopupChat.ClosePopup(PopupChat.popups[j]);
        }
    },

    RegisterPopup: function() {
        for(var i = 0; i < PopupChat.popups.length; i++)
        {
            if(PopupChat.params.post == PopupChat.popups[i])
            {
                PopupChat.Remove(PopupChat.popups, i);
                PopupChat.popups.push(PopupChat.params.post);
                PopupChat.CalculatePopups();
                return;
            }
        }
        PopupChat.getTemplate();
        $("#popup-chat-" + PopupChat.params.post + " .popup-head").css("background-color", PopupChat.active_color);
        $("#popup-chat-" + PopupChat.params.post).find('.send').css("background-color", PopupChat.active_color);
        PopupChat.popups.push(PopupChat.params.post);
        PopupChat.CalculatePopups();
        PopupChat.MoveMeetButton();
    },

    getTemplate: function(){
        var list_template = _.template($("#popup_chat").html());
        if (PopupChat.params.chat_type == 0) {
            var append_html = list_template({post_id: PopupChat.params.post, chat_type: PopupChat.params.chat_type, post_name: PopupChat.params.post_name, post_avatar: PopupChat.params.post_avatar});
        } else {
            var append_html = list_template({post_id: PopupChat.params.post, chat_type: PopupChat.params.chat_type, post_name: PopupChat.params.post_name, post_description: PopupChat.params.post_description, topic_id: PopupChat.params.topic_id, city_name: PopupChat.params.city_name, city: PopupChat.params.city});
        }

        $('.map_content').append(append_html);
        Topic.OnClickTopicFeed();
    },

    CalculatePopups: function() {
        var width = window.innerWidth;

        if(width < 540)
        {
            PopupChat.total_popups = 0;
        }
        else
        {
            width = width - 200;
            PopupChat.total_popups = parseInt(width/320);

            if (PopupChat.total_popups > PopupChat.max_total_popups) {
                PopupChat.total_popups = PopupChat.max_total_popups;
            }
        }

        PopupChat.DisplayPopups();
        Default.displayPopupOnTop();
    },

    ChangeStylePopupChat: function() {
        var id = PopupChat.params.post;

        var target_popup_active = $("#popup-chat-" + id + " .popup-head");
        $("#textarea-" + id).on("focus", function() {
            target_popup_active.css("background-color", PopupChat.active_color);
        });
        $("#textarea-" + id).on("focusout", function() {
            target_popup_active.css("background-color", PopupChat.inactive_color);
        });

        $("#popup-chat-" + id).on("click", function() {
            $(PopupChat.popup_chat_class + " .popup-head").css("background-color", PopupChat.inactive_color);
            $(PopupChat.popup_chat_class).find('.send').css("background-color", PopupChat.inactive_color);
            target_popup_active.css("background-color", PopupChat.active_color);
            $(this).find('.send').css("background-color", PopupChat.active_color);
            $('#popup-chat-'+id).find('textarea').focus();
        });

        $("body").mouseup(function (e) {
            var container = $("#popup-chat-" + id);

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                target_popup_active.css("background-color", PopupChat.inactive_color);
                $("#popup-chat-" + id).find('.send').css("background-color", PopupChat.inactive_color);
            }
        });
    },

    MoveMeetButton: function() {
        var have_popup = false,
            max_height = 0,
            bottom_btnMeet;

        if (!$('#btn_meet').data('original_bottom')) {
            $('#btn_meet').data('original_bottom', parseInt($('#btn_meet').css('bottom')))
        }
        bottom_btnMeet = $('#btn_meet').data('original_bottom');

        $('#btn_meet').css('bottom', bottom_btnMeet + 'px');

        /*max_height = $('#popup-chat-' + PopupChat.popups[0]).height();

        $(PopupChat.popup_chat_class).each(function() {
            var id = this.id;
            if ($('#' + id).css('display') == 'block') {
                have_popup = true;
                return false;
            }
        });

        if (have_popup) {
            $('#btn_meet').css('bottom', bottom_btnMeet + max_height + 'px');
        } else {
            $('#btn_meet').css('bottom', bottom_btnMeet + 'px');
        }*/
    },

    ShowChatBox: function(popup_id){
        if (isMobile) {
            var target_form = $(PopupChat.parent);
            if(isGuest){
                target_form.find('.send_message.login').removeClass('active');
                target_form.find('.send_message.no-login').addClass('active');
            }else{
                target_form.find('.send_message.no-login').removeClass('active');
                target_form.find('.send_message.login').addClass('active');
            }
        } else {
            var target_form = $(PopupChat.parent);
            target_form.each(function(){
                if(isGuest){
                    $(this).find('.send_message.login').removeClass('active');
                    $(this).find('.send_message.no-login').addClass('active');
                }else{
                    $(this).find('.send_message.no-login').removeClass('active');
                    $(this).find('.send_message.login').addClass('active');
                }
            });
        }
    },

    OnclickLogin: function(){
        var btn = PopupChat.parent+' .send_message.no-login .input-group-addon';

        $(document).on('click', btn, function(){
            if(isMobile){
                window.location.href = baseUrl + "/netwrk/user/login?url_callback="+ $(PopupChat.parent).find('.send_message').attr('data-url');
            }else{
                $('.modal').modal('hide');
                Login.modal_callback = PopupChat;
                Login.initialize();
                return false;
            }
        });
    },

    SetUrl: function(){
        PopupChat.url = MainWs.url;
    },

    // Set info for each popup chat when user active or open the popup
    SetDataChat: function(fromChatList){
        if(isMobile){
            PopupChat.parent = '#post_chat';
            PopupChat.container = '.container_post_chat';
        } else {
            PopupChat.parent = PopupChat.modal;
            PopupChat.container = '.popup_chat_container';
        }
        var popup = '#popup-chat-'+PopupChat.params.post;
        // popup.unbind();
        $(document).on('click', popup, function(e) {
            $(e.currentTarget).css('z-index', '10500');
            PopupChat.params.post = $(this).attr('data-id');
            PopupChat.params.chat_type = $(this).attr('data-chat-type');
            PopupChat.OnWsChat();
            PopupChat.HandleWsFile();
            PopupChat.GetListEmoji();
            PopupChat.HandleEmoji();

            /*var userID = $(ChatInbox.modal).find('#chat_private li .chat-post-id[data-post='+ PopupChat.params.post +']').attr('data-user');
            if(userID && fromChatList){
                fromChatList = false;
                ChatInbox.ChangeStatusUnreadMsg(userID);
                Default.ShowNotificationOnChat();
            }*/
        });
    },

    // Fetch data from server websocket to client
    FetchDataChat: function() {
        if (isMobile) {
            var data_link = PopupChat.GetSearchParam(window.location.search);
            PopupChat.params.post = data_link['post'];
            PopupChat.params.chat_type = data_link['chat_type'];
            PopupChat.params.previous_flag = data_link['previous-flag'];
            window.ws.onopen = function(){
                window.ws.send("fetch", {'post_id': PopupChat.params.post, 'chat_type': PopupChat.params.chat_type});
                $(PopupChat.parent).find('textarea').focus();
                PopupChat.OnclickLogin();
                PopupChat.OnWsChat();
                PopupChat.OnWsFile();
                PopupChat.HandleWsFile();
                PopupChat.GetListEmoji();
                PopupChat.HandleEmoji();
                PopupChat.ShowChatBox();
              }
        } else {
            // window.ws.send('fetch', {'post_id': PopupChat.params.post, 'chat_type': PopupChat.params.chat_type});
            window.ws.send('fetch', {'post_id': PopupChat.params.post, 'chat_type': PopupChat.params.chat_type, 'current_user': UserLogin});
        }
    },

    // Define commit message by enter or press send button
    OnWsChat: function(){
        if (isMobile) {
            var btn = $(PopupChat.parent).find('#msgForm .send');
            var formWsChat = $(PopupChat.parent).find('#msgForm');
        } else {
            var btn = $('#popup-chat-'+PopupChat.params.post).find('#msgForm .send');
            var formWsChat = $('#popup-chat-'+PopupChat.params.post).find('#msgForm');
        }
        formWsChat.off().on("keydown", function(event){
            if (event.keyCode == 13 && !event.shiftKey) {
                event.preventDefault();
                PopupChat.OnWsSendData(event.currentTarget);
            }
        });
        btn.unbind();
        btn.on("click", function(e){
            console.log('in OnWsChat');
            PopupChat.OnWsSendData(e.currentTarget);
        });
    },

    // Handle send data chat message
    OnWsSendData: function(e) {
      console.log('ws send data');
        var parent = $(e).parent();
        var val  = parent.find("textarea").val();
        if(val != ""){
            window.ws.send("send", {"type": 1, "msg": val,"room": PopupChat.params.post,"user_id": UserLogin, 'chat_type': PopupChat.params.chat_type});
            if (PopupChat.params.chat_type == 0) {
                window.ws.send("notify", {"sender": UserLogin, "receiver": -1,"room": PopupChat.params.post, "message": val});
            }
            if (PopupChat.params.chat_type == 1) {
                console.log('in chat_type 1');
                console.log(PopupChat.params.post);
                window.ws.send("discussion", {"sender": UserLogin, "receiver": -1,"room": PopupChat.params.post, "message": val, "chat_type": PopupChat.params.chat_type});

            }
            parent.find("textarea").val("");
            parent.find("textarea").focus();

            // var notify = $('.chat-post-id[data-post='+PopupChat.params.post+']').find('.title-description-user');
            // notify.find('.description-chat-inbox').html(val);
        }
    },

    // Define button file upload
    OnWsFile: function(){
        if (isMobile) {
            var btn = PopupChat.parent+' #file_btn';
            var btn_input = $(PopupChat.parent).find('#file_upload');
        } else {
            var btn = '#popup-chat-'+PopupChat.params.post+' #file_btn';
            var btn_input = $('#popup-chat-'+PopupChat.params.post).find('#file_upload');
        }
        $(document).on("click", btn, function(){
            btn_input.click();
        });
    },

    // Handle upload file from chat pop up
    HandleWsFile: function(){
        if (isMobile) {
            var parentChat = $(PopupChat.parent);
            var input_change = $(PopupChat.parent).find('#file_upload');
        } else {
            var parentChat = $('#popup-chat-'+PopupChat.params.post);
            var input_change = $('#popup-chat-'+PopupChat.params.post).find('#file_upload');
        }
        input_change.unbind('change');
        input_change.change(function(){
            if(typeof input_change[0].files[0] != "undefined"){
                var size_file = input_change[0].files[0].size;
                var type_file = input_change[0].files[0].type;

                // List of array support
                var array_type_support = [
                        "image/png",
                        "image/jpeg",
                        "image/pjpeg",
                        "image/gif",
                        "text/plain",
                        "application/msword",
                        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                        "application/excel",
                        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                        "application/vnd.ms-excel" ,
                        "application/x-excel" ,
                        "application/x-msexcel",
                        "application/mspowerpoint",
                        "application/powerpoint",
                        "application/vnd.ms-powerpoint",
                        "application/x-mspowerpoint",
                        "application/vnd.openxmlformats-officedocument.presentationml.presentation",
                        "application/pdf",
                        "audio/mpeg3",
                        "video/mpeg",
                        "video/avi",
                        "application/x-shockwave-flash",
                        "audio/wav, audio/x-wav",
                        "application/xml",
                        "image/x-icon"
                    ]
                file = input_change[0].files[0];

                fd = new FormData();
                fd.append('file', file);
                fd.append('post', PopupChat.params.post);
                if ((size_file > 12582912) || ($.inArray(type_file, array_type_support) === -1)) {
                    alert("Uploaded file is not supported or it exceeds the allowable limit of 12MB.");
                    input_change.val('');
                } else {
                    $.ajax({
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if(evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    percentComplete = parseInt(percentComplete * 100);
                                    parentChat.find(".loading_image").css('display', 'block');
                                }
                            }, false);
                            return xhr;
                        },
                        url:  baseUrl + "/netwrk/chat/upload",
                        type: "POST",
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function(result) {
                            var fileForm = $(PopupChat.parent).find('#msgForm');
                            val  = fileForm.find("textarea").val();
                            if(result != "" && result !== false){
                                var result = $.parseJSON(result);
                                window.ws.send("send", {"type" : result.type, "msg" : val, "file_name" : result.file_name,"room": PopupChat.params.post,"user_id": UserLogin, 'chat_type': PopupChat.params.chat_type});
                                if (PopupChat.params.chat_type == 0) {
                                    window.ws.send("notify", {"sender": UserLogin, "receiver": -1,"room": PopupChat.params.post, "message": val});
                                }
                                parentChat.find(".loading_image").css('display', 'none');
                                // fileForm.find("textarea").val('');
                            }
                        }
                    });
                }

            }
        });
    },

    //Append list emoji to icon emoji in chat pop up
    GetListEmoji: function(){
        var data = Emoji.GetEmoji();
        if (isMobile) {
            var parent = $(PopupChat.parent).find('.emoji .dropdown-menu');
        } else {
            var parent = $('#popup-chat-'+PopupChat.params.post).find('.emoji .dropdown-menu');
        }
        var template = _.template($( "#list_emoji" ).html());
        var append_html = template({emoji: data});
        if(PopupChat.status_emoji == 1){
            if ($(parent).find('.mCustomScrollBox').length <= 0) {
                parent.append(append_html);
                parent.mCustomScrollbar({
                    theme:"dark"
                });
                PopupChat.ConvertEmoji();
            }
        }
    },

    // Convert text emoji to icon emoji
    ConvertEmoji: function(){
        if (isMobile) {
            var strs  = $(PopupChat.parent).find('.emoji').find('.dropdown-menu li');
        } else {
            var strs  = $('#popup-chat-'+PopupChat.params.post).find('.emoji').find('.dropdown-menu li');
        }
        $.each(strs,function(i,e){
            Emoji.Convert($(e));
            // PopupChat.status_emoji = 0;
        });
    },

    //Handle emoji icon from user text or choose from emoji
    HandleEmoji: function(){
        if (isMobile) {
            var btn  = $(PopupChat.parent).find('.emoji').find('.dropdown-menu li');
            var parent = $(PopupChat.parent);
        } else {
            var btn = $('#popup-chat-'+PopupChat.params.post).find('.emoji').find('.dropdown-menu li');
            var parent = $('#popup-chat-'+PopupChat.params.post);
        }
        btn.unbind();
        btn.on('click',function(e){
            if (isMobile) {
                PopupChat.text_message = $('.nav_input_message').find('.send_message textarea').val();
            } else {
                PopupChat.text_message = $('#popup-chat-'+PopupChat.params.post).find('.send_message textarea').val();
            }
            PopupChat.text_message += $(e.currentTarget).attr('data-value') + ' ';
            parent.find('textarea').val(PopupChat.text_message);
            parent.find('textarea').focus();
        });
    },

    // Convert emoji when load data chat to popup
    FetchEmojiOne: function(data, popup_active){
        if (isMobile) {
            var messages = $('.post-id-'+popup_active).find(PopupChat.container).find('.message .content_message .content');
        } else {
            var messages = $('#popup-chat-'+popup_active).find(PopupChat.container).find('.message .content_message .content');
        }
        if(data.type === "fetch"){
            $.each(messages,function(i,e){
                Emoji.Convert($(e));
            });
        }else{
            Emoji.Convert(messages.last());
        }
    },

    // Underscore mission to append data
    getMessageTemplate:function(data,from){
        var popup_id = data["post_id"];
        if ($("#message_chat").length > 0 ) {
            var template = _.template($("#message_chat").html());
            var append_html = template({msg: data,baseurl: baseUrl});
            if (isMobile) {
                $('.post-id-'+popup_id).find(PopupChat.container).append(append_html);
                PopupChat.OnClickReceiverAvatarMobile();
            } else {
                $('#popup-chat-'+popup_id).find(PopupChat.container).append(append_html);

                if(from == 'single'){
                    /*$('#popup-chat-'+popup_id).find(PopupChat.container+' div:last-child').find('.user_thumbnail').hide().fadeIn(1000);*/
                    $('#popup-chat-'+popup_id).find(PopupChat.container+' div:last-child').find('.content_message').hide().fadeIn(2500);
                }

                PopupChat.OnClickReceiverAvatar();
            }
        }
        // PopupChat.OnClickParticipantAvatarMobile();
    },

    //Always scroll to bottom chat
    ScrollTopChat: function(popup_active){
        var popup_current = $('#popup-chat-'+popup_active);
        if(isMobile){
            if ($('#post_chat').length > 0) {
                $('#post_chat').find(PopupChat.container).scrollTop($(PopupChat.page).find('.container_post_chat')[0].scrollHeight);
            }
        }else{
            if (popup_current.length > 0) {
                if(PopupChat.scrollToMsg == 0){
                    popup_current.find('.popup-messages').mCustomScrollbar("scrollTo",$('#popup-chat-'+popup_active).find(PopupChat.container)[0].scrollHeight);
                } else {
                    popup_current.find('.popup-messages').mCustomScrollbar("scrollTo",$('#popup-chat-'+popup_active).find(PopupChat.container).find('#collapse'+PopupChat.scrollToMsg));
                }
            }
        }
    },

    CustomScrollBar: function(){
        var parent = $('#popup-chat-'+PopupChat.params.post).find('.popup-messages');
        parent.mCustomScrollbar({
            theme:"dark"
        });
    },

    /**
    * Mobile version
    **/
    // Redirect to the chat url
    RedirectChatPostPage: function(postId, chat_type, previous_flag)
    {
        window.location.href = baseUrl + "/netwrk/chat/chat-post?post="+ postId +"&chat_type="+chat_type+"&previous-flag=" + previous_flag;
    },

    // set height of mobile screen
    SetHeightContainerChat: function() {
        var size = get_size_window();
        var h_navSearch = $('.navbar-mobile').height();
        var h_header = $(PopupChat.page).find('.header').height();
        var btn_meet = $('#btn_meet_mobile').height()-10;
        var nav_message = $(PopupChat.page).find('.nav_input_message').height();
        var nav_bottom = $('.navigation-wrapper').height();

        var wh = size[1] - h_navSearch - h_header - nav_message;

        $(PopupChat.page).find('.container_post_chat').css('height',wh);
        $(PopupChat.page).find('.feedback-section').css('height',wh);
    },

    // Click chat icon on mobile version
    OnClickChatInboxBtnMobile: function() {
        var target = $('#chat_inbox_btn_mobile');
        target.unbind();
        target.on('click',function(){
            sessionStorage.url = window.location.href;
            ChatInbox.OnClickChatInboxMobile();
        });
    },

    // Get param from url
    GetSearchParam: function(url) {
        var query_string = {};
        var query = url.substring(1);
        var vars = query.split("&");
        for (var i=0;i<vars.length;i++) {
            var pair = vars[i].split("=");
                    // If first entry with this name
                    if (typeof query_string[pair[0]] === "undefined") {
                        query_string[pair[0]] = decodeURIComponent(pair[1]);
                    // If second entry with this name
                } else if (typeof query_string[pair[0]] === "string") {
                    var arr = [ query_string[pair[0]],decodeURIComponent(pair[1]) ];
                    query_string[pair[0]] = arr;
                    // If third or later entry with this name
                } else {
                    query_string[pair[0]].push(decodeURIComponent(pair[1]));
                }
            }
        return query_string;
    },

    OnClickBackBtn: function(){
        var BackBtn = $(PopupChat.parent).find('.back_page').add($('.box-navigation .btn_nav_map'));
        BackBtn.unbind();
        BackBtn.on('click',function(){
            if(isMobile){
                if(sessionStorage.landing_post == 1){
                    //Go to post by landing page
                    sessionStorage.landing_post = 0 ;
                    window.location.href = sessionStorage.url_landing;
                }else if(sessionStorage.feed_topic == 1){
                    sessionStorage.feed_topic = 0;
                    window.location.href = sessionStorage.url;
                }
                else if(sessionStorage.feed_post == 1){
                    sessionStorage.feed_post = 0;
                    window.location.href = sessionStorage.url;
                }
                else if (PopupChat.params.chat_type == 1 ) {
                    //Go to post
                    Post.RedirectPostPage($(PopupChat.parent).attr('data-topic'));
                } else {
                    window.location.href = baseUrl+'/netwrk/chat-inbox/'+'?chat-type=0';
                }
            }
        });
    },

    OnClickReceiverAvatar: function(){
        var avatar = $('.popup-box').find('.message_receiver .user_thumbnail'),
            disc = $('#popup-chat-' + PopupChat.params.post).find('.chat-discussion');
        avatar.unbind();
        avatar.on('click', function(e){
            user_view = $(e.currentTarget).parent().attr('data-user-id');
            pid = $(e.currentTarget).parent().attr('data-post-id');

            if(user_view != UserLogin){

                if(disc.length > 0){
                    Meet.pid = 0;
                    Meet.ez = user_view;
                }else{
                    Meet.pid = pid;
                    Meet.ez = 0;
                }

                $('.modal').modal('hide');
                $(Post.modal).modal('hide');
                Meet.initialize();
                setTimeout(function(){
                    Topic.displayPositionModal();
                }, 50);
            }
        });
    },

    OnClickReceiverAvatarMobile: function(){
        var avatar = $('#post_chat .container_post_chat .message_receiver .user_thumbnail'),
            post_id = $('#post_chat').attr('data-post'),
            chat_type = $('#post_chat').attr('data-chat-type');
        avatar.unbind();
        avatar.on('click', function(e){
            if(chat_type == 1){
                var user_id = $(e.currentTarget).attr('data-user-id');
                if (user_id != UserLogin){
                    window.location.href = baseUrl + "/netwrk/meet?user_id=" + user_id + "&from=discussion";
                }
            }else{
                window.location.href = baseUrl + "/netwrk/meet?post_id=" + post_id + "&from=private";
            }
        });
    },

    ShowPopupChatWhenModalDisplay: function(){
        setTimeout(function(){
            Default.displayPopupOnTop();
        }, 100);
    },

    OnClickMinimizeBtn: function() {
        var btn = $('#popup-chat-'+PopupChat.params.post).find('.minimize-btn');
        btn.unbind();
        btn.on('click', function(){
            var target = $(this).parents('.popup-box.chat-popup'),
            target_discussion = $(this).parents('.popup-box.chat-popup').find('.chat-discussion');
            if (target_discussion.length > 0) {
                if (target.css('height') == '36px') {
                    target.css('height', '330px');
                    target.find('.nav_input_message').css('display', 'block');
                    btn.css('bottom', '11px');
                } else {
                    target.css('height', '36px');
                    target.find('.nav_input_message').css('display', 'none');
                    btn.css('bottom', '17px');
                }
            } else {
                if (target.css('height') == '28px') {
                    target.css('height', '330px');
                    target.find('.nav_input_message').css('display', 'block');
                    btn.css('bottom', '5px');
                } else {
                    target.css('height', '28px');
                    target.find('.nav_input_message').css('display', 'none');
                    btn.css('bottom', '11px');
                }
            }
            PopupChat.MoveMeetButton();
        });
    },
}
