<?php
use Models\User;
session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";

$postData = $_POST;
$redirectPage = 'admin-index.php';
$response = validateIsSetPostData($postData);
if($response['status'] == 1){
    try {
        $usuarioObj = new User($postData['usuario'], $postData['senha'],null,null,null, false);
        $response = $usuarioObj->makeLogin();   
    
        if($response['status'] === 0){
            $_SESSION['msg'] = $response['msg'];
            $redirectPage = 'login.php';
        }
    } catch (Exception $e) {
        $_SESSION['msg'] = $e->getMessage();
        header('Location: ' . $redirectPage);
    }
}
$response['urlLocation'] = $redirectPage;
$_SESSION = $response;

echo json_encode($response);

function validateIsSetPostData($postData)
{
    if(!isset($postData)){
        return ['status' => 0, 'msg' => 'Erro ao coletar dados do formulário'];
    }
    return ['status' => 1];
}
