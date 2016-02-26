/**
 *Gallery settings
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};


KaikMedia.Gallery.settings = (function ($) {
    
    //
    var mode = ['info'];
    
    var mediaTypes = {};
    
    var functionality = {};
    
    var modules = {};
    
    var upload = {};    
    
    var settings = {};
    
    var global = {};
    
    var obj = { name: 'KaikmediaGalleryModule' };

    function set(data)
    {
        settings = data;
       // mediaTypes = data.mediaTypes;
        global = data.settings[obj.name];
               
        if(settings.obj_name !== ''){
           this.mode = ['info','attachment', 'insert'];
           obj.name = data.obj_name;
           obj.reference = data.obj_reference;
           obj.settings = data.settings[obj.name];
        }
        
        //  
        //console.log('Gallery:init:0: module set settings');
        console.log(settings);
        console.log(global);
    };
    
    function isGalleryEnabled() { return global.enabled; };   
    function getObject() { return obj; };

    function getMediaTypes() { return mediaTypes; };
    
    function getUpload() { return upload; };    
    
    return { set:set,
             mode:mode,
             isGalleryEnabled:isGalleryEnabled,
             getObject:getObject,
             getMediaTypes:getMediaTypes,
             getUpload:getUpload
    };

})(jQuery);