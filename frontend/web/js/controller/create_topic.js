var Create_Topic={
    params:{
        topic:'',
        post: '',
        message:'',
        city:null,
        city_name:'',
        netwrk_name:'',
        zip_code:'',
        lat:'',
        lng:''
    },
    status_change:{
        topic:false,
        post: false,
        message: false,
        total: false
    },

    initialize: function(city,name){
        if(isMobile){
            Create_Topic.params.city = $('#create_topic').attr('data-city');
            Create_Topic.params.netwrk_name = $('#create_topic').attr('data-name-city');
            Create_Topic.params.zip_code = $('#create_topic').attr('data-zipcode');
            Create_Topic.params.lat = $('#create_topic').attr('data-lat');
            Create_Topic.params.lng = $('#create_topic').attr('data-lng');
            this.changeData();
            Create_Topic.onclickBack();
            // Create_Topic.showNetWrkBtn();
            Create_Topic.eventClickMeetMobile();
            Create_Topic.postTitleFocus();
            Create_Topic.OnClickChatInboxBtnMobile();
            Default.SetAvatarUserDropdown();
        }else{
            if(isGuest){
                if(!Login.modal_callback){
                    Login.modal_callback = Topic;
                }
                Login.initialize();
                return false;
            }
            if(city && name){
                Create_Topic.params.city = city;
                Create_Topic.params.city_name = name;
            }

            Create_Topic.showModalCreateTopic();
            // Create_Topic.showSideBar();
            Create_Topic.onCloseModalCreateTopic();
            Create_Topic.changeData();
            Create_Topic.onclickBack();
            // Create_Topic.showNetWrkBtn();
            Create_Topic.eventClickdiscover();
            Create_Topic.postTitleFocus();
            Create_Topic.showZipcodeBreadcrumb(Create_Topic.params.city_name);
            Create_Topic.onClickBackZipcodeBreadcrumb();
            Create_Topic.onClickBackNetwrkLogo();
            Topic.displayPositionModal();
        }
    },
    showZipcodeBreadcrumb: function(zipcode){
        var target = $('#create_topic').find('.scrumb .zipcode');
        target.html(zipcode);
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
        var parent = $('#create_topic'),
            target = parent.find('#btn_discover');
            target.unbind();
            target.on('click',function(){
                parent.modal('hide');
            });
    },
    eventClickMeetMobile: function(){
        // var target = $('#btn_discover_mobile');
        var target = $('#btn_meet_mobile');
        target.unbind();
        target.on('click',function(){
            target.bind();
            window.location.href = baseUrl + "/netwrk/meet";
            // Meet.reset_page();
            // Meet._init();
        });
    },

    showNetWrkBtn: function(){
        var parent = $('#create_topic');
        if(isMobile){
            // if($('#create_topic').size()>0){
            //     $('#btn_meet_mobile').hide();
            //     $('#btn_discover_mobile').show();
            // }
        }else{
            $('#btn_meet').hide();
            set_position_btn(parent,parent.find('#btn_discover'),130,100);
            set_position_btn_resize(parent,parent.find('#btn_discover'),130,100);
        }
    },

    hideNetWrkBtn: function(){
        var parent = $('#create_topic');
        if(isMobile){
            // $('#btn_meet_mobile').show();
            // $('#btn_discover_mobile').hide();
        }else{
            $('#btn_meet').show();
            parent.find('#btn_discover').hide();
        }

    },

    showSideBar: function(){
        var sidebar = $('.map_content .sidebar');
        var city_name = "<span>"+ Create_Topic.params.city_name +"</span>";

        sidebar.find('.container').append(city_name);
        sidebar.show();
    },

    hideSideBar: function(){
        var sidebar = $('.map_content .sidebar');

        sidebar.find('.container span').remove();
        sidebar.hide();
    },

    showModalCreateTopic: function(){
        var parent = $('#create_topic');
        parent.modal({
            backdrop: true,
            keyboard: false
        });
    },

    onCloseModalCreateTopic: function(){
        $('#create_topic').on('hidden.bs.modal',function() {
            Create_Topic.hideModalCreateTopic();
        });
        $('.modal-backdrop.in').click(function(e) {
            Create_Topic.hideModalCreateTopic();
        });
    },

    hideModalCreateTopic:function(){
        var parent = $('#create_topic');
        Create_Topic.reset_data();
        Create_Topic.setDefaultBtn();
        // Create_Topic.hideSideBar();
        Create_Topic.hideNetWrkBtn();
        parent.modal('hide');

    },

    onclickBack: function(){
        var parent = $('#create_topic').find('.back_page span').add($('.box-navigation .btn_nav_map'));
        var city = Create_Topic.params.city;
        var params = {zipcode: Create_Topic.params.city_name};
        parent.unbind();
        parent.click(function(){
            if(isMobile){
                Create_Topic.redirect();
            }else{
                Create_Topic.hideModalCreateTopic();
                Topic.initialize(city,params);
            }
        });
    },
    onClickBackZipcodeBreadcrumb: function(){
        var parent = $('#create_topic').find('.scrumb .zipcode');
        var city = Create_Topic.params.city;
        var params = {zipcode: Create_Topic.params.city_name};
        parent.unbind();
        parent.click(function(){
            if(isMobile){
                Create_Topic.redirect();
            }else{
                Create_Topic.hideModalCreateTopic();
                Topic.initialize(city,params);
            }
        });
    },
    onClickBackNetwrkLogo: function(){
        $('#create_topic .scrumb .logo').click(function(){
            $('#create_topic').modal('hide');
        });
    },
    changeData: function(){
        var parent = $('#create_topic');

        this.onChangeData(parent.find('.name_topic'),'topic');
        this.onChangeData(parent.find('.name_post'),'post');
        this.onChangeData(parent.find('.message'),'message');
    },

    redirect: function(){
        if(Create_Topic.params.zip_code){
            window.location.href = baseUrl + "/netwrk/topic/topic-page?city="+Create_Topic.params.city+"&zipcode="+Create_Topic.params.zip_code+"&name="+Create_Topic.params.city_name+"&lat="+Create_Topic.params.lat+"&lng="+Create_Topic.params.lng;;
        }else{
            window.location.href = baseUrl + "/netwrk/topic/topic-page?city="+Create_Topic.params.city;
        }
    },

    onChangeData: function(target,filter){
        target.unbind();
        target.on('keyup input',function(e){
            if($(e.currentTarget).val().length > 0){
                Create_Topic.params[filter] = $(e.currentTarget).val();
                Create_Topic.status_change[filter] = true;
            }else{
                Create_Topic.status_change[filter] = false;
            }
            Create_Topic.onCheckStatus();
        });
        // target.on('input',function(e){
        //     if($(e.currentTarget).val().length > 0){
        //         Create_Topic.params[filter] = $(e.currentTarget).val();
        //         Create_Topic.status_change[filter] = true;
        //     }else{
        //         Create_Topic.status_change[filter] = false;
        //     }
        //     Create_Topic.onCheckStatus();
        // });
    },

    onCheckStatus: function(){
        var status = Create_Topic.status_change;
        if(status.topic && status.post && status.message){
            status.total = true;
        }else{
            status.total = false;
        }
        Create_Topic.OnBtnTemplate();
    },

    OnBtnTemplate: function(){
        Create_Topic.OnshowReset();
        Create_Topic.OnshowSave();
    },

    OnshowReset: function(){
        var status = Create_Topic.status_change,
            parent = $('#create_topic'),
            btn = parent.find('.cancel');
        if(status.topic || status.post || status.message){
            btn.removeClass('disable');
            Create_Topic.OnclickReset();
        }else if(!status.topic && !status.post && !status.message){
            btn.addClass('disable');
        }
    },

    OnclickReset: function(){
        var parent = $('#create_topic'),
            btn = parent.find('.cancel');

        btn.unbind();
        btn.on('click',function(){
            if(!btn.hasClass('disable')){
                Create_Topic.reset_data();
                Create_Topic.setDefaultBtn();
            }
        });

    },

    reset_data: function(){
        var parent = $('#create_topic');

        parent.find('.name_topic').val('');
        parent.find('.name_post').val('');
        parent.find('.message').val('');

        Create_Topic.status_change.topic = false;
        Create_Topic.status_change.post = false;
        Create_Topic.status_change.message = false;
        Create_Topic.status_change.total = false;
    },

    setDefaultBtn: function(){
        var parent = $('#create_topic'),
            btn_save = parent.find('.save'),
            btn_cancel = parent.find('.cancel');

            btn_save.addClass('disable');
            btn_cancel.addClass('disable');
    },

    OnshowSave: function(){
        var status = Create_Topic.status_change,
            parent = $('#create_topic'),
            btn = parent.find('.save');
        if(status.topic && status.post && status.message){
            btn.removeClass('disable');
            Create_Topic.OnclickSave();
        }else if(!status.topic || !status.post || !status.message){
            btn.addClass('disable');
        }
    },

    OnclickSave: function(){
        var parent = $('#create_topic'),
            btn = parent.find('.save'),
            city = Create_Topic.params.city;

        btn.unbind();
        btn.on('click',function(){
            if(!btn.hasClass('disable')){
                Ajax.new_topic(Create_Topic.params).then(function(data){
                    Create_Topic.params.city = data;
                    Create_Topic.reset_data();
                    Create_Topic.setDefaultBtn();
                    setTimeout(function(){
                        if(isMobile){
                            Create_Topic.redirect(Create_Topic.params.city);
                        }else{
                            Create_Topic.hideModalCreateTopic();
                            Topic.initialize(Create_Topic.params.city);
                            ChatInbox.GetDataListChatPost();
                            Map.update_marker(Create_Topic.params.city);
                        }
                    },700);
                });
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