<?php
/*
 * Copyright (c) misspo 2021.
 */

class User {
	public $user = false;
	const TIME_H = 3600;

	private function cookiesValue($param) {
		$user = [];
		if (isset($_COOKIE['log']) and isset($_COOKIE['id_sess'])) {
			$loginValid    = (new Filter())->clearFullSpecialChars($_COOKIE['log']);
			$passwordValid = (new Filter())->clearFullSpecialChars($_COOKIE['id_sess']);
			$user          = (new Db())->getRow("select name, pass from users where name = ?s and pass = ?s limit ?i",
                $loginValid, $passwordValid, 1);
        }
		return $user[$param];
	}

    /**
     * @return bool
     */
	public function cookiesValid() {
		if (self::cookiesValue('name') and self::cookiesValue('pass')) {
			return true;
		} else {
			return false;
		}
	}

	private function setUser($param) {
		if (self::cookiesValid() == true) {
			setcookie('log', $this->cookiesValue('name'), time()+self::TIME_H);
			setcookie('id_sess', $this->cookiesValue('pass'), time()+self::TIME_H);
			(new Db())->query("update users set online = ?i where name = ?s and pass = ?s",
                time(),
                $this->cookiesValue('name'),
                $this->cookiesValue('pass'));
			$name = (new Db())->getRow("select * from users where name = ?s and pass = ?s limit ?i",
                $this->cookiesValue('name'),
                $this->cookiesValue('pass'),
                1);
		}
		return $name[$param];
	}

	public function getUser($param) {
		return self::setUser($param);
	}

	/**  Если кук нет то нахер с сайта   */
	public function no_reg() {
		if (self::cookiesValid() == false) {
			(new Site())->session_err("Вы не авторизованы!", "enter.php");
		}
	}

	/** Если куки есть то не пускаем в регу и авторизацию  */
	public function reg() {
		if (self::cookiesValid() == true) {
			(new Site())->session_err("", "/");
		}
	}

	public function exit() {
		setcookie('log', '');
		setcookie('id_sess', '');
		session_destroy();
		(new Site())->session_err("Вы покинули игру", "enter.php");
		exit;
	}

	public function add_narushenie($user) {
        (new Db())->query("update users set narushenie = narushenie + ?i where name = ?s", 1, $user);
    }

	public function addMoney($value) {
		$addMoney = self::getUser('money')+$value;
		$addMoney = round((new Filter())->clearInt($addMoney));
		(new Db())->query("update users set money = ?i where name = ?s limit ?i", $addMoney, self::getUser('name'), 1);
	}
}