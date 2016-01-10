/**
 *Gallery settings
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};
KaikMedia.Gallery.settings = {};
KaikMedia.Gallery.settings.data = KaikMedia.Gallery.settings.data || {};

(function ($) {

    KaikMedia.Gallery.settings.set = function ($settings)
    {
        KaikMedia.Gallery.settings.data = $settings;
        console.log('Gallery:init:0: module set settings');
        console.log(KaikMedia.Gallery.settings.data);
    };

    KaikMedia.Gallery.settings.get = function (module)
    {
        return KaikMedia.Gallery.settings.data[module];
    };

})(jQuery);