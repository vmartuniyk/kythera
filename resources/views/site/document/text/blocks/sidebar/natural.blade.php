<!-- natural -->
@if (count($items))
    
    <hr class="line gray clear">
    <h3 class="h3">Natural History Museum</h3>
    
    @foreach($items as $i=>$item)
        <div class="item">
           <div class="col-md-6 w35">
               <div>
                   <img src="{!!$item->image!!}" alt="{!!$item->title!!}">
               </div>
           </div>
           <div class="col-md-6 w65">
                @if (Config::get('app.debug'))
                <h2>{!!$item->id!!}:{!!$item->crumbs!!}</h2>
                @else
                <h2>{!!$item->crumbs!!}</h2>
                @endif
        		<h3><a href="{!!$item->uri!!}" title="{!!$item->title!!}">{!!$item->title!!}</a></h3>
           </div>
           <br class="clear">
        </div>
    @endforeach
    
@endif
<!-- /natural -->

<?php return; ?>

                   <hr class="line gray mt40">
	               <h3 class="h3">Natural History Museum</h3>
                   <div class="item">
                       <div class="col-md-6 w35">
                           <img src="http://lorempixel.com/640/480">
                       </div>
                       <div class="col-md-6 w65">
                           <h2>Natural History Museum, Birds</h2>
                           <h3>Picture: Little Owl</h3>
                       </div>
                       <br class="clear">
                   </div>
                   
                   <div class="item">
                       <div class="col-md-6 w35">
                           <img src="http://lorempixel.com/640/480">
                       </div>
                       <div class="col-md-6 w65">
                           <h2>Natural History Museum, Birds</h2>
                           <h3>Picture: Little Owl</h3>
                       </div>
                       <br class="clear">
                   </div>
                   
                   <div class="item last">
                       <div class="col-md-6 w35">
                           <img src="http://lorempixel.com/640/480">
                       </div>
                       <div class="col-md-6 w65">
                           <h2>Natural History Museum, Birds</h2>
                           <h3>Picture: Little Owl</h3>
                       </div>
                       <br class="clear">
                   </div>


