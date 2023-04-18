<?php
session_start();
use Models\Student;
use Models\Presence;

require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";
$studentRegistration = $_REQUEST['aluno'];
$response = ['status' => 0, 'msg' => 'Erro ao registrar presença para o aluno'];

$student = Student::getStudentByAttribute(null,'matricula',$studentRegistration);

if(empty($student)){
    $response['msg'] = "Aluno reconhecido porém não cadastrado no sistema";
}

if($student['status'] == 0){
    $response['msg'] = "Aluno reconhecido porém inativado no sistema";
}

$presenceObj = new Presence($student['id'], $student['nome']);

if($presenceObj instanceof Presence){
    $response = $presenceObj->registerPresenceAuto();
    $response['student'] = $student['nome'];
}

$_SESSION['responseStudentPresence'] = $response;
header('Location: ../../../presenca-resposta.php?auth='.base64_encode($response['status']));
?>


