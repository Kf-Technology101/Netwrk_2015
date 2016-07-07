<?php use yii\helpers\Url; ?>
<div class="modal" id='create_group_modal'>
    <!-- <div id="btn_discover"><img src="<?= Url::to('@web/img/icon/meet_btn.png'); ?>"/></div> -->
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head">
                    <div class="back_page">
                        <span><i class="fa fa-arrow-circle-left"></i> Back </span>
                    </div>
                    <div class="name_user">
                        <p>Create Group</p>
                    </div>
                </div>
                <div class="scrumb">
                    <div class="logo">
                        <img src="<?= Url::to('@web/img/icon/netwrk-logo.png'); ?>">
                    </div>
                    <p class="break"> > </p>
                    <p class="zipcode"> 46975 </p>
                    <p class="break"> > </p>
                    <p class="topic"> Discussion over Democratic Primary </p>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="modal-body">
                <div class="page" id="create_topic_group">
                    <div class="post">
                        <div class="clearfix">
                            <div class="post-title">
                                <p class="title">Group name</p>
                                <div class="input-group">
                                    <input type="text" class="name_post" id="group_name" maxlength="128" placeholder="Group name">
                                </div>
                            </div>
                            <div class="group-permission">
                                <p class="title">Permission</p>
                                <div class="dropdown input-group">
                                    <div class="dropdown-toggle" type="button" id="dropdown-permission" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Public</div>
                                    <span class="input-group-addon" data-toggle="dropdown"><i class="fa fa-sort"></i></span>
                                    <ul class="dropdown-menu" aria-labelledby="dropdown-permission">
                                        <li data-value="public">Public</li>
                                        <li data-value="private">Private</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="group-category-content">

                        </div>
                        <div class="post-message">
                            <p class="title">Invite users by email</p>
                            <textarea class="message" id="emails-input" placeholder="Enter users or emails separated by commas ( , )" maxlength="1024"></textarea>
                            <div class="error-msg">Please input valid email address</div>
                            <div class="add" id="add-email">
                                <span>Add</span>
                            </div>
                        </div>
                        <div class="post-message">
                            <p class="title">User lists</p>
                            <div class="user-lists">
                                <ol id="emails-list">
                                    <li>jonny@xyz.com<span class="delete"></span></li>
                                    <li>novy@gmail.com<span class="delete"></span></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="btn-control">
                        <div class="cancel" id="cancel_group">
                            <p>Cancel</p>
                        </div>
                        <div class="save" id="save_group">
                            <span>Create group</span>
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script id="group-category-template" type="text/x-underscore-template">
    <section class="group-category-wrapper">
        <% if(data.length > 0) { %>
            <p class="title">Type</p>
            <select name="office" class="form-control dropdown-office">
                <% _.each(data, function(item,i) { %>
                    <option value="<%= item.id%>" data-value="<%= item.id%>"><%= item.office %></option>
                <% }); %>
            </select>

        <% } %>
    </section>
</script>