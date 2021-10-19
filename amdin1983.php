<?php
$title = "Главная";
require_once "inc/header.php";
$users->no_reg();

$admin = new Admin(1983);
$admin->AdminError("Стучался в админку", "Admin");

if ($admin->returnAdmin()) {
    switch ($_GET['s']) {
        default: ?>
            <a href="?s=lvl">Добавить уровни</a><br>
            <a href="?s=addBaze">Добавить домики в базу</a><br>
            <a href="?s=log">Смотреть логи</a><?php
            break;

        case 'lvl':
            $lvl_all = $sql->getAll("select * from lvl order by lvl desc limit ?i", 1);
            foreach ($lvl_all as $lvl) {
                echo $lvl['lvl'];
            }
            if ($lvl['lvl'] <= 10) $exp_plus = round(($lvl['exp'] * 1.8) / 1.5);
            elseif ($lvl['lvl'] > 10) $exp_plus = round(($lvl['exp'] * 1.7) / 1.5);
            elseif ($lvl['lvl'] > 20) $exp_plus = round(($lvl['exp'] * 1.5) / 3);
            elseif ($lvl['lvl'] > 30) $exp_plus = round(($lvl['exp'] * 1.3) / 3);
            elseif ($lvl['lvl'] > 40) $exp_plus = round(($lvl['exp'] * 1.3) / 4);
            elseif ($lvl['lvl'] > 50) $exp_plus = round(($lvl['exp'] * 1.2) / 5);
            elseif ($lvl['lvl'] > 60) $exp_plus = round(($lvl['exp'] * 1.1) / 6);
            elseif ($lvl['lvl'] > 70) $exp_plus = round(($lvl['exp'] * 1) / 8);
            elseif ($lvl['lvl'] > 80) $exp_plus = round(($lvl['exp'] + 50000) / 10);
            if (isset($_POST['add_lvl'])) {
                $lvl = $filter->clearInt($_POST['lvl']);
                $lvl = round($lvl);
                $exp = $filter->clearInt($_POST['exp']);
                $exp = round($exp);
                $sql->query("insert into lvl set lvl = ?i, exp = ?i", $lvl, $exp);
                $site->session_err("", "?s=lvl");
            } else { ?>
                <form action="?s=lvl" method="post">
                    <input name="lvl" type="number" value="<?= round($lvl['lvl'] + 1) ?>"> LVL<br>
                    <input name="exp" type="number" value="<?= $exp_plus ?>"> EXP<br>
                    <input name="add_lvl" type="submit" value="Добавить">
                </form><?php
            }
            break;

        case 'add_res':
            $smotr = new Smotr();
            if (isset($_POST['enter'])) {
                $money1 = $smotr->smotr_id('money');
                $gold1 = $smotr->smotr_id('gold');
                $user_name = $filter->clearFullSpecialChars($_POST['user']);
                $money = $filter->clearInt($_POST['money']);
                $gold = $filter->clearInt($_POST['gold']);
                $site->error_sess($users->getUser("name"), "Изменение $ с {$money1} на {$money}, Gold c {$gold1} на {$gold}", "Admin");
                $sql->query("update users set money = ?i, gold = ?i where name = ?s limit ?i", $money, $gold, $user_name, 1);
                $site->session_err("Успешно!", "?s=add_res&user={$user_name}");
            } else { ?>
                <form action="?s=add_res&user=<?= $_GET['user'] ?>" method="post">
                    <label>
                        <input name="user" type="text" value="<?= $smotr->smotr_id('name') ?>"> Кому
                    </label><br>
                    <label>
                        <input name="money" type="number" value="<?= $smotr->smotr_id("money") ?>"> $
                    </label><br>
                    <label>
                        <input name="gold" type="number" value="<?= $smotr->smotr_id('gold') ?>"> Gold
                    </label><br>
                    <input name="enter" type="submit" value="Добавить">
                </form><?php
            }
            break;

        case 'reface_user':
            if (isset($_FILES['file'])) {
                // проверяем, можно ли загружать изображение
                $check = $site->can_upload($_FILES['file']);
                if($check === true) {
                    $komu = $filter->clearFullSpecialChars($_POST['user']);
                    $money = 1000;
                    $new_money = $filter->clearInt($users->getUser('gold') - $money);
                    // загружаем изображение на сервер
                    $site->make_upload($_FILES['file']);
                    echo "<strong>Файл успешно загружен!</strong>";
                    $sql->query("update users set avatar = ?s where name = ?s limit ?i", $site->upload_file_name(), $komu, 1);
                    exit;
                } else {
                    // выводим сообщение об ошибке
                    echo "<strong>$check</strong>";
                }
            } else { ?>
                <div class="col-xs-12 text-center text-info">
                    <p>
                        Смена аватарки стоит 1000 Gold. Загружать можно форматы jpg png jpeg bmp gif
                    </p>
                </div>
                <form action="?s=reface_user" method="post" enctype="multipart/form-data">
                    <input name="user" type="text" value="<?= $_GET['user'] ?>">
                    <input type="file" name="file">
                    <input type="submit" value="Загрузить файл!">
                </form><?php
            }
            break;

        case 'log':
            $log = $sql->getAll("select * from log order by id desc limit ?i", 30);
            foreach ($log as $l) {
                echo "<a href='user.php?s=smotr&user={$l['kto']}'>{$l['kto']}</a> | {$l['gde']} | {$l['text']} | {$l['r_date']} {$l['r_time']} | {$l['tip']} | {$l['ip']} | {$l['soft']}<hr>";
            }
            break;

        case 'addBaze':
            if (isset($_POST['enter'])) {
                try {
                    if (!$_POST['name']) {
                        throw new Exception('Название пустое');
                    } else {
                        $name = $filter->clearFullSpecialChars($_POST['name']);
                    }
                    if (!$_POST['min_lvl'] || !is_numeric($_POST['min_lvl']) || $_POST['min_lvl'] <= 0) {
                        throw new Exception('min_lvl пустое');
                    } else {
                        $min_lvl = $filter->clearInt($_POST['min_lvl']);
                    }
                    if (!$_POST['cena'] || !is_numeric($_POST['cena']) || $_POST['cena'] <= 0) {
                        throw new Exception('cena пустое');
                    } else {
                        $cena = $filter->clearInt($_POST['cena']);
                    }
                    if (!$_POST['type']) {
                        throw new Exception('Type пустое');
                    } else {
                        $type = $filter->clearFullSpecialChars($_POST['type']);
                    }
                    $sql->query("insert into Baza set name = ?s, min_lvl = ?i, type = ?s, cena = ?i", $name, $min_lvl, $type, $cena);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            } else { ?>
                <form action="?s=addBaze" method="post">
                    <label>
                        <input name="name" type="text" value="name"> Name
                    </label><br>
                    <label>
                        <input name="min_lvl" type="number" value="min_lvl"> min lvl
                    </label><br>
                    <label>
                        <input name="type" type="text" value="type"> Type
                    </label><br>
                    <label>
                        <input name="cena" type="number" value="cena"> $
                    </label><br>
                    <input name="enter" type="submit" value="Добавить">
                </form><?php
            }
            break;
    }
}
require_once "inc/footer.php";