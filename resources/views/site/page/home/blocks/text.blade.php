<!-- tourist info -->
@if (count($items))
    
    <hr class="line gray clear">
    <h3 class="h3"><a href="{!!$category!!}">Tourist Information</a></h3>
    
    @foreach($items as $i=>$item)
    
        @if ($i==0)
        <div class="item first">
            <div>
                <!--640/480 -->
                <img src="{!!$item->image!!}" alt="{!!$item->title!!}">
            </div>
            <div class="overlay">
        		<h2>{!!$item->crumbs!!}</h2>
        		<h3><a href="{!!$item->uri!!}" title="{!!$item->title!!}">{!!$item->title!!}</a></h3>
            </div>
           <br class="clear">
        </div>
        @elseif ($i < count($items)-1)
        <div class="item">
           <div class="col-md-6 w35">
               <div>
                    <a href="{!!$item->uri!!}" title="{!!$item->title!!}">
                    <img src="{!!$item->image!!}" alt="{!!$item->title!!}">
                    </a>
               </div>
           </div>
           <div class="col-md-6 w65">
                @if (Config::get('app.debug'))
                <h2>{!!$item->id!!}:{!!$item->crumbs!!}</h2>
                @else
                <h2>{!!$item->crumbs!!}</h2>
                @endif
        		
        		<h3><a href="{!!$item->uri!!}" title="{!!$item->title!!}">{!!$item->title!!}</a></h3>
        		<p>{!!$item->content!!}</p>
           </div>
           <br class="clear">
        </div>
        @else
        <div class="item last">
           <div class="col-md-6 w35">
                    <a href="{!!$item->uri!!}" title="{!!$item->title!!}">
                    <img src="{!!$item->image!!}" alt="{!!$item->title!!}">
                    </a>
           </div>
           <div class="col-md-6 w65">
        		<h2>{!!$item->crumbs!!}</h2>
        		<h3><a href="{!!$item->uri!!}" title="{!!$item->title!!}">{!!$item->title!!}</a></h3>
        		<p>{!!$item->content!!}</p>
           </div>
           <br class="clear">
        </div>
        @endif
    
    @endforeach
    
@endif
<!-- /tourist info -->

<?php return; ?>


                        <hr class="line gray mt40 clear">
                        <h3 class="h3">Tourist information</h3>
                        <!-- tourist -->
                   <div class="item first">
                       <img src="http://lorempixel.com/640/480">
                       <div class="overlay">
                           <h2>Tourist Information, Sightseeing</h2>
                           <h3>Follow the Blue Dots</h3>
                       </div>
                       <br class="clear">
                   </div>
                   
                    <div class="item">
                       <div class="col-md-6 w35">
                           <img src="http://lorempixel.com/640/480">
                       </div>
                       <div class="col-md-6 w65">
                           <h2>Tourist Information, Where to eat</h2>
                           <h3>Virons's Cafe in Mitata</h3>
                           <p>Lorem ipsum dolor sit amet,
consectetur adipiscing elit. Integer in
Libero sed neque sagittis Mollis</p>
                       </div>
                       <br class="clear">
                   </div>
                   
                    <div class="item">
                       <div class="col-md-6 w35">
                           <img src="http://lorempixel.com/640/480">
                       </div>
                       <div class="col-md-6 w65">
                           <h2>Tourist Information, Where to eat</h2>
                           <h3>Virons's Cafe in Mitata</h3>
                           <p>Lorem ipsum dolor sit amet,
consectetur adipiscing elit. Integer in
Libero sed neque sagittis Mollis</p>
                       </div>
                       <br class="clear">
                   </div>
                   
                    <div class="item last">
                       <div class="col-md-6 w35">
                           <img src="http://lorempixel.com/640/480">
                       </div>
                       <div class="col-md-6 w65">
                           <h2>Tourist Information, Where to eat</h2>
                           <h3>Virons's Cafe in Mitata</h3>
                           <p>Lorem ipsum dolor sit amet,
consectetur adipiscing elit. Integer in
Libero sed neque sagittis Mollis</p>
                       </div>
                       <br class="clear">
                   </div>
