<!DOCTYPE html>
<html lang="<?php echo $lang ?>">
<head>
    <title><?php echo $title ?></title>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="private">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta name='viewport' content='width=device-width, maximum-scale=1.0, initial-scale=1.0, user-scalable=no'>
    <meta name="description" content="<?php echo $desc ?>">
    <meta name="keywords" content="<?php echo $keywords ?>">

    <link rel="stylesheet" href="/themes/custom/css/style.min.css?8423 ?> /">

    <link rel="icon" href="/themes/custom/favicon.ico" sizes="16x16">
</head>
<body>
    <!--- Header --->
<?php $this->template('header') ?>

    <!--- Content --->
<?php echo $content ?>

    <!--- Footer --->
<?php $this->template('footer') ?>

<?php $this->render('layout', true, true) ?>
</doby>
</html>
