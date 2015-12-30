var PopupChat = {
    params:{
        post:'',
        chat_type: 1,
        post_name: '',
        post_description: '',
        post_avatar: '',
    },
    total_popups: 0,
    max_total_popups: 4,
    popup_chat_class: '.popup-box.chat-popup',
    inactive_color: "#5888ac",
    active_color: "#5da5d8",
    popups: [],
    url:'',
    page:'#popup_chat',
    modal:'#popup_chat_modal',
    parent: '',
    container: '',
    status_emoji: 1,
    text_message:'',
    message_type:1,
    msg_lenght: 0,
    temp_post: 0,

    initialize: function() {
        PopupChat.SetUrl();
        PopupChat.SetDataChat();
        PopupChat.FetchDataChat();
        if(isMobile){

        }else{
            PopupChat.RegisterPopup();
            PopupChat.ChangeStylePopupChat();
            PopupChat.HandleWsFile();
            PopupChat.GetListEmoji();
            PopupChat.HandleEmoji();
            PopupChat.CustomScrollBar();
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
            var id = this.id
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

    SetUrl: function(){
        if(baseUrl === 'http://netwrk.rubyspace.net'){
            PopupChat.url = 'box.rubyspace.net';
        }else{
            PopupChat.url = window.location.href;
        };

    },

    // Set info for each popup chat when user active or open the popup
    SetDataChat: function(){
        if(!isMobile){
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
        });
    },


    // Fetch data from server websocket to client
    FetchDataChat: function() {
        window.ws.send('fetch', {'post_id': PopupChat.params.post, 'chat_type': PopupChat.params.chat_type});
    },


    // Define commit message by enter or press send button
    OnWsChat: function(){
        var btn = $('#popup-chat-'+PopupChat.params.post).find('.send');
        var formWsChat = $('#popup-chat-'+PopupChat.params.post).find('#msgForm');
        formWsChat.on("keydown", function(e){
            if (event.keyCode == 13 && !event.shiftKey) {
                e.preventDefault();
                PopupChat.OnWsSendData(e.currentTarget);
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
            parent.find("textarea").val("");
            parent.find("textarea").focus();
        }
    },

    // Define button file upload
    OnWsFile: function(){
        var btn = $('#popup-chat-'+PopupChat.params.post).find('#file_btn');
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
        var parent = $('#popup-chat-'+PopupChat.params.post).find('.emoji .dropdown-menu');
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

    ConvertEmoji: function(){
        var strs  = $('#popup-chat-'+PopupChat.params.post).find('.emoji').find('.dropdown-menu li');
        $.each(strs,function(i,e){
            Emoji.Convert($(e));
            // PopupChat.status_emoji = 0;
        });
    },

    HandleEmoji: function(){
        var btn = $('#popup-chat-'+PopupChat.params.post).find('.emoji').find('.dropdown-menu li');
        btn.unbind();
        btn.on('click',function(e){
            PopupChat.text_message = $(PopupChat.parent).find('.send_message textarea').val();
            PopupChat.text_message += $(e.currentTarget).attr('data-value') + ' ';
            $('#popup-chat-'+PopupChat.params.post).find('textarea').val(PopupChat.text_message);
            $('#popup-chat-'+PopupChat.params.post).find('textarea').focus();
        });
    },

    FetchEmojiOne: function(data, popup_active){
        var messages = $('#popup-chat-'+popup_active).find(PopupChat.container).find('.message .content_message .content');
        if(data.type === "fetch"){
            $.each(messages,function(i,e){
                Emoji.Convert($(e));
            });
        }else{
            Emoji.Convert(messages.last());
        }
    },

    getMessageTemplate:function(data){
        var popup_id = data["post_id"];
        var template = _.template($("#message_chat").html());
        var append_html = template({msg: data,baseurl: baseUrl});
        $('#popup-chat-'+popup_id).find(PopupChat.container).append(append_html);
        // PopupChat.OnClickParticipantAvatarMobile();z
    },

    ScrollTopChat: function(popup_active){
        console.log('scroll');
        var popup_current = $('#popup-chat-'+popup_active);
        if(isMobile){
            popup_current.find(PopupChat.container).scrollTop($('#popup-chat-'+popup_active).find(PopupChat.container).scrollHeight);
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

}
