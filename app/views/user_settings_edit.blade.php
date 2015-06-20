<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        {{ Form::open(array('url' => 'user-settings/'.$hash.'/update', 'files' => true)) }}
        <div>{{Form::label('name', 'Имя');}}{{Form::text('name');}}</div>
        <div>{{Form::label('image', 'Фото');}}{{Form::file('image');}}</div>
        <div>{{Form::label('rating', 'Рейтинг');}}{{Form::text('rating');}}</div>
        <div>{{Form::label('social_integration', 'Соц. интеграция');}}{{Form::text('social_integration');}}</div>
        <div>{{Form::label('interests', 'Интересы');}}{{Form::text('interests');}}</div>
        <div>{{Form::submit('Отправить данные!');}}</div>
        {{ Form::close() }}
    </body>
</html>
