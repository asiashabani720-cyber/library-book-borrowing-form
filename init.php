<?php
session_start();
require_once __DIR__ . '/config.php';
function is_logged_in() {
    return !empty($_SESSION['user']);
}
function require_login() {
    if (!is_logged_in()) {
        header('Location: admin/login.php');
        exit;
    }
}
?>
