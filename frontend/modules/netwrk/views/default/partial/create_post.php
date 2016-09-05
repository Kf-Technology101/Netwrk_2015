<?php use yii\helpers\Url; ?>
<div class="left-slider" id="create_post_slider">
    <div class="slider-dialog" id="create_post" data-lat="" data-lng="">
        <div class="slider-content">
            <div class="slider-header">
                <div class="head">
                    <div class="back_page">
                        <span><i class="fa fa-arrow-circle-left"></i> Back </span>
                    </div>
                    <div class="name_user">
                        <p> Build a line</p>
                    </div>
                </div>
                <div class="scrumb">
                    <p class="zipcode"> 46975 </p>
                    <p class="break"> > </p>
                    <p class="topic"> Discussion over Democratic Primary </p>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="slider-body">
                <div class="page" id="create_topic">
                    <div class="post">
                        <input type="hidden" name="post_id" id="post_id" value=""/>
                        <div class="post-category-content">

                        </div>
                        <div class="post-title">
                            <p class="title"> Line </p>
                            <!--<div class="input-group">
                                <span class="input-group-addon" id="sizing-addon2">#</span>
                                <input type="text" class="name_post" maxlength="128" placeholder="Head-line">
                            </div>-->
                            <textarea class="name_post name_post_textarea" placeholder="Whats the line about?" maxlength="128" id="name_post_textarea"></textarea>
                        </div>
                        <div class="post-topic-category-content">

                        </div>
                        <div class="post-message hide">
                            <p class="title"> Message </p>
                            <textarea class="message" placeholder="Don't be shy! Say something!" maxlength="1024"></textarea>
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
<script id="post-category-template" type="text/x-underscore-template">
    <section class="post-category-wrapper">
        <% if(data.length > 0) { %>
            <p class="title">Type</p>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-lg fa-minus-circle"></i>
                </span>
                <select name="office" class="form-control dropdown-office">
                    <% _.each(data, function(item,i) { %>
                    <option value="<%= item.id%>" data-value="<%= item.id%>" data-city_name="<%= item.zip_code %>"><%= item.community %></option>
                    <% }); %>
                </select>
            </div>
        <% } %>
    </section>
</script>
<script id="post-topic-category-template" type="text/x-underscore-template">
    <section class="post-topic-category-wrapper">
        <% if(data.length > 0) { %>
        <p class="title">Channel</p>
        <select name="topic" class="form-control post-topic-dropdown">
            <% _.each(data, function(item,i) { %>
            <option value="<%= item.id%>" data-value="<%= item.id%>"><%= item.title %></option>
            <% }); %>
        </select>
        <% } else { %>
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
        <% } %>
    </section>
</script>