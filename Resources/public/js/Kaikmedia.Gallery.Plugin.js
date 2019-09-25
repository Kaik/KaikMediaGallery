/**
 *Gallery plugin manager
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};
(function ($) {
    KaikMedia.Gallery.plugin = (function () {
        // Init
        var data = {
        };
        var settings = {
            limit: 100,
            ajax_timeout: 10000,
	    accept: 'image/*'
        }
        
        var selected = [];
        
        var actions = [];
        
	 // Init
        function init()
        {
            console.log('Gallery plugin 1.0');
            
	    $('.add-action').click(function (e) {
		e.preventDefault();
                readSettings($(this));
                console.log(settings);
                clearDialog();
		handleAdd();
//                if (settings.collection.multiple) {
//                } else {
//                  clearSelected();
//                }                
	    });            
        }
        ;

	function setPresets(presets) {
	    settings.presets = presets;
	}
	;
        
	function readSettings($this) {
	    settings.container = $this.data('container');
	    settings.$container = $('#' + settings.container);
            
	    settings.$collection = settings.$container.find('.collection-data');
            settings.$handler = $this;
	    settings.$selected = settings.$container.find('.selected-media');
	    settings.$dialog = settings.$container.find('.dialog');
            
            settings.handler = settings.$handler.data();
	    readCollectionSettings();
	}
	;
        
	function readCollectionSettings() {
            settings.collection = settings.$collection.data();
            settings.collection.hookedobjectid = (settings.collection.hookedobjectid == '' ? newID() : settings.collection.hookedobjectid);
	}
	;
	function generateIdPrefixWithRelationIdAndMediaItemId(relationId, mediaItemId) 
        {
            let prefixId = generateIdPrefixWithRelationId(relationId)
                    + '_'+ mediaItemId + ''
            ;

	    return prefixId;
	}
	;
	function generateFormNamePrefixWithRelationIdAndMediaItemId(relationId, mediaItemId) 
        {
            let prefixForm = generateFormNamePrefixWithRelationId(relationId)
                    + '['+ mediaItemId +']'
            ;
            
	    return prefixForm;
	}
	;
        
	function generateIdPrefixWithRelationId(relationId) 
        {
            let prefixId = ''
                    + settings.collection.providerarea 
                    + '_'+ settings.collection.hookedmodule
                    + '_'+ settings.collection.hookedareaid
                    + '_'+ settings.collection.hookedobjectid
                    + '_'+ settings.collection.name + ''
                    + '_'+ relationId + '' // relation id hmm 
            ;

	    return prefixId;
	}
	;
        
	function generateFormNamePrefixWithRelationId(relationId) 
        {
            let prefixForm = ''
                    + settings.collection.providerarea 
                    + '['+ settings.collection.hookedmodule +']'
                    + '['+ settings.collection.hookedareaid +']'
                    + '['+ settings.collection.hookedobjectid +']'
                    + '['+ settings.collection.name +']'
                    + '['+ relationId +']'
            ;
            
	    return prefixForm;
	}
	;

        function newID() 
        {
          // Math.random should be unique because of its seeding algorithm.
          // Convert it to base 36 (numbers + letters), and grab the first 9 characters
          // after the decimal.
          return 'new_' + Math.random().toString(36).substr(2, 9);
        };
        
        function handleAdd() 
        {
            //call handler
            for (var handler in KaikMedia.Gallery.handler) {
                if (handler === settings.handler.type) {      
                    KaikMedia.Gallery
                        .handler[handler]
                        .setSettings(Object.assign({},settings.handler, settings.collection,{ dialog: settings.$dialog[0] }));
                        // now we need to
                        // load data to view
                        // process global progress
                        // 
                    KaikMedia.Gallery
                        .handler[handler]
                        .add()
                        .progress( progress => {
//                            console.log(progress);
                        })
                        .done( mediaItemPromises => {
                            handlePromises(mediaItemPromises)
                                .progress(percent => {
                                    console.log(percent);
                                })
                                .done( mediaItem => {
//                                    console.log(mediaItem);
                                })
                                .fail(handleErrors) // bad things happened
                                ;
                        })
                        .fail(handleErrors) // bad things happened
                    ;
                }
            }
        }
        ;

        function handlePromises(mediaItemPromises) {
            var deferred = $.Deferred();
    
//                if (settings.collection.multiple) {
//                } else {
//                  clearSelected();
//                }  
    
                for (const p of mediaItemPromises) {
                    p.progress(status => { 
//                        console.log(status);
                    }).done(mediaItem => {
                        loadItem(mediaItem);
                        pushToSelected(mediaItem);
                        mediaItemSave(mediaItem)
                            .progress(percent => {
                                deferred.notify(percent);
                            })
                            .done(mediaItemData => {
                                handleSave(mediaItem, mediaItemData);
                                deferred.resolve(mediaItem);
                            })
                            .fail(handleErrors) // bad things happened
                            ;
                    });
                }   
                
	    return deferred.promise();
        }
        ;
        
        function mediaItemSave(mediaItem) {
            var deferred = $.Deferred();
            if (settings.collection.autosave) {
                if (mediaItem.isError() == false) {
                    mediaItem
                    .save()
                    .progress(percent => {
                            deferred.notify(percent);
                    })
                    .done(mediaItemData => {
                        deferred.resolve(mediaItemData);
                    });
                }                                        
            } else {
                deferred.resolve();
            }
            
            return deferred.promise();
        }
        ;
        
        function loadItem(mediaItem) {
            if (settings.collection.multiple) {
                settings.$selected.append(mediaItem.view.render);
            } else {
                settings.$selected.html(mediaItem.view.render);
            }
        }
        ;
        
        function handleSave(mediaItem, mediaItemData) {
            var newId = newID();
            mediaItem.setIdPrefix(generateIdPrefixWithRelationIdAndMediaItemId(newId, mediaItemData.id));
            
            mediaItem.setFormNamePrefix(generateFormNamePrefixWithRelationIdAndMediaItemId(newId, mediaItemData.id));
            
            mediaItem.setMediaItemData(mediaItemData);
            
            
            
//          remove button
            var remove = {'name' : 'remove',
                         'boxCssClass' : 'action remove dropdown',
                         'buttonId' : generateIdPrefixWithRelationIdAndMediaItemId(newId, mediaItemData.id)+'_remove_action',
                         'buttonCssClass' : 'btn btn-sm btn-link',
                         'icon': 'fa fa-trash fa-fw text-danger',
                         'dropdownBoxId': generateIdPrefixWithRelationIdAndMediaItemId(newId, mediaItemData.id)+'_remove_action_content',
                         'dropdownBoxClass': 'dropdown-menu',
                         'dropdownBoxHtml': document.createElement('div'),
                         'dropdownBoxTitleCssClass': 'dropdown-header',
                         'dropdownBoxTitle': Translator.__('Remove this item?')
                     };
            mediaItem.view.addAction(remove);




//          image info
            var info = {'name' : 'info',
                         'boxCssClass' : 'action info dropdown' + (settings.presets.enable_info == '1' ? ' ' : ' hide') + ' ',
                         'buttonId' : generateIdPrefixWithRelationIdAndMediaItemId(newId, mediaItemData.id)+'_info_action',
                         'buttonCssClass' : 'btn btn-sm btn-link',
                         'icon': 'fa fa-info fa-fw',
                         'dropdownBoxId': generateIdPrefixWithRelationIdAndMediaItemId(newId, mediaItemData.id)+'_info_action_content',
                         'dropdownBoxClass': 'dropdown-menu dropdown-menu-right',
                         //get intem info data 
                         'dropdownBoxHtml': mediaItem.view.getMediaItemDetails(),
                         'dropdownBoxTitleCssClass': 'dropdown-header',
                         'dropdownBoxTitle': Translator.__('Media info')
                     };
            mediaItem.view.addAction(info);

//          handler image edit stuff
            var edit = {'name' : 'edit',
                         'boxCssClass' : 'action edit dropup' + (settings.presets.enable_editor == '1' ? ' ' : ' hide') + ' ',
                         'buttonId' : generateIdPrefixWithRelationIdAndMediaItemId(newId, mediaItemData.id)+'_edit_action',
                         'buttonCssClass' : 'btn btn-sm btn-link',
                         'icon': 'fa fa-paint-brush fa-fw',
                         'dropdownBoxId': generateIdPrefixWithRelationIdAndMediaItemId(newId, mediaItemData.id)+'_edit_action_content',
                         'dropdownBoxClass': 'dropdown-menu dropdown-menu-right',
                         //get edit content from hmm?
                         'dropdownBoxHtml': KaikMedia.Gallery.handler[settings.handler.type].getActions(),
                         'dropdownBoxTitleCssClass': 'dropdown-header',
                         'dropdownBoxTitle': Translator.__('Edit media')
                     };
            mediaItem.view.addAction(edit);

            setRelations(newId, mediaItem, mediaItemData);
            
            return mediaItem;
        }
        ;

        function setRelations(newId, mediaItem, mediaItemData) {
            var deferred = $.Deferred();
            var mediaRelationItem = new KaikMedia.Gallery.model.mediaRelationItem();
            
            mediaRelationItem.setIdPrefix(generateIdPrefixWithRelationId(newId));
            mediaRelationItem.setFormNamePrefix(generateFormNamePrefixWithRelationId(newId));

            var mediaRelationData = {};
            mediaRelationData.id = newId;
            mediaRelationData.media = mediaItemData.id;
            mediaRelationData.feature = settings.collection.name;
            
            // add presets here
            mediaRelationData.mediaExtra = mediaItemData.mediaExtra;
            mediaRelationData.mediaExtra['title'] = mediaItemData.tile;
            mediaRelationData.relationExtra = '';
            mediaRelationData.featureExtra = settings.collection;
            console.log(mediaItemData);
            
            // hooks
            mediaRelationData.hookedModule = settings.collection.hookedmodule;
            mediaRelationData.hookedAreaId = settings.collection.hookedareaid;
            mediaRelationData.hookedObjectId = settings.collection.hookedobjectid;
            mediaRelationData.hookedUrlObject = settings.collection.hookedurlobject;
            
            // feature
//            mediaRelationData.collection = settings.collection;
            
            // mediaItemData
            mediaRelationItem.setMediaRelationItemData(mediaRelationData);
            
            // render
//            mediaItem.view.insertActionHtml('relation', mediaRelationItem.view.render);
            var formsBox = document.createElement('div');
            formsBox.append(mediaItem.view.getMediaItemForm()[0]);
            formsBox.append(mediaRelationItem.view.getMediaRelationItemForm());
            
            var relation = {'name' : 'relation',
                         'boxCssClass' : 'action relation dropup' + (settings.presets.enable_extra == '1' ? ' ' : ' hide') + ' ',
                         'buttonId' : generateIdPrefixWithRelationIdAndMediaItemId(newId, mediaItemData.id)+'_relation_action',
                         'buttonCssClass' : 'btn btn-sm btn-link',
                         'icon': 'fa fa-sticky-note-o fa-fw',
                         'dropdownBoxId': generateIdPrefixWithRelationIdAndMediaItemId(newId, mediaItemData.id)+'_relation_action_content',
                         'dropdownBoxClass': 'dropdown-menu dropdown-menu-right pre-scrollable',
                         'dropdownBoxTitleCssClass': 'dropdown-header',
                         'dropdownBoxTitle': Translator.__('Display options'),
                         'dropdownBoxHtml': formsBox
                     };
            mediaItem.view.addAction(relation);

            // deferred.resolve();
            return deferred.promise();
        }
        ;
        
        function pushToSelected(mediaItem) {
            selected.push(mediaItem);
        }
        ;
        
	function handleErrors(errors) {
            console.log(errors);
	}
	; 
        
	function displayDialog($html) {
	    settings.$dialog.html($html);
	}
	; 
        
	function clearDialog() {
	    settings.$dialog.html('')	    
	}
	;        

	function clearSelected() {
	    settings.$selected.html('');
            selected = [];
	}
	;

        //return this and init when ready
        return {
            init: init,
            setPresets: setPresets,
            displayDialog: displayDialog,
            clearDialog: clearDialog
        };  
    })();
    $(function () {
        KaikMedia.Gallery.plugin.init();
    });
}
)(jQuery);
