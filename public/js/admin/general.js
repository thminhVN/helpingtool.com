function createTable(id, url){
	var table = $("#" + id);
	var bFilter = table.data("bfilter") || false;
	var iDisplayLength = table.data("idisplaylength") || 25;
	var sPaginationType = table.data("spaginationtype") || "full_numbers";
	var bProcessing = table.data("bprocessing") || true;
	var bServerSide = table.data("bServerSide") || true;
	var aoColumns = new Array();
	var fn;
	var disableSort = [0];
	var sort_columns = new Array();
	var createdRow = new Array();
	var join_tables = new Array();
	url = url || "/admin/" + id + "/paging";

	$(table).find("th").each(function(index, value){
		tmp_object = {};
		tmp_join = {};
		if($(this).data("mdata") != undefined && $(this).data("mdata") != ""){
			tmp_object.mData = $(this).data("mdata");
		}
		if($(this).data("swidth") != undefined && $(this).data("swidth") != ""){
			tmp_object.sWidth = $(this).data("swidth");
		}
		if($(this).data("sclass") != undefined && $(this).data("sclass") != ""){
			tmp_object.sClass = $(this).data("sclass");
		}
		if($(this).data("fnrender") != undefined && $(this).data("fnrender") != ""){
			tmp_object.sDefaultContent = "";
			fn = window[$(this).data("fnrender")];
			tmp_object.render = fn;
		}
		aoColumns.push(tmp_object);
		sort_columns[index] = $(this).data("sort-column") || "o." + $(this).data('mdata');
	});

	var oTable = table.dataTable({
		buttons: [
	          'selectAll',
	          'selectNone'
	      ],
	    "scrollX": true,
	    "bFilter": bFilter,
	    "aLengthMenu": [50,100,500,1000 ],
	    "aaSorting": [[ 1, "desc" ]],  
	    "aoColumnDefs" : [
	        {
	            "bSortable" : false,
	            "aTargets" : disableSort
	        }
	    ],                                    
	    "iDisplayLength": iDisplayLength,
	    "sPaginationType": sPaginationType,
	    "bProcessing": bProcessing,
	    "bServerSide": bServerSide,
	    "sAjaxSource": url,
	    "aoColumns": aoColumns,
	       
	    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
	        // excute before row render;
	    	for(var $i=0; $i < createdRow.length; $i++)
    		{
	    		if(aData[createdRow[$i].mdata] == createdRow[$i].condition)
    			{
	    			$(nRow).addClass(createdRow[$i].sclass);
    			}
    		}
	    },
	    "fnDrawCallback": function( nRow, aData, iDataIndex ) {
	        // excute before row render and every time when we change state(load dynamic data)
	    	if($('.selectpicker').length > 0) {
	    		$('.selectpicker').selectpicker();
	    	}
	    	if($('input.switch').length > 0) {
	    		$('input.switch').bootstrapSwitch({
	    			'onColor' : 'success',
	    			'offColor' : 'danger',
	    			'onSwitchChange' : function(event, state){
	    				switchFunction(event, state);
	    			}
	    		});
	    	}
	       
	    },
	    "fnInitComplete": function( nRow, aData, iDataIndex ) {
	        // excute after row render and just the first time table init completed
	    	if($('.selectpicker').length > 0) {
	    		$('.selectpicker').selectpicker();
	    	}
	    	if($('input.switch').length > 0) {
	    		$('input.switch').bootstrapSwitch({
	    			'onColor' : 'success',
	    			'offColor' : 'danger',
	    			'onSwitchChange' : function(event, state){
	    				switchFunction(event, state);
	    			}
	    		});
	    	}
	    },
	    "fnServerParams": function ( aoData ) {
	    	if(join_tables.length > 0){
	    		aoData.push({name: "join_tables", value: JSON.stringify(join_tables)});
	    	}
	    	aoData.push({name: "sort_columns", value: sort_columns});
	        var $data = $("form#filter-form").serializeArray();
	        aoData = $.merge(aoData, $data);
	    }       
	});
	return oTable;
}
function renderSetting(data, type, row, meta){
	$controller = $("#controller").val();
	var controls = '<div class="btn-group">';
    controls += '<a type="button" title="edit" href="/admin/' + $controller + '/edit/' + row.id + '" class="btn btn-warning btn-sm glyphicon glyphicon-pencil"></a>';
    controls += '<button type="button" title="delete" data-controller="' + $controller + '" data-id="' + row.id + '" class="btn btn-danger btn-sm glyphicon glyphicon-remove remove"></button>';
    controls += '</div>';
    return controls;
}
function renderFirstColumn(data, type, row, meta){
    return '<input type="checkbox" class="record" value="' + row.id + '">';
}
function renderSelect(data, type, row, meta){
	var property = $("thead th").eq(meta.col).data('mdata');
	$controller = $("#controller").val();
    var controls = '<select class="form-control select-to-change-attr input-sm" data-attribute="' + property + '" data-controller="' + $controller + '" data-id="' + row.id + '">';
    var options = row['option_'+property];
    var selected;
    for(var i in options){
        selected = "";
        if(i == data){
            selected = " selected";
        }
        controls += '<option value="' + i + '"' + selected + '>' + options[i] + '</option>';
    }
    controls += '</select>';
    return controls;
}
function renderDatetime(data, type, row, meta){
	var date = moment(data.date);
    return date.format('MM-DD-YYYY HH:mm:ss');
}

function renderOrder(data, type, row, meta){
	$("#change-order").removeClass('hidden');
	return '<input type="text" class="order form-control" data-id="'+row.id+'" data-old="'+data+'" value="'+data+'">';
}

function setAttribute($controller, $id, $attribute, $value) {
	$.ajax({
		type: 'post',
		url: "/admin/" + $controller + "/" + $attribute,
		data : {
			id: $id,
			attribute: $attribute,
			value: $value
		},
		beforeSending:  function() {
			$("#loading").show();
		}, 
		success: function($response) {
			$class = 'text-danger';
			if($response.success) {
				$class = 'text-success';
			}
			bootbox.alert('<h3 class="' + $class + '">' + $response.message + '</h3', function(){})
		},
		complete: function() {
			$("#loading").hide();
		}
	})
}

function addImageItemToView(files)
{
	var grid_view = $('#selectable');
	var title_name;
	grid_view.empty();
	if(files.length == 0){
		grid_view.html('<h3 class="text-warning">No file in this folder</h3>');
	} else {	
		for(var i=0;i < files.length; i++) {
			file_extension = getExtensionFile(files[i]);
			var real_path = $('#folder-path').text() + "/" + files[i];
			var src_img = $('#folder-path').text() + "/thumbnail/" + files[i];
			if($.inArray(file_extension, ['jpg', 'jpeg', 'png', 'gif']) == -1){
				src_img = 'img/admin/file.png';
			}
			var html = '<li class="ui-state-default image-view-item">';
				html+=  '<img title="'+ files[i] +'" src="/'+src_img+'" data-file="'+files[i]+'" data-realpath="'+real_path+'">';
				html+= '</li>'
			grid_view.append(html);
		}
	}
}
// get extension file
function getExtensionFile(filename){
	return filename.substr((~-filename.lastIndexOf(".") >>> 0) + 2);
}

$(function() {
    $('#side-menu').metisMenu();
    $(document).on('click', '#filter-form .dropdown-menu li', function(e){
    	e.stopPropagation();
    });
    
    //Loads the correct sidebar on window load,
    //collapses the sidebar on window resize.
	// Sets the min-height of #page-wrapper to window size
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    $(document).on('click', '.click-to-change-attr', function(){
    	$target = $(this);
    	if($target[0].nodeName == 'LABEL') {
    		$this = $target.find('input');
    	} else {
    		$this = $target;
    	}
    	setAttribute($this.data('controller'), $this.data('id'), $this.data('attribute'), $this.data('value'));
    });

    $(document).on('change', '.select-to-change-attr', function(){
    	$this = $(this);
    	setAttribute($this.data('controller'), $this.data('id'), $this.data('attribute'), $this.val());
    });

    $("#filter-form").on('submit', function(e){
    	e.preventDefault();
        var dataTable = $("#" + $(this).data('controller')).dataTable();
        dataTable.fnDraw();
    })

    //select all
	$('#select-all').on('click',function(){
		var checkboxes = $('input[type="checkbox"].record');
	    if($(this).prop('checked')) {
	      checkboxes.prop('checked', true); 
	    } else {
	      checkboxes.prop('checked', false);
	    }
	});
    
    $(document).on('click','.remove, #multi-delete',function(){
		var $this = $(this);
		var $message = '';
		var $controller = $this.data('controller');
		var $ids = [];
		if($this.attr('id') == "multi-delete"){
			$('input[type="checkbox"].record:checked').each(function(index, value){
				$ids.push($(this).val());
			});//	   					
			$message = 'selected ' + $controller;
		} else {
			$ids.push($this.data('id'));
			$message = $this.data('title') || 'this ' + $controller;
		}
		if($ids.length <= 0) return false;
		bootbox.confirm('<h3 class="text-warning">Do you want to remove <span class="text-danger">' + $message + "</span> ?</h3>", function(result){
			if(result){
				$.ajax({ 
					type: 'POST',
					cache: false,
					data: {
						ids:$ids
					},
					url:'/admin/'+$controller+'/remove',
					beforeSend: function(){
						bootbox.alert('<h3 class="text-info">Loading...</h3>', function() {});
					},
					success: function(){ 
						$('.dataTable').dataTable().fnDraw();
					},
					failure: function(){
						alert('wrong');
					},
					complete: function(){
						bootbox.hideAll();
					}
				});	
			}
		});
	});
    
    $(document).on('click','#change-order',function(){
    	var $this = $(this);
    	var $data = [];
    	var $controller = $("#controller").val();
		$('input[type="text"].order').each(function(index, value){
			if($(this).val() != $(this).data('old'))
			{
				var $tmp={};
				$tmp['id'] = $(this).data('id');
				$tmp['order'] = $(this).val();
				$data.push($tmp);
			}
		});
		if($data.length <= 0 ){return false;}
   		bootbox.confirm('<h3 class="text-warning">Are you sure?</h3>', function(result){
   			if(result){
   				$.ajax({ 
    				type: 'POST',
    				cache: false,
    				data: {data:$data},
    				url:'/admin/'+$controller+'/order',
    				beforeSend: function(){
    					bootbox.alert('<h3 class="text-info">Loading...</h3>', function() {});
    				},
    				success: function($response){ 
    					bootbox.hideAll();
    					$class = 'text-danger';
    					if($response.success) {
    						$class = 'text-success';
    					}
    					bootbox.alert('<h3 class="' + $class + '">' + $response.message + '</h3', function(){})
    				},
    				failure: function(){
    					alert('wrong');
    				},
    				complete: function(){
    				}
   				});	
   			}
   		});
    });
    $(document).on('click','.page-preview-image',function(){
		var $target = $(this).data('target');
		var $type = $(this).data('type');
		$img_links = $(decodeURIComponent($target)).val();
		var $images = [];
		if($type !="single") {
			try {
				$images = $.parseJSON($img_links);
			} catch(e) {
				console.log(e);
			}
		} else {
			$images.push($img_links);
		}
		if($images.length > 0) {
			$("body").append('<ul id="preview" class="hidden"></ul>');
			$($images).each(function(index, value){
				var title_name = value.split('/');
				title_name = title_name[title_name.length-1];
				$("ul#preview").append('<a class="image-preview" href="'+value+'" title="'+title_name+'">');
			});
			$(".image-preview").colorbox({
				height: '80%',
				width: '80%',
				rel:'image-preview',
				open:true,
				onClosed:function(){ 
					$("ul#preview").remove();
				},
			});
		} else {
			return false;
		}
		
	});
    
    $(".colorbox-modal").colorbox({
		width:'60%', 
		height:'80%'
	});
    $(document).on('click','.window-open',function(e){
    	e.preventDefault();
    	target = $(this);
    	url = target.attr('href');
    	var width = 1024;
    	var height = 700;
    	var top = screen.height/2 - height/2;
    	var left = screen.width/2 - width/2;
    	window.open(url, "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=" + top + ", left=" + left + ", width=" + width + ", height=" + height + "\"");
    });
    
    $(".datetimepicker").datetimepicker({
    	format: 'MM-DD-YYYY HH:mm:ss'
    });
    
    $(".datetimepicker-from").datetimepicker({
    	maxDate: moment().format('MM-DD-YYYY 23:59'),
    	format: 'MM-DD-YYYY HH:mm:ss'
    });
    $(".datetimepicker-to").datetimepicker({
    	maxDate: moment().format('MM-DD-YYYY 23:59'),
    	useCurrent: false,
    	format: 'MM-DD-YYYY HH:mm:ss'
    });
    $(".datetimepicker-from").on("dp.change", function (e) {
    	var currentTarget = $(e.currentTarget);
    	var currentTargetId = currentTarget.attr('id');
    	var targetId = currentTargetId.replace("from","to");
        $('#'+targetId).data("DateTimePicker").minDate(e.date);
    });
    if($(".dataTable").length > 0) {
    	$table_id = $(".dataTable").attr('id');
    	var oTable = createTable($table_id);
    }

});