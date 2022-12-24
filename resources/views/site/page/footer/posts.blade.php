<h5>{!!$title!!}</h5>
@foreach($items as $i=>$item)
    <div>
        <h6><a href="{!!$item->uri!!}" title="{!!$item->title!!}">{!!$item->title!!}</a></h6>
        <p>{!!$item->content!!}</p>
    </div>
@endforeach



<!-- <h5>latest postSx</h5>
<div>
    <h6>Post #1</h6>
    <p>Vivamus ipsum metus, lobortis nec rhoncus id, lacinia in sem. Pellentesque at dignissim odio.</p>
</div>
<div>
    <h6>Post #1</h6>
    <p>Vivamus ipsum metus, lobortis nec rhoncus id, lacinia in sem. Pellentesque at dignissim odio.</p>
</div>
<div>
    <h6>Post #1</h6>
    <p>Vivamus ipsum metus, lobortis nec rhoncus id, lacinia in sem. Pellentesque at dignissim odio.</p>
</div>
<div>
    <h6>Post #1</h6>
    <p>Vivamus ipsum metus, lobortis nec rhoncus id, lacinia in sem. Pellentesque at dignissim odio.</p>
</div> -->
