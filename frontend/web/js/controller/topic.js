var Topic = {
    data:{ 
        filter: 'post',
        city: '',
        size: 12,
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
    initialize: function(){
        this._onclickBack();
        this.load_topic();
        this.filter_topic();
        this.scroll_bot();
    },  
    init: function(city){
        if (isMobile) {
            Topic.show_page_topic(city);
        } else {
            Topic.show_modal_topic(city);
        }
    },

    scroll_bot: function(){
        var self = this;
        $(window).scroll(function() {   
            if( $(window).scrollTop() + $(window).height() == $(document).height() && self.list[self.data.filter].status_paging == 1 ) {
                setTimeout(function(){
                    self.load_topic_more();
                },700);
            }
        });
    },
    show_modal_topic: function(city){

        var filter = 'post';
        var params = {'city': city, 'filter': filter,'size': 5,'page':1};
        Ajax.show_topic(params).then(function(data){
            console.log(data);
        })
    },

    show_page_topic: function(city){
        var self = this;
        window.location.href = "netwrk/topic/topic-page?city="+city;   
    },

    _onclickBack: function(){
        $('.back_page i').click(function(){
            window.history.back();
        })
    },

    load_topic: function(){
        var self = this;
        var city = $('#show-topic').data('city');
        var params = {'city': city, 'filter': self.data.filter,'size': 12,'page':self.list[self.data.filter].paging};
        self.data.city = city;
        $(window).scrollTop(0);
        if(self.list[self.data.filter].status_paging == 1){
            Ajax.show_topic(params).then(function(data){
                var parent = $('#item_list_'+self.data.filter);
                self.list[self.data.filter].loaded = self.list[self.data.filter].paging ;
                self.getTemplate(parent,data);
            });
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

    filter_topic: function(){
        var target = $('.filter_sidebar').find('td');
        var city = $('#show-topic').data('city');
        var self = this;
        target.on('click',function(e){
            var filter = $(e.currentTarget).attr('class');
            if(!$(e.currentTarget).hasClass('active')){
                $("div[id^='item_list']").hide();
                $(window).scrollTop(0);
                self.data.city = city;
                self.data.filter = filter;

                self.change_button_active(target,$(e.currentTarget),city,filter);
                self.load_topic_filter($(e.currentTarget),city,filter);
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
        var parent = $('#item_list_'+self.data.filter);
        var params = {'city': self.data.city, 'filter': self.data.filter,'size': 12,'page':self.list[self.data.filter].paging};

        parent.show();
        console.log(self.list[self.data.filter].paging);
        console.log(self.list[self.data.filter].loaded);

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
        var append_html = list_template({topices: json});
        parent.append(append_html); 

        if(json.length == 0 || json.length < 12){
            self.list[self.data.filter].status_paging = 0;
        }
    },
};
