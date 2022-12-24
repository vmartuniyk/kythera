<!--<div class="txtdoc-list clearfix">-->
<!--    <div class="line"></div>-->


<!--    @if($items)-->
<!--        <div class="txtdoc-filter clearfix">-->
           
<!--        </div>-->

<!--        <div class="line"></div>-->

<!--        @foreach($items as $item)-->
<!--        <div class="txtdoc text clearfix">-->
            
<!--            <div>-->
                

<!--                <a href="{!!$item->uri!!}" title="{{ $item->title }}">-->
<!--                    <h3>-->
<!--                    {{ $item->title }}-->
<!--                    </h3>-->
<!--                </a>-->
<!--                <p class="author">{!! trans('locale.submitted', array('fullname'=>xhtml::fullname($item, false), 'date'=>$item->created_at->format('d.m.Y'))) !!}</p>-->
<!--                <br/>-->
<!--                <p class="content">{!! str_limit(strip_tags($item->content), 250) !!}</p>-->
<!--            </div>-->
<!--        </div>-->
<!--        @endforeach-->

<!--        <span class="personal pull-right">{!!$pages->render()!!}</span>-->
<!--    @else-->
<!--        <p>There are currently no entries in this section.</p>-->
<!--    @endif-->
    
<div class="inner-articles__top">
    <h3 class="inner-articles__title">All entries</h3>
    <div class="inner-articles__sort-menu sort-menu-inner">
        <div class="sort-menu-inner__text">{!!trans('locale.filter.sortedby')!!}:</div>
        <div class="sort-menu-inner__select">
            
                <form method="get" action="{!! URL::full() !!}">
                    
                    {{-- <select class="sort-menu-inner__enter-field auto-submit" name="po">
                        {{ $i = 1}}
                        @foreach($paginate_orders as $order)
                        <option value="{{ $i }}" class="sort-menu-inner__item"> {{ $order }} </option>
                         {{ $i++}}   
                        @endforeach
                        
            
                    </select> --}}
                   
                    {!!Form::select('po', $paginate_orders, Session::get('paginate_order'), array('class'=>'sort-menu-inner__enter-field auto-submit'))!!}
                </form>
                {{-- <li class="sort-menu-inner__item">The most popular</li>
                <li class="sort-menu-inner__item">Most Recent</li>
                <li class="sort-menu-inner__item">By date</li> --}}
            {{-- </ul> --}}
        </div>
    </div>
</div>

<section class="content-inner__articles inner-articles">
    <div class="inner-articles__cards">
        @foreach($items as $i=>$item)

            <article class="inner-articles__card card-articles">
                
                <div class="card-articles__info" style="margin-left: 0px">
                    <h4 class="card-articles__title">{!!$item->title!!}</h4>
                    <p class="card-articles__text">
                        {!! str_limit(strip_tags($item->content), 250) !!}
                    </p>
                    <a href="{!!$item->uri!!}" class="card-articles__link view-link">
                        View Full Message
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.132 16.693">
                            <g data-name="Group 23" transform="translate(-1292.865 -1328.645)">
                                <g data-name="Group 22">
                                    <path data-name="Path 40" d="M1307.614,1328.991l7.691,8-7.691,8" fill="none" stroke="#24646d" stroke-miterlimit="10" stroke-width="1" />
                                </g>
                                <line data-name="Line 3" x1="21.613" transform="translate(1292.865 1336.991)" fill="none" stroke="#24646d" stroke-miterlimit="10" stroke-width="1" />
                            </g>
                        </svg>
                    </a>
                    <div class="card-articles__footer">
                        <div class="card-articles__date">
                            <time datetime="{!! $item->created_at->format('d.m.Y') !!}">{!! $item->created_at->format('d.m.Y') !!}</time> &bull;
                            <span class="card-articles__autor">{!! $item->firstname . " ". $item->lastname !!}</span>
                        </div>
                        <span class="card-articles__description">
                            {!! $item->crumbs !!}
                        </span>
                    </div>
                </div>
            </article>
       
        @endforeach
    </div>
    <span class="pull-pagination">{!! $pages->render() !!}</span>
  
</section>
</div>

