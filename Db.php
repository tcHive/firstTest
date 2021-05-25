<?php
/**
 * Model
 * 
 * Database class Api
 * 
 * @category Database
 * @doc      Connector and data handler Api
 * @package  FirstTest
 * @author   RamakhanyaD <techcodehive@gmail.com>
 * @license  openSource 
 * @link     https://revtech.co.za
 **/
class Db
{

    private $_host = "localhost";
    private $_db_name = "test";
    private $_user = "root";
    private $_password = "";

    protected $pdo;

    function __construct(){
        $this->pdo = $this->connect();
    }
    /**
     * Function to establish pdo
     * 
     * @return pdo
     */
    protected function connect()
    {
            $this->pdo = null;

        try
        {
                    
            $dsn = "mysql:hostname=$this->_host;dbname=$this->_db_name";
            $this->pdo = new PDO($dsn, $this->_user, $this->_password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); 
            
            return $this->pdo;
        }
        catch(PDOException $e){

            echo 'Connection Error'.$e->getMessage();
        }

           
    }

    /**  
     * Insert function Api
     *
     * @param $tableName name of table
     * @param Arr $columns   name of the column
     * @param Arr $values    value of the column
     * 
     * @return bool
     * **/
    protected function insert(string $tableName, $columns, $values)
    {

            $string = implode(',', $columns);
            
            $value = null;
            
        foreach ( $columns as $columns) {
            if ($value == null) {
                    $value = '?';
            } else {     
                 $value = $value. ',?';
            }
        }

            $sql = "
                Insert into $tableName($string) Values($value)
            ";

            $stmt = $this->pdo->prepare($sql);

        if ($stmt->execute($values)) {

                return true;
        } else {
                return false;
        }
    }

    /**  
     * Update Function Api
     *
     * @param $tableName name of table
     * @param Arr $columns   name of the column
     * @param Arr $values    value of the column
     * @param $where     id of column we want to effect change
     * 
     * @return bool
     * **/
    protected function update(string $tableName, $columns, $values, $where)
    {   
        $i = 0;

        foreach ( $columns as $col) {
                $columns[$i] = $col .' = ?';
                $i++; 
        }
            $string = implode(',', $columns);

        $sql = "
                UPDATE $tableName SET  $string WHERE $where = ?
            ";

            $stmt = $this->pdo->prepare($sql);

        if ($stmt->execute($values)) {

                return true;
        } else {
                return false;
        }
    }

    /**  
     * Basic Select Function Api
     *
     * @param $tableName name of table
     * @param Arr $columns   name of the column
     * @param default:null $where     name of column
     * 
     * @return Sql/bool
     * **/
    protected function select(string $tableName, $columns, 
        $values = null, $where = null
    ) {
            
        $string = implode(',', $columns);

        $sql = "
                SELECT $string FROM $tableName
            ";

        if ($where !== null) {
                $sql = $sql ." WHERE $where = ?";
        }

            $stmt = $this->pdo->prepare($sql);

        if ($values !== null) {

                $stmt->execute($values);

                if ($num = $stmt->rowCount() && $num > 0) {
              
                return $row = $stmt->fetchAll();
            }

                return false;

        } else {
                
                $stmt->execute();

            if ($num = $stmt->rowCount() && $num > 0) {
            
                return $row = $stmt->fetchAll();
            }

                return false;
        }
    }

    /**  
     * Delete Function Api 
     * 
     * @param $tableName name of table
     * @param $values    value of the column
     * @param $where     name of column
     * 
     * @return bool
    **/
    protected function delete(string $tableName, $values, $where)
    { 
            $sql = "
                DELETE FROM $tableName WHERE $where = ?
            ";

            $stmt = $this->pdo->prepare($sql);

        if ($stmt->execute($values)) {

                return true;
        } else {
                return false;
        }
    }

    /** 
      *  Null the PDO connector
     **/
    function __destruct()
    {
            $this->pdo = null;
    }
        

}
?>
