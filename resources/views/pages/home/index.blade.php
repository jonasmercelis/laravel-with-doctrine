@extends('shared.document')
@section('pageTitle', 'Home')
@section('content')
<div>
    <h2>Home</h2>
    <div>
        <a href="{{ route('articles.index') }}">Articles</a>
    </div>
</div>
@endsection

