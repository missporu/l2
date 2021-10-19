<?php
require_once "require.php";

$sql = new Db();
$site = new Site();
$filter = new Filter();
$users = new User();

if (isset($_COOKIE['log']) AND isset($_COOKIE['id_sess'])) {
    $loginValid = $filter->clearFullSpecialChars($_COOKIE['log']);
    $passwordValid = $filter->clearFullSpecialChars($_COOKIE['id_sess']);
    $user = $sql->getRow("select name, pass from users where name = ?s and pass = ?s limit ?i", $loginValid, $passwordValid, 1);
    if ($user) {
        $usr = $sql->getRow("select * from users where name = ?s and pass = ?s limit ?i", $user['name'], $user['pass'], 1);
    }
}


function aka() {
    global $site, $usr;
    if ($usr['akadem'] <= 6) {
        $text = "Вы не закончили обучение";
        if ($usr['akadem'] == 0) {
            $text = "Добро пожаловать в игру!";
        }
        $site->session_err($text, "akadem.php?aka={$usr['akadem']}");
    }
}