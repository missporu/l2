<?php

class Baza {

    public array $all_home;

    public function allHome() {
        try {
            $this->all_home = (new Db())->getAll("select name from user_baza where user_id = ?i order by id desc limit ?i", (new User())->getUser('id'), 10);
            if ($this->all_home) {
                foreach ($this->all_home as $all) {
                    echo $all['name']."<br>";
                }
                return true;
            } else {
                throw new Exception("Ничего нет");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function oneHome($v) {
        try {
            $baze_name = (new Db())->getRow("select * from user_baza where user_id = ?i and name = ?s limit ?i", (new User())->getUser('id'), $v, 1);
            if ($baze_name) return $baze_name[$v];
            else throw new Exception('Ошибка №311');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function addHome($name) {
        (new Db())->query("insert into user_baza set user_id = ?i, name = ?s, lvl = ?i", (new User())->getUser('id'), $name, 1);
    }
}