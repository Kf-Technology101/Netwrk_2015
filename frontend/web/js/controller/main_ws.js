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
            MainWs.url = "127.0.0.1";
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
                    // handle fetch data
                },
                onliners: function(e){
                    // handle user online
                    console.log('onliners');
                },
                single: function(e){
                    console.log('single');
                    // handle of chat
                },
                notify: function(e){
                    // handle notify
                }
            }
        });
    }
};