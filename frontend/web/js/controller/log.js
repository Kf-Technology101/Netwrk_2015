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
    }

};