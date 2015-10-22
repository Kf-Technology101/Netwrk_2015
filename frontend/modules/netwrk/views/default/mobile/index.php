<ul class="indiana_marker" style="display:none;" >
</ul>

<div class="map_content_mobile">
  <div id="googleMap" style=""></div>
</div>
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


