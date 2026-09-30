<?php

class Auth {

    public static function check() {
        if (!isset($_SESSION['user'])) {
            header("Location: /public/login.php");
            exit;
        }
    }

    public static function user() {
        return $_SESSION['user'] ?? null;
    }

    public static function adminOnly() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: /public/login.php");
            exit;
        }
    }
}