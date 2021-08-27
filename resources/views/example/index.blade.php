@extends('layouts.main')

@section('content')

    <h4 class="h-remove-top"><a href="{{ route('examples.index') }}">Examples</a></h4>

    <a class="btn btn-primary" href="{{ route('examples.create') }}">New Example</a>

    @if (Session::has('message') && Session::has('message-status'))
        <div class="alert {{ Session::get('message-status') === 'error' ? 'alert-danger' : '' }} {{ Session::get('message-status') === 'success' ? 'alert-success' : '' }}" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="table-responsive">

        <table class="table">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Created on</th>
                    <th>Updated on</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($examples as $example)
                        <tr>
                            <td><a href="{{ route('examples.show', ['example' => $example->id]) }}" title="">{{ $example->title }}</a></td>
                            <td>{{ $example->created_at->isoformat('D-M-Y HH:mm:ss') }}</td>
                            <td>{{ $example->updated_at->isoformat('D-M-Y HH:mm:ss') }}</td>
                        </tr>
                    @endforeach
                </tbody>
        </table>

    </div>

    {{ $examples->links() }}

@endsection
