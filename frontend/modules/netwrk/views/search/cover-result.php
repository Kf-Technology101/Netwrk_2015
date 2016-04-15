<?php use yii\helpers\Url;?>
<div class="cover-result-search">
	<div class="result">

	</div>

	<p class="notice hide">
		Not what your looking for? Bummer, try again with more detail or make it yourself
	</p>
</div>
<script id="cover_result" type="text/x-underscore-template">
	<% if(result.cover_result == 0){ %>
		<p class="no-result all">
			There is no matching result
		</p>
	<% }else{ %>
		<div class="cover-location">
			<% _.each(result.cover_result,function(e){ %>
				<% if(result.result_type == 'zip_code') { %>
					<div class="title title-result" data-value="<%= e.zipCode %>">
						<%= e.zipCode %>
					</div>
				<% } else { %>
					<div class="title title-result" data-value="<%= e.name %>,<%= e.stateAbbr %>">
						<%= e.name %>, <%= e.stateAbbr %>
					</div>
				<% } %>
			<% }); %>
		</div>
	<% } %>
</script>