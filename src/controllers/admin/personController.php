<?php

use Models\Person;

session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";

$postData = $_POST;
$postFiles = $_FILES;
$response = validateIsSetPostData($postData,$postFiles);
if($response['status'] == 1){
    switch ($postData['action']) {
        case 'changeStatus':
            $response = changeStatus($postData);
            break;
    
        case 'insertUpdate':
            $response = insertUpdatePerson($postData, $postFiles);
            break;
    }
    
    $response['urlLocation'] = 'pessoa.php';
}

echo json_encode($response);


function changeStatus($postData)
{
    $response = Person::changeStatus($postData['userId'], $postData['newStatus']);
    return $response;
}

function insertUpdatePerson($postData, $postFiles)
{
    $img_01Post = isset($postFiles['img_01']['tmp_name']) ?  $postFiles['img_01']['tmp_name'] : "";
    $img_02Post = isset($postFiles['img_02']['tmp_name']) ?  $postFiles['img_02']['tmp_name'] : "";
    $personObj = new Person($postData['nome'], $postData['id_categoria'], $postData['matricula'],$img_01Post , $img_02Post, $postData['id_responsavel'],$postData['status'], true);    
    if (isset($postData['isUpdate'])) {
        $personObj->id = $postData['uid'];
        $response = $personObj->update();
    } else {
        $response = $personObj->save();
    }
    return $response;
}

function validateIsSetPostData($postData, $postFiles)
{
    if(!isset($postData)){
        return ['status' => 0, 'msg' => 'Erro ao coletar dados do formulário'];
    }
    
    if( ($postData['action'] != 'changeStatus' && empty($postData['isUpdate'])) && (!isset($postFiles) || !isset($postFiles['img_01']) || !isset($postFiles['img_02']))){
        return ['status' => 0, 'msg' => 'Duas imagens da pessoa deve ser inseridas'];
    }
    return ['status' => 1];
}
?>