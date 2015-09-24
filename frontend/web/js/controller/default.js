var Default ={
    initialize: function() {
        var self = this;
        if(isMobile){

        }else{
            self._eventClickMeetBtn();
        }
    },

    _eventClickMeetBtn: function() {
        var target = $('#btn_meet'),
            self = this;

        target.on('click',function(){
            Meet.initialize();
        });
    },


};