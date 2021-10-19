<?php

require_once "User.php";

class Admin extends User {

    public function __construct($v) {
        return $this->admin = $v;
    }

    public function returnAdmin() {
        return (parent::getUser('dostup') == $this->admin) ? true : false;
    }

    public function AdminError ($text, $type) {
        if (parent::getUser('dostup') != $this->admin) {
            (new Site())->error_sess(parent::getUser('name'), $text, $type);
            self::add_narushenie();
            (new Site())->session_err("Запрет входа. Админ получил письмо. Бан выехал", "/");
        }
    }
}