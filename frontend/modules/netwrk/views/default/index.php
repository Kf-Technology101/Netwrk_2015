<?php use yii\helpers\Url; ?>
<ul class="indiana_marker" style="display:none;" >

</ul>

<div class="map_content">
  <div id="btn_my_location" data-toggle="tooltip" title="Show My Local Netwrk">
    <i class="fa fa-crosshairs"></i>
  </div>
  <div class="sidebar">
    <div class="container">
      <img src="<?=Url::to('@web/img/icon/location_marker.png'); ?>"/>
    </div>
  </div>
  <div id="btn_meet"><img src="<?= Url::to('@web/img/icon/meet_btn.png'); ?>"/></div>
  <div id="googleMap" style=""></div>
</div>
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
<?= $this->render('partial/topic');?>
<?= $this->render('partial/create_topic');?>
<?= $this->render('partial/meet');?>
<?= $this->render('partial/create_post');?>
<?= $this->render('partial/post');?>
<?= $this->render('partial/chat_post');?>
<?= $this->render('partial/login');?>
<?= $this->render('partial/signup');?>
<?= $this->render('partial/chat_inbox');?>
<?= $this->render('partial/forgot_password');?>
<?= $this->render('partial/reset_password');?>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
