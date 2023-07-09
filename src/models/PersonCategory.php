<?php

namespace Models;
use Models\Database;
use Exception;
use PDO;

class PersonCategory
{
    public int $id;
    public ?string $ds_categoria;
    private ?string $classValidation;

    public function __construct($ds_categoria, $fromRegister = false)
    {
        $this->ds_categoria = $ds_categoria;

        if (!$this->classValidation() && $fromRegister === true) {
            throw new Exception($this->classValidation['errors']);
        }
    }

    public static function getCategoryByAttribute($connection = null, $attribute, $value) 
    {
        if(empty($connection)){
            $connection = Database::getConnection();
        }

        $category = null;

        $sql = "SELECT * FROM ".DB_NAME.".categoria_pessoa where $attribute = '$value' limit 1";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        if($stmt->setFetchMode(PDO::FETCH_ASSOC)){
            $category = $stmt->fetch();
        }
        $connection = null;
        return $category;
    }

    public static function getCategories($connection = false, $fields = "*")
    {
        if (empty($connection)) {
            $connection = Database::getConnection();
        }
        $categories = [];

        $sql = "SELECT $fields FROM " . DB_NAME . ".categoria_pessoa order by ds_categoria";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        if ($stmt->setFetchMode(PDO::FETCH_ASSOC)) {
            $categories = $stmt->fetchAll();
        }
        $connection = null;
        return $categories;
    }

    private function classValidation()
    {
        if (!$this->validateDsCategoria()) {
            return false;
        }

        return true;
    }

    private function validateDsCategoria()
    {
        if (!empty($this->ds_categoria) && strlen($this->ds_categoria) > 3) {
            return true;
        }
        return false;
    }
}

?>