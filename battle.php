<?php
$title = "Война";
require_once "inc/header.php";
$users->no_reg();
aka();

switch ($_GET['s']) {
    default: ?>
        <div class="col-xs-12">
            <h4 class="text-info">
                Выбирите для себя подходящий режим
            </h4>
            <p>
                <a href="?s=att">Атаковать игроков</a>
            </p>
            <p>
                <a href="?s=pustosh">Уйти в поход на поиски предметов</a>
            </p>
        </div><?php
        break;

    /**
     * Атака
     */
    case 'att':
        function usersRand() {
            global $sql, $users;
            $usr = $sql->getAll("select * from users 
                where name != ?s 
                and lvl >= ?i 
                and lvl <= ?i 
                order by rand() limit ?i",
                $users->getUser('name'),
                ($users->getUser('lvl') - 5),
                ($users->getUser('lvl') + 5),
                10
            );
            foreach ($usr as $vrag) { ?>
                <div class="col-xs-6">
                    <a href="user.php?s=smotr&user=<?= $vrag['name'] ?>"><?= $vrag['name'] ?></a>
                </div>
                <div class="col-xs-6 text-right">
                    <a href="?s=battle&user=<?= $vrag['id'] ?>">Атаковать</a>
                </div>
                <div class="clearfix"></div><hr><?php
            }
        } ?>
        <div class="col-xs-12"><?php
            usersRand(); ?>

            <a href="?s=att">Обновить</a>
        </div><?php
        break;

    /**
     * Поиски
     */
    case 'pustosh':
        // re
        break;

    case 'battle':
        $smotr = new Smotr();
        echo $smotr->smotr_id('name');
        break;
}

require_once "inc/footer.php";