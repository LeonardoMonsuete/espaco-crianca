<?php

use Models\Student;

require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";

$postData = $_POST;

switch ($postData['action']) {
    case 'getAllRegistration':
        $registrations = [];
        foreach (Student::getStudents(false,'matricula') as $key => $student) {
            $registrations[] = $student['matricula'];
        }
        echo json_encode($registrations);
        exit;
        break;
    
    default:
        # code...
        break;
}

