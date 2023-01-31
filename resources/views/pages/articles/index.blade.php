@php
/** @var \Doctrine\Common\Collections\Collection<int, \App\Entities\Article> $articles */
@endphp
@extends('shared.document')
@section('content')
    <div>
        <div style="display: flex; justify-content: space-between; align-items: baseline;">
            <div>
                <h2>Articles ({{ $articles->count() }})</h2>
            </div>
            <div>
                <a href="{{ route('articles.create') }}">Add article</a>
            </div>
        </div>
        <hr>
        @foreach($articles as $article)
            <div
                style="
                margin: 3px;
                background-color: #a0aec0;
                padding: 4px;
                border-radius: 2px;
                "
            >
                <div>
                    {{ $article->getTitle() }} | Written by: {{ $article->getAuthor()?->getEmail() ?? 'No author' }}
                </div>
                <div>
                    <a href="{{ route('articles.show', ['slug' => $article->getSlug()]) }}">Show</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
