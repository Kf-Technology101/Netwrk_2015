var Create_Post={
    params:{
        topic: null,
        topic_name: '',
        post:'',
        message: '',
        city:'',
        city_name: '',
        post_id: '',
        post_title: '',
        location: '',
        formatted_address: ''
    },
    status_change:{
        post: false,
        message: false,
        total: false,
        topic: false
    },
    slider: '#create_post_slider',
    slider_hidden: '-400px',
    isOpenCreatePostSlider: false,

    initialize: function(city,topic,name_city,name_topic,post_id){
        Create_Post.resetParams();
        if(isMobile){
            Create_Post.params.topic = $('#create_post').attr('data-topic');
            Create_Post.params.city = $('#create_post').attr('data-city');
            Create_Post.params.post_id = $('#create_post').attr('data-post_id');
            Create_Post.params.isCreateFromBlueDot = ($('#create_post').attr('data-isCreateFromBlueDot') == 'true') ? true : false;

            //set status_change status as true So save button will be active in create post form
            // as post msg and message required field is alread updated.
            if(Create_Post.params.post_id) {
                Create_Post.status_change.total = true;
                Create_Post.status_change.post = true;
                Create_Post.status_change.message = true;
                Create_Post.onCheckStatus();
            }
            if(Create_Post.params.isCreateFromBlueDot) {
                Create_Post.params.city_name = $('#create_post').attr('data-city_zipcode');
                Create_Post.params.lat = $('#create_post').attr('data-lat');
                Create_Post.params.lng = $('#create_post').attr('data-lng');
                Create_Post.getPostLocation(Create_Post.params.lat,Create_Post.params.lng);

                Create_Post.getPostTimeout();
                Create_Post.showPostCategory(Create_Post.params.city_name);
            }
            Create_Post.changeData();
            Create_Post.onclickBack();
            // Create_Post.showNetWrkBtn();
            Create_Post.eventClickdiscoverMobile();
            Create_Post.postTitleFocus();
            Create_Post.OnClickChatInboxBtnMobile();
            Default.SetAvatarUserDropdown();
        }else{
            if(isGuest){
                Login.modal_callback = Post;
                Login.initialize();
                return false;
            }
            //if edit form, then initialize the create_post edit form
            if(post_id != 'undefined' && post_id != null) {
                //todo: fetch post details and show on create post form.
                var error = false;
                Create_Post.changeData();
                Ajax.show_post(post_id).then(function(data) {
                    var json = $.parseJSON(data);
                    if (json.error) {
                        alert("Unable to load post");
                        error = true;
                    } else {
                        //If post is exists of perticular post_id then set the post params. So
                        //these params are available for furthur processing.
                        city = json.city_id;
                        topic = json.topic_id;

                        name_city = json.city_zipcode;
                        name_topic = json.topic_name;

                        Create_Post.params.city = json.city_id;
                        Create_Post.params.city_name = json.city_name;
                        Create_Post.params.topic = json.topic_id;
                        Create_Post.params.topic_name = json.topic_name;

                        Create_Post.params.message = json.content;
                        Create_Post.params.post_id = json.id;
                        Create_Post.params.post_title = json.title;

                        //set status_change status as true So save button will be active in create post form
                        // as post msg and message required field is alread updated.
                        Create_Post.status_change.total = true;
                        Create_Post.status_change.post = true;
                        Create_Post.status_change.message = true;
                    }
                });
                if (error) return;
            }
            //if topic creats from blue dot. It means name = city name, and city id = null
            if (name_city && city == null && topic == null) {
                //add class to new class design update purpose so type, channel, line would be in one line
                //on create post modal
                $('#create_post').find('.post').find('.item-row').addClass('create_post_from_blue_dot');
                Create_Post.params.lat = $('#create_post').attr('data-lat');
                Create_Post.params.lng = $('#create_post').attr('data-lng');
                Create_Post.params.isCreateFromBlueDot = true;
                Create_Post.getPostLocation(Create_Post.params.lat,Create_Post.params.lng);
                Create_Post.getPostTimeout();
                Create_Post.showPostCategory(name_city);
            } else {
                $('#create_post').find('.post').find('.item-row').removeClass('create_post_from_blue_dot');
                Create_Post.params.city = city;
                Create_Post.params.topic = topic;
                Create_Post.params.city_name = name_city;
            }

            //Create_Post.initializeSlider();
            Create_Post.showModalCreatePost();
            // Create_Post.showNetWrkBtn();
            Create_Post.onCloseModalCreatePost();
            // Create_Post.showSideBar(name_city,name_topic)
            Create_Post.changeData();
            Create_Post.onclickBack();
            Create_Post.eventClickdiscover();
            Create_Post.postTitleFocus();
            Create_Post.showDataBreadcrumb(name_city,name_topic);
            Create_Post.onClickBackTopicBreakcrumb();
            Create_Post.onClickBackNetwrkLogo();
            Create_Post.onClickBackZipcodeBreadcrumb();
            Topic.displayPositionModal();
        }
    },
    initializeSlider: function() {
        /*Create_Post.showCreatePostSlider();

        Common.CustomScrollBar($(Create_Post.slider).find('.slider-body'));*/
    },
    showCreatePostSlider: function() {
        $.when(Common.closeAllLeftSliders()).done(function() {
            $.when($('#create_post_slider').animate({
                "left": "50%"
            }, 500)).done(function(){
                Create_Post.onEnterPostNameTextarea();
            });
        });
    },
    closeCreatePostSlider: function() {
        $.when($('#create_post_slider').animate({
            "left": Create_Post.slider_hidden
        }, 500)).done(function(){
            Create_Post.hideModalCreatePost();
        });
    },
    onEnterPostNameTextarea: function() {
        var target = $('#name_post_textarea');
        //target.unbind();
        target.keydown(function(e){
            // Enter was pressed without shift key
            if (e.keyCode == 13)
            {
                e.preventDefault();
                console.log('enter key has disable for name post textarea');
            }
        });
    },
    showCreatePostModal: function(zipcode, lat, lng) {
        var parent = $('#create_post');
        parent.attr('data-lat', lat);
        parent.attr('data-lng', lng);

        if(isMobile) {
            if(zipcode){
                window.location.href = baseUrl + "/netwrk/post/create-post?city=null&topic=null&zipcode="+zipcode+"&lat="+lat+"&lng="+lng+"&isCreateFromBlueDot=true";
            }
        } else {
            Create_Post.initialize(null, null, zipcode);
        }
    },
    // Get post location
    getTemplatePostLocation: function(parent,data){
        var json = data;
        var target = parent.find('.post-location-content');

        var list_template = _.template($("#post-location-template").html());
        var append_html = list_template({data: json});
        target.append(append_html);
    },
    getPostLocation: function(lat, lng){
        var parent = $('#create_post').add('#create_post_slider');
        parent.find('.post-location-content').html('');
        var params = {'lat': lat, 'lng': lng};
        Ajax.getPostLocation(params).then(function(data){
            var json = $.parseJSON(data);
            if(json.success == true){
                Create_Post.params.location = json.location;
                Create_Post.params.formatted_address = json.formatted_address;
                Create_Post.getTemplatePostLocation(parent,json);
            }
        });
    },
    getPostTimeout: function() {
        //hide the timeout dropdown for post edit.
        if(!Create_Post.params.post_id) {
            var parent = $('#create_post');
            var target = parent.find('.post-timeout-content');
            target.html('');

            var json = Create_Post.params;

            var list_template = _.template($("#post-timeout-template").html());
            var append_html = list_template({data: json});
            target.append(append_html);

            Create_Post.onChangePostTimeout();
        }
    },
    onChangePostTimeout: function() {
        var parent = $('#create_post').find('.post-timeout-dropdown');
        var timeout = parent.val();
        parent.unbind();
        parent.on('change', function(e){
            var timeout = $(this).val();
            Create_Post.params.timeout = timeout;
            console.log(Create_Post.params.timeout);
        });
        Create_Post.params.timeout = timeout;
    },
    /* Display community category dropdown on Create post modal. */
    showPostCategory: function(zipcode){
        var parent = $('#create_post').add('#create_post_slider');
        parent.find('.post-category-content').html('');
        parent.find('.post-topic-category-content').html('');
        var params = {'zip_code': zipcode};
        //todo: fetch weather api data
        Ajax.get_topics_by_zipcode(params).then(function(data){
            var json = $.parseJSON(data);
            console.log(json);
            Create_Post.getTemplatePostCategory(parent,json);
        });
    },
    getTemplatePostCategory: function(parent,data){
        var json = data;
        var target = parent.find('.post-category-content');

        var list_template = _.template($("#post-category-template").html());
        var append_html = list_template({data: json});

        target.append(append_html);
        //fetch topic list by city id and fill the topic dropdown dropdown
        Create_Post.onTemplatePostCategory();
    },
    onTemplatePostCategory: function() {
        Create_Post.onChangePostCategory();

        /*var parent = $('#create_post'),
            communityDropdown = parent.find('.dropdown-office'),
            city_id = communityDropdown.val();
        console.log(city_id);
        Create_Post.showPostTopicCategory(city_id);*/
    },
    //update create_post.params.city variable on change of group category dropdown in create topic form.
    onChangePostCategory: function() {
        var parent = $('#create_post').find('.post-topic-dropdown');
        var city_id = parent.find(':selected').attr('data-city_id');
        var city_name = parent.find(':selected').attr('data-city_name'); //todo: check params in date-city_id here
        var topic_id = parent.find(':selected').attr('data-topic_id');

        parent.unbind();
        parent.on('change', function(e){
            console.log('in change');
            var city_id = $(this).find(':selected').attr('data-city_id');
            var city_name = $(this).find(':selected').attr('data-city_name');
            var topic_id = $(this).find(':selected').attr('data-topic_id');
            Create_Post.params.city = city_id;
            Create_Post.params.city_name = city_name;
            Create_Post.params.topic = topic_id;
            console.log(Create_Post.params);
            //if topic data is null then save button should be disable.
            if(Create_Post.params.topic) {
                Create_Post.status_change['topic'] = true;
            } else {
                Create_Post.status_change['topic'] = false;
            }
            console.log(Create_Post.status_change['topic']);
            Create_Post.onCheckStatus();
        });
        //set form params cityid and name
        Create_Post.params.city = city_id;
        Create_Post.params.city_name = city_name;
        Create_Post.params.topic = topic_id;

        if(Create_Post.params.topic) {
            Create_Post.status_change['topic'] = true;
        } else {
            Create_Post.status_change['topic'] = false;
        }
        console.log(Create_Post.params);
    },
    showPostTopicCategory: function(city_id){
        //get topic list by cityId and create topic list dropdown and append it to create_post modal.
        console.log('get topic by cityId'+city_id);
        var parent = $('#create_post');
        parent.find('.post-topic-category-content').html('');
        var params = {'city_id': city_id};
        Ajax.get_topic_by_city(params).then(function(data){
            var json = $.parseJSON(data);
            Create_Post.getTemplatePostTopicCategory(parent,json);

            //if topic data is null then save button should be disable.
            if(json.length > 0) {
                Create_Post.status_change['topic'] = true;
            } else {
                Create_Post.status_change['topic'] = false;
            }
            console.log(Create_Post.status_change['topic']);
            Create_Post.onCheckStatus();
        });
    },
    getTemplatePostTopicCategory: function(parent,data){
        //get topic dropdown and set it to #create_post modal
        var json = data;
        var target = parent.find('.post-topic-category-content');

        var list_template = _.template($("#post-topic-category-template").html());
        var append_html = list_template({data: json});

        target.append(append_html);
        Create_Post.onChangePostTopicCategory();
    },
    //update Create_Topic.params.topic variable on change of post topic category dropdown in create post form.
    onChangePostTopicCategory: function() {
        //set form params topic id
        var parent = $('#create_post').find('.post-topic-dropdown');
        var topic_id = parent.val();

        parent.unbind();
        parent.on('change', function(){
            topic_id = $(this).val();
            Create_Post.params.topic = topic_id;
        });
        Create_Post.params.topic = topic_id;
    },
    showDataBreadcrumb: function(zipcode, topic){
        var target = $('#create_post').find('.scrumb .zipcode');
        var target_topic = $('#create_post').find('.scrumb .topic');
        target.html(zipcode);
        target_topic.html(topic);
    },
    postTitleFocus: function(){
        $('.name_post').focus(function(){
            $('.input-group').addClass('clsFocus');
        });
        $('.name_post').focusout(function(){
            $('.input-group').removeClass('clsFocus');
        });
    },
    eventClickdiscover: function(){
        var parent = $('#create_post'),
            target = parent.find('#btn_discover');
            target.unbind();
            target.on('click',function(){
                // target.bind();

                parent.modal('hide');
                // self._init();
                // location.href.reload ;
            });
    },

    eventClickdiscoverMobile: function(){
        var target = $('#btn_discover_mobile');
        target.unbind();
        target.on('click',function(){
            target.bind();
            window.location.href = baseUrl;
            // Meet.reset_page();
            // Meet._init();
        });
    },

    showNetWrkBtn: function(){
        var parent = $('#create_post');

        if(isMobile){
            if($('#create_post').size()>0){
                // $('#btn_meet_mobile').hide();
                // $('#btn_discover_mobile').show();
            }

        }else{
            $('#btn_meet').hide();
            set_position_btn(parent,parent.find('#btn_discover'),130,100);
            set_position_btn_resize(parent,parent.find('#btn_discover'),130,100);
        }
    },

    hideNetWrkBtn: function(){
        var parent = $('#create_post');

        if(isMobile){
            // $('#btn_meet_mobile').show();
            // $('#btn_discover_mobile').hide();
        }else{
            $('#btn_meet').show();
            parent.find('#btn_discover').hide();
        }

    },

    showSideBar:function(city,topic){
        var sidebar = $('.map_content .sidebar');
        var city_name = "<span>"+ city +"</span> <i class='fa fa-angle-right'></i><span>"+ topic +"</span>";

        sidebar.find('.container').append(city_name);
        sidebar.show();
    },

    hideSideBar:function(city,topic){
        var sidebar = $('.map_content .sidebar');
        var city_name = "<span>"+ city +"</span> <i class='fa fa-angle-right'></i><span>"+ topic +"</span>";

        sidebar.find('.container').find('span,.fa').remove();
        sidebar.hide();
    },
    showModalCreatePost: function(){
        var parent = $('#create_post');

        parent.find('.name_post').val(Create_Post.params.post_title);
        parent.find('.message').val(Create_Post.params.message);
        if(Create_Post.params.post_id) {
            parent.find('#post_id').val(Create_Post.params.post_id);
        }
        Create_Post.onCheckStatus();

        parent.modal({
            backdrop: true,
            keyboard: false
        }).removeAttr("style").css("display", "block");

        Common.CustomScrollBar(parent.find('.modal-body'));
    },
    onCloseModalCreatePost: function(){
        $('#create_post').on('hidden.bs.modal',function() {
            Create_Post.hideModalCreatePost();
        });
        $('.modal-backdrop.in').click(function(e) {
            Create_Post.hideModalCreatePost();
        });
    },

    hideModalCreatePost:function(){
        var parent = $('#create_post');
        parent.modal('hide');
        // Create_Post.hideSideBar();
        Create_Post.hideNetWrkBtn();
        Create_Post.reset_data();
        Create_Post.setDefaultBtn();
    },

    changeData: function(){
        var parent = $('#create_post');

        this.onChangeData(parent.find('.name_post'),'post');
        this.onChangeData(parent.find('.message'),'message');
    },

    onChangeData: function(target,filter){
        target.unbind();
        target.on('keyup input',function(e){
            //Copy the content of name_post textarea into message textarea
            if(e.currentTarget.id == 'name_post_textarea') {
                Create_Post.copyPostNameToMessage();
            }
            if($(e.currentTarget).val().length > 0){
                Create_Post.params[filter] = $(e.currentTarget).val();
                Create_Post.status_change[filter] = true;
            }else{
                Create_Post.status_change[filter] = false;
            }
            Create_Post.onCheckStatus();
        });
    },
    copyPostNameToMessage: function() {
        //copy whatever entered in chat
        var parent = $('#create_post'),
            name_post = parent.find('.name_post'),
            message = parent.find('.message');

        message.val(name_post.val());
        Create_Post.params['message'] = name_post.val();
        console.log(Create_Post.params.message);
    },
    onCheckStatus: function(){
        var status = Create_Post.status_change;

        //if line create from blue dot then topic is required
        if(Create_Post.params.isCreateFromBlueDot) {
            if(status.post && /*status.message &&*/ status.topic){
                status.total = true;
            }else{
                status.total = false;
            }
        } else {
            if(status.post /*&& status.message*/){
                status.total = true;
            }else{
                status.total = false;
            }
        }
        console.log('Create_Post.status_change.total => '+status.total);
        Create_Post.OnBtnTemplate();
    },

    OnBtnTemplate: function(){
        Create_Post.OnshowReset();
        Create_Post.OnshowSave();
    },

    OnshowReset: function(){
        var status = Create_Post.status_change,
            parent = $('#create_post'),
            btn = parent.find('.cancel');
        if(status.post || status.message){
            btn.removeClass('disable');
            Create_Post.OnclickReset();
        }else if(!status.post && !status.message){
            btn.addClass('disable');
        }
    },

    OnclickReset: function(){
        var parent = $('#create_post'),
            btn = parent.find('.cancel');

        btn.unbind();
        btn.on('click',function(){
            if(!btn.hasClass('disable')){
                Create_Post.reset_data();
                Create_Post.setDefaultBtn();
            }
        });

    },

    reset_data: function(){
        var parent = $('#create_post');

        parent.find('.name_post').val('');
        parent.find('.message').val('');
        parent.find('#post_id').val('');
        parent.find('.post-category-content').html('');
        parent.find('.post-topic-category-content').html('');
        parent.find('.post-location-content').html('');

        Create_Post.status_change.post = false;
        Create_Post.status_change.message = false;
        Create_Post.status_change.total = false;
        Create_Post.status_change.topic = false;
    },

    setDefaultBtn: function(){
        var parent = $('#create_post'),
        btn_save = parent.find('.save'),
        btn_cancel = parent.find('.cancel');

        btn_save.addClass('disable');
        btn_cancel.addClass('disable');
    },

    onclickBack: function(){
        var parent = $('#create_post').find('.back_page span').add($('.box-navigation .btn_nav_map'));
        var city = Create_Post.params.city;

        parent.unbind();
        parent.click(function(){
            if(isMobile){
                Create_Post.redirect();
            }else{
                Create_Post.hideModalCreatePost();
                Create_Post.closeCreatePostSlider();
                //Post.initialize();
            }
        });
    },

    onClickBackZipcodeBreadcrumb: function(){
        var parent = $('#create_post').find('.scrumb .zipcode');
        var city = Create_Post.params.city;
        var params = {zipcode: Create_Post.params.city_name};
        parent.unbind();
        parent.click(function(){
            if(isMobile){
                Create_Post.redirect();
            }else{
                Create_Post.hideModalCreatePost();
                Topic.initialize(city,params);
            }
        });
    },

    onClickBackTopicBreakcrumb: function(){
        var parent = $('#create_post').find('.scrumb .topic');
        var city = Create_Post.params.city;

        parent.unbind();
        parent.click(function(){
            if(isMobile){
                Create_Post.redirect();
            }else{
                Create_Post.hideModalCreatePost();
                Post.initialize();
            }
        });
    },

    onClickBackNetwrkLogo: function(){
        $('#create_post .scrumb .logo').click(function(){
            $('#create_post').modal('hide');
        });
    },

    redirect: function(){
        window.location.href = baseUrl + "/netwrk/post?city="+Create_Post.params.city+"&topic="+Create_Post.params.topic;
    },

    OnshowSave: function(){
        var status = Create_Post.status_change,
            parent = $('#create_post'),
            btn = parent.find('.save');
        if(status.total){
            btn.removeClass('disable');
            Create_Post.OnclickSave();
        }else{
            btn.addClass('disable');
        }
    },

    OnclickSave: function(){
        var parent = $('#create_post'),
            btn = parent.find('.save'),
            city = Create_Post.params.city;

        btn.unbind();
        btn.on('click',function(){
            if(!btn.hasClass('disable')){
                console.log(Create_Post.params);
                Ajax.new_post(Create_Post.params).then(function(){
                    Create_Post.reset_data();
                    Create_Post.setDefaultBtn();

                    if(isMobile){
                        setTimeout(function(){
                            Create_Post.redirect();
                        },700);
                    } else {
                        Create_Post.hideModalCreatePost();
                        Create_Post.closeCreatePostSlider();
                        return;
                        setTimeout(function(){
                            Post.params.city = Create_Post.params.city;
                            Post.params.city_name = Create_Post.params.city_name;
                            Post.params.topic = Create_Post.params.topic;
                            Post.params.topic_name = Create_Post.params.topic_name;
                            Post.initialize();
                        },700);
                    }
                });
                ChatInbox.GetDataListChatPost();
                //Map.update_marker(Create_Post.params.city);
            }
        });
    },

    OnClickChatInboxBtnMobile: function() {
        var target = $('#chat_inbox_btn_mobile');
        target.unbind();
        target.on('click',function(){
            sessionStorage.url = window.location.href;
            ChatInbox.OnClickChatInboxMobile();
            // Ajax.set_previous_page(window.location.href).then(function(data){
            //     ChatInbox.OnClickChatInboxMobile();
            // });
        });
    },
    resetParams: function() {
        Create_Post.params.post_title = '';
        Create_Post.params.message = '';
        Create_Post.params.post_id = '';

        Create_Post.params.city = '';
        Create_Post.params.city_name = '';
        Create_Post.params.topic = '';
        Create_Post.params.topic_name = '';

        Create_Post.params.message = '';
        Create_Post.params.post_id = '';
        Create_Post.params.post_title = '';

        Create_Post.params.lat = '';
        Create_Post.params.lng = '';
        Create_Post.params.isCreateFromBlueDot = '';
        Create_Post.params.location = '';
        Create_Post.params.formatted_address = '';
    }

};