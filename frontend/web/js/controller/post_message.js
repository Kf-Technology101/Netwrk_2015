var Post_Message = {
    parent : $('#post_message'),
    params:{
        message: '',
        post_id: ''
    },
    status_change:{
        message: false
    },
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
            Post_Message.onCheckStatus();
        });
    },

    onCheckStatus: function(){
        var status = Post_Message.status_change;

        if(status.message){
            Post_Message.enableButton();
        } else {
            Post_Message.disableButton();
        }
    },

    enableButton: function(){
        var parent = Post_Message.parent,
            btn = parent.find('.save');
        btn.removeClass('disable');
        Post_Message.onClickSave();
    },

    disableButton: function(){
        var parent = Post_Message.parent,
            btn = parent.find('.save');
        btn.addClass('disable');
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
            btn = parent.find('.save');

        btn.unbind();
        btn.on('click',function(){
            Ajax.postMessage(Post_Message.params).then(function(data) {
                Post_Message.reset_data();
                Post_Message.disableButton();
                var json = $.parseJSON(data);
                if(isMobile){
                    window.location.href = baseUrl + "/netwrk/chat-inbox?current=area_news";
                } else {
                    Post_Message.hideModalPostMessage();
                }
            });
        });
    }
};