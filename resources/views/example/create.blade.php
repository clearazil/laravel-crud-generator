@extends('layouts.main')

@section('content')

    <h4 class="h-remove-top">
        <a href="{{ route('example.index') }}">Examples</a> \
        <a href="{{ route('example.create') }}">New Example</a>
    </h4>

    <form method="POST" action="{{ route('example.store') }}" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="title">Title</label>
            @error('title')
                <div class="warning">{{ $message }}</div>
            @enderror
            <input name="title" class="h-full-width" type="text" id="title" value="{{ old('title') }}">
        </div>

        <label for="body">Body</label>
        @error('body')
            <div class="warning">{{ $message }}</div>
        @enderror
        <textarea name="body" class="h-full-width" id="body">{{ old('body') }}</textarea>

        <input class="btn--primary" type="submit" value="Submit">
    </form>

@endsection
