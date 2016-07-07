<?php use yii\helpers\Url; ?>
<div id="create_group_page" data-city="<?php echo $city_id ?>"
    <?php
        if ($data->status == 0){
       echo 'data-zipcode="'.$data->zipcode.'"
            data-lat="'.$data->lat.'"
            data-lng="'.$data->lng.'"';
        }
    ?>
     data-isCreateFromBlueDot="<?php echo $isCreateFromBlueDot; ?>"
     data-name-city="<?php echo $data->city_name;?>">
    <div class="header">
        <div class="back_page">
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="title_page">
            <span class="title"><?php echo ($data->zipcode) ? $data->zipcode : $city->zip_code  ?> > Create Group</span>
        </div>
    </div>

    <div class="container">
        <div class="post">
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
            <?php if(isset($isCreateFromBlueDot) && $isCreateFromBlueDot == true): ?>
                <div class="group-category-content">
                    <section class="group-category-wrapper">
                        <p class="title">Type</p>
                        <select name="office" class="form-control dropdown-office">
                            <?php foreach($zipcode_cities as $item): ?>
                                <option value="<?php echo $item['id'] ?>" data-name-city="<?php echo $item['name'] ?>" data-value="<?php echo $item['id'] ?>"><?php echo $item['office'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </section>
                </div>
            <?php endif; ?>
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