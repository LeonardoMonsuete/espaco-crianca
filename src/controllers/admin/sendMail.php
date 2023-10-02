<?php
include_once "../../config/settings.config.php";
include_once "../../config/database.config.php";
include_once "../../config/gmail.config.php";
include('../../../../espaco-crianca/src/libs/mail/src/PHPMailer.php');
include('../../../../espaco-crianca/src/libs/mail/src/SMTP.php');
include('../../../../espaco-crianca/src/libs/mail/src/Exception.php');
include('../../../../espaco-crianca/src/models/Config.php');
include('../../../../espaco-crianca/src/models/Presence.php');
include('../../../../espaco-crianca/src/models/PersonCategory.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Models\Config;
use Models\PersonCategory;
use Models\Presence;

$messageReturnConsole = "Relatorio de presencas enviado com sucesso !";
try {
    $connection = getConnection();
    $destinataryDailyReport = Config::getConfigByAttribute($connection,'ds_configuracao', Config::_CONFIG_MAIL_REPOSITORY_)['valor_configuracao'];
    $hourToSendDailyReport = Config::getConfigByAttribute($connection,'ds_configuracao', Config::_CONFIG_TIME_TRIGGER_TO_SEND_PRESENCES_REPORT_)['valor_configuracao'];

    // if($hourToSendDailyReport !== date('H:i')){
    //     echo "Hora atual não é a hora configurada no sistema para envio do relatório";
    //     return true;
    // }

    if(!$destinataryDailyReport){
        echo "Nao ha e-mail(s) configurado(s) para receber o relatorio";
        return true;
    }

    $destinatariesArr = explode(";", $destinataryDailyReport);
    $dataToCsv = getDataToReport($connection);

    if(!is_array($dataToCsv) && !$dataToCsv){
        echo "Nao ha presenças de hoje ate o momento para geracao do relatorio";
        return true;
    }

    if($fileSent = fopen("tmp/relatorio_".date('d-m-Y').".csv", 'r')){
        echo "Ja foi enviado relatorio diario hoje";
        fclose($fileSent);
        return false;
    }

    if(!resetTmpFolder()){
        echo "Houve um erro ao limpar a pasta tmp de relatorios antigos ja enviados por e-mail";
        return false;
    }

    $csv = createCsv($dataToCsv);


    foreach ($destinatariesArr as $destinatary) {
        if (!sendDailyMail($destinatary, $csv)) {
            $messageReturnConsole = "Erro ao enviar e-mail de notificacao para " .$destinatary;
            if(!resetTmpFolder()){
                echo "Houve um erro ao limpar a pasta tmp de relatorios antigos ja enviados por e-mail";
                return false;
            }
        }
    }

    echo $messageReturnConsole;
} catch (\Throwable $th) {
    echo $th->getMessage();
}

return true;


function resetTmpFolder()
{
    try {
        $files = glob('tmp' . '/*');
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }
    } catch (\Throwable $th) {
        echo $th->getMessage();
        return false;
    }
    return true;
}

function sendDailyMail(string $destinatary, string $csv): bool
{
    $mail = new PHPMailer(true);
    $return = true;
    try {
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = SENDER_MAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = SENDER_MAIL_SECURE_PROTCOL;
        $mail->Username = SENDER_MAIL_ADDRESS;
        $mail->Password = SENDER_MAIL_PASS;
        $mail->Port = SENDER_MAIL_PORT;
        $mail->setFrom(SENDER_MAIL_ADDRESS, 'Espaço da Criança - Relatório diário de presenças');
        $mail->AddEmbeddedImage('../../../../espaco-crianca/src/assets/images/logo-s-bg.png', 'project-banner');
        $mail->addAddress($destinatary); //email do Solicitante
        $mail->Subject = "Relatório diário de presença de assistidos " . date('d/m/Y');
        $mail->isHTML(true);
        $mail->Body = getBody();

        $mail->AddAttachment($csv , "relatorio_" . date('d/m/Y').".csv" );

        if (!$mail->send()) {
            $return = false;
        }

    } catch (Exception $e) {
        $_SESSION['msgcad'] = "<div class='alert alert-danger' role='alert'>Erro ao enviar e-mail de notificação ! {$e->getMessage()}<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
        </button>
        </div>";
        echo $e->getMessage();
        $return = false;
    }

    return $return;
}


function getBody(): string
{
    return "<div style='width:100%; height:400px; border-style: solid; border-width: 1px; border-color: gray;'>
    <div style='height: 100px; background-color:#97A5E2; margin-right: -4px;'>
        <table style='background-color:#97A5E2; width:100%;'>
            <thead>
                <tr>
                    <th style='text-align:left;' colspan='4'><img style='float:left; display:
                            inline-block;' width='80' height='80' src='cid:project-banner' /></th>
                    <th colspan='4'>
                        <h2 style='color:white; display: inline-block;  '>Olá !</h2>
                    </th>
                    <th style='text-align:left;' colspan='4'><img style='float:right; display:
                            inline-block;' width='80' height='80' src='cid:project-banner' /></th>
                </tr>
                <thead>
                <tbody>
                </tbody>
        </table>
    </div>
    <div style='height: 140px;'>
        <p style='color:black; margin-left:10px; font-size:20px; margin-top:10px; margin-bottom:10px;'>Relatório diário de presenças de 
            assistidos gerado com sucesso em anexo !
            Clique <a target='_blank' href='https://localhost/espaco-crianca/login.php' style='color:black;'>aqui</a> para
            acessar o sistema caso queira analisar utilizando os filtros de relatório.
    </div>
    <div
        style='height: 100px; width:100%; background-color:#97A5E2;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; margin-top: 8px;'>
        <p style='color: white;font-size: 20px; margin-top:20px; margin-bottom:20px;'>Por favor <strong>não</strong>
            respoda este e-mail! Este é um e-mail gerado automaticamente pelo sistema.
            <br>
        <p style='color: white; text-align:center;'>© Todos os direitos reservados
    </div>
</div> ";
}

function getConnection()
{
    $pdoConfig  = "mysql:". "Server=" . DB_SERVER . ";";
    $pdoConfig .= "Database=espaco_crianca;".DB_NAME.";";

    try {
        if(!isset($connection)){
            $connection =  new PDO($pdoConfig, DB_USER, DB_PASS);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $connection;
     } catch (Exception $e) {
        $mensagem = "Drivers disponiveis: " . implode(",", PDO::getAvailableDrivers());
        $mensagem = "\nErro: " . $e->getMessage();
        throw new Exception($mensagem);
     }
}

function getDataToReport($connection): Array
{
    $dailyPresences = Presence::getPresencesByDateAndCategoryOrAll($connection, date('Y-m-d 00:00:00.000'), date('Y-m-d 23:59:59.000'), PersonCategory::getCategoryByAttribute($connection,'ds_categoria', PersonCategory::_DS_CAT_ASSISTIDO)['id']);
    $dataArrayResponse = [];
    foreach ($dailyPresences as $key => $value) {
        $dataArrayResponse[$key]['aluno'] = $value['nome'];
        $dataArrayResponse[$key]['matricula'] = $value['matricula'];
        $dataArrayResponse[$key]['data'] = date('d/m/Y', strtotime($value['data']));
        $dataArrayResponse[$key]['entrada'] = date('H:m:i', strtotime($value['hora_entrada']));
        $dataArrayResponse[$key]['saida'] = ($value['hora_saida']) ? date('H:m:i', strtotime($value['hora_saida'])) : "N/A";
    }
    return $dataArrayResponse;
}

function createCsv(Array $dataArr): string
{
    $fileName = "tmp/relatorio_".date('d-m-Y').".csv";
    $arquivo = fopen($fileName, 'w');
    fputcsv($arquivo, ['Nome', 'Matrícula', 'Data', 'Hora entrada' ,'Hora de saída']);
    foreach ($dataArr as $linha) {
        fputcsv($arquivo, $linha);
    }

    fclose($arquivo);
    return $fileName;
}
