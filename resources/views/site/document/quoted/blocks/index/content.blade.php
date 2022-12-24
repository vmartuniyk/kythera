<div class="txtdoc-list">
    <div class="line"></div>

    @if(!empty($page->content))
    <p>{!!$page->content!!}</p>
    <br/>
    <div class="line"></div>
    @endif

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
            {{--<img src="http://lorempixel.com/194/145">--}}
            <div>
                {!! xmenu::entry_edit($item->user_id, $item) !!}


                @if (Config::get('app.debug'))
                <h2>{!!$item->id!!}:{!! xhtml::crumbs(Router::getSelected(), ' &gt; ', false) !!}</h2>
                @else
                <h2>{!!xhtml::crumbs(Router::getSelected(), ' &gt; ', false) !!}</h2>
                @endif
                <p class="author">{!! trans('locale.submitted', array('fullname'=>xhtml::fullname($item, false), 'date'=>$item->created_at->format('d.m.Y'))) !!}</p>
                {{--
                <a href="{!!route(Router::getControllerUrl('entry'), (string)$item->uri)!!}" title="{{ $item->title }}">
                    <h3>&gt; {{ $item->title }}</h3>
                </a>
                --}}
                <a href="{!!$item->uri!!}" title="{{ $item->title }}">
                    <h3>{{ $item->title }}</h3>
                </a>
            </div>
        </div>
        @endforeach

        <span class="personal pull-right">{!!$pages->render()!!}</span>
    @else
        <p>There are currently no entries in this section.</p>
    @endif
</div>