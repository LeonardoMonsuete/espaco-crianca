<?php

namespace Models;
use Models\Database;
use DateTime;
use Exception;
use PDO;
use PDOException;

class Config
{
    public int $id;
    public ?string $ds_configuracao;
    public ?string $valor_configuracao;
    public ?string $tipo;
    public DateTime $created_at;
    public DateTime $updated_at;
    private ?string $classValidation;
    public const _CONFIG_REGISTRA_SAIDA_ALUNO_ = 'Registra saida do aluno';
    public const _CONFIG_DEFAULT_BACKGROUND_IMAGE_ = 'Imagem de plano de fundo padrão';
    public const _CONFIG_MAIL_REPOSITORY_ = 'Destinatários disparo de e-mails (caso queira mais de um endereço separa-los com ; ).';
    public const _CONFIG_TIME_TRIGGER_TO_SEND_PRESENCES_REPORT_ = 'Horário de disparo de relatório diário de presença';

    public function __construct($ds_configuracao, $valor_configuracao, $tipo, $fromRegister = false)
    {
        $this->ds_configuracao = $ds_configuracao;
        $this->valor_configuracao = empty($valor_configuracao) ? 0 : $valor_configuracao;
        $this->tipo = $tipo;

        if (!$this->classValidation() && $fromRegister === true) {
            throw new Exception($this->classValidation['errors']);
        }
    }

    public static function getConfigByAttribute($connection = null, $attribute, $value) 
    {   
        if(empty($connection)){
            $connection = Database::getConnection();
        }

        $config = null;

        $sql = "SELECT * FROM ".DB_NAME.".configuracao where $attribute = '$value' limit 1";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        if($stmt->setFetchMode(PDO::FETCH_ASSOC)){
            $config = $stmt->fetch();
        }
        $connection = null;
        return $config;
    }

    public static function getConfigs($connection = false, $fields = "*")
    {
        if (empty($connection)) {
            $connection = Database::getConnection();
        }
        $configs = [];

        $sql = "SELECT $fields FROM " . DB_NAME . ".configuracao";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        if ($stmt->setFetchMode(PDO::FETCH_ASSOC)) {
            $configs = $stmt->fetchAll();
        }
        $connection = null;
        return $configs;
    }

    public function update()
    {
        $response = ['status' => 1, 'msg' => 'Configuração ' . $this->ds_configuracao . ' atualizada com sucesso !'];

        if($this->tipo == 'arquivo'){
            $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/assets/images";
            if(!is_dir($baseDir)){
                if(mkdir($baseDir)){
                    copy($this->valor_configuracao, $baseDir . "/background.jpeg");
                }
            } else {
                if($this->valor_configuracao){
                    copy($this->valor_configuracao, $baseDir . "/background.jpeg");
                }
            }

            $this->valor_configuracao = "background.jpeg";
        }

        $connection = Database::getConnection();

        try {
         
            $sql = "UPDATE " . DB_NAME . ".configuracao SET valor_configuracao = :valor_configuracao, updated_at = now()
            WHERE id = :id";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':valor_configuracao', $this->valor_configuracao);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
        } catch (PDOException $e) {
            $response['status'] = 0;
            $response['msg'] = 'Erro ao atualizar configuração => ' . $e->getMessage();
        }
        $connection = null;
        return $response;
    }

    private function classValidation()
    {
        if (!$this->validateDsConfiguracao()) {
            return false;
        }

        return true;
    }

    private function validateDsConfiguracao()
    {
        if (!empty($this->ds_configuracao) && strlen($this->ds_configuracao) > 3) {
            return true;
        }
        return false;
    }
}

?>