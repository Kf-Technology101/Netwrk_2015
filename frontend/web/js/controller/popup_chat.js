var PopupChat = {
  params:{
    post:''
  },
  total_popups: 0,
  popups: [],

  initialize: function() {
    if(isMobile){

    }else{
      PopupChat.RegisterPopup();
    }
  },

  Remove: function(array, from, to) {
    var rest = array.slice((to || from) + 1 || array.length);
    array.length = from < 0 ? array.length + from : from;
    return array.push.apply(array, rest);
  },

  ClosePopup: function(id) {
    for(var i = 0; i < PopupChat.popups.length; i++)
    {
      if(id == PopupChat.popups[i])
      {
        PopupChat.Remove(PopupChat.popups, i);
        
        document.getElementById(id).style.display = "none";
        
        PopupChat.CalculatePopups();
        
        return;
      }
    }
  },

  DisplayPopups: function() {
    var right = 330;
  
    var i = 0;
    for(i; i < PopupChat.total_popups; i++)
    {
      if(PopupChat.popups[i] != undefined)
      {
        var element = document.getElementById(PopupChat.popups[i]);
        element.style.right = right + "px";
        right = right + 320;
        element.style.display = "block";
      }
    }

    for (var j = (PopupChat.popups.length - i)-1; j >= 0; j--) {
      var element = document.getElementById(PopupChat.popups[j]);
      element.style.display = "none"; 
      PopupChat.ClosePopup(PopupChat.popups[j]);
    }
  },

  RegisterPopup: function(id, name) {
    console.log('RegisterPopup');
    for(var i = 0; i < PopupChat.popups.length; i++)
    {   
      //already registered. Bring it to front.
      if(PopupChat.params.post == PopupChat.popups[i])
      {
        PopupChat.Remove(PopupChat.popups, i);
        PopupChat.popups.push(PopupChat.params.post);
        PopupChat.CalculatePopups();
        return;
      }
    }                
    PopupChat.getTemplate();  
    PopupChat.popups.push(PopupChat.params.post);
    PopupChat.CalculatePopups();
  },

  getTemplate: function(){
    // var self = this;
    var list_template = _.template($("#popup_chat" ).html());
    var append_html = list_template({post_id: PopupChat.params.post});

    $('body').append(append_html);
    
  },
  CalculatePopups: function() {
    var width = window.innerWidth;

    if(width < 540)
    {
      PopupChat.total_popups = 0;
    }
    else
    {
      width = width - 200;
      //320 is width of a single popup box
      PopupChat.total_popups = parseInt(width/320);

      // maximum is 4 popups
      if (PopupChat.total_popups > 4) {
        PopupChat.total_popups = 4;
      }
    }
    
    PopupChat.DisplayPopups();
  }



}