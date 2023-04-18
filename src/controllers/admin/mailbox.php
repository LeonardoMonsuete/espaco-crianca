<?php
    date_default_timezone_set('America/Sao_Paulo');
    //require_once $_SERVER['DOCUMENT_ROOT'] . '\config\defines.php';
    // @include_once "mailbox_includes.php";
    require_once("C:/xampp/htdocs/src/controller/conexao.php");
    // @include_once "mailbox_includes.php";
    require_once('C:/xampp/htdocs/src/mail/src/PHPMailer.php');
    require_once('C:/xampp/htdocs/src/mail/src/SMTP.php');
    require_once('C:/xampp/htdocs/src/mail/src/Exception.php');

    // $mailbox = "{imap.gmail.com:993/ssl}INBOX";
    // $username = "info.brjsp@gmail.com";
    // $password = "infobrjsp152436";
    
    // $mailbox = "{outlook.office365.com:993/ssl}INBOX/teste";
    // $username = "info.br@jsp.com";
    // $password = "@TIjsp152436";

    $mailbox = "{challenger.jsp.com:993/ssl}INBOX";
    $username = "jsp\suporte.ti";
    $password = "jsp152436";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    $mail = new PHPMailer(true);
    $mail2 = new PHPMailer(true);

    $Conexao = Conexao::getConnection();

    $inbox = imap_open($mailbox, $username, $password) or die('Cannot connect to email: ' . imap_last_error());

    $date = date("j F Y");

    $emails = imap_search($inbox,'ALL',SE_UID);
    //To put the newest emails on top:
    //If emails are returned, you can cycle through each email:
    $from = [];
    $subject = [];
    $message = [];
    $structure_parts = [];
    $attachments = [];
    $anexos_chamados = [];

    if($emails){
     rsort($emails);

     $header = [];
     for($i = 1; $i <= count($emails); $i++){
        $aux_header = imap_headerinfo($inbox, $i);
       
        $structure = imap_fetchstructure($inbox, $i);
        //$body_imgs = imap_body($inbox, $i);

        //var_dump($body_imgs);
    
        //echo base64_decode($aux_body);
        //$aux_body = quoted_printable_decode(imap_fetchbody($inbox,$i, 2));
        //iconv(mb_detect_encoding($aux_body, mb_detect_order(), true), "utf-8", $aux_body);
        //$message_value = (strip_tags(utf8_encode($aux_body)));
        // Start
        //$body_imgs
        $obj_structure = imap_fetchstructure($inbox, $i);
        // preg_match_all('/src="cid:(.*)"/Uims', $body_imgs, $matches);
        // print_r($matches);
        // Recherche de la section contenant le corps du message et extraction du contenu
        $obj_section = $obj_structure;
      
        $section = "1";
        for ($j= 0 ; $j< 10 ; $j++) {
            if ($obj_section->type == 0) {
                break;
            } else {
                $obj_section = $obj_section->parts[0];
                $section.= ($j> 0 ? ".1" : "");
            }
        }
        $text = imap_fetchbody($inbox, $i, $section);

        // Décodage éventuel
        if ($obj_section->encoding == 3) {
            $text = imap_base64($text);
        } else if ($obj_section->encoding == 4) {
            $text = imap_qprint($text);
        }
        // Encodage éventuel
        foreach ($obj_section->parameters as $obj_param) {
            if (($obj_param->attribute == "charset") && (mb_strtoupper($obj_param->value) != "UTF-8")) {
                $text = utf8_encode($text);
                break;
            }
        }

        // End
        //print $text;
        $message_value = $text;
        //print $message_value;
        //var_dump($structure->parts); //strtolower($structure->parts[$i]->disposition) == 'attachment'

        // if(strtolower($structure->parts->disposition) == 'inline'){
        //     echo "has inlin e image";
        // }
        //print_r($structure->parts);
        if(isset($structure->parts) && count($structure->parts) > 0){
            for($j = 1; $j < count($structure->parts); $j++){
                $attachments[$j] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => '',
                    'num_chamado' => ''
                );
              
                if($structure->parts[$j]->ifdparameters){
                    foreach($structure->parts[$j]->dparameters as $object){
                        if(strtolower($object->attribute) == 'filename'){
                            $attachments[$j]['is_attachment'] = true;
                            $attachments[$j]['filename'] = $object->value;
                            $attachments[$j]['num_chamado'] = $i;
                        }
                        //echo $object->attribute;
                    }
                }

                if($structure->parts[$j]->ifparameters){
                    foreach($structure->parts[$j]->parameters as $object){
                        if(strtolower($object->attribute) == 'name'){
                            $attachments[$j]['is_attachment'] = true;
                            $attachments[$j]['name'] = $object->value;
                            $attachments[$j]['num_chamado'] = $i;
                        }
                    }
                }

                if($attachments[$j]['is_attachment']){
                    $attachments[$j]['attachment'] = imap_fetchbody($inbox, $i, $j+1);

                    /* 3 = BASE64 encoding */
                    if($structure->parts[$j]->encoding == 3){ 
                        $attachments[$j]['attachment'] = base64_decode($attachments[$j]['attachment']);
                    }
                    /* 4 = QUOTED-PRINTABLE encoding */
                    elseif($structure->parts[$j]->encoding == 4){ 
                        $attachments[$j]['attachment'] = quoted_printable_decode($attachments[$j]['attachment']);
                    }
                }
            }
            $attach_sequence = 0;

            // echo "attachments array: ";
            // var_dump($attachments);
            // echo "<br>";
           
            foreach($attachments as $attachment){
                
                if($attachment['is_attachment'] == 1){
                 
    
                    //$filename = strip_tags(utf8_encode($attachment['name']));
                    $filename = str_replace("?=","",$attachment['name']);
                    $extensao = substr($filename, -5);
                    $extensao = explode(".", $extensao);
                    $filename = "arq_".$attach_sequence.".".$extensao[1];
                    //if(empty($filename)) $filename = strip_tags(utf8_encode($attachment['filename']));
                    if(empty($filename)){
                        $filename = str_replace("?=","",$attachment['filename']);
                        $extensao = substr($filename, -5);
                        $extensao = explode(".", $extensao);
                        $filename = "arq_".$attach_sequence.".".$extensao[1];
                    } 
    
                    if(empty($filename)) $filename = time() . ".dat";
                    $folder = "C:\\xampp\\htdocs\\src\\fotos\\ti\\chamados";
                    //echo $_SERVER['DOCUMENT_ROOT'];
                    if(!is_dir($folder)){
                        mkdir($folder);
                    }
                    $filename_complete = $i ."-".$attach_sequence."_".$filename;
                    $file_aux = $folder ."\\". $filename_complete;
                    $fp = fopen($file_aux, "w+");
                    fwrite($fp, $attachment['attachment']);
                    fclose($fp);
                    array_push($anexos_chamados, ['num_chamado' => $i, 'filename_location' => $filename_complete]);

                    $destinationFolder = 'INBOX/processadas';
                   
                    
                  
    
                    // imap_close($inbox, CL_EXPUNGE);
                }
                $attach_sequence++;
            }
        }
        // echo "anexos array: ";
        // var_dump($anexos_chamados);
        // echo "<br>";

        $aux_header->subject = iconv_mime_decode($aux_header->subject,
                       0, "UTF-8");
        
        //iconv(mb_detect_encoding($aux_header->subject, mb_detect_order(), true), "utf-8", $aux_header->subject);
        //echo $aux_header->subject;
        array_push($from, $aux_header->from);
        array_push($subject, $aux_header->subject);
        // array_push($message, preg_replace('/\s+/', ' ', $message_value));
        array_push($message, $message_value);
  
        // $movingResult = imap_mail_move($inbox, strval(count($emails)), 'INBOX/processadas', CP_UID);
        // if($movingResult){
        //     echo "moved";
        // }else{
        //     echo imap_last_error();
        // }
        
     }       
     $myArray = json_decode(json_encode($from), true);
    //  $subject_decoded = json_decode($subject);
    //  var_dump($subject_decoded);
     //var_dump($message);
     

     foreach ($myArray as $key => $value) {
        
        $email_from = $myArray[$key][0]['mailbox']."@".$myArray[$key][0]['host'];
        $titulo = $subject[$key];
        //$descricao = $message[$key];
        $descricao = strip_tags($message[$key], "<p>");
        //$descricao = preg_replace( "/\r|\n/", "", $descricao );
        
        //echo strip_tags($descricao);
        //echo $email_from."EMAIL <<<<<";
        $queryGetSolicitante = $Conexao->query("SELECT * FROM usuario where email like '$email_from'");
        //echo $titulo;
            if($queryGetSolicitante){
                foreach ($queryGetSolicitante as $solicitante) {
                    $nome_solicitante = $solicitante['nome'].' '.$solicitante['sobrenome'];
                    $id_solicitante = $solicitante['id_usuario'];
                }

                try {
                    $descricao = str_replace("&nbsp;","",$descricao);
                    $descricao = str_replace("margin-left","",$descricao);
                    $descricao = str_replace("'","´",$descricao);

                    $titulo = str_replace("'","´", $titulo);
                    $queryBase = "INSERT INTO ti_chamados 
                    (id_usuario_abertura, data_abertura, origem_chamado, titulo, descricao, id_status)
                    values
                    ('$id_solicitante', GETDATE(), 'email','$titulo','$descricao',1)";
                    
                    //echo $queryBase;
                    $queryInsereChamado = $Conexao->query($queryBase);
    
                    $last_inserted_chamado = $Conexao->lastInsertId();

                //INICIO DE EMAIL DE NOTIFICAÇÃO PARA O SOLICITANTE
    
                if(sendMailUser($mail2, $email_from, $last_inserted_chamado, $nome_solicitante)){
                    $sents = true;
                }else{
                    $sents = false;
                }
                //FIM DE EMAIL DE NOTIFICAÇÃO PARA O SOLICITANTE

                    $queryInsertStatus = $Conexao->query("INSERT INTO ti_status_chamado (id_chamado, status, data_atribuicao, id_usuario_atribuicao)
                    values ('$last_inserted_chamado', 1, GETDATE(), '$id_solicitante')");
               
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            
                $sem_anexos = false;
                //var_dump($anexos_chamados);
                $count_img = 1;
                foreach ($anexos_chamados as $anexos) {
                
                    $data_img = date("d-m-Y");
                    $hora_img = date("H-i");
                    
                    if($anexos['num_chamado'] == $key+1){
                        
                        
                        $src_index = $anexos['filename_location'];
                        $extensions = explode('.',$src_index);
                        $src_index_new_name = "chamado_".$last_inserted_chamado."-".$data_img."-".$hora_img."-evi-".$count_img.".".$extensions[1];
                        $full_path_filename_old = "C:\\xampp\\htdocs\\src\\fotos\\ti\\chamados\\".$src_index;
                        $full_path_filename_new = "C:\\xampp\\htdocs\\src\\fotos\\ti\\chamados\\".$src_index_new_name;
                        rename($full_path_filename_old, $full_path_filename_new);
    
                        $queryInsereAnexos = $Conexao->query("INSERT INTO ti_anexos_chamado
                        (id_chamado, caminho, data_anexo, id_usuario_anexou)
                        values
                        ('$last_inserted_chamado', '$src_index_new_name', GETDATE(),'$id_solicitante')");
                    
                    $count_img++;
                    }else{
                        $sem_anexos = true;
                    }
                
                }
         
               
                //ENVIO DE EMAILS DE NOTIFICAÇÃO
                //INICIO DE EMAIL DE NOTIFICAÇÃO PARA OS ATUANTES DO TI
                 $emails_ti_sent = false;
             
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                 $mail->CharSet = 'UTF-8';
                $mail->isSMTP();
                 $mail->Host = 'challenger.jsp.com';
                 $mail->SMTPAuth = false;
                 $mail->Username = 'info.br@jsp.com';
                 $mail->Password = '@TIjsp152436';
                 $mail->Port = 25;
                 $mail->setFrom('info.br@jsp.com', 'JSPSystem - Abertura de chamado técnico');
                 $mail->AddEmbeddedImage('C:/xampp/htdocs/src/assets/images/jsp_logo.jpg', 'jsplogo');
                 $mail->AddEmbeddedImage('C:/xampp/htdocs/src/assets/images/JSPsystem_email.png', 'jspsystemlogo');
                 $mail->addAddress('adriano.ribeiro@jsp.com');
                 $mail->addAddress('bruno.sampe@jsp.com');
                 $mail->addAddress('marcelo.moizes@jsp.com');
                
            
                 $mail->isHTML(true);
                 $mail->Subject = "Abertura de chamado técnico de TI nº '$last_inserted_chamado'";
                 $mail->Body = "<div style='width:100%; height:400px; border-style: solid; border-width: 1px; border-color: gray;'>
                      <div style='height: 100px; background-color:#343a40; margin-right: -4px;'>
                          <table style='background-color:#343a40; width:100%;'>
                                  <thead>
                                      <tr>
                                         <th style='text-align:left;' colspan='4'><img style='width='150 float:left; display: inline-block;' height='70' src='cid:jsplogo' /></th>
                                          <th colspan='4'><h2 style='color:orange; display: inline-block;'>Olá TI</h2></th>
                                          <th style='text-align:right;' colspan='4'><img style='float:right;  display: inline-block;' width='180' height='70' src='cid:jspsystemlogo' /></th>
                                      </tr>
                                  <thead>
                                  <tbody>
                                  </tbody> 
                          </table>            
                      </div>    
                      <div style='height: 140px;'>
                      <p style='color:black; margin-left:10px; font-size:20px; margin-top:10px; margin-bottom:10px;'>Um novo chamado foi aberto !
                      <p style='color:black; margin-left:10px; font-size:20px; margin-top:10px; margin-bottom:10px;'>Usuário abertura: $nome_solicitante
                      <p style='color:black; margin-left:10px; font-size:20px; margin-top:10px; margin-bottom:10px;'>Titulo: $titulo
                      <p style='color:black; margin-left:10px; font-size:20px; margin-top:10px; margin-bottom:10px;'>Descrição: $descricao
                      <p>Clique <a href='http://10.80.10.142/' style='color:black;'>aqui</a> para acessar JSPSystem e tratar.
                      </div>  
                      <div style='height: 100px; width:100%; background-color:#343a40;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; margin-top: 8px;'>
                          <p style='color: orange;font-size: 20px; margin-top:20px; margin-bottom:20px;'>Por favor <strong>não</strong> respoda este e-mail! Este é um e-mail gerado automaticamente pelo sistema JSPSYSTEM.
                          <br>
                          <p style='color: orange; text-align:center;'>© Todos os direitos reservados
                      </div>
                      </div> ";
                 $mail->send();
                //FIM DE EMAIL DE NOTIFICAÇÃO PARA OS ATUANTES DO TI
                // if($mail->send()){
                //     echo "evniado;";
                // }
    
                ##################################################################################################################################
    
       
               
                
            }else{
                break;
            }

          
    } 
    // if($sents){
        echo "emails coletados e notificações enviadas";
        $destinationFolder = 'INBOX/processadas';
        $emailsInbox = imap_search($inbox, 'ALL', SE_UID);
        foreach ($emailsInbox as $emailUID) {
            // Move
            $movingResult = imap_mail_move($inbox, $emailUID, $destinationFolder, CP_UID);
        }
        imap_close($inbox, CL_EXPUNGE);
   // }

    }else{
        echo "sem novos chamados";
    }

    function format_html($str) 
    {
        // Convertit tous les caractères éligibles en entités HTML en convertissant les codes ASCII 10 en $lf
        $str = htmlentities($str, ENT_COMPAT, "UTF-8");
        $str = str_replace(chr(10), "<br>", $str);
        return $str;
    }

    function sendMailUser($mail2, $email_from, $last_inserted_chamado, $nome_solicitante)
    {
                
        $mail2->CharSet = 'UTF-8';
        $mail2->isSMTP();
        $mail2->Host = 'challenger.jsp.com';
        $mail2->SMTPAuth = false;
        $mail2->Username = 'info.br@jsp.com';
        $mail2->Password = '@TIjsp152436';
        $mail2->Port = 25;
        $mail2->setFrom('info.br@jsp.com', 'JSPSystem - Abertura de chamado técnico');
        $mail2->AddEmbeddedImage('C:/xampp/htdocs/src/assets/images/jsp_logo.jpg', 'jsplogo');
        $mail2->AddEmbeddedImage('C:/xampp/htdocs/src/assets/images/JSPsystem_email.png', 'jspsystemlogo');
        //$mail2->addAddress('marcelo.moizes@jsp.com');
        $mail2->addAddress($email_from);
        
        
    
        $mail2->isHTML(true);
        $mail2->Subject = "Abertura de chamado técnico de TI nº '$last_inserted_chamado'";
        $mail2->Body = "<div style='width:100%; height:400px; border-style: solid; border-width: 1px; border-color: gray;'>
                <div style='height: 100px; background-color:#343a40; margin-right: -4px;'>
                    <table style='background-color:#343a40; width:100%;'>
                            <thead>
                                <tr>
                                    <th style='text-align:left;' colspan='4'><img style='width='150 float:left; display: inline-block;' height='70' src='cid:jsplogo' /></th>
                                    <th colspan='4'><h2 style='color:orange; display: inline-block;'>Olá $nome_solicitante</h2></th>
                                    <th style='text-align:right;' colspan='4'><img style='float:right;  display: inline-block;' width='180' height='70' src='cid:jspsystemlogo' /></th>
                                </tr>
                            <thead>
                            <tbody>
                            </tbody> 
                    </table>
                </div>    
                <div style='height: 140px;'>
                <p style='color:black; margin-left:10px; font-size:20px; margin-top:10px; margin-bottom:10px;'>Seu chamado técnico de TI foi aberto com sucesso e entrou na fila de atendimento, clique <a href='http://10.80.10.142/jspsystem' style='color:black;'>aqui</a> para acessar JSPSystem e acompanhar
                </div>  
                <div style='height: 100px; width:100%; background-color:#343a40;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; margin-top: 8px;'>
                    <p style='color: orange;font-size: 20px; margin-top:20px; margin-bottom:20px;'>Por favor <strong>não</strong> respoda este e-mail! Este é um e-mail gerado automaticamente pelo sistema JSPSYSTEM.
                    <br>
                    <p style='color: orange; text-align:center;'>© Todos os direitos reservados
                </div>
                </div>";

        if($mail2->send()){
            return true;
        }else{
            return false;  
        }

    }
    

?>