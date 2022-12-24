@extends("admin.layout.default")

@section('style')
@stop

@section("content")
<div class="container">
        <h1>{!!$title!!}</h1>

        @if ($type != 'top')
        <hr class="thin" />
        {!! Form::open(array('url'=> action('Admin\AdminEntryController@search', array($type)), 'method'=>'GET', 'class'=>'form-inline')) !!}
        	<div class="form-group">
            {!! Form::text('q', isset($q) ? $q : '', array('class'=>'form-control', 'placeholder'=>'Search...', 'style'=>'width:500px')) !!}
            </div>

            <div class="form-group">
            {!! Form::submit(trans('locale.button.search'), array('class'=>'btn btn-black pull-right')) !!}
            </div>
        {!! Form::close() !!}
        @endif

    <br>
    <span class="pull-right">{!! $pages->render() !!}</span>
    <br>
	@if($type == 'guestbook')
    <form>
        <select id="dropdown-pagination" class="form-control" style="width:10%;"> 
            <option value="20" @if($itemsToShow == 20) selected @endif >20</option>
            <option value="50" @if($itemsToShow == 50) selected @endif >50</option>
            <option value="100" @if($itemsToShow == 100) selected @endif >100</option>
            <option value="500" @if($itemsToShow == 500) selected @endif >500</option>
        </select>
    </form>
<div class="select_all_container" style="padding:15px 0px;">
    <label for="check_all" style="cursor:pointer"> <input type="checkbox" id="check_all"> Select All</label>
    <button style="margin-left: 15px;" class="btn btn-danger delete-all" data-url="">Delete</button>
</div>
@endif
    <table class="table table-striped table-hover table-condensed">
        @if (count($items))
            @foreach($items as $i=>$item)
            <tr>
                @if (Config::get('app.debug'))
                <td>{{ $item->id }}</td>
                @endif
				@if($type == 'guestbook')
                <td><input type="checkbox" class="checkbox" data-id="{{$item->id}}"></td>
				@endif
                @if (get_class($item) == 'App\Models\Comment')
                    <td style="position:relative;">
                        <a href="{!! $item->entry->uri !!}">{{ $item->entry->title }}</a> ({!! $item->entry->crumbs !!})
                        <p> {!! strip_tags($item->comment, Config::get('view.comments.allow_tags')) !!} </p>
                        <p class="author">{!! trans('locale.submitted', array('fullname'=>xhtml::fullname($item, false), 'date'=>$item->created_at->format('d.m.Y'))) !!}</p>
                        {!! xcomment::entry_edit($item->user_id, $item) !!}
                    </td>
                @else
                    <td style="position:relative;">
                        <a href="{!! $item->uri !!}">{{ $item->title }}</a> ({!!$item->crumbs!!})
                        <p class="author">{!! trans('locale.submitted', array('fullname'=>xhtml::fullname($item, false), 'date'=>$item->created_at->format('d.m.Y'))) !!}</p>
                        {!! xmenu::entry_edit($item->user_id, $item) !!}
                    </td>
                @endif
            </tr>
            @endforeach
        @else
            <p>No results found.</p>
        @endif
    </table>
    <hr class="thin" />
   
@if($type == 'guestbook')
    <div class="select_all_container" style="padding:15px 0px; width:50%; display:inline-block;">
        <label for="check_all2" style="cursor:pointer"> <input type="checkbox" id="check_all2"> Select All</label>
        <button style="margin-left: 15px;" class="btn btn-danger delete-all" data-url="">Delete</button>
    </div>
@endif
    </form>
    <span class="pull-right">{!! $pages->render() !!}</span>
    
</div>
@stop
@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
        document.getElementById('dropdown-pagination').onchange = function() {
            window.location = "{{ $pages->url(1) }}&showItems=" + this.value;
        };

        <?php if(isset($_GET['showItems'])): ?>
            $('.pagination li a').each(function(){
                var href= $(this).attr('href');
                var url = $(this).attr('href', href+'&showItems='+ '{{$_GET['showItems']}}');
            });
        <?php endif;?>

        //bulk delete
        $('#check_all , #check_all2').on('click', function(e) {
            if($(this).is(':checked',true))  
            {
                $(".checkbox").prop('checked', true); 
            } else {
                $(".checkbox").prop('checked',false);  
            }  
        });

        $('.delete-all').on('click', function(e)
        {
            var idsArr = [];  

            $(".checkbox:checked").each(function() {  
                idsArr.push($(this).attr('data-id'));
            });

            if(idsArr.length <=0)  
            {
                alert("Please select atleast one record to delete.");  
            }
            else {  
                if(confirm("Are you sure, you want to delete the selected records?")){  
                    var strIds = idsArr.join(","); 
                    $.ajax({
                        url: "/admin/entries/multiDelete/" + strIds,
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 
                            'Accept':'application/json',
                            },
                        cache: false,
                        success: function (data) {
                            if (data['status']==true) {
                                $(".checkbox:checked").each(function() {  
                                    $(this).parents("tr").remove();
                                });
                                console.log(data['message']);
                                location.reload();
                            } else {
                                alert('Whoops Something went wrong!!');
                            }
                        },
                        error: function (data) {
                            console.log(data.responseText);
                        }
                    });
                }
            }
    });
});

    
</script>
@stop