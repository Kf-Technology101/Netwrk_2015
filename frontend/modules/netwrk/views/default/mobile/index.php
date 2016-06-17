<?php use yii\helpers\Url; ?>
<ul class="indiana_marker" style="display:none;" >
</ul>

<div class="map_content_mobile">
  <div id="googleMap" style=""></div>
</div>
<!-- Loader -->
<div class="loader-wrap hide">
  <div class="netwrk-loader">
    <img src="<?= Url::to('@web/img/icon/loader.gif'); ?>" alt="loading..."/>
  </div>
</div>
<!-- /Loader -->
<script src="http://maps.googleapis.com/maps/api/js"></script>
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


