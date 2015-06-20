<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Создание и редактирование нового типа достижения</title>
</head>
<body>
@if (isset($id))
{{ Form::open(array('url' => 'api/types/'.$id.'?hash='.$hash, 'files' => true)) }}
@else
{{ Form::open(array('url' => 'api/types?hash='.$hash, 'files' => true)) }}
@endif
<div>{{Form::label('name', 'Название: ');}}{{Form::text('name');}}</div>
<div>{{Form::submit('Отправить данные!');}}</div>
{{ Form::close() }}
</body>
</html>