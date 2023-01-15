
<section class="posts">
        <div class="posts__container">
            <div class="posts__top">
                <h4 class="posts__title section-label">Popular posts</h4>
                <div class="posts__arrows">
                    <button type="button" class="button-prev">
                        <svg class="icon-arrow-slider" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.826 17.386">
                            <g id="Group_12" data-name="Group 12" transform="translate(-833.866 0.693)">
                                <path id="Path_39" data-name="Path 39" d="M848.614,724l7.69,8-7.69,8" transform="translate(0 -724)" fill="none" stroke="#C9503F" stroke-miterlimit="10" stroke-width="2" />
                                <line id="Line_2" data-name="Line 2" x1="21.613" transform="translate(833.866 8)" fill="none" stroke="#C9503F" stroke-miterlimit="10" stroke-width="2" />
                            </g>
                        </svg>
                    </button>
                    <button type="button" class="button-next">
                        <svg class="icon-arrow-slider" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.826 17.386">
                            <g id="Group_12" data-name="Group 12" transform="translate(-833.866 0.693)">
                                <path id="Path_39" data-name="Path 39" d="M848.614,724l7.69,8-7.69,8" transform="translate(0 -724)" fill="none" stroke="#C9503F" stroke-miterlimit="10" stroke-width="2" />
                                <line id="Line_2" data-name="Line 2" x1="21.613" transform="translate(833.866 8)" fill="none" stroke="#C9503F" stroke-miterlimit="10" stroke-width="2" />
                            </g>
                        </svg>
                    </button>
                </div>
                <a href="recent-posts" class="posts__link-view view-link">
                    View All Posts
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.132 16.693">
                        <g id="Group_23" data-name="Group 23" transform="translate(-1292.865 -1328.645)">
                            <g id="Group_22" data-name="Group 22">
                                <path id="Path_40" data-name="Path 40" d="M1307.614,1328.991l7.691,8-7.691,8" fill="none" stroke="#24646d" stroke-miterlimit="10" stroke-width="1" />
                            </g>
                            <line id="Line_3" data-name="Line 3" x1="21.613" transform="translate(1292.865 1336.991)" fill="none" stroke="#24646d" stroke-miterlimit="10" stroke-width="1" />
                        </g>
                    </svg>
                </a>
            </div>
        </div>
        <div class="posts__slider cards-posts">
            <div class="cards-posts__swiper">
                @foreach($items as $i=>$item)
                    <article class="cards-posts__item">
                        <div class="cards-posts__image">
                            <picture>
                            
                                @if($item->image !== '')
                                    <a href="{!!$item->uri!!}">
                                        <picture>
                                            <source srcset="{!!$item->image!!}" type="image/webp">
                                            <img src="{!!$item->image!!}" alt="{!!$item->title!!}">
                                        </picture>
                                    </a>
                                @else
                                    <a href="{!!$item->uri!!}">
                                        <picture>
                                            <source srcset="../assets/img/cards-img.webp" type="image/webp">
                                            <img src="img/cards-img.jpg?_v=1655485994518" alt="{!!$item->title!!}">
                                        </picture>
                                    </a>
                                @endif
                            </picture>
                        </div>

                        <div class="cards-posts__info info-article-one">
                            <a href="{!!$item->uri!!}" >
                                <h4 class="cards-posts__title">
                                    {!!$item->title!!}
                                </h4>
                            </a>
                            
                            <p class="cards-posts__text">
                                {!!$item->content!!}
                            </p>
                            <div class="cards-posts__bottom">
                                <div class="cards-posts__date">
                                    <time datetime="{{ $item->created_at->format('Y-m-d')}} ">{{ $item->created_at->format('Y.m.d')}}</time>
                                    <span class="cards-posts__autor">Stephen Tryfyllis</span>
                                </div>
                                <span class="cards-posts__description">Photos, Churches, Icons</span>
                            </div>
                        </div>
                    </article>
                @endforeach
        </div>
    </div>
</section>