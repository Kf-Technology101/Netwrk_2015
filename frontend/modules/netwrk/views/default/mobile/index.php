<?php use yii\helpers\Url; ?>
<ul class="indiana_marker" style="display:none;" >
</ul>

<div class="map_content_mobile noselect">
  <div id="googleMap" style=""></div>
</div>
<div id="btnCenterLocation" class="hide" data-toggle="tooltip" data-placement="bottom" title="Make Location Centered">
  <i class="fa fa-location-arrow"></i>
</div>
<div id="btnShareLocation" class="" data-toggle="tooltip" data-placement="bottom" title="Share Your Location">
  <i class="fa fa fa-crosshairs"></i>
</div>
</div>
<!-- Loader -->
<div class="loader-wrap hide">
  <div class="netwrk-loader">
    <img src="<?= Url::to('@web/img/icon/loader.gif'); ?>" alt="loading..."/>
  </div>
</div>
<!--<div class="loader-text-wrap hide">
  <div class="netwrk-text-loader">
    <img src="<?/*= Url::to('@web/img/icon/loader-text.gif'); */?>" alt="loading..."/>
  </div>
</div>-->
<!-- /Loader -->
<script src="https://maps.googleapis.com/maps/api/js"></script>
<script src="/js/lib/richmarker-compiled.js"></script>
<script id="netwrk_place" type="text/x-underscore-template">
  <%
    if(cities){
      _.each(cities,function(city){
  %>
        <li num-city="<%= city.id %>" lat="<%= city.lat %>" lng="<%= city.lng %>"> <%= city.name %> </li>
  <%
      }); 
    }
  %>
</script>

<?= $this->render('@frontend/modules/netwrk/views/marker/blue_dot_post_content') ?>
<?= $this->render('@frontend/modules/netwrk/views/default/partial/landing_welcome');?>
<?= $this->render('@frontend/modules/netwrk/views/default/partial/fb_share_email_setting.php');?>
<?= $this->render('@frontend/modules/netwrk/views/default/partial/on_boarding.php');?>
<?= $this->render('@frontend/modules/netwrk/views/default/partial/social_signup_profile_info.php');?>
<?= $this->render('@frontend/modules/netwrk/views/default/partial/join_home_confirmation_modal.php');?>

