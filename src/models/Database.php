<?php
namespace Models;
use Exception;
use PDO;
use PDOException;

require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/database.config.php";
Class Database {

    public static $connection;

    public function __construct(){}

    public static function getConnection()
    {
        $pdoConfig  = "mysql:". "Server=" . DB_SERVER . ";";
        $pdoConfig .= "Database=espaco_crianca;".DB_NAME.";";

        try {
            if(!isset($connection)){
                $connection =  new PDO($pdoConfig, DB_USER, DB_PASS);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $connection;
         } catch (PDOException $e) {
            $mensagem = "Drivers disponiveis: " . implode(",", PDO::getAvailableDrivers());
            $mensagem = "\nErro: " . $e->getMessage();
            throw new Exception($mensagem);
         }
    }

}