<?php
$title = "Профиль";
if ($_GET['s'] == 'setting') {
    $title = "Настройки";
} elseif ($_GET['s'] == 'avatars') {
    $title = "Смена аватара";
} elseif ($_GET['s'] == 'rename') {
    $title = "Смена имени";
} elseif ($_GET['s'] == 'smotr') {
    if (!is_numeric($_GET['user'])) {
        $title = "Профиль игрока {$_GET['user']}";
    } else {
        $title = "Осмотр";
    }
} else {
    $title = "Главная";
}
require_once "inc/header.php";
$users->no_reg();
aka();

$smotr = new Smotr();
$admin1 = new Admin(1);
$admin2 = new Admin(2);
$admin3 = new Admin(3);
$admin4 = new Admin(4);
$admin5 = new Admin(5);
$baza = new Baza();

switch ($_GET['s']) {
    default: ?>
        <div class="col-xs-12 text-center">
            <h3 class="text-info">
                <?= $users->getUser('name') ?>
            </h3>
        </div>
        <div class="col-xs-12">
            <div class="col-xs-4"><?
                if (empty($users->getUser('avatar'))) {
                    if (!empty($users->getUser('sex'))) { ?>
                        <img src="pic/avatars/<?= $users->getUser('sex'); ?>.jpg" class="img-responsive"><?
                    } else { ?>
                        <img src="pic/avatars/0.jpg" class="img-responsive"><?
                    }
                } else { ?>
                    <img src="pic/avatar/<?= $users->getUser('avatar'); ?>" class="img-responsive"><?
                } ?>
            </div>
            <div class="col-xs-8"><?
                $baza->allHome();
                if ($baza->oneHome('name') == true) { ?>
                    BazeName : <? $baza->oneHome('name');
                } ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-12 text-center">
            <a href="?s=setting">Настройки</a>
        </div><?php
        break;

    case 'setting': ?>
        <div class="col-xs-12">
            <a href="?s=rename">Сменить nickname</a><br>
            <a href="?s=avatars">Сменить аватарку</a>
        </div><?php
        break;

    case 'avatars':
        if(isset($_FILES['file'])) {
            // проверяем, можно ли загружать изображение
            $check = $site->can_upload($_FILES['file']);

            if($check === true) {
                $money = 1000;
                $new_money = $filter->clearInt($users->getUser('gold') - $money);
                // загружаем изображение на сервер
                $site->make_upload($_FILES['file']);
                echo "<strong>Файл успешно загружен!</strong>";
                $sql->query("update users set avatar = ?s, gold = ?i where name = ?s limit ?i", $site->upload_file_name(), $new_money, $users->getUser('name'), 1);
                var_dump($_FILES);
                exit;
            }
            else{
                // выводим сообщение об ошибке
                echo "<strong>$check</strong>";
            }

        } else { ?>
                <div class="col-xs-12 text-center text-info">
                    <p>
                        Смена аватарки стоит 1000 Gold. Загружать можно форматы jpg png jpeg bmp gif
                    </p>
                </div>
            <form action="?s=avatars" method="post" enctype="multipart/form-data">
                <input type="file" name="file">
                <input type="submit" value="Загрузить файл!">
            </form>
                <?php

        }
        break;

    case 'rename':
        if (isset($_POST['enter'])) {
            $money = 500;
            $new_name = $filter->clearFullSpecialChars($_POST['name']);
            $new_money = $users->getUser('gold') - $money;
            if (strlen(trim($new_name)) < 3 || trim($new_name) == "" || strlen(trim($new_name)) > 16) {
                $site->session_err("Не выполнены условия", "?s=rename");
            }
            $user_valid = $sql->getRow("select name from users where name = ?s limit ?i", $new_name, 1);
            if ($new_name == $user_valid['name']) {
                $site->session_err("Это имя занято", "?s=rename");
            }
            if ($users->getUser('gold') < $money) {
                $site->session_err("У вас нехватает Gold! Пополните счет!");
            }
            $sql->query("insert into user_rename set id_user = ?i, last_name = ?s, name = ?s, time_w = ?s, date_w = ?s", $users->getUser('id'), $users->getUser('name'), $new_name, $site->getTime(), $site->getDate());
            $sql->query("update users set name = ?s, gold = ?i where name = ?s and pass = ?s", $new_name, $new_money, $users->getUser('name'), $users->getUser('pass'));
            setcookie('log', $new_name, time() + 3600);
            $site->session_err("Вы успешно сменили имя");
        } else { ?>
            <div class="col-xs-12">
                <p class="text-info">
                    Смена имени стоит 500 Gold нажимая на кнопку вы принимаете условия
                </p>
                <form action="?s=rename" method="post">
                    Новое имя (min 3 max 16)<br>
                    <label>
                        <input name="name" maxlength="16" type="text">
                    </label><br>
                    <input name="enter" type="submit" value="Сменить">
                </form>
            </div><?php
        }
        break;

    case "smotr": ?>
        <div class="col-xs-12">
            <h3 class="text-center">
                <?= $smotr->smotr_id("name") ?> <? $smotr->online_user() ?>
            </h3>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-12">
            <div class="col-xs-4"><?
                if (empty($smotr->smotr_id('avatar'))) {
                    if (!empty($smotr->smotr_id('sex'))) { ?>
                        <img src="pic/avatars/<?= $smotr->smotr_id('sex'); ?>.jpg" class="img-responsive"><?
                    } else { ?>
                        <img src="pic/avatars/0.jpg" class="img-responsive"><?
                    }
                } else { ?>
                    <img src="pic/avatar/<?= $smotr->smotr_id('avatar'); ?>" class="img-responsive"><?
                } ?>
            </div>
            <div class="col-xs-8">
                <div class="col-xs-4 text-danger">
                    lvl
                </div>
                <div class="col-xs-8 text-info text-right">
                    <?= $smotr->smotr_id('lvl') ?>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-4 text-danger">
                    exp
                </div>
                <div class="col-xs-8 text-info text-right">
                    <?= $smotr->smotr_id('exp') ?>
                </div>
                <div class="clearfix"></div><?
                if ($admin1983->returnAdmin() || $admin5->returnAdmin() || $admin4->returnAdmin()) { ?>
                    <div class="col-xs-4 text-danger">
                        money
                    </div>
                    <div class="col-xs-8 text-info text-right">
                        <?= $smotr->smotr_id('money') ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-4 text-danger">
                        gold
                    </div>
                    <div class="col-xs-8 text-info text-right">
                        <?= $smotr->smotr_id('gold') ?>
                    </div>
                    <div class="clearfix"></div><?
                }
                if ($admin1983->returnAdmin() || $admin5->returnAdmin()) { ?>
                    <div class="col-xs-4 text-danger">
                        e-mail
                    </div>
                    <div class="col-xs-8 text-info text-right">
                        <?= $smotr->smotr_id('mail') ?>
                    </div>
                    <div class="clearfix"></div><?
                }
                if ($admin1983->returnAdmin()) { ?>
                    <div class="col-xs-4 text-danger">
                        dostup
                    </div>
                    <div class="col-xs-8 text-info text-right">
                        <?= $smotr->smotr_id('dostup') ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-4 text-danger">
                        IP
                    </div>
                    <div class="col-xs-8 text-info text-right">
                        <?= $smotr->smotr_id('ip') ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-4 text-danger">
                        Browser
                    </div>
                    <div class="col-xs-8 text-info text-right">
                        <?= $smotr->smotr_id('soft') ?>
                    </div>
                    <div class="clearfix"></div><?
                }
                if ($admin1983->returnAdmin() || $admin5->returnAdmin() || $admin4->returnAdmin()) {
                    if (!empty($smotr->smotr_id('referal'))) { ?>
                        <div class="col-xs-4 text-danger">
                            referal
                        </div>
                        <div class="col-xs-8 text-info text-right"><?
                            $referal = $sql->getRow("select id, name, referal from users where id = ?i", $smotr->smotr_id('referal')); ?>
                            <a href="user.php?s=smotr&user=<?= $referal['name'] ?>"><?= $referal['name'] ?></a>
                        </div>
                        <div class="clearfix"></div><?
                    }
                } ?>
                <div class="col-xs-4 text-danger">
                    Registration time
                </div>
                <div class="col-xs-8 text-info text-right">
                    <?= $smotr->smotr_id('reg_time') . " | " . $smotr->smotr_id('reg_data')?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
            <?php
        if ($admin1983->returnAdmin()) { ?>
            <div class="col-xs-12">
                <a href="amdin1983.php?s=add_res&user=<?= $smotr->smotr_id('name') ?>">Выдать рес</a><br>
                <a href="amdin1983.php?s=reface_user&user=<?= $smotr->smotr_id('name') ?>">Сменить аву</a><br>
            </div><?php
        }
        break;
}

require_once "inc/footer.php";