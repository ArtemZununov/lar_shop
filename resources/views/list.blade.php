@extends('layout')

@section('content')
    <div class="title m-b-md">
        Saved requests
    </div>
    {{-- TODO: develop select with requests options and add redirects to needed page --}}
    @foreach ($requests as $request)
        <p>Request created at <a href="/parse-requests/{{ $request->id  }}">{{ $request->created_at }}</a></p>
    @endforeach


@endsection