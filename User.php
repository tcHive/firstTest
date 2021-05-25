<?php
/**
 * User model
 * 
 * @category Database
 * @doc      Connector and data handler function 
 * @package  FirstTest
 * @author   RamakhanyaD <techcodehive@gmail.com>
 * @license  openSource 
 * @link     https://revtech.co.za
 */

require "db.php";

class User extends Db{

    private $tableName = 'users';

    function __construct(){
            $this->pdo = $this->connect();
    }

    function setProperties( string $name, string $email, string $password){

            $columns =['name','email','password'];
            $values = [$name, $email, $password];
           
            if($this->insert($this->tableName, $columns, $values)){

                echo 'true';
            }else{
                echo 'false';
            }
    }


    function updateProperties( string $name, string $email, string $password, $where){

            $columns =['name','email','password'];
            $values = [$name, $email, $password, $where];
            
            if($this->update($this->tableName, $columns, $values, 'id')){

                echo 'true';
            }else{
                echo 'false';
            }
    }

    function selectProperties( $where = null){

            $columns =['name','email','password'];
          
            if( $where !== null){

                $values = [$where];
                $colunmToChange = 'id';

                if($this->select($this->tableName, $columns,$values,$colunmToChange)){

                    echo 'true';
                }else{
                    echo 'false';
                }
            }else{

                if($this->select($this->tableName, $columns)){

                    echo 'true';
                }else{
                    echo 'false';
                }
            }
    }

    function deleteProperties( $value){

            $value = [$value];

            if($this->delete($this->tableName, $value, 'id')){

                echo 'true';
            }else{

                echo 'false';
            }
    }
        
}
?>