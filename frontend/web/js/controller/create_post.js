var Create_Post={
    params:{
        topic: null,
        post:'',
        message: '',
    },
    status_change:{
        post: false,
        message: false,
        total: false
    },

    initialize: function(city,topic){
        if(isMobile){
            Create_Post.params.topic = $('#create_post').attr('data-topic');
            Create_Post.params.city = $('#create_post').attr('data-city');
            Create_Post.changeData();
            Create_Post.onclickBack();
        }else{
            Create_Post.params.city = city;
            Create_Post.params.topic = topic;
            Create_Post.showModalCreatePost();
            Create_Post.onCloseModalCreatePost();
            Create_Post.changeData();
        }
    },

    showModalCreatePost: function(){
        var parent = $('#create_post');
        parent.modal({
            backdrop: true,
            keyboard: false
        });
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
        target.on('keyup',function(e){
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
        Create_Post.OnshowBack();
        Create_Post.OnshowSave();
    },

    OnshowBack: function(){
        var status = Create_Post.status_change,
            parent = $('#create_post'),
            btn = parent.find('.cancel');
        if(status.post || status.message){
            btn.removeClass('disable');
            Create_Post.OnclickBack();
        }else if(!status.post && !status.message){
            btn.addClass('disable');
        }
    },

    OnclickBack: function(){
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
        var parent = $('#create_post');
        parent.find('.back_page img').click(function(){
            if(isMobile){
                Create_Post.redirect();
            }else{
                this.showModalCreateTopic();
                this.changeData();
            }
        });
    },

    redirect: function(){
        window.location.href = baseUrl + "/netwrk/topic/topic-page?city="+Create_Post.params.city;
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
                Ajax.new_post(Create_Post.params).then(function(){
                    Create_Post.reset_data();
                    Create_Post.setDefaultBtn();
                    setTimeout(function(){
                        if(isMobile){
                            Create_Post.redirect();
                        }else{
                            Create_Post.hideModalCreatePost();
                            Topic.init(city);
                        }
                    },700);
                });
            }
        });
    }

};