<?php 

header('Content-Type: text/html; charset=UTF-8');
require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";
require_once($_SERVER['DOCUMENT_ROOT'].'/espaco-crianca/src/libs/mail/src/PHPMailer.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/espaco-crianca/src/libs/mail/src/SMTP.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/espaco-crianca/src/libs/mail/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try{
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    $mail->Username = 'info.brjsp@gmail.com';
    $mail->Password = 'jsp152436';
    $mail->Port = 465;
    $mail->setFrom('info.brjsp@gmail.com', 'JSPSystem - Baixa de ativo (Patrimônio)');

    $mail->AddEmbeddedImage($_SERVER['DOCUMENT_ROOT'].'/src/assets/images/jsp_logo.jpg', 'jsplogo');
    $mail->AddEmbeddedImage($_SERVER['DOCUMENT_ROOT'].'/src/assets/images/JSPsystem_email.png', 'jspsystemlogo');
    //$mail->addAddress($email_destino);//email do Solicitante
    $mail->addAddress('leonardo.oliveira@jsp.com');//email do Solicitante
    $mail->Subject = "Solicitação de baixa de ativo Nº $id_baixa";
    $mail->isHTML(true);
    $mail->Body = "<div style='width:100%; height:400px; border-style: solid; border-width: 1px; border-color: gray;'>
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
    <p style='color:black; margin-left:10px; font-size:20px; margin-top:10px; margin-bottom:10px;'>Sua solicitação de recurso de TI número $aprovar_solic_id referente ao: $acesso_solicitado, foi efetivada pelo departamento de TI ! Clique <a href='http://10.80.10.142/' style='color:black;'>aqui</a> para acessar JSPSystem e validar.
    </div>  
    <div style='height: 100px; width:100%; background-color:#343a40;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; margin-top: 8px;'>
        <p style='color: orange;font-size: 20px; margin-top:20px; margin-bottom:20px;'>Por favor <strong>não</strong> respoda este e-mail! Este é um e-mail gerado automaticamente pelo sistema JSPSYSTEM.
        <br>
        <p style='color: orange; text-align:center;'>© Todos os direitos reservados
    </div>
</div> ";

    if($mail->send()){
        $_SESSION['msgsent'] = "<div class='alert alert-info' role='alert'>Email de confirmação/notificação enviado com sucesso!<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
        </button>
        </div>";

    }else{
        $_SESSION['msgcad'] = "<div class='alert alert-danger' role='alert'>Erro ao enviar e-mail de notificação !<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
        </button>
        </div>";
    }   

}catch(Exception $e){
    $_SESSION['msgcad'] = "<div class='alert alert-danger' role='alert'>Erro ao enviar e-mail de notificação ! {$mail->ErroInfo}<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
    </button>
    </div>";
    echo $e->getMessage();
}

?>