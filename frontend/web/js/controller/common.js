/**
 * All Global functions required to whole site.
 */
var Common = {
    initialize: function() {
        Common._eventClickProfileNavMenu();
    },

    _eventClickProfileNavMenu: function() {
        var target = $('.profile-trigger'),
            self = this;

        target.unbind();
        target.on('click',function(){
            $('.modal').modal('hide');
            User_Profile.initialize();
        });
    },

    CustomScrollBar: function(taget,options){
        options = (options) ? options : {
            theme:"dark"
        };

        taget.mCustomScrollbar(options);
    },
};
