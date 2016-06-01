var Create_Post={
    params:{
        topic: null,
        post:'',
        message: '',
        city:'',
        city_name: '',
        post_id: '',
        post_title: ''
    },
    status_change:{
        post: false,
        message: false,
        total: false
    },

    initialize: function(city,topic,name_city,name_topic,post_id){

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
                    topic = json.topic_id;
                    city = json.city_id;
                    name_city = json.city_name;

                    Create_Post.params.city = json.city_id;
                    Create_Post.params.city_name = json.city_name;
                    Create_Post.params.topic = json.topic_id;

                    Create_Post.params.message = json.content;
                    Create_Post.params.post_id = json.id;
                    Create_Post.params.post_title = json.title;

                    //set change status as true as post msg and message required field is alread updated.
                    Create_Post.status_change.total = true;
                    Create_Post.status_change.post = true;
                    Create_Post.status_change.message = true;
                }

            });
            if (error) return;
        }
        if(isMobile){
            Create_Post.params.topic = $('#create_post').attr('data-topic');
            Create_Post.params.city = $('#create_post').attr('data-city');
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
            Create_Post.params.city = city;
            Create_Post.params.topic = topic;
            Create_Post.params.city_name = name_city;

            console.log(Create_Post.params.city);

            Create_Post.showModalCreatePost();
            // Create_Post.showNetWrkBtn();
            Create_Post.onCloseModalCreatePost();
            // Create_Post.showSideBar(name_city,name_topic)
            Create_Post.changeData();
            Create_Post.onclickBack();
            Create_Post.eventClickdiscover();
            Create_Post.postTitleFocus();
            Create_Post.showDataBreadcrumb(name_city, name_topic);
            Create_Post.onClickBackTopicBreakcrumb();
            Create_Post.onClickBackNetwrkLogo();
            Create_Post.onClickBackZipcodeBreadcrumb();
            Topic.displayPositionModal();
        }
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
        Create_Post.onCheckStatus();

        parent.modal({
            backdrop: true,
            keyboard: false
        }).removeAttr("style").css("display", "block");
    },
    onCloseModalCreatePost: function(){
        $('#create_post').on('hidden.bs.modal',function() {
            Create_Post.hideModalCreatePost();
        });
        $('.modal-backdrop.in').click(function(e) {
            Create_Post.hideModalCreatePost()
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
            if($(e.currentTarget).val().length > 0){
                Create_Post.params[filter] = $(e.currentTarget).val();
                Create_Post.status_change[filter] = true;
            }else{
                Create_Post.status_change[filter] = false;
            }
            Create_Post.onCheckStatus();
        });
    },

    onCheckStatus: function(){
        var status = Create_Post.status_change;

        if(status.post && status.message){
            status.total = true;
        }else{
            status.total = false;
        }
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

        Create_Post.status_change.post = false;
        Create_Post.status_change.message = false;
        Create_Post.status_change.total = false;
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
                Post.initialize();
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
                    setTimeout(function(){
                        if(isMobile){
                            Create_Post.redirect();
                        }else{
                            Create_Post.hideModalCreatePost();
                            Post.initialize();
                        }
                    },700);
                });
                ChatInbox.GetDataListChatPost();
                Map.update_marker(Create_Post.params.city);
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
    }

};