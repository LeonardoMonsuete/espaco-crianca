<?php
    date_default_timezone_set('America/Sao_Paulo');
    $hora_atual_nf = date("H:i:s");
    $manha_nf = "11:59:59";
    $tarde_nf = "17:59:59";
    $noite_nf = "00:00:00";
    $hora_atual = DateTime::createFromFormat("H:i:s",$hora_atual_nf);
    $manha = DateTime::createFromFormat("H:i:s",$manha_nf);
    $tarde = DateTime::createFromFormat("H:i:s",$tarde_nf);
    $noite = DateTime::createFromFormat("H:i:s",$noite_nf);
    $greeting = "Bom dia ";
    if($hora_atual < $manha && $hora_atual > $noite){
        $greeting;
    }else if($hora_atual > $manha && $hora_atual < $tarde){
        $greeting = "Boa tarde ";
    }else{
        $greeting = "Boa noite ";
    }

?>