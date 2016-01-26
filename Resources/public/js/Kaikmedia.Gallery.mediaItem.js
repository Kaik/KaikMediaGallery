/**
 *Gallery settings
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};
// sub namespace
KaikMedia.Gallery.model = KaikMedia.Gallery.model || {};
/*
 * Media
 * 
 */
(function ($) {

// constructor
    KaikMedia.Gallery.model.mediaItem = function () {

        var item = {
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

        var mediaView = function () {

            //this is item as jquery dom element..
            var $item = $('<div/>').addClass('mdtm');
            var $progress = $('<div/>').addClass('progress');
            var $preview = $('<div/>').addClass('preview');
            var $details = $('<div/>').addClass('details');
            var $error = $('<div/>').addClass('error');

            $item.append($progress);
            $item.append($preview);
            $item.append($details);
            $item.append($error);

            addPreview();
            addError();

            function setView(mediaItem) {
                mediaItem.view = this;
                mediaItem.$item = $item;
            }

            function addPreview() {
                if (item.mediaExtra.isImage) {
                    $preview.html(imagePreview());
                } else {
                    $preview.html(iconPreview());
                }
            }
            ;

            function imagePreview() {
                return $('<img/>')
                        .addClass('image img-responsive')
                        .attr('src', item.mediaExtra.file)
                        .attr('title', escape(item.title));
            }
            ;

            function updatePreview() {
                if (item.mediaExtra.isImage) {
                    $preview.html(imagePreview());
                } else {
                    $preview.html(iconPreview());
                }
            }
            ;

            function iconPreview() {
                var icon = typeof (item.mediaExtra.mediaType) !== 'undefined' ? item.mediaExtra.mediaType.icon : 'fa fa-file';
                return $('<i/>')
                        .addClass('icon ' + icon + ' fa-5x')
                        .attr('title', escape(item.title));

            }
            ;

            function addDetails() {

                $('<p/>')
                        .addClass('size')
                        .html(item.mediaExtra.size)
                        .appendTo($details);
                $('<p/>')
                        .addClass('type')
                        .html(item.mediaExtra.type)
                        .appendTo($details);

            }
            ;

            function displayTitle() {
                $('<p/>')
                        .addClass('title')
                        .html(item.title)
                        .appendTo($details);
            }
            ;

            function addError() {
                if (item.mediaExtra.error !== false) {
                    $('<p/>')
                            .addClass('text text-danger')
                            .html(item.mediaExtra.error)
                            .appendTo($error);
                }
                setProgressType('progress-bar-danger');
            }
            ;

            function addProgress() {
                $('<div/>')
                        .addClass('progress-bar')
                        .css('width', 0 + '%')
                        .attr('aria-valuenow', 0)
                        .attr('aria-valuemin', 0)
                        .attr('aria-valuemax', 100)
                        .appendTo($progress);
            }
            ;

            function setProgressType(type) {
                var type = typeof (type) !== 'undefined' && type !== null ? type : '';
                var $progres_bar = $progress.find('.progress-bar');
                $progres_bar.removeClass().addClass('hide').addClass('progress-bar').addClass(type).removeClass('hide');
            }
            ;


            function updateProgress(x) {
                var width = typeof (x) !== 'undefined' && x !== null ? x : 0;

                if (width === 100) {
                    // setProgressType('progress-bar-success'); 
                }

                var $progres_bar = $progress.find('.progress-bar');
                $progres_bar.css('width', width + '%')
                        .attr('aria-valuenow', width);
            }
            ;

            function removeProgress() {
                $progress.fadeOut(300, function () {
                    $(this).remove();
                });

            }
            ;

            function render() {
                return $item;
            }
            ;

            return {
                render: render,
                setView: setView,
                addProgress: addProgress,
                setProgressType: setProgressType,
                updateProgress: updateProgress,
                removeProgress: removeProgress,
                addPreview: addPreview,
                updatePreview: updatePreview,
                addError: addError,
                displayTitle: displayTitle
            };

        };

        var view = new mediaView();
        view.setView(this);

        this.isImage = function () {
            return item.mediaExtra.mimeType.match('image.*');
        };

        //this.view = view;

        this.readFile = function (f) {

            //based on actual media data;
            var reader = new FileReader();


            reader.onerror = errorHandler;
            reader.onprogress = updateProgress;
            //reader.onabort = function(e) {
            //  alert('File read cancelled');
            //};

            reader.onloadstart = function (e) {
                view.addProgress();
            };

            // Closure to capture the file information.
            reader.onload = (function (theFile) {
                return function (e) {
                    item.mediaExtra.file = e.target.result;
                    view.updateProgress(100);
                    view.updatePreview();
                    create();
                };
            })(f);

            // Read in the image file as a data URL.
            reader.readAsDataURL(f);
        };

        function errorHandler(evt) {
            switch (evt.target.error.code) {
                case evt.target.error.NOT_FOUND_ERR:
                    item.mediaExtra.error = 'File Not Found!';
                    break;
                case evt.target.error.NOT_READABLE_ERR:
                    item.mediaExtra.error = 'File is not readable';
                    break;
                case evt.target.error.ABORT_ERR:
                    break; // noop
                default:
                    item.mediaExtra.error = 'An error occurred reading this file.';
            }
            ;
        }

        function updateProgress(evt) {
            // evt is an ProgressEvent.
            if (evt.lengthComputable) {
                var percentLoaded = Math.round((evt.loaded / evt.total) * 100);
                // Increase the progress bar length.
                if (percentLoaded < 100) {
                    view.updateProgress(percentLoaded);
                }
            }
        }


        this.setDatafromUpload = function (f) {

            item.title = f.name;
            view.displayTitle();
            item.mediaExtra.fileData = f;
            item.mediaExtra.name = f.name;
            item.mediaExtra.size = f.size;
            item.mediaExtra.mimeType = f.type;
            item.mediaExtra.error = f.error;
            item.mediaExtra.mediaType = f.mediaType;
            item.mediaExtra.isUpload = true;
            item.mediaExtra.isImage = this.isImage();
            this.readFile(f);
            view.addError();
            //console.log(item);

        };

        function create() {

            if (item.mediaExtra.error) {
                view.setProgressType('progress-bar-danger');
                return false;
            }

            updateProgress(0);
            view.setProgressType('progress-bar-warning');
            console.log(item);
            var type = typeof (item.mediaExtra.mediaType) !== 'undefined' ? item.mediaExtra.mediaType.handler : 'unknown';
            var data = getMediaItemDataForm();
            //var token = $newLi.find('input[name="upload_token"]').val();
            //console.log(token);
            //data.append('images[_token]',token);  

            $.ajax({
                type: "POST",
                url: Routing.generate('kaikmediagallerymodule_media_create', { "type": type, "_format": 'json'}),
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            updateProgress(percentComplete);
                            if (percentComplete === 100) {
                                view.setProgressType('progress-bar-success');
                            }
                        }
                    }, false);

                    xhr.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            updateProgress(percentComplete);
                        }
                    }, false);
                    return xhr;
                }
            }).success(function (result) {
                console.log(result);
                //var template = result.template;
                //manager.view.itemEdit(template);
                view.setProgressType('progress-bar-success');
                view.removeProgress();
            }).error(function (result) {
                console.log(result);
                view.setProgressType('progress-bar-danger');
                //manager.view.displayError(result.status + ': ' + result.statusText);
            }).always(function () {
                //console.log('always');
                //manager.view.hideBusy();           
            });




        }
        ;
        
        function getMediaItemDataForm() {
            var type = typeof (item.mediaExtra.mediaType) !== 'undefined' ? item.mediaExtra.mediaType.handler : 'unknown';
            var data = new FormData();
            data.append('media_'+type+'[title]', item.title);
            data.append('media_'+type+'[description]', item.description !== '' ? item.description : item.title );
            data.append('media_'+type+'[urltitle]', item.urltitle);
            data.append('media_'+type+'[legal]', item.legal !== '' ? item.legal : 'unknow');
            data.append('media_'+type+'[publicdomain]', item.publicdomain);
            data.append('media_'+type+'[mediaExtra]', item.mediaExtra.type);
            if(item.mediaExtra.isUpload){
                data.append('media_'+type+'[file]', dataURItoBlob());
            }
            return data;
        }
        
        function dataURItoBlob() {
            // convert base64 to raw binary data held in a string
            // doesn't handle URLEncoded DataURIs

            var byteString = window.atob(item.mediaExtra.file.split(',')[1]);
            // separate out the mime component

            //var ab = new ArrayBuffer(byteString.length);
            var ia = new Uint8Array(byteString.length);
            for (var i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }

            // write the ArrayBuffer to a blob, and you're done
            var blob = new Blob([ia], {type: item.mediaExtra.mimeType});

            return blob;
        }

    };

}(jQuery));

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