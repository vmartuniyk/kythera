



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
                <div class="card-articles__image">
                    
                    @if($item->image)
                        <picture><source srcset="{{ $item->image }}" type="image/webp">
                            <img src="{{ $item->image }} alt=""></picture>
                    @else
                        <picture><source srcset="{{ URL::asset('assets/img/history.webp') }}" type="image/webp">
                            <img src="{{ URL::asset('assets/img/history.jpg?_v=1657459303074') }}" alt=""></picture>    
                    
                    @endif
                    
                </div>
                <div class="card-articles__info">
                    <h4 class="card-articles__title">{!!$item->title!!}</h4>
                    <p class="card-articles__text">
                        {!! str_limit(strip_tags($item->content)) !!}
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
    {{-- <div class="inner-articles__show-more show-more-btn">
        <button type="button">View More Entries</button>
    </div> --}}
</section>


@section('js')
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
@endsection