/**
 *Gallery manager
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};

KaikMedia.Gallery.Manager = KaikMedia.Gallery.Manager || {};

/*
 * manager  
 * 
 */
(function (manager, $, undefined) {

    /*
     * manager properties 
     *
     */

    //Public Property
    manager.upload = [];
    manager.mediaTypes = [];
    manager.obj = false;
    manager.functionality = [];
    manager.features = {};
    manager.origins = {};
    manager.insert = [];
    manager.library = [];
    manager.selected = [];
    manager.current = {};

    /*
     * manager functions  
     *         //init view
     //manager.view.init(config.$container.find('#kmgallery_manager'));    	
     //load data true ajax false view
     //switch origin to defautl
     //manager.view.switchOrigin(manager.current.origin.name);     
     */
    manager.init = function () {
        
        console.log(KaikMedia);
        
        console.log(KaikMedia.Gallery.settings.getObject());
        /*
        //add vars from config
        manager.obj = config.obj;
        // view as singelton
        manager.mediaTypes = config.mediaTypes;
        manager.functionality = config.features;
        manager.addmedia = config.addmedia;
        manager.upload = config.addmedia[0];
        manager.view = view.getInstance(config.$container.find('#kmgallery_manager'));
        loadData(true);
        */
    };

    /*
     * manager config  
     * 
     */
    function loadData(useAjax) {

        if (useAjax) {
            $.ajax({
                type: "GET",
                url: Routing.generate('kaikmediagallerymodule_manager_load', {"_format": 'json'})
            }).success(function (result) {
                var mediaArray = result.media;
                $.each(mediaArray, function (index, mediaItemData) {
                        var mediaItem = new KaikMedia.Gallery.model.mediaItem(); 
                        mediaItem.setMediaItemData(mediaItemData);
                        manager.library.push(mediaItem);
            });
            manager.view.refreshLibrary();
            //console.log(manager);
            }).error(function (result) {
                //console.log(result);
                //view.setProgressType('progress-bar-danger');
                //manager.view.displayError(result.status + ': ' + result.statusText);
            }).always(function () {
                //console.log('always');
                //manager.view.hideBusy();           
            });

            
        } else {
            //console.log('load data from view');
            // manager.view.getDataFromView();
        }

        getEnabledFeaturesAndOrigins();
        //console.log(manager);
        manager.current = {feature: getDefaultFeature(),
                            origin: getDefaultOrigin()
        };
        
        manager.view.switchOrigin(manager.current.origin.name);  
    }
    ;
    //     
    function getEnabledFeaturesAndOrigins() {
        /* set origins according to settings */
        $.each(manager.functionality, function (feature_key, feature) {   
            if (feature.type === 'origin' && feature.enabled === 1) {
                manager.origins[feature.name] = feature;
            }
            ;
            if (feature.type === 'feature' && feature.enabled === 1) {
                manager.features[feature.name] = feature;
            }
            ;
        });
        
                console.log(manager);
    }

    function getDefaultOrigin() {
        /* set origins according to settings */
        if (manager.origins.user.enabled) {
            return manager.origins.user;
        } else if (manager.origins.public.enabled) {
            return manager.origins.public;
        } else if (manager.origins.addmedia.enabled) {
            return manager.origins.addmedia;
        }
    }

    function getDefaultFeature() {
        return {name: 'info', settings: false, selected: false};
    }


    function getSelectedByFeature(feature_name) {
        //use $.filter
        return manager.selected.filter(function (item) {
            return (item.type === feature_name);
        });
    }

    manager.switchFeature = function (feature_name) {
        var feature_name = feature_name;

        if ('info' === feature_name) {
            manager.current.feature = getDefaultFeature();

        } else {
            $.each(manager.features, function (index, feature) {
                if (feature.name === feature_name) {
                    manager.current.feature = {name: feature.name,
                        settings: feature,
                        selected: getSelectedByFeature(feature_name)
                    };
                }
                ;
            });
        }
        ;

        manager.view.switchFeature();
    };

    manager.switchOrigin = function (origin_name) {
        var origin_name = origin_name;
        /* set origins according to settings */
        $.each(manager.origins, function (index, origin) {
            if (origin.name === origin_name) {
                manager.current.origin = origin;
            }
            ;
        });
        manager.view.switchOrigin(manager.current.origin.name);
    };

    manager.prepareForUpload = function (f) {
        // add media type info.
        f.mediaType = manager.mediaTypes[f.type];
        // add preupload validation info.        
        f.error = false;
        if (f.size > manager.upload.uploadMaxSingleSize) {
            f.error = 'File is too big. Maximum single file size is ' + manager.upload.uploadMaxSingleSize;
        }   
        
        return f;
    };

    /*
     * manager.view
     * 
     */
    var view = (function () {

        // Instance stores a reference to the Singleton
        var instance;

        function Init($modal) {

            /*
             * manager.view properties
             * 
             */
            //container
            var $modal = $modal;
            //features menu container
            var $features_menu = $modal.find('#kmgallery_manager_features');
            //origins menu container
            var $origins_menu = $modal.find('#kmgallery_manager_origins');
            //details container
            var $details_box = $modal.find('#kmgallery_manager_details');
            // msg box
            var $msg_box = $modal.find('#kmgallery_manager_msg_box');
            var $library_box = $modal.find('#kmgallery_manager_library_box');
            var $upload_preview = $modal.find('#upload_preview');
            /*
             * manager.view init
             */
            //start listening for actions
            bindViewEvents();

            //console.log(itemTemplate())
            /*
             * manager.view functions 
             * 
             */
            function bindViewEvents() {

                /* bind mode switch */

                $('#select-files').click(function () {
                    $('#addmedia_form_files').click();
                    return false;
                });

                $('#addmedia_form_files').on('change', function (e) {
                    handleFiles(e);
                });

                // Setup the dnd listeners.
                $('#drop_zone').bind({
                    "dragover": handleDragOver,
                    "dragleave": handleDragOver,
                    "drop": handleFiles
                });

                /* bind mode switch 
                 $features_menu.find('a').each(function () {
                 $(this).on('click', function (e) {
                 manager.switchFeature($(this).attr('data-feature'));
                 });
                 });
                 */
                /* bind mode switch */
                 $origins_menu.find('a').each(function () {
                 $(this).on('click', function (e) {
                 manager.switchOrigin($(this).attr('data-origin'));
                 });
                 });
                
                /* item hover action over selected icon - unselect   
                 $modal.find('a.media-unselect').each(function () {
                 $(this).hover(
                 function () {
                 $(this).children('.media-icon').removeClass('fa-check-circle').addClass("fa-minus");
                 },
                 function () {
                 $(this).children('.media-icon').removeClass('fa-minus').addClass("fa-check-circle");
                 }
                 );
                 $(this).on('click', function (e) {
                 manager.itemUnSelect(getItemDataFromElement($(this).parent()));
                 });
                 });
                 */
                /* media preview 
                 $modal.find('div.item-details').each(function () {
                 $(this).on('click', function (e) {
                 var item = getItemDataFromElement($(this).parent());
                 manager.itemGetDetails(item);
                 $details_box.find('.item-details-edit-box').append(getOverlay());
                 manager.itemEdit(item);
                 });
                 });
                 */
                /* media add to current selection
                 $modal.find('div.item-select').each(function () {
                 $(this).on('click', function (e) {
                 //manager.itemSelect($(this).parent());  				    	 
                 });
                 });  */
            }

            /*
             * manager.view functions 
             * Data
            
            function getDataFromView() {

                var $origins_box = $modal.find('#kmgallery_manager_media_box');
                $origins_box.find('div.media-item').each(function () {              
                    var mediaItem = new KaikMedia.Gallery.model.mediaItem(); 
                        mediaItem.getItemFromElement($(this));
                        manager.library.push(mediaItem);
                });
                
                //console.log(manager);
               
                var $features_box = $modal.find('#kmgallery_manager_selected');
                $features_box.find('div.media-item').each(function () {
                    manager.selected.push(getItemDataFromElement($(this)));
                });
               
            }
            */
            //
            function getSelectedFromView() {

            }

            //origins and features
            function switchFeature() {
                libraryCleanSelection();
                if (manager.current.feature.selected !== false) {
                    $.each(manager.current.feature.selected, function (index, item) {
                        itemSelect(item);
                    });
                }
                ;
            }
            //
            function switchOrigin(origin) {
                //deactive tab
                $origins_menu.find('.origins').removeClass('active');
                $('#kmgallery_manager_origins_' + origin + '_pill').parent('.origins').addClass('active');
                //var $library_box = $modal.find('#kmgallery_manager_media_box');
                //$library_box.find('li').addClass('hide');
                //$library_box.find('li.origin-' + origin).removeClass('hide');
                //enable tab     	
                $('#kmgallery_manager_media_box').addClass('active');
                console.log('origin swiched');
            }

            function handleDragOver(e) {
                e.stopPropagation();
                e.preventDefault();
                e.target.className = (e.type === "dragover" ? "hover" : "");
            }

            function handleFiles(e) {
                e.stopPropagation();
                e.preventDefault();
                var dt = e.dataTransfer || (e.originalEvent && e.originalEvent.dataTransfer);
                var files = e.target.files || (dt && dt.files);
                if (files) {
                    for (var i = 0, f; f = files[i]; i++) {
                        var mediaItem = new KaikMedia.Gallery.model.mediaItem(); 
                        mediaItem.setDatafromUpload(manager.prepareForUpload(f));
                        $upload_preview.append(mediaItem.view.render());
                        manager.library.push(mediaItem);
                        //mediaItem.upload();
                    }
                } else {
                    // Perhaps some kind of message here
                }
                switchOrigin('user');
                manager.view.refreshLibrary();
            }



            //Item
            function libraryCleanSelection() {
                /* set origins according to settings */
                var $library_box = $modal.find('#kmgallery_manager_media_box ul');
                $library_box.find('li').each(function () {
                    itemUnSelect(getItemDataFromElement($(this)));
                });

            }
            
            //Item
            function refreshLibrary() {
                /* set origins according to settings */
                $.each(manager.library, function (index, mediaItem) {
                          $library_box.append(mediaItem.view.render());
                    });
            }            

            //modal
            function Open() {
                $modal.modal('show');
            }
            //
            function Close() {
                $modal.modal('hide');
            }
            function Close() {
                $modal.modal('hide');
            }

            //
            function getOverlay() {
                return $("<div id='overlay'><i class='fa fa-circle-o-notch fa-spin'></i></div>");
            }

            //
            function removeOverlay() {
                $('#overlay').remove();
            }

            //
            function showBusy() {
                $('#kmgallery_manager').append(getOverlay());
            }

            //
            function hideBusy() {
                $('#overlay').remove();
            }

            function displayError(html) {
                $msg_box.html('<div class="alert"><a class="close" data-dismiss="alert">×</a><span>' + html + '</span></div>');
                $msg_box.find('.alert').addClass('alert-danger');
            }
            ;

            /*
             * manager.view public
             * 
             */
            return {
                open: Open,
                close: Close,
                refreshLibrary : refreshLibrary,
                switchOrigin: switchOrigin,
                switchFeature: switchFeature,
                showBusy: showBusy,
                hideBusy: hideBusy,
                displayError: displayError
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

}(KaikMedia.Gallery.Manager, jQuery));
