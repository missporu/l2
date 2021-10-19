<?php
$title = "Регистрация";
require_once "inc/header.php";

$users->reg();
$refer = "";
if (!empty($_GET['ref'])) {
    $refer = $filter->clearFullSpecialChars($_GET['ref']);
}

if (isset($_POST['enter'])) {
    if (!empty($_POST['log'])) {
        if (strlen(trim($_POST['log'])) < 3 || trim($_POST['log']) == "") {
            $site->session_err("Поле 'Имя' должно быть от 3х символов", "?");
        }
        $name = $filter->clearFullSpecialChars($_POST['log']);
        if (is_numeric($name)) {
            $site->session_err("Имя не может быть числом");
        }
        $usr = $sql->getRow("select name from users where name = ?s limit ?i",$name,1);
        if ($name == $usr['name']) {
            $site->session_err("Это имя занято");
        }
    } else {
        $name = null;
        $site->session_err("Не заполнено поле 'Имя'");
    }
    if (!empty($_POST['pass']) || !empty($_POST['pass2'])) {
        if ($_POST['pass'] != $_POST['pass2']) {
            $site->session_err("Пароли не совпадают!");
        }
        if (strlen($_POST['pass']) < 7 || trim($_POST['pass']) == "" || strlen($_POST['pass2']) < 7 || trim($_POST['pass2']) == "") {
            $site->session_err("Поле 'Пароль' должно быть от 7 символов");
        }
        $pass2 = $filter->clearFullSpecialChars($_POST['pass2']);
        $pass = $filter->clearFullSpecialChars($_POST['pass']);
        $pass = password_hash($pass, PASSWORD_DEFAULT);
    } else {
        $pass = null;
        $site->session_err("Не заполнено поле 'Пароль'");
    }
    if (filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
        $email_b = $_POST['mail'];
        $usm = $sql->getRow("select mail from users where mail = ?s limit ?i",$email_b,1);
        if ($email_b == $usm['mail']) {
            $site->session_err("Эта почта уже используется");
        }
    } else {
        $email_b = null;
        $site->session_err("E-mail адрес указан неверно.");
    }
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $ip = $site->getIp();
    if (!empty($_GET['ref'])) {
        $referal = $sql->getRow("select name, money, ip from users where name = ?s", $refer);
        if ($referal['ip'] == $ip) {
            $site->session_err("Нельзя твинить рефералов");
        }
        $add_money = $filter->clearInt($referal['money'] + 100);
        $sql->query("update users set money = ?i where name = ?s limit ?i", $add_money, $referal['name'], 1);
    }
    $sql->query("insert into users set name = ?s, pass = ?s, mail = ?s, ip = ?s, soft = ?s, ppus = ?s, reg_time = ?s, reg_data = ?s, online = ?s, akadem = ?i, referal = ?s", $name, $pass, $email_b, $ip, $agent, $pass2, $site->getTime(), $site->getDate(), time(), 0, $refer);
    //$sql->query("insert into enter set user_id = ?i, user_name = ?s, ip = ?s, soft = ?s, time_w = ?s, date_w = ?s", $name, $ip, $agent, $site->getTime(), $site->getDate());

    $site->session_err("", "enter.php");
} else { ?>
    <div class="col-xs-12"><hr>
        <form role="form" action="" method="POST">
            <div class="form-group">
                    <label for="exampleInputEmail1">От 3 символов</label>
                    <input type="text" name="log" class="form-control" id="exampleInputEmail1" placeholder="Введите логин..." required>
            </div>
            <div class="form-group">
                    <label for="exampleInputEmail1">От 5 символов</label>
                    <input type="password" name="pass" class="form-control" id="exampleInputEmail1" placeholder="Введите пароль" required>
            </div>
            <div class="form-group">
                    <label for="exampleInputEmail1">От 5 символов</label>
                    <input type="password" name="pass2" class="form-control" id="exampleInputEmail1" placeholder="Подтвердите пароль" required="">
            </div>
            <div class="form-group">
                    <label for="exampleInputEmail1">E-mail</label>
                    <input type="email" name="mail" class="form-control" id="exampleInputEmail1" placeholder="Введите почту" required="">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Пригласивший</label>
                <input type="text" name="refer" class="form-control" id="exampleReferer" placeholder="<?= $refer ?>" disabled>
            </div>
                <input type="submit" name="enter" class="btn btn-danger btn-block text-center" value="Регистрация"><br>
        </form>
    </div>
    <div class="col-xs-6">
        <a class="btn btn-info btn-block text-center" href="restore.php">Забыли пароль?</a>
    </div>
    <div class="col-xs-6">
        <a class="btn btn-info btn-block text-center" href="enter.php">Вход</a>
    </div><?php
}


require_once "inc/footer.php";