function CustomMarker(latlng, map, args) {
	this.latlng = latlng;	
	this.args = args;	
	this.setMap(map);	
}

CustomMarker.prototype = new google.maps.OverlayView();

CustomMarker.prototype.draw = function() {
	
	var self = this;
	
	var div = this.div;
	
	if( !div ){
	
		div = this.div = document.createElement('div');
		
		div.className = 'zmarker-wrapper';
		
		inner_div = document.createElement('div');
		inner_div.className = 'zmarker zmarker-'+self.args.label_direction;
		
		div.appendChild(inner_div);
		
		if( typeof(self.args.division) !== 'undefined' ){
		
			var division_wrapper 	= document.createElement('h2');
			var division			= document.createTextNode( self.args.division );
			
			division_wrapper.appendChild(division);
			inner_div.appendChild(division_wrapper);
			
		}
		
		if( typeof(self.args.contact_name) !== 'undefined' ){
		
			var title_wrapper 	= document.createElement('h3');
			var title			= document.createTextNode( 'Director '+self.args.contact_name );
			
			title_wrapper.appendChild(title);
			inner_div.appendChild(title_wrapper);
			
		}
		
		if( typeof(self.args.contact_image) !== 'undefined' ){
		
			var img_wrapper 	= document.createElement('div');
			img_wrapper.className = 'zmarker-img';
			img_wrapper.style.backgroundImage = "url('/"+self.args.contact_image+"')";
			//var title			= document.createTextNode( 'Director '+self.args.contact_name );
			
			//img_wrapper.appendChild(div);
			inner_div.appendChild(img_wrapper);
			
		}
		
		
		if( typeof(self.args.marker_id) !== 'undefined' ){
		
			div.dataset.marker_id = self.args.marker_id;
		
		}
		
/*
		google.maps.event.addDomListener(div, "click", function(event){
			
			alert('You clicked on a custom marker! '+ self.args.contact_name);			
			google.maps.event.trigger(self, "click");
		
		});
*/
		
		var panes = this.getPanes();
		panes.overlayImage.appendChild(div);
		
	}
	
	var point = this.getProjection().fromLatLngToDivPixel(this.latlng);
	
	if( point ){
		
		div.style.left	= (point.x - 10) + 'px';
		div.style.top	= (point.y - 20) + 'px';
		
	}
	
};

CustomMarker.prototype.remove = function(){
	
	if( this.div ){
		
		this.div.parentNode.removeChild(this.div);
		this.div = null;
		
	}
		
};

CustomMarker.prototype.getPosition = function(){
	
	return this.latlng;	

};