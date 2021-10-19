jQuery(function($){
	
	var hash = window.location.hash;
	
	if( hash == '#email' ){
		
		$('#emailmodal').modal('show');
		
	}
	
	equalheight = function(container){

		var currentTallest = 0,
		currentRowStart = 0,
		rowDivs = new Array(),
		$el,
		topPosition = 0;
		
		$(container).each(function() {
	
			$el = $(this);
			$($el).height('auto')
			topPostion = $el.position().top;
			
			if( currentRowStart != topPostion ){
				
				for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
					
					rowDivs[currentDiv].height(currentTallest);
				
				}
				
				rowDivs.length = 0; // empty the array
				currentRowStart = topPostion;
				currentTallest = $el.height();
				rowDivs.push($el);
			
			}else{
				
				rowDivs.push($el);
				currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
				
			}
			
			for( currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++ ){
				
				rowDivs[currentDiv].height(currentTallest);
			
			}
	   
	   });
	 
	}
	
	//This little trickery removes the top border from the element below the active element.
	//Have to admit, this is a little anal on my part.
	$(".sidebar li.active").next().find('a').css('border-top', '0');	
	
	$(window).load(function(){repositionQuicklinks();});
	$(window).resize(function(){repositionQuicklinks();});
	
	repositionQuicklinks = function(){
		
		$('#zquicklinks').css('top', $('#zheader').height());
		
	}
	
	$(document).ready(function(){
		$('#alert-notification').fadeIn();
	});
	
});