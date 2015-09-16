var Topic = {
    initialize: function(){
        this._onclickBack();
        this.load_topic();
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
        Ajax.show_topic(city).then(function(data){
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
