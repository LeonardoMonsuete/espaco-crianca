<?php

use Models\User;

session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";

$postData = $_POST;
$response = validateIsSetPostData($postData);
if($response['status'] == 1){
    switch ($postData['action']) {
        case 'insertUpdate':
            $response = insertUpdateUser($postData);
            break;
    }
    
    $response['urlLocation'] = 'usuarios.php';
}

echo json_encode($response);


function insertUpdateUser($postData)
{
    $isUpdating = isset($postData['isUpdate']);
    $userObj = new User($postData['usuario'],($isUpdating) ? null : $postData['senha'],$postData['nome'],$postData['email'], $postData['master'],true);    
    if ($isUpdating) {
        $userObj->id = $postData['uid'];
        $response = $userObj->update();
    } else {
        $response = $userObj->save();
    }
    return $response;
}

function validateIsSetPostData($postData)
{
    if(!isset($postData)){
        return ['status' => 0, 'msg' => 'Erro ao coletar dados do formulário'];
    }

    if(empty($postData['action'])){
        return ['status' => 0, 'msg' => 'Erro ao identificar ação'];
    }

    return ['status' => 1];
}
?>