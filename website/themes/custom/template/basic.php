<!DOCTYPE html>
<html lang="<?= $response->local_html ?>">
<head>
    <title><?= $response->title ?></title>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="private">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta name='viewport' content='width=device-width, maximum-scale=1.0, initial-scale=1.0, user-scalable=no'>
    <meta name="description" content="<?= $response->description ?>">
    <meta name="keywords" content="<?= $response->keywords ?>">

    <link rel="stylesheet" href="/themes/custom/style.css?8423 ?> /">

    <link rel="icon" href="/themes/custom/favicon.ico" sizes="16x16">
</head>
<body>
    <div class="logo">
        <a href="/" title="Главная">
        <img src="/themes/custom/img/logo.png" alt="*" /></a>
    </div>

    <?= $response->content ?>

    <div class="footer">
        <a href="/" title="Онлайн">Онлайн: 1 </a> | 0<br />
        Память: <?= $response->memory ?> кб<br />
        Загрузчик: <?= $response->autoload['counter'] ?> за <?= $response->autoload['timing'] ?> сек<br />
        Генерация: <?= $response->timing ?> сек
    </div>
</doby>
</html>
