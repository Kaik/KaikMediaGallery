/**
 *Gallery settings
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};
// sub namespace
KaikMedia.Gallery.handler = KaikMedia.Gallery.handler || {};
/*
 * Media
 */
(function ($) {

     KaikMedia.Gallery.handler.youtube = (function () {
        var settings = {};
        
	 // Init
        function init()
        {
            console.log('Gallery handler youtube 1.0');
        }
        ;
        function setSettings(data)
        {
            console.log('Gallery handler youtube settings recived.');
            settings = data;
            console.log(settings); 
        }
        ;
        
        function add() {
            var deferred = $.Deferred();
                textDialog({
                    parent:settings.dialog,
                    placeholder_text: Translator.__('Enter YouTube url'), 
                    button_text: Translator.__('Search'), 
                }).then(text => {
                    deferred.resolve(handleText(text))
                })
            
	    return deferred.promise();
        }
        ;
 
        function handleText(text) {
            // Return promise/callvack
            var promises = [];
            var deferred = $.Deferred();
            var mediaItem = new KaikMedia.Gallery.model.mediaItem();
            mediaItem.setIdPrefix(settings.idPrefix);
            mediaItem.setFormNamePrefix(settings.formNamePrefix);
            var ykey = getYoutubeKey(text);
            
            if (!ykey) {
                return deferred.rejected(mediaItem);
            }
            
            mediaItem.setMediaItemData(mediaItemData({ykey: ykey}))
            
            getYoutubePreview(ykey)
                .done(preview => {
                    mediaItem.view.setPreview(preview);
                    deferred.resolve(mediaItem);
                });

            promises.push(deferred.promise());
            
	    return promises;
        }
        ;        
        
        function mediaItemData(data) 
        {
//            var deferred = $.Deferred();
            var item = {
                mediaExtra: {}
            };
//            f.error !== 'undefined' ? f.error :
//            console.log(settings);
            item.title = 'yt filmik';
            item.mediaExtra.name = data.ykey;
//            item.mediaExtra.size = f.size;
//            item.mediaExtra.mimeType = f.type;
            item.mediaExtra.error = false ;
//            item.mediaExtra.mediaType = f.mediaType;
            item.mediaExtra.isFile = false;
            item.mediaExtra.handler = 'youtube';
            item.mediaExtra.handler_name = 'youtubeHandler';
//            item.mediaExtra.dir = settings.dir;
//            item.mediaExtra.prefix = settings.prefix;
//            deferred.resolve(item);

	    return item;
        };
        
        function getYoutubeKey(text){
            var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
            var match = text.match(regExp);
            return (match&&match[7].length==11)? match[7] : false;
	}
	;
        
	function getYoutubeData(ykey) {
//            $.getJSON('http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=ojCkgU5XGdg&format=json',function(data,status,xhr){
//                console.log(data.data);
//                // data contains the JSON-Object below
//                return data.data;
//            });
	}
	;        
        
        // https://www.w3schools.in/load-youtube-video-dynamically/
        function getYoutubePreview(ykey) {
            var deferred = $.Deferred();

            const $preview = document.createElement('div');
            $preview.className = 'youtube-preview vplayer';
            const $button = document.createElement('div');
            $button.className = 'plybtn';

            var source = "https://img.youtube.com/vi/"+ ykey +"/sddefault.jpg";
            var image = new Image();
            image.src = source;

            image.addEventListener("load", function() {
                $preview.appendChild(image);
            }());

            $preview.appendChild($button);
            $preview.addEventListener( "click", function() {
                        this.innerHTML = "";
                        this.appendChild(getYoutubeIframe(ykey));
            } );    
            deferred.resolve($preview);        

            return deferred.promise();        
        }
        ;
        
	function getYoutubeIframe(ykey) {
            
            var iframe = document.createElement( "iframe" );

            iframe.setAttribute( "allowfullscreen", "" );
            iframe.setAttribute( "frameborder", "0" );
            iframe.setAttribute( "src", "https://www.youtube.com/embed/"+ ykey +"?rel=0&showinfo=0&autoplay=1" );
            
            return iframe;
	}
	;
        
        //return this and init when ready
        return {
            init: init,
            setSettings:setSettings,
            add:add
        };
    })();
    $(function () {
        KaikMedia.Gallery.handler.youtube.init();
    });
}(jQuery));




//        function addMedia(f) {
//            mediaForm = createMediaForm();
//            mediaForm.append('file', f);
//            showProgress();
//            doAjax(Routing.generate('kaikmediagallerymodule_media_create', {"type": settings.type, "_format": 'json'}), mediaForm)
//                .done(postMediaAdd)
//                .fail(displayError) 
//                .progress(updateProgress)
//        }
//        ;
//
//        function postMediaAdd(data) {
//            setMediaData(data);
//            showRemoveButton();
//            showDetailsButton();
//            setProgressType('progress-bar-success');
//            removeProgress();
//        }
//        ;
//	// View
//	function showRelationData() {
//	    settings.$container.find(".relation-data").addClass('in');	    
//	}
//	;
//	
//	function hideRelationData() {
//	    settings.$container.find(".relation-data").removeClass('in');    
//	}
//	;
//	
//	function clearRelationData() {
//	    settings.$container.find("input[name*='relationExtra'][type='text']").val('');	    
//	}
//	;
//	function clearDialog() {
//	    settings.$dialog.html('')	    
//	}
//	;
//	function clearPreview() {
//	    settings.$preview.html('');	    
//	}
//	;
//        
//	function displayDialog($html) {
//	    settings.$dialog.html($html);
//	}
//	;        
//        
//	function displayPreview(html) {
//	    settings.$preview.html(html);
//	    settings.$preview.removeClass('hide');
//	}
//	;
//	
//	function displayError(error) {
//	    settings.$container.find('.error-message').html(error).removeClass('hide');
//	}
//	;
//	
//	function hideError() {
//	    settings.$container.find('.error-message').addClass('hide');
//	}
//	;
//	
//	function showProgress() {
//	    settings.$container.find('.progress').removeClass('hide');
//	}
//	;
//	
//        function setProgressType(type) {
//                var type = typeof (type) !== 'undefined' && type !== null ? type : '';
//                var $progres_bar = settings.$container.find('.progress-bar');
//                $progres_bar.removeClass().addClass('hide').addClass('progress-bar').addClass(type).removeClass('hide');
//            }
//        ;
//	
//	function updateProgress(x) {
//	    var width = typeof (x) !== 'undefined' && x !== null ? x : 0;
//	    var $progres_bar = settings.$container.find('.progress-bar');
//	    $progres_bar.css('width', width + '%')
//		    .attr('aria-valuenow', width);
//	}
//	;
//	
//	function removeProgress() {
//	    settings.$container.find('.progress').fadeOut(300, function () {
//		$(this).addClass('hide');
//	    });
//	}
//	;
//	
//	function hideProgress() {
//	    settings.$container.find('.progress').addClass('hide');
//	}
//	;

/*

 KaikMedia.Gallery.Media = (function($) {

 this.item = {
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
 inlist: 1,
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

 this.item.view = (function () {

 //this is item as jquery dom element..
 var $item = $('<div/>').addClass('media-item col-md-2 ');

 function addPreview() {

 var $preview = $('<div/>')
 .addClass('media-preview')
 .appendTo($item);

 if (item.isImage !== false) {
 $preview.append(imagePreview());
 } else if (item.mediaExtra.mediaType.icon !== false) {
 $preview.append(iconPreview());
 }
 }
 ;

 function imagePreview() {
 return $('<img/>')
 .addClass('media-preview-file thumbnail img-responsive')
 .attr('src', item.mediaExtra.file)
 .attr('title', escape(item.title));
 }
 ;

 function imagePreviewUpdate() {
 $item.find('.media-preview-file').attr('src', item.mediaExtra.file);
 }
 ;

 function iconPreview() {
 return $('<i/>')
 .addClass('media-preview-icon ' + item.mediaExtra.mediaType.icon + ' fa-5x')
 .attr('title', escape(item.title));

 }
 ;

 function addDetails() {

 var details = $('<div/>')
 .addClass('media-preview-details')
 .appendTo($item);

 $('<p/>')
 .addClass('name file-name')
 .html(item.title)
 .appendTo(details);

 if (item.mediaExtra.error !== false) {
 $('<p/>')
 .addClass('error text-danger')
 .html(item.mediaExtra.error)
 .appendTo(details);
 }

 $('<p/>')
 .addClass('size')
 .html(item.mediaExtra.size)
 .appendTo(details);
 $('<p/>')
 .addClass('type')
 .html(item.mediaExtra.type)
 .appendTo(details);

 }
 ;

 function render() {
 addPreview();
 addDetails();
 return $item;
 }
 ;

 return {
 render: render,
 imagePreviewUpdate: imagePreviewUpdate
 };
 })();

 return {
 Item: function(data) {
 this.data = data;
 return this;
 }
 };
 }(jQuery));

 /*
 namespace = (function() {
 function a() {};
 function b(name) {
 this.name = name;
 };
 b.prototype.hi = function() {
 console.log("my name is " + this.name);
 }
 return {
 a: a,
 b: b
 }
 })();

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
 inlist: 1,
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
 add remove item from selected

 item.view.UnSelect(item);
 };

 item.GetDetails = function (item) {
 change item data acording to view need's like size in kb

 item.view.itemDetails(item);
 };

 item.isImage = function () {
 return item.type.match('image.*');
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

 };

 item.setDatafromUpload = function (f) {

 item.title = f.name;
 item.mediaExtra.name = f.name;
 item.mediaExtra.size = f.size;
 item.mediaExtra.mimeType = f.type;
 item.mediaExtra.error = f.error;
 item.mediaExtra.mediaType = f.mediaType;
 item.readFile(f);
 console.log(item);

 };


 item.readFile = function (f) {

 //based on actual media data;
 var reader = new FileReader();
 // Closure to capture the file information.
 reader.onload = (function (theFile) {
 return function (e) {
 item.mediaExtra.file = e.target.result;
 item.view.imagePreviewUpdate();
 };
 })(f);



 // Read in the image file as a data URL.
 reader.readAsDataURL(f);
 };

 item.upload = function () {

 //return item;
 };

 /*
 * manager.view
 *

 item.view = (function () {

 //this is item as jquery dom element..
 var $item = $('<div/>').addClass('media-item col-md-2 ');

 function addPreview() {

 var $preview = $('<div/>')
 .addClass('media-preview')
 .appendTo($item);

 if (item.isImage !== false) {
 $preview.append(imagePreview());
 } else if (item.mediaExtra.mediaType.icon !== false) {
 $preview.append(iconPreview());
 }
 }
 ;

 function imagePreview() {
 return $('<img/>')
 .addClass('media-preview-file thumbnail img-responsive')
 .attr('src', item.mediaExtra.file)
 .attr('title', escape(item.title));
 }
 ;

 function imagePreviewUpdate() {
 $item.find('.media-preview-file').attr('src', item.mediaExtra.file);
 }
 ;

 function iconPreview() {
 return $('<i/>')
 .addClass('media-preview-icon ' + item.mediaExtra.mediaType.icon + ' fa-5x')
 .attr('title', escape(item.title));

 }
 ;

 function addDetails() {

 var details = $('<div/>')
 .addClass('media-preview-details')
 .appendTo($item);

 $('<p/>')
 .addClass('name file-name')
 .html(item.title)
 .appendTo(details);

 if (item.mediaExtra.error !== false) {
 $('<p/>')
 .addClass('error text-danger')
 .html(item.mediaExtra.error)
 .appendTo(details);
 }

 $('<p/>')
 .addClass('size')
 .html(item.mediaExtra.size)
 .appendTo(details);
 $('<p/>')
 .addClass('type')
 .html(item.mediaExtra.type)
 .appendTo(details);

 }
 ;

 function render() {
 addPreview();
 addDetails();
 return $item;
 }
 ;

 return {
 render: render,
 imagePreviewUpdate: imagePreviewUpdate
 };
 })();





 function getItem() {
 return $item;
 }


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


 //Item template
 function itemTemplate( ) {

 var $item = $("<div>", {class: "item" });

 return $item
 }

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



 })(KaikMedia.Gallery.mediaItem, jQuery);
 */