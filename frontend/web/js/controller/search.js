var Search = {
	parent:'.box-search',
	result:'.result-search',
	params:{
		text:'',
		lat:'',
		lng:''
	},
	result_data:'',
	initialize: function(){
		Search.CheckUserLocation();
		if(isMobile){

		}else{
			Search.CustomScrollBar();
		}
		Search.OnKeypress();
		Search.OnBlurResult();
	},

	CheckUserLocation: function(){
		if(isGuest){
	        navigator.geolocation.getCurrentPosition(function(position) {
	        	Search.params.lat = position.coords.latitude;
	        	Search.params.lng = position.coords.longitude;
	        });
		}
	},

	OnBlurResult: function(){
		var target = $(Search.parent).find('.input-search');
		target.unbind('blur');

		target.on('blur',function(){
			target.val('');
			Search.HideResultSearch();
		});
	},

	OnKeypress: function(){
		var target = $(Search.parent).find('.input-search');

		target.unbind('keyup');
		target.on('keyup',function(e){
			var len = $(e.currentTarget).val().length;
			Search.params.text = $(e.currentTarget).val();
			if(len > 1){
				Search.ShowResultSearch()
			}else{
				Search.HideResultSearch()
			}
		});
	},

	ShowResultSearch: function(){
		Ajax.global_search(Search.params).then(function(res){
			Search.result_data = $.parseJSON(res);
			Search.ResetResultSearch();
			Search.GetTemplateSearch();
			$(Search.result).show();
		});
	},

	HideResultSearch: function(){
		var target = $(Search.parent).find('.input-search');
		$(Search.result).hide();
		Search.ResetResultSearch();
	},

	ResetResultSearch: function(){
		$(Search.result).find('.result').empty();
	},

	GetTemplateSearch: function(){
		console.log(Search.result_data);
		var list_template = _.template($("#list_result" ).html());
		var append_html = list_template({result: Search.result_data});

		$(Search.result).find('.result').append(append_html);
	},

	CustomScrollBar: function(){
		var parent = $(Search.result);
		parent.mCustomScrollbar({
			theme:"dark"
		});
	},

};