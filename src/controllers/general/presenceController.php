<?php
session_start();
use Models\Person;
use Models\Presence;

require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";
$personRegistration = isset($_POST['pessoa']) ? $_POST['pessoa'] : $_GET['pessoa'];
$dsCategory = Person::getPersonByAttribute(null,"matricula",$personRegistration)['ds_categoria'] ?? ' pessoa';
$response = ['status' => 0, 'msg' => 'Erro ao registrar presença para o ' . $dsCategory];

$person = Person::getPersonByAttribute(null,'matricula',$personRegistration);

if(empty($person)){
    $response['msg'] = "Pessoa reconhecida porém não cadastrado no sistema";
}

if($person['status'] == 0){
    $response['msg'] = ucfirst($dsCategory) . " reconhecida porém inativado no sistema";
}
$manual = isset($_POST['pessoa']) ? 1 : 0;
$presenceObj = new Presence($person['id'], $person['nome'], $manual);

if($presenceObj instanceof Presence){
    $response = $presenceObj->registerPresenceAuto();
    $response['person'] = $person['nome'];
    $response['category'] = $person['ds_categoria'];
}

$_SESSION['responsePersonPresence'] = $response;
if($manual === 1){
    $response['urlLocation'] = '/espaco-crianca/presenca-resposta.php?auth='.base64_encode($response['status']);
    echo json_encode($response);
} else {
    header('Location: ../../../presenca-resposta.php?auth='.base64_encode($response['status']));
}
?>


