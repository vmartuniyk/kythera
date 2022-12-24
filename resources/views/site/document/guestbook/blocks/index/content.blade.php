<div class="txtdoc-list clearfix">
    <div class="line"></div>

    @if(!empty($page->content))
    <p>{!!$page->content!!}</p>
    <br/>
    <div class="line"></div>
    @endif


    @if(Session::has('global'))<p class="bg-info">{!! Session::get('global') !!}</p>@endif

    @if (count($items))
        <div class="txtdoc-filter clearfix">
            <span class="pull-left">{!!trans('locale.filter.showing', array(
                'start'=>$pages->firstItem(),
                'end'=>$pages->firstItem()+$pages->count()-1,
                'total'=>$pages->total()
                ))!!}</span>
            <div class="pull-right">
                <form method="get" action="{!! URL::full() !!}">
                    {!!trans('locale.filter.show')!!}:
                    {!!Form::select('ps', $paginate_sizes, Session::get('paginate_size'), array('class'=>'filter auto-submit'))!!}
                    {!!trans('locale.filter.sortedby')!!}:
                    {!!Form::select('po', $paginate_orders, Session::get('paginate_order'), array('class'=>'filter auto-submit'))!!}
                </form>
            </div>
        </div>

        <div class="line"></div>

        @foreach($items as $item)
        <div class="txtdoc text clearfix">
            {!! xmenu::entry_edit($item->item->persons_id, $item) !!}
            <div>
            	<h2>
                @if (Config::get('app.debug'))
                {!!$item->id!!}
                @endif
                {{ $item->title }}
                </h2>
                <span class="date">{!!$item->date!!}</span>
                <p>{!!$item->content!!}</p>
                @if ($email = $item->entry->getEmail())
                <a href="{!!action('DocumentGuestbookController@contact',$item->id)!!}">contact</a>
                @endif
                </div>
        </div>
        @endforeach

        <span class="personal pull-right">{!!$pages->render()!!}</span>
    @else
        <p>There are currently no entries in this section.</p>
    @endif
</div>

