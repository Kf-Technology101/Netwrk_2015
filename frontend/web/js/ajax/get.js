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
            async: true,
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },

    getUserMeeting: function(params){
        var url,defer = $.Deferred();

        if (isMobile) {
            url = "get-user-meet";
        }else{
            url = "netwrk/meet/get-user-meet";
        }

        $.ajax({
            url: url,
            data: params,
            async: false,
            cache: false,
            type: 'GET',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },
    
    usermeet: function(params){
        var url,defer = $.Deferred();

        if (isMobile) {
            url = "user-meet";
        }else{
            url = "netwrk/meet/user-meet";
        }

        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            async: true,
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },

    usermet: function(params){
        var url,defer = $.Deferred();

        if (isMobile) {
            url = "user-met";
        }else{
            url = "netwrk/meet/user-met";
        }

        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            async: true,
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    }
}

