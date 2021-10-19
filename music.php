<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Музыка Miss Po</title>
    <meta name="description", content="Новая браузерная онлайн-игра, в которую можно играть с компьютера и мобильного телефона!">
    <meta name="keywords" content="войнушка, онлайн-игра, игра онлайн, онлайн, игра, компьютера, мобильного, телефона, играть, браузерная, новая, игрок, ролевая, стратегия"/>
    <meta name="robots" content="all"/>
    <meta name="author" content="misspo">
    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="text-info text-center">
                Скачайте музыку или ставьте прямо с сайта на выступление (при наличие хорошего интернет - соединения)
            </h2>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-12"><?
function music_new($name, $path) { ?>
    <p class="text-danger"><?= $name ?></p>
    <audio controls>
        <source src="music/<?= $path ?>" type="audio/ogg; codecs=vorbis">
        <source src="music/<?= $path ?>" type="audio/mpeg">
        Тег audio не поддерживается вашим браузером.
        <a href="music/<?= $path ?>">Скачайте музыку</a>.
    </audio><?
}
switch ($_GET['s']) {
    case 'quest_lady':
        music_new("Quest Lady", "quest_lady.mp3");
        break;

    default:
        music_new("Невеста (misspo.ru)", "Nevesta - misspo.ru.mp3");
        break;
} ?>
        </div>
    </div>
</div>
</body>
</html>