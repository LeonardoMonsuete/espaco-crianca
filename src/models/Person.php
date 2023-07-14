<?php

namespace Models;
use Models\Database;
use DateTime;
use Exception;
use PDO;
use PDOException;

class Person
{
    public int $id;
    public ?string $nome;
    public ?int $id_categoria;
    public ?string $matricula;
    public ?string $img_01;
    public ?string $img_02;
    public mixed $id_responsavel;
    public DateTime $created_at;
    public DateTime $updated_at;
    public bool $status;
    private ?string $classValidation;
    public const _MEDIA_FILE_PATH = '/espaco-crianca/media/pictures/person/';

    public function __construct($nome, $id_categoria, $matricula, $img_01, $img_02, $id_responsavel, $status, $fromRegister = false)
    {
        $this->nome = $nome;
        $this->id_categoria = $id_categoria;
        $this->matricula = $matricula;
        $this->img_01 = $img_01;
        $this->img_02 = $img_02;
        $this->status = $status;
        $this->id_responsavel = (empty($id_responsavel)) ? 0 : $id_responsavel;

        if (!$this->classValidation() && $fromRegister === true) {
            throw new Exception($this->classValidation['errors']);
        }
    }

    public static function getPersonByAttribute($connection = null, $attribute, $value)
    {
        try {
            if (empty($connection)) {
                $connection = Database::getConnection();
            }

            $person = null;

            $sql = "SELECT p.*, cp.id as id_categoria, cp.ds_categoria FROM " . DB_NAME . ".pessoa p JOIN " . DB_NAME . ".categoria_pessoa cp on p.id_categoria = cp.id where p.$attribute = '$value' limit 1";

            $stmt = $connection->prepare($sql);
            $stmt->execute();
            if ($stmt->setFetchMode(PDO::FETCH_ASSOC)) {
                $person = $stmt->fetch();
            }

            $connection = null;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $person;
    }

    public static function getPerson($connection = false, $fields = "*")
    {
        try {
            if (empty($connection)) {
                $connection = Database::getConnection();
            }
            $pessoas = [];
    
            $sql = "SELECT $fields FROM " . DB_NAME . ".pessoa";
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            if ($stmt->setFetchMode(PDO::FETCH_ASSOC)) {
                $pessoas = $stmt->fetchAll();
            }
            $connection = null;
        
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $pessoas;
    }

    public function save()
    {
        $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/media/pictures/person/" . $this->matricula;
        if(!is_dir($baseDir)){
            if(mkdir($baseDir)){
                copy($this->img_01, $baseDir . "/img_01.jpg");
                copy($this->img_02, $baseDir . "/img_02.jpg");
            }
        } else {
            copy($this->img_01, $baseDir . "/img_01.jpg");
            copy($this->img_02, $baseDir . "/img_02.jpg");
        }

        $response = ['status' => 1, 'msg' => 'Pessoa ' . $this->nome . ' inserida com sucesso !'];
        if (!empty($this->id)) {
            throw new Exception('Id ja configurado para esse registro' . $this->id);
        }

        $connection = Database::getConnection();

        try {
            $img_01_to_record = $this->matricula . "/img_01.jpg";
            $img_02_to_record = $this->matricula . "/img_02.jpg";
            $sql = "INSERT INTO " . DB_NAME . ".pessoa (nome, matricula, 
            img_01, img_02, id_responsavel, status) 
            VALUES (:nome,:matricula,:img_01,:img_02,:id_responsavel, :status)";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':matricula', $this->matricula);
            $stmt->bindParam(':status', $this->status);
            $stmt->bindParam(':img_01', $img_01_to_record);
            $stmt->bindParam(':img_02', $img_02_to_record);
            $stmt->bindParam(':id_responsavel', $this->id_responsavel);
            $stmt->execute();
        } catch (PDOException $e) {
            $response['status'] = 0;
            $response['msg'] = 'Erro ao inserir pessoa => ' . $e->getMessage();
            if ($e->getCode() == 23000) {
                $response['msg'] = 'O nome utilizado ja está em uso => ' . $e->getMessage();
            }
        }
        $connection = null;
        return $response;
    }

    public function update()
    {
        $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/media/pictures/person/" . $this->matricula;
        if(!is_dir($baseDir)){
            if(mkdir($baseDir)){
                copy($this->img_01, $baseDir . "/img_01.jpg");
                copy($this->img_02, $baseDir . "/img_02.jpg");
            }
        } else {
            if($this->img_01 && $this->img_02){
                copy($this->img_01, $baseDir . "/img_01.jpg");
                copy($this->img_02, $baseDir . "/img_02.jpg");
            }
        }

        $response = ['status' => 1, 'msg' => 'Pessoa ' . $this->nome . ' atualizado com sucesso !'];

        $connection = Database::getConnection();

        try {
            $img_01_to_record = $this->matricula . "/img_01.jpg";
            $img_02_to_record = $this->matricula . "/img_02.jpg";
            $imgsSqlComplement = "";
            if($this->img_01 && $this->img_02){
                $imgsSqlComplement = "img_01 = :img_01, img_02 = :img_02,";
            }
            $sql = "UPDATE " . DB_NAME . ".pessoa SET nome = :nome, id_categoria = :id_categoria, matricula = :matricula, updated_at = now(),
            $imgsSqlComplement id_responsavel = :id_responsavel, status = :status
            WHERE id = :id";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':matricula', $this->matricula);
            $stmt->bindParam(':id_categoria', $this->id_categoria);
            $stmt->bindParam(':status', $this->status);
            if($this->img_01 && $this->img_02){
                $stmt->bindParam(':img_01', $img_01_to_record);
                $stmt->bindParam(':img_02', $img_02_to_record);
            }
            $stmt->bindParam(':id_responsavel', $this->id_responsavel);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
        } catch (PDOException $e) {
            $response['status'] = 0;
            $response['msg'] = 'Erro ao atualizar pessoa => ' . $e->getMessage();
        }
        $connection = null;
        return $response;
    }

    public static function changeStatus(int $userId, int $newStatus)
    {
        $newStatusText = $newStatus == 1 ? 'ativado' : 'inativado';
        $response = ['status' => 1, 'msg' => 'Pessoa ' . $newStatusText . ' com sucesso !'];
        $connection = Database::getConnection();
        try {
            $sql = "UPDATE " . DB_NAME . ".pessoa set status = :status WHERE id = :id";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':status', $newStatus);
            $stmt->bindParam(':id', $userId);

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
        if (!$this->validateNome()) {
            return false;
        }

        if (!$this->validateMatricula()) {
            return false;
        }

        if (!$this->validateImages()) {
            return false;
        }

        return true;
    }

    private function validateNome()
    {
        if (!empty($this->nome) && strlen($this->nome) > 3) {
            return true;
        }
        return false;
    }

    private function validateMatricula()
    {
        if (!empty($this->matricula)) {
            return true;
        }
        return false;
    }

    private function validateImages()
    {
        $isRegisterAlreadyPresent = self::getPersonByAttribute(null,'nome',$this->nome);
        if(isset($_POST['uid'])){
            $isRegisterAlreadyPresent = self::getPersonByAttribute(null,'id',$_POST['uid']);
        }

        if(empty($isRegisterAlreadyPresent)){
            if (empty($this->img_01) || empty($this->img_02)) {
                return false;
            }
        }
        
        if(empty($isRegisterAlreadyPresent['img_01'])){
            if (empty($this->img_01)) {
                return false;
            }
        }

        if(empty($isRegisterAlreadyPresent['img_02'])){
            if (empty($this->img_02)) {
                return false;
            }
        }

        return true;
    }
}
