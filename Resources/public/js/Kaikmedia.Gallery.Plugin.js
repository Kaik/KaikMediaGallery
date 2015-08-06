/**
 *Gallery plugin manager
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};
KaikMedia.Gallery.Plugin = {};

( function($) {
    
    KaikMedia.Gallery.Plugin.init = function ()
    {
      $('#kmgallery_plugin_upload_url').on('click', function(e) {
    	  KaikMedia.Gallery.Plugin.addFileForm();
  		});
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
                					$('<span />', { class: 'fa fa-upload fa-2x' })),		
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
       	/*
            var data = new FormData();
            var token = $newLi.find('input[name="upload_token"]').val();
            console.log(token);
            data.append('images[name]', 'test');
            data.append('images[description]', 'lalala');
            data.append('images[promoted]', false);
            data.append('images[path]', 'testpath');
            data.append('images[legal]', 'testlegal');
            data.append('images[publicdomain]', false);            
            data.append('images[file]',files[0]);
            data.append('images[_token]',token);            
            $.ajax({
                type: 'POST',
                data: data,
                url: Routing.generate('kaikmediapagesmodule_galleryajax_add'),
                cache: false,
                contentType: false,
                processData: false,
                success: function(data){
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
                }
            });            
            */
    		
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