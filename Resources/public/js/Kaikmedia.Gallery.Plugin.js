/**
 *Gallery plugin manager
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};
KaikMedia.Gallery.Plugin = {};

( function($) {
	
	var mode = 'info';
	
	KaikMedia.Gallery.Plugin.icon = [];
	KaikMedia.Gallery.Plugin.featured = [];
	KaikMedia.Gallery.Plugin.additional = [];	
	
	KaikMedia.Gallery.Plugin.selected = [];
    
    KaikMedia.Gallery.Plugin.init = function ()
    {    	
    	KaikMedia.Gallery.Plugin.loadSelected();
    	//console.log(KaikMedia.Gallery.Settings);
    	/* set origins according to settings */
    	if(KaikMedia.Gallery.Settings.user.enabled){
    		$('#kmgallery_plugin_user_pill').parent('.origins').addClass('active');
    		$('#kmgallery_plugin_user_box').addClass('active');    		
    	}else if(KaikMedia.Gallery.Settings.public.enabled){
    		$('#kmgallery_plugin_public_pill').parent('.origins').addClass('active');    		
    		$('#kmgallery_plugin_public_box').addClass('active');      		    		
    	}else if(KaikMedia.Gallery.Settings.upload.enabled){
    		$('#kmgallery_plugin_upload_pill').parent('.origins').addClass('active');    		
    		$('#kmgallery_plugin_upload_box').addClass('active');      			
    	}
    	  	
    	/* modal auto reset mode on close  */
    	$('#kmgallery_plugin').on('hide.bs.modal', function (event) {
	        KaikMedia.Gallery.Plugin.switchView('reset');   		    		
    	});
    		
    	/* bind selected media previews on click */
		$('#kmgallery_plugin_selected_previews').find('a').each(function(){
		      $(this).on('click', function(e) {
	  		  e.preventDefault();
		      	  var options = {'single': 1,
		      		  			 'type': $(this).data('type'),
		      		  			 'original': $(this).data('original'),
		      		  			 'relation':$(this).data('relation')
		      	  };
	      	  //console.log(type);
	      	  KaikMedia.Gallery.Plugin.openManager(options);	      	  
		      }); 
		});     
		
    	/* bind mode switch */
		$('#kmgallery_plugin_mode').find('a').each(function(){
		      $(this).on('click', function(e) {
		        KaikMedia.Gallery.Plugin.switchView($(this).data('mode'));   		    	  
		      }); 				
		});	
		
    	/* bind media details load on click  */    	
	    $('#kmgallery_plugin').find('a.media-information').each(function() {
	    	  $(this).children('.original').removeClass('current');
		      $(this).on('click', function(e) {
		    		  e.preventDefault();
		        	  KaikMedia.Gallery.Plugin.loadInfo($(this));
		      });    	  
	    }); 		
		
    	/* bind hover action   */    	
	    $('#kmgallery_plugin').find('a.media-toggle').each(function() {	    
		    $(this).hover(
		    function () {
		        $(this).children('.media-icon').removeClass('fa-check-circle').addClass("fa-minus");		        
		     },
			function () {
			        $(this).children('.media-icon').removeClass('fa-minus').addClass("fa-check-circle");		        
			 }		    
		    );
	    });
	    
    };
    
    KaikMedia.Gallery.Plugin.loadSelected = function ()
    {        
    
    $('#kmgallery_plugin_icon_box').find('a.media-information').each(function() {
    	KaikMedia.Gallery.Plugin.icon.push($(this).data('original'));    	
    });
    
    $('#kmgallery_plugin_featured_box').find('a.media-information').each(function() {
    	KaikMedia.Gallery.Plugin.featured.push($(this).data('original'));     	
    });    	    
    
    $('#kmgallery_plugin_additional_box').find('a.media-information').each(function() {
    	KaikMedia.Gallery.Plugin.additional.push($(this).data('original'));    	    
    });    
    
	//console.log(KaikMedia.Gallery.Plugin.icon);    	    	    	
	//console.log(KaikMedia.Gallery.Plugin.featured);  
	//console.log(KaikMedia.Gallery.Plugin.additional);  
    
	
    };
    
    
    KaikMedia.Gallery.Plugin.openManager = function (options)
    {    		 	   	
    	console.log(options);
    	
    	if (options.single){
			$('#kmgallery_plugin_mode').find('.mode').each(function(){
				$(this).addClass('hide');
    			$(this).removeClass('active');    				
			});			
			
    		if(options.type){   		   			
	        	$('.mode-' + options.type).addClass('active');    			
	        	$('.mode-' + options.type).removeClass('hide');
	        	$('#kmgallery_plugin_left_col').find('.tab-pane').each(function(){
	        		$(this).removeClass('active');        	
	        	});   
	        	
	        	$('#kmgallery_plugin_' + options.type + '_box').addClass('active');	        	
	        	KaikMedia.Gallery.Plugin.switchView(options.type);       	
    		};   		
    	}else{   		
    	/* Defautl  */	

    		
    	}    	
    	$('#kmgallery_plugin').modal('show');    	
    };    
    
 
    
    KaikMedia.Gallery.Plugin.switchView = function (type)
    {     	
    	if(type === 'reset'){
			$('#kmgallery_plugin_mode').find('.mode').each(function(){
    			$(this).removeClass('hide');
               	$('#kmgallery_plugin_action_button').html('Save changes');
    		});      		    		
    	}else {   		
	    	$('#kmgallery_plugin').find('a.media-toggle').each(function(){
	    		$(this).addClass('hide');
	    	});
	    	
	    	var original_id = 0;
	    	var original_relation = 0;	    	
	    	
    	    $('#kmgallery_plugin_'+ type +'_box').find('a.media-information').each(function() {
	    	    	original_id = $(this).data('original');
	    	    	original_relation = $(this).data('relation');
	    	    	console.log(original_relation);
	    	    	$('#kmgallery_plugin').find('a.item-information-' + original_id).each(function(){
	    	    		//$(this).removeClass('hide');  	    	
	    	    		console.log($(this));
	    	    		console.log(original_relation);
	    	    		$(this).attr("data-relation", original_relation);
	    	    	});	     	    	
    	    	
	    	    	$('#kmgallery_plugin').find('a.item-toggle-' + $(this).data('original')).each(function(){
	    	    		$(this).removeClass('hide');
	    	    	});	     	
    	    });    		
   		
    		$('#kmgallery_plugin_action_button').html('Save ' + type);        		
    	}	
    }; 
    
    
    KaikMedia.Gallery.Plugin.loadInfo = function (el)
    {   
    	/* bind media details load on click  */    	
	    $('#kmgallery_plugin').find('a.media-information').each(function() {
	    	  $(this).children('.original').removeClass('current'); 	  
	    });       	
    	el.children('.original').addClass('current');
    	var pars = {
    			original: el.data('original'),
    			relation: el.data('relation')		
    	};
		$('#kmgallery_plugin_selected_box').prepend("<i id='temp-spinner' class='fa fa-circle-o-notch fa-spin'></i>");	
        $.ajax({
            type: "GET",
            url: Routing.generate('kaikmediagallerymodule_pluginajax_mediainfo'),
            data: pars
        }).success(function(result) {
        	//console.log(result);
        	
            var template = result.template;
            $('#kmgallery_plugin_selected_box').html(template);
            /*
            KaikMedia.Gallery.AlbumTree.albumBox.html(template);            
            $('#album_save').click(function(e){
            	e.preventDefault();
            	var form = $('form[name="album"]').serialize();	
            	//console.log(form)
                KaikMedia.Gallery.AlbumTree.saveAlbum(pars.id,form);
            })             
            */
        }).error(function(result) {
            alert(result.status + ': ' + result.statusText);
        }).always(function() {
            $('#temp-spinner').remove();
            
        });       
    };    
    
        
    $(document).ready(function() {
        KaikMedia.Gallery.Plugin.init();
    });
})(jQuery);