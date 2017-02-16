/*
 * Universal Scripts
 * Used for public facing interactivity that the theme universally requires
 * - Handles sub-menu toggling for vertical menus
 * - Hanldes triggering of video popup if a video links has been set in the header section (page by page basis)
 * - Handles animation containers, animated to an active state when it's in focus. Most elements will fade up and in!
 */

jQuery(document).ready(function($){
	

	$window = $(window);
	windowHeight = $window.height(); 
	windowTopPosition = $window.scrollTop();
	windowBottomPosition = windowTopPosition + windowHeight;
	
	//find all animating containers
	$animation_containers = $('.animation-container');
	
	
	//Animate in containers when scrolling
	function checkElementsInView($elements){
		
		//update current position
		windowTopPosition = $window.scrollTop();
		windowBottomPosition = windowTopPosition + windowHeight;
			
		if($elements.length != 0){
			
			$elements.each(function(){
				
				var $element = $(this);

				var elementHeight = $element.outerHeight();
				var elementTopPosition = $element.offset().top;
				var elementBottomPosition = (elementTopPosition + elementHeight);
				
				//check if in view
				if((elementBottomPosition >= windowTopPosition) && (elementTopPosition <= windowBottomPosition)){
					$element.addClass('active');
				}	
			})
		}	
	}
	checkElementsInView($animation_containers); 
	
	
	
	//hook on scroll
	$(window).on('scroll', function(){
		
		//update current Y position
		windowYPos = $(document).scrollTop();
		
		//check if elements now in view
		checkElementsInView($animation_containers);
		
	});
	
	
	

	
	//clicking on the video button in the header opens the video popup
	$('.video-button-wrap').on('click', function(){
		$('.video-popup').addClass('active');
		
	});
	//clicking on the close buttons closes popup
	$('.toggle-video-popup').on('click', function(){
		$('.video-popup').removeClass('active');
	});
	
	
	
	//Flexslider traditional slider
	$('.flexslider.slider.fade').flexslider({
		selector: ".slides > .slide",
		animation: "fade",
		smoothHeight: true
	});
	
	$('.flexslider.slider').flexslider({
		selector: ".slides > .slide",
		animation: "slide",
		smoothHeight: true
		
	});
	
	//remove empty <p> tags (generally added via the tinymce editor)
	$('p:empty').remove();
	
	
	//Flexslider carousel (used for gallery sliders, displaying X items at once)
	$('.flexslider.carousel').flexslider({
		selector: ".slides > .slide",
		animation: "slide",
		animationLoop: false,
	    itemWidth: 210,
	    itemMargin: 15,
	    minItems: 2,
	    maxItems: 4
	});
	
	//Scroll to top functionality
	$('.scroll-to-top-wrap').on('click', function(){
		$("html, body").animate({ scrollTop: 0 }, "slow");
		
	});
	
	
	//find all collections using the 'equal-height-items' class
	var equalHeightItems = $('.equal-height-items');
	setTimeout(function(){
		
		equalHeightItems.each(function(){
			setEqualHeight(equalHeightItems);
		});
		
	}, 500);
	
	
	//loop through all 'equal-height-items' elements and make them all the same height
	function setEqualHeight( equalHeightItems){
		
		//go through each collection of items
		equalHeightItems.each(function(){
			
			//detect if container is hidden
			var wasHidden = false;
			if(!$(this).is(':visible')){
				$(this).show();
				wasHidden = true;
			}
			
			var items = $(this).find('.equal-height-item');
			var maxHeight = 0;
			
			//find max height
			items.each(function(){
				var element = $(this);
				var height = 0;
				element.css('height', 'auto');
				
				//conditional check for invisible elements
				if(!element.is(':visible')){
					element.show();
					height = $(this).outerHeight();
					element.hide();
				}else{
					height = $(this).outerHeight();
				}
				//compare heights
				if(height > maxHeight){
					maxHeight = height;
				}
				
				
				
			});
			
			//apply height
			items.each(function(){
				$(this).outerHeight(maxHeight);
			})
			
			
			//re-hide container if we need to
			if(wasHidden == true){
				$(this).hide();
			}
			
		})

	}
	
	
	
	setTimeout(function(){
		//Masonry functionality 
		$(".masonry-elements").masonry({
			itemSelector: '.masonry-item'	
		});
	}, 1000);
	
	
	
	
	
	//Toggling sub-menu children in vertical-menus
	$('.vertical-nav').each(function(){
		var menu = $(this);
		
		//find all applicable toggles
		var toggles = menu.find('li.menu-item-has-children > a .submenu-toggle');
		toggles.each(function(){
			
			
			var toggle = $(this);
			var subMenu = toggle.parent('a').siblings('.sub-menu');
			
			//on click
			toggle.on('click', function(e){
				e.preventDefault();
				toggle.toggleClass('active')
				subMenu.toggleClass('active');
				
				subMenu.slideToggle(500);
			});	
		});
	});
	
	//toggling the main menu open / closed.
	$('.toggle-main-menu').on('click', function(){
		var menu = $('#site-navigation');
		menu.toggleClass('active');
		
		var menuChildren = menu.find('ul.menu > li');
		//if opening menu, subtle animate in top level menu items
		if(menu.hasClass('active')){
			
			var totalChildren = menuChildren.length;
			var counter = 0;
			var offset = 100; 
			menuChildren.each(function(){
				var $item = $(this);
				
				setTimeout(function(){
					$item.toggleClass('active');
				}, (offset * counter));
				
				counter++;
			})
		}
		//if closing menu, remove active classes for top level items
		else{
			menuChildren.each(function(){
				$(this).removeClass('active');	
			});
		}
		
	});
	
	
	//Toggling the 'search' overlay
	$('.toggle-search').on('click', function(){
		var search = $('.site-search');
		search.toggleClass('active');
	})
	
	
	//global resize events
	$(window).on("resize", function(){
		setEqualHeight( equalHeightItems );
	});


});