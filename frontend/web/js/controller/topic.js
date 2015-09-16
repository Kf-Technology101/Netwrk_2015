var Topic = {
    initialize: function(){
        this._onclickBack();
        this.load_topic();
        this.filter_topic();
    },  
    init: function(city){
        if (isMobile) {
            Topic.show_page_topic(city);
        } else {
            Topic.show_modal_topic(city);
        }
    },

    show_modal_topic: function(city){
        Ajax.show_topic(city).then(function(data){
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
        var city = $('#show-topic').data('city');
        var filter = 'post';
        Ajax.show_topic(city,filter).then(function(data){
            Topic.getTemplate(data);
        })
    },

    filter_topic: function(){
        var target = $('.filter_sidebar').find('td');
        var city = $('#show-topic').data('city');
        var self = this;
        target.on('click',function(e){
            var filter = $(e.currentTarget).attr('class');
            if(!$(e.currentTarget).hasClass('active')){
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

    load_topic_filter: function(parent,city,filter){
        Ajax.show_topic(city,filter).then(function(data){
            
            $('#item_list').find('.item').remove();
            Topic.getTemplate(data);
        })
    },

    getTemplate: function(data){
        var a = $.parseJSON(data); 
        var list_template = _.template($( "#topic_list" ).html());
        var append_html = list_template({topices: a});
        $('#item_list').html(append_html);
    },
};
