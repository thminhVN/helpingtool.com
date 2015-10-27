define([
    'jquery',
    'underscore',
    'backbone',
    //'text!templates/form/form_add_admin.html'
    ], function($, _, Backbone,loginTemplate){
        var view = Backbone.View.extend({
            el: $(".media-section"),
            initialize: function(){
            },
            events: {
            	'click .create-tree-folder' : 'createNode',
            	'click .rename-tree-folder' : 'renameNode',
            	'click .remove-tree-folder' : 'removeNode',
            	'click #remove-images' : 'removeFile',
            	'click #apply-images' : 'applyFile',
            	'click #apply-ckeditor-images' : 'applyCkeditorFile',
            	'click #preview-images' : 'previewFile',
            	'click #detail-tab' : 'selectNode'
            },
            selectNode: function(e){
            	if($($('#detail-tab').attr('href')).hasClass('active')) return false;
            	var tree = $('#media_tree').jstree(true);
            	tree.refresh();
            },
            previewFile: function(e){
            	$("body").append('<ul id="preview" class="hidden"></ul>');
            	$images = $("li.ui-selected");
    			$images.each(function(index, value){
    				$this = $(this)
    				$path = "/"+$this.find('img').data('realpath');
    				$title = $this.find('img').data('file');
    				$("ul#preview").append('<a class="image-preview" href="'+$path+'" title="'+$title+'">');
    			});
    			$(".image-preview").colorbox({
    				rel:'image-preview',
    				open:true,
    				onClosed:function(){ 
    					$("ul#preview").remove();
    				},
    			});
            },
            createNode: function(e){
            	var ref = $('#media_tree').jstree(true);
            	var sel = ref.get_selected();
            	if(!sel.length) return false;
            	ref.create_node(sel[0], {"type":"default"});
            },
            renameNode: function(e){
            	var ref = $('#media_tree').jstree(true);
				var sel = ref.get_selected();
				ref.edit(sel);
            },
            removeNode: function(e){
            	bootbox.confirm("Are you sure?", function(result) {
        			if(result) {
        				var ref = $('#media_tree').jstree(true);
    					var sel = ref.get_selected();
    					if(!sel.length) { return false; }
    					$.ajax({
        					type: 'POST',
        					url: '/admin/media/deletefolder',
        					data: {
        						id: sel[0]
        					},
        					success: function(respone){
        						if(respone.result){
        							ref.delete_node(sel[0]);
        						} else {
        							bootbox.alert('<h3 class="text-danger">'+respone.message+'</h3>', function() {});
        						}
        					}
        				});
    				}
        		}); 
            },
            removeFile: function(e){
            	var sel_img = $("li.ui-selected");
        		var images = [];
        		if(sel_img.length == 0) return false;
        		bootbox.confirm("Are you sure?", function(result) {
        			if(result)
    				{
	            		sel_img.each(function(index,value){
	            			var $this = $(this);
	            			var fileName = $this.find("img").data("file");
	            			var data = {
            					path: encodeURIComponent($("#folder-path").text()),
	            				file: fileName
	            			};
	            			$.ajax({
		            			type: "DELETE",
		            			url: "/admin/media/delete?"+$.param(data),
		            			dataType: 'json',
		            			beforeSend: function(){
		            			},
		            			success: function(respone){
		            				if(respone[fileName]){
		            					$this.remove();
	        						} else {
	        							bootbox.alert('<h3 class="text-danger">Fail</h3>', function() {});
	        						}
		            			},
		            			complete: function(){
		            			},
		            		});
	            		});
    				}
        		});
            },
            applyFile: function(e){
            	var target = $("#target_element").val();
        		target = decodeURIComponent(target);
        		target = target.replace(/\\/g, '');
        		target = $(target, opener.document);
        		var sel_img = $("#selectable > li.ui-selected");
        		var images = [];
        		if(target.hasClass("sortable")) {
        			sel_img.each(function(index,value){
        				var src = $(this).find("img").attr("src");
        				target.append('<li class="ui-state-default image-view-item"><img src="'+src+'" class="thumbnail"><input type="hidden" name="data" /><button type="button" class="btn btn-sm btn-success edit-image">Change</button></li>');
        			});
        		} else {
        			var value = target.val();
    				try {
    					images = $.parseJSON(value);
    				} catch(e) {
    					
    				}
        			
        			if(sel_img.length == 0) {return false;}
        			
        			sel_img.each(function(index,value){
        				var src = $(this).find("img").attr("src");
        				src = src.replace("thumbnail\/","");
        				images.push(src);
        			});
        			var result = '';
        			
        			if($("#element_type").val() == "single") {
        				result = images[images.length-1];
        			} else {
        				result = JSON.stringify(images);		
        			}
        			target.val(result).trigger('change');
        			window.close();
        		}
            },
            applyCkeditorFile: function(e){
            	var element, dialog;
        		var img_url = $("li.ui-selected").eq(0).find("img").attr("src");
        		img_url = img_url.replace("thumbnail\/","");
        		dialog = window.opener.CKEDITOR.dialog.getCurrent();	
        		element = dialog.getContentElement( 'info', 'txtUrl' );		
        		element.setValue( img_url );
        		window.close();	
            },
            index: function(){
            	$("#selectable" ).selectable();
            	$("#media_tree").jstree({
            		"plugins" : [ "search", "state","types"],
            		'core' : {
            			"animation" : 0,
            			"check_callback" : true,
            			'data' : {
            			    'url' : function (node) {
            			      return '/admin/media/browserfolder';
            			    },
            			    'data' : function (node) {
            			      return { 'id' : node.id };
            			    }
            			}
            		}
        		})
        		
        		.on('select_node.jstree',function(e, data){
        			console.log('changed')
        			var tree = $('#media_tree').jstree(true);
					var sel = tree.get_selected();
					if(!sel.length) return false; 
					var path = sel[0].replace(/\./g,'/');
					$('[name="path"]').val(path);
					$("#folder-path").text(path);
					$.ajax ({
						type: 'POST',
        				url: '/admin/media/browserfile',
        				data: {
        					id: sel[0],
        				},
        				success: function(respone){
        					if(respone.result) {
								addImageItemToView(respone.file);
        					} else {
        						bootbox.alert('<h3 class="text-danger">'+respone.message+'</h3>', function() {});
        						tree.delete_node(data.node.id);
    						}
        				}
			        });
        		})
        		
        		.on('create_node.jstree',function(e, data){
        			var tree = $('#media_tree').jstree(true);
        			var selected_node = tree.get_selected();
        			tree.open_node(selected_node);
        			$.ajax({
        				type: 'POST',
        				url: '/admin/media/createfolder',
        				data: {
        					id: data.parent,
        					text: data.node.original.text
        				},
        				success: function(respone){
        					if(respone.result){
        						tree.refresh();
        					} else {
        						bootbox.alert('<h3 class="text-danger">'+respone.message+'</h3>', function() {});
        						tree.delete_node(data.node.id);
    						}
        				}
        			});
        			
        		}).on('rename_node.jstree', function(e,data){
        			var tree = $('#media_tree').jstree(true);
        			if(data.text == data.old) return false;
        			var patt = new RegExp("\W*");
        			if(patt.test(data.text)) {
        				bootbox.alert('<h3 class="text-danger">Folder mustn\'t contain special character</h3>', function(){});
        				tree.refresh();
        				return false;
        			}
    				$.ajax({
    					type: 'POST',
    					url: '/admin/media/renamefolder',
    					data: {
    						id: data.node.id,
    						text: data.text,
    						old: data.old
    					},
    					success: function(respone){
    						if(respone.result) {
    							tree.refresh();
    						} else {
    							bootbox.alert('<h3 class="text-danger">'+respone.message+'</h3>', function() {});
    						}
    					}
    				});
        		});
            	//validate upload file
            	$('#fileupload').fileupload({
        		    add: function (e, data) {
    		    	    var input = $('[name="path"]').val();
    		  	    	if (input == "") {
    		  	    		bootbox.alert('<h3 class="text-success">Please choose folder to upload</h3>', function() {});
		  	    		} else {
    		  	    		$.blueimp.fileupload.prototype.options.add.call(this, e, data);
    	  	    		}
        		    } 
        		});
            },
            render: function() {
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
                
            }
        });
        return view = new view;
});