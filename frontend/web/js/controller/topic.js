var Topic ={
  init: function(city){
    if (isMobile) {
      Topic.show_page_topic(city);
    } else {
      Topic.show_modal_topic(city);
    }
  },

  show_modal_topic: function(city){
    Ajax.show_topic(city).then(function(data){
      console.log(data);
    })
  },

  show_page_topic: function(city){
    window.location.href = "netwrk/topic/get-topic-mobile?param=" + city;
  }

};
