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
     KaikMedia.Gallery.handler.image = (function () 
     {
        var settings = {};
        
	 // Init
        function init()
        {
            console.log('Gallery handler image 1.0');
        }
        ;

	 // Init
        function setSettings(data)
        {
            console.log('Gallery handler image settings recived.');
            settings = data;
        }
        ;

        function add() {
            var deferred = $.Deferred();
                fileDialog({
                    multiple: settings.multiple, 
                    accept: settings.accept 
                }).then(files => {
                    deferred.resolve(handleFiles(files))
                })
	    return deferred.promise();
        }
        ;
        
        // Files
        function handleFiles(files) {
            var promises = [];
            var fileslenght = files.length;
            for (var i = 0; i < fileslenght; i++) {
                promises.push(handleFile(files[i]));
            }
            
	    return promises;
        }
        ;

        function handleFile(file) {
            var deferred = $.Deferred();
            var mediaItem = new KaikMedia.Gallery.model.mediaItem();
            
            mediaItem.setIdPrefix(settings.idPrefix);
            mediaItem.setFormNamePrefix(settings.formNamePrefix);
            
            checkFile(file)
            .done(file => {
                processMediaItem(mediaItem, file)
                .done(processedMediaItem => {
                    deferred.resolve(processedMediaItem);
                })
                ;
            })
            .fail(error => {
                mediaItem.addError(error)
                deferred.reject(mediaItem);
            }) 
            ;
            
	    return deferred.promise();
        }
        ;

        function processMediaItem(mediaItem, file) 
        {       
            var deferred = $.Deferred();
            mediaItemDataFromUpload(file)
                .done(item => { 
                    mediaItem.setMediaItemData(item); 
                    mediaItem.view.setPreview(getImagePreview(item.mediaExtra.fileDataUrl));
                    deferred.resolve(mediaItem);
                })
                .fail(error => { 
                    mediaItem.addError(error) 
                    deferred.resolve(mediaItem);
                })
            ;
            
            return deferred.promise();
        }
        ;
        
        function mediaItemDataFromUpload(f) 
        {
            var deferred = $.Deferred();
            var item = {
                mediaExtra: {}
            };
            
//            f.error !== 'undefined' ? f.error :
            item.title = f.name;
            item.mediaExtra.file = f;
            item.mediaExtra.name = f.name;
            item.mediaExtra.size = f.size;
            item.mediaExtra.mimeType = f.type;
            item.mediaExtra.error = false;
            item.mediaExtra.mediaType = f.mediaType;
            item.mediaExtra.isFile = true;
            item.mediaExtra.handler = 'image';
            item.mediaExtra.handler_name = 'imageHandler';
            item.mediaExtra.dir = settings.dir;
            item.mediaExtra.prefix = settings.prefix;

            if (item.mediaExtra.isFile) {
                var reader = new FileReader();
                // Closure to capture the file information.
                reader.onload = (function (theFile) {
                    return function (e) {
                        item.mediaExtra.fileDataUrl = e.target.result;
                        deferred.resolve(item);
                    };
                })(f);
                
                if (typeof (f) !== 'undefined') {
                    // Read in the image file as a data URL.
                    reader.readAsDataURL(f);
                }
                
//            reader.onabort
//            reader.onerror = errorHandler;
//            reader.onprogress = updateProgress;
//            reader.onloadstart = //add progress

            } else {
                deferred.resolve(item);
            }

	    return deferred.promise();
        };

	function checkFile(f) {
	    var deferred = $.Deferred();
	    if (f.size > settings.uploadLimit * 1000000) {
		deferred.reject(Translator.__('Image size is bigger than ' + settings.uploadLimit + 'MB'));
	    } else {
		deferred.resolve(f);
	    }

	    return deferred.promise();
	}
	;
	
	function getImagePreview(src) {
            var $html = $("<img>", {"class": "positioning img-responsive", "src": src});
            return $html;
	}
	;
        
	function getActions() {
            const $handler_actions = document.createElement('div');
            $handler_actions.className = 'handler-actions form-group has-float-label';
            
            $handler_actions.append(getPositioningButton());
//                mediaPositioner({previewContainer: mediaItem.view.getPreviewContainer()});
//                console.log(mediaPositioner.progress());       
            
            return $handler_actions;
	}
	;
	
        function getPositioningButton() {
            const $actionButton = document.createElement('button');
            $actionButton.className = 'btn btn-link';
            $actionButton.setAttribute('type', 'button');
            
            const $actionButton_icon = document.createElement('i');
            $actionButton_icon.className = 'fa fa-arrows fa-fw';
            $actionButton.appendChild($actionButton_icon);

            return $actionButton;
        };
        
        function getPositionForm() {
            const $display_group = document.createElement('div');
            $display_group.className = 'form-group has-float-label';

//                Display select
            const $display_group_align_select = document.createElement('input');
            $display_group_align_select.className = 'form-control';
            $display_group_align_select.setAttribute('id', idPrefix + '_display_select');
            $display_group_align_select.setAttribute('name', formNamePrefix + '[relationExtra][display]');
            const $display_group_align_select_top = document.createElement('option');
            $display_group_align_select_top.value = 'top'; 
            $display_group_align_select_top.innerHTML = Translator.__('Top');
            const $display_group_align_select_center = document.createElement('option');
            $display_group_align_select_center.value = 'center'; 
            $display_group_align_select_center.innerHTML = Translator.__('Center');                
            const $display_group_align_select_bottom = document.createElement('option');
            $display_group_align_select_bottom.value = 'bottom'; 
            $display_group_align_select_bottom.innerHTML = Translator.__('Bottom');                
            $display_group_align_select.append($display_group_align_select_top);
            $display_group_align_select.append($display_group_align_select_center);
            $display_group_align_select.append($display_group_align_select_bottom);
            const $display_group_align_select_label = document.createElement('label');
            $display_group_align_select_label.setAttribute('for', idPrefix + '_display_select');
            $display_group_align_select_label.innerHTML = Translator.__('Media position');

            // add display select
            $display_group.appendChild($display_group_align_select);
            $display_group.appendChild($display_group_align_select_label);

            return $display_group;
        };
        
        const mediaPositioner = (...args) => {
             var deferred = $.Deferred();
             var $previewContainer;
             var $dragImage;

             // Set config
             if(typeof args[0] === 'object') {
                 if(args[0].previewContainer !== undefined) {
                    $previewContainer = args[0].previewContainer;
                    $dragImage = $previewContainer.find('.positioning');
                 } else {
                    $deffered.rejected('worng arguments used');
                 }
             }
             var _DRAGGGING_STARTED = 0;
             var _LAST_MOUSEMOVE_POSITION = { x: null, y: null };
             var _DIV_OFFSET = $previewContainer.offset();
             var _CONTAINER_WIDTH = $previewContainer.outerWidth();
             var _CONTAINER_HEIGHT = $previewContainer.outerHeight();
             var _IMAGE_WIDTH;
             var _IMAGE_HEIGHT;
             var _IMAGE_LOADED = 0;
             
             // Check whether image is cached or wait for the image to load 
             // This is necessary before calculating width and height of the image
//             console.log(this);
             if($dragImage.get(0).complete) {
                     ImageLoaded();
             }
             else {
                     $dragImage.on('load', function() {
                             ImageLoaded();
                     });
             }

             // Image is loaded
             function ImageLoaded() {
                     _IMAGE_WIDTH = $dragImage.width();
                     _IMAGE_HEIGHT = $dragImage.height();
                     _IMAGE_LOADED = 1;	
//                     console.log(_IMAGE_WIDTH);
//                      console.log($dragImage);

             }

             $previewContainer.on('mousedown', function(event) {
                 
//                if(_DRAGGGING_STARTED == 1) {
//                    _DRAGGGING_STARTED == 0;
//                } else {
                    //start dragging
                     /* Image should be loaded before it can be dragged */
                     if(_IMAGE_LOADED == 1) { 
                             _DRAGGGING_STARTED = 1;

                             /* Save mouse position */
                             _LAST_MOUSE_POSITION = { x: event.pageX - _DIV_OFFSET.left, y: event.pageY - _DIV_OFFSET.top };
//                              console.log(_LAST_MOUSE_POSITION);
//                      console.log('mousedown', $dragImage);
                     }   
//                }
             });

             $previewContainer.on('mouseup', function() {
                 
                     _DRAGGGING_STARTED = 0;
                     
                   console.log('mouseup', $dragImage);

             });

             $previewContainer.on('mousemove', function(event) {
                 
                     if(_DRAGGGING_STARTED == 1) {
                             var current_mouse_position = { x: event.pageX - _DIV_OFFSET.left, y: event.pageY - _DIV_OFFSET.top };
                             var change_x = current_mouse_position.x - _LAST_MOUSE_POSITION.x;
                             var change_y = current_mouse_position.y - _LAST_MOUSE_POSITION.y;

                             /* Save mouse position */
                             _LAST_MOUSE_POSITION = current_mouse_position;

                             var img_top = parseInt($dragImage.css('top'), 10);
                             var img_left = parseInt($dragImage.css('left'), 10);

                             var img_top_new = img_top + change_y;
                             var img_left_new = img_left + change_x;

                             /* Validate top and left do not fall outside the image, otherwise white space will be seen */
//                             if(img_top_new > 0)
//                                     img_top_new = 0;
//                             if(img_top_new < (_CONTAINER_HEIGHT - _IMAGE_HEIGHT))
//                                     img_top_new = _CONTAINER_HEIGHT - _IMAGE_HEIGHT;
//
//                             if(img_left_new > 0)
//                                     img_left_new = 0;
//                             if(img_left_new < (_CONTAINER_WIDTH - _IMAGE_WIDTH))
//                                     img_left_new = _CONTAINER_WIDTH - _IMAGE_WIDTH;

                            $dragImage.css({ top: img_top_new + 'px', left: img_left_new + 'px' });
//                            console.log($dragImage);
                     }
                     
             });

             return deferred.promise();

         };
        
        //return this and init when ready
        return {
            init: init,
            setSettings:setSettings,
            add:add,
            getActions:getActions
        };
    })();
    $(function () {
        KaikMedia.Gallery.handler.image.init();
    });
}(jQuery));



/*
                
                
//                reader.done(file => {
////                    console.log()
//
//                });
//                var reader = fileReader({
//                    fileData: item.mediaExtra.fileData
//                });
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

                if (item.mediaExtra.file === 'undefined') {

                }

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

        function readFile() {


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
                    if (!item.id) {
                        create();
                    } else {
                        view.setProgressType('progress-bar-success');
                        view.removeProgress();
                    }

                };
            })(item.mediaExtra.fileData);

            if (typeof (item.mediaExtra.fileData) !== 'undefined') {
                // Read in the image file as a data URL.
                reader.readAsDataURL(item.mediaExtra.fileData);

            } else {
                var success = function (result) {
                    item.mediaExtra.fileData = result;
                    reader.readAsDataURL(item.mediaExtra.fileData);
                };
                var url = Routing.getBaseUrl().replace(/\w+\.php$/gi, '') + '/' + item.path + '/' + item.mediaExtra.path;

                var xhr = new XMLHttpRequest();
                xhr.open('GET', url, true);
                xhr.responseType = "blob";
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (success)
                            success(xhr.response);
                    }
                };
                xhr.send(null);
            }
        }
        ;

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
            readFile();
            view.addError();
        };

        this.setMediaItemData = function (itemData) {
            $.extend(item, itemData);
            view.displayTitle();
            readFile();
            view.addError();
            //console.log(itemData);
            //item.mediaExtra.isImage = this.isImage();
            return item;
        };

        function create() {
            //console.log('create');
            if (item.mediaExtra.error) {
                view.setProgressType('progress-bar-danger');
                return false;
            }

            updateProgress(0);
            view.setProgressType('progress-bar-warning');
            //console.log('create');
            var type = typeof (item.mediaExtra.mediaType) !== 'undefined' ? item.mediaExtra.mediaType.handler : 'unknown';
            var data = getMediaItemDataForm();
            //var token = $newLi.find('input[name="upload_token"]').val();
            //console.log(token);
            //data.append('images[_token]',token);

            $.ajax({
                type: "POST",
                url: Routing.generate('kaikmediagallerymodule_media_create', {"type": type, "_format": 'json'}),
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
                //console.log(result);
                //var template = result.template;
                //manager.view.itemEdit(template);
                view.setProgressType('progress-bar-success');
                view.removeProgress();
            }).error(function (result) {
                //console.log(result);
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
            data.append('media_' + type + '[title]', item.title);
            data.append('media_' + type + '[description]', item.description !== '' ? item.description : item.title);
            data.append('media_' + type + '[urltitle]', item.urltitle);
            data.append('media_' + type + '[legal]', item.legal !== '' ? item.legal : 'unknow');
            data.append('media_' + type + '[publicdomain]', item.publicdomain);
            data.append('media_' + type + '[mediaExtra]', JSON.stringify(getMediaExtraData()));
            if (item.mediaExtra.isUpload) {
                data.append('media_' + type + '[file]', dataURItoBlob());
            }
            return data;
        }


        function getMediaExtraData() {
            var data = $.extend({}, item.mediaExtra);
            delete data.file;
            delete data.fileData;
            return data;
        }

        function dataURItoBlob() {
            // convert base64 to raw binary data held in a string             // doesn't handle URLEncoded DataURIs

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