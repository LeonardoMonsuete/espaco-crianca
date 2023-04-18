<?php
namespace Models;
use Models\Database;
use DateTime;
use Exception;
use PDO;
use PDOException;

class User {
    public int $id;
    public ?string $nome;
    public ?string $usuario;
    public ?string $senha;
    public ?string $email;
    public DateTime $created_at;
    public DateTime $updated_at;
    public Bool $master;
    private ?string $classValidation;

    public function __construct($usuario, $senha, $nome, $email, $master, $fromRegister = true, $updating = false){
        $this->nome = $nome;
        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->email = $email;
        $this->master = ($master) ? true : false;

        if(!$this->classValidation() && $fromRegister === true){
            throw new Exception($this->classValidation['errors']);
        }
    }

    public function makeLogin()
    {
        $connection = Database::getConnection(); 
        $response = ['status' => 0, 'msg' => 'Senha incorreta'];
        if(!$connection){
            ['status' => 0, 'msg' => 'Erro ao criar conexão com  o banco de dados'];
        }

        $user = $this->getUserByAttribute($connection, 'usuario', $this->usuario);

        if(empty($user)){
            $response['msg'] = 'Usuário incorreto';
            return $response;
        }

        if($this->senha === $user['senha']){
            $response['status'] = 1;
            $response['loggedUserData'] = $user;
            $response['msg'] = 'Login permitido';
            return $response;
        }

        return $response;
    }

    public static function getUserByAttribute($connection = null, $attribute, $value) 
    {   
        if(empty($connection)){
            $connection = Database::getConnection();
        }

        $user = null;

        $sql = "SELECT * FROM ".DB_NAME.".usuarios where $attribute = '$value' limit 1";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        if($stmt->setFetchMode(PDO::FETCH_ASSOC)){
            $user = $stmt->fetch();
        }
        $connection = null;
        return $user;
    }

    public static function getUsers() 
    {
        $connection = Database::getConnection();
        $users = [];
    
        $sql = "SELECT * FROM ".DB_NAME.".usuarios";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        if($stmt->setFetchMode(PDO::FETCH_ASSOC)){
            $users = $stmt->fetchAll();
        }
        $connection = null;
        return $users;
    }

    public function save()
    {
        $response = ['status' => 1, 'msg' => 'Usuário ' . $this->nome . ' inserido com sucesso !'];
        if (!empty($this->id)) {
            throw new Exception('Id ja configurado para esse registro' . $this->id);
        }

        $connection = Database::getConnection();

        try {
            $sql = "INSERT INTO " . DB_NAME . ".usuarios (nome, usuario, senha, master) 
            VALUES (:nome,:usuario,:senha,:master)";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':usuario', $this->usuario);
            $stmt->bindParam(':senha', $this->senha);
            $stmt->bindParam(':master', $this->master);
            $stmt->execute();
        } catch (PDOException $e) {
            $response['status'] = 0;
            $response['msg'] = 'Erro ao inserir usuário => ' . $e->getMessage();
            if ($e->getCode() == 23000) {
                $response['msg'] = 'O nome de usuário utilizado ja está em uso => ' . $e->getMessage();
            }
        }
        $connection = null;
        return $response;
    }

    public function update()
    {
        $response = ['status' => 1, 'msg' => 'Usuário ' . $this->nome . ' atualizado com sucesso !'];

        $connection = Database::getConnection();

        try {
         
            $sql = "UPDATE " . DB_NAME . ".usuarios SET nome = :nome, usuario = :usuario, email = :email, updated_at = now(), master = :master
            WHERE id = :id";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':usuario', $this->usuario);
            $stmt->bindParam(':master', $this->master);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
        } catch (PDOException $e) {
            $response['status'] = 0;
            $response['msg'] = 'Erro ao atualizar usuário => ' . $e->getMessage();
        }
        $connection = null;
        return $response;
    }

    private function classValidation()
    {
        if(!$this->validateNome()){
            return false;
        }

        if(!$this->validatEmail()){
            return false;
        }

        if(!$this->validateUsuario()){
            return false;
        }

        if(!$this->validateSenha()){
            return false;
        }

        return true;
    }

    private function validateNome()
    {
        if(!empty($this->nome) && strlen($this->nome) > 3){
            return true;
        }
        return false;
    }

    private function validatEmail()
    {
        if(!empty($this->email) && preg_match("/^[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/",$this->email)){
            return true;
        }
        return false;
    }

    private function validateUsuario()
    {
        if(!empty($this->usuario) && strlen($this->usuario) > 3){
            return true;
        }
        return false;
    }

    private function validateSenha()
    {
        if(isset($_POST['isUpdate']) && $_POST['isUpdate'] == true){
            return true;
        }
        if(!empty($this->senha) && strlen($this->senha) > 5){
            return true;
        }
        return false;
    }
    
}