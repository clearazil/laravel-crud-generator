@extends('layouts.main')

@section('content')

    <h4 class="h-remove-top">
        <a href="{{ route('example.index') }}">Examples</a> \
        <a href="{{ route('example.show', ['example' => $example->id]) }}" title="">{{ $example->title }}</a>
    </h4>

    <a class="btn btn--small" href="{{ route('example.edit', ['example' => $example->id]) }}">Edit</a>
    <form method="POST" action="{{ route('example.delete', ['example' => $example->id])}}">
        @csrf
        @method('DELETE')
        <button class="btn btn--small" href="{{ route('example.delete', ['example' => $example->id]) }}">Delete</button>
    </form>

    <div class="table-responsive">

        <table>
            <tbody>
                <tr>
                    <th>Title</th>
                    <td>{{ $example->title }}</td>
                </tr>
                <tr>
                    <th>Body</th>
                    <td>{!! nl2br(e($example->body)) !!}</td>
                </tr>
                <tr>
                    <th>Created on</th>
                    <td>{{ $example->created_at->isoformat('D-M-Y HH:mm:ss') }}</td>
                </tr>
                <tr>
                    <th>Updated on</th>
                    <td>{{ $example->updated_at->isoformat('D-M-Y HH:mm:ss') }}</td>
                </tr>
            </tbody>
        </table>

    </div>

@endsection
