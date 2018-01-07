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
        ;
	 // Init
        function init()
        {
            console.log('Gallery init');
	    
	    $('.upload-action').click(function (e) {
		e.preventDefault();
		readSettings($(this));
		handleUpload();
	    });
	    
	    $('.re-upload-action').click(function (e) {
		e.preventDefault();
		readSettings($(this));
		handleChange();
	    });
	    
	    $('.remove-action').click(function (e) {
		e.preventDefault();
		readSettings($(this));
		handleRemove();
	    });
        }
        ;

	function readSettings($this) {
	    settings.containerId = $this.data('containerid');
	    settings.$container = $('#' + settings.containerId);
	    settings.feature = $this.data('feature');
	    settings.handler = $this.data('handler');
	    settings.prefix = $this.data('prefix');
	    settings.dir = $this.data('dir');
	    settings.uploadLimit = $this.data('singlefilemaxsize');
	    settings.accept = $this.data('allowedMimeTypes');
//	    console.log(settings);
	}
	;

	function handleUpload() {
	    hideError();
	    hideProgress();
	    fileDialog({accept: settings.accept})
		.then(file => {
		    checkFile(file[0])
			.fail(displayError)
			.done(previewFile)
			.done(function(f) {
//			    console.log(f);
			    var form = new FormData();
			    form.append('file', f);
			    form.append('prefix', settings.prefix);
			    form.append('dir', settings.dir);
			    showProgress();
			    doAjax(Routing.generate('kaikmediagallerymodule_media_create', {"type": 'image', "_format": 'json'}), form)
				.done(function(data) {
				    showRelationData();
				    settings.$container.find('.relation-data-media').val(data.media_id);
				    settings.$container.find('.remove-action').removeClass('hide');
				    settings.$container.find('.re-upload-action').removeClass('hide');
				    settings.$container.find('.upload-action').addClass('hide');
				    setProgressType('progress-bar-success');
				    removeProgress();
				})
				.fail(displayError) 
				.progress(updateProgress)
			    ;
			    
			});
			
	    });
	}
	;

	function handleRemove() {
	    var form = new FormData();
	    form.append('media_relation', settings.$container.find('.relation-data-relation').val());
	    form.append('media_item', settings.$container.find('.relation-data-media').val());
	    showProgress();
	    doAjax(Routing.generate('kaikmediagallerymodule_media_remove', {"_format": 'json'}), form)
		.done(function(data) {
		    settings.$container.find('.relation-data-media').val('');
		    settings.$container.find('.relation-data-relation').val('');
		    hideRelationData();
		    clearRelationData();
		    settings.$container.find('img').attr("src", "").addClass('hide');
		    settings.$container.find('.remove-action').addClass('hide'); //$this...
		    settings.$container.find('.re-upload-action').addClass('hide');
		    settings.$container.find('.upload-action').removeClass('hide');
		    removeProgress();
		})
		.fail(displayError) 
		.progress(updateProgress)
	    ;
	}
	;

	function handleChange() {
	    console.log('change');
	    hideError();
	    hideProgress();
	    fileDialog({accept: settings.accept})
		.then(file => {
		    checkFile(file[0])
			.fail(displayError)
			.done(previewFile)
			.done(function(f) {
			    var form = new FormData();
			    form.append('file', f);
			    form.append('prefix', settings.prefix);
			    form.append('dir', settings.dir);
			    form.append('media_relation', settings.$container.find('.relation-data-relation').val());
			    form.append('media_item', settings.$container.find('.relation-data-media').val());
			    showProgress();
			    doAjax(Routing.generate('kaikmediagallerymodule_media_replace', {"type": 'image', "_format": 'json'}), form)
				.done(function(data) {
				    showRelationData();
				    settings.$container.find('.relation-data-media').val(data.media_id);
				    settings.$container.find('.remove-action').removeClass('hide');
				    settings.$container.find('.re-upload-action').removeClass('hide');
				    settings.$container.find('.upload-action').addClass('hide');
				    setProgressType('progress-bar-success');
				    removeProgress();
				})
				.fail(displayError) 
				.progress(updateProgress)
			    ;
			    
			});
	    });
	}
	;

	function previewFile(f) {
	    // Only process image files.
	    if (!f.type.match('image.*')) {
		return;
	    }

	    var reader = new FileReader();

	    // Closure to capture the file information.
	    reader.onload = (function (theFile) {
		return function (e) {
		    displayPreview(e.target.result);
		};
	    })(f);

	    // Read in the image file as a data URL.
	    reader.readAsDataURL(f);
	}
	;
	
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
	    settings.$container.find("input[name*='relationExtra']").removeClass('hide');	    
	}
	;
	
	function hideRelationData() {
	    settings.$container.find("input[name*='relationExtra']").addClass('hide');    
	}
	;
	
	function clearRelationData() {
	    settings.$container.find("input[name*='relationExtra']").val('');	    
	}
	;
	
	function displayPreview(src) {
	    settings.$container.find('img').attr("src", src).removeClass('hide');
	}
	;
	
	function displayError(error) {
	    settings.$container.find('img').addClass('hide');
	    settings.$container.find('.error-message').html(error).removeClass('hide');
	}
	;
	
	function hideError() {
	    settings.$container.find('.error-message').addClass('hide');
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
	
        //return this and init when ready
        return {
            init: init
        };
    })();
    $(function () {
        KaikMedia.Gallery.plugin.init();
    });
}
)(jQuery);
