var Post_Message = {
    parent : $('#post_message'),
    params:{
        message: '',
        post_id: '',
        msg_type: 1
    },
    status_change:{
        message: false
    },
    status_emoji: 1,
    initialize: function(){
        if(isMobile){
            var postId = Post_Message.parent.attr('data-post_id'),
                lat = Post_Message.parent.attr('data-lat'),
                lng = Post_Message.parent.attr('data-lng');
            Post_Message.params.post_id = postId;

            Post_Message.getPostLocation(lat,lng);
        } else {
            if(isGuest){
                Login.modal_callback = Post_Message;
                Login.initialize();
                return false;
            }

            Ajax.getMessagePostDetails().then(function(data) {
                var json = $.parseJSON(data);
                Post_Message.params.post_id = json.post_id;
                Post_Message.parent.find('.line-input').val(json.post_title);
                var lat = json.city_lat,
                    lng = json.city_lng;
                Post_Message.showModalPostMessage();

                Post_Message.getPostLocation(lat,lng);
            });
        }

        Post_Message.onClickBack();
        Post_Message.changeData();
        Post_Message.GetListEmoji();
        Post_Message.HandleEmoji();
        Post_Message.OnWsFile();
        Post_Message.HandleWsFile();
    },

    // Append list emoji to icon emoji in message interface
    GetListEmoji: function(){
        var data = Emoji.GetEmoji(),
            parent = Post_Message.parent.find('.emoji .dropdown-menu'),
            template = _.template($( "#message_list_emoji" ).html()),
            append_html = template({emoji: data});

        if(Post_Message.status_emoji == 1){
            if ($(parent).find('.mCustomScrollBox').length <= 0) {
                parent.append(append_html);
                parent.mCustomScrollbar({
                    theme:"dark"
                });
                Post_Message.ConvertEmoji();
            }
        }
    },

    // Convert text emoji to icon emoji
    ConvertEmoji: function(){
        var strs  = Post_Message.parent.find('.emoji').find('.dropdown-menu li');

        $.each(strs,function(i,e){
            Emoji.Convert($(e));
        });
    },

    // Handle emoji icon from user text or choose from emoji
    HandleEmoji: function(){
        var parent = Post_Message.parent,
            btn  = Post_Message.parent.find('.emoji').find('.dropdown-menu li');

        btn.unbind();
        btn.on('click',function(e){
            Post_Message.params['message'] = $('.nav_input_message').find('.send_message textarea').val();
            Post_Message.params['message'] += $(e.currentTarget).attr('data-value') + ' ';
            Post_Message.status_change['message'] = true;
            Post_Message.onClickSave();

            parent.find('textarea').val(Post_Message.params['message']);
            parent.find('textarea').focus();
        });
    },

    OnWsFile: function(){
        var btn = Post_Message.parent.find('#msgFileBtn');
        var btn_input = Post_Message.parent.find('#msgFileUpload');

        btn.unbind();
        btn.on('click',function(e){
            btn_input.click();
        });
    },

    // Handle upload file from message interface
    HandleWsFile: function(){
        var parentChat = Post_Message.parent;
        var input_change = Post_Message.parent.find('#msgFileUpload');

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
                ];

                file = input_change[0].files[0];

                fd = new FormData();
                fd.append('file', file);
                fd.append('post', Post_Message.params.post_id);
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
                            var val  = Post_Message.parent.find("textarea").val();
                            if(result != "" && result !== false){
                                var result = $.parseJSON(result);
                                Post_Message.params.message = result.file_name;
                                Post_Message.params.msg_type = 2;
                                Ajax.postMessage(Post_Message.params).then(function(data) {
                                    Post_Message.reset_data();
                                    var json = $.parseJSON(data);
                                    if(isMobile){
                                        window.location.href = baseUrl + "/netwrk/chat-inbox?current=area_news";
                                    } else {
                                        Default.getFeeds();
                                        Post_Message.hideModalPostMessage();
                                    }
                                });
                            }
                        }
                    });
                }
            }
        });
    },

    hideModalPostMessage:function(){
        var parent = Post_Message.parent;
        parent.modal('hide');
    },

    showModalPostMessage: function(){
        var parent = Post_Message.parent;

        parent.modal({
            backdrop: true,
            keyboard: false
        }).removeAttr("style").css("display", "block");

        Common.CustomScrollBar(parent.find('.modal-body'));
        Common.centerPositionModal();
    },

    onClickBack: function(){
        var parent = Post_Message.parent.find('.back_page span');

        parent.unbind();
        parent.click(function(){
            if(isMobile){
                window.location.href = baseUrl + "/netwrk/chat-inbox?current=area_news";
            }else{
                Post_Message.reset_data();
                Post_Message.disableButton();
                Post_Message.hideModalPostMessage();
            }
        });
    },

    // Get post location
    getTemplatePostLocation: function(parent,data){
        var json = data;
        var target = parent.find('.post-location-content');

        var list_template = _.template($("#message-location-template").html());
        var append_html = list_template({data: json});
        target.append(append_html);
    },

    getPostLocation: function(lat, lng){
        var parent = Post_Message.parent;
        parent.find('.post-location-content').html('');
        var params = {'lat': lat, 'lng': lng};
        Ajax.getPostLocation(params).then(function(data){
            var json = $.parseJSON(data);
            if(json.success == true){
                Post_Message.getTemplatePostLocation(parent,json);
                // Google street view api to get location image
                parent.find('#messageLocationImage').find('img').attr({'src' : 'http://maps.googleapis.com/maps/api/streetview?size=600x240&sensor=false&location='+encodeURI(json.formatted_address)+'&key='+Common.google.apiKey});
                parent.find('#messageLocationImage').removeClass('hide');
            }
        });
    },

    changeData: function(){
        var parent = Post_Message.parent;

        this.onChangeData(parent.find('.post_message'),'message');
    },

    onChangeData: function(target,filter){
        target.unbind();
        target.on('keyup input',function(e){
            if($(e.currentTarget).val().length > 0){
                Post_Message.params[filter] = $(e.currentTarget).val();
                Post_Message.status_change[filter] = true;
            }else{
                Post_Message.status_change[filter] = false;
            }
            Post_Message.onClickSave();
        });
    },

    reset_data: function(){
        var parent = Post_Message.parent;

        parent.find('.line-input').val('');
        parent.find('.post_message').val('');
        parent.find('.post-location-content').html('');

        Post_Message.status_change.message = false;
    },

    onClickSave: function(){
        var parent = Post_Message.parent,
            btn = parent.find('.send');

        btn.unbind();
        btn.on('click',function(){
            Ajax.postMessage(Post_Message.params).then(function(data) {
                Post_Message.reset_data();
                var json = $.parseJSON(data);
                if(isMobile){
                    window.location.href = baseUrl + "/netwrk/chat-inbox?current=area_news";
                } else {
                    Default.getFeeds();
                    Post_Message.hideModalPostMessage();
                }
            });
        });
    }
};