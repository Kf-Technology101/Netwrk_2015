<?php use yii\helpers\Url; ?>

<section class="Profile Profile-view">
    <section class="header">
        <div class="back_page">
            <span><i class="fa fa-arrow-circle-left"></i> Back </span>
        </div>
        <div class="title_page">
            <span class="title">Profile</span>
        </div>
        <div class="setting">
            <span class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gears"></i></span>
            <ul class="dropdown-menu dropdown-menu-right">
                <li class=''><a href="javascript:" id="password_setting"><i class="fa fa-key"></i> Password setting</a></li>
                <li class=''><a href="javascript:" id="search_setting"><i class="fa fa-search"></i> Search setting</a></li>
                <li class=''><a href="javascript:" id="my_profile_info"><i class='fa fa-user'></i> My profile info</a></li>
                <li class=''><a href="<?= Url::base(true); ?>/netwrk/user/logout"><i class="fa fa-power-off"></i> Sign Out</a></li>
            </ul>
        </div>
    </section>
    <section class="profile-container">
        <section class="profile-info clearfix">
            <div class="cover-photo">
                <img class="img-responsive" src="/img/background/cover-bg.png">
                <div class="change-cover"><i class="fa fa-camera"></i> Edit cover image</div>
            </div>
            <div class="profile-picture pull-left">
                <div class="img-user text-center"><img src="/img/icon/no_avatar.jpg"></div>
                <div class="change-profile">
                    <i class="fa fa-camera"></i>
                </div>
            </div>
            <div class="user-details-wrapper clearfix">
                <div class="user-details pull-left">
                    <div class="user-name">yogesh mahale, 28</div>
                    <div class="user-location">, , </div>
                </div>
                <div class="brillant pull-right">
                    <div class="count">
                        <span>0</span>
                    </div>
                </div>
            </div>
        </section>
        <div class="profile-activity-wrapper">
            <section class="fav-communities-wrapper">
                <div class="activity-header pull-left">Following Communities </div>
                <div class="seperator-line pull-right">
                    <hr>
                </div>
                <div class="clearfix form-group"></div>
            </section>

            <article class="fav-communities_content-wrapper">
                <div class="fav-communities_list clearfix">
                    <div class="fav-communities-list" id="favoriteCommunities">
                        <div class="fav-community">
                            <span class="fav-zip-code pull-left"><a class="community-modal-trigger" href="javascript:" data-city-id="1911">47320</a></span>
                            <span class="fav-action pull-right un-favorite-trigger" data-object-type="city" data-object-id="1911"><i class="fa fa-trash-o"></i></span>
                        </div>
                        <div class="fav-community">
                            <span class="fav-zip-code pull-left"><a class="community-modal-trigger" href="javascript:" data-city-id="1911">47321</a></span>
                            <span class="fav-action pull-right un-favorite-trigger" data-object-type="city" data-object-id="1911"><i class="fa fa-trash-o"></i></span>
                        </div>
                        <div class="fav-community">
                            <span class="fav-zip-code pull-left"><a class="community-modal-trigger" href="javascript:" data-city-id="1911">47322</a></span>
                            <span class="fav-action pull-right un-favorite-trigger" data-object-type="city" data-object-id="1911"><i class="fa fa-trash-o"></i></span>
                        </div>
                    </div>
                </div>
            </article>


            <section class="recent_activities_wrapper">
                <div class="activity-header pull-left">Recent Activities</div>
                <div class="seperator-line pull-right">
                    <hr>
                </div>
                <div class="clearfix form-group"></div>

                <article class="row">
                    <div class="col-xs-8">
                        <div role="group" class="btn-group btn-group-default navigation-btn-group">
                            <button class="btn btn-default group" type="button" id="">
                                <span>Groups</span>
                            </button>
                            <button class="btn btn-default topic" type="button" id="">
                                <span>Topics</span>
                            </button>
                            <button class="btn btn-default post" type="button" id="">
                                <span>Posts</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="group-count">
                            My Groups: 90
                        </div>
                    </div>
                </article>
                <article id="recent_activity_container" class="hidden">
                    <p class="no-data">There is no data available yet</p>
                </article>

            </section>
        </div>
    </section>


</section>

