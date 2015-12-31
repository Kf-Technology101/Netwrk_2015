var PopupChat = {
    params:{
        post:'',
        chat_type: 1,
        post_name: '',
        post_description: '',
        post_avatar: '',
        previous_flag: '',
    },
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
        PopupChat.SetDataChat();
        PopupChat.FetchDataChat();
        PopupChat.OnclickLogin();
        if(isMobile){
            PopupChat.SetHeightContainerChat();
            PopupChat.OnClickChatInboxBtnMobile();
            ChatInbox.HideMeetIconMobile();
            PopupChat.OnClickBackBtn();
        }else{
            PopupChat.RegisterPopup();
            PopupChat.ChangeStylePopupChat();
            PopupChat.HandleWsFile();
            PopupChat.GetListEmoji();
            PopupChat.HandleEmoji();
            PopupChat.CustomScrollBar();
            // PopupChat.OnClickReceiverAvatar();
            PopupChat.ShowChatBox(PopupChat.params.post);
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
        var right = 330,
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

        min_popup_right = Math.min.apply(Math, popup_rights)
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
        PopupChat.popups.push(PopupChat.params.post);
        PopupChat.CalculatePopups();
        PopupChat.MoveMeetButton();
    },

    getTemplate: function(){
        var list_template = _.template($("#popup_chat").html());
        if (PopupChat.params.chat_type == 0) {
            var append_html = list_template({post_id: PopupChat.params.post, chat_type: PopupChat.params.chat_type, post_name: PopupChat.params.post_name, post_avatar: PopupChat.params.post_avatar});
        } else {
            var append_html = list_template({post_id: PopupChat.params.post, chat_type: PopupChat.params.chat_type, post_name: PopupChat.params.post_name, post_description: PopupChat.params.post_description});
        }

        $('.map_content').append(append_html);
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
    },

    ChangeStylePopupChat: function() {
        var id = PopupChat.params.post;


        $("#textarea-" + id).on("focus", function() {
            $("#popup-chat-" + id + " .popup-head").css("background-color", PopupChat.active_color);
        });
        $("#textarea-" + id).on("focusout", function() {
            $("#popup-chat-" + id + " .popup-head").css("background-color", PopupChat.inactive_color);
        });

        $("#popup-chat-" + id).on("click", function() {
            $(PopupChat.popup_chat_class + " .popup-head").css("background-color", PopupChat.inactive_color);
            $("#popup-chat-" + id + " .popup-head").css("background-color", PopupChat.active_color);
        });

        $("body").mouseup(function (e) {
            var container = $("#popup-chat-" + id);

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $("#popup-chat-" + id + " .popup-head").css("background-color", PopupChat.inactive_color);
            }
        });
    },

    MoveMeetButton: function() {
        var have_popup = false,
            top_btnMeet;

        if (!$('#btn_meet').data('original_top')) {
            $('#btn_meet').data('original_top', parseInt($('#btn_meet').css('top')))
        }
        top_btnMeet = $('#btn_meet').data('original_top');

        $(PopupChat.popup_chat_class).each(function() {
            var id = this.id;
            if ($('#' + id).css('display') == 'block') {
                have_popup = true;
                return false;
            }
        });

        if (have_popup && parseInt($('#btn_meet').css('top')) == top_btnMeet) {
            $('#btn_meet').css('top', top_btnMeet - 322 + 'px');
        } else if (!have_popup) {
            $('#btn_meet').css('top', top_btnMeet + 'px');
        }
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
                    console.log('come here no login');
                    $(this).find('.send_message.login').removeClass('active');
                    $(this).find('.send_message.no-login').addClass('active');
                }else{
                    console.log('come here  login');
                    $(this).find('.send_message.no-login').removeClass('active');
                    $(this).find('.send_message.login').addClass('active');
                }
            });
        }
    },

    OnclickLogin: function(){
        var btn = $(PopupChat.parent).find('.send_message.no-login .input-group-addon');

        btn.unbind();
        btn.on('click',function(){
            if(isMobile){
                window.location.href = baseUrl + "/netwrk/user/login?url_callback="+ $(PopupChat.parent).find('.send_message').attr('data-url');
            }else{
                $(PopupChat.parent).hide();
                Login.modal_callback = PopupChat;
                Login.initialize();
                return false;
            }
        });
    },

    SetUrl: function(){
        if(baseUrl === 'http://netwrk.rubyspace.net'){
            PopupChat.url = 'box.rubyspace.net';
        }else{
            PopupChat.url = window.location.href;
        };

    },

    // Set info for each popup chat when user active or open the popup
    SetDataChat: function(){
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

            PopupChat.params.post = $(this).attr('data-id');
            PopupChat.params.chat_type = $(this).attr('data-chat-type');
            PopupChat.OnWsChat();
            PopupChat.OnWsFile();
            PopupChat.HandleWsFile();
            PopupChat.GetListEmoji();
            PopupChat.HandleEmoji();

            var userID = $(ChatInbox.modal).find('#chat_private li .chat-post-id[data-post='+ PopupChat.params.post +']').attr('data-user');
            if(userID){
                ChatInbox.ChangeStatusUnreadMsg(userID);
                Default.ShowNotificationOnChat();
            }
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
                window.ws.send('fetch', {'post_id': PopupChat.params.post, 'chat_type': PopupChat.params.chat_type});
                PopupChat.OnWsChat();
                PopupChat.OnWsFile();
                PopupChat.HandleWsFile();
                PopupChat.GetListEmoji();
                PopupChat.HandleEmoji();
                PopupChat.ShowChatBox();
              }
        } else {
            // window.ws.send('fetch', {'post_id': PopupChat.params.post, 'chat_type': PopupChat.params.chat_type});
            window.ws.send('fetch', {'post_id': PopupChat.params.post, 'chat_type': PopupChat.params.chat_type});
        }
    },

    // Define commit message by enter or press send button
    OnWsChat: function(){
        if (isMobile) {
            var btn = $(PopupChat.parent).find('.send');
            var formWsChat = $(PopupChat.parent).find('#msgForm');
        } else {
            var btn = $('#popup-chat-'+PopupChat.params.post).find('.send');
            var formWsChat = $('#popup-chat-'+PopupChat.params.post).find('#msgForm');
        }
        formWsChat.on("keydown", function(event){
            if (event.keyCode == 13 && !event.shiftKey) {
                event.preventDefault();
                PopupChat.OnWsSendData(event.currentTarget);
            }
        });
        btn.unbind();
        btn.on("click", function(e){
            PopupChat.OnWsSendData(e.currentTarget);
        });
    },

    // Handle send data chat message
    OnWsSendData: function(e) {
        var parent = $(e).parent();
        var val  = parent.find("textarea").val();
        if(val != ""){
            window.ws.send("send", {"type": 1, "msg": val,"room": PopupChat.params.post,"user_id": UserLogin, 'chat_type': PopupChat.params.chat_type});
            if (PopupChat.params.chat_type == 0) {
                window.ws.send("notify", {"sender": UserLogin, "receiver": -1,"room": PopupChat.params.post, "message": val});
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
            var btn = $(PopupChat.parent).find('#file_btn');
        } else {
            var btn = $('#popup-chat-'+PopupChat.params.post).find('#file_btn');
        }
        btn.unbind();
        btn.on("click", function(){
            var btn_input = $(PopupChat.parent).find('#file_upload');
            btn_input.click();
        });
    },

    // Handle upload file from chat pop up
    HandleWsFile: function(){
        var parentChat = $(PopupChat.parent);
        var input_change = $(PopupChat.parent).find('#file_upload');
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
                                parentChat.find(".loading_image").css('display', 'none');
                                fileForm.find("textarea").val('');
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
            PopupChat.text_message = $(PopupChat.parent).find('.send_message textarea').val();
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
    getMessageTemplate:function(data){
        var popup_id = data["post_id"];
        var template = _.template($("#message_chat").html());
        var append_html = template({msg: data,baseurl: baseUrl});
        if (isMobile) {
            $('.post-id-'+popup_id).find(PopupChat.container).append(append_html);
        } else {
            $('#popup-chat-'+popup_id).find(PopupChat.container).append(append_html);
        }
        // PopupChat.OnClickParticipantAvatarMobile();
        PopupChat.OnClickReceiverAvatar();
    },

    //Always scroll to bottom chat
    ScrollTopChat: function(popup_active){
        console.log('scroll');
        var popup_current = $('#popup-chat-'+popup_active);
        if(isMobile){
            $('#post_chat').find(PopupChat.container).scrollTop($(PopupChat.page).find('.container_post_chat')[0.].scrollHeight);
        }else{
            if (popup_current.length != 0) {
                popup_current.find('.popup-messages').mCustomScrollbar("scrollTo",$('#popup-chat-'+popup_active).find(PopupChat.container)[0].scrollHeight);
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
        var btn_meet = $('#btn_meet_mobile').height()-40;
        var nav_message = $(PopupChat.page).find('.nav_input_message').height();
        var wh = size[1] - h_navSearch -h_header - btn_meet - nav_message;
        $(PopupChat.page).find('.container_post_chat').css('height',wh);
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
        var BackBtn = $(PopupChat.parent).find('.back_page');
        BackBtn.unbind();
        BackBtn.on('click',function(){
            if(isMobile){
                if (PopupChat.params.chat_type == 1 ) {
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

            }
        });
    },

}
