var Ajax ={

  show_topic: function(id,filter){
    var defer = $.Deferred(),
        url = "get-topic?city="+id+"&filter="+filter;

    $.ajax({
      url: url,
      type: 'GET',
      success: defer.resolve,
      error: defer.reject
    });

    return defer.promise();
  },
}

