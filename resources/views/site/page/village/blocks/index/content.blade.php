@if(!empty($page->content))
    <p>{!!$page->content!!}</p>
    <br/>
    <div class="line"></div>
@endif


<ul id="alpha" class="items alpha">
    @foreach($villages as $letter => $items)
        <li><a href="#{!!$letter!!}">{!!$letter!!}</a></li>
    @endforeach
    <br class="clear"/>
</ul>

<div class="col-md-4 names">
    @foreach($villages as $letter => $items)
	    @if ((in_array($letter, array('G', 'Κ', 'M', 'Π'))))
	        </div>
	        <div class="col-md-4 names">
	    @endif

	    <h3><a id="{!!$letter!!}" class="letter">{!!$letter!!}</a><a class="back" href="#alpha"><i class="glyphicon glyphicon-menu-up"></i></a></h3>
	    <hr class="gray"/>
	    <ul class="items">
	        @foreach($items as $item)
	            <li>
		            @if ($item->count)
			            <a href="{!!route(Router::getControllerUrl('entry'), App\Models\Translation::slug($item->village_name))!!}" title="{{ $item->village_name }}">{!!$item->village_name!!}</a>
			            ({!!$item->count!!})
		            @else
		            	{!!$item->village_name!!}
		            @endif
	            </li>
	        @endforeach
	    </ul>
    @endforeach
</div>

<div class="inner-page__content content-inner text-first-screen">
	<div class="content-inner__wrap">
		<section class="content-inner__text inner-main-text">
			<div class="inner-main-text__label section-label">All in the family</div>
			<h1 class="inner-main-text__title">Kytherian Family Names</h1>
			<p class="inner-main-text__paragraf">
			@if(!empty($page->content))
				{!!$page->content!!}
			@endif	
			</p>
		</section>

		<ul id="alpha" class="items alpha">
			@foreach($villages as $letter => $items)
				<li><a href="#{!!$letter!!}">{!!$letter!!}</a></li>
			@endforeach
			<br class="clear"/>
		</ul>

		<div class="names">
			<ul class="names__letters">
				@foreach($villages as $letter => $items)
					<li><a href="#{!!$letter!!}">{!!$letter!!}</a></li>
				@endforeach
			</ul>
			<div class="names__wrap">
				@foreach($villages as $letter => $items)
					@if ((in_array($letter, array('G', 'Κ', 'M', 'Π'))))
						</div>
						<div class="col-md-4 names">
					@endif
					<div class="column-names__top">
						<div class="column-names__letter"><a id="{!!$letter!!}" class="letter">{!!$letter!!}</a></div>
					</div>

					<h3><a id="{!!$letter!!}" class="letter">{!!$letter!!}</a><a class="back" href="#alpha"><i class="glyphicon glyphicon-menu-up"></i></a></h3>
					<hr class="gray"/>
					<ul class="column-names__list">
						<li class="column-names__item">
								@foreach($items as $item)
								<li>
									@if ($item->count)
										<a href="{!!route(Router::getControllerUrl('entry'), App\Models\Translation::slug($item->village_name))!!}" title="{{ $item->village_name }}">{!!$item->village_name!!}</a>
										({!!$item->count!!})
									@else
										{!!$item->village_name!!}
									@endif
								</li>
	        					@endforeach
						</li>
						
					</ul>
				@endforeach

				<div class="names__column column-names">
					<div class="column-names__top">
						<div class="column-names__letter">A</div>
					</div>
					<ul class="column-names__list">
						<li class="column-names__item">
								@foreach($items as $item)
								<li>
									@if ($item->count)
										<a href="{!!route(Router::getControllerUrl('entry'), App\Models\Translation::slug($item->village_name))!!}" title="{{ $item->village_name }}">{!!$item->village_name!!}</a>
										({!!$item->count!!})
									@else
										{!!$item->village_name!!}
									@endif
								</li>
	        					@endforeach
							<span class="column-names__name">Acropolis</span>
							<span class="column-names__number">101</span>
						</li>
						
					</ul>
				</div>
				
			
				
			</div>
		</div>
	</div>
</div>