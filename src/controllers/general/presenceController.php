<?php
session_start();
use Models\Person;
use Models\Presence;

require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";
$personRegistration = $_REQUEST['pessoa'];
$dsCategory = Person::getPersonByAttribute(null,"matricula",$personRegistration)['ds_categoria'] ?? ' pessoa';
$response = ['status' => 0, 'msg' => 'Erro ao registrar presença para o ' . $dsCategory];

$person = Person::getPersonByAttribute(null,'matricula',$personRegistration);

if(empty($person)){
    $response['msg'] = "Pessoa reconhecida porém não cadastrado no sistema";
}

if($person['status'] == 0){
    $response['msg'] = ucfirst($dsCategory) . " reconhecida porém inativado no sistema";
}

$presenceObj = new Presence($person['id'], $person['nome']);

if($presenceObj instanceof Presence){
    $response = $presenceObj->registerPresenceAuto();
    $response['person'] = $person['nome'];
    $response['category'] = $person['ds_categoria'];
}

$_SESSION['responsePersonPresence'] = $response;
header('Location: ../../../presenca-resposta.php?auth='.base64_encode($response['status']));
?>


