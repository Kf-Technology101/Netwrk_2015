var Default ={
    initialize: function() {
        var self = this;
        if(isMobile){
            self._eventClickMeetBtnMobile();
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

    _eventClickMeetBtnMobile: function(){
        var target = $('#btn_meet_mobile');

        target.on('click',function(){
            Meet.showUserMeetMobile();
        });
    }
};