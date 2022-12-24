<div class="row hidden-xs small">
        <div class="col-md-6">
        @foreach($items as $i=>$item)
            @if ($i==3)
            </div>
            <div class="col-md-6">
            @endif
            
            <div class="col-md-4 key">
                <!--165/111-->
                    <a href="{!!$item->uri!!}" title="{!!$item->title!!}">
                        <img src="{!!$item->image!!}" alt="{!!$item->title!!}" style="max-height:111px"/>
                    </a>
            
                <div class="text">
                    @if (Config::get('app.debug'))
                    <h2>{!!$item->id!!}:{!!$item->crumbs!!}</h2>
                    @else
                    <h2>{!!$item->crumbs!!}</h2>
                    @endif
                    <p><a href="{!!$item->uri!!}" title="{!!$item->title!!}">{{ $item->title }}</a></p>
                </div>
            </div>
        @endforeach
        </div>
</div>

<?php return ?>

    <div class="row hidden-xs small">
        <div class="col-md-6">
            <div class="col-md-4 key">
               <img src="http://lorempixel.com/165/111">
                <div class="text">
                    <h2>Culture, Bibliography</h2>
                    <p>
                    Kythiraiki Epitheorisis
                    - Kythera Inspection
                    </p>
                </div>
            </div>
            <div class="col-md-4 key">
               <img src="http://lorempixel.com/165/111">
                <div class="text">
                    <h2>Culture, Bibliography</h2>
                    <p>
                    Kythiraiki Epitheorisis
                    - Kythera Inspection
                    </p>
                </div>
            </div>
            <div class="col-md-4 key">
               <img src="http://lorempixel.com/165/111">
                <div class="text">
                    <h2>Culture, Bibliography</h2>
                    <p>
                    Kythiraiki Epitheorisis
                    - Kythera Inspection
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-4 key">
               <img src="http://lorempixel.com/165/111">
                <div class="text">
                    <h2>Culture, Bibliography</h2>
                    <p>
                    Kythiraiki Epitheorisis
                    - Kythera Inspection
                    </p>
                </div>
            </div>
            <div class="col-md-4 key">
               <img src="http://lorempixel.com/165/111">
                <div class="text">
                    <h2>Culture, Bibliography</h2>
                    <p>
                    Kythiraiki Epitheorisis
                    - Kythera Inspection
                    </p>
                </div>
            </div>
            <div class="col-md-4 key">
               <img src="http://lorempixel.com/165/111">
                <div class="text">
                    <h2>Culture, Bibliography</h2>
                    <p>
                    Kythiraiki Epitheorisis
                    - Kythera Inspection
                    </p>
                </div>
            </div>
        </div>
    </div>
