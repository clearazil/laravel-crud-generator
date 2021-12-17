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

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            @error('title')
                <div class="warning">{{ $message }}</div>
            @enderror
            <input name="title" class="form-control" type="text" id="title" value="{{ old('title', $example->title) }}">
        </div>

        <div class="mb-3">
            <label for="body" class="form-label">Body</label>
            @error('body')
                <div class="warning">{{ $message }}</div>
            @enderror
            <textarea name="body" class="form-control" id="body">{{ (old('body', $example->body)) }}</textarea>
        </div>


        <input class="btn btn-primary" type="submit" value="Submit">
    </form>

@endsection
