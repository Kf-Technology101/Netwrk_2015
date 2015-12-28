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
            PopupChat.ChangeStylePopupChat();

            var parent = $('.popup-box.chat-popup').find('.popup-messages');
            parent.mCustomScrollbar({
                theme:"dark"
            });
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
                document.getElementById('popup-chat-' + id).style.display = "none";
                PopupChat.CalculatePopups();

                return;
            }
        }
    },

    DisplayPopups: function() {
        var right = 330,
            popup_rights = [];

        $('.popup-box').each(function(){
            var parseRight = parseInt($(this).css('right'));
            if (!isNaN(parseRight)) {
                popup_rights.push(parseRight);
            }            
        });

        min_popup_right = Math.min.apply(Math, popup_rights)
        if (min_popup_right < right) {
            right = min_popup_right;
        }

        var i = 0;
        for(i; i < PopupChat.total_popups; i++)
        {
            if(PopupChat.popups[i] != undefined)
            {
                var element = document.getElementById('popup-chat-' + PopupChat.popups[i]);
                element.style.right = right + "px";
                right = right + 280;
                element.style.display = "block";
            }
        }

        for (var j = (PopupChat.popups.length - i)-1; j >= 0; j--) {
            var element = document.getElementById('popup-chat-' + PopupChat.popups[j]);
            element.style.display = "none";
            PopupChat.ClosePopup(PopupChat.popups[j]);
        }
    },

    RegisterPopup: function() {
        for(var i = 0; i < PopupChat.popups.length; i++)
        {
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
        var list_template = _.template($("#popup_chat").html());
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
            PopupChat.total_popups = parseInt(width/320);

            if (PopupChat.total_popups > 4) {
                PopupChat.total_popups = 4;
            }
        }

        PopupChat.DisplayPopups();
    },

    ChangeStylePopupChat: function() {
        var id = PopupChat.params.post,
            inactive_color = "#5888ac",
            active_color = "#5da5d8";

        $("#textarea-" + id).on("focus", function() {
            $("#popup-chat-" + id + " .popup-head").css("background-color", active_color);
        });
        $("#textarea-" + id).on("focusout", function() {
            $("#popup-chat-" + id + " .popup-head").css("background-color", inactive_color);
        });

        $("#popup-chat-" + id).on("click", function() {
            $(".popup-box.chat-popup .popup-head").css("background-color", inactive_color);
            $("#popup-chat-" + id + " .popup-head").css("background-color", active_color);
        });

        $("body").mouseup(function (e) {
            var container = $("#popup-chat-" + id);

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $("#popup-chat-" + id + " .popup-head").css("background-color", inactive_color);
            }
        });
    }
}
