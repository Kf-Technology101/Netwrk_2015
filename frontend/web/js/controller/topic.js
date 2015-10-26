var Topic = {
    data:{ 
        filter: 'post',
        city: '',
        size: 12,
        city_name:'',
        zipcode:''

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
        topic:{
            paging:1,
            status_paging: 1,
            loaded: 0
        }
    },
    params:{
        zipcode:'',
        name:'',
        lat: '',
        lng:'',
    },
    initialize: function(){
        this._onclickBack();
        this.load_topic();
        this.get_data_new_netwrk();
        this.filter_topic($(window));
        this.scroll_bot();
    },

    init: function(city,params){
        if (isMobile) {
            Topic.show_page_topic(city,params);
        }else {
            Topic.show_modal_topic(city,params);
            Topic.close_modal();
            Topic.OnClickBackdrop();
        }
    },
    OnClickBackdrop: function(){
        $('.modal-backdrop.in').click(function(e) {
            $('#modal_topic').modal('hide');
        });
    },

    get_data_new_netwrk: function(){
        var parent = $('#show-topic');

        if (parent.attr('data-zipcode')){
            Topic.params.zipcode = parent.attr('data-zipcode');
            Topic.params.lat = parent.attr('data-lat');
            Topic.params.lng = parent.attr('data-lng');
            Topic.params.name = parent.attr('data-name');
        }
    },

    create_post: function(){
        var btn;
        if(isMobile){
            btn = $('#show-topic').find('.item .num_count');
            btn.unbind();
            btn.on('click',function(e){
                var topic_id = $(e.currentTarget).parents('.item').eq(0).attr('data-item');
                window.location.href = baseUrl + "/netwrk/post/create-post?city="+ Topic.data.city +"&topic="+topic_id;
            });
        }else{
            btn = $('#modal_topic').find('.item .num_count');
            btn.unbind();
            btn.on('click',function(e){
                var target = $(e.currentTarget).parents('.item').eq(0),
                    topic_id = target.attr('data-item');
                    toptic_name = target.find('.name_topic p').text();
                $('#modal_topic').modal('hide');
                // Topic.reset_modal();
                Create_Post.initialize(Topic.data.city,topic_id,Topic.data.city_name,toptic_name);
            });
        }
    },

    create_topic: function(){
        var btn;
        if(isMobile){
            btn = $('#show-topic').find('.create_topic');
            btn.unbind();
            btn.on('click',function(){
                if(Topic.params.zipcode){
                    window.location.href = baseUrl + "/netwrk/topic/create-topic?city="+Topic.data.city+"&zipcode="+Topic.params.zipcode+"&name="+Topic.params.name+"&lat="+Topic.params.lat+"&lng="+Topic.params.lng;
                }else{
                    window.location.href = baseUrl + "/netwrk/topic/create-topic?city="+Topic.data.city;
                }
            });
        }else{
            btn = $('#modal_topic').find('.create_topic');
            btn.unbind();
            btn.on('click',function(){
                $('#modal_topic').modal('hide');
                // Topic.reset_modal();
                Create_Topic.initialize(Topic.data.city,Topic.data.city_name);
            });
        }
    },

    scroll_bot: function(){
        var self = this;
        var containt = $('.containt');
        if (isMobile) {
            $(window).scroll(function() {   
                if( $(window).scrollTop() + $(window).height() == $(document).height() && self.list[self.data.filter].status_paging == 1 ) {
                    setTimeout(function(){
                        self.load_topic_more();
                    },300);
                }
            });
        }else{
            containt.scroll(function(e){
                var parent = $('#item_list_'+self.data.filter);
                var  hp = parent.height() + 20;
                if(containt.scrollTop() + containt.height() == hp && self.list[self.data.filter].status_paging == 1){
                    self.list[self.data.filter].status_paging = 0;
                    setTimeout(function(){
                        self.load_topic_more();
                    },300);
                }
            });
        }
    },

    show_modal_topic: function(city,new_params){
        var self = this;
        var parent = $('#item_list_'+self.data.filter);
        var sidebar = $('.map_content .sidebar');
        self.data.city = city;

        if(new_params){
            self.data.zipcode = new_params.zipcode;
        }else{
            self.data.zipcode = '';
        }

        var params = {'city': self.data.city,'zipcode': self.data.zipcode, 'filter': self.data.filter,'size': self.data.size,'page':1};

        $('#modal_topic .filter_sidebar').find('td').first().addClass('active');
        parent.show();

        $('#modal_topic').modal({
            backdrop: true,
            keyboard: false
        });
        sidebar.show();
        Ajax.show_topic(params).then(function(data){
            parent.scrollTop(0);
            self.list[self.data.filter].loaded = self.list[self.data.filter].paging ;
            self.getTemplate(parent,data);
            if (sidebar.find('.container span').size() == 0){
                self.getTemplateModal(sidebar,data);
            }
            
            self.scroll_bot();
            self.filter_topic(parent);
            

        });
    },

    close_modal: function(){
        $('#modal_topic').on('hidden.bs.modal',function(e) {
            $(e.currentTarget).unbind(); // or $(this)        
            Topic.reset_modal();
        });
    },

    reset_modal: function(){
        var self = this;
        var parent = $("div[id^='item_list']");
        var target = $('#modal_topic .filter_sidebar').find('td');
        var filter = ['post','view','topic'];

        self.data.filter = 'post';
        parent.hide();
        $('.map_content .sidebar').hide();
        $('.map_content .sidebar .container').find('span').remove();
        $.each(filter,function(i,e){
            self.list[e].paging = 1;
            self.list[e].status_paging = 1; 
            self.list[e].loaded = 0; 
        });

        $.each(parent,function(i,e){
            $(e).find('.item').remove();
        });

        $.each(target,function(i,s){
            if($(s).hasClass('active')){
                $(s).removeClass('active');
            }
        });
        $(target[0]).addClass('active');
        Map.get_data_marker();
    },

    show_page_topic: function(city,params){
        console.log(params);
        if (params){
            window.location.href = "netwrk/topic/topic-page?city="+city+"&zipcode="+params.zipcode+"&name="+params.name+"&lat="+params.lat+"&lng="+params.lng; 
        }else{
            window.location.href = "netwrk/topic/topic-page?city="+city;
        }
    },

    _onclickBack: function(){
        $('#show-topic .back_page img').click(function(){
            window.location.href = baseUrl;
        })
    },

    load_topic: function(){
        var self = this;
        var city = $('#show-topic').data('city');
        var zipcode = $('#show-topic').data('zipcode');
        if(zipcode){
            self.data.zipcode = zipcode;
        }else{
            self.data.zipcode = '';
        }
        var params = {'city': city,'zipcode': self.data.zipcode, 'filter': self.data.filter,'size': 12,'page':self.list[self.data.filter].paging};
        self.data.city = city;
        
        $(window).scrollTop(0);
        if($('#Topic').attr('data-action') == 'topic-page'){
            if(self.list[self.data.filter].status_paging == 1){
                Ajax.show_topic(params).then(function(data){
                    var parent = $('#item_list_'+self.data.filter);
                    self.list[self.data.filter].loaded = self.list[self.data.filter].paging ;
                    self.getTemplate(parent,data);
                });
            }
        }
    },

    load_topic_more: function(){
        var self = this;
        self.list[self.data.filter].paging ++ ;
        var params = {'city': self.data.city, 'filter': self.data.filter,'size': self.data.size,'page':self.list[self.data.filter].paging};
        Ajax.show_topic(params).then(function(data){
            var parent = $('#item_list_'+self.data.filter);
            self.getTemplate(parent,data);
        });
    },

    filter_topic: function(contain){
        var target = $('#modal_topic,#show-topic').find('.filter_sidebar td');
        var self = this;
        target.on('click',function(e){
            var filter = $(e.currentTarget).attr('class');
            if(!$(e.currentTarget).hasClass('active')){
                $("#modal_topic,#show-topic").find("div[id^='item_list']").hide();
                contain.scrollTop(0);
                self.data.filter = filter;

                self.change_button_active(target,$(e.currentTarget),self.data.city,self.data.filter);
                self.load_topic_filter($(e.currentTarget),self.data.city,self.data.filter);
            }
        });
    },

    change_button_active: function(target,parent,city,filter){
        $.each(target,function(i,s){
            if($(s).hasClass('active')){
                $(s).removeClass('active');
                parent.addClass('active');
            }
        });
    },

    load_topic_filter: function(){
        var self = this;
        var parent = $('#modal_topic,#show-topic').find('#item_list_'+self.data.filter);
        var params = {'city': self.data.city, 'filter': self.data.filter,'size': 12,'page':self.list[self.data.filter].paging};

        parent.show();

        if (self.list[self.data.filter].paging != self.list[self.data.filter].loaded){
            Ajax.show_topic(params).then(function(data){
                self.getTemplate(parent,data);
                self.list[self.data.filter].loaded = self.list[self.data.filter].paging ;
            });
        }
    },


    getTemplate: function(parent,data){
        var self = this;
        var json = $.parseJSON(data); 
        var list_template = _.template($( "#topic_list" ).html());
        var append_html = list_template({topices: json.data});

        parent.append(append_html);
        self.onTemplate(json); 
    },

    onTemplate: function(json){
        var self = this;

        if(json.data.length == 0){ 
            $('#item_list_'+self.data.filter).find('.no-data').show();
            self.list[self.data.filter].status_paging = 0;
        }else if(json.data.length < 12 && json.data.length > 0){
            $('.no-data').hide();
            self.list[self.data.filter].status_paging = 0;
        }else{
            $('.no-data').hide();
            self.list[self.data.filter].status_paging = 1;
        }
        this.create_topic();
        this.create_post();
    },

    getTemplateModal: function(parent,data){
        var self = this;
        var json = $.parseJSON(data); 
        var list_template = _.template($( "#city_name" ).html());
        var append_html = list_template({city: json.city});
        Topic.data.city_name = json.city;
        parent.find('.container').append(append_html); 
    },
};
