@extends('site.layout.default')

@section('title')
    Family tree of {{ \Kythera\Models\Person::buildDescription($subject) }}
@stop

@section('style')
	ul.a {list-style:none;padding:0}
	ul.a li {float:left;}

    #basicdiagram {width: 640px; height: 320px;}
    #basicdiagram {width: 100%; height: 320px;}
    #basicdiagram {cursor:grab;border:1px solid #ccc;}
    {{--#basicdiagram div.famdiagram {overflow:hidden !important}--}}

    .bp-item .bp-title-frame {display:none;background-color:#38ACF1 !important}
    .bp-item .bp-title {cursor:auto;}
    .bp-item {cursor:auto;}
    .bp-item.bp-photo-frame, .bp-item.bp-description {top: 7px !important;}
    .bp-item.bp-photo-frame img {height: 60px; width: auto !important; overflow: hidden;}
    .bp-item.bp-photo-frame img {height: 100% !important; width: 100% !important;}

    .icon-anc, .icon-des {width:14px;height:12px}
    .icon-anc {background-image: url("/assets/vendors/primitives/images/icon_ancesters.gif"); background-position:0;}
    .icon-des {background-image: url("/assets/vendors/primitives/images/icon_decendants.gif"); background-position:0;}

    .ui-button-icon-only {padding:10px !important;}
    .ui-icon-info {background-image: url("/assets/vendors/primitives/images/icon_info.gif") !important; background-position:1px !important;}
    .ui-icon-edit {background-image: url("/assets/vendors/primitives/images/icon_edit.gif") !important; background-position:1px !important;}
    .ui-icon-asc {background-image: url("/assets/vendors/primitives/images/icon_ancesters.gif") !important; background-position:1px !important;}
    .ui-icon-des {background-image: url("/assets/vendors/primitives/images/icon_decendants.gif") !important; background-position:1px !important;}


	p span {
	    color: #000;
	    font: bold 14px/14px arial;
	}

    p.author {
	    font: 12px/12px arial;
	    margin: 4px 0 0;
    }

	p.author a {
	    text-decoration-color: #000;
	    text-decoration-style: solid;
	}
@stop

@section('stylesheet')
	<link href="/assets/vendors/primitives/js/jquery/ui-lightness/jquery-ui-1.10.2.custom.css" rel="stylesheet" />
    <link href="/assets/vendors/primitives/css/primitives.latest.css?2023" media="screen" rel="stylesheet" type="text/css" />
@stop

@section('javascript')
	<script type="text/javascript" src="/assets/vendors/primitives/js/jquery/jquery-ui-1.10.2.custom.min.js"></script>
    <script type="text/javascript" src="/assets/vendors/primitives/js/primitives.min.custom.js?2016"></script>
    <script type="text/javascript" src="/assets/vendors/primitives/tree.js"></script>
    <script type="text/javascript">
		$(window).load(function () {
        /*
			primitives.orgdiagram.BaseController.prototype._onDefaultTemplateRender = function (event, data) {//ignore jslint
			    var itemConfig = data.context,
			        itemTitleColor = itemConfig.itemTitleColor != null ? itemConfig.itemTitleColor : "#4169e1",
			        color = primitives.common.highestContrast(itemTitleColor, this.options.itemTitleSecondFontColor, this.options.itemTitleFirstFontColor);
			    data.element.find("[name=titleBackground]").css({ "background": itemTitleColor });
			    data.element.find("[name=photo]").attr({ "src": itemConfig.image, "alt": itemConfig.title });
			    data.element.find("[name=title]").css({ "color": color }).text(itemConfig.title);
			    data.element.find("[name=description]").html(itemConfig.description);
			};
        */

		    var items = [
		                 { id: 1, parents: [], spouses: [2], title: "MOTHER", label: "", description: "*1999 (Sydney NSW Australia) ", image: "/ftree/demo/images/photos/avatar.png" },
		                 { id: 2, parents: [], spouses: [], title: "FATHER", label: "", description: "1st HUSBAND",image: "/ftree/demo/images/photos/avatar.png" },
		                 { id: 3, parents: [1,2], spouses: [], title: "CHILD 1", label: "", description: "1st CHILD",image: "/ftree/demo/images/photos/avatar.png" },
		                 { id: 4, parents: [1,2], spouses: [], title: "CHILD 2", label: "", description: "2nd CHILD",image: "/ftree/demo/images/photos/avatar.png" }
		             ];
		    var items = [
		    {"id":"929","entry_id":"1692","parents":[],"spouses":["930"],"title":"","label":"","description":"Γεωργιος Παναγιωτης (*1867 \u20201948)","image":"\/assets\/vendors\/primitives\/images\/photos\/avatar1.png", itemTitleColor: "#4b0082"},
		    {"id":"930","entry_id":"1693","parents":[],"spouses":["929"],"title":"","label":"","description":"Zaphiro Griengi (*1874 \u20201954)","image":"\/assets\/vendors\/primitives\/images\/photos\/avatar1.png"},
		    {"id":"927","entry_id":"1690","parents":["929","930"],"spouses":[928],"title":"","label":"","description":"George Mavromatis ","image":"\/assets\/vendors\/primitives\/images\/photos\/avatar1.png"},
		    {"id":"928","entry_id":"1690","parents":[],"spouses":[927],"title":"","label":"","description":"SPOUSE George Mavromatis ","image":"\/assets\/vendors\/primitives\/images\/photos\/avatar1.png"},
		    {"id":"931","entry_id":"1694","parents":["929","930"],"spouses":[],"title":"","label":"","description":"Chrisso Mavromatis ","image":"\/assets\/vendors\/primitives\/images\/photos\/avatar1.png"},
		    {"id":"932","entry_id":"1695","parents":["929","930"],"spouses":[],"title":"","label":"","description":"Rene Mavromatis (*1895)","image":"\/assets\/vendors\/primitives\/images\/photos\/avatar1.png"},
		    {"id":"933","entry_id":"1696","parents":["929","930"],"spouses":[],"title":"","label":"","description":"Soutiri Mavromatis (*1905)","image":"\/assets\/vendors\/primitives\/images\/photos\/avatar1.png"}
		    ];

		    var items = {!!$json!!};

		    var options = new primitives.famdiagram.Config();
		    options.items = items;


//			console.log(items[0].id);

		    options.pageFitMode = 0;
		    options.graphicsType = 1;
		    options.elbowType = 0;
		    options.hasSelectorCheckbox = primitives.common.Enabled.False;
		    options.templates = [getCursorTemplate2()];
		    options.defaultTemplateName = "CursorTemplate";

            options.normalLevelShift = 40;
            options.dotLevelShift = 40;
            options.lineLevelShift = 20;
            options.normalItemsInterval = 20;
            options.dotItemsInterval = 20;
            options.lineItemsInterval = 20;

		    var buttons = [];
		    @if (Auth::check())
		    	buttons.push(new primitives.orgdiagram.ButtonConfig("add", "ui-icon-edit", "Add relative..."));
		    	buttons.push(new primitives.orgdiagram.ButtonConfig("edit", "ui-icon-info", "Edit personal info..."));
		    	//buttons.push(new primitives.orgdiagram.ButtonConfig("info", "ui-icon-info", "Info"));
            @else
            	buttons.push(new primitives.orgdiagram.ButtonConfig("info", "ui-icon-info", "Info"));
            	@if (Config::get('app.debug'))
		            buttons.push(new primitives.orgdiagram.ButtonConfig("descendants", "ui-icon-des", "View descendants"));
		            buttons.push(new primitives.orgdiagram.ButtonConfig("ancestors", "ui-icon-asc", "View ancestors"));
	            @endif
            @endif
            options.buttons = buttons;
		    options.hasButtons = primitives.common.Enabled.True;
		    options.onButtonClick = onButtonClick;
            options.onMouseClick = onMouseClick;

		    jQuery("#basicdiagram").famDiagram(options);

            openFirst(items[0]);
	    });

        function openFirst(item) {
			var description = item.description;
			if (!description.includes("Living")) {
				var url = "/{{ LaravelLocalization::getCurrentLocale() }}/family-trees/%7Bpersons_id%7D/info";
				url = url.replace('%7Bpersons_id%7D', item.id);
				jQuery(".loader").toggle();
				jQuery.get(url, function (data) {
					jQuery("#info").html(data);
				}).always(function () {
					jQuery(".loader").toggle();
				});
			}else{
				jQuery("#info").html('<div class="col-md-12"><h3>Living</h3></div>');
			}
        }

		function onMouseClick(e, data) {
 			var description = data.context.description;
			if (!description.includes("Living")) {
				var target = jQuery(e.originalEvent.target);
				var url = "/{{ LaravelLocalization::getCurrentLocale() }}/family-trees/%7Bpersons_id%7D/info";
				url = url.replace('%7Bpersons_id%7D', data.context.id);
				jQuery(".loader").toggle();
				jQuery.get(url, function (data) {
					jQuery("#info").html(data);
				}).always(function () {
					jQuery(".loader").toggle();
				});
		    }else{
				jQuery("#info").html('<div class="col-md-12"><h3>Living</h3></div>');
			}
		}


		function onButtonClick(e, data) {
			switch (data.name) {
				case 'add':
					@if (Auth::check())
	                	title = '<span class="blue">Add to</span> ' + data.context.description;
	                	message = '<p>Please choose an option...</p>';
	                	message+= '<div class="radio"><label><input type="radio" name="optionsRadios" id="optionsRadios1" value="{!!\Kythera\Models\Person::MEMBER_PARENT!!}" checked>Add a parent</label></div>';
	                	message+= '<div class="radio"><label><input type="radio" name="optionsRadios" id="optionsRadios2" value="{!!\Kythera\Models\Person::MEMBER_SPOUSE!!}">Add a spouse</label></div>';
	                	message+= '<div class="radio"><label><input type="radio" name="optionsRadios" id="optionsRadios3" value="{!!\Kythera\Models\Person::MEMBER_CHILD!!}">Add a child</label></div>';

	                    jQuery(".btn-primary").on("click", function(){
	                    	jQuery('#myModal').modal('hide');

	                       	var val = parseInt(jQuery("input[name=optionsRadios]:checked").val(),0);
							var url = "/{{ LaravelLocalization::getCurrentLocale() }}/family-trees/%7Bpersons_id%7D/add/%7Bmember%7D";
							url = url.replace('%7Bpersons_id%7D', data.context.id);
							url = url.replace('%7Bmember%7D', val);
							//window.location.replace(url);
							window.location.href = url;
							return;
	                    });

		                var options = {backdrop: false};
		                jQuery('#myModal').on('show.bs.modal', function (event) {
							var button = jQuery(event.relatedTarget) // Button that triggered the modal
							// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
							// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
							var modal = jQuery(this);
							modal.find('.modal-title').html(title);
							modal.find('.modal-body p').html(message);
		                }).modal(options);
	        @endif
	        data.cancel = true;
				break;
				case 'edit':
					@if (Auth::check())
						var url = "/{{ LaravelLocalization::getCurrentLocale() }}/family-trees/%7Bpersons_id%7D/edit";
						//url = url.replace('%7Bpersons_id%7D', data.context.id);
						url = url.replace('%7Bpersons_id%7D', data.context.entry_id);
						//console.log(data);
						window.location.href = url;
					@endif
				break;
				case 'info':
					//http://ajaxload.info/
					var url = "/{{ LaravelLocalization::getCurrentLocale() }}/family-trees/%7Bpersons_id%7D/info";
					url = url.replace('%7Bpersons_id%7D', data.context.id);
					jQuery(".loader").toggle();
					jQuery.get(url, function(data) {
						jQuery("#info").html(data);
					}) .always(function() {
						jQuery(".loader").toggle();
					});
				break;
				default:
					var message = "User clicked '" + data.name + "' button for item '" + data.context.id + "'.";
					alert(message);
			}
		};
    </script>
@stop

@section('content')
    <div class="container">
        <div class="head">
            <h1 class="pull-left">
            {{ $page->title }} > {!!\Kythera\Models\Person::buildDescription($subject)!!}
            </h1>
            <div class="crumb pull-right"><a href="/{{ LaravelLocalization::getCurrentLocale() }}/family-trees?selLet={!!Session::get('family.selLet', 2)!!}&selName={!!Session::get('family.selName', 0)!!}">Back</a></div>
        </div>
        <br class="clear"/>
        <hr class="thin"/>
        <div class="content">
		    <div id="basicdiagram"></div>
        	{{--<p class="author">{!! trans('locale.submitted', array('fullname'=>xhtml::fullname($entry, false), 'date'=>$entry->updated_at->format('d.m.Y'))) !!}</p>--}}
            {!! xmenu::author($entry) !!}

		    <div id="info">{!!$info!!}</div>
        </div>
    </div>

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
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	        <button type="button" class="btn btn-primary" id="save">Continue</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
@stop
