<?php use yii\helpers\Url; ?>
<ul class="indiana_marker" style="display:none;" >
  <?php
    if($cities){
      foreach($cities as $key => $city){
  ?>
  <li num-city="<?php print $city->id ?>" lat="<?php print $city->lat ?>" lng="<?php print $city->lng ?>"> <?php print $city->name?></li>
  <?php
      }
    }
  ?>
</ul>

<div class="map_content">
  <div class="sidebar">
    <div class="container">
      <img src="<?=Url::to('@web/img/icon/location_marker.png'); ?>"/>
    </div>
  </div>
  <div id="btn_meet"><img src="<?= Url::to('@web/img/icon/meet_btn.png'); ?>"/></div>
  <div id="googleMap" style=""></div>
</div>
<?= $this->render('partial/topic');?>
<?= $this->render('partial/create_topic');?>    
<?= $this->render('partial/meet');?>

<script src="http://maps.googleapis.com/maps/api/js"></script>

