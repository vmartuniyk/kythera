<?php
$leavesPlacementType = $_GET['l'] ? $_GET['l'] : 0;
$orientationType = $_GET['o'] ? $_GET['o'] : 0;
//echo __FILE__.__LINE__.'<pre>$leavesPlacementType='.htmlentities(print_r($leavesPlacementType,1)).'</pre>';

?>


<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Proposal family tree editor</title>


    <link rel="stylesheet" href="demo/js/jquery/ui-lightness/jquery-ui-1.10.2.custom.css" />
    <script type="text/javascript" src="demo/js/jquery/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="demo/js/jquery/jquery-ui-1.10.2.custom.min.js"></script>

    <!-- # include file="src/src.primitives.html"-->
    <script  type="text/javascript" src="demo/js/primitives.min.js?2023"></script>
    <link href="demo/css/primitives.latest.css?2023" media="screen" rel="stylesheet" type="text/css" />

    <!-- Bootsrap provides its own incompetible implementation of jQuery widgets  -->
<!--     <link rel="stylesheet" type="text/css" href="demo/bootstrap/css/bootstrap.min.css" /> -->
<!--     <script type="text/javascript" src="demo/bootstrap/js/bootstrap.js"></script> -->
<!--     <link rel="stylesheet" type="text/css" href="demo/bootstrap/css/bootstrap-responsive.min.css" /> -->
<!-- http://getbootstrap.com/migration/ Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>



    <style type="text/css">
    #basicdiagram {width: 640px; height: 480px;}
    #basicdiagram {width: 100%; height: 480px;}
    #basicdiagram {cursor:grab;}
    #basicdiagram div.famdiagram {overflow:hidden !important}
    .bp-item {cursor:auto;}
    .bp-item .bp-title {cursor:auto;}
    .bp-item .bp-title-frame {background-color:#38ACF1 !important}
    .icon-anc, .icon-des {width:12px;height:12px}
    .icon-anc {background-image: url("demo/images/icon_ancesters.gif"); background-position:0;}
    .icon-des {background-image: url("demo/images/icon_decendants.gif"); background-position:0;}
    </style>

    <script type='text/javascript'>//<![CDATA[

        $(window).load(function () {
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
                         { id: 1, parents: [], spouses: [2], title: "MOTHER", label: "", description: "*1999 (Sydney NSW Australia) ", image: "demo/images/photos/avatar.png" },
                         { id: 2, parents: [], spouses: [], title: "FATHER", label: "", description: "1st HUSBAND",image: "demo/images/photos/avatar.png" },
                         { id: 3, parents: [1,2], spouses: [], title: "CHILD 1", label: "", description: "1st CHILD",image: "demo/images/photos/avatar.png" },
                         { id: 4, parents: [1,2], spouses: [], title: "CHILD 2", label: "", description: "2nd CHILD",image: "demo/images/photos/avatar.png" }
                     ];


            var items = [
			{"id":"306","parents":[],"spouses":["307"],"title":"","label":"","description":"Eleni  Prineas (*1900 \u20201983)","image":"\/ftree\/demo\/images\/photos\/avatar.png"},
            {"id":"307","parents":[],"spouses":["306"],"title":"","label":"","description":"Dimitri Georgos Prineas (*1889 \u20201949)","image":"\/ftree\/demo\/images\/photos\/avatar.png"},
			{"id":"305","parents":["306","307"],"spouses":[],"title":"","label":"","description":"Victor Brett Prineas (*1932)","image":"\/ftree\/demo\/images\/photos\/avatar.png"},
			{"id":"378","parents":["306","307"],"spouses":[],"title":"","label":"","description":"Mary  Aroney (*1926)","image":"\/ftree\/demo\/images\/photos\/avatar.png"},
			{"id":"379","parents":["306","307"],"spouses":[],"title":"","label":"","description":"George Dimitri Prineas (*1928)","image":"\/ftree\/demo\/images\/photos\/avatar.png"},
			{"id":"380","parents":["306","307"],"spouses":[],"title":"","label":"","description":"Paniotitsa  Prineas (*1931)","image":"\/ftree\/demo\/images\/photos\/avatar.png"}
			];

            <?php
            switch ($orientationType) {
                /*ancestor view from left to right*/
                case 2:
                    ?>
                    var items = [
                                 { id: 1, parents: [], spouses: [2], title: "PERSON", label: "", description: "*1999", image: "demo/images/photos/avatar.png" },
                                 { id: 3, parents: [1, 2], spouses: [], title: "FATHER", label: "", description: "*1973",image: "demo/images/photos/avatar.png" },
                                 { id: 4, parents: [1, 2], spouses: [], title: "MOTHER", label: "", description: "*1971",image: "demo/images/photos/avatar.png" },
                                 { id: 5, parents: [3], spouses: [], title: "GRAND FATHER", label: "", description: "*1873",image: "demo/images/photos/avatar.png" },
                                 { id: 6, parents: [3], spouses: [], title: "GRAND MOTHER", label: "", description: "*1871",image: "demo/images/photos/avatar.png" }
                             ];
                    <?php
                break;
            }

            ?>

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
            options.orientationType = <?= $orientationType ?>;;

            options.verticalAlignment = 0;
            options.verticalAlignment = 2;
            options.leavesPlacementType = <?= $leavesPlacementType ?>;


            /*
            options.graphicsType = 0;
            options.pageFitMode = 0;
            options.minimalVisibility = 1;
            options.orientationType = 0;
            options.horizontalAlignment = 0;
            options.verticalAlignment = 0;

            options.arrowsDirection = 0;
            options.connectorType = 0;
            options.elbowType = 0;
            options.elbowDotSize = 14;

            options.emptyDiagramMessage = 0;

            options.selectCheckBoxLabel = 'Selected';
            options.selectionPathMode = 1;

            options.lineLevelShift = 20;
            options.normalLevelShift = 20;
            options.normalItemsInterval = 20;
            options.lineItemsInterval = 20;
            options.cousinsIntervalMultiplier = 10;

            options.leavesPlacementType = 0;
            options.cursorItem = 0;
            options.linesWidth = 1;

            options.linesColor = "#EEEEEE";
            */

            options.itemTitleFirstFontColor = primitives.common.Colors.White;
            options.itemTitleSecondFontColor = primitives.common.Colors.White;

            jQuery("#basicdiagram").famDiagram(options);
        });


        function getCursorTemplate() {
            var result = new primitives.orgdiagram.TemplateConfig();
            result.name = "CursorTemplate";

            result.itemSize = new primitives.common.Size(180, 100);
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
        //]]>
    </script>
</head>
<body>
    <div>
        <a href="/ftree/tree.php">View A: two generations vert. </a><br/>
        <a href="/ftree/tree.php?l=1">View B: two generations vert. 2 </a><br/>
        <a href="/ftree/tree.php?o=2">View C: multi generations horz. </a><br/>
    </div>
    <div id="basicdiagram" style="xwidth: 640px; xheight: 480px; border-style: dotted; border-width: 1px;"></div>

	<div id="myModal" class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Modal title</h4>
	      </div>
	      <div class="modal-body">
	        <p>One fine body&hellip;</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary" id="save">Save</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</body>
</html>
