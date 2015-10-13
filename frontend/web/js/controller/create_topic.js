var Create_Topic={
    params:{
        topic:'',
        post: '',
        message:'',
        city:null
    },
    status_change:{
        topic:false,
        post: false,
        message: false,
        total: false
    },

    initialize: function(city){        
        if(isMobile){
            Create_Topic.params.city = $('#create_topic').attr('data-city');
            this.changeData();
            this.onclickBack();
        }else{
            Create_Topic.params.city = city;
            this.showModalCreateTopic();
            this.onCloseModalCreateTopic();
            this.changeData();
        }
        
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
        parent.modal('hide');
        Create_Topic.reset_data();
        Create_Topic.setDefaultBtn();
    },

    onclickBack: function(){
        var parent = $('#create_topic');
        parent.find('.back_page img').click(function(){
            if(isMobile){
                Create_Topic.redirect();
            }else{
                this.showModalCreateTopic();
                this.changeData();
            }
        });
    },
    changeData: function(){
        var parent = $('#create_topic');
        
        this.onChangeData(parent.find('.name_topic'),'topic');
        this.onChangeData(parent.find('.name_post'),'post');
        this.onChangeData(parent.find('.message'),'message');
    },

    redirect: function(){
        window.location.href = baseUrl + "/netwrk/topic/topic-page?city="+Create_Topic.params.city;
    },

    onChangeData: function(target,filter){
        target.unbind();
        target.on('keyup',function(e){
            if($(e.currentTarget).val().length > 0){
                Create_Topic.params[filter] = $(e.currentTarget).val();
                Create_Topic.status_change[filter] = true;
            }else{
                Create_Topic.status_change[filter] = false;
            }
            Create_Topic.onCheckStatus();
        });
        
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
        Create_Topic.OnshowBack();
        Create_Topic.OnshowSave();
    },

    OnshowBack: function(){
        var status = Create_Topic.status_change,
            parent = $('#create_topic'),
            btn = parent.find('.cancel');
        if(status.topic || status.post || status.message){
            btn.removeClass('disable');
            Create_Topic.OnclickBack();
        }else if(!status.topic && !status.post && !status.message){
            btn.addClass('disable');
        }
    },

    OnclickBack: function(){
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
                Ajax.new_topic(Create_Topic.params).then(function(){
                    Create_Topic.reset_data();
                    Create_Topic.setDefaultBtn();
                    setTimeout(function(){
                        if(isMobile){
                            Create_Topic.redirect();
                        }else{
                            Create_Topic.hideModalCreateTopic();
                            Topic.init(city);
                        }
                    },700);
                });
            }
        });
    }
};