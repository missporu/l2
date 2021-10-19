<?php
date_default_timezone_set('Europe/Moscow');
session_start();
ob_start();
require_once "function.php";
$admin1983 = new Admin(1983); ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo (!empty($title) ? $title : "Игра"); ?></title>
    <meta name="description", content="Новая браузерная онлайн-игра, в которую можно играть с компьютера и мобильного телефона!">
    <meta name="keywords" content="войнушка, онлайн-игра, игра онлайн, онлайн, игра, компьютера, мобильного, телефона, играть, браузерная, новая, игрок, ролевая, стратегия"/>
    <meta name="robots" content="all"/>
    <meta name="author" content="misspo">
    <link rel="icon" href="/war-game.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/war-game.ico" type="image/x-icon">
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
            <h4 class="text-center">
                <?= $title ?>
            </h4>
        </div>
        <div class="clearfix"></div><?php
        if (isset($_SESSION['err'])) { ?>
            <div class="alert alert-warning alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <?= $_SESSION['err'] ?>
            </div><?php
            $_SESSION['err'] = NULL;
        } ?>
        <div class="clearfix"></div><?
        if ($users->cookiesValid() == true) { ?>
            <div class="col-xs-4 text-center text-green"><?= $users->getUser('money') ?> $</div>
            <div class="col-xs-4 text-center text-red"><?= $users->getUser('lvl') ?> LvL</div>
            <div class="col-xs-4 text-center"></div>
            <div class="clearfix"></div><br>
            <div class="col-xs-4 text-center text-gold"><?= $users->getUser('gold') ?> Gold</div>
            <div class="col-xs-4 text-center text-yellow"><?= $users->getUser('exp') ?> EXP</div>
            <div class="col-xs-4 text-center"></div>
            <div class="clearfix"></div>
            <div class="col-xs-12">
                <hr>
            </div>
            <div class="clearfix"></div><?php
            if ($admin1983->returnAdmin()) { ?>
                <div class="col-xs-12 text-center">
                    <a href="./amdin1983.php">GM</a>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12">
                    <hr>
                </div><?php
            }
        }