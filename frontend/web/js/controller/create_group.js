/**
 * Created by iliya on 08.01.16.
 */
var Create_Group={
    params:{
        topic: null,
        post:'',
        message: '',
        city:'',
        city_name: ''
    },
    status_change:{
        post: false,
        message: false,
        total: false
    },
    data:{
        filter: 'recent',
        city: '',
        size: 30,
        city_name:'',
        zipcode:''
    },
    added_users: [],

    initialize: function(city,topic,name_city,name_topic){
        if(isMobile){
            Create_Group.params.topic = $('#create_group').attr('data-topic');
            Create_Group.params.city = $('#create_group').attr('data-city');
            Create_Group.changeData();
            Create_Group.onclickBack();
            // Create_Group.showNetWrkBtn();
            Create_Group.eventClickdiscoverMobile();
            Create_Group.postTitleFocus();
            Create_Group.OnClickChatInboxBtnMobile();
        }else{
            if(isGuest){
                Login.modal_callback = Post;
                Login.initialize();
                return false;
            }
            Create_Group.params.city = city;
            Create_Group.params.topic = topic;
            Create_Group.params.city_name = name_city;

            Create_Group.showModalCreateGroup();
            Create_Group.OnClickAddEmail();
            Create_Group.OnClickCreateGroup();
            Create_Group.onclickBack();

            // Create_Group.showNetWrkBtn();
            /*Create_Group.onCloseModalCreatePost();
            // Create_Group.showSideBar(name_city,name_topic)
            Create_Group.changeData();
            Create_Group.onclickBack();
            Create_Group.postTitleFocus();
            Create_Group.showDataBreadcrumb(name_city, name_topic);
            Create_Group.onClickBackTopicBreakcrumb();
            Create_Group.onClickBackNetwrkLogo();
            Create_Group.onClickBackZipcodeBreadcrumb();*/
        }
    },
    showDataBreadcrumb: function(zipcode, topic){
        var target = $('#create_group').find('.scrumb .zipcode');
        var target_topic = $('#create_group').find('.scrumb .topic');
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
        var parent = $('#create_group'),
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
        var parent = $('#create_group');

        if(isMobile){
            if($('#create_group').size()>0){
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
        var parent = $('#create_group');

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
    showModalCreateGroup: function(){
        var parent = $('#create_group_modal');
        parent.modal('show');
    },
    onCloseModalCreatePost: function(){
        $('#create_group').on('hidden.bs.modal',function() {
            Create_Group.hideModalCreatePost();
        });
        $('.modal-backdrop.in').click(function(e) {
            Create_Group.hideModalCreatePost()
        });
    },

    hideModalCreateGroup:function(){
        var parent = $('#create_group_modal');
        parent.modal('hide');
        // Create_Group.hideSideBar();
        //Create_Group.hideNetWrkBtn();
        //Create_Group.reset_data();
        //Create_Group.setDefaultBtn();
    },

    changeData: function(){
        var parent = $('#create_group');

        this.onChangeData(parent.find('.name_post'),'post');
        this.onChangeData(parent.find('.message'),'message');
    },

    onChangeData: function(target,filter){
        target.unbind();
        target.on('keyup input',function(e){
            if($(e.currentTarget).val().length > 0){
                Create_Group.params[filter] = $(e.currentTarget).val();
                Create_Group.status_change[filter] = true;
            }else{
                Create_Group.status_change[filter] = false;
            }
            Create_Group.onCheckStatus();
        });
    },

    onCheckStatus: function(){
        var status = Create_Group.status_change;

        if(status.post && status.message){
            status.total = true;
        }else{
            status.total = false;
        }
        Create_Group.OnBtnTemplate();
    },

    OnBtnTemplate: function(){
        Create_Group.OnshowReset();
        Create_Group.OnshowSave();
    },

    OnshowReset: function(){
        var status = Create_Group.status_change,
            parent = $('#create_group'),
            btn = parent.find('.cancel');
        if(status.post || status.message){
            btn.removeClass('disable');
            Create_Group.OnclickReset();
        }else if(!status.post && !status.message){
            btn.addClass('disable');
        }
    },

    OnclickReset: function(){
        var parent = $('#create_group'),
            btn = parent.find('.cancel');

        btn.unbind();
        btn.on('click',function(){
            if(!btn.hasClass('disable')){
                Create_Group.reset_data();
                Create_Group.setDefaultBtn();
            }
        });

    },

    reset_data: function(){
        var parent = $('#create_group');

        parent.find('.name_post').val('');
        parent.find('.message').val('');

        Create_Group.status_change.post = false;
        Create_Group.status_change.message = false;
        Create_Group.status_change.total = false;
    },

    setDefaultBtn: function(){
        var parent = $('#create_group'),
            btn_save = parent.find('.save'),
            btn_cancel = parent.find('.cancel');

        btn_save.addClass('disable');
        btn_cancel.addClass('disable');
    },

    onclickBack: function(){
        var parent = $('#create_group_modal').find('.back_page span');
        var city = Create_Group.params.city;

        parent.unbind();
        parent.click(function(){
            if(isMobile){
                Create_Group.redirect();
            }else{
                Create_Group.hideModalCreateGroup();
                Topic.initialize();
                Group.initialize();
            }
        });
    },

    onClickBackZipcodeBreadcrumb: function(){
        var parent = $('#create_group').find('.scrumb .zipcode');
        var city = Create_Group.params.city;
        var params = {zipcode: Create_Group.params.city_name};
        parent.unbind();
        parent.click(function(){
            if(isMobile){
                Create_Group.redirect();
            }else{
                Create_Group.hideModalCreatePost();
                Topic.initialize(city,params);
            }
        });
    },

    onClickBackTopicBreakcrumb: function(){
        var parent = $('#create_group').find('.scrumb .topic');
        var city = Create_Group.params.city;

        parent.unbind();
        parent.click(function(){
            if(isMobile){
                Create_Group.redirect();
            }else{
                Create_Group.hideModalCreatePost();
                Post.initialize();
            }
        });
    },

    onClickBackNetwrkLogo: function(){
        $('#create_group .scrumb .logo').click(function(){
            $('#create_group').modal('hide');
        });
    },

    redirect: function(){
        window.location.href = baseUrl + "/netwrk/post?city="+Create_Group.params.city+"&topic="+Create_Group.params.topic;
    },

    OnshowSave: function(){
        var status = Create_Group.status_change,
            parent = $('#create_group'),
            btn = parent.find('.save');
        if(status.total){
            btn.removeClass('disable');
            Create_Group.OnclickSave();
        }else{
            btn.addClass('disable');
        }
    },

    OnclickSave: function(){
        var parent = $('#create_group'),
            btn = parent.find('.save'),
            city = Create_Group.params.city;

        btn.unbind();
        btn.on('click',function(){
            if(!btn.hasClass('disable')){
                Ajax.new_post(Create_Group.params).then(function(){
                    Create_Group.reset_data();
                    Create_Group.setDefaultBtn();
                    setTimeout(function(){
                        if(isMobile){
                            Create_Group.redirect();
                        }else{
                            Create_Group.hideModalCreatePost();
                            Post.initialize();
                        }
                    },700);
                });
                ChatInbox.GetDataListChatPost();
            }
        });
    },

    OnClickChatInboxBtnMobile: function() {
        var target = $('#chat_inbox_btn_mobile');
        target.unbind();
        target.on('click',function(){
            Ajax.set_previous_page(window.location.href).then(function(data){
                ChatInbox.OnClickChatInboxMobile();
            });
        });
    },

    OnClickAddEmail: function() {
        $('#add-email').click(function() {
            //checking emails validity
            var emailsInput = $('#emails-input').val().split(",");
            $('.error-msg').hide();
            for (var i in emailsInput) {
                if (!emailsInput.hasOwnProperty(i)) continue;
                var email = $.trim(emailsInput[i]);
                if (!/\S+@\S+\.\S+/.test(email)) {
                    $('.error-msg').show();
                    return;
                } else {
                    if (Create_Group.added_users.indexOf(email) == -1) {
                        Create_Group.added_users.push(email);
                    }
                }
            }
            $('#emails-list').find("li").remove();
            for (i in Create_Group.added_users) {
                if (!Create_Group.added_users.hasOwnProperty(i)) continue;
                $('#emails-list').append('<li>' + Create_Group.added_users[i] + '<span class="delete"></span></li>');
            }
        });
    },

    OnClickCreateGroup: function() {
        $("#save_group").click(function() {
            Ajax.create_edit_group({
                emails: Create_Group.added_users,
                permission: ($('#dropdown-permission').text() == "Private" ? 2 : 1),
                name: $('#group_name').val()
            }).then(function(data) {
                var json = $.parseJSON(data);
                if (json.error) alert(json.message);
                else {
                    alert("Group created!");
                }
            });
        });
    }

};