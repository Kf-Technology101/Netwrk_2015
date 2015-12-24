  <script id="popup_chat" type="text/x-underscore-template">
    <div class="popup-box chat-popup" id="<%= post_id %>">
      <div class="popup-head">
        <div class="popup-head-left">Post name</div>
        <div class="popup-head-right">
          <a href="#">&#10005;</a>
        </div>
        <div style="clear: both"></div>
      </div>
      <div class="popup-messages">
        Content
      </div>
      <div class="popup-btn">
      asdads
                  <form id="msgForm" class="send_message input-group login">
            <textarea type="text" class="form-control" placeholder="Type message here..." maxlength="1024"></textarea>
            <div id="file_btn" class="input-group-addon paper"><i class="fa fa-paperclip"></i></div>
            <input type="file" id="file_upload" name="file_upload" style="display:none" />
            <div class="input-group-addon emoji dropup">
              <i class="fa fa-smile-o dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="button" ></i>
              <ul class="dropdown-menu"></ul>
            </div>
            <div class="input-group-addon send" id="sizing-addon2">Send</div>
          </form>
      </div>
    </div>
  </script>