<div class="row">
        <div class="col-md-6 big key2">
            
            <img src="{!!$image!!}" alt="{!!$page->title!!}" width="527" height="354"/>
            <div class="text color1">
                {!!$text!!}
            </div>

        </div>

        <div class="col-md-6 hidden-xs medium">
        @foreach($items as $i=>$item)

            @if ($box && $i==1)

                {!!$box!!}

            @else

                <!-- 255/164 -->
                <div class="col-md-6 key2">
                    <a href="{!!$item->uri!!}" title="{!!$item->title!!}">
                        <div>
                            <img src="{!!$item->cache!!}" alt="{!!$item->title!!}" />
                        </div>
                        <div class="text color{!!($i+2)!!}">

                            @if (Config::get('app.debug'))
                                <h2>{!!$item->id!!}:{!!$item->crumbs!!}</h2>
                            @else
                                <h2>{!!$item->crumbs!!}</h2>
                            @endif
                            <div class="line"></div>
                            <p>{!!$item->title!!}</p>

                        </div>
                    </a>
                </div>

            @endif


        @endforeach
        </div>
    </div>