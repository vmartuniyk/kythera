<?php
$comments = null;
$comments = $comments ?: App\Models\Comment::whereEntity($item);
$count = App\Models\Comment::whereEntityCount($item)
?>
<div class="entry-comments">
    <div class="entry-comments__top">
        <button type="button" class="entry-comments__show-all">Condense All Comments (<span>{{ $count  }}</span>)</button>
        <svg xmlns="http://www.w3.org/2000/svg" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
            <path data-name="Path 123" d="M344,177.74l7.691,8-7.691,8" transform="translate(194.433 -343.279) rotate(90)" fill="none" stroke="#24646d" stroke-miterlimit="10" stroke-width="2" />
        </svg>
    </div>
    <div class="entry-comments__wrap">
        @foreach ($comments as $comment)
            <div class="entry-comments__item">
                <h4 class="entry-comments__title">{{$comment->created_at->format('d.m.Y') }} &bull; {{$comment->firstname}} {{$comment->lastname}}</h4>
                <p class="entry-comments__text">
                    {{ strip_tags($comment->comment) }}
                </p>
            </div>
        @endforeach
    </div>
</div>