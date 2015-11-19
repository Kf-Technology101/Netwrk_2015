var Template ={

    getTemplate:function(parent,target,data,callback) {
        var self = this;
        var json = $.parseJSON(data); 
        var list_template = _.template(target.html());
        var append_html = list_template({topices: json.data});

        parent.append(append_html);

        if($.isFunction(callback)){
            callback();
        }
    }

};