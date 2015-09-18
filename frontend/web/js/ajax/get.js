var Ajax ={

  show_topic: function(param){
    var url,defer = $.Deferred();

    if (isMobile) {
      url = "get-topic-mobile";
    }else{
      url = "netwrk/topic/get-topic-mobile";
    }

    $.ajax({
      url: url,
      data: param,
      type: 'GET',
      success: defer.resolve,
      error: defer.reject
    });

    return defer.promise();
  },
}

