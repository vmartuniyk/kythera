$(window).load(function () {
	/*
    var items = [
                 { id: 1, parents: [7, 8], spouses: [2], title: "MOTHER", label: "", description: "*1999 (Sydney NSW Australia) ", image: "demo/images/photos/avatar.png" },
                 { id: 2, parents: [5, 6], spouses: [], title: "FATHER", label: "", description: "1st HUSBAND",image: "demo/images/photos/avatar.png" },
                 { id: 3, parents: [1, 2], spouses: [], title: "CHILD 1", label: "", description: "1st CHILD",image: "demo/images/photos/avatar.png" },
                 { id: 4, parents: [1, 2], spouses: [], title: "CHILD 2", label: "", description: "2nd CHILD",image: "demo/images/photos/avatar.png" },
                 { id: 5, parents: [], spouses: [], title: "GRANDPA 1", label: "", description: "GRANDPA",image: "demo/images/photos/avatar.png" },
                 { id: 6, parents: [], spouses: [5], title: "GRANDMA 1", label: "", description: "GRANDMA",image: "demo/images/photos/avatar.png" },
                 { id: 7, parents: [], spouses: [], title: "GRANDPA 2", label: "", description: "GRANDPA",image: "demo/images/photos/avatar.png" },
                 { id: 8, parents: [], spouses: [7], title: "GRANDMA 2", label: "", description: "GRANDMA",image: "demo/images/photos/avatar.png", itemTitleColor: "#38ACF1"}
             ];
    var items = [
                 { id: 1, parents: [], spouses: [2], title: "MOTHER", label: "", description: "*1999 (Sydney NSW Australia) ", image: "/ftree/demo/images/photos/avatar.png" },
                 { id: 2, parents: [], spouses: [], title: "FATHER", label: "", description: "1st HUSBAND",image: "/ftree/demo/images/photos/avatar.png" },
                 { id: 3, parents: [1,2], spouses: [], title: "CHILD 1", label: "", description: "1st CHILD",image: "/ftree/demo/images/photos/avatar.png" },
                 { id: 4, parents: [1,2], spouses: [], title: "CHILD 2", label: "", description: "2nd CHILD",image: "/ftree/demo/images/photos/avatar.png" }
             ];

    var options = new primitives.famdiagram.Config();
    options.items = items;
    options.hasSelectorCheckbox = primitives.common.Enabled.False;
    options.hasButtons = primitives.common.Enabled.False;
    options.onMouseClick = onMouseClick;
    options.templates = [getCursorTemplate()];
    options.defaultTemplateName = "CursorTemplate";

    options.elbowType = 0;
    options.pageFitMode = 0;
    options.graphicsType = 1;

    options.orientationType = 1;
    options.orientationType = 0;
    //options.orientationType = <?= $orientationType ?>;;
    
    options.verticalAlignment = 0;
    options.verticalAlignment = 2;
    //options.leavesPlacementType = <?= $leavesPlacementType ?>;
    
    options.itemTitleFirstFontColor = primitives.common.Colors.White;
    options.itemTitleSecondFontColor = primitives.common.Colors.White;
    
    jQuery("#basicdiagram").famDiagram(options);
    */
});


function getCursorTemplate() {
    var result = new primitives.orgdiagram.TemplateConfig();
    result.name = "CursorTemplate";

    result.itemSize = new primitives.common.Size(180, 100);
    result.itemSize = new primitives.common.Size(180, 80);
    result.minimizedItemSize = new primitives.common.Size(3, 3);
    result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);
    result.cursorPadding = new primitives.common.Thickness(3, 3, 50, 8);

    var cursorTemplate = jQuery("<div></div>")
    .css({
        position: "absolute",
        overflow: "hidden",
        width: (result.itemSize.width + result.cursorPadding.left + result.cursorPadding.right) + "px",
        height: (result.itemSize.height + result.cursorPadding.top + result.cursorPadding.bottom) + "px"
    });

    var cursorBorder = jQuery("<div></div>")
    .css({
        width: (result.itemSize.width + result.cursorPadding.left + 1) + "px",
        height: (result.itemSize.height + result.cursorPadding.top + 1) + "px"
    }).addClass("bp-item bp-corner-all bp-cursor-frame");
    cursorTemplate.append(cursorBorder);

    var bootStrapVerticalButtonsGroup = jQuery("<div></div>")
    .css({
        position: "absolute",
        overflow: "hidden",
        top: result.cursorPadding.top + "px",
        left: (result.itemSize.width + result.cursorPadding.left + 10) + "px",
        width: "35px",
        height: (result.itemSize.height + 1) + "px"
    }).addClass("btn-group-xs btn-group-vertical");

    bootStrapVerticalButtonsGroup.append('<button class="btn btn-default btn-sm" data-buttonname="add" type="button" title="Add"><i class="glyphicon glyphicon-plus"></i></button>');
    //bootStrapVerticalButtonsGroup.append('<button class="btn btn-default btn-sm" data-buttonname="edit" type="button" title="Edit"><i class="icon-edit"></i></button>');
    bootStrapVerticalButtonsGroup.append('<button class="btn btn-default btn-sm" data-buttonname="remove" type="button" title="Remove"><i class="glyphicon glyphicon-remove"></i></button>');
    bootStrapVerticalButtonsGroup.append('<button class="btn btn-default btn-sm" data-buttonname="des" type="button" title="Descendants"><i class="glyphicon icon-des"></i></button>');
    bootStrapVerticalButtonsGroup.append('<button class="btn btn-default btn-sm" data-buttonname="anc" type="button" title="Ancestors"><i class="glyphicon icon-anc"></i></button>');
    cursorTemplate.append(bootStrapVerticalButtonsGroup);

    result.cursorTemplate = cursorTemplate.wrap('<div>').parent().html();

    return result;
}

function getCursorTemplate2() {
	var result = new primitives.orgdiagram.TemplateConfig();
	result.name = "CursorTemplate";
	
	result.itemSize = new primitives.common.Size(180, 80);
	//result.minimizedItemSize = new primitives.common.Size(3, 3);
	result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);
	//result.cursorPadding = new primitives.common.Thickness(3, 3, 50, 8);
	
	var cursorTemplate = jQuery("<div></div>")
	.css({
		position: "absolute",
		overflow: "hidden",
		width: (result.itemSize.width + result.cursorPadding.left + result.cursorPadding.right) + "px",
		height: (result.itemSize.height + result.cursorPadding.top + result.cursorPadding.bottom) + "px"
	});
	
	var cursorBorder = jQuery("<div></div>")
	.css({
		width: (result.itemSize.width + result.cursorPadding.left + 1) + "px",
		height: (result.itemSize.height + result.cursorPadding.top + 1) + "px"
	}).addClass("bp-item bp-corner-all bp-cursor-frame");
	//cursorTemplate.append(cursorBorder);
	
	var bootStrapVerticalButtonsGroup = jQuery("<div></div>")
	.css({
		position: "absolute",
		overflow: "hidden",
		top: result.cursorPadding.top + "px",
		left: (result.itemSize.width + result.cursorPadding.left + 10) + "px",
		width: "35px",
		height: (result.itemSize.height + 1) + "px"
	}).addClass("btn-group-xs btn-group-vertical");
	
	bootStrapVerticalButtonsGroup.append('<button class="btn btn-default btn-sm" data-buttonname="add" type="button" title="Add"><i class="glyphicon glyphicon-plus"></i></button>');
	//bootStrapVerticalButtonsGroup.append('<button class="btn btn-default btn-sm" data-buttonname="edit" type="button" title="Edit"><i class="icon-edit"></i></button>');
	bootStrapVerticalButtonsGroup.append('<button class="btn btn-default btn-sm" data-buttonname="remove" type="button" title="Remove"><i class="glyphicon glyphicon-remove"></i></button>');
	bootStrapVerticalButtonsGroup.append('<button class="btn btn-default btn-sm" data-buttonname="des" type="button" title="Descendants"><i class="glyphicon icon-des"></i></button>');
	bootStrapVerticalButtonsGroup.append('<button class="btn btn-default btn-sm" data-buttonname="anc" type="button" title="Ancestors"><i class="glyphicon icon-anc"></i></button>');
	//cursorTemplate.append(bootStrapVerticalButtonsGroup);
	
	result.cursorTemplate = cursorTemplate.wrap('<div>').parent().html();
	
	return result;
}

function onItemRender(event, data) {
	console.log(data);
}

function onMouseClick(event, data) {
    var target = jQuery(event.originalEvent.target);
    if (target.hasClass("btn") || target.parent(".btn").length > 0) {
        var button = target.hasClass("btn") ? target : target.parent(".btn");
        var buttonname = button.data("buttonname");
        
        jQuery(".btn-primary").unbind();

        var title = data.context.title;
        var message = "User clicked '" + buttonname + "' button for item '" + data.context.title + "'.";
        message += (data.parentItem != null ? " Parent item '" + data.parentItem.title + "'" : "");
        
        switch(buttonname) {
            case 'add':
            	title = data.context.title;
            	message = '<div class="radio"><label><input type="radio" name="optionsRadios" id="optionsRadios1" value="1" checked>Add a parent</label></div><div class="radio"><label><input type="radio" name="optionsRadios" id="optionsRadios2" value="2">Add a spouse</label></div><div class="radio"><label><input type="radio" name="optionsRadios" id="optionsRadios3" value="3">Add a child</label></div>';
                
                jQuery(".btn-primary").on("click", function(){
                	var val = parseInt(jQuery("input[name=optionsRadios]:checked").val(),0);
                	$('#myModal').modal('hide');
                	add(val, data);
                });
            	break;
            case 'edit':
                title = 'Edit ' + data.context.title;
                break;
            case 'remove':
                title = 'Remove ' + data.context.title;
                message = 'Please confirm deletion of member from family tree? <br/><br/><div class="alert alert-danger" role="alert">! All descendants will be removed aswell.</div>';
                
                jQuery(".btn-primary").on("click", function(){
                	$('#myModal').modal('hide');
                    remove(data);
                });
                break;
            case 'share':
                title = 'Invite a person to join your tree.';
                message = '<label for="e">Please enter the email address</label><input id="e" type="text"/>';
                break;
            default:
        }
        
        var options = {backdrop: false};
        jQuery('#myModal').on('show.bs.modal', function (event) {
			var button = jQuery(event.relatedTarget) // Button that triggered the modal
			// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			var modal = jQuery(this);
			modal.find('.modal-title').text(title);
			modal.find('.modal-body p').html(message);
        }).modal(options);
        

        data.cancel = true;
    }
}


function find(arr, propName, propValue) {
	for (var i=0; i < arr.length; i++) {
	    if (arr[i][propName] == propValue) {
	      return i;
	      //return arr[i];
	    }
	}
	return -1;
}


function remove(data) {
	//console.log(data);
	//console.log(data.context.parents.length);
    if (data.context.parents.length == 0) {
        alert("You are trying to delete root item!");
    }
    else {
        var items = jQuery("#basicdiagram").famDiagram("option", "items");
        
        var itemIndex = find(items, 'id', data.context.id);
        items.splice(itemIndex, 1);

        /* update items list in chart */
        jQuery("#basicdiagram").famDiagram({
            items: items
        });
        jQuery("#basicdiagram").famDiagram("update", /*Refresh: use fast refresh to update chart*/ primitives.orgdiagram.UpdateMode.Refresh);
    }
}


function add(val, data) {
	//console.log(val);
	//console.log('id: '+data.context.id);
	//console.log(data.context.title);
	//console.log(data);
	
	
    /* get items collection */
    var items = jQuery("#basicdiagram").famDiagram("option", "items");
    var newId = items.length;
/*
            var items = [
                         { id: 1, parents: [7, 8], spouses: [2], title: "MOTHER", label: "", description: "*1999 (Sydney NSW Australia) ", image: "demo/images/photos/avatar.png" },
                         { id: 2, parents: [5, 6], spouses: [], title: "FATHER", label: "", description: "1st HUSBAND",image: "demo/images/photos/avatar.png" },
                         { id: 3, parents: [1, 2], spouses: [], title: "CHILD", label: "", description: "1st CHILD",image: "demo/images/photos/avatar.png" },
                         { id: 4, parents: [1, 2], spouses: [], title: "CHILD", label: "", description: "2nd CHILD",image: "demo/images/photos/avatar.png" },
                         { id: 5, parents: [], spouses: [], title: "GRANDPA 1", label: "", description: "GRANDPA",image: "demo/images/photos/avatar.png" },
                         { id: 6, parents: [], spouses: [5], title: "GRANDMA 1", label: "", description: "GRANDMA",image: "demo/images/photos/avatar.png" },
                         { id: 7, parents: [], spouses: [], title: "GRANDPA 2", label: "", description: "GRANDPA",image: "demo/images/photos/avatar.png" },
                         { id: 8, parents: [], spouses: [7], title: "GRANDMA 2", label: "", description: "GRANDMA",image: "demo/images/photos/avatar.png" }
                     ];
  */
    
    /* create new item */
    switch(parseInt(val)) {
        case 1:
             /* add parent */
            var newItem = new primitives.famdiagram.ItemConfig({
                id: ++newId,
                parents: [],//data.context.id
                title: "PARENT id:"+newId,
                description: "Parent of id:"+data.context.id+', '+data.context.title,
                description: "Parent of "+data.context.title,
                image: "demo/images/photos/avatar.png"
            });
             
             /* update child*/
             var childIndex = find(items, 'id', data.context.id);
             //console.log(childIndex);
             items[childIndex].parents.push(newItem.id);
        break;
        case 2:
             /* add spouse */
            var newItem = new primitives.famdiagram.ItemConfig({
                id: ++newId,
                spouses: [data.context.id],
                title: "SPOUSE id:"+newId,
                description: "Spouse of id:"+data.context.id+', '+data.context.title,
                description: "Spouse of "+data.context.title,
                image: "demo/images/photos/avatar.png"
            });
        break;
        case 3:
             /* add child */
            var newItem = new primitives.famdiagram.ItemConfig({
                id: ++newId,
                parents: getParents(data),
                title: "CHILD id:"+newId,
                description: "Child of id:"+data.context.id+', '+data.context.title,
                description: "Child of "+data.context.title,
                image: "demo/images/photos/avatar.png"
            });
        break;
        default:
        	alert('Not implemented');
            	 
    }
    
    /* add it to items collection and put it back to chart, actually it is the same reference*/
    items.push(newItem);
    //console.log(items);
    jQuery("#basicdiagram").famDiagram({
        items: items,
        cursorItem: newItem.id
    });
    /* updating chart options does not fire its referesh, so it should be call explicitly */
    jQuery("#basicdiagram").famDiagram("update", /*Refresh: use fast refresh to update chart*/ primitives.orgdiagram.UpdateMode.Refresh);
}


function getParents(data) {
    return [data.context.id, data.context.spouses[0]];
}


