@if (Auth::user()->is_favorite($microposts->id))
    {!! Form::open(['route' => ['user.unfavorite', $microposts->id], 'method' => 'delete']) !!}
        {!! Form::submit('Unfavorite', ['class' => "btn btn-danger"]) !!}
    {!! Form::close() !!}
@else
    {!! Form::open(['route' => ['user.add_favorite', $microposts->id]]) !!}
        {!! Form::submit('Favorite', ['class' => "btn btn-primary"]) !!}
    {!! Form::close() !!}
@endif