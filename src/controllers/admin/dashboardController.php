<?php
session_start();
use Models\Presence;

require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";
$response = ['status' => 0, 'msg' => 'Erro ao coletar dados para o dashboard'];
$postData = $_POST;

if(isset($postData['action'])){
    switch ($postData['action']) {
        case 'getPresencesPerWeekDay':
            $response = array_values(Presence::getPresencesPerWeekDay());
            if(empty($response)){
                $response = [0,0,0,0,0];
            }
            if(!isset($response['status'])){
                $response['status'] = 1;
            }
            break;
        case 'getDataForDashboardCounters':
            $response = Presence::getDataForDashboardCounters();
            if(!isset($response['status'])){
                $response['status'] = 1;
            }
            break;
    }
}

echo json_encode($response);
?>


