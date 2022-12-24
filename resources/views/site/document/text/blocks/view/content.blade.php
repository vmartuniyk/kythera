<div class="txtdoc-view text">
    <div class="line"></div>

    <div class="txtdoc clearfix">
        {{-- xmenu::entry_edit($item->user_id, $item) --}}
        <div>
        	<h2>
            @if (Config::get('app.debug'))
            {!!$item->id!!}:
            @endif
            {!! xhtml::crumbs(Router::getSelected(), ' &gt; ', false) !!}
            </h2>
            {{-- <p class="author">{!! trans('locale.submitted', array('fullname'=>xhtml::fullname($item, false), 'date'=>$item->created_at->format('d.m.Y'))) !!}</p> --}}
            {!! xmenu::author($item) !!}
            <h1>{{ $item->title }}</h1>
        </div>
    </div>
<?php //echo '<pre>'; print_r($item); die;?>
    @if($item->image)
        <div class="documentTextImage">
            @if ( !empty($item->lightbox) )
                <a href="{!!$item->lightbox!!}" data-lightbox="{!!$item->title!!}" data-title="{!!$item->title!!}">
                    <img alt="{!!$item->title!!}" src="{!!$item->image!!}" />
                </a>
            
            @endif
            @if ( !empty($item->copyright) )
                <br/><span class="copyright">{!!$item->copyright!!}</span>
            @endif
            <div class="line"></div>
        </div>
    @endif

    <p>{!!$item->content!!}</p>
</div>