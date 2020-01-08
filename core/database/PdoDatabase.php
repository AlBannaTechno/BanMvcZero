<?php

// TODO : Update Syntax to ^7.4.1 which support types https://wiki.php.net/rfc/typed_properties_v2

/**
 * We Implement Singleton pattern
 * so we need to use IoC to make at pattern oriented because of singleton is an anti pattern
 */
class PdoDatabase
{
    private static $instance = null;
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    // db handler
    private  $dbh;
    private  $_stmt;
    private  $error;

    // trick to go around php ,  we should remove it after upgrade to php 7.4.1+
    private function stmt() : PDOStatement {
        return $this->_stmt;
    }

    private function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = [
            // for performance
            PDO::ATTR_PERSISTENT => true,
            // exception very coast , so we may change this in the feature
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try{
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }catch (PDOException $pe){
            $this->error = $pe->getMessage();
            echo $this->error;
        }
    }

    public static function getInstance(): PdoDatabase {
        if (self::$instance === null){
            self::$instance = new PdoDatabase();
        }
        return self::$instance;
    }

    public function query($aql) : PdoDatabase {
        $this->_stmt = $this->dbh->prepare($aql);
        return $this;
    }

    public function bind($param, $value, $type = null) : PdoDatabase {
        if ($type === null){
            switch (true){
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case $value === null:
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt()->bindValue($param, $value, $type);

        return $this;
    }

    public function bindSet(array $values) : PdoDatabase{
        foreach ($values as $param => $value) {
            $this->bind($param, $values);
        }
        return $this;
    }

    public function execute() : PdoDatabase {
        $this->stmt()->execute();
        return $this;
    }

    public function resultSet() : array {
         return $this->stmt()->fetchAll(PDO::FETCH_OBJ);
    }

    public function single() : object {
        return $this->stmt()->fetch(PDO::FETCH_OBJ);
    }

    public function count() : int {
        return $this->stmt()->rowCount();
    }

}
