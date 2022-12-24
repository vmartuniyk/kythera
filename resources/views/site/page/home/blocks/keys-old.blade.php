    <div class="row">
        <div class="col-md-6 big key">
            <!-- 527/354 -->
            <img src="/en/photos/1/527/354/key.jpg" width="527" height="354">
            <div class="text">
                <h1>This is YOUR Kythera Heritage Site!</h1>
                <p>
                Kythera-Family.net aims to preserve the rich heritage of our wonderful island. Members of the community are invited to submit their family collection of stories, photographs, recipes, maps, oral histories, historical documents, songs and poems, home remedies etc. to the site. Uploading directly to the site is easy. Just log-on or register (top right), go to the category you wish to submit to, and click on the "submit" button. Thus you can help make available valuable and interesting material for current and future generations, and inspire young Kytherians to learn more about their fascinating heritage.
                </p>
            </div>
        </div>

        <div class="col-md-6 hidden-xs medium">
            @foreach($items as $i=>$item)
                <!-- 255/164 -->
                <div class="col-md-6 key">
                    @if (0)
                    <div>
                        <a href="{!!$item->uri!!}" title="{!!$item->title!!}">
                        <img src="{!!$item->image!!}" alt="{!!$item->title!!}" />
                        </a>
                    </div>
                    <div class="text">
                        <a href="{!!$item->uri!!}" title="{!!$item->title!!}">
                            @if (Config::get('app.debug'))
                            <h2>{!!$item->id!!}:{!!$item->crumbs!!}</h2>
                            @else
                            <h2>{!!$item->crumbs!!}</h2>
                            @endif
                            <p><a href="{!!$item->uri!!}" title="{!!$item->title!!}">{!!$item->title!!}</a></p>
                        </a>
                    </div>
                    @else
                    <div>
                        <img src="{!!$item->image!!}" alt="{!!$item->title!!}" />
                    </div>
                    <div class="text">
                        <a href="{!!$item->uri!!}" title="{!!$item->title!!}" style="display:block;height:144px;text-decoration:none">
                            @if (Config::get('app.debug'))
                            <h2>{!!$item->id!!}:{!!$item->crumbs!!}</h2>
                            @else
                            <h2>{!!$item->crumbs!!}</h2>
                            @endif
                            <p>{!!$item->title!!}</p>
                        </a>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
