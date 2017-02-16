jQuery(document).ready(function($){
	
	$('.sticky-side-menu').on('click' , '.menu-toggle', function(){
		
		//make overlay active
		$(this).parents('.sticky-side-menu').siblings('.sticky-side-menu-background ').toggleClass('active');
		$(this).parent('.sticky-side-menu').toggleClass('active');
		$(this).toggleClass('active');
	});
	
	//clicking on the background closes the menu
	$('.sticky-side-menu-background').on('click', function(){
		$(this).toggleClass('active');
		//remove active state from menu
		$(this).siblings('.sticky-side-menu').toggleClass('active');
		$(this).siblings('.sticky-side-menu').find('.sticky-side-menu').toggleClass('active');
		
	});
	
});
