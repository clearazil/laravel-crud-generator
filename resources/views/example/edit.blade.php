@extends('layouts.main')

@section('content')

    <h4 class="h-remove-top">
        <a href="{{ route('examples.index') }}">Examples</a> \
        <a href="{{ route('examples.show', ['example' => $example->id]) }}">{{ $example->title }}</a> \
        <a href="{{ route('examples.edit', ['example' => $example->id]) }}">Edit</a>
    </h4>

    <form method="POST" action="{{ route('examples.update', ['example' => $example->id]) }}">
        @csrf
        @method('PUT')

        <div>
            <label for="title">Title</label>
            @error('title')
                <div class="warning">{{ $message }}</div>
            @enderror
            <input name="title" class="h-full-width" type="text" id="title" value="{{ old('title', $example->title) }}">
        </div>

        <label for="body">Body</label>
        @error('body')
            <div class="warning">{{ $message }}</div>
        @enderror
        <textarea name="body" class="h-full-width" id="body">{{ (old('body', $example->body)) }}</textarea>

        <input class="btn--primary" type="submit" value="Submit">
    </form>

@endsection
