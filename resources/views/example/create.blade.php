@extends('layouts.main')

@section('content')

    <h4 class="h-remove-top">
        <a href="{{ route('examples.index') }}">Examples</a> \
        <a href="{{ route('examples.create') }}">New Example</a>
    </h4>

    <form method="POST" action="{{ route('examples.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            @error('title')
                <div class="warning">{{ $message }}</div>
            @enderror
            <input name="title" class="form-control" type="text" id="title" value="{{ old('title') }}">
        </div>

        <div class="mb-3">
            <label for="body" class="form-label">Body</label>
            @error('body')
                <div class="warning">{{ $message }}</div>
            @enderror
            <textarea name="body" class="form-control" id="body">{{ old('body') }}</textarea>
        </div>


        <input class="btn btn-primary" type="submit" value="Submit">
    </form>

@endsection
