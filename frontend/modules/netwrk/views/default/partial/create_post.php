<?php use yii\helpers\Url; ?>
<div class="modal" id='create_post' data-lat="" data-lng="">
    <!-- <div id="btn_discover"><img src="<?= Url::to('@web/img/icon/meet_btn.png'); ?>"/></div> -->
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head">
                    <div class="back_page">
                        <span><i class="fa fa-arrow-circle-left"></i> Back </span>
                    </div>
                    <div class="name_user">
                        <p> Add a line</p>
                    </div>
                </div>
                <div class="scrumb">
<!--                     <div class="logo">
                        <img src="<?#= Url::to('@web/img/icon/netwrk-logo.png'); ?>">
                    </div>
                    <p class="break"> > </p> -->
                    <p class="zipcode"> 46975 </p>
                    <p class="break"> > </p>
                    <p class="topic"> Discussion over Democratic Primary </p>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="modal-body">
                <div class="page" id="create_topic">
                    <div class="post">
                        <input type="hidden" name="post_id" id="post_id" value=""/>
                        <div class="post-title">
                            <p class="title"> Line </p>
                            <div class="input-group">
                                <span class="input-group-addon" id="sizing-addon2">#</span>
                                <input type="text" class="name_post" maxlength="128" placeholder="Head-line">
                            </div>
                        </div>
                        <div class="post-category-content">

                        </div>
                        <div class="post-topic-category-content">

                        </div>
                        <div class="post-message">
                            <p class="title"> Message </p>
                            <textarea class="message" placeholder="Don't be shy! Say something!" maxlength="1024"></textarea>
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
                </div>
            </div>
        </div>
    </div>
</div>
<script id="post-category-template" type="text/x-underscore-template">
    <section class="post-category-wrapper">
        <% if(data.length > 0) { %>
        <p class="title">Type</p>
        <select name="office" class="form-control dropdown-office">
            <% _.each(data, function(item,i) { %>
            <option value="<%= item.id%>" data-value="<%= item.id%>" data-city_name="<%= item.zip_code %>"><%= item.community %></option>
            <% }); %>
        </select>
        <% } %>
    </section>
</script>
<script id="post-topic-category-template" type="text/x-underscore-template">
    <section class="post-topic-category-wrapper">
        <% if(data.length > 0) { %>
        <p class="title">Topic</p>
        <select name="topic" class="form-control post-topic-dropdown">
            <% _.each(data, function(item,i) { %>
            <option value="<%= item.id%>" data-value="<%= item.id%>"><%= item.title %></option>
            <% }); %>
        </select>
        <% } %>
    </section>
</script>