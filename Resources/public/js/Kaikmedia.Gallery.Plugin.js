/**
 *Gallery plugin manager
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};

;(function ( $ ) {
KaikMedia.Gallery.Plugin = {	
			    
		config: {
			$container: false,
				   obj: {	name: false,
			        		reference: false,
			        		settings:false
			        	}
		},
			    
		init: function ($container, obj_name, obj_reference){
			    	
                this.config.$container = $container;
	    	this.config.obj.name = obj_name;
	    	this.config.obj.reference = obj_reference;
	    	this.config.obj.settings = KaikMedia.Gallery.Settings[obj_name];
	    	
	    	//init manager
	    	KaikMedia.Gallery.Manager.init(this.config);
	    	//console.log(KaikMedia)
		}		
};
})( jQuery );