/**
 *Gallery settings
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};


KaikMedia.Gallery.settings = (function ($) {
    
    //
    var mode = 'info';
    
    var mediaTypes = {};
    
    var functionality = {};
    
    var modules = {};
    
    var upload = {};    
    
    var settings = {};
    
    var obj = { name: 'KaikmediaGalleryModule' };

    function set(data)
    {
        settings = data;
        mediaTypes = data.mediaTypes;
        
        //if(data.obj_name !== ''){
           this.mode = 'info attach insert';
          // obj.name = data.obj_name;
          // obj.reference = data.obj_reference;
          // obj.settings = data.settings[obj.name];
       // }
        
        //KaikMedia.Gallery.Manager.init();  
        //console.log('Gallery:init:0: module set settings');
        //console.log(mode);
    };
    
    
    function getObject() { return obj; };

    function getMediaTypes() { return mediaTypes; };
    
    function getUpload() { return upload; };    
    
    return { set:set,
             mode:mode,
             getObject:getObject,
             getMediaTypes:getMediaTypes,
             getUpload:getUpload
    };

})(jQuery);