jQuery(document).ready(function($){
	
	//SERVICE INTERACTIVITY
	//selecting category circle shows applicable services and applicable category text / link
	$('.services-categories').on('click', '.category', function(){	
		showServicesForCategory($(this));
		showCategoryInformation($(this));
		
	});
	
	//given a category element, toggle it's description / permalink below the main category display
	function showCategoryInformation($category){
		
		if($category.length != 0){
			
			var categoryID = $category.attr('id');
			
			var $categoryInfo = $('.category-information .information');
			//make sure all containers are inactive
			$categoryInfo.each(function(){
				$(this).removeClass('active');
				$(this).css('display', 'none');
			})
			
			//find active container
			var $activeInfo = $categoryInfo.filter('[id=information-for-' + categoryID + ']');
			
	
			//make required container active
			if($activeInfo.length != 0){
				$activeInfo.css('display', 'block');
				
				setTimeout(function(){
					$activeInfo.addClass('active');
				}, 40);
			}
			
			
		}
	
	
		
	}
	//on load, find the active category and show it's information
	showCategoryInformation($('.services-categories .category.active'));
	showServicesForCategory($('.services-categories .category.active'));
	
	//given a category element, find all of it's products and show them
	function showServicesForCategory($category){
		
		if($category.length != 0){
			
			var categoryID = $category.attr('id');
		
			var $serviceCategories = $('.services-categories .category');
			
			//make other categories inactive
			$serviceCategories.each(function(){
				$(this).removeClass('active');
			})
			
			$category.addClass('active');
			
			var $serviceContainers = $('.services-cards');
			//ensure all containers are not shown
			$serviceContainers.each(function(){
				$(this).css('display', 'none');
				$(this).removeClass('active');
			})
			
			//find our active container
			var $activeContainer = $serviceContainers.filter('[id=services-for-' + categoryID + ']');
			
			if($activeContainer.length != 0){
				$activeContainer.css('display', 'block');
				//do this to allow smooth transition 
				setTimeout(function(){
					$activeContainer.addClass('active');
				}, 40); 
			
			}
			
			
			//Uncmment this to make the active element shift to the centre
			//get a copy of the active category and then remove it
			//var $categoryCopy = $serviceCategories.filter('.active');
			//$serviceCategories.filter('.active').remove();
		
			//get the services again, updated with removed element
			//$serviceCategories = $('.services-categories .category');
	
			//find the middle element and inset our active category in the middle
			//var middlePosition = Math.round(($serviceCategories.length - 1) / 2);	
			//var middelElement = $serviceCategories.eq(middlePosition);
			///middelElement.before($categoryCopy);
			
			
		}
		
		
		
		
	}
	
});
