<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Статистика Rest API project</title>
    <meta name="description" content="HTML5">
    <meta name="author" content="Author">

    <link rel="stylesheet" href="css/styles.css?v=1.0">

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>
<h3>Статистика по вызовам поставщиков</h3>
@foreach($arrayClients as $key=>$item)
    <span>Класс: {{$key}}, успешно вызывался => {{$item}} раз</span><br>
@endforeach

<br>
<h3>Пример вызова основного API для инн: 0276073077</h3>

@php
    echo '<pre>';
      if (isset($json))
        var_export($json);
      else
        echo 'Вернулся NULL';
    echo '</pre>';
@endphp

</body>
</html>
