<?php use yii\helpers\Url; ?>
<div class="modal" id="create_post" data-lat="" data-lng="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head">
                    <div class="name_user">
                        <p> Build a line</p>
                    </div>
                    <div class="back_page">
                        <span><i class="fa fa-times-circle"></i></span>
                    </div>
                </div>
                <!--<div class="scrumb">
                    <p class="zipcode"> 46975 </p>
                    <p class="break"> > </p>
                    <p class="topic"> Discussion over Democratic Primary </p>
                    <div class="clearfix"></div>
                </div>-->
            </div>
            <div class="modal-body">
                <div class="page" id="create_topic">
                    <div class="post">
                        <input type="hidden" name="post_id" id="post_id" value=""/>
                        <div class="post-location-content">

                        </div>

                        <div class="post-title">
                            <p class="title"> Subject </p>
                            <!--<div class="input-group">
                                <span class="input-group-addon" id="sizing-addon2">#</span>
                                <input type="text" class="name_post" maxlength="128" placeholder="Head-line">
                            </div>-->
                            <textarea class="name_post name_post_textarea" placeholder="Whats the line subject?" maxlength="128" id="name_post_textarea"></textarea>
                        </div>
                        <div class="post-message">
                            <p class="title"> Line </p>
                            <textarea class="message" placeholder="Whats the line about?" maxlength="1024"></textarea>
                        </div>

                        <div class="post-category-content">
                            <section class="post-category-wrapper">
                                <p class="title">Category</p>
                                <select name="category" id="postCategoryDropdown" class="form-control post-category-dropdown">
                                    <option value="1">Friends</option>
                                    <option value="2">Breakfast</option>
                                    <option value="3">Lunch</option>
                                    <option value="4">Dinner</option>
                                    <option value="5">Drinks</option>
                                    <option value="6">Sports</option>
                                    <option value="7">Outdoor activity</option>
                                </select>
                            </section>
                        </div>

                        <div class="post-channel-content">

                        </div>
                        <div class="post-topic-category-content">

                        </div>
                        <div class="post-timeout-content">

                        </div>

                    </div>
                    <div class="btn-control">
                        <!--<div class="cancel disable">
                            <p>Reset</p>
                        </div>-->
                        <div class="save disable">
                            <span>Build</span>
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script id="post-location-template" type="text/x-underscore-template">
    <section class="post-location-wrapper">
        <p class="title">Location</p>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-lg fa-map-marker"></i>
            </span>
            <input type="text" name="location" class="form-control location-input" value="<%= data.location %>" disabled="disabled"  />
            <input type="hidden" name="formatted_address" value="<%= data.formatted_address %>" />
        </div>
    </section>
</script>
<script id="post-channel-template" type="text/x-underscore-template">
    <section class="post-channel-wrapper">
        <% if(! _.isEmpty(data)){ %>
            <p class="title">Channel</p>
            <select name="topic" class="form-control post-topic-dropdown">
                <% _.each(data,function(items, key){ %>
                    <optgroup label="<%= key %>">
                        <% _.each(items,function(item, index){ %>
                            <option value="<%= item.topic_id %>"
                                    data-topic_id="<%= item.topic_id%>"
                                    data-city_id="<%= item.city_id %>"
                                    data-city_name="<%= item.city_name %>"
                                    data-zip_code="<%= item.zip_code %>"
                                    data-community="<%= item.community %>"
                            >
                                <%= item.topic_title %>
                            </option>
                        <% }); %>
                    </optgroup>
                <% }); %>
            </select>
        <% } else {%>
            <div class="alert alert-danger">No channel available in this area. Please check out community on this area and create a channel.</div>
            <select name="topic" class="form-control post-topic-dropdown" disabled="disabled">
                <% if(data.length > 0) { %>
                    <% _.each(data, function(item,i) { %>
                        <option value="<%= item.id%>" data-value="<%= item.id%>"><%= item.title %></option>
                    <% }); %>
                <% } else { %>
                    <option value="" data-value="">No channel available</option>
                <% } %>
            </select>
            <br>
            <div class="text-danger">No channel available in this area. Please check out community on this area and create a channel.</div>
        <% } %>
    </section>
</script>
<script id="post-timeout-template" type="text/x-underscore-template">
    <section class="post-timeout-wrapper">
        <p class="title">Timeout</p>
        <select name="timeout" class="form-control post-timeout-dropdown">
            <option value="0">No timer selected</option>
            <option value="10">10 minutes</option>
            <option value="30">30 minutes</option>
            <option value="60">60 minutes</option>
        </select>
    </section>
</script>