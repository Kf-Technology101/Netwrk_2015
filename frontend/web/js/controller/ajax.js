var Ajax ={
    chat_post_name: function(params){
        var url,defer = $.Deferred();

            url = baseUrl + "/netwrk/chat/chat-name";

        $.ajax({
            url: url,
            data: params,
            async: true,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },
    vote_post: function(params){
        var url,defer = $.Deferred();

            url = baseUrl + "/netwrk/post/vote-post";

        $.ajax({
            url: url,
            data: params,
            async: true,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },
    get_topic: function(params){
        var url,defer = $.Deferred();

            url = baseUrl + "/netwrk/topic/get-topic";

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

    update_view_topic: function(params){
        var url,defer = $.Deferred();

            url = baseUrl + "/netwrk/topic/update-view-topic";

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

    get_post_by_topic:function(params){
        var url,defer = $.Deferred();

            url = baseUrl + "/netwrk/post/get-all-post";

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

    get_position_user: function(){
        var url,defer = $.Deferred();

            url = baseUrl + "/netwrk/default/get-user-position";

        $.ajax({
            url: url,
            // data: params,
            async: false,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },

    get_top_post: function(params){
        var url,defer = $.Deferred();

            url = baseUrl + "/netwrk/default/get-top-post";

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

    check_zipcode_exist: function(params){
        var url,defer = $.Deferred();

            url = baseUrl + "/netwrk/default/check-exist-zipcode";

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

    get_marker_default: function(){
        var url,defer = $.Deferred();

            url = baseUrl + "/netwrk/default/get-maker-default-zoom";

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

    get_marker_zoom: function(){
        var url,defer = $.Deferred();

            url = baseUrl + "/netwrk/default/get-maker-max-zoom";

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
            async: false,
            cache: false,
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
            // data: params,
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
            url = baseUrl +"/netwrk/meet/user-meet";
        }else{
            url = baseUrl +"/netwrk/meet/user-meet";
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
            url = baseUrl +"/netwrk/meet/user-met";
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

        // if (isMobile) {
            url = baseUrl + "/netwrk/setting/load-profile";
        // }else{
        //     url = "netwrk/meet/get-user-meet";
        // }

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

        // if (isMobile) {
            url = baseUrl +"/netwrk/setting/update-profile";
        // }else{
            // url = "netwrk/meet/get-user-meet";
        // }

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
// 
        // if (isMobile) {
            url = baseUrl +"/netwrk/setting/upload-image";
        // }else{
            // url = "netwrk/meet/get-user-meet";
        // }

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
    },

    get_setting: function(params){
        var url,defer = $.Deferred();

        // if (isMobile) {
            url = baseUrl +"/netwrk/setting/get-user-setting";
        // }else{
            // url = "netwrk/meet/get-user-meet";
        // }

        $.ajax({
            url: url,
            // data: params,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            type: 'GET',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },

    update_setting: function(params){
        var url,defer = $.Deferred();

        // if (isMobile) {
            url = baseUrl +"/netwrk/setting/update-user-setting";
        // }else{
            // url = "netwrk/meet/get-user-meet";
        // }//

        $.ajax({
            url: url,
            data: params,
            async: false,
            cache: false,
            // contentType: false,
            // processData: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },

    new_topic: function(params){
        var url,defer = $.Deferred();
     
        url = baseUrl +"/netwrk/topic/new-topic";

        $.ajax({
            url: url,
            data: params,
            async: false,
            cache: false,
            // contentType: false,
            // processData: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },

    new_post: function(params){
        var url,defer = $.Deferred();
     
        url = baseUrl +"/netwrk/post/new-post";

        $.ajax({
            url: url,
            data: params,
            async: false,
            cache: false,
            // contentType: false,
            // processData: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    }
}

