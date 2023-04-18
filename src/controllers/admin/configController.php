<?php

use Models\Config;

session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";

$postData = $_POST;
$postFiles = $_FILES;
$response = validateIsSetPostData($postData,$postFiles);
if($response['status'] == 1){
    switch ($postData['action']) {
        case 'insertUpdate':
            $response = updateConfig($postData,$postFiles);
            break;
    }
}
$response['urlLocation'] = 'configuracao.php';
echo json_encode($response);


function updateConfig($postData, $postFiles)
{
    $valor_config = isset($postData['valor_configuracao']) ? $postData['valor_configuracao'] : '';
    if($postData['tipo'] == 'arquivo'){
        $valor_config = isset($postFiles['valor_configuracao']['tmp_name']) ?  $postFiles['valor_configuracao']['tmp_name'] : "";
    }
    $configObj = new Config($postData['ds_configuracao'], $valor_config,$postData['tipo']);    
    $configObj->id = $postData['uid'];
    $response = $configObj->update();
    return $response;
}

function validateIsSetPostData($postData,$postFiles)
{
    if(!isset($postData)){
        return ['status' => 0, 'msg' => 'Erro ao coletar dados do formulário'];
    }

    if(empty($postData['action'])){
        return ['status' => 0, 'msg' => 'Erro ao identificar ação'];
    }

    if(isset($postData['tipo']) && $postData['tipo'] == 'arquivo'){
        if(empty($postFiles['valor_configuracao']['tmp_name'])){
            return ['status' => 0, 'msg' => 'Erro ao coletador dados da imagem carregado'];
        }
    }

    return ['status' => 1];
}
?>