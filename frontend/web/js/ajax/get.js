var Ajax ={

    show_topic: function(params){
        var url,defer = $.Deferred();

        if (isMobile) {
            url = baseUrl +"/netwrk/topic/get-topic-mobile";
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
            url = baseUrl +"/netwrk/meet/get-user-meet";
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
            url = baseUrl +"/netwrk/meet/";
        }else{
            url = baseUrl +"netwrk/meet/";
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
            url = baseUrl + "/netwrk/meet/user-met";
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
    },

    userprofile: function(){
        var url,defer = $.Deferred();

        if (isMobile) {
            url = baseUrl + "/netwrk/setting/load-profile";
        }else{
            url = "netwrk/meet/get-user-meet";
        }

        $.ajax({
            url: url,
            // data: params,
            async: false,
            cache: false,
            type: 'GET',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },

    update_profile: function(params){
        var url,defer = $.Deferred();

        if (isMobile) {
            url = baseUrl +"/netwrk/setting/update-profile";
        }else{
            url = "netwrk/meet/get-user-meet";
        }

        $.ajax({
            url: url,
            data: params,
            async: false,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },

    upload_image:function(params){
        var url,defer = $.Deferred();

        if (isMobile) {
            url = baseUrl +"/netwrk/setting/upload-image";
        }else{
            url = "netwrk/meet/get-user-meet";
        }

        $.ajax({
            url: url,
            data: params,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    }
}

