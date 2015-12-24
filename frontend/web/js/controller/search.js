var Search = {
	parent:'.box-search',
	result:'.result-search',
	params:{
		text:'',
		lat:'',
		lng:''
	},
	initialize: function(){
		if(isMobile){

		}else{
			Search.CustomScrollBar();
		}
		// Search.OnKeypress();
		// Search.OnBlurResult();
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
		Ajax.global_search(Search.params).then(function(data){
			console.log($.parseJSON(data).data);
			$(Search.result).show();
		});
	},

	HideResultSearch: function(){
		var target = $(Search.parent).find('.input-search');

		$(Search.result).hide();
	},

	CustomScrollBar: function(){
		var parent = $(Search.result);
		parent.mCustomScrollbar({
			theme:"dark"
		});
	},

};