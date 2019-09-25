/**
 *Gallery settings
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};


KaikMedia.Gallery.settings = (function ($) {
    var settings = {};
    var global = {};

    function set(data)
    {
       console.log(data);
        if ($.isEmptyObject(data)){
            global.enabled = 0;           
        } else {     
        settings = data;
        }
    };
    
    function isGalleryEnabled() { return global.enabled; };   
    
    return { set:set,
             isGalleryEnabled:isGalleryEnabled
    };

})(jQuery);
    
    

//    //
//    var mode = ['info'];
//    
//    var mediaTypes = {};
//    
//    var functionality = {};
//    
//    var modules = {};
//    
//    var upload = {};    
//        global = data.settings[obj.name];
//               
//        if(settings.obj_name !== ''){
//                this.mode = ['info','attachment', 'insert'];
//                obj.name = data.obj_name;
//                obj.settings = data.settings[obj.name];
//                obj.reference = data.obj_reference;
//            }            
//        }
//             mode:mode,
//             getObject:getObject,
//             getMediaTypes:getMediaTypes,
//             getUpload:getUpload
//    function getObject() { return obj; };
//
//    function getMediaTypes() { return mediaTypes; };
//    
//    function getUpload() { return upload; };
        //console.log('Gallery:init:0: module set settings');
        //console.log(settings);
        //console.log(global);