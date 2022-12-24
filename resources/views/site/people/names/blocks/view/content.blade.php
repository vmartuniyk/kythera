<div class="line"></div>

<h1>&gt; {{ $entry->name }}</h1>

<br>

@foreach ($results as $items)
    {!!xhtml::crumbs($items[0]->page, ', ', true)!!} ({!!count($items)!!})
    <ul>
        @foreach ($items as $i=>$item)
        <li>
        <a href="{!!$item->uri!!}">{!!$item->item->title!!}</a>
        </li>
        @endforeach
    </ul>
@endforeach
