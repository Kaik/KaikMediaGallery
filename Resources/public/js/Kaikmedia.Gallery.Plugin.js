/**
 *Gallery plugin manager
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};
KaikMedia.Gallery.Plugin = {};

( function($) {
	
	KaikMedia.Gallery.Plugin.selected = [];
    
    KaikMedia.Gallery.Plugin.init = function ()
    {
      $("input[name='pageform[images]']").val(KaikMedia.Gallery.Plugin.selected);
      
      $('#kmgallery_plugin').find('a.mediaselect').each(function() {
    	  if($(this).data('selected') == '1'){
    		  	$(this).css('background-color','#eee');
    			KaikMedia.Gallery.Plugin.selected.push($(this).data('id'));    		  
    	  }   
    	  
      $(this).on('click', function(e) {
    		  e.preventDefault();
        	  KaikMedia.Gallery.Plugin.toggleSelect($(this));
    		});    	  
      });
      
      $("input[name='pageform[images]']").val(KaikMedia.Gallery.Plugin.selected);                
      
      $('#kmgallery_plugin_upload_url').on('click', function(e) {
    	  KaikMedia.Gallery.Plugin.addFileForm();
  		});
    };
    
    KaikMedia.Gallery.Plugin.toggleSelect = function (el)
    {   
  	  if(el.data('selected') == '1'){
  		  	//console.log('unselect');	
	  		var id = el.data('id');	 
	  		for(var i = KaikMedia.Gallery.Plugin.selected.length - 1; i >= 0; i--) {
	  		    if(KaikMedia.Gallery.Plugin.selected[i] === el.data('id')) {
	  		    	KaikMedia.Gallery.Plugin.selected.splice(i, 1);
	  		    }
	  		}	  			  			  		
	  		$("#kmgallery_plugin").find("[data-id='" + id + "']").each(function() {
	  			$(this).css('background-color','#fff').data('selected', '0');
	  		});
	  		$("#kmgallery_plugin_this_gallery").find("[data-id='" + id + "']").parent().remove();	  		
	  }else{
		  	console.log('select');
  		  	el.css('background-color','#eee');
  		  	el.data('selected', '1');
			KaikMedia.Gallery.Plugin.selected.push(el.data('id'));
			el.parent().clone(true, true).appendTo("#kmgallery_plugin_this_gallery");
	  }

      $("input[name='pageform[images]']").val(KaikMedia.Gallery.Plugin.selected);    	  
      console.log($("input[name='pageform[images]']").val());  
    	
    };    
    
    KaikMedia.Gallery.Plugin.addFileForm = function ()
    {     	
    	if(KaikMedia.Gallery.Settings.upload_max_total_size > 0 && parseInt(KaikMedia.Gallery.Settings.user_total) > parseInt(KaikMedia.Gallery.Settings.upload_max_total_size)){
    		$('#kmgallery_plugin_upload').append(  				
                    $('<div />' ,{ id: 'upload_too_big', class: 'col-md-12 rounded_box bg-danger' }).append(
                 		   $('<p />').append('You have exceeded total allowed space. Delete unused files and try again.'))   
             )
          return;   // to do add allowed amount displayed
    	}
    	
    	$('#kmgallery_plugin_upload').empty().append(
                $('<form />', { action: 'sharer.php', method: 'POST', enctype: 'multipart/form-data' }).append(
                	$('<div />' ,{ id: 'upload_box', class: 'col-md-12 rounded_box bg-info' }).append(
                			$('<div />' ,{ id: 'upload_icon_box', class: 'col-md-2' }).append(	
                					$('<span />', { class: 'fa fa-download fa-2x' })),		
                			$('<div />' ,{ id: 'upload_input_box', class: 'col-md-10' }).append(	                					
                			$('<input />', { id: 'upload_input', type: 'file' }))
                    )
                )
         )
            
        $('#upload_input').change(function() {  
        	$('#kmgallery_plugin_upload').empty();
            KaikMedia.Gallery.Plugin.FileUpload($(this));         	                         
        });                                   
    };
    
    KaikMedia.Gallery.Plugin.FileUpload = function (files)
    { 
    	var file = files[0].files[0];
    	//add ext/mimetype validation
    	var validation = KaikMedia.Gallery.Plugin.FileValidation(file);
    	
    	if(validation === true ){
        	
       	console.log('upload');
       	
            var data = new FormData();
            var token = $('input[name="__upload_token"]').val();
            console.log(token);
            data.append('media[name]', 'test');
            data.append('media[description]', 'lalala');
            //data.append('media[path]', 'testpath');
            data.append('media[legal]', 'testlegal');
            data.append('media[publicdomain]', false);            
            data.append('media[file]', file);
            data.append('media[_token]',token);            
            $.ajax({
                type: 'POST',
                data: data,
                url: Routing.generate('kaikmediagallerymodule_upload_newmedia'),
                cache: false,
                contentType: false,
                processData: false,
                success: function(data){
                	
                	console.log(data);
                	/*
                	var file = data.file;
                	//remove elements
                	
                	//hide upload
                    $newLi.find('.upload_icon').toggleClass("hide");
                    $newLi.find('.upload_file').toggleClass("hide"); 
                	//fill new element form with data
                    //thumbnail 
                	$newLi.find('.thumbnail').prepend($('<img>',{id:'image'+file.id ,src: data.homeurl+'/web/'+file.absolute_path}))
                	$newLi.find('.thumbnail').toggleClass("hide");
                    $newLi.find('.thumbnail').css("height","auto");  
                	$newLi.find('.thumbnail').addClass("col-xs-4");
                	//fields
                	$newLi.find('.name input').val(file.name);                  	
                	$newLi.find('.path input').val(file.path);
                	$newLi.find('.description input').val(file.description);
                	$newLi.find('.legal input').val(file.legal);
                	$newLi.find('.publicdomain input').val(file.publicdomain);
                	$newLi.find('.promoted input').val(file.promoted); 
                	//show
                    $newLi.find('.image_data').toggleClass("hide");
                    // enable menu options
                    KaikMedia.Pages.Manager.enableMenu($newLi);  
                	$newLi.find('.expand').html("<i class='fa fa-check fa-2x'> </i>" ).toggleClass("hide");
                    $newLi.find('.edit').toggleClass("hide");
                    $newLi.find('.delete').toggleClass("hide");
                    //listener
                    KaikMedia.Pages.Manager.enablePasteListener($newLi);  
                    //expand
                    $newLi.removeClass("col-xs-4 col-md-3 new_element").addClass("col-xs-12").css("background","#eee");      
                    */              
                }
            });            
            
    		
    	}else{
    	//remove form 	
    		$('#kmgallery_plugin_upload').append(    				
                   $('<div />' ,{ id: 'upload_too_big', class: 'col-md-12 rounded_box bg-danger' }).append(
                		   $('<p />').append(validation))   
            )   		   		
    	}
    };
    
    KaikMedia.Gallery.Plugin.FileValidation = function (file)
    {     	
    	//media max size on/off this is first important check
    	if(KaikMedia.Gallery.Settings.upload_max_media_size > 0 && KaikMedia.Gallery.Settings.upload_max_media_size < file.size || KaikMedia.Gallery.Settings.php_limit > 0 && KaikMedia.Gallery.Settings.php_limit < file.size){
    		$('#kmgallery_plugin_upload').append(
                    KaikMedia.Gallery.Plugin.addFileForm()
            )        
    		return 'File is too big. Please chose another one.';
    	}	
    		
    	if(KaikMedia.Gallery.Settings.upload_max_total_size > 0 && parseInt(KaikMedia.Gallery.Settings.user_total) + file.size > parseInt(KaikMedia.Gallery.Settings.upload_max_total_size)){
            return 'You have exceeded total allowed space. Delete unused files and try again.';
    	}
    	   	
    	return true;
    };    
        
    $(document).ready(function() {
        KaikMedia.Gallery.Plugin.init();
    });
})(jQuery);