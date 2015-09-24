var Meet ={
    params:{
        user_id: 0,
        gender: 'All',
        distance: '',
        age: ''
    },
    initialize: function() {
        var self = this;

        self.ShowModalMeet();
    },

    ShowModalMeet: function(){
        var modal = $('#modal_meet'),
            self = this;
            modal.modal({
                backdrop: true,
                keyboard: false
            });
        Ajax.getUserMeeting(self.params).then(function(data){
            var json = $.parseJSON(data);
            console.log(json.data);
            // modal.modal({
            //     backdrop: true,
            //     keyboard: false
            // });
        });
        
    },
};