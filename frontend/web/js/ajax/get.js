var Ajax ={

  show_topic: function(id){
    var defer = $.Deferred(),
        url = "get-topic?param="+id;

    $.ajax({
      url: url,
      type: 'GET',
      success: defer.resolve,
      error: defer.reject
    });

    return defer.promise();
  },
}

