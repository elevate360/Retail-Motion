/*
 * Data Summary JS
 * interactivity for the data-summary elements. Includes animating them in and doing fancy things
 * - Animates characters from 0 to a set number (for a counter effect)
 */

jQuery(document).ready(function($){
	
	
	//check for data summaries and animate if they are in view
	$dataSummaries = $('.data-summary');
	
	$(window).on('scroll', function(){
		
		$dataSummaries.each(function(){
			
			$element = $(this);
			//if activate, trigger functionality
			if($element.hasClass('active')){
				animateDigits($element);
			}
			
		});
		
	});
	
	//Find the digits inside the element and animate them to their final value state
	function animateDigits($element){
		
		//first check if we're already animating, so it's only triggered once
		if(!$element.hasClass('animating')){
			
			$element.addClass('animating');
			
			var $values = $element.find('.value');
			var maxVal = 100;
			
			//loop through all values and update, with a subtle offset
			count = 1;
			duration = 200;
			
			$values.each(function(){
				
				var $value = $(this);
				var targetValue = parseInt($value.text());
				var distance = maxVal - (maxVal - targetValue);
				
				//timeout
				setTimeout(function(){
					
					//make the fragment containing these values active
					var $fragment = $value.parents('.fragment');
					$fragment.addClass('active');
				
					//jump through each step
					for(var counter = 1; counter < distance; counter+=3){
						
						//update number for element
						updateNumber(counter, $value);
					}
					
				}, duration * count);
	
				count++;
			});
				
		}
	}
	
	//visually update our numbers 
	function updateNumber(counter, $value){
		var delay = 25;
		setTimeout(function(){
			$value.text(counter + '%');
		}, delay * counter);
	}
	
	
});
