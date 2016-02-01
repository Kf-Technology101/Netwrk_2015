// Define the overlay, derived from google.maps.OverlayView
function Label(opt_options) {
	// Initialization
	this.setValues(opt_options);

	// Label specific
	var span = this.span_ = document.createElement('span');
	// span.style.cssText = 'position: relative; left: -50%; top: -8px; ' +
	//                      'white-space: nowrap; border: 1px solid blue; ' +
	//                      'padding: 2px; background-color: white';
	span.className = 'map-marker-label cid-' + opt_options.cid;
	var div = this.div_ = document.createElement('div');
	div.appendChild(span);
	div.style.cssText = 'position: absolute; display: none';
};
if (typeof google !== "undefined") {
	Label.prototype = new google.maps.OverlayView;
}

// Implement onAdd
Label.prototype.onAdd = function() {
	var pane = this.getPanes().overlayLayer;
	pane.appendChild(this.div_);

	// Ensures the label is redrawn if the text or position is changed.
	var me = this;
	this.listeners_ = [
		google.maps.event.addListener(this, 'position_changed', function() {
			me.draw();
		}),
		google.maps.event.addListener(this, 'text_changed', function() {
			me.draw();
		})
	];
};

// Implement onRemove
Label.prototype.onRemove = function() {
	this.div_.parentNode.removeChild(this.div_);

	// Label is removed from the map, stop updating its position/text.
	for (var i = 0, I = this.listeners_.length; i < I; ++i) {
		google.maps.event.removeListener(this.listeners_[i]);
	}
};

// Implement draw
Label.prototype.draw = function() {
	if(Map.map.getZoom() >= 12){
		var projection = this.getProjection();
		var position = projection.fromLatLngToDivPixel(this.get('position'));

		var div = this.div_;
		div.style.left = (position.x - (32 / 2)) -  60 + 'px'; // position.x + 'px';
		div.style.top = (position.y - 60 + 50) + 'px'; // position.y + 'px';
		div.style.display = 'block';

		this.span_.innerHTML = this.get('text').toString();
	}
};