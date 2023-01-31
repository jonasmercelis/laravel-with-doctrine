@section('pageTitle', 'New Article')
@extends('shared.document')
@section('content')
    <div>
        <div style="display: flex; justify-content: space-between; align-items: baseline;">
            <div>
                <h2>Add article</h2>
                <a href="{{ route('articles.index') }}">All articles</a>
            </div>
        </div>
        <hr>
        <div>
            <form id="createArticleForm" action="{{ route('articles.store') }}" method="post">
                @csrf
                <div>
                    <div>
                        <label for="title">Title</label>
                    </div>
                    <input
                        id="title"
                        name="title"
                        type="text"
                        value="{{ old('title') }}"
                    >
                </div>
                <div>
                    <div>
                        <label for="createArticleForm">Text</label>
                        <small>(Must be valid GitHub flavoured markdown.)</small>
                    </div>
                    <textarea
                        id="createArticleForm"
                        form="createArticleForm"
                        placeholder="Article content.."
                        name="text"
                        style="width: 100%; min-height: 20vh;"
                    >{{ old('text') }}</textarea>
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
