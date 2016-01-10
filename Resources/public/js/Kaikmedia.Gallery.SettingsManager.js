/**
 *Gallery settings
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};
KaikMedia.Gallery.SettingsManager  = KaikMedia.Gallery.SettingsManager  || {};

/**
 * Inbox manager
 */

(function (manager, settings, $, undefined) {
    /*
     * manager properties 
     */
    manager.busy = false;
    /*
     * manager init 
     */
    manager.init = function () {
        // view as singelton init
        manager.view = view.getInstance();
        //load data true ajax false view
        manager.loadData(false);
        console.log('KaikMedia.Gallery.SettingsManager initiated');
    };
    /*
     * manager config  
     */
    manager.loadData = function (useAjax) {
        if (useAjax) {
        } else {
            manager.view.getDataFromView();
        }
    };
    /*
     * manager 
     */

    manager.load = function (url) {
        manager.busy = true;
        manager.view.showBusy();
        
        $.ajax({
            type: "GET",
            dataType: "json",
            url: url
        }).success(function (result) {
            manager.busy = false;
            manager.view.hideBusy();
        }).error(function (result) {

        }).always(function () {
            manager.view.hideBusy();
        });
    };
   
    /*
     * manager.view
     */
    var view = (function () {

        // Instance stores a reference to the Singleton
        var instance;
        function Init() {
            /*
             * manager.view properties
             */
            var $manager = $('#km_gallery_admin_settings');
            
            /*
             * manager.view init
             */
            //start listening for actions
            bindViewEvents();
            console.log('KaikMedia.Gallery.SettingsManager.view initialised');
            /*
             * manager.view functions 
             */
            function bindViewEvents() {
                /* bind header events  */
                $manager.find('div.module-switch').each(function () {
                    $(this).find('input').on('change', function () {
                        if (manager.busy) {
                            return false;
                        }
                        if ($(this).val() === 1) {
                            $(this).parent().parent().find('button').prop("disabled", (_, val) => !val);   
                        }else{
                            $(this).parent().parent().find('button').prop("disabled", (_, val) => !val);                        
                        }
                    });
                });
                $manager.find('div.feature-switch').each(function () {
                    $(this).find('input').on('change', function () {
                        if (manager.busy) {
                            return false;
                        }
                        if ($(this).val() === 1) {
                            $(this).parent().parent().find('button').prop("disabled", (_, val) => !val);   
                        }else{
                            $(this).parent().parent().find('button').prop("disabled", (_, val) => !val);                        
                        }
                    });
                });               

                //console.log('Zikula.Languages.Manager.view events binded');
            }

            /*
             * manager.view functions 
             * Data
             */
            function getDataFromView() {

            }

            //overlay
            function getOverlay() {
                return $("<div id='overlay'><i class='fa fa-circle-o-notch fa-spin fa-5x'></i></div>");
            }
            
            //busy
            function showBusy() {
                $manager.find('#conversations').prepend(getOverlay());
            }
            function hideBusy() {
                $('#overlay').remove();
            }
            //errors
            function displayError(html) {

            }
            ;
            /*
             * manager.view public
             */
            return {
                getDataFromView: getDataFromView,
                showBusy: showBusy,
                hideBusy: hideBusy,
                displayError: displayError
            };
        }
        ;
        return {
            // Get the Singleton instance if one exists
            // or create one if it doesn't
            getInstance: function () {
                if (!instance) {
                    instance = Init();
                }
                return instance;
            }
        };
    })();
}(KaikMedia.Gallery.SettingsManager, KaikMedia.Gallery.settings, jQuery));