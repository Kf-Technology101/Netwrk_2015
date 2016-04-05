var Log = {
    params: {},

    initialize: function() {
        console.log('log init');
    },
    create: function(params) {

        Ajax.create_log(params).then(function(data){
            var json = $.parseJSON(data);

            console.log(json);
        });
    },
    OnClickDelete: function() {
        if(isMobile) {
            var target = $('.Profile-view').find('.remove-recent-trigger');
        } else {
            var target = $('#modal_profile').find('.remove-recent-trigger');
        }

        target.unbind();
        target.on('click',function(){
            var self = $(this),
                params = {
                    'type': self.attr('data-type'),
                    'log_id': self.attr('data-log_id'),
                    'city_id': self.attr('data-city_id')
                };

            Ajax.delete_log(params).then(function(data){
                var json = $.parseJSON(data);

                if(target[0].className == 'recent-action pull-right remove-recent-trigger'){
                    self.closest('.recent-community').remove();
                }

                console.log(json);
            });

        });
    }

};