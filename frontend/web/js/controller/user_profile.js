var User_Profile = {
    data:{},
    contexts: {
        modalProfile: '.modal-profile'
    },
    templateData:{
        groups:{},
        topics:{},
        posts:{},
        items:{},
        favoriteCommunities: {},
        recentCommunities: {}
    },
    params:{
        age: 0,
        work: '',
        about: '',
        zipcode:0,
        lat:'',
        lng:''
    },
    list:{
        post:{
            paging:1,
            status_paging: 1,
            loaded: 0
        },
        view:{
            paging:1,
            status_paging: 1,
            loaded: 0
        },
        recent:{
            paging:1,
            status_paging: 1,
            loaded: 0
        }
    },
    tab_current: 'group',
    img:{
        image:''
    },
    num_len:true,
    zipcode: true,
    status_change:{
        age:true,
        zipcode: true,
        work: false,
        about:false,
        total:false
    },
    state: 'Indiana',
    profileContainer: $('.profile-container'),
    profileInfo: $('.profile-info'),
    editProfileModal: $('#modal_change_profile_picture'),
    initialize: function(){
        if(isMobile){
            Default.SetAvatarUserDropdown();
            User_Profile.OnClickBack();
        } else {
            User_Profile.ShowModalProfile();
        }

        User_Profile.resetProfile();
        User_Profile.getProfileInfo();

        //Show favorite communities of currentUser on profile modal
        User_Profile.ShowFavoriteCommunities();

        //Show Recent communities of currentUser on profile modal
        User_Profile.ShowRecentCommunities();

        User_Profile.OnClickTabBtn();

        //Init the recent activities button group and get data according to tab.
        User_Profile.getDataOnTab();

        //events
        User_Profile._eventClickPasswordSetting();
        User_Profile._eventClickSearchSetting();
        User_Profile._eventClickProfileInfo();
    },

    resetProfile: function(){
        User_Profile.profileInfo.html('');
    },

    getProfileInfo: function(){
        var self = this,
            profile_data = $('#profile_info');

        Ajax.getUserProfile().then(function(data){
            var json = $.parseJSON(data);
            User_Profile.data = json;

            User_Profile.params.age = json.age;
            User_Profile.params.work = json.work;
            User_Profile.params.about = json.about;
            User_Profile.params.zipcode = json.zip;

            if(User_Profile.data.status == 1){
                User_Profile.getTemplateProfileInfo(User_Profile.profileInfo,profile_data);
                User_Profile.editProfilePicture();
            }
        });
    },

    getTemplateProfileInfo: function(parent,target,callback){
        var template = _.template(target.html());
        var append_html = template({data: User_Profile.data});
        parent.append(append_html);

        if(_.isFunction(callback)){
            callback();
        }
    },

    editProfilePicture: function(){
        var btn = $('.change-profile');
        btn.on('click',function(){
            User_Profile.editProfileModal.modal({
                backdrop: true,
                keyboard: false
            });
            User_Profile.onchangeModalUpload();
        });
    },

    onchangeModalUpload: function(){
        $('.modal-backdrop.in').last().addClass('active');
        User_Profile.onBrowse();
        User_Profile.onCancel();
        User_Profile.onBackdrop();
    },

    onBrowse: function(){
        var btn = User_Profile.editProfileModal.find('.browse');
        btn.unbind();
        btn.on('click',function(e){
            $('.preview_img').find('img').remove();
            $('.preview_img_ie').find('img').remove();
            $('.image-preview').find('p').show();
            $('#input_image')[0].click();

            $('#input_image').unbind();
            $('#input_image').change(function(e) {
                User_Profile.handleFiles(this.files);
            });

        });
    },

    handleFiles: function(files){
        // var target = $('img.preview_image');
        var img = new Image(),
            parent_text = $('.image-preview').find('p'),
            btn_control_save = $('.btn-control-modal').find('.save');

        if(files.length > 0){
            img.src = window.URL.createObjectURL(files[0]);

            img.onload = function() {
                window.URL.revokeObjectURL(this.src);
                User_Profile.onEventSaveImage();
            };

            btn_control_save.removeClass('disable');
            parent_text.hide();

            if (isonIE()){
                $('.preview_img_ie').append(img);

            }else{
                $('.preview_img').addClass('active');
                $('.preview_img').append(img);
            }
            User_Profile.showImageOnIE();
        }
    },

    onEventSaveImage:function(){
        var btn_save = $('.btn-control-modal').find('.save');

        if (!btn_save.hasClass('disable')) {
            btn_save.on('click',function(){
                $('#upload_image').unbind();
                $('#upload_image').on('submit',function( event ) {
                    event.preventDefault();
                    var formData = new FormData(this);

                    Ajax.uploadProfileImage(formData).then(function(data){
                        var json = $.parseJSON(data);
                        User_Profile.img.images = json.data_image;
                        User_Profile.reloadProfilePicture();
                        $('.preview_img').find('img').remove();
                    });

                });
                $('#upload_image').submit();
            });
        };
    },

    reloadProfilePicture: function(){
        User_Profile.editProfileModal.modal('hide');
        $('.img-user').find('img').attr('src',User_Profile.img.images);
    },

    showImageOnIE: function(img){
        var target = $('.preview_img_ie').find('img'),
            w = $('.preview_img_ie').find('img').attr('width'),
            h = $('.preview_img_ie').find('img').attr('height');
    },

    onCancel: function(){
        var btn = $('.btn-control-modal').find('.cancel');
        btn.on('click',function(){
            User_Profile.editProfileModal.modal('hide');
            // $('img.preview_image').attr('src','');
            // $('img.preview_image').hide();
            $('.preview_img').find('img').remove();
            $('.image-preview').find('p').show();
            $('.btn-control-modal').find('.save').addClass('disable');
            $('.preview_img').removeClass('active');
        });
    },

    onBackdrop: function(){
        User_Profile.editProfileModal.on('hidden.bs.modal',function() {
            $('img.preview_image').attr('src','');
            $('img.preview_image').hide();
            $('.image-preview').find('p').show();
            $('.btn-control-modal').find('.save').addClass('disable');
            $('.preview_img').removeClass('active');
        });
    },

    ShowModalProfile: function(){
        var profileModal = $('#modal_profile'),
            self = this;

        profileModal.modal({
            backdrop: true,
            keyboard: false
        });

        Common.CustomScrollBar(profileModal.find('.modal-body'));

        profileModal.on('hidden.bs.modal',function() {
            profileModal.modal('hide');
        });
        $('.modal-backdrop.in').click(function(e) {
            profileModal.modal('hide');
        });
    },

    _eventClickPasswordSetting: function() {
        var target = $('#password_setting'),
            self = this;

        target.unbind();
        target.click(function(){
            if(isMobile){
                window.location.href = baseUrl+ "/netwrk/password-setting";
            } else {
                $('.modal').modal('hide');
                Password_Setting.initialize();
            }
        });
    },

    _eventClickSearchSetting: function() {
        var target = $('#search_setting'),
            self = this;

        target.unbind();
        target.click(function(){
            if(isMobile){
                window.location.href = baseUrl+ "/netwrk/search-setting";
            } else {
                $('.modal').modal('hide');
                Search_Setting.initialize();
            }
        });
    },

    _eventClickProfileInfo: function() {
        var target = $('#my_profile_info','.user-details-wrapper'),
            self = this;

        target.unbind();
        target.click(function(){
            if(isMobile){
                window.location.href = baseUrl+ "/netwrk/profile-info";
            } else {
                $('.modal').modal('hide');
                ProfileInfo.initialize();
            }
        });
    },

    _eventClickCommunityTrigger: function(){
        var target = $('.community-modal-trigger','#favoriteCommunities').add('.community-modal-trigger','#recentCommunities');

        target.unbind();
        target.click(function(e){
            var city_id = $(e.currentTarget).attr('data-city-id');
            var lat = $(e.currentTarget).attr('data-lat');
            var lng = $(e.currentTarget).attr('data-lng');

            if(isMobile){
                var url = baseUrl + "/netwrk/topic/topic-page?city="+city_id;
                window.location.href= url;
            } else {
                $('.modal').modal('hide');
                Topic.initialize(city_id);
                //set center the map using city lat and lng
                //Map.SetMapCenter(lat,lng);
            }
        });
    },

    getTemplateGroupInfo: function(parent,target,callback){
        var template = _.template(target.html());
        var append_html = template({groups: User_Profile.templateData.groups});
        parent.append(append_html);

        if(_.isFunction(callback)){
            callback();
        }
    },
    getTemplateTopicInfo: function(parent,target,callback){
        var template = _.template(target.html());
        var append_html = template({topics: User_Profile.templateData.topics});
        parent.append(append_html);

        if(_.isFunction(callback)){
            callback();
        }
    },
    getTemplatePostInfo: function(parent,target,callback){
        var template = _.template(target.html());
        var append_html = template({posts: User_Profile.templateData.posts});
        parent.append(append_html);

        if(_.isFunction(callback)){
            callback();
        }
    },

    //set selected navigation like group, topic or post as active.
    setTabActive: function(){
        var target = $('.navigation-btn-group', '.profile-activity-wrapper');

        //Remove active class from button.
        target.find('.btn').each(function(){
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
            }
        });

        //Check profiles current tab and set selected tab active.
        switch(User_Profile.tab_current) {
            case 'post':
                target.find('.post').addClass('active');
                break;
            case 'topic':
                target.find('.topic').addClass('active');
                break;
            case 'group':
                target.find('.group').addClass('active');
                break;
        }
    },

    //Show group information of users
    ShowGroups: function(){
        var template = $('#recent_activity_container');
        var templateData = $('#profile_group_info');
        var params = {'filter': 'recent', 'user_id': UserLogin};

        //show tamplate
        template.removeClass('hidden');
        template.html('');

        //set tab current as group
        User_Profile.tab_current = 'group';
        User_Profile.setTabActive();

        Ajax.show_user_groups(params).then(function(data){
            var json = $.parseJSON(data);

            //assign ajax data to template data
            User_Profile.templateData.groups = json.data;

            //set my topics count on recent activity section
            if (json.total_count) {
                $('.recent_activities_wrapper', '.profile-activity-wrapper').find('.group-count').html('').html('My Groups: '+json.total_count);
            }


            template.scrollTop(0);
            //hide no data section
            template.find('.no-data').hide();
            User_Profile.getTemplateGroupInfo(template, templateData);
        });
    },

    //Show Topics information of users
    ShowTopics: function(){
        var template = $('#recent_activity_container');
        var templateData = $('#profile_topic_info');
        var params = {'filter': 'recent'};

        //show tamplate
        template.removeClass('hidden');
        template.html('');

        //set tab current as group
        User_Profile.tab_current = 'topic';
        User_Profile.setTabActive();

        Ajax.show_user_topics(params).then(function(data){
            var json = $.parseJSON(data);

            //assign ajax data to template data
            User_Profile.templateData.topics = json.data;

            //set my topics count on recent activity section
            if (json.total_count) {
                $('.recent_activities_wrapper', '.profile-activity-wrapper').find('.group-count').html('').html('My Topics: '+json.total_count);
            }

            template.scrollTop(0);
            //hide no data section
            template.find('.no-data').hide();
            User_Profile.getTemplateTopicInfo(template, templateData);

            // Initialize click on topic name
            Topic.OnClickTopicFeed();
        });
    },
    //Show Topics information of users
    ShowPosts: function(){
        if (isMobile) {
            var template = $('#recent_activity_container', '.Profile-view');
        } else {
            var template = $('#recent_activity_container', User_Profile.contexts.modalProfile);
        }
        var templateData = $('#profile_post_info');
        var params = {'filter': 'recent'};

        //show tamplate
        template.removeClass('hidden');
        template.html('');

        //set tab current as group
        User_Profile.tab_current = 'post';
        User_Profile.setTabActive();

        Ajax.show_user_posts(params).then(function(data){
            var json = $.parseJSON(data);

            //assign ajax data to template data
            User_Profile.templateData.posts = json.data;

            //set my topics count on recent activity section
            if (json.total_count) {
                $('.recent_activities_wrapper', '.profile-activity-wrapper').find('.group-count').html('').html('My Posts: '+json.total_count);
            }

            template.scrollTop(0);
            //hide no data section
            template.find('.no-data').hide();
            User_Profile.getTemplatePostInfo(template, templateData);

            // Initialize click on post name
            Topic.OnClickPostFeed();
        });
    },

    OnClickTabBtn: function () {
        var Context = '.recent_activities_wrapper',
            Topic = '.topic',
            Group = '.group',
            Post  = '.post';

        $(Topic, Context).unbind();
        $(Topic, Context).on('click', function(){
            User_Profile.tab_current = 'topic';
            User_Profile.getDataOnTab();
        });

        $(Group, Context).unbind();
        $(Group, Context).on('click', function(){
            User_Profile.tab_current = 'group';
            User_Profile.getDataOnTab();
        });

        $(Post, Context).unbind();
        $(Post, Context).on('click', function(){
            User_Profile.tab_current = 'post';
            User_Profile.getDataOnTab();
        });

    },
    getDataOnTab: function() {
        switch(User_Profile.tab_current) {
            case 'post':
                User_Profile.ShowPosts();
                break;
            case 'topic':
                User_Profile.ShowTopics();
                break;
            case 'group':
                User_Profile.ShowGroups();
                break;
        }
    },
    getTemplateFavoriteInfo: function(parent,target,callback){
        var template = _.template(target.html());

        var append_html = template({items: User_Profile.templateData.favoriteCommunities});
        parent.append(append_html);

        if(_.isFunction(callback)){
            callback();
        }
    },
    getTemplateRecentInfo: function(parent,target,callback){
        var template = _.template(target.html());

        var append_html = template({items: User_Profile.templateData.recentCommunities});
        parent.append(append_html);

        if(_.isFunction(callback)){
            callback();
        }
    },
    ShowFavoriteCommunities: function(){
        if (isMobile) {
            var parent = $('.fav-communities_content-wrapper', '.Profile-view');
        } else {
            var parent = $('.fav-communities_content-wrapper', User_Profile.contexts.modalProfile);
        }

        var content = $('#profile_fav-communities_template');
        var params = {'filter': 'recent'};

        parent.html('');

        Ajax.show_user_favorite_communities(params).then(function(data){
            var json = $.parseJSON(data);

            //Assign ajax data to template data
            User_Profile.templateData.favoriteCommunities = json.data;

            //Set the template data
            User_Profile.getTemplateFavoriteInfo(parent, content);

            // Initialize the click on community name and delete icon
            User_Profile._eventClickCommunityTrigger();
            Topic.OnClickFavorite();
        });
    },
    ShowRecentCommunities: function(){
        if (isMobile) {
            var parent = $('.recent-communities_content-wrapper', '.Profile-view');
        } else {
            var parent = $('.recent-communities_content-wrapper', User_Profile.contexts.modalProfile);
        }

        var content = $('#profile_recent-communities_template');
        var params = {'filter': 'recent'};

        parent.html('');

        Ajax.show_user_recent_communities(params).then(function(data){
            var json = $.parseJSON(data);

            //Assign ajax data to template data
            User_Profile.templateData.recentCommunities = json.data;

            //Set the template data
            User_Profile.getTemplateRecentInfo(parent, content);

            // Initialize the click on community name and delete icon
            User_Profile._eventClickCommunityTrigger();
            Log.OnClickDelete();
        });
    },
    OnClickBack: function(){
        if(isMobile){
            $('.Profile-view .back-page').off('click').on('click', function(){
                sessionStorage.show_landing = 1;
                window.location.href = baseUrl + "/netwrk/default/home";
            })
        }
    }
};