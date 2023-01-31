@php
    /** @var \App\Entities\Article $article */
@endphp
@section('pageTitle', 'Edit Article')
@extends('shared.document')
@section('content')
    <div>
        <div style="display: flex; justify-content: space-between; align-items: baseline;">
            <div>
                <h2>Edit article</h2>
                <a href="{{ route('articles.index') }}">All articles</a>
            </div>
        </div>
        <hr>
        <div>
            <form
                id="createArticleForm"
                action="{{ route('articles.update', ['id' => $article->getId()]) }}"
                method="post"
            >
                @csrf
                @method('PATCH')
                <div style="padding-bottom: 8px;">
                    <div>
                        <label for="title">Title</label>
                    </div>
                    <input
                        id="title"
                        name="title"
                        type="text"
                        value="{{ old('title') ?? $article->getTitle() }}"
                    >
                </div>
                <div>
                    <div>
                        <label for="createArticleForm">Text</label> (Must be valid GitHub flavoured markdown.)
                    </div>
                    <textarea
                        id="createArticleForm"
                        form="createArticleForm"
                        placeholder="Article content.."
                        name="text"
                        style="width: 100%; min-height: 20vh;"
                    >{{ old('text') ?? $article->getText() }}</textarea>
                </div>
                @if ($errors->any())
                    <div style="color: red;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div>
                    <input type="submit">
                </div>
            </form>
        </div>
    </div>
@endsection
