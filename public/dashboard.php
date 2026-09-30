<?php 
session_start();

require_once '../app/core/Auth.php';

Auth::check();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

if ($user['role'] === 'admin') {
    header("Location: /public/admin/admin.php");
    exit;
} else {
    header("Location: /public/user/user.php");
    exit;
}