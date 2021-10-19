<?php
if ($_GET['s'] == 'pr') {
    $title = "Правила";
} elseif ($_GET['s'] == 'online') {
    $title = "Онлайн";
}else {
    $title = "Главная";
}
require_once "inc/header.php";
$users->no_reg();
aka();

switch ($_GET['s']) {
    default: ?>
        <div class="col-xs-6 text-center">
            <a href="user.php">Профиль</a>
        </div>
        <div class="col-xs-6 text-center">
            <a href="battle.php">Война</a>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-12 text-center">
            <hr>
        </div>
        <div class="clearfix"></div><?php
        if ($admin1983->returnAdmin()) { ?>
            <div class="col-xs-6 text-center">
                <a href="chat.php">Чат</a>
            </div>
            <div class="col-xs-6 text-center">
                <a href="baza.php">База</a>
            </div><?php
        }
        break;

    case 'pr':
        echo "123";
        break;

    case 'online':
        $kolvo = $sql->getOne("select count(id) from users where online > ?i", time()-600);
        if ($kolvo > 0) {
            $online = $sql->getAll("select name, online from users where online > ?i", time()-600);
            foreach ($online as $on) { ?>
                <div class="col-xs-12">
                    <a href="user.php?s=smotr&user=<?= $on['name'] ?>"><?= $on['name'] ?></a>
                </div><?php
            }
        } else { ?>
            <div class="col-xs-12 text-center">
                <p class="text-info">
                    Никого нет
                </p>
            </div><?php
        }
        break;

    case 'exit':
        $users->exit();
        break;
}

require_once "inc/footer.php";