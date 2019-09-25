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
    KaikMedia.Gallery.model.mediaRelationItem = function () {
        
        var formNamePrefix;
        var idPrefix;
        
        var item = {
            id: false,
            media: null,
            feature: null,
            // feature presets
            featureExtra: [],
            // special data for this relation
            relationExtra: [],
            // media item mediaExtra override
            // title legal 
            mediaExtra: {
//                file: false,
//                size: 0,
//                ext: '',
//                mimeType: '',
//                relation: 'new',
//                error: false
            },
            hookedModule: null,
            hookedAreaId: null,
            hookedObjectId: null,
            hookedUrlObject:null,
            
            status: 'A',
            publishedAt: false,
            expiredAt: false,
            createdAt: false,
            createdBy: false,
            updatedAt: false,
            updatedBy: false,
            deletedAt: false,
            deletedBy: false
        };

        this.setIdPrefix = function (prefix) {
            idPrefix = prefix;
        };
        
        this.setFormNamePrefix = function (prefix) {
            formNamePrefix = prefix;
        }; 
        
        var relationView = function () {
            var $item = document.createElement('div');
            $item.className = 'relation-data';

            function setView(mediaRelationItem) {
                mediaRelationItem.view = this;
                mediaRelationItem.$item = $item;
            }
            
            function getMediaRelationItemForm() {
                var $form = document.createElement('div');
                $form.className = 'media-item-relation';
                
                $form.append(getMediaRelationDataForm());
                $form.append(getMediaRelationMediaExtraForm());
                $form.append(getMediaRelationItemHooksForm());
                $form.append(getMediaRelationRelationExtraForm());

                return $form;
            }
            function getMediaRelationDataForm() {
                var $form = document.createElement('div');
                $form.className = 'media-item-relation-data hide';
                
                var fields = [];
                    
                fields.push({
                    'id'    : idPrefix + 'id',
                    'type' : 'text',
                    'name' : formNamePrefix + '[id]',
                    'field' : 'input',
                    'value' : item.id,
                    'cssClass' : 'media-title form-control input-sm',
                    'icon': 'fa fa-link fa-fw',
                    'label' : Translator.__('Relation Id'),
                    'placeholder': Translator.__('Relation Id')
                });   
                fields.push({
                    'id'    : idPrefix + 'media',
                    'type' : 'text',
                    'name' : formNamePrefix + '[media]',
                    'field' : 'input',
                    'value' : item.media,
                    'cssClass' : 'media-id form-control input-sm',
                    'icon': 'fa fa-link fa-fw',
                    'label' : Translator.__('Media Id'),
                    'placeholder': Translator.__('Media Id')
                });  
                
                fields.forEach( function(fieldData) { 
                    $form.appendChild(getInputField(fieldData));
                });
                
                return $form;
            }
            
            function getMediaRelationMediaExtraForm() {
                var $form = document.createElement('div');
                $form.className = 'media-item-relation-data-mediaextra';
                
                var fields = [];

                //media extra
                fields.push({
                    'id'    : idPrefix + '_mediaextra_title',
                    'type' : 'text',
                    'name' : formNamePrefix + '[mediaExtra][title]',
                    'field' : 'input',
                    'value' : item.mediaExtra.original,
                    'cssClass' : 'mediaextra_title form-control input-sm',
                    'icon': 'fa fa-link fa-fw',
                    'label' : Translator.__('Title'),
                    'placeholder': Translator.__('Display title')
                });

                fields.forEach( function(fieldData) { 
                    $form.appendChild(getInputField(fieldData));
                });
                
                return $form;
            }
            
            function getMediaRelationItemHooksForm() {
                
                var $form_hooks = document.createElement('div');
                $form_hooks.className = 'media-item-relation-data-hooks hide';
                
                var fields = [];
                // hooks
                fields.push({
                    'id'    : idPrefix + 'hookedModule',
                    'type' : 'text',
                    'name' : formNamePrefix + '[hookedModule]',
                    'field' : 'input',
                    'value' : item.hookedModule,
                    'cssClass' : 'hookedModule-id form-control input-sm',
                    'icon': 'fa fa-link fa-fw',
                    'label' : Translator.__('hookedModule Id'),
                    'placeholder': Translator.__('hookedModule Id')
                });   
                fields.push({
                    'id'    : idPrefix + 'hookedAreaId',
                    'type' : 'text',
                    'name' : formNamePrefix + '[hookedAreaId]',
                    'field' : 'input',
                    'value' : item.hookedAreaId,
                    'cssClass' : 'hookedAreaId-id form-control input-sm',
                    'icon': 'fa fa-link fa-fw',
                    'label' : Translator.__('hookedAreaId Id'),
                    'placeholder': Translator.__('hookedAreaId Id')
                });   
                fields.push({
                    'id'    : idPrefix + 'hookedObjectId',
                    'type' : 'text',
                    'name' : formNamePrefix + '[hookedObjectId]',
                    'field' : 'input',
                    'value' : item.hookedObjectId,
                    'cssClass' : 'hookedObjectId-id form-control input-sm',
                    'icon': 'fa fa-link fa-fw',
                    'label' : Translator.__('hookedObjectId Id'),
                    'placeholder': Translator.__('hookedObjectId Id')
                });   
                fields.push({
                    'id'    : idPrefix + 'hookedUrlObject',
                    'type' : 'text',
                    'name' : formNamePrefix + '[hookedUrlObject]',
                    'field' : 'input',
                    'value' : item.hookedUrlObject,
                    'cssClass' : 'hookedUrlObject-id form-control input-sm',
                    'icon': 'fa fa-link fa-fw',
                    'label' : Translator.__('hookedUrlObject Id'),
                    'placeholder': Translator.__('hookedUrlObject Id')
                });

                fields.forEach( function(fieldData) { 
                    $form_hooks.appendChild(getInputField(fieldData));
                });
                
                return $form_hooks;
            }
            
            function getMediaRelationRelationExtraForm() {
                const $display_group = document.createElement('div');
                $display_group.className = 'form-group has-float-label';
                
//                Display select
                const $display_group_align_select = document.createElement('select');
                $display_group_align_select.className = 'form-control';
                $display_group_align_select.setAttribute('id', idPrefix + '_relationextra_display_select');
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
                $display_group_align_select_label.setAttribute('for', idPrefix + '_relationextra_display_select');
                $display_group_align_select_label.innerHTML = Translator.__('Media position');
                
                // add display select
                $display_group.appendChild($display_group_align_select);
                $display_group.appendChild($display_group_align_select_label);
                
                return $display_group;
            };   
            
            function getInputField(fieldData) {
                const $field_box = document.createElement('div');
                $field_box.className = 'form-group has-float-label';
                
                const $field = document.createElement(fieldData.field);
                $field.className = fieldData.cssClass;
                $field.setAttribute('id', fieldData.id);
                $field.setAttribute('type', fieldData.type);
                $field.setAttribute('name', fieldData.name);
                $field.setAttribute('placeholder', fieldData.placeholder);
                $field.setAttribute('value', fieldData.value);
                
                const $field_label = document.createElement('label');
                $field_label.setAttribute('for', fieldData.id);
                $field_label.innerHTML = fieldData.label;
                
                $field_box.appendChild($field);
                $field_box.appendChild($field_label);
                
                return $field_box;
            };  

            function render() {
                return $item;
            }
            ;

            return {
                render: render,
                setView: setView,
                getMediaRelationItemForm: getMediaRelationItemForm,
            };
        };   
            
        var view = new relationView();
        view.setView(this);

        this.setMediaRelationItemData = function (relationData) {
            $.extend(item, relationData);
            
            return item;
        };

        this.save = function () {
            var deferred = $.Deferred();
            
//            view.addProgress();
//            view.setProgressType('progress-bar-warning');
//            updateProgress(10);
//            view.showProgress();
//            
//            deferred.notify(20);
//                                        console.log(item);
//
//            var type = typeof (item.mediaExtra.handler) !== 'undefined' ? item.mediaExtra.handler : 'unknown';
//            var data = getMediaItemDataForm();
////                //var token = $newLi.find('input[name="upload_token"]').val();
////                //data.append('images[_token]',token);
////
//            $.ajax({
//                type: "POST",
//                url: Routing.generate('kaikmediagallerymodule_media_create', {"type": type, "_format": 'json'}),
//                data: data,
//                cache: false,
//                contentType: false,
//                processData: false,
////                    dataType:"json",
////                    mimeType: "application/x-www-form-urlencoded",
//                xhr: function () {
//                    var xhr = new window.XMLHttpRequest();
//                    xhr.upload.addEventListener("progress", function (evt) {
//                        if (evt.lengthComputable) {
//                            var percentComplete = (evt.loaded / evt.total) * 100;
//                            deferred.notify(percentComplete);
//                            //internal progress
//                            updateProgress(percentComplete);
//                            if (percentComplete === 100) {
//                                view.setProgressType('progress-bar-success');
//                            }
//                            console.log(percentComplete);
//                        }
//                    }, false);
//
//                    xhr.addEventListener("progress", function (evt) {
//                        if (evt.lengthComputable) {
//                            var percentComplete = (evt.loaded / evt.total) * 100;
//                            deferred.notify(percentComplete);
//                            updateProgress(percentComplete);
//                            console.log(percentComplete);
//
//                        }
//                    }, false);
//                    return xhr;
//                }
//            }).done(function (result) {
//                deferred.resolve(result);
////                console.log(result);
//                //var template = result.template;
//                //manager.view.itemEdit(template);
//                
//                view.setProgressType('progress-bar-success');
//                view.removeProgress();
//                
//                view.addActions();
//                
//            }).fail(function (result) {
//                deferred.reject();
//                console.log(result);
//                view.setProgressType('progress-bar-danger');
//                //manager.view.displayError(result.status + ': ' + result.statusText);
//            })
//            .always(function () {
//                console.log('always');
//                //manager.view.hideBusy();
//            });
            
            return deferred.promise();
        }
        ;         
    };
}(jQuery));


//            function showRelationData() {
////                $item.removeClass('hide');	    
//            }
//            ;
//
//            function hideRelationData() {
////                $item.addClass('hide');	    
//            }
//            ;
//
//            function clearRelationData() {
////                $item.find("input[name*='relationExtra'][type='text']").val('');	    
//            }
//            ;
//        this.setMediaRelationMediaExtra = function (mediaExtra) {
////            item.feature = 
//            item.mediaExtra = itemData.media_extra;
//            return item;
//        }; 
//        function getMediaRelationItemDataForm() {
//            var data = new FormData();
//
//            var type = typeof (item.mediaExtra.handler) !== 'undefined' ? item.mediaExtra.handler : 'unknown';
//            data.append('media_' + type + '[title]', item.title);//
//            data.append('media_' + type + '[description]', item.description !== '' ? item.description : item.title);
//            data.append('media_' + type + '[legal]', item.legal !== '' ? item.legal : 'unknow');
//            data.append('media_' + type + '[publicdomain]', item.publicdomain);
////                data.append('media_' + type + '[urltitle]', item.urltitle);
////                    data.append('media_' + type + '[file]', dataURItoBlob());
//            if (item.mediaExtra.isFile) {
//                data.append('media_' + type + '[file]', item.mediaExtra.fileData);
//            }
//
//            data.append('media_' + type + '[mediaExtra]', JSON.stringify(getMediaExtraData()));
//
//            return data;
//        }  
//        function updateProgress(evt) {
////            // evt is an ProgressEvent.
////            if (evt.lengthComputable) {
////                var percentLoaded = Math.round((evt.loaded / evt.total) * 100);
////                // Increase the progress bar length.
////                if (percentLoaded < 100) {
////                    view.updateProgress(percentLoaded);
////                }
////            }
//        }

//        this.addError = function (error) {
////            item.mediaExtra.error = error;
////            view.setError();
////            view.hideDetails();//by default
////            view.hidePreview();//by default
////            view.hideProgress();//by default
////            view.showError();//by default
//        };            
//
//        this.isError = function () {
////            return item.mediaExtra.error;
//        }; 
////            function render() {

////                
////                return $item;
////            }
////            ;
//
//            return {
//                render: render,

//                addProgress:addProgress
//            };
//
//        };
//
//        var view = new mediaView();
//        view.setView(this);

//            addPreview();
//            addError();

//            function setView(mediaItem) {
////                mediaItem.view = this;
////                mediaItem.$item = $item;
//            }
//            setPreview: view.setPreview,
//                getPreview: getPreview,
//                updatePreview: updatePreview,
//            addError: addError,
//                displayTitle: displayTitle
//                setView: setView,
//                addProgress: addProgress,
//                setProgressType: setProgressType,
//                updateProgress: updateProgress,
//                removeProgress: removeProgress,
//                getPreview: getPreview,
//                updatePreview: updatePreview,
//                addError: addError,
//                displayTitle: displayTitle
//            function addPreview() {
//                if (item.mediaExtra.isImage) {
//                    $preview.html(imagePreview());
//                } else {
//                    $preview.html(iconPreview());
//                }
//            }
//            ;
//
//            function imagePreview() {
//
//                if (item.mediaExtra.file === 'undefined') {
//
//                }
//
//                return $('<img/>')
//                        .addClass('image img-responsive')
//                        .attr('src', item.mediaExtra.file)
//                        .attr('title', escape(item.title));
//            }
//            ;
//
//            function updatePreview() {
//                if (item.mediaExtra.isImage) {
//                    $preview.html(imagePreview());
//                } else {
//                    $preview.html(iconPreview());
//                }
//            }
//            ;
//
//            function iconPreview() {
//                var icon = typeof (item.mediaExtra.mediaType) !== 'undefined' ? item.mediaExtra.mediaType.icon : 'fa fa-file';
//                return $('<i/>')
//                        .addClass('icon ' + icon + ' fa-5x')
//                        .attr('title', escape(item.title));
//
//            }
//            ;
//
//            function addDetails() {
//
//                $('<p/>')
//                        .addClass('size')
//                        .html(item.mediaExtra.size)
//                        .appendTo($details);
//                $('<p/>')
//                        .addClass('type')
//                        .html(item.mediaExtra.type)
//                        .appendTo($details);
//
//            }
//            ;
//
//            function displayTitle() {
//                $('<p/>')
//                        .addClass('title')
//                        .html(item.title)
//                        .appendTo($details);
//            }
//            ;

//        this.isImage = function () {
//            return item.mediaExtra.mimeType.match('image.*');
//        };

        //this.view = view;
//
//        function readFile() {
//
//
//            //based on actual media data;
//            var reader = new FileReader();
//
//
//            reader.onerror = errorHandler;
//            reader.onprogress = updateProgress;
//            //reader.onabort = function(e) {
//            //  alert('File read cancelled');
//            //};
//
//            reader.onloadstart = function (e) {
//                view.addProgress();
//            };
//
//            // Closure to capture the file information.
//            reader.onload = (function (theFile) {
//                return function (e) {
//                    item.mediaExtra.file = e.target.result;
//                    view.updateProgress(100);
//                    view.updatePreview();
//                    if (!item.id) {
//                        create();
//                    } else {
//                        view.setProgressType('progress-bar-success');
//                        view.removeProgress();
//                    }
//
//                };
//            })(item.mediaExtra.fileData);
//
//            if (typeof (item.mediaExtra.fileData) !== 'undefined') {
//                // Read in the image file as a data URL.
//                reader.readAsDataURL(item.mediaExtra.fileData);
//
//            } else {
//                var success = function (result) {
//                    item.mediaExtra.fileData = result;
//                    reader.readAsDataURL(item.mediaExtra.fileData);
//                };
//                var url = Routing.getBaseUrl().replace(/\w+\.php$/gi, '') + '/' + item.path + '/' + item.mediaExtra.path;
//
//                var xhr = new XMLHttpRequest();
//                xhr.open('GET', url, true);
//                xhr.responseType = "blob";
//                xhr.onreadystatechange = function () {
//                    if (xhr.readyState === 4) {
//                        if (success)
//                            success(xhr.response);
//                    }
//                };
//                xhr.send(null);
//            }
//        }
//        ;
//
//        function errorHandler(evt) {
//            switch (evt.target.error.code) {
//                case evt.target.error.NOT_FOUND_ERR:
//                    item.mediaExtra.error = 'File Not Found!';
//                    break;
//                case evt.target.error.NOT_READABLE_ERR:
//                    item.mediaExtra.error = 'File is not readable';
//                    break;
//                case evt.target.error.ABORT_ERR:
//                    break; // noop
//                default:
//                    item.mediaExtra.error = 'An error occurred reading this file.';
//            }
//            ;
//        }
//
//        function updateProgress(evt) {
//            // evt is an ProgressEvent.
//            if (evt.lengthComputable) {
//                var percentLoaded = Math.round((evt.loaded / evt.total) * 100);
//                // Increase the progress bar length.
//                if (percentLoaded < 100) {
//                    view.updateProgress(percentLoaded);
//                }
//            }
//        }

//

//        this.setDatafromUpload = function (f) {
//            item.title = f.name;
//            view.displayTitle();
//            item.mediaExtra.fileData = f;
//            item.mediaExtra.name = f.name;
//            item.mediaExtra.size = f.size;
//            item.mediaExtra.mimeType = f.type;
//            item.mediaExtra.error = f.error;
//            item.mediaExtra.mediaType = f.mediaType;
//            item.mediaExtra.isUpload = true;
//            item.mediaExtra.isImage = this.isImage();
//            readFile();
//            view.addError();
//        };
//
//        this.setMediaItemData = function (itemData) {
//            $.extend(item, itemData);
//            view.displayTitle();
//            readFile();
//            view.addError();
//            //console.log(itemData);
//            //item.mediaExtra.isImage = this.isImage();
//            return item;
//        };
//
//        function create() {
//            //console.log('create');
//            if (item.mediaExtra.error) {
//                view.setProgressType('progress-bar-danger');
//                return false;
//            }
//
//            updateProgress(0);
//            view.setProgressType('progress-bar-warning');
//            //console.log('create');
//            var type = typeof (item.mediaExtra.mediaType) !== 'undefined' ? item.mediaExtra.mediaType.handler : 'unknown';
//            var data = getMediaItemDataForm();
//            //var token = $newLi.find('input[name="upload_token"]').val();
//            //console.log(token);
//            //data.append('images[_token]',token);
//
//            $.ajax({
//                type: "POST",
//                url: Routing.generate('kaikmediagallerymodule_media_create', {"type": type, "_format": 'json'}),
//                data: data,
//                cache: false,
//                contentType: false,
//                processData: false,
//                xhr: function () {
//                    var xhr = new window.XMLHttpRequest();
//                    xhr.upload.addEventListener("progress", function (evt) {
//                        if (evt.lengthComputable) {
//                            var percentComplete = (evt.loaded / evt.total) * 100;
//                            updateProgress(percentComplete);
//                            if (percentComplete === 100) {
//                                view.setProgressType('progress-bar-success');
//                            }
//                        }
//                    }, false);
//
//                    xhr.addEventListener("progress", function (evt) {
//                        if (evt.lengthComputable) {
//                            var percentComplete = (evt.loaded / evt.total) * 100;
//                            updateProgress(percentComplete);
//                        }
//                    }, false);
//                    return xhr;
//                }
//            }).success(function (result) {
//                //console.log(result);
//                //var template = result.template;
//                //manager.view.itemEdit(template);
//                view.setProgressType('progress-bar-success');
//                view.removeProgress();
//            }).error(function (result) {
//                //console.log(result);
//                view.setProgressType('progress-bar-danger');
//                //manager.view.displayError(result.status + ': ' + result.statusText);
//            }).always(function () {
//                //console.log('always');
//                //manager.view.hideBusy();
//            });
//
//
//
//        }
//        ;
//
//        function getMediaItemDataForm() {
//            var type = typeof (item.mediaExtra.mediaType) !== 'undefined' ? item.mediaExtra.mediaType.handler : 'unknown';
//            var data = new FormData();
//            data.append('media_' + type + '[title]', item.title);
//            data.append('media_' + type + '[description]', item.description !== '' ? item.description : item.title);
//            data.append('media_' + type + '[urltitle]', item.urltitle);
//            data.append('media_' + type + '[legal]', item.legal !== '' ? item.legal : 'unknow');
//            data.append('media_' + type + '[publicdomain]', item.publicdomain);
//            data.append('media_' + type + '[mediaExtra]', JSON.stringify(getMediaExtraData()));
//            if (item.mediaExtra.isUpload) {
//                data.append('media_' + type + '[file]', dataURItoBlob());
//            }
//            return data;
//        }
//
//
//        function getMediaExtraData() {
//            var data = $.extend({}, item.mediaExtra);
//            delete data.file;
//            delete data.fileData;
//            return data;
//        }
//
//        function dataURItoBlob() {
//            // convert base64 to raw binary data held in a string             // doesn't handle URLEncoded DataURIs
//
//            var byteString = window.atob(item.mediaExtra.file.split(',')[1]);
//            // separate out the mime component
//            //var ab = new ArrayBuffer(byteString.length);
//            var ia = new Uint8Array(byteString.length);
//            for (var i = 0; i < byteString.length; i++) {
//                ia[i] = byteString.charCodeAt(i);
//            }
//            // write the ArrayBuffer to a blob, and you're done
//            var blob = new Blob([ia], {type: item.mediaExtra.mimeType});
//
//            return blob;
//        }


/*


        function addMedia(f) {
            mediaForm = createMediaForm();
            mediaForm.append('file', f);
            showProgress();
            doAjax(Routing.generate('kaikmediagallerymodule_media_create', {"type": settings.type, "_format": 'json'}), mediaForm)
                .done(postMediaAdd)
                .fail(displayError) 
                .progress(updateProgress)
        }
        ;

        function postMediaAdd(data) {
            setMediaData(data);
            showRemoveButton();
            showDetailsButton();
            setProgressType('progress-bar-success');
            removeProgress();
        }
        ;
 
        function setMediaData(data) {
            settings.$container.find('.relation-data-media').val(data.media_id);
        }
        ;
        function showRemoveButton() {
            settings.$container.find('.remove-action').removeClass('hide');
        }
        ;
        function showDetailsButton() {
            settings.$container.find('.relation-data-details').removeClass('hide');
        }
        ;
        
        function createMediaForm() {
            
            var form = new FormData();
            
            form.append('feature', settings.feature);
            form.append('accept', settings.accept);
            form.append('handler', settings.handler);
            form.append('prefix', settings.prefix);
            form.append('dir', settings.dir);
            
            return form;
        }
        ;



	//ajax util
	function doAjax(url, data) {
	    var deferred = $.Deferred();
	    $.ajax({
                type: "POST",
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
		success: deferred.resolve,  // resolve it 
		error: deferred.reject,  // reject it
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (e) {
                        if (e.lengthComputable) {
			    deferred.notify(parseInt(e.loaded / e.total * 100));
                        }
                    }, false);

                    xhr.addEventListener("progress", function (e) {
                        if (e.lengthComputable) {
			deferred.notify(parseInt(e.loaded / e.total * 100));
                        }
                    }, false);
                    return xhr;
                }
            });
	    
	    return deferred.promise();
	}

	// View
	function showRelationData() {
	    settings.$container.find(".relation-data").addClass('in');	    
	}
	;
	
	function hideRelationData() {
	    settings.$container.find(".relation-data").removeClass('in');    
	}
	;
	
	function clearRelationData() {
	    settings.$container.find("input[name*='relationExtra'][type='text']").val('');	    
	}
	;
	function clearDialog() {
	    settings.$dialog.html('')	    
	}
	;
	function clearPreview() {
	    settings.$preview.html('');	    
	}
	;
        
	function displayDialog($html) {
	    settings.$dialog.html($html);
	}
	;        
        
	function displayPreview(html) {
	    settings.$preview.html(html);
	    settings.$preview.removeClass('hide');
	}
	;

	
	function showProgress() {
	    settings.$container.find('.progress').removeClass('hide');
	}
	;
	
        function setProgressType(type) {
                var type = typeof (type) !== 'undefined' && type !== null ? type : '';
                var $progres_bar = settings.$container.find('.progress-bar');
                $progres_bar.removeClass().addClass('hide').addClass('progress-bar').addClass(type).removeClass('hide');
            }
        ;
	
	function updateProgress(x) {
	    var width = typeof (x) !== 'undefined' && x !== null ? x : 0;
	    var $progres_bar = settings.$container.find('.progress-bar');
	    $progres_bar.css('width', width + '%')
		    .attr('aria-valuenow', width);
	}
	;
	
	function removeProgress() {
	    settings.$container.find('.progress').fadeOut(300, function () {
		$(this).addClass('hide');
	    });
	}
	;
	
	function hideProgress() {
	    settings.$container.find('.progress').addClass('hide');
	}
	;








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