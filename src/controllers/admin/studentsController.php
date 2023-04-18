<?php

use Models\Student;

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
            $response = insertUpdateStudent($postData, $postFiles);
            break;
    }
    
    $response['urlLocation'] = 'alunos.php';
}

echo json_encode($response);


function changeStatus($postData)
{
    $response = Student::changeStatus($postData['userId'], $postData['newStatus']);
    return $response;
}

function insertUpdateStudent($postData, $postFiles)
{
    $img_01Post = isset($postFiles['img_01']['tmp_name']) ?  $postFiles['img_01']['tmp_name'] : "";
    $img_02Post = isset($postFiles['img_02']['tmp_name']) ?  $postFiles['img_02']['tmp_name'] : "";
    $studentObj = new Student($postData['nome'], $postData['matricula'],$img_01Post , $img_02Post, $postData['id_responsavel'],$postData['status'], true);    
    if (isset($postData['isUpdate'])) {
        $studentObj->id = $postData['uid'];
        $response = $studentObj->update();
    } else {
        $response = $studentObj->save();
    }
    return $response;
}

function validateIsSetPostData($postData, $postFiles)
{
    if(!isset($postData)){
        return ['status' => 0, 'msg' => 'Erro ao coletar dados do formulário'];
    }
    
    if( ($postData['action'] != 'changeStatus' && empty($postData['isUpdate'])) && (!isset($postFiles) || !isset($postFiles['img_01']) || !isset($postFiles['img_02']))){
        return ['status' => 0, 'msg' => 'Duas imagens do aluno deve ser inseridas'];
    }
    return ['status' => 1];
}
?>