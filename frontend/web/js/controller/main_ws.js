/**
 * Main controller of socket JS
 * @type ws
 */
var MainWs ={

    url: '',
    ws: '',
    userLogin: '',

    initialize: function() {
        MainWs.setUrl();
        MainWs.wsConnect(UserLogin);
    },

    setUrl: function(){
        if(window.location.hostname == 'www.netwrk.com'){
            MainWs.url = 'www.netwrk.com:2311';
        } else if(window.location.hostname == 'dev.netwrk.com'){
            MainWs.url = 'dev.netwrk.com:2312';
        } else if(window.location.hostname == 'beta.netwrk.com'){
            MainWs.url = 'beta.netwrk.com:2312';
        } else if(window.location.hostname == 'test.netwrk.com'){
            MainWs.url = 'test.netwrk.com:2314';
        } else if(window.location.hostname == 'local.netwrk.com'){
            MainWs.url = 'local.netwrk.com:2311';
        } else {
            MainWs.url = '127.0.0.1:2311';
        }
    },

    wsConnect: function(user_id){

        var _self = this;

        window.ws = new ReconnectingWebSocket("ws://"+MainWs.url+"?user_id=" + user_id, null, {debug: false, reconnectInterval: 3000, timeoutInterval: 5000, reconnectDecay: 5});

        window.ws.onmessage = function(e){
          console.group("WS SEND");
          console.log("Sending: %o", $.parseJSON(e.data));
          console.groupEnd();
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
                    case 'discussion':
                        _self.events.discussion(msg);
                    break;
                }
            }
        }

        window.ws.onopen = function() {
          console.group("WS OPEN");
          console.log("Open connection");
          console.log(window.ws);
          console.groupEnd();
        }

        window.ws.onclose = function(e) {
          console.group("WS LOSING");
          console.log("Disconnected");
          console.groupEnd();
        }
    },

    events: {
        fetch: function(e) {
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
                /*if(isMobile){
                    fix_width_chat_post($(PopupChat.parent).find('.content_message'),$($(PopupChat.parent).find('.message')[0]).find('.user_thumbnail').width() + 50);
                }*/
                PopupChat.FetchEmojiOne({type: 'fetch'}, PopupChat.params.post);
            }
        },
        onliners: function(e){
        },
        single: function(e){
            // handle of chat
            var update_list_chat;
            var popup_active = e.data[0]['post_id'];
            var user_id = e.data[0]['id'];
            var chat_type = e.data[0]['chat_type'];
            var chat_list_user = $('[data-post='+e.data[0]['post_id']+']',ChatInbox.modal).attr('data-user');

            PopupChat.ScrollTopChat(popup_active);

            $.each(e.data, function(i, elem){
                PopupChat.message_type = elem.msg_type;
                PopupChat.getMessageTemplate(elem,'single');
                update_list_chat = $.parseJSON(elem.update_list_chat);
            });
            /*if(isMobile){
                fix_width_chat_post($(PopupChat.parent).find('.content_message'),$($(PopupChat.parent).find('.message')[0]).find('.user_thumbnail').width() + 50);
            }*/
            if(!isMobile){
                if (chat_type == 0) {
                    ChatInbox.getTemplateChatPrivateItem($("#chat_inbox").find('#chat_private ul'), update_list_chat, user_id, chat_list_user,popup_active);
                } else {
                    ChatInbox.getTemplateChatInbox($("#chat_inbox").find('#chat_discussion ul'), update_list_chat, user_id);
                }
            }
            if(PopupChat.message_type == 1){
                PopupChat.FetchEmojiOne({type: 'single'}, popup_active);
            }
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
                                    ChatInbox.getTemplateChatPrivate($("#chat_inbox").find('#chat_private ul'), update_list_chat, e.data.receive, e.data.sender);
                                }
                            }
                        } else {
                            var pchat = $('#popup-chat-' + e.data.room);
                            // if popup chat is opened
                            if (pchat.length != 0 && pchat.css('display') == 'block' && PopupChat.params.post == e.data.room) {
                                console.log('in user is online');
                                Ajax.update_notification_status(e.data.sender);
                                var notify = $('.chat-post-id[data-user='+e.data.sender+']').find('.title-description-user');
                                notify.find('.description-chat-inbox').html(e.data.message);
                                notify.find('.description-chat-inbox').removeClass('match-description');
                                notify.parent().find('.time-chat-inbox').html(e.data.recent_time);
                            } else {
                                console.log('in user is offline');
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
                                    ChatInbox.getTemplateChatPrivate($("#chat_inbox").find('#chat_private ul'), update_list_chat, e.data.receiver, e.data.sender);
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
        },
        discussion: function(e){
            console.log(e.data);
            if (e != null) {
                //if logged in user is participant of discussion then show notification
                console.log('UserLogin=>'+UserLogin);
                if ((jQuery.inArray(UserLogin, e.data.receivers) !== -1)) { // if receiver is current user, display notification
                    if (isMobile) {                               // if mobile

                    } else {
                        var pchat = $('#popup-chat-' + e.data.room);
                        // if popup chat is opened
                        if (pchat.length != 0 && pchat.css('display') == 'block' && PopupChat.params.post == e.data.room) {
                            console.log('in discussion if user is online');
                            Ajax.update_discussion_notification_status(UserLogin, e.data.room);
                            var notify = $('.chat-post-id[data-post='+e.data.room+']').find('.title-description-user');
                            notify.find('.description-chat-inbox').html(e.data.message);
                            notify.find('.description-chat-inbox').removeClass('match-description');
                            notify.parent().find('.time-chat-inbox').html(e.data.recent_time);
                        } else {
                            console.log('in discussion if user is offline');
                            // if popup chat is closed
                            var notify = $('.chat-post-id[data-post='+e.data.room+']').find('.title-description-user'),
                                chat_notify = $('#chat_inbox_btn').find('.notify');
                            if(e.data.notification_count > 0){
                                notify.find('.description-chat-inbox').html(e.data.message);
                                notify.find('.notify-chat-inbox').html(e.data.notification_count);
                                notify.find('.notify-chat-inbox').removeClass('disable');
                                chat_notify.html(e.data.notification_count);
                                chat_notify.removeClass('disable');
                            }
                        }
                    }
                }
            }
        }
    }
};
