<div class="form">
    {!! Form::open(['action' => 'ParseController@parse']) !!}
    <div class="form-group">
        {!! Form::label('limit', 'Limit') !!}
        {!! Form::text('limit', '', ['class' => 'form-control']) !!}
    </div>
    <button class="btn btn-success" type="submit">parse it</button>
    <a href="/" class="btn btn-warning" role="button">clear</a>
    {!! Form::close() !!}
</div>