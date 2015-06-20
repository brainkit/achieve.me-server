<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Создание и редактирование нового комментария</title>
</head>
<body>
{{ Form::open(array('url' => 'api/comments?hash='.$hash)) }}
<div>
    {{Form::label('user_id', 'Пользователь');}}
    <select name = "user_id">
        @foreach ($users as $user)
        <option value='{{$user->id}}'>{{$user->email}}</option>
        @endforeach
    </select>
</div>
<div>
    {{Form::label('achievement_id', 'Достижение');}}
    <select name = "achievement_id">
        @foreach ($achievements as $achievement)
        <option value='{{$achievement->id}}'>{{$achievement->title}}</option>
        @endforeach
    </select>
</div>
<div>{{Form::label('text', 'Текст: ');}}{{Form::textarea('text');}}</div>
<div>{{Form::submit('Отправить данные!');}}</div>
{{ Form::close() }}
</body>
</html>