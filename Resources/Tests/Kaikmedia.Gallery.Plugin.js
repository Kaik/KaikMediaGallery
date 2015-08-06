/**
 *Gallery manager
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};
KaikMedia.Pages.Manager = {};

( function($) {
    
    KaikMedia.Pages.Manager.init = function ()
    {
    	$form = $('form[name=pageform]');
    	if ( $form.length ) {    	
    	KaikMedia.Pages.Manager.textarea();
    	
        // Get the ul that holds the collection of tags
        $collectionHolder = $form.find('.images');
        
        // add a delete link to all of the existing tag form li elements
        $collectionHolder.find('li').each(function() {
            KaikMedia.Pages.Manager.enableMenu($(this));
            KaikMedia.Pages.Manager.enablePasteListener($(this));  
        });         
        KaikMedia.Pages.Manager.AddNew();
        KaikMedia.Pages.Manager.AddNewImage();        
    	}
    };
    
    KaikMedia.Pages.Manager.enablePasteListener = function ($li)
    { 
    	$li.find('.thumbnail').on('click', function(e) {
            e.preventDefault();    		
    		var sr = $('img', this).attr('src');
    		tinyMCE.execCommand('mceInsertContent', false, '<img width="100" src="' + sr + '"/>');
        	$('#gallery').modal('hide'); 
    	});    
    }
    
    KaikMedia.Pages.Manager.addDeleteLink = function ($FormLi)
    {
        var $removeFormA = $('<a href="#" class="delete hide"> <i class="fa fa-trash fa-2x"> </i></a>');
        $FormLi.find('.menu').append($removeFormA);

        $removeFormA.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();
            // remove the li for the tag form
            $FormLi.remove();
        });
    };
    
    KaikMedia.Pages.Manager.addEditLink = function ($FormLi)
    {
        var $editFormA = $('<a href="#" class="edit hide"> <i class="fa fa-pencil-square fa-2x"> </i></a>');
        $FormLi.find('.menu').append($editFormA);

        $editFormA.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();
            // display editable fields
            $FormLi.find('.name input').toggleClass( "simplebox");
            $FormLi.find('.description textarea').toggleClass( "simplebox");
        });
    };       

    KaikMedia.Pages.Manager.enableMenu = function ($FormLi)
    {
        KaikMedia.Pages.Manager.addDeleteLink($FormLi);
        KaikMedia.Pages.Manager.addEditLink($FormLi); 
    	
    	$FormLi.find('.expand').on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();            
            if ($FormLi.hasClass( "col-xs-12" )){
                // enable menu options            
                $FormLi.find('.edit').toggleClass("hide");
                $FormLi.find('.delete').toggleClass("hide");            	
                $FormLi.removeClass("col-xs-12").addClass("col-xs-4 col-md-3").css("background","");
                $FormLi.find('.image_data').toggleClass("hide");
                $FormLi.find('.thumbnail').toggleClass("col-xs-4");
                $FormLi.find('.thumbnail').css("height","130px");
                $(this).html("<i class='fa fa-pencil-square fa-2x'> </i>" );
            }else {
                // enable menu options
                $FormLi.find('.edit').toggleClass("hide");
                $FormLi.find('.delete').toggleClass("hide");
                $(this).html("<i class='fa fa-check fa-2x'> </i>" ); 
                $FormLi.removeClass("col-xs-4 col-md-3").addClass("col-xs-12").css("background","#eee");
                $FormLi.find('.image_data').toggleClass("hide");
                $FormLi.find('.thumbnail').toggleClass("col-xs-4");
                $FormLi.find('.thumbnail').css("height","auto");            	
            }
        });            

    };    
    
    KaikMedia.Pages.Manager.AddNew = function ()
    {
        $('#addnew').on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();
            //console.log('test');
            // add a new tag form (see next code block)
            var isready = $collectionHolder.find('.new_element');
            //console.log(isready);
        	if ( !isready.length ) {                
            KaikMedia.Pages.Manager.addForm();
        	}            
        });   
    };

    KaikMedia.Pages.Manager.AddNewImage = function ()
    {
    	var id = false;
        $('#addnewimage').on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();
            $.ajax({
                type: 'GET',
                data: {
                    'id' : id,
                },
                url : Routing.generate('kaikmediapagesmodule_galleryajax_new', {'id': id}),
                success: function(response) {
                    if(response) {
                        $('#image_new').html(response.template);
                        $('#gallery_new').find('.modal-footer').html( '<a class="item_save" ><i class="fa fa-save"> </i> Save</a>');
                        $('#gallery_new').modal('show');   
                    	$('.item_save').on('click', function(){                    		
                	    	var form = $('form[name="images"]');
                	    	var formdata = new FormData(form);
                	        $.ajax({
                	            type: 'POST',
                	            data: formdata,
                	            cache: false,
                	            processData: false,
                	            contentType: false,
                	            url : Routing.generate('kaikmediapagesmodule_galleryajax_new'),
                	            success: function(response) {
                	                if(response) {
                	                		console.log(response);
                	               } else {
                	              	//error  
                	              }
                	            }
                	        });                 	    	           	    	
                    	});                          
                   } else {
                  	  //error
                  }
                }
            });            
        });   
    };
    
    KaikMedia.Pages.Manager.addForm = function ()
    {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');
        //console.log(prototype);
        // get the new index
        var index = $collectionHolder.data('index');
        //console.log(index);
       //
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a tag" link li
        var $newLi = $('<li class="gallery_item col-xs-12 col-md-12 form-group-sm new_element"></li>').append(newForm);
        
        $collectionHolder.prepend($newLi);   
        
        $newLi.find('.thumbnail').toggleClass("hide");
        //$newLi.find('.upload_icon').toggleClass("hide");
        //$newLi.find('.upload_file').toggleClass("hide");
        //$newLi.find('.image_data').toggleClass("hide");
        $newLi.find('.expand').toggleClass("hide"); 
        //$newLi.find('.promoted').toggleClass( "hide");
        //$newLi.find('.name').toggleClass( "hide");
        //$newLi.find('.legal').toggleClass( "hide");
        //$newLi.find('.description').toggleClass( "hide");
        //$newLi.find('.menu').toggleClass( "hide");
       // $newFormLi.find('.name').toggleClass( "hide");
        
        $newLi.find(':file').change(function() {
        	var files = $(this)[0].files;
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
                      
        });        
        
    }    

    $(document).ready(function() {
        KaikMedia.Pages.Manager.init();
    });
})(jQuery);