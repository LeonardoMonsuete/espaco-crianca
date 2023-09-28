<?php

namespace Models;
use Models\Database;
use Exception;
use PDO;
use PDOException;
setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
date_default_timezone_set( 'America/Sao_Paulo');

class Presence
{
    public int $id;
    public string $data;
    public string $hora_entrada;
    public string $hora_saida;
    public int $id_pessoa;
    public string $nome_pessoa;
    public bool $entrada_manual;
    public bool $saida_manual;
    private ?string $classValidation;


    public function __construct($id_pessoa, $nome_pessoa, $entrada_manual = 0)
    {
        $this->data = date('Y-m-d');
        $this->hora_entrada = date('H:i:s');
        $this->id_pessoa = $id_pessoa;
        $this->nome_pessoa = $nome_pessoa;
        $this->entrada_manual = $entrada_manual;

        if (!$this->classValidation()) {
            throw new Exception($this->classValidation['errors']);
        }
    }

    public function registerPresenceAuto()
    {
        $connection = Database::getConnection();
        $dsCategory = PersonCategory::getCategoryByAttribute($connection, "id", Person::getPersonByAttribute($connection,"id",$this->id_pessoa)['id_categoria'])['ds_categoria'];
        $response = ['status' => 1, 'msg' => "Presença do $dsCategory {$this->nome_pessoa} inserida com sucesso !"];

        if(count($this->validatePresence($connection)) > 0){
            $response['status'] = 0;
            setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
            $response['msg'] = "Entrada já registrada hoje ".date('d/m/Y', strtotime($this->data)).", para o $dsCategory em questão.";
            return $response;
        }

        try {
            $sql = "INSERT INTO " . DB_NAME . ".presenca (data, hora_entrada, entrada_manual,
            id_pessoa) 
            VALUES (:data,:hora_entrada,:entrada_manual,:id_pessoa)";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':data', $this->data);
            $stmt->bindParam(':hora_entrada', $this->hora_entrada);
            $stmt->bindParam(':entrada_manual', $this->entrada_manual);
            $stmt->bindParam(':id_pessoa', $this->id_pessoa);
            $stmt->execute();
        } catch (PDOException $e) {
            $response['status'] = 0;
            $response['msg'] = "Erro ao registrar presença para o $dsCategory {$this->nome_pessoa} => " . $e->getMessage();
        }

        $connection = null;
        return $response;
    }

    public static function getPresencesByDateAndCategoryOrAll($connection = null, $startDate = null, $endDate = null, $category = null)
    {
        if (empty($connection)) {
            $connection = Database::getConnection();
        }

        $registers = null;
        $categoryFilter = "";

        if(!empty($category)){
            $categoryFilter = " AND a.id_categoria in (select id from " . DB_NAME . ".categoria_pessoa where id = '$category' ) ";
        }

        $sql = "SELECT * FROM " . DB_NAME . ".presenca as p inner join " . DB_NAME . ".pessoa as a on p.id_pessoa = a.id WHERE DATE(data) = CURDATE() $categoryFilter";

        if(!empty($startDate) && !empty($endDate)){
            $sql = "SELECT * FROM " . DB_NAME . ".presenca as p inner join " . DB_NAME . ".pessoa as a on p.id_pessoa = a.id WHERE DATE(data) >= '$startDate' AND DATE(data) <= '$endDate' $categoryFilter";
        }


        $stmt = $connection->prepare($sql);
        $stmt->execute();
        if ($stmt->setFetchMode(PDO::FETCH_ASSOC)) {
            $registers = $stmt->fetchAll();
        }
        $connection = null;
        return $registers;
    }

    public function validatePresence($connection = null)
    {
        if (empty($connection)) {
            $connection = Database::getConnection();
        }

        $registers = null;
        $sql = "SELECT * FROM " . DB_NAME . ".presenca where id_pessoa = {$this->id_pessoa} and data = '{$this->data}'";

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        if ($stmt->setFetchMode(PDO::FETCH_ASSOC)) {
            $registers = $stmt->fetchAll();
        }
        $connection = null;
        return $registers;
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

    
    public static function getPresencesPerWeekDay($connection = null, $startDate = null, $endDate = null)
    {
        if (empty($connection)) {
            $connection = Database::getConnection();
        }

        $registers = null;

        $sql = "SELECT distinct
        (select count(id) from " . DB_NAME . ".presenca where week(data) = week(now()) AND WEEKDAY(data) = 0) as countMonday,
        (select count(id) from " . DB_NAME . ".presenca where week(data) = week(now()) AND WEEKDAY(data) = 1) as countTuesday,
        (select count(id) from " . DB_NAME . ".presenca where week(data) = week(now()) AND WEEKDAY(data) = 2) as countWednesday,
        (select count(id) from " . DB_NAME . ".presenca where week(data) = week(now()) AND WEEKDAY(data) = 3) as countThursday,
        (select count(id) from " . DB_NAME . ".presenca where week(data) = week(now()) AND WEEKDAY(data) = 4) as countFriday
        from " . DB_NAME . ".presenca pr join " . DB_NAME . ".pessoa pe on pe.id = pr.id_pessoa 
        WHERE pe.id_categoria in (select id from " . DB_NAME . ".categoria_pessoa cp where cp.ds_categoria = 'Assistido' );";

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        if ($stmt->setFetchMode(PDO::FETCH_ASSOC)) {
            $registers = $stmt->fetchAll();
        }

        if(isset($registers[0])){
            return $registers[0];
        }

        $connection = null;
        return $registers;
    }

    public static function getDataForDashboardCounters($connection = null, $startDate = null, $endDate = null)
    {
        if (empty($connection)) {
            $connection = Database::getConnection();
        }

        $registers = null;

        //getting presents
        $sql = "SELECT count(*) FROM " . DB_NAME . ".presenca pr join " . DB_NAME . ".pessoa pe on pe.id = pr.id_pessoa WHERE DATE(data) = CURDATE()
        AND pe.id_categoria in (select id from " . DB_NAME . ".categoria_pessoa cp where cp.ds_categoria = 'Assistido' )";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        if ($stmt->setFetchMode(PDO::FETCH_ASSOC)) {
            $res = $stmt->fetchAll();
            $registers['presentPersonCounter'] = $res ? array_values($res[0]) : 0;
        }

        //getting absents
        $sql = "SELECT count(*) from ".DB_NAME.".pessoa pe where pe.id_categoria in (select id from " . DB_NAME . ".categoria_pessoa cp where cp.ds_categoria = 'Assistido' ) 
        AND id not in (SELECT id_pessoa FROM " . DB_NAME . ".presenca WHERE DATE(data) = CURDATE())";

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        if ($stmt->setFetchMode(PDO::FETCH_ASSOC)) {
            $res = $stmt->fetchAll();
            $registers['presentAbsentCounter'] = $res ? array_values($res[0]) : 0;
        }

        $connection = null;
        return $registers;
    }

    public static function changeStatus(int $configId, int $newStatus)
    {
        $newStatusText = $newStatus == 1 ? 'ativado' : 'inativado';
        $response = ['status' => 1, 'msg' => 'Configuração ' . $newStatusText . ' com sucesso !'];
        $connection = Database::getConnection();
        try {
            $sql = "UPDATE " . DB_NAME . ".configuracao set status = :status WHERE id = :id";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':status', $newStatus);
            $stmt->bindParam(':id', $configId);

            $stmt->execute();
        } catch (PDOException $e) {
            $response['status'] = 0;
            $response['msg'] = 'Erro ao atualizar status => ' . $e->getMessage();
        }
        $connection = null;
        return $response;
    }

    private function classValidation()
    {
        if (!$this->validateInputs()) {
            return false;
        }

        return true;
    }

    private function validateInputs()
    {
        if (!empty($this->data) && !empty($this->hora_entrada) && !empty($this->id_pessoa)) {
            return true;
        }
        return false;
    }
}
