<?php
$title = "Главная";
require_once "inc/header.php";
$users->no_reg();

if($users->getUser('akadem') > 6) {
    $site->session_err("Вы уже прошли обучение, изменить данные теперь можно из панели управления профилем", "/");
}


function aka_update($value1) {
    global $site, $users;
    if ($users->getUser('akadem') < 7 and $users->getUser('akadem') != $value1) {
        $site->session_err("", "?aka={$users->getUser('akadem')}");
    }
}

switch ($_GET['aka']) {
    case '1':
        aka_update(1);
        if (isset($_POST['enter'])) {
            $profa = $_POST['prof'];
            if (empty($profa)) {
                $site->session_err("Выберите профессию!", "?aka=1");
            } else {
                $sql->query("update users set profession = ?s, akadem = ?i where name = ?s limit ?i", $profa, 2, $users->getUser('name'), 1);
                $site->session_err("Отлично! Едем дальше!", "?aka=2");
            }
        } ?>
        <div class="col-xs-12">
            <p>
                Отлично! Еще несколько шагов и я с уверенностью смогу пустить тебя в свободную жизнь в нашей вселенной!<br>
                Мы перевели на твой счет 100$ для уверенного начала в игре!
            </p>
            <p>
                Выбери, кем ты хочешь быть в нашем мире. Пока что есть всего два доступных пути: <br>
                <div class="clearfix"></div>
                <div class="col-xs-6 text-info">
                    Собиратель - Получает бонус к сбору материалов и чертежей в открытом мире, и в pvp-сражениях.
                </div>
                <div class="col-xs-6 text-right text-info">
                    Конструктор - Умеет собирать механизмы, детали и технику из чертежей.
                </div>
                <div class="clearfix"></div>
            </p>
            <p>
                Обрати внимание, что оба класса взаимодействуют друг с другом, и кого бы ты не выбрал - тебе будет рад любой клан!
            </p>
            <p>
                Итак, выбор за тобой!
            </p>
        </div>
        <form action="?aka=1" method="post">
            <div class="col-xs-6">
                <input type="radio" name="prof" value="scavenger">Хочу быть собирателем!
            </div>
            <div class="col-xs-6 text-right">
                <input type="radio" name="prof" value="krafter">Хочу быть конструктором!
            </div>
            <div class="clearfix"></div>
            <div class="col-xs-12 text-center">
                <input class="text-center" name="enter" type="submit" value="Выбрать!">
            </div>
        </form><?php
        break;

    /**
     * Дописать !!!
     */

    case '2':
        aka_update(2);
        sleep(1);
        $sql->query("update users set akadem = ?i where name = ?s limit ?i", 7, $users->getUser('name'), 1);
        $site->session_err("Обучение окончено!", "/");
        break;

    case '3':
        aka_update(3);
        echo "3";
        break;

    case '4':
        aka_update(4);
        echo "4";
        break;

    case '5':
        aka_update(5);
        echo "5";
        break;

    case '6':
        aka_update(6);
        echo "6";
        break;

    default:
        aka_update(0);
        if (isset($_POST['enter'])) {
            $sex = $filter->clearFullSpecialChars($_POST['sex']);
            if (empty($sex)) {
                $site->session_err("Выберите свой пол!", "?");
            } else {
                $sql->query("update users set sex = ?s, money = ?i, akadem = ?i where name = ?s limit ?i", $sex, 110, 1, $users->getUser('name'), 1);
                $site->session_err("Отлично! Едем дальше!", "?aka=1");
            }
        } ?>
        <div class="col-xs-12">
            <p>
                Мы рады приветствовать тебя в нашей вселенной, путник!
            </p>
            <p>
                Ты еще молод, и совсем не понимаешь что это за место, но не переживай!
            </p>
            <p>
                Меня назначили специально встречать всех новобранцев и делать с ними первые шаги!
            </p>
            <p>
                Итак, для начала, назови мне свой пол
            </p>
            <form action="?" method="post">
                <div class="col-xs-6">
                    <input type="radio" name="sex" value="m">Мужской
                </div>
                <div class="col-xs-6 text-right">
                    <input type="radio" name="sex" value="w">Женский
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12 text-center">
                    <input name="enter" type="submit" value="Назвать пол">
                </div>
            </form>
        </div><?php
        break;
}
require_once "inc/footer.php";