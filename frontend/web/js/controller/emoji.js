var Emoji ={

	initialize: function(){
	  emojione.imageType = 'png';
	  emojione.ascii = true;
	  emojione.imagePathPNG = baseUrl + '/css/emojione/png/';
	  emojione.imagePathSVG = baseUrl + '/css/emojione/svg/';
	},

	Convert: function(target){
		var emoji = emojione.shortnameToImage(target.text());
		target.html(emoji);
	},

	GetEmoji: function(){
		return emojione.asciiList;
	}
}