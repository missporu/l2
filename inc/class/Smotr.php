<?php

require_once "User.php";

class Smotr extends User {
    public function smotr_id ($value) {
        if (isset($_GET['user'])) {
            try {
                if (is_numeric($_GET['user'])) {
                    $name = (new Filter())->clearInt($_GET['user']);
                    $s_user = (new Db())->getRow("select * from users where id = ?i limit ?i", $name, 1);
                } else {
                    $name = (new Filter())->clearFullSpecialChars($_GET['user']);
                    $s_user = (new Db())->getRow("select * from users where name = ?s limit ?i", $name, 1);
                }
                if ($s_user) {
                    return $s_user[$value];
                } else {
                    throw new Exception("Такого пользователя нет");
                }
            } catch (Exception $e) {
                (new Site())->session_err($e->getMessage());
            }
        }
    }

    public function online_user() {
        if (self::smotr_id('online') > time()-600) {
            echo "<small class='text-green'>(Онлайн)</small>";
        } else {
            echo "<small>Последний раз был в игре " . (time() - (self::smotr_id('online'))) . "</small>";
        }
    }
}