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
                    <h4 class="card-articles__title">
                        <a href="{!!$item->uri!!}">
                            {!!$item->title!!}
                        </a>
                    </h4>
                    <p class="card-articles__text">
                        {!! str_limit(strip_tags($item->content), 250) !!}
                    </p>

                    <div class="card-articles__footer">
                        <div class="card-articles__date">
                            <time datetime="{!! $item->created_at->format('d.m.Y') !!}">{!! $item->created_at->format('d.m.Y') !!}</time> &bull;
                            <span class="card-articles__autor">{!! $item->firstname . " ". $item->lastname !!}</span>
                        </div>

                    </div>
                </div>
            </article>
       
        @endforeach
    </div>
    <span class="pull-pagination">{!! $pages->render() !!}</span>
  
</section>
</div>

