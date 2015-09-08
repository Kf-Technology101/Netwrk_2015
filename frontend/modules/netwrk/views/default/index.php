<ul class="indiana_marker" style="display:none;" >
  <?php
    if($cities){
      foreach($cities as $key => $city){
  ?>
  <li num-city="<?php print $key + 1 ?>" lat="<?php print $city->lat ?>" lng="<?php print $city->lng ?>"> <?php print $city->name?></li>
  <?php
      }
    }
  ?>
</ul>

<div class="map_content">
  <div id="btn_meet"><span>Meet</span></div>
  <div id="googleMap" style="width:1024px;height:600px;"></div>
</div>

<script src="http://maps.googleapis.com/maps/api/js"></script>
