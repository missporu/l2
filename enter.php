<?php
$title = "Вход";
require_once "inc/header.php";
$users->reg();

if (isset($_POST['enter'])) {
    $login = $filter->clearFullSpecialChars($_POST['login']);
    $pass = $filter->clearFullSpecialChars($_POST['pass']);
    if (empty($login) || trim($login) == "" || strlen($login) < 3) {
        $site->session_err("Не заполнено поле логин");
    }
    if (empty($pass) || trim($pass) == "" || strlen($pass) < 5) {
        $site->session_err("Не заполнено поле пароль");
    }
    $usr = $sql->getRow("select * from users where name = ?s limit ?i", $login, 1);
    $hash = $usr['pass'];
    $pass_get = password_verify($pass, $hash);
    if ($pass_get == TRUE and $login = $usr['name']) {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $ip = $site->getIp();
        $last_ip = $usr['ip'];
        $sql->query("update users set soft = ?s, ip = ?s where name = ?s",$agent,$ip,$login);
        $sql->query("insert into enter set user_id = ?i, user_name = ?s, ip = ?s, soft = ?s, time_w = ?s, date_w = ?s", $usr['id'], $usr['name'], $ip, $agent, $site->getTime(), $site->getDate());
        setcookie("log", $login, time() + 3600);
        setcookie("id_sess", $hash, time() + 3600);
        $site->session_err("Добро пожаловать!<br>Текущий ip {$ip}, последний вход был с {$last_ip}", "/");
    } else {
        $site->error_sess($login, "пытался зайти на сайт {$pass}", 'novhod');
        $site->session_err("Неверные данные");
    }
} else { ?>
    <div class="col-xs-12"><hr>
        <form role="form" action="?" method="POST">
            <div class="form-group">
                <label for="exampleInputEmail1">От 3 символов</label>
                <input type="text" name="login" class="form-control" id="exampleInputEmail1" placeholder="Введите логин...">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">От 5 символов</label>
                <input type="password" name="pass" class="form-control" id="exampleInputEmail1" placeholder="Введите пароль">
            </div>
            <input type="submit" name="enter" class="btn btn-danger btn-block text-center" value="Войти"><br>
        </form>
    </div><?php
} ?>
    <div class="col-xs-6">
        <a class="btn btn-info btn-block text-center" href="restore.php">Забыли пароль?</a>
    </div>
    <div class="col-xs-6">
        <a class="btn btn-info btn-block text-center" href="reg.php">Регистрация</a>
    </div>
    </div><?php
require_once "inc/footer.php";