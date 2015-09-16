/**
 *Gallery admin manager
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};
KaikMedia.Gallery.Admin = {};

( function($) {
	
    KaikMedia.Gallery.Admin.init = function ()
    { 
    	$("#save_button").click(function(e) {
    		e.preventDefault();
    	  var objpreferences = {};    	  
    	  $("#objpreferences").serializeArray().map(function(x){
    		 var name = x.name.split('_');
    		 var modname = name[0];
    		 var option = name[1];
    		 objpreferences[modname] = objpreferences[modname] || {};
    		 if (name[2] !== 'undefined'){
    			 var setting = name[2];
    			 var value = x.value
        		 objpreferences[modname][option] = objpreferences[modname][option] || {};
        		 objpreferences[modname][option][setting] = objpreferences[modname][option][setting] || {};        		 
        		 objpreferences[modname][option][setting] = x.value;    			     			 
    		 }else {
        		 //objpreferences[modname][option] = x.value;
    		 }
    	  });       	  
  	      console.log(objpreferences);
  	    KaikMedia.Gallery.Admin.save(objpreferences);
    	});
    };
    
    
    
 
    KaikMedia.Gallery.Admin.save = function(settings) {

    	var pars = {
    			settings: JSON.stringify(settings)
    	};
		$('#save_button').first().after("<i id='temp-spinner' class='fa fa-circle-o-notch fa-spin'></i>");	
        $.ajax({
            type: "POST",
            url: Routing.generate('kaikmediagallerymodule_adminajax_objpreferences'),
            data: pars
        }).success(function(result) {
        	//console.log(result);
        	/*
            var template = result.template;
            KaikMedia.Gallery.AlbumTree.albumBox.html(template);
            $('#album_save').click(function(e){
            	e.preventDefault();
            	var form = $('form[name="album"]').serialize();	
            	//console.log(form)
                KaikMedia.Gallery.AlbumTree.saveAlbum(id,form);
            })             
            KaikMedia.Gallery.AlbumTree.refresh();
            */
        }).error(function(result) {
            alert(result.status + ': ' + result.statusText);
        }).always(function() {
           $('#temp-spinner').remove();
            
        });

        return true;
    };  
    
    $(document).ready(function() {
        KaikMedia.Gallery.Admin.init();
    });
})(jQuery);