@extends('layout')

@section('content')
    <div class="title m-b-md">
        Saved requests
    </div>
    @component('parse-form')
    @endcomponent
    {{-- TODO: add layout for article table --}}
    <p>Request created at {{ $request->created_at }}</p>
    @foreach ($request->articles as $article)
        <p>Article title is {{ $article->title }}</p>
    @endforeach
    @if (!$request->is_saved)
    {!! Form::open(['action' => ['ParseController@save', $request->id]]) !!}
    <button class="btn btn-success" type="submit">save to db</button>
    {!! Form::close() !!}
    @endif
    @if (!$request->csv_file_link)
        {!! Form::open(['action' => ['ParseController@csv', $request->id]]) !!}
        <button class="btn btn-success" type="submit">save to csv</button>
        {!! Form::close() !!}
    @endif
    @if ($request->csv_file_link)
        <a href="{{$request->csv_file_link}}">Download csv</a>
        {!! Form::open(['action' => ['ParseController@email', $request->id]]) !!}
        <div class="form-group">
            {!! Form::label('receiver', 'Email') !!}
            {!! Form::text('receiver', '', ['class' => 'form-control']) !!}
        </div>
        <button class="btn btn-success" type="submit">send to email</button>
        {!! Form::close() !!}
    @endif


@endsection