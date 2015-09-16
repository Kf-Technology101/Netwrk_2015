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
  <div id="btn_meet"><img src="<?= Url::to('@web/img/icon/meet_btn.png'); ?>"/></div>
  <div id="googleMap" style=""></div>
</div>
<div class="modal">
  <?= $this->render('partial/topic');?>
</div>
