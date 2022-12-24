@extends("admin.layout.default")
@section("content")
    <div class="import">
        <div class="col-md-8">
        
            <h1>{{ $item[1]->language01 }}({!! $entry_id !!})</h1>
            <p>{!!URL::current()!!}</p>
            <p>Created: {!! $item[2]->created !!}
            <br/>Last change: {!! $item[2]->lastchange !!}
            @if (isset($item[2]->date))
            <br/>{!! $item[2]->date->diffForHumans() !!}
            @endif
            </p>
            
            @if (isset($gallery))
            {!! $gallery[0] !!}
            @endif
            
            @if ($audio)
            <br/>
            <br/> Listen {!! $audio[0] !!}
            <br/>
            <br/>
            @endif
            
            <p>{!! nl2br($item[2]->language01) !!}</p>
            
            
        </div>
        <div class="col-md-4">
            @if (isset($person))
                <h2>Author({!!$person->persons_id!!})</h2>
                <br/>{!! $person->firstname !!} {!! $person->middlename !!} {!! $person->lastname !!}
                <br/>{!! $person->email !!}
                <br/>{{-- $person->nickname --}}
                <br/>{{--$person->login--}}
                <br/>{{--$person->password--}}
            @endif
            
            @if (isset($village))
                <h2>Village({!!$village->info->id!!})</h2>
                <br/>{!! $village->info->village_name !!}
                @if (isset($village->compounds))
                    @foreach($village->compounds as $language=>$names)
                        @foreach($names as $name)
                            <br/>{!!$name->village_name!!}
                        @endforeach
                    @endforeach
                @endif

                @if (isset($village->info->image_filename))
                <br/>
                <br/><img src="http://www.kythera-family.net/{!!$village->info->image_filename!!}" width="150" />
                @endif
                
                <br/>{!! $village->info->title_id !!}
                <br/>{!! $village->info->description_id !!}
            @endif
        </div>
    </div>
@stop