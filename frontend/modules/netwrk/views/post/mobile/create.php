<?php use yii\helpers\Url; ?>
<div id="create_post" data-topic="<?= $topic->id?>" data-city="<?= $city->id ?>" data-post_id="<?= $post->id?>"
     data-isCreateFromBlueDot="<?php echo $isCreateFromBlueDot; ?>"
     data-city_zipcode="<?php echo $city_zipcode; ?>"
     data-lat="<?php echo $lat; ?>"
     data-lng="<?php echo $lng; ?>"
>
    <div class="header">
        <div class="back_page">
            <!-- <img src="<?= Url::to('@web/img/icon/back_btn_hdpi.png'); ?>"> -->
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="title_page">
            <?php if($isCreateFromBlueDot == true): ?>
                <span class="title"><?php echo $city_zipcode ?> > Build a line</span>
            <?php else: ?>
                <span class="title"><?= $city->zip_code ?> > Build a line</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <div class="post">
            <?php if(isset($isCreateFromBlueDot) && $isCreateFromBlueDot == true): ?>
                <div class="post-location-content">

                </div>
            <?php endif; ?>
            <div class="post-title">
                <p class="title"> Line </p>
                <!--<div class="input-group">
                    <span class="input-group-addon" id="sizing-addon2">#</span>
                    <input type="text" class="name_post" maxlength="128" placeholder="Head-line" value="<?/*= isset($post->title) ? $post->title :''*/?>">
                </div>-->
                <textarea class="name_post name_post_textarea" placeholder="Whats the line about?" maxlength="128" id="name_post_textarea"></textarea>
            </div>
            <?php if(isset($isCreateFromBlueDot) && $isCreateFromBlueDot == true): ?>
                <div class="post-category-content">

                </div>
                <div class="post-topic-category-content">

                </div>
                <div class="post-timeout-content">

                </div>
            <?php endif; ?>
            <div class="post-message hide">
                <p class="title"> Message </p>
                <textarea class="message" placeholder="Don't be shy! Say something!" maxlength="1024"><?= isset($post->content) ? $post->content :''?></textarea>
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
<script id="post-location-template" type="text/x-underscore-template">
    <section class="post-location-wrapper">
        <p class="title">Location</p>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-lg fa-minus-circle"></i>
            </span>
            <input type="text" name="location" class="form-control location-input" value="<%= data.location %>" disabled="disabled"  />
            <input type="hidden" name="formatted_address" value="<%= data.formatted_address %>" />
        </div>
    </section>
</script>
<script id="post-category-template" type="text/x-underscore-template">
    <section class="post-category-wrapper">
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