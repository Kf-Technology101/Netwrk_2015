var MainWs ={
    url: '',
    userLogin: '',
    initialize: function() {
        MainWs.setUrl();
        window.connect = MainWs.wsConnect(UserLogin);
    },

    setUrl: function(){
        if(baseUrl === 'http://netwrk.rubyspace.net'){
            MainWs.url = 'box.rubyspace.net';
        }else{
            MainWs.url = window.location.host;
        };
    },

    wsConnect: function(user_id){
        window.ws = $.websocket("ws://"+MainWs.url+":2311?user_id=" + user_id, {
            open: function() {
                console.log('open');
                // handle when socket is opened
            },
            close: function() {
                console.log('close');
                // handle when connection close
            },
            events: {
                fetch: function(e) {
                    console.log('fetch');
                    PopupChat.msg_lenght = e.data.length;
                    $.each(e.data, function(i, elem){
                        PopupChat.getMessageTemplate(elem);
                        PopupChat.ScrollTopChat(elem.post_id);
                    });
                    // if(isMobile){
                    //     fix_width_chat_post($(PopupChat.parent).find('.content_message'),$($(PopupChat.parent).find('.message')[0]).find('.user_thumbnail').width() + 50);
                    // }
                    PopupChat.FetchEmojiOne({type: 'fetch'}, PopupChat.params.post);
                },
                onliners: function(e){
                    // handle user online
                    console.log('onliners');
                },
                single: function(e){
                    console.log('single');
                    // handle of chat
                    var update_list_chat;
                    var popup_active = e.data[0]['post_id'];
                    var user_id = e.data[0]['id'];
                    $.each(e.data, function(i, elem){
                        PopupChat.message_type = elem.msg_type;
                        PopupChat.getMessageTemplate(elem);
                        update_list_chat = $.parseJSON(elem.update_list_chat);
                    });
                    if(isMobile){
                        fix_width_chat_post($(PopupChat.parent).find('.content_message'),$($(PopupChat.parent).find('.message')[0]).find('.user_thumbnail').width() + 50);
                    } else {
                        ChatInbox.getTemplateChatInbox($("#chat_inbox").find('#chat_discussion ul'), update_list_chat, user_id);
                    }
                    if(PopupChat.message_type == 1){
                        PopupChat.FetchEmojiOne({type: 'single'}, popup_active);
                    }
                    PopupChat.ScrollTopChat(popup_active);
                },
                notify: function(e){
                    // handle notify
                }
            }
        });
    }
};