<?php
$title = "База";
require_once "inc/header.php";
$users->no_reg();
aka();
$baza = new Baza();

switch ($_GET['s']) {
    default:
        if ($baza->all_home) {

        }
        if ($baza->allHome() == false) { ?>
            <br><a href="?s=add">Добавить</a><br><?php
        } else {

        }
        break;

    case 'add':
        $all_baze = $sql->getAll("select name from Baza order by id desc limit ?i", 10);
        foreach ($all_baze as $i) { ?>
            <a href="?s=new&name=<?= $i['name'] ?>"><?= $i['name'] ?></a><br><?php
        }
        break;

    case 'new':
        echo $_GET['name'];
        break;
}


require_once "inc/footer.php";