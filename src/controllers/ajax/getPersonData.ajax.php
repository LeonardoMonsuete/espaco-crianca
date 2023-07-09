<?php

use Models\Person;

require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";

$postData = $_POST;

switch ($postData['action']) {
    case 'getAllRegistration':
        $registrations = [];
        foreach (Person::getPerson(false,'matricula') as $key => $person) {
            $registrations[] = $person['matricula'];
        }
        echo json_encode($registrations);
        exit;
        break;
    
    default:
        # code...
        break;
}

