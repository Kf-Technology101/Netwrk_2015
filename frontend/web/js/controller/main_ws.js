var MainWs ={

    url: '',
    ws: '',
    userLogin: '',

    initialize: function() {
        MainWs.setUrl();
        MainWs.wsConnect(UserLogin);
    },

    setUrl: function(){
        if(baseUrl === 'http://netwrk.rubyspace.net'){
            MainWs.url = 'box.rubyspace.net';
        }else{
            MainWs.url = window.location.host;
        };
    },

    wsConnect: function(user_id){

        var _self = this;

        window.ws = new ReconnectingWebSocket("ws://"+MainWs.url+":2311?user_id=" + user_id);

        window.ws.onmessage = function(e){
            if (e.data) {
                msg = $.parseJSON(e.data);
                switch(msg.type) {
                    case 'fetch':
                        _self.events.fetch(msg);
                    break;
                    case 'onliners':
                        _self.events.onliners(msg);
                    break;
                    case 'single':
                        _self.events.single(msg);
                    break;
                    case 'notify':
                        _self.events.notify(msg);
                    break;
                }
            }
        }

    },

    events: {
        fetch: function(e) {
            console.log('fetch');
            PopupChat.msg_lenght = e.data.length;
            if (PopupChat.msg_lenght > 0) {
                var message = $('#popup-chat-'+e.data[0]['post_id']).find(PopupChat.container).find('.message');
                if (message.length > 0) {
                    message.remove();
                }
                $.each(e.data, function(i, elem){
                    PopupChat.getMessageTemplate(elem);
                    PopupChat.ScrollTopChat(elem.post_id);
                });
                $('#popup-chat-'+e.data[0]['post_id']).find('textarea').focus();
                if(isMobile){
                    fix_width_chat_post($(PopupChat.parent).find('.content_message'),$($(PopupChat.parent).find('.message')[0]).find('.user_thumbnail').width() + 50);
                }
                PopupChat.FetchEmojiOne({type: 'fetch'}, PopupChat.params.post);
            }
        },
        onliners: function(e){
            console.log('Onliner');
        },
        single: function(e){
            console.log('single');
            // handle of chat
            var update_list_chat;
            var popup_active = e.data[0]['post_id'];
            var user_id = e.data[0]['id'];
            var chat_type = e.data[0]['chat_type'];
            $.each(e.data, function(i, elem){
                PopupChat.message_type = elem.msg_type;
                PopupChat.getMessageTemplate(elem);
                update_list_chat = $.parseJSON(elem.update_list_chat);
            });
            if(isMobile){
                fix_width_chat_post($(PopupChat.parent).find('.content_message'),$($(PopupChat.parent).find('.message')[0]).find('.user_thumbnail').width() + 50);
            } else {
                if (chat_type == 0) {
                    ChatInbox.getTemplateChatPrivate($("#chat_inbox").find('#chat_private ul'), update_list_chat, user_id);
                } else {
                    ChatInbox.getTemplateChatInbox($("#chat_inbox").find('#chat_discussion ul'), update_list_chat, user_id);
                }
            }
            if(PopupChat.message_type == 1){
                PopupChat.FetchEmojiOne({type: 'single'}, popup_active);
            }
            PopupChat.ScrollTopChat(popup_active);
        },
        notify: function(e){
            if (e != null) {                                          // check data returned
                if (e.data.ismeet == 0) {                             // if message unread
                    if (e.data.receiver == UserLogin) {               // if receiver is current user, display notification
                        if (isMobile) {                               // if mobile
                            var chat_box = $('#post_chat');
                            if (chat_box.length != 0){                 // if chat page is opened
                                var post_id = chat_box.attr('data-post');
                                if (post_id == e.data.room) {
                                    Ajax.update_notification_status(e.data.sender);
                                }
                            } else {                                   // if chat page is closed
                                var notify = $('.chat-post-id[data-user='+e.data.sender+']').find('.title-description-user'),
                                    chat_notify = $('#chat_inbox_btn_mobile').find('.notify');
                                if (e.data.msg_count > 0 && e.data.chat_count > 0) {
                                    notify.find('.description-chat-inbox').html(e.data.message);
                                    notify.find('.description-chat-inbox').removeClass('match-description');
                                    notify.find('.notify-chat-inbox').html(e.data.msg_count);
                                    notify.find('.notify-chat-inbox').removeClass('disable');
                                    chat_notify.html(e.data.chat_count);
                                    chat_notify.removeClass('disable');
                                    // notify.parent().find('.time-chat-inbox').html(e.data.recent_time);
                                    // update list chat
                                    update_list_chat = $.parseJSON(e.data.update_list_chat);
                                    ChatInbox.getTemplateChatPrivate($("#chat_inbox").find('#chat_private ul'), update_list_chat, e.data.receiver);
                                }
                            }
                        } else {
                            var pchat = $('#popup-chat-' + e.data.room);
                            // if popup chat is opened
                            if (pchat.length != 0 && pchat.css('display') == 'block' && PopupChat.params.post == e.data.room) {
                                Ajax.update_notification_status(e.data.sender);
                                var notify = $('.chat-post-id[data-user='+e.data.sender+']').find('.title-description-user');
                                notify.find('.description-chat-inbox').html(e.data.message);
                                notify.find('.description-chat-inbox').removeClass('match-description');
                                notify.parent().find('.time-chat-inbox').html(e.data.recent_time);
                            } else {
                                // if popup chat is closed
                                var notify = $('.chat-post-id[data-user='+e.data.sender+']').find('.title-description-user'),
                                    chat_notify = $('#chat_inbox_btn').find('.notify');
                                if(e.data.msg_count > 0 && e.data.chat_count > 0){
                                    notify.find('.description-chat-inbox').html(e.data.message);
                                    notify.find('.notify-chat-inbox').html(e.data.msg_count);
                                    notify.find('.notify-chat-inbox').removeClass('disable');
                                    chat_notify.html(e.data.chat_count);
                                    chat_notify.removeClass('disable');
                                    // notify.parent().find('.time-chat-inbox').html(e.data.recent_time);
                                    // update list chat
                                    update_list_chat = $.parseJSON(e.data.update_list_chat);
                                    ChatInbox.getTemplateChatPrivate($("#chat_inbox").find('#chat_private ul'), update_list_chat, e.data.receiver);
                                }
                            }
                        }
                    }
                } else {
                    ChatInbox.GetDataListChatPrivate();
                    Default.ShowNotificationOnChat();
                    setTimeout(function(){
                        var notify = $('.chat-post-id[data-user='+e.data.sender+']').find('.title-description-user'),
                        chat_notify = $('#chat_inbox_btn_mobile').find('.notify');
                        if (e.data.msg_count > 0 && e.data.chat_count > 0) {
                            notify.find('.description-chat-inbox').html(e.data.message);
                            notify.find('.notify-chat-inbox').html(e.data.msg_count);
                            notify.find('.notify-chat-inbox').removeClass('disable');
                            chat_notify.html(e.data.chat_count);
                            chat_notify.removeClass('disable');
                        }
                    }, 400);
                }
            }
        }
    }
};