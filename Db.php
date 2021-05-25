<?php
/**
 * Database class
 * Some form of short description
 * 
 * @category Database
 * @doc      Connector and data handler function 
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
        }
        catch(PDOException $e){

            echo 'Connection Error'.$e->getMessage();
        }

            return $this->pdo;
    }

    /**  
     * Halala
     *
     * @param $tableName name of table
     * @param $columns   value of the column
     * @param $values    value of the column
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
     * Halala
     *
     * @param $tableName name of table
     * @param $columns   name of the column
     * @param $values    value of the column
     * @param $where     name of column we want to effect change
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
     * Halala
     *
     * @param $tableName name of table
     * @param $columns   value of the column
     * @param $values    value of the column
     * @param $where     name of column
     * 
     * @return bool
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

                $num = $stmt->rowCount();
    
            if ($num > 0) {
            
                    $row = $stmt->fetchAll();

                    var_dump($row);
                    echo $row = implode(',', $columns);

                    //error_log($row);
                return true;
            }

                return false;

        } else {
                
                $stmt->execute();

                $num = $stmt->rowCount();
    
            if ($num > 0) {
            
                $row = $stmt->fetchAll();
            
                return true;
            }

                return false;
        }
    }

        /**  
         * Halala 
         * 
         * @param $tableName name of table
         * @param $values    value of the column
         * @param $where     name of column
         * 
         * @return bool
         * **/
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
         *  */
    function __destruct()
    {
            $this->pdo = null;
    }
        
}
?>
