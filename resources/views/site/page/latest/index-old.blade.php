{{-- <div class="container">
    <div class="head">
        <h1 class="pull-left">{{ $page->title }}</h1>
        <div class="crumb pull-right">
        	{!! xhtml::crumbs(Router::getSelected(), ' &gt; ', false) !!}
        </div>
    </div>
    <br class="clear"/>

    <hr class="thin"/>
    <div class="content">


        <div class="txtdoc-list">

            <div class="clearfix">
                <span class="pull-left">
                <ul>
                <li><a href="{!!action('LatestPostsController@getIndex', array('text'))!!}">Show all documents</a></li>
                <li><a href="{!!action('LatestPostsController@getIndex', array('comment'))!!}">Show comments</a></li>
                <li><a href="{!!action('LatestPostsController@getIndex', array('image'))!!}">Show image documents</a></li>
                <!-- <li><a href="{!!action('LatestPostsController@getIndex', array('tree'))!!}">Show family tree documents</a></li> -->
                </ul>
                </span>
                <span class="pull-right">{!! $pages->render() !!}</span>
            </div>
            <hr class="thin clearfix" />



            @foreach($items as $i=>$item)
                @if ($type == 'comment')
                    <div class="txtdoc text clearfix">
                        <div>
                            {{--<pre>{!!print_r($item,1)!!}</pre>--}}

                            {{-- @if (isset($item->entry->crumbs))
                            <h2>{!! $item->entry->crumbs !!}</h2>
                            @else
                            <h2>NO CRUMBS</h2>
                            @endif

                            <p class="author">{!! trans('locale.submitted', array('fullname'=>xhtml::fullname($item, false), 'date'=>$item->created_at->format('d.m.Y'))) !!}</p>

                            @if (isset($item->entry->uri))
                            <a href="{!!$item->entry->uri!!}" title="{{ $item->entry->title }}">
                                <h3>{{ $item->entry->title }}</h3>
                            </a>
                            @else
                            <h2>NO TITLE</h2>
                            @endif

                            <p>{!! str_limit(strip_tags($item->comment)) !!}</p>
                        </div>
                    </div>
                @elseif ($type == 'image') --}}
                {{-- <?php //echo '<pre>'; print_r($item); die;?>

                    <div class="txtdoc image clearfix">
                        <div>
                            <a href="{!!$item->uri!!}" title="{{ $item->title }}">
                                <img alt="{!!$item->title!!}" src="{!!$item->cache!!}"/>
                            </a>
                        </div>
                        <div>
                            <h2>{!! $item->crumbs !!}</h2>
                            <p class="author">{!! trans('locale.submitted', array('fullname'=>xhtml::fullname($item, false), 'date'=>$item->created_at->format('d.m.Y'))) !!}</p>
                            <a href="{!!$item->uri!!}" title="{!!$item->title!!}">
                                <h3>{!!$item->title!!}</h3>
                            </a>
                            <p>{!! str_limit(strip_tags($item->content)) !!}</p>
                        </div>
                    </div>
                @else

                    <div class="txtdoc text clearfix">
                        @if(isset($item->uri) && !empty($item->uri) && isset($item->title) && !empty($item->title) && isset($item->cache) && !empty($item->cache))
                            <div>
                                <a href="{!!$item->uri!!}" title="{{ $item->title }}">
                                    <img class="text-section image" alt="{!!$item->title!!}" src="{!!$item->cache!!}"/>
                                </a>
                            </div>
                        @endif
                        <div>
                            <h2>{!! $item->crumbs !!}</h2>
                            <p class="author">{!! trans('locale.submitted', array('fullname'=>xhtml::fullname($item, false), 'date'=>$item->created_at->format('d.m.Y'))) !!}</p>
                            <a href="{!!$item->uri!!}" title="{{ $item->title }}">
                                <h3>{{ $item->title }}</h3>
                            </a>
                            <p>{!! str_limit(strip_tags($item->content)) !!}</p>
                        </div>
                    </div>
                @endif
            @endforeach

            <span class="pull-right">{!! $pages->render() !!}</span>
        </div>

    </div>
</div> --}} 