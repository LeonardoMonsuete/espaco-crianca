<?php
session_start();
if(!isset($_SESSION['loggedUserData'])){
    $_SESSION['msg'] = 'É preciso logar-se para acessar a URL em questão';
    header('Location: login.php');
    exit;
}
include_once('./src/views/admin/students.view.php');