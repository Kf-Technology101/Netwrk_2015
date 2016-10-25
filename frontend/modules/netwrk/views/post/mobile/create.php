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
            <span class="title">Build line > <span class="line-area"></span> </span>
        </div>
    </div>
    <div class="container">
        <div class="post">
            <div class="post-category-content">
                <section class="post-category-wrapper">
                    <p class="title">What do you want to do</p>
                    <select name="category" class="form-control post-category-dropdown">
                        <option value="1" <?php if($post->category == 1) { ?> selected="selected"<?php }?>>Community</option>
                        <option value="2" <?php if($post->category == 2) { ?> selected="selected"<?php }?>>Breakfast</option>
                        <option value="3" <?php if($post->category == 3) { ?> selected="selected"<?php }?>>Lunch</option>
                        <option value="4" <?php if($post->category == 4) { ?> selected="selected"<?php }?>>Dinner</option>
                        <option value="5" <?php if($post->category == 5) { ?> selected="selected"<?php }?>>Drinks</option>
                        <option value="6" <?php if($post->category == 6) { ?> selected="selected"<?php }?>>Sports</option>
                        <option value="7" <?php if($post->category == 7) { ?> selected="selected"<?php }?>>Outdoor activity</option>
                        <option value="8" <?php if($post->category == 8) { ?> selected="selected"<?php }?>>Groceries</option>
                    </select>
                </section>
            </div>

            <div class="post-title">
                <p class="title"> Give your line a headline </p>
                <!--<div class="input-group">
                    <span class="input-group-addon" id="sizing-addon2">#</span>
                    <input type="text" class="name_post" maxlength="128" placeholder="Head-line" value="<?/*= isset($post->title) ? $post->title :''*/?>">
                </div>-->
                <textarea class="name_post name_post_textarea" placeholder="Whats the line subject?" maxlength="128" id="name_post_textarea"><?= isset($post->title) ? $post->title :''?></textarea>
            </div>

            <?php if(isset($isCreateFromBlueDot) && $isCreateFromBlueDot == true): ?>
                <div class="post-location-content">

                </div>
            <?php endif; ?>

            <div class="post-message hidden">
                <p class="title"> Line </p>
                <textarea class="message" placeholder="Whats the line about?" maxlength="1024"><?= isset($post->content) ? $post->content :''?></textarea>
            </div>

            <?php if(isset($isCreateFromBlueDot) && $isCreateFromBlueDot == true): ?>
                <div class="post-channel-content hidden">

                </div>
                <div class="post-topic-category-content">

                </div>
                <div class="post-timeout-content hidden">

                </div>
            <?php endif; ?>
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
        <p class="title"> Line location </p>
        <input type="text" name="location" class="form-control location-input" value="<%= data.location %>" disabled="disabled"  />
        <input type="hidden" name="formatted_address" value="<%= data.formatted_address %>" />
        <div id="lineLocationImage" class="line-location-image-wrapper hide">
            <img src="" class="line-location-image" alt="" />
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