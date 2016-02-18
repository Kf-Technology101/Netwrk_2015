var Ajax ={
    top_landing: function(){
        var url,defer = $.Deferred();
            url = baseUrl + "/netwrk/default/feed-global";

        $.ajax({
            url: url,
            async: true,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },

    global_search: function(params){
        var url,defer = $.Deferred();
            url = baseUrl + "/netwrk/search/global-search";

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

    reset_password: function(params){
        var url,defer = $.Deferred();
            url = baseUrl + "/netwrk/user/user-reset-password";

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
    forgot_password: function(form){
        var url,defer = $.Deferred();
            url = baseUrl + "/netwrk/user/forgot-password";

        $.ajax({
            url: url,
            data: $(form).serialize(),
            async: true,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },
    user_signup: function(form){
        var url,defer = $.Deferred();
            url = baseUrl + "/netwrk/user/signup-user";

        $.ajax({
            url: url,
            data: $(form).serialize(),
            async: true,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },

    user_login: function(form){
        var url,defer = $.Deferred();
            url = baseUrl + "/netwrk/user/login-user";

        $.ajax({
            url: url,
            data: $(form).serialize(),
            async: true,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },

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
    update_view_post: function(params){
        var url,defer = $.Deferred();

            url = baseUrl + "/netwrk/post/update-view-post";

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

    place_check_zipcode_exist: function(params){
        var url,defer = $.Deferred();

            url = baseUrl + "/netwrk/default/check-exist-place-zipcode";

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

    get_marker_groups_loc: function(groupId){
        var url,defer = $.Deferred();

        url = baseUrl + "/netwrk/default/get-groups-loc" + (groupId != null ? "?groupId = " + groupId : "");

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
            url = baseUrl + "/netwrk/topic/get-topic-mobile";
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

    show_feed: function(params){
        var url,defer = $.Deferred();

        if (isMobile) {
            url = baseUrl +"/netwrk/topic/get-feed";
        }else{
            url = baseUrl + "/netwrk/topic/get-feed";
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

    show_groups: function(params){
        var url,defer = $.Deferred();

        if (isMobile) {
            url = baseUrl +"/netwrk/group/get-groups";
        }else{
            url = baseUrl +"/netwrk/group/get-groups";
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

    show_group: function(id) {
        var url,defer = $.Deferred();

        if (isMobile) {
            url = baseUrl +"/netwrk/group/get-group";
        }else{
            url = "netwrk/group/get-group";
        }

        $.ajax({
            url: url,
            data: { "id" : id },
            type: 'POST',
            async: false,
            cache: false,
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },

    create_edit_group: function(params){
        var url,defer = $.Deferred();

        url = baseUrl +"/netwrk/group/create-edit-group";

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

    delete_group: function(params) {
        var url,defer = $.Deferred();

        url = baseUrl +"/netwrk/group/delete-group";

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

    get_users: function(group_id) {
        var url,defer = $.Deferred();

        if (isMobile) {
            url = baseUrl +"/netwrk/group/get-users";
        }else{
            url = "netwrk/group/get-users";
        }

        $.ajax({
            url: url,
            data: { "id" : group_id },
            type: 'POST',
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
            url = baseUrl + "/netwrk/meet/get-user-meet";
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
    },

    new_place: function(params){
        var url,defer = $.Deferred();

        url = baseUrl +"/netwrk/default/place-save";

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

    list_chat_post: function(){
        var url,defer = $.Deferred();
        url = baseUrl +"/netwrk/post/get-chat-inbox";

        $.ajax({
            url: url,
            data: null,
            async: false,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });

        return defer.promise();
    },

    get_chat_private_list: function(user_id) {
        var url,defer = $.Deferred();
        url = baseUrl +"/netwrk/chat-private/get-chat-private-list";

        $.ajax({
            url: url,
            data: {'user_id': user_id},
            async: false,
            cache: false,
            type: 'GET',
            success: defer.resolve,
            error: defer.reject
        });
        return defer.promise();
    },

    get_user_met_profile: function(chat_post_id){
        var url,defer = $.Deferred();
        if (isMobile) {
            url = baseUrl +"/netwrk/meet/get-user-meet-profile";
        }else{
            url = baseUrl + "/netwrk/meet/get-user-meet-profile";
        }

        $.ajax({
            url: url,
            data: {'post_id': chat_post_id},
            async: false,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });
        return defer.promise();
    },

    get_user_met_profile_discussion: function(user_view){
        var url,defer = $.Deferred();
        if (isMobile) {
            url = baseUrl +"/netwrk/meet/get-user-meet-profile-discussion";
        }else{
            url = baseUrl + "/netwrk/meet/get-user-meet-profile-discussion";
        }

        $.ajax({
            url: url,
            data: {'user_view': user_view},
            async: false,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });
        return defer.promise();
    },

    count_unread_message: function(){
        var url,defer = $.Deferred();
        url = baseUrl +"/netwrk/notify/count-unread-message";

        $.ajax({
            url: url,
            data: null,
            async: false,
            cache: false,
            type: 'GET',
            success: defer.resolve,
            error: defer.reject
        });
        return defer.promise();
    },

    count_unread_msg_from_user: function(sender){
        var url,defer = $.Deferred();
        url = baseUrl +"/netwrk/notify/count-unread-msg-from-user";

        $.ajax({
            url: url,
            data: {'sender': sender},
            async: false,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });
        return defer.promise();
    },

    update_notification_status: function(sender){
        var url,defer = $.Deferred();
        url = baseUrl +"/netwrk/notify/change-status-unread-msg";

        $.ajax({
            url: url,
            data: {'sender': sender},
            async: false,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });
        return defer.promise();
    },

    change_chat_show_message: function(){
        var url,defer = $.Deferred();
        url = baseUrl +"/netwrk/notify/update-chat-show-status";

        $.ajax({
            url: url,
            data: null,
            async: false,
            cache: false,
            type: 'GET',
            success: defer.resolve,
            error: defer.reject
        });
        return defer.promise();
    },

    /**
     * [Function is used to get ajax user profile]
     * @return             [data json]
     */

     get_user_profile: function(){
        var url,defer = $.Deferred();
        url = baseUrl +"/netwrk/default/get-user-profile";

        $.ajax({
            url: url,
            data: null,
            async: false,
            cache: false,
            type: 'GET',
            success: defer.resolve,
            error: defer.reject
        });
        return defer.promise();
    },

    get_info_post: function(post_id) {
        var url,defer = $.Deferred();
        url = baseUrl +"/netwrk/post/get-info-post";

        $.ajax({
            url: url,
            data: {'post_id': post_id},
            async: false,
            cache: false,
            type: 'GET',
            success: defer.resolve,
            error: defer.reject
        });
        return defer.promise();
    },

    insert_local_university: function() {
        var url,defer = $.Deferred();
        url = baseUrl +"/netwrk/default/insert-local-university";

        $.ajax({
            url: url,
            data: null,
            async: false,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });
        return defer.promise();
    },

    insert_local_government: function() {
        var url,defer = $.Deferred();
        url = baseUrl +"/netwrk/default/insert-local-government";

        $.ajax({
            url: url,
            data: null,
            async: false,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });
        return defer.promise();
    },

    get_marker_update: function(city) {
        var url,defer = $.Deferred();
        url = baseUrl +"/netwrk/default/get-marker-update";

        $.ajax({
            url: url,
            data: {'city': city},
            async: false,
            cache: false,
            type: 'POST',
            success: defer.resolve,
            error: defer.reject
        });
        return defer.promise();
    }
}

