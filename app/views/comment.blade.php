<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Создание и редактирование нового комментария</title>
</head>
<body>
@if (isset($id))
{{ Form::open(array('url' => 'api/comments/'.$id.'?hash='.$hash)) }}
@else
{{ Form::open(array('url' => 'api/comments?hash='.$hash)) }}
@endif
<div>
    {{Form::label('user_id', 'Пользователь');}}
    <select name = "user_id">

        @foreach ($users as $user)
        @if ($comment->user_id == $user->id)
            <option value='{{$user->id}}' selected="selected">{{$user->email}}</option>
        @else
        <option value='{{$user->id}}'>{{$user->email}}</option>
        @endif

        @endforeach
    </select>
</div>
<div>
    {{Form::label('achievement_id', 'Достижение');}}
    <select name = "achievement_id">

        @foreach ($achievements as $achievement)
        @if ($comment->achievement_id == $achievement->id)
        <option value='{{$achievement->id}}'  selected="selected">{{$achievement->title}}</option>
        @else
        <option value='{{$achievement->id}}'>{{$achievement->title}}</option>
        @endif
        @endforeach
    </select>
</div>
<div>{{Form::label('text', 'Текст: ');}}{{Form::textarea('text');}}</div>
<div>{{Form::submit('Отправить данные!');}}</div>
{{ Form::close() }}
</body>
</html>