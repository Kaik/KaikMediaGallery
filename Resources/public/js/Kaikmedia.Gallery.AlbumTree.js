/**
 *Gallery album tree manager
 */

var KaikMedia = KaikMedia || {};
KaikMedia.Gallery = KaikMedia.Gallery || {};
KaikMedia.Gallery.AlbumTree = {};

( function($) {
 			
	KaikMedia.Gallery.AlbumTree.Tree = false;
	KaikMedia.Gallery.AlbumTree.albumBox = false;	
	KaikMedia.Gallery.AlbumTree.nodesDisabledForDrop = [];	

	
    KaikMedia.Gallery.AlbumTree.init = function ()
    { 
      // Tree & Box
       KaikMedia.Gallery.AlbumTree.Tree = $('#kmgallery_albumTree');   	
       KaikMedia.Gallery.AlbumTree.albumBox = $('#kmgallery_albumBox');
      
       KaikMedia.Gallery.AlbumTree.Tree.jstree({
          'core': {
        	  'data' : KaikMedia.Gallery.AlbumTree.TreeData,
              'multiple': false,
              'check_callback': function(operation, node, node_parent, node_position, more) {
                  if (operation === 'move_node') {
                      return ($.inArray($(node).attr('id'), KaikMedia.Gallery.AlbumTree.nodesDisabledForDrop) == -1);
                  }
                  return true; // allow all other operations
              }
          },
          'contextmenu': {
              'items':  KaikMedia.Gallery.AlbumTree.getContextMenuActions
          },
          'dnd': {
              'copy': false,
              'is_draggable': function(node) {
                  // disable drag and drop for root category
                  return ($(node).attr('id') != 'node_1');
              },
              'inside_pos': 'last'
          },
          'state': {
              'key': 'categoryTree'
          },
          'plugins': [ 'contextmenu', 'dnd', 'search', 'state', 'types' ],
          'types': {
              '#': {
                  // prevent unwanted drops on root
                  'max_children': 1
              },
              'default': {
                  'icon': 'fa fa-folder'
              }
          }
      });    
           
      // allow redirecting if a link has been clicked
      KaikMedia.Gallery.AlbumTree.Tree.on('click', 'li.jstree-node a', function(e) {
    	  var id = KaikMedia.Gallery.AlbumTree.Tree.jstree(true).get_node($(this)).id;
    	  	console.log(id);  
    	  KaikMedia.Gallery.AlbumTree.getAlbum(id);
      });
      
      // Drag & drop
      KaikMedia.Gallery.AlbumTree.Tree.on('move_node.jstree', KaikMedia.Gallery.AlbumTree.move);
           
    };
    
    KaikMedia.Gallery.AlbumTree.getContextMenuActions = function(node) {

    	if (node.id == 'node_1') {
            return {};
        }
    	
        var actions = {
            editItem: {
                label: /*Zikula.__(*/'Edit'/*)*/,
                action: function (obj) {
                	KaikMedia.Gallery.AlbumTree.performContextMenuAction(node, 'edit');
                },
                icon: 'fa fa-edit'
            },
            deleteItem: {
                label: /*Zikula.__(*/'Delete'/*)*/,
                action: function (obj) {
                    getCategoryDeleteMenuAction(node);
                },
                icon: 'fa fa-remove'
            },
            activateItem: {
                label: /*Zikula.__(*/'Activate'/*)*/,
                action: function (obj) {
                	KaikMedia.Gallery.AlbumTree.performContextMenuAction(node, 'activate');
                },
                icon: 'fa fa-check-square-o'
            },
            deactivateItem: {
                label: /*Zikula.__(*/'Deactivate'/*)*/,
                action: function (obj) {
                	KaikMedia.Gallery.AlbumTree.performContextMenuAction(node, 'deactivate');
                },
                icon: 'fa fa-square-o'
            },
            addItemAfter: {
                label: /*Zikula.__(*/'Add album (after selected)'/*)*/,
                action: function (obj) {
                	KaikMedia.Gallery.AlbumTree.performContextMenuAction(node, 'addafter');
                },
                icon: 'fa fa-level-up fa-rotate-90'
            },
            addItemInto: {
                label: /*Zikula.__(*/'Add album (into selected)'/*)*/,
                action: function (obj) {
                	KaikMedia.Gallery.AlbumTree.performContextMenuAction(node, 'addchild');
                },
                icon: 'fa fa-long-arrow-right'
            }
        };

        var currentNode = KaikMedia.Gallery.AlbumTree.Tree.jstree('get_node', node, true);
        // disable unwanted context menu items
        if (currentNode.closest('li').hasClass('z-tree-unactive') || currentNode.hasClass('z-tree-unactive')) {
            actions.deactivateItem._disabled = true;
        } else {
            actions.activateItem._disabled = true;
        }

        return actions;
    };
    
    KaikMedia.Gallery.AlbumTree.performContextMenuAction = function(node, action, extrainfo) {
    	
        var allowedActions = ['edit', 'delete', 'deleteandmovesubs', 'copy', 'activate', 'deactivate', 'addafter', 'addchild'];
        var parentId;
        if (!$.inArray(action, allowedActions) == -1) {
            return false;
        }

        var nodeId = $(node).attr('id');
        // append spinner
        $('#'+nodeId).find('a').first().after("<i id='temp-spinner' class='fa fa-circle-o-notch fa-spin'></i>");

        if (nodeId == 'node_1') {
            // do not allow editing of root category
            $('#temp-spinner').remove();
            return false;
        }
        
        var pars = {};
        
        switch (action) {
            case 'edit':
                pars.id = nodeId.replace('node_', '');
                break;
            case 'addafter':
                parentId = 	KaikMedia.Gallery.AlbumTree.Tree.jstree('get_parent', node);
                pars.parent = parentId.replace('node_', '');
                action = 'edit';
                break;
            case 'addchild':
                action = 'edit';
                pars.parent = nodeId.replace('node_', '');
                break;
        }

        $.ajax({
            type: "POST",
            url: Routing.generate('kaikmediagallerymodule_albumsajax_' + action, {'id': pars.id}),
            data: pars
        }).success(function(result) {
        	//console.log(result);
            var template = result.template;
            KaikMedia.Gallery.AlbumTree.albumBox.html(template);            
            $('#album_save').click(function(e){
            	e.preventDefault();
            	var form = $('form[name="album"]').serialize();	
            	//console.log(form)
                KaikMedia.Gallery.AlbumTree.saveAlbum(pars.id,form);
            })          
        }).error(function(result) {
            alert(result.status + ': ' + result.statusText);
        }).always(function() {
            $('#temp-spinner').remove();
            
        });

        return true;
    };   
    
	KaikMedia.Gallery.AlbumTree.getAlbum = function(id) {
		
		$('#'+id).find('a').first().after("<i id='temp-spinner' class='fa fa-circle-o-notch fa-spin'></i>");	
			
	    $.ajax({
	            type: "GET",
	            url: Routing.generate('kaikmediagallerymodule_albumsajax_getalbum',{id: id}),
	        }).success(function(result) {
	            var template = result.template;
	            KaikMedia.Gallery.AlbumTree.albumBox.html(template);
	        }).error(function(result) {
	            alert(result.status + ': ' + result.statusText);
	        }).always(function() {
	            $('#temp-spinner').remove();            
	        });				
	};
	
	KaikMedia.Gallery.AlbumTree.refresh = function() {
		
	    $.ajax({
	            type: "GET",
	            url: Routing.generate('kaikmediagallerymodule_albumsajax_refresh'),
	        }).success(function(result) {
	        		//console.log(result);
	        		KaikMedia.Gallery.AlbumTree.Tree.jstree(true).settings.core.data = result;
	        		KaikMedia.Gallery.AlbumTree.Tree.jstree(true).refresh();
	        }).error(function(result) {
	            alert(result.status + ': ' + result.statusText);
	        });							
	};    
    
    KaikMedia.Gallery.AlbumTree.saveAlbum = function(id, data) {

        $.ajax({
            type: "POST",
            url: Routing.generate('kaikmediagallerymodule_albumsajax_edit', {'id': id}),
            data: data
        }).success(function(result) {
        	//console.log(result);
            var template = result.template;
            KaikMedia.Gallery.AlbumTree.albumBox.html(template);
            $('#album_save').click(function(e){
            	e.preventDefault();
            	var form = $('form[name="album"]').serialize();	
            	//console.log(form)
                KaikMedia.Gallery.AlbumTree.saveAlbum(id,form);
            })             
            KaikMedia.Gallery.AlbumTree.refresh();
        }).error(function(result) {
            alert(result.status + ': ' + result.statusText);
        }).always(function() {
           $('#temp-spinner').remove();
            
        });

        return true;
    };  
    
    KaikMedia.Gallery.AlbumTree.move = function(event, data) {
        
    	var node = data.node;
      
        // do not allow inserts on forbidden leaf nodes
        if ($.inArray($(node).attr('id'), KaikMedia.Gallery.AlbumTree.nodesDisabledForDrop) > -1) {
            return false;
        }
        
        $('#1').find('a').first().after("<i id='temp-spinner' class='fa fa-circle-o-notch fa-spin'></i>");        
              
        $.ajax({
            type: "POST",
            url: Routing.generate('kaikmediagallerymodule_albumsajax_move', {'id': node.id}),
            data: {
                parent: node.parent
            }
        }).success(function(result) {
            var data = result.data;
            KaikMedia.Gallery.AlbumTree.refresh();
        }).error(function(result) {
            alert(result.status + ': ' + result.statusText);
            KaikMedia.Gallery.AlbumTree.refresh();            
        });
        
        return true;
    };    
           
    $(document).ready(function() {
        KaikMedia.Gallery.AlbumTree.init();
    });
})(jQuery);