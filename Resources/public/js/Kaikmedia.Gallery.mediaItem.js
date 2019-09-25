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

        var formNamePrefix = '';
        var idPrefix = '';

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
                relation: 'new',
                error: false
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
            var $error = $('<div/>').addClass('error hide');
            var $preview = $('<div/>').addClass('preview');
            var $progress = $('<div/>').addClass('progress hide');
            
            $item.append($error);
            $item.append($preview);
            $item.append($progress);
            
            var editor = function () {
                function addAction(actionData) {
                    const $actionBox = document.createElement('div');
                    $actionBox.className = actionData.boxCssClass;
                    
                    const $actionButton = document.createElement('button');
                    $actionButton.className = actionData.buttonCssClass;
                    $actionButton.setAttribute('id', actionData.buttonId);
                    $actionButton.setAttribute('type', 'button');
                    $actionButton.setAttribute('data-toggle', "dropdown");
                    $actionButton.setAttribute('aria-haspopup', true);
                    $actionButton.setAttribute('aria-expanded', false);
                    
                    const $actionButton_icon = document.createElement('i');
                    $actionButton_icon.className = actionData.icon;
                    $actionButton.appendChild($actionButton_icon);
                    
                    $actionBox.append($actionButton);
                    
                    const $dropdown = document.createElement('div');
                    $dropdown.className = actionData.dropdownBoxClass;
                    $dropdown.setAttribute('id', actionData.dropdownBoxId);
                    $dropdown.setAttribute('aria-labelledby', actionData.buttonId);
                    const $dropdown_title = document.createElement('p');
                    $dropdown_title.className = actionData.dropdownBoxTitleCssClass;
                    $dropdown_title.innerText = actionData.dropdownBoxTitle;
                    $dropdown.append($dropdown_title);
                    
                    const $dropdown_html = document.createElement('div');
                    $dropdown_html.innerHTML = actionData.dropdownBoxHtml.innerHTML;
                    $dropdown.append($dropdown_html);
                    
                    $actionBox.append($dropdown);
                    $item.append($actionBox);
//                    console.log(actionData);
                }
                ;

                function render() {
                    return $editor;
                }
                ;                
                
                return {
                    render: render,
                    addAction: addAction,
             };
            }
            ;
            
            var $editor = new editor();
            $item.append($editor.render);
            
            function setView(mediaItem) {
                mediaItem.view = this;
                mediaItem.$item = $item;
            }

            function getPreviewContainer() {
                return $preview;
            }
            ;
            function setPreview($html) {
                $preview.html($html);
            }
            ;
            function showPreview() {
                $preview.removeClass('hide');
            }
            ;
            function hidePreview() {
                $preview.addClass('hide');
            }
            ;

            function setError() {
                $('<p/>')
                .addClass('text text-danger')
                .html(item.mediaExtra.error)
                .appendTo($error);
            }
            ;
            function showError() {
                $error.removeClass('hide');
            }
            ;
            function hideError() {
                $error.addClass('hide');
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

            function showProgress() {
                $progress.removeClass('hide');
            }
            ;
            function hideProgress() {
                $progress.addClass('hide');
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

            function getMediaItemDetails() {
                const $detailsBox = document.createElement('div');
                $detailsBox.className = 'media-item-data';                
                
                const $title_box = document.createElement('div');
                $title_box.className = 'dropdown-item';
                $title_box.innerText = item.title;
                
                const $size_box = document.createElement('div');
                $size_box.className = 'dropdown-item';
                $size_box.innerText = item.mediaExtra.size;
                
                $detailsBox.append($title_box);
                $detailsBox.append($size_box);
                
                return $detailsBox;
            }

            function getMediaItemForm() {
                const $form = $('<div/>').addClass('media-item-data hide');
                var fields = [];
                    
                fields.push({
                    'id'    : 'title',
                    'type' : 'text',
                    'name' : 'title',
                    'field' : 'input',
                    'value' : item.title,
                    'cssClass' : 'media-title form-control',
                    'icon': 'fa fa-link fa-fw',
                    'label' : Translator.__('Title'),
                    'placeholder': Translator.__('Title')
                });   
                fields.push({
                    'id'    : 'description',
                    'type' : 'text',
                    'name' : 'description',
                    'field' : 'input',
                    'value' : item.description,
                    'cssClass' : 'media-description form-control',
                    'icon': 'fa fa-link fa-fw',
                    'label' : Translator.__('Description'),
                    'placeholder': Translator.__('Description')
                });   

                fields.forEach( function(fieldData) { 
                    $form.append(getInputField(fieldData));
                });               
                
                return $form;
            }

            function getInputField(fieldData) {
                const $field_box = document.createElement('div');
                $field_box.className = 'form-group has-float-label';//'media-title form-control';
                
                const $field = document.createElement(fieldData.field);
                $field.className = fieldData.cssClass;//'media-title form-control';
                $field.setAttribute('id', fieldData.id);
                $field.setAttribute('type', fieldData.type);
                $field.setAttribute('name', fieldData.name);//'media-title-input'
                $field.setAttribute('placeholder', fieldData.placeholder);
                $field.setAttribute('value', fieldData.value);
                
                const $field_label = document.createElement('label');
                $field_label.setAttribute('for', fieldData.id);
                $field_label.innerHTML = fieldData.label;
                
                $field_box.append($field);
                $field_box.append($field_label);
                
                return $field_box;
            };

            function render() {
                return $item;
            }
            ;

            return {
                render: render,
                setView: setView,
                
                setPreview: setPreview,
                getPreviewContainer: getPreviewContainer,
                showPreview: showPreview,
                hidePreview: hidePreview,
                
                setError: setError,
                showError: showError,
                hideError: hideError,
                
                addAction: $editor.addAction,
                
                addProgress: addProgress,
                showProgress: showProgress,
                hideProgress: hideProgress,
                setProgressType: setProgressType,
                updateProgress: updateProgress,
                removeProgress: removeProgress,
                
                getMediaItemDetails: getMediaItemDetails,
                getMediaItemForm: getMediaItemForm,
            };

        };   
            
        var view = new mediaView();
        view.setView(this);

        this.setIdPrefix = function (prefix) {
            idPrefix = prefix;
        };
        this.setFormNamePrefix = function (prefix) {
            formNamePrefix = prefix;
        };
        this.setMediaItemData = function (itemData) {
            
            $.extend(item, itemData);
            
            return item;
        };
        this.setMediaExtraData = function (mediaExtraData) {
//            console.log(item, mediaExtraData);
//            $.extend(item.mediaExtra, mediaExtraData);
            return item;
        };

        function getMediaExtraData() {
            var data = $.extend({}, item.mediaExtra);
            return data;
        }

        this.save = function () {
            var deferred = $.Deferred();
            console.log(item);

            view.addProgress();
            view.setProgressType('progress-bar-warning');
            updateProgress(1);
            view.showProgress();
            
            deferred.notify(5);

            var type = typeof (item.mediaExtra.handler) !== 'undefined' ? item.mediaExtra.handler : 'unknown';
            var data = getMediaItemDataForm();
//                //var token = $newLi.find('input[name="upload_token"]').val();
//                //data.append('images[_token]',token);
            $.ajax({
                type: "POST",
                url: Routing.generate('kaikmediagallerymodule_media_create', {"type": type, "_format": 'json'}),
                data: data,
                cache: false,
                timeout: 20000,
                contentType: false,
                processData: false,
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            deferred.notify(percentComplete);
                            //internal progress
                            if (percentComplete === 100) {
                                updateProgress(percentComplete);
                                view.setProgressType('progress-bar-success');
                            }
                        }
                    }, false);

                    xhr.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            deferred.notify(percentComplete);
                            updateProgress(percentComplete);
                        }
                    }, false);
                    return xhr;
                }
                
//            xhr: function() {
//                var progress = $('.progress'),
//                    xhr = $.ajaxSettings.xhr();
//
//                progress.show();
//
//                xhr.upload.onprogress = function(ev) {
//                    if (ev.lengthComputable) {
//                        var percentComplete = parseInt((ev.loaded / ev.total) * 100);
//                        progress.val(percentComplete);
//                        if (percentComplete === 100) {
//                            progress.hide().val(0);
//                        }
//                    }
//                };
//
//                return xhr;
//            },
                
            }).done(function (result) {
                view.setProgressType('progress-bar-success');
                view.removeProgress();
                deferred.resolve(result);
            }).fail(function (result) {
                deferred.reject(result);
                view.setProgressType('progress-bar-danger');
            })
            .always(function () {
            });
            
            return deferred.promise();
        }
        ;

        function getMediaItemDataForm() {
            var type = typeof (item.mediaExtra.handler) !== 'undefined' ? item.mediaExtra.handler : 'unknown';
            var data = new FormData();
                data.append('media_' + type + '[title]', item.title);//
                data.append('media_' + type + '[description]', item.description !== '' ? item.description : item.title);
                data.append('media_' + type + '[legal]', item.legal !== '' ? item.legal : 'unknow');
                data.append('media_' + type + '[publicdomain]', item.publicdomain);
    //                data.append('media_' + type + '[urltitle]', item.urltitle);
                if (item.mediaExtra.isFile) {
                    data.append('media_' + type + '[file]', item.mediaExtra.file);
                }
                
                var mediaExtra = getMediaExtraData();
                delete mediaExtra.file;
                delete mediaExtra.fileDataUrl;

                data.append('media_' + type + '[mediaExtra]', JSON.stringify(mediaExtra));
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

        this.isError = function () {
            return item.mediaExtra.error;
        };
        
        this.addError = function (error) {
            item.mediaExtra.error = error;
            view.setError();
            view.hideDetails();//by default
            view.hidePreview();//by default
            view.hideProgress();//by default
            view.showError();//by default
        };            
    };
}(jQuery));

//                console.log(result);
                //var template = result.template;
                //manager.view.itemEdit(template);
//                view.updateTitle();
//                view.updateSize();
//                view.setId();   
//                var $editor = $('<div/>').addClass('editor');
//                var $editor_container = $('<div/>').addClass('editor_container tab-content');
//                var $actions = $('<div/>').addClass('actions');

//                $editor.append($editor_container); 
//                $editor.append($actions);
//                bindEditorEvents();

//                function addActions() {

//                    var actions = [];
//                    
//                    actions.push({'name' : 'remove',
//                        'boxCssClass' : 'action remove dropdown',
//                        'buttonId' : idPrefix+'_remove_action',
//                        'buttonCssClass' : 'btn btn-link',
//                        'icon': 'fa fa-trash fa-fw',
//                        'dropdownBoxId': idPrefix+'_remove_action_content',
//                        'dropdownBoxClass': 'dropdown-menu',
////                        'addPanel' : true,
////                        'panelClass' : 'tab-pane',
////                        'panelTitle': Translator.__('Display options')
//                    });
//                    actions.push();
//                    actions.push({'name' : 'edit',
//                        'boxCssClass' : 'action edit dropdown',
//                        'buttonId' : idPrefix+'_edit_action',
//                        'buttonCssClass' : 'btn btn-link',
//                        'icon': 'fa fa-pencil fa-fw',
//                        'dropdownBoxId': idPrefix+'_edit_action_content',
//                        'dropdownBoxClass': 'dropdown-menu',
////                        'addPanel' : true,
////                        'panelClass' : 'tab-pane',
////                        'panelTitle': Translator.__('Display options')
//                    });
//                    actions.push({'name' : 'relation',
//                        'cssClass' : 'edit-relation action btn btn-link',
//                        'icon': 'fa fa-pencil fa-fw',
//                        'addPanel' : true,
//                        'panelId' : idPrefix+'_edit_relation',
//                        'panelClass' : 'tab-pane',
//                        'panelTitle': Translator.__('Display options')
//                    });
//                    actions.push({'name' : 'details',
//                        'cssClass' : 'edit-media action btn btn-link',
//                        'icon': 'fa fa-info fa-fw', 
//                        'addPanel' : true,
//                        'panelId' : idPrefix+'_details',
//                        'panelClass' : 'tab-pane',
//                        'panelTitle': Translator.__('Media details')    
//                    });
//                    actions.push({'name' : 'remove-relation',
//                        'cssClass' : 'remove-relation action btn btn-link',
//                        'icon': 'fa fa-trash fa-fw fa-fw',
//                        'addPanel' : true,
//                        'panelId' : idPrefix+'_unlink',
//                        'panelClass' : 'tab-pane',
//                        'panelTitle': Translator.__('Unlink media')
//                    });
//                    actions.push({'name' : 'remove-media',
//                        'cssClass' : 'remove-media action btn btn-link',
//                        'icon': 'fa fa-trash fa-fw',
//                        'addPanel' : true,
//                        'panelId' : idPrefix+'_remove_media',
//                        'panelClass' : 'tab-pane',
//                        'panelTitle': Translator.__('Remove media')
//                    });
//                    actions.push({'name' : 'position-media',
//                        'cssClass' : 'position-media action btn btn-link',
//                        'icon': 'fa fa-arrows fa-fw',
//                        'addPanel' : false,
//                        'panelId' : idPrefix+'_position_media',
//                        'panelClass' : 'tab-pane',
//                        'panelTitle': Translator.__('Position media')
//                    });

//                    actions.forEach( function(actionData) { 
//                        addAction(actionData);
////                        if (actionData.addPanel) {
////                            addActionPanel(actionData)
////                        }
//                    });
//                }
//                ;     
                
//                function addActionPanel(actionPanelData) {
//
//                    const $dropdown = document.createElement('ul');
//                    $dropdown.className = 'dropdown-menu';
//                    $dropdown.setAttribute('aria-labelledby', actionPanelData.buttonId);
////                    $tab.setAttribute('data-content', actionPanelData.name);
////                    $tab.setAttribute('id', actionPanelData.panelId);
////                    $tab.setAttribute('placeholder', '');
//                    const $tab_title = document.createElement('p');
//                    $tab_title.className = 'action-title';
//                    $tab_title.innerText = actionPanelData.panelTitle ;
//                    $dropdown.append($tab_title);
//                    
//                    $editor_container.append($tab);
//                }
//                ;

//                function bindEditorEvents() {
//                    $item.on('click', 'a.action', function() {
////                            switchAction($(this));
//                    });
//                }
//                ;
                
//                function switchAction($action) {
////                    var content = $action.data('content');
////                    var $tab = $editor_container
////                        .find("div[data-content='" + content + "']")
////                    ;
////                    
////                    if ($tab.hasClass('active')) {
////                        $actions
////                            .find('.active').removeClass('active');
////                        $editor_container
////                            .find('.active').removeClass('active');
////                    } else {     
////                        $actions
////                            .find('.active').removeClass('active');
////                        $editor_container
////                            .find('.active').removeClass('active');         
////                        $action.addClass('active');    
////                        $tab.addClass('active');	    
////                    }
//                }
//                ;
//                
//                function insertActionHtml(action, $html) {
//                    $($item)
//                        .find("action ." + action + "")
//                        .append($html);	    
//                }
//                ;             
                
//                    const $relations = document.createElement('div');
//                    $relations.className = 'tab-pane active';
//                    $relations.setAttribute('role', 'tabpanel');
//                    $relations.setAttribute('data-content', 'relation');
//                    $relations.setAttribute('id', 'media-item-details');
//                    $relations.setAttribute('placeholder', '');Translator.__('Media details')

                // remove media


//                    var $actionButton = $('<a/>')
//                            .addClass(actionData.cssClass)
//                            .attr('data-content', actionData.name)
//                            .attr('data-toggle', 'tab')
//                            .attr('role', 'tab')
//                    ;
//                    var $actionButton_icon = $('<i/>').addClass(actionData.icon);
//                    $actionButton_icon.appendTo($actionButton);
//                    $actionButton.appendTo($actions);
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