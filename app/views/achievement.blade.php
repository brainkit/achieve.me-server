<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Создание достижения</title>
</head>
<body>
@if (isset($id))
{{ Form::open(array('url' => 'api/achievements/'.$id.'?hash='.$hash, 'files' => true)) }}
@else
{{ Form::open(array('url' => 'api/achievements?hash='.$hash, 'files' => true)) }}
@endif
<div>{{Form::label('title', 'Название');}}{{Form::text('title');}}</div>
<div>{{Form::label('image', 'Картинка');}}{{Form::file('image');}}</div>
<div>{{Form::label('description', 'Описание');}}{{Form::textarea('description');}}</div>
<div>{{Form::label('points', 'Баллы');}}{{Form::text('points');}}</div>
<div>{{Form::submit('Отправить данные!');}}</div>
{{ Form::close() }}
</body>
</html>
