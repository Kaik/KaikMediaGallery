



( function($) {
	
	KaikMedia.Gallery.Plugin.mode = 'info';	
	KaikMedia.Gallery.Plugin.icon = [];
	KaikMedia.Gallery.Plugin.featured = [];
	KaikMedia.Gallery.Plugin.additional = [];	
	KaikMedia.Gallery.Plugin.insert = [];
	
	KaikMedia.Gallery.Plugin.selected = [];
    
    KaikMedia.Gallery.Plugin.init = function ()
    {    	
    	KaikMedia.Gallery.Plugin.loadSelected();
    	console.log(KaikMedia);
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
    		//add data reset for origins
	        KaikMedia.Gallery.Plugin.switchView('reset');   		    		
    	});
    	    	
    	/* bind mode switch */
		$('#kmgallery_plugin_mode').find('a').each(function(){
		      $(this).on('click', function(e) {
		        KaikMedia.Gallery.Plugin.switchView($(this).attr('data-mode'));   		    	  
		      }); 				
		});	    	
    		
    	/* bind selected media previews on click */
		$('#kmgallery_plugin_selected_previews').find('a').each(function(){
		      $(this).on('click', function(e) {
	  		  e.preventDefault();
		      	  var options = {'single': 1,
		      		  			 'type': $(this).attr('data-type'),
		      		  			 'original': $(this).attr('data-original'),
		      		  			 'relation':$(this).attr('data-relation')
		      	  };
	      	  //console.log(options);
	      	  KaikMedia.Gallery.Plugin.openManager(options);	      	  
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

    	/* bind hover action */   
    	var relation = 0;
	    $('#kmgallery_plugin_center_col').find('a.media-information').each(function() {
			    $(this).hover(
				    function () {
				    	$('#kmgallery_plugin_center_col').find('a.media-add').each(function() { $(this).addClass('hide') });
				    	relation = $(this).attr('data-relation');
				    	if(relation == '0' && KaikMedia.Gallery.Plugin.mode != 'info'){
				    		var parent = $(this).parent('.item');
				    		var hidden = parent.children('a.media-add');	
					        hidden.removeClass('hide');	
				    		//$(this).children('.media-icon').removeClass('fa-check-circle').addClass("fa-plus");		        
				    		//console.log(hidden_tresure);				    	
				    	};

				    },
					function () {
        
					 }		    
			    );
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
	    
    	/* bind media add on click  */    	
	    $('#kmgallery_plugin').find('a.media-add').each(function() {
	    	  //$(this).children('.original').removeClass('current');
		      $(this).on('click', function(e) {
		    		  e.preventDefault();
		        	  KaikMedia.Gallery.Plugin.addMedia($(this));
		      });    	  
	    }); 	    
	    
    	/* bind media remove on click  */    	
	    $('#kmgallery_plugin').find('a.media-toggle').each(function() {
	    	  //$(this).children('.original').removeClass('current');
		      $(this).on('click', function(e) {
		    		  e.preventDefault();
		        	  KaikMedia.Gallery.Plugin.removeMedia($(this));
		      });    	  
	    }); 	    
	    
	    
    };
    
    KaikMedia.Gallery.Plugin.loadSelected = function ()
    {        
    
    $('#kmgallery_plugin_icon_box').find('a.media-information').each(function() {
    	KaikMedia.Gallery.Plugin.icon.push($(this).attr('data-original'));    	
    });
    
    $('#kmgallery_plugin_featured_box').find('a.media-information').each(function() {
    	KaikMedia.Gallery.Plugin.featured.push($(this).attr('data-original'));     	
    });    	    
    
    $('#kmgallery_plugin_additional_box').find('a.media-information').each(function() {
    	KaikMedia.Gallery.Plugin.additional.push($(this).attr('data-original'));    	    
    });    
	
   // console.log(KaikMedia.Gallery.Plugin.additional);
    
    };
    
    
    KaikMedia.Gallery.Plugin.openManager = function (options)
    {    		 	   	
    	//console.log(options);
    	
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
    		
	    	KaikMedia.Gallery.Plugin.mode = type;
	    	
	    	$('#kmgallery_plugin').find('a.media-toggle').each(function(){
	    		$(this).addClass('hide');
	    		
	    	});
	    	    	
	    	//reset attr
	    	$('#kmgallery_plugin_center_col').find('a.media-information').each(function(){
	    		$(this).attr('data-relation', '0');
	    		
	    	});	    	
	    		    	   	    	
	    	var relation = 0;	    	
	    	
    	    $('#kmgallery_plugin_'+ type +'_box').find('a.media-information').each(function() {
    	    	
	    	    	relation = $(this).attr('data-relation');
	    	    	//console.log(relation);
	    	    	//change data according to feature type/name
	    	    	$('#kmgallery_plugin_center_col').find('a.item-information-' + $(this).attr('data-original')).each(function(){	    	    		
	    	    		$(this).attr('data-relation', relation);
	    	    	});	     	    	
    	    		    	    	
	    	    	//change mark icon accordingly 
	    	    	$('#kmgallery_plugin').find('a.item-toggle-' + $(this).attr('data-original')).each(function(){
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
	    //console.log(mode);
    	el.children('.original').addClass('current');
    	var pars = {
    			mode: KaikMedia.Gallery.Plugin.mode,
    			original: el.attr('data-original'),
    			relation: el.attr('data-relation')		
    	};
    	
		$('#kmplugin_selected_col_title h4').append(" <i id='temp-spinner' class='fa fa-circle-o-notch fa-spin'></i>");	
        $.ajax({
            type: "GET",
            url: Routing.generate('kaikmediagallerymodule_pluginajax_mediainfo'),
            data: pars
        }).success(function(result) {	
            var template = result.template;
            $('#kmgallery_plugin_selected_box').html(template);
        }).error(function(result) {
            alert(result.status + ': ' + result.statusText);
        }).always(function() {
            $('#temp-spinner').remove();
            
        });       
    };  
    
    KaikMedia.Gallery.Plugin.addMedia = function (el)
    {   
    	
    	var relation = {'original': el.data('original'),
    					'mode': KaikMedia.Gallery.Plugin.mode
    					};
 
    	var new_el = el.parent().clone();  	
    	new_el.removeClass('col-md-2').addClass('col-md-12').appendTo('#kmgallery_plugin_'+ KaikMedia.Gallery.Plugin.mode +'_box'); 
    	    	
    	/* bind hover action   */    	
    	new_el.find('a.media-toggle').each(function() {	    
		    $(this).hover(
		    function () {
		        $(this).children('.media-icon').removeClass('fa-check-circle').addClass("fa-minus");		        
		     },
			function () {
			    $(this).children('.media-icon').removeClass('fa-minus').addClass("fa-check-circle");		        
			 }		    
		    );
		  	//$(this).children('.original').removeClass('current');
		    $(this).on('click', function(e) {
		    		  e.preventDefault();
		        	  KaikMedia.Gallery.Plugin.removeMedia($(this));
		    });		    	
    	});
    	
    	//element data 
    	$('#kmgallery_plugin_center_col').find('a.item-information-' + relation.original).each(function(){	    	    		
    		$(this).attr('data-relation', 'unsaved');
    	});	     	

    	//change mark icon accordingly 
    	$('#kmgallery_plugin').find('a.item-add-' + relation.original).each(function(){
    		$(this).addClass('hide');
    	});    	
    	
    	//change mark icon accordingly 
    	$('#kmgallery_plugin').find('a.item-toggle-' + relation.original).each(function(){
    		$(this).removeClass('hide');
    	});	    	
    	   
    }; 
    
    KaikMedia.Gallery.Plugin.removeMedia = function (el)
    {    	
    	console.log('remove media' + el);

        
    };    
    
        
    $(document).ready(function() {
        KaikMedia.Gallery.Plugin.init();
    });
    
    
})(jQuery);

manager: {
	
	obj: false,
	feature: 'info',
	feature_settings: false,
	origin: 'user',
	origin_settings: false,			
	$menu: false,
	$modal: false,
	$details_box: false,
	$origins: false,
	$selectable: [],
	$selected: [],
	$current: [],			
	
	init: function(config) {			
		
		this.obj = config.obj;
		this.$modal = config.$container.find('#kmgallery_manager');
		this.$menu = config.$container.find('#kmgallery_manager_features');
		this.feature = this.$menu.find('li.active a').data('feature');
		this.$origins = config.$container.find('#kmgallery_manager_origins');
		this.$details_box = this.$modal.find('#kmgallery_manager_details');
		this.loadSelectable();
		this.loadSelected();
		this.setOrigins();
		this.switchFeature(this.feature);
		this.bindEvents();
		//console.log(KaikMedia);
	},
	
	open: function(){ this.$modal.modal('show'); },			
	close: function(){this.$modal.modal('show'); },	
	
	bindEvents: function(){
		//var $origins_box = this.$modal.find('#kmgallery_manager_origins');				
		//var $origins = $origins_box.find('a');
		$this = this;
						
    	/* bind mode switch */
		this.$menu.find('a').each(function(){
		     $(this).on('click', function(e) {
		    	 $this.switchFeature($(this).attr('data-feature'));   		    	  
		    }); 				
		});
		
    	/* bind origin switch */
		this.$origins.find('a').each(function(){
		     $(this).on('click', function(e) {
		    	 $this.switchOrigin($(this).attr('data-origin'));   		    	  
		    }); 				
		});				
		
    	/* media preview */
		this.$modal.find('div.item-details').each(function(){
		     $(this).on('click', function(e) {
		    	 $this.details($(this));   		    	  
		    }); 				
		});	
		
    	/* media select */
		this.$modal.find('div.item-select').each(function(){
		     $(this).on('click', function(e) {				    	 
		    	 $this.select($(this));   				    	 
		    }); 				
		});	
		
    	/* bind hover action   */    	
	    this.$modal.find('a.media-unselect').each(function() {				    	
		    $(this).hover(
			    function () {
			        $(this).children('.media-icon').removeClass('fa-check-circle').addClass("fa-minus");		        
			     },
				function () {
				    $(this).children('.media-icon').removeClass('fa-minus').addClass("fa-check-circle");		        
				 }		    
		    );
		    
		     $(this).on('click', function(e) {				    	 
		    	 $this.unselect($(this));   				    	 
		    });				    				    
	    });				
		
		//console.log($origins);
	},		    				
	setOrigins: function(){				
    	/* set origins according to settings */
    	if(this.obj.settings.user.enabled){
    		//console.log('user enabled');
    		this.$origins.find('.origins').removeClass('active');
    		this.switchOrigin('user');
    		$('#kmgallery_manager_origins_user_pill').parent('.origins').addClass('active');
    		$('#kmgallery_manager_media_box').addClass('active'); 
    		
    	}else if(this.obj.settings.public.enabled){
    		//console.log('public enabled');
    		this.$origins.find('.origins').removeClass('active');
    		this.switchOrigin('public');
    		$('#kmgallery_manager_origins_user_pill').parent('.origins').addClass('active');    		
    		$('#kmgallery_manager_media_box').addClass('active');  
    		
    	}else if(this.obj.settings.upload.enabled){
    		this.$origins.find('.origins').removeClass('active');
    		this.switchOrigin('upload');		    		
    		$('#kmgallery_plugin_upload_pill').parent('.origins').addClass('active');    		
    		$('#kmgallery_plugin_upload_box').addClass('active');      			
    	}
		//console.log('');
	},
	loadSelectable: function(){
		var $selectable_box = this.$modal.find('#kmgallery_manager_media_box ul');				
		this.$selectable = $selectable_box.find('li');
		//console.log(this.$selectable);
	},
	
	loadSelected: function(){
		var $selected_box = this.$modal.find('#kmgallery_manager_selected');				
		this.$selected = $selected_box.find('li');
		//console.log(this.$selected);
	},				
	select: function(el){
		this.$modal.find('.current').removeClass('current');
		el.find('.item-image').addClass('current');
		if (this.feature == 'info')  {
			return;
		}					
		$elem = el.parent('li.media-item');			
		var maxitems = 0; // unlimited
		if(this.feature_settings.hasOwnProperty('maxitems')){
			maxitems = parseInt(this.feature_settings['maxitems']);
		};		
		
		if (maxitems == this.$current.length){
			console.log('only ' + maxitems + ' allowed');
			this.$origins.find('a.media-unselect').addClass('hide');
			//replace miniature
			
			
		
		};
		
		$elem.find('a.media-unselect').removeClass('hide');
		
		
		//console.log(this.$current);
	},
	unselect: function(el){
		//var $selectable_box = this.$modal.find('#kmgallery_manager_media_box ul');				
		//this.$selectable = $selectable_box.find('li');
		console.log(el);
	},
	details: function(el){
		var $elem = el.parent('li.media-item');				
		//this.$selectable = $selectable_box.find('li');
		var $box = this.$details_box;
		//console.log($elem);				
		
    	var pars = {
    			mode: this.feature,
    			original: $elem.attr('data-id'),
    			relation: $elem.attr('data-relation')		
    	};
    	
		$box.find('h4').append(" <i id='temp-spinner' class='fa fa-circle-o-notch fa-spin'></i>");	
        $.ajax({
            type: "GET",
            url: Routing.generate('kaikmediagallerymodule_pluginajax_mediainfo'),
            data: pars
        }).success(function(result) {	
            var template = result.template;
            $box.html(template);
        }).error(function(result) {
            alert(result.status + ': ' + result.statusText);
        }).always(function() {
            $('#temp-spinner').remove();
            
        });       

	},			
	switchOrigin: function(origin){				
		this.origin = origin;
		this.$selectable.addClass('hide');
		//this.$selectable.find('.origin-public').addClass('hide');	
		this.$selectable.filter('.origin-' + this.origin).removeClass('hide');				
		//console.log(this.$selectable);
		//console.log('origin switched ' + this.origin);
	},
	switchFeature: function(feature){				
		this.feature = feature;
    	 //console.log(this);
		this.$origins.find('.media-unselect').addClass('hide');				
		if(this.obj.settings.hasOwnProperty(feature)){
			this.feature_settings = this.obj.settings[feature];
		};
		
		this.$current = this.$selected.filter('.feature-' + this.feature);

		var $origins = this.$origins;
		this.$current.each(function(){					
			$origins.find('a.item-unselect-' + $(this).data('id')).removeClass('hide');	
		});				
		
		console.log(this.$current);
	},						
	setSaveBtn: function(data){
		//var $select_box = this.$modal.find('#kmgallery_plugin_left_col');				
		//this.selected = $select_box.find('.item');
	},			
},