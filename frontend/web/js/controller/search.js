var Search = {
	parent:'.result-search',
	initialize: function(){
		if(isMobile){

		}else{

		}
		Search.CustomScrollBar();
	},

	CustomScrollBar: function(){
		var parent = $(Search.parent);
		parent.mCustomScrollbar({
			theme:"dark"
		});
	},

};