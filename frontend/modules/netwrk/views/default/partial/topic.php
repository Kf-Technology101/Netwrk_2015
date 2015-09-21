<div class="modal" id='modal_topic'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <div class="name_modal"><span> Topics</span></div>
             <div class="filter_option">
                 <table class="filter_sidebar">
                      <tr>
                          <td class="active post">Most posts</td>
                          <td class="view">Most viewed</td>
                          <td class="topic">My Topics</td>
                      </tr>
                  </table> 
             </div>
             <div class="create_topic">
                 <span>Create a topic +</span>
             </div>
      </div>
      <div class="modal-body containt">
           <div id="item_list_post">
             <p class="no-data">There is no data available yet</p>
           </div>
           <div id="item_list_view">
             <p class="no-data">There is no data available yet</p>
           </div>
           <div id="item_list_topic">
             <p class="no-data">There is no data available yet</p>
           </div>
      </div>
    </div>
  </div>
   
   
  <script id="city_name" type="text/x-underscore-template">
    <span><%= city %></span>
  </script>
  <script id="topic_list" type="text/x-underscore-template" >
      <% _.each(topices,function(topic){ %>
          <div class="item">
            <div class="topic_post">
                <div class="name_topic">
                    <p><%= topic.title %></p>
                </div>
                <div class="name_post">
                    <span>#First post</span>
                    <span>#Second post</span>
                    <span>#Third post</span>
                </div> 
            </div>
            <div class="time_ago">
                <span><img src="<%= topic.img%>"/><%= topic.created_at%></span>
            </div>
            <div class="num_count">
                <div class="most_post">
                  <p><%= topic.view_count %></p>
                </div>
                <% if (topic.view_count == 1) {%>
                    <p>View</p>
                <%}else{ %> 
                    <p>Views</p>
                <% } %> 
            </div>
        </div>
    <% }); %>  
    </script>
</div>
