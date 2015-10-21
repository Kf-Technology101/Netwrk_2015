<ul class="indiana_marker" style="display:none;" >
  <?php
if ($cities) {
    foreach ($cities as $key => $city) {
?>
  <li num-city="<?php
        print $city->id ?>" lat="<?php
        print $city->lat ?>" lng="<?php
        print $city->lng ?>"> <?php
        print $city->name ?></li>
  <?php
    }
}
?>
</ul>

<div class="map_content_mobile">
  <div id="googleMap" style=""></div>
</div>
<script src="http://maps.googleapis.com/maps/api/js"></script>



