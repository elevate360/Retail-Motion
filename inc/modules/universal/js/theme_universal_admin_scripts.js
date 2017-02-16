jQuery(document).ready(function($){
	
	//Header Info Metabox
	//Conditionally shows / hides options for the 'Header Info' metabox displays on all posts. Only show options if we want to override the header
	$('input[name="header_enabled"]').on('change', function(){
		
		$current_value = $(this).val(); 
		//not needed, hide all other options
		if($current_value == 'no'){
			$(this).parents('.form-table').siblings('.form-table').hide();
		}else{
			$(this).parents('.form-table').siblings('.form-table').show();
		}
	});
	$('input[name="header_enabled"]:checked').trigger('change');



	//create a mutation observer to look for added 'attachments' in the media uploader
	var observer = new MutationObserver(function(mutations){
		
	  // look through all mutations that just occured
	  for (var i=0; i < mutations.length; i++){
	  	
	  	// look through all added nodes of this mutation
	    for (var j=0; j < mutations[i].addedNodes.length; j++){
	    	
	    	//get the applicable element
	    	element = $(mutations[i].addedNodes[j]); 
	    	
	    	if(element.attr('class')){
	    		
	    		elementClass = element.attr('class');
	    		
	    		//find all 'attachments'
	    		if (elementClass.indexOf('attachment') != -1){
	    			
	    			//find attachment inner (which contains subtype info)
	    			attachmentPreview = element.children('.attachment-preview');
	    			if(attachmentPreview.length != 0){
	    				
	    				//only run for SVG elements
	    				if(attachmentPreview.attr('class').indexOf('subtype-svg+xml') != -1){
		    				
		    				var dataID = element.attr('data-id');
		    				
		    				//bind an inner function to element so we have access to it. 
		    				var handler = function(element){
		    					
		    					//do a WP ajax call to get the URL 
			    				$.ajax({
			    					
			    					url: ajaxurl,
			    					data: {
			    						'action'		: 'getattachmenturl',
			    						'attachmentID'	: dataID
			    					},
			    					success: function(data){
			    						if(data){
			    							//replace the default image with the SVG
			    							element.find('img').attr('src', data);
			    							element.find('.filename').text('SVG Image');
			    						}
			    					}
			    				});
		    					
		    				}(element); 
	
		    			}
	    			}
	    			
	    		}
	    		
	    		
	    	}
	    }
	  }
	});
	
	//trigger our observer for the media library
	var mediaGrid = $('#wp-media-grid');

	if(mediaGrid.length != 0){
		observer.observe(mediaGrid.get(0), {
		  childList: true,
		  subtree: true
		});
	}
	
	

	

	//Conditionally show / hide the 'Showcase Metabox' based on the current page template
	$showcaseMetabox = $('#showcase-page-meta-box');
	$pageTemplateSelector = $('#page_template');
	
	$pageTemplateSelector.on('change', function(){

		//show or hide metabox
		($(this).val() != 'page-showcase.php') ? $showcaseMetabox.hide() : $showcaseMetabox.show(); 
		
	});
	$pageTemplateSelector.trigger('change');
	
	
	
	//image upload admin functionality
	$('.image-upload-button').on('click', function(){
		
		var upload_button = $(this);
		var images_container = $(this).siblings('.image-container');
		event.preventDefault();
		
		//Determine the type of element to be selected (defaults to all)
		var fileType = upload_button.attr('data-file-type'); 
		
		//Determine if multiple-selections allowed
		var multiImage = (upload_button.attr('data-multiple-upload') == 'true') ? true : false;
		
		//create the media frame for the uploader
		file_frame = wp.media.frames.customHeader = wp.media({
			title: (multiImage) ? 'Upload / Select your images' : 'Upload / Select your image',
			library: {
				type: 'image'
			},
			button: {
				text: (multiImage) ? 'Select Images' : 'Select Image'
			},
			multiple: multiImage
		});
		
		//on select choose from uploader
		file_frame.on('select', function(){
			
			var attachments = file_frame.state().get('selection').toJSON();
			$(attachments).each(function(){
				
				//get attachment object
				var attachment = (this);
				var attachment_id = attachment.id; 
				
				//get properties based on type
				var attachment_type = attachment.type; 
				var attachment_subtype = attachment.subtype; 
				
				//Images
				if(attachment_type == 'image'){
					
					//if sub-type is SVG
					if(attachment_subtype == 'svg+xml'){
						attachment_sample_image = attachment.url;	
					}
					//else normal image, get preview image
					else{
						attachment_sample_image = '';
						if(attachment.sizes.hasOwnProperty('thumbnail')){
							attachment_sample_image = attachment.sizes['thumbnail'].url;
						}else{
							attachment_sample_image = attachment.sizes['full'].url;
						}
						
					}	
				}
				
			
				//image output
				var image = '';
				
				//determine name of hidden field based on supplied data attribute for re-useability
				//needed as we might have several image upload sections on admin back-end
				var field_name = upload_button.attr('data-field-name');
				var name = (multiImage) ? field_name + '[]' : field_name; 
				
				image += '<div class="image">';
				image +=	'<input type="hidden" name="' + name + '" value="' +  attachment_id + '"/>';
				image +=	'<div class="image-preview" style="background-image:url(' + attachment_sample_image + ');"></div>';
				image +=	'<div class="image-controls cf">';
				image +=		'<div class="control remove_image"><i class="fa fa-minus"></i></div>';
				//only need up/down controls on multi
				if(multiImage){
				image +=		'<div class="control image_up"><i class="fa fa-caret-up"></i></div>';
				image +=		'<div class="control image_down"><i class="fa fa-caret-down"></i></div>';
				}
				image +=	'</div>';
				image += '</div>';
				
				//remove existign image if not multi image
				if(!multiImage){
					images_container.find('.image').remove();
				}
				//add our new image
				images_container.prepend(image);

			});
			
			//call actions 
			
		});
		
		file_frame.open();
		
	});
	
	//removes an image when clicking on the remove button
	$('.image-container').on('click', '.remove_image', function(event){
		$(this).parents('.image').remove();
	});
	
	//moves an image up (moving it to first position)
	$('.image-container.multi-image').on('click', '.image_up', function(event){
		
		var currentImage = $(this).parents('.image');
		var previousImage = currentImage.prev('.image');
		
		//if we have a prev image, insert current before it
		if(previousImage){
			previousImage.before(currentImage);
		}	
	});
	
	$('.image-container.multi-image').on('click', '.image_down', function(event){
		var currentImage = $(this).parents('.image');
		var nextImage = currentImage.next('.image');
		
		//if we have next image, insert current after it
		if(nextImage){
			nextImage.after(currentImage);
		}
	});
	
	//apply sortable for the images
	$('.image-container.multi-image').sortable({
		items: '.image',
		cursor: 'move',
		helper: 'clone',
		tolerance: 'pointer',
		update: function(event,ui){
			
		}
	});
	
	/*Admin Color Picker*/
	$('.colorpicker-field').wpColorPicker({
		palettes: true,
		hide: true
	});
	
	
});
