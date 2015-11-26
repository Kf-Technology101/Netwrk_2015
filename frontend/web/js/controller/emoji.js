var Emoji ={

	initialize: function(){
	  emojione.imageType = 'png';
	  emojione.ascii = true;
	  emojione.imagePathPNG = baseUrl + '/css/emojione/png/';
	  emojione.imagePathSVG = baseUrl + '/css/emojione/svg/';
	},

	Convert: function(target){
		var emoji = emojione.shortnameToImage(target.text().replace(/(?:\r\n|\r|\n)/g, '<br />'));
		target.html(emoji);
	},

	GetEmoji: function(){
		var emoji =[":heart:", ":broken_heart:", ":joy:", ":smiley:", ":smile:", ":sweat_smile:", ":laughing:", ":wink:" , ":sweat:", ":kissing_heart:", ":stuck_out_tongue_winking_eye:", ":disappointed:", ":angry:", ":cry:", ":persevere:", ":fearful:", ":flushed:", ":dizzy_face:", ":ok_woman:", ":innocent:", ":sunglasses:", ":expressionless:", ":confused:", ":stuck_out_tongue:", ":open_mouth:", ":no_mouth:"]
		return emoji;
	}
}