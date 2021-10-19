<?php

class Site {
    private string $name;

    public function getIp() {
        $keys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR'
        ];
        foreach ($keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = trim(end(explode(',', $_SERVER[$key])));
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
    }

    public function error_sess ($kto, $text, $tip) {
        global $sql;
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $gde = $_SERVER['SCRIPT_NAME'];
        $sql->query("insert into log set kto = ?s, text = ?s, gde = ?s, tip = ?s, r_time = ?s, r_date = ?s, soft = ?s, ip = ?s", $kto, $text, $gde, $tip, self::getTime(), self::getDate(), $agent, self::getIp());
    }


    public function session_err($text = "", $s = "?") {
        if (!empty($text)) {
            $_SESSION['err'] = $text;
        }
        $this->_location($s);
    }

    public function _location ($s) {
        header("Location: ".$s."");
        exit;
    }

    public function getTime() {
        return date("H:i:s");
    }

    public function getDate() {
        return date("d.m.Y");
    }

    public function can_upload($file) {
        // если имя пустое, значит файл не выбран
        if($file['name'] == '')
            return 'Вы не выбрали файл.';

        /* если размер файла 0, значит его не пропустили настройки
        сервера из-за того, что он слишком большой */
        if($file['size'] == 0)
            return 'Файл слишком большой.';

        // разбиваем имя файла по точке и получаем массив
        $getMime = explode('.', $file['name']);
        // нас интересует последний элемент массива - расширение
        $mime = strtolower(end($getMime));
        // объявим массив допустимых расширений
        $types = array('jpg', 'png', 'gif', 'bmp', 'jpeg');

        // если расширение не входит в список допустимых - return
        if(!in_array($mime, $types))
            return 'Недопустимый тип файла.';

        return true;
    }

    public function make_upload($file) {
        // формируем уникальное имя картинки: случайное число и name
        $name = mt_rand(0, 9999999) . "misspo.ru";
        copy($file['tmp_name'], 'pic/avatar/' . $name);
        $this->name = $name;
    }

    public function upload_file_name () {
        return $this->name;
    }
}