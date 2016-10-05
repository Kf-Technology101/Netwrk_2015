<?php use yii\helpers\Url; ?>
<div id="meetListing" class="hide">
    <article class="header hide">
        <div class="netwrk-icon landing-trigger">
            <img src="/img/icon/netwrk-icon-inactive.png">
        </div>
        <div class="title-page">
            <span class="title">Meet</span>
        </div>
    </article>
    <section id="meetListWrapper" class="meet-list-wrapper">
        <p class="no_data alert alert-info text-center">Currently there is no data for meet</p>
        <ul>

        </ul>
    </section>
</div>
<div id="show_meet" class="">
  <div class="container_meet">
    <div class='page' id="meeting_page">
      <div class="user_list">
          <div class="meet-navigation-control control-btn">
              <div class="nav-control back disable">
                  <i class="fa fa-caret-left"></i>
              </div>
              <div class="nav-control next">
                  <i class="fa fa-caret-right"></i>
              </div>
          </div>
      </div>
      <p class="alert alert-info no_data">There is no relevant user. Check in another netwrk. Try with broadening your Meet Settings.</p>
    </div>
  </div>
</div>

<script id="name_user" type="text/x-underscore-template">
    <span class="user_meet_<%= vt %> name" data-meet="<%= user.met %>"><%= user.username %></span>
</script>

<script id="list_user" type="text/x-underscore-template">
  <div class="user_item user_meet_<%= vt %>">
      <div class="avatar-image"><img src="<%= user.image %>"/></div>
      <div class="user-info text-center">
          <h4 class="user-name"><%= user.username %></h4>
          <div class="user-meet-info"><%= user.meet_info %></div>
          <div class="control-btn">
              <div class="btn btn-block meet">
                  <span>Meet</span>
              </div>
              <div class="btn btn-block met <% if(user.meet == 1) { %> met-green<%}%>">
                  <span>Met</span>
              </div>
          </div>
      </div>
  </div>
</script>

<script id="user_info" type="text/x-underscore-template">
    <div class="user_avatar">
        <div class="img_user"><img src="<%= data.image %>"></div>
        <div class="change_avatar">
            <i class="fa fa-camera"></i>
        </div>
    </div>
    <div class="user_information">
        <div class="user"><%= data.username %>, <%= data.year_old %></div>
        <div class="field">
            <p><i class="fa fa-birthday-cake"></i> Birthday </p>
            <input type="text" class="birthday" maxlength="10" value="<%= data.age %>" />
        </div>
        <div class="field field-margin">
            <p><i class="fa fa-map-marker"></i> Home Zip </p>
            <input type="text" class="zip_code" maxlength="5" value="<%= data.zip %>" />
        </div>
        <div class="field_info">
            <p><i class="fa fa-briefcase"></i> Work </p>
            <input type="text" class="work" maxlength="128" value="<%= data.work %>"/>
        </div>
        <div class="field_info">
            <p><i class="fa fa-info-circle"></i> About </p>
            <textarea class="about" maxlength="2000"><%= data.about %></textarea>
        </div>
    </div>
    <div class="btn-control">
        <div class="cancel disable">
            <p>Reset</p>
        </div>
        <div class="save disable">
            <span>Save</span>
            <i class="fa fa-check"></i>
        </div>
    </div>
</script>

<script id="user_name_current" type="text/x-underscore-template">
    <span class="name"><%= data.username %></span>
</script>

<script id="meet_list" type="text/x-underscore-template" >
    <% _.each(meet_list,function(meet_user){ %>
        <li class="clearfix">
            <div class='meet-user-row'>
                <div class='user-image'>
                    <img src='<%= meet_user.information.image %>' />
                </div>
                <div class='user-details-wrapper'>
                    <div class='user-name'><%= meet_user.username %></div>
                    <!--<div class='user-info'><%= meet_user.information.about %></div>-->
                    <div class="user-meet-info"><%= meet_user.information.meet_info %></div>
                </div>
                <div class='meet-button-wrapper' data-user-id="<%= meet_user.user_id %>">
                    <% if(meet_user.met == 0) { %>
                        <div class="btn btn-default btn-meet-trigger">Meet</div>
                    <% } else { %>
                        <% if(meet_user.meet == 0) { %>
                            <div class="btn btn-default btn-met btn-meet-trigger">Met</div>
                        <% } else { %>
                            <div class="btn btn-default btn-met-green">Met</div>
                        <% } %>
                    <% } %>
                </div>
            </div>
        </li>
    <% }); %>
</script>
