/**
 *Gallery settings
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};
KaikMedia.Gallery.mediaItem = {
    id: false,
    title: '',
    urltitle: '',
    description: '',
    publicdomain: false,
    legal: '',
    mediaExtra: {
        file: false,
        size: 0,
        ext: '',
        mimeType: '',
        relation: 'new'
    },
    author: false,
    views: 0,
    
    online: 1,
    depot: 0,
    inmenu: 1,
    inlist:1,
    language: '',
    layout: '',
    
    publishedAt: false,
    expiredAt: false,
    
    status: 'A',
    createdAt: false,
    createdBy: false,
    updatedAt: false,
    updatedBy: false,
    deletedAt: false,
    deletedBy: false    
};

/*
 * mediaItem 
 * 
 */
(function (item, $, undefined) {

    
    item.set = function ()
    {
        //KaikMedia.Gallery.settings.data = $settings;
        //console.log('Gallery:init:0: module set settings');
        //console.log(KaikMedia.Gallery.settings.data);
    };

    item.get = function ()
    {
        return item;
    };

    item.select = function (item) {
        item.view.select(item);
    };

    item.UnSelect = function (item) {
        /* add remove item from selected */

        item.view.UnSelect(item);
    };

    item.GetDetails = function (item) {
        /* change item data acording to view need's like size in kb */

        item.view.itemDetails(item);
    };

    item.edit = function (item) {
        /* get ajax form 

        var pars = {
            mode: manager.current.feature.name,
            original: item.id,
            relation: item.relation
        };

        //manager.view.showBusy();
        
        $.ajax({
            type: "GET",
            url: Routing.generate('kaikmediagallerymodule_manager_edit'),
            data: pars
        }).success(function (result) {
            var template = result.template;
            manager.view.itemEdit(template);
        }).error(function (result) {
            manager.view.displayError(result.status + ': ' + result.statusText);
        }).always(function () {
            //manager.view.hideBusy();           
        });
        */
    };
    
    item.readFromFile = function (f) {
        var reader = new FileReader();
        // Closure to capture the file information.
        reader.onload = (function (theFile) {
                return function (e) {
                    item.view.displaySelectedFile(theFile, e.target.result);
                };
            })(f);
            
        // Read in the image file as a data URL.
        reader.readAsDataURL(file);       
    };

    /*
     * manager file
     * 
     */
    item.upload = function (file) {

        /*
        if (file.type.match('image.*')) {

        } else {
            item.view.displaySelectedFile(file, false);
        }
        */
    };

    item.clone = function () {
        return {id: false,
            name: '',
            src: '',
            publicdomain: false,
            file: false,
            description: '',
            size: 0,
            ext: '',
            mimeType: '',
            legal: '',
            relation: 0
        };
    };


    /*
     * manager.view
     * 
     */
    var view = (function () {

        // Instance stores a reference to the Singleton
        var instance;

        

        function Init($item) {   
            
            
            $item = 
            
            
            /*
             * manager.view public
             * 
             */
 
 
 
 
 
            function getItemDataFromElement($el) {

                var item = {};

                item.id = $el.attr('data-id');
                item.name = $el.attr('aria-label');
                item.description = $el.attr('data-description');
                item.publicdomain = $el.attr('data-public');
                item.ext = $el.attr('data-ext');
                item.mimeType = $el.attr('data-mimetype');
                item.author = $el.attr('data-author');
                item.size = $el.attr('data-size');
                item.legal = $el.attr('data-legal');

                item.relation = $el.attr('data-relation');
                item.type = $el.attr('data-type');

                item.src = $el.find('img').attr('src');
                //console.log(item, $el);
                return item;
            }            
            
             /*
             //Item template
             function itemTemplate( ) {
             
             var $item = $("<div>", {class: "item" });
             
             return $item
             }       
             */
            //Item
            function itemSelect(item) {
                $modal.find('.item-' + item.id).each(function () {
                    $(this).find('a.media-unselect').removeClass('hide');
                    $(this).attr('data-relation', item.relation);
                });
            }
            //
            function itemUnSelect(item) {
                $modal.find('.item-' + item.id).each(function () {
                    $(this).find('a.media-unselect').addClass('hide');
                    $(this).attr('data-relation', 'new');
                });
            }           
            
            function displaySelectedFile(theFile, src) {
                // Render thumbnail.
                //console.log(theFile);
                var li = $('<li/>')
                        .addClass('media-preview col-md-2 ')
                        .appendTo($upload_preview);
                if (src !== false) {
                    $('<img/>')
                            .addClass('media-preview-file thumbnail img-responsive')
                            .attr('src', src)
                            .attr('title', escape(theFile.name))
                            .appendTo(li);
                } else {
                    $('<i/>')
                            .addClass(theFile.mediaType.icon + ' fa-5x')
                            .attr('title', escape(theFile.name))
                            .appendTo(li);
                }

                var details = $('<div/>')
                        .addClass('media-preview-details')
                        .appendTo(li);
                
                $('<p/>')
                        .addClass('name file-name')
                        .html(theFile.name)
                        .appendTo(details);                
                  
                if (theFile.error !== false) {                
                $('<p/>')
                        .addClass('error text-danger')
                        .html(theFile.error)
                        .appendTo(details);
                }
                /*
                $('<p/>')
                        .addClass('size')
                        .html(theFile.size)
                        .appendTo(details);
                $('<p/>')
                        .addClass('type')
                        .html(theFile.type)
                        .appendTo(details);
                */
            }

            //
            function itemDetails(item) {
                var $item = $("<div>", {class: "item-details"});
                $item.html('<img class="img-responsive" src="' + item.src + '">');
                $details_box.find('.item-details-title-box').removeClass('hide');
                $details_box.find('.item-details-preview-box').html($item).removeClass('hide');

                $details = $details_box.find('.item-details-box');
                $details.find('.name').html(item.name);
                $details.find('.size').html(item.size);
                $details.find('.mimetype').html(item.mimeType);
                $details.find('.ext').html(item.ext);
                $details.find('.author').html(item.author);
                $details.removeClass('hide');
                //console.log($item);

                $details_box.find('.item-details-info-box').addClass('hide');
            }

            //
            function itemEdit(form) {
                $details_box.find('.item-details-edit-box').html(form).removeClass('hide');
                removeOverlay();
                //bind saveItem form action       	
            }            
            
            
            
            
            
            
            return {
                //getDataFromView: getDataFromView,
                //select: itemSelect,
                //unSelect: itemUnSelect,
                //Details: itemDetails,
                //Edit: Edit,
                //displayError: displayError
            };
        }
        ;

        return {
            // Get the Singleton instance if one exists
            // or create one if it doesn't
            getInstance: function ($modal) {

                if (!instance) {
                    instance = Init($modal);
                }

                return instance;
            }

        };

    })();


})(KaikMedia.Gallery.mediaItem, jQuery);