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
            {!! xmenu::author($item) !!}
            <h1>{{ $item->title }}</h1>
        </div>
    </div>

    <div>
        <a class="view audio" href="{!! $item->audio !!}" title="{{ $item->title }}">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAABD0lEQVRYw+3XsQqDMBAG4L6suogIIoq7ToIuDi4i6C6+gSDo6OjjXEkhoUNibcCYtPdDFk3MB8Z4eTwwGAzG7FiWBVEUwbIsoC2QtqIo7kGGYSiceBxH8DyPIZMkAeU4MvGnfmVZqkdS3BkgSdd1rH+e56AMdxZIUlUVGzNNEyjBfQN8H+/7PijB8YDbtgknX9eVjZvnGS7H8YDkWl3XwsnJ3kj6pGkqD4zjGGhzXVeIEwFJa5qGCxiG4XXftm154BHoLFC0Pvd9l1q/lwDbtoWj598OzLKMi3AcB4G/8Yov+0i032a036iN+9UZUSwYUW7JFqx936srWI0o+WmCIND30GTEsdPogzsGg/n3PAGH4GdMo71vewAAAABJRU5ErkJggg==">
        </a>
        <div class="line"></div>
    </div>

    <p>{!!$item->content!!}</p>
</div>