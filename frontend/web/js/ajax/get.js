var Ajax ={

    show_topic: function(params){
        var url,defer = $.Deferred();

        if (isMobile) {
            url = "get-topic-mobile";
        }else{
            url = "netwrk/topic/get-topic-mobile";
        }

        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },

    getUserMeeting: function(params){
        var url,defer = $.Deferred();

        if (isMobile) {
            url = "get-topic-mobile";
        }else{
            url = "netwrk/meet/get-user-meet";
        }

        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    }

}

