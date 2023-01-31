@php
    /** @var \App\Entities\Article $article */
    /** @var string $convertedHtml */
@endphp
@extends('shared.document')
@section('content')
<div>
    <div style="display: flex; justify-content: space-between; align-items: baseline;">
        <div>
            <h2>Article</h2>
        </div>
        <div>
            <a href="{{ route('articles.edit', ['slug' => $article->getSlug()]) }}">Edit article</a>
        </div>
    </div>
    <hr>
    <strong>Author: </strong> {{ $article->getAuthor()?->getEmail() }}
    <div>
        @if($convertedHtml !== null)
            {!! $convertedHtml !!}
        @else
            Article has no text.
        @endif
    </div>
</div>
@endsection
