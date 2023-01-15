<div class="txtdoc-list clearfix">
{{--    <div class="line"></div>--}}

{{--    @if(!empty($page->content))--}}
{{--    <p>{!!$page->content!!}</p>--}}
{{--    <br/>--}}
{{--    <div class="line"></div>--}}
{{--    @endif--}}


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

{{--        @foreach($items as $item)--}}
{{--        <div class="txtdoc text clearfix">--}}
{{--            {!! xmenu::entry_edit($item->item->persons_id, $item) !!}--}}
{{--            <div>--}}
{{--            	<h2>--}}
{{--                @if (Config::get('app.debug'))--}}
{{--                {!!$item->id!!}--}}
{{--                @endif--}}
{{--                {{ $item->title }}--}}
{{--                </h2>--}}
{{--                <span class="date">{!!$item->date!!}</span>--}}
{{--                <p>{!!$item->content!!}</p>--}}
{{--                @if ($email = $item->entry->getEmail())--}}
{{--                <a href="{!!action('DocumentGuestbookController@contact',$item->id)!!}">contact</a>--}}
{{--                @endif--}}
{{--                </div>--}}
{{--        </div>--}}
{{--        @endforeach--}}

{{--        <span class="personal pull-right">{!!$pages->render()!!}</span>--}}
    @else
        <p>There are currently no entries in this section.</p>
    @endif

    <section class="content-inner__articles inner-articles">
        <div class="inner-articles__cards">
            @foreach($items as $i=>$item)

                <article class="inner-articles__card card-articles">

                    <div class="card-articles__info" style="margin-left: 0px">
                        {!! xmenu::entry_edit($item->item->persons_id, $item) !!}
                        <h4 class="card-articles__title">
                              {!!$item->title!!}
                        </h4>
                        <p class="card-articles__text">
                            {!! $item->content !!}
                        </p>
                        @if ($email = $item->entry->getEmail())
                            <a href="{!!action('DocumentGuestbookController@contact',$item->id)!!}" class="card-articles__link view-link">
                                contact
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.132 16.693">
                                    <g data-name="Group 23" transform="translate(-1292.865 -1328.645)">
                                        <g data-name="Group 22">
                                            <path data-name="Path 40" d="M1307.614,1328.991l7.691,8-7.691,8" fill="none" stroke="#24646d" stroke-miterlimit="10" stroke-width="1" />
                                        </g>
                                        <line data-name="Line 3" x1="21.613" transform="translate(1292.865 1336.991)" fill="none" stroke="#24646d" stroke-miterlimit="10" stroke-width="1" />
                                    </g>
                                </svg>
                            </a>
                        @endif

                        <div class="card-articles__footer">
                            <div class="card-articles__date">
                                <time datetime="{!! $item->date !!}">{!! $item->date !!}</time>
                            </div>

                        </div>
                    </div>
                </article>

            @endforeach
        </div>
        <span class="pull-pagination">{!! $pages->render() !!}</span>

    </section>
</div>

