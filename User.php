<?php
    
/**
 * Controller 
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

    protected $columns = ['name','email','password'];


    function setProperties( string $name, string $email, string $password){

            $columns =$this->columns;
            $values = [$name, $email, $password];
           
            if($this->insert($this->tableName, $columns, $values)){

                echo 'true';
            }else{
                echo 'false';
            }
    }

    function updateProperties( string $name, string $email, string $password, $where){

            $columns = $this->columns;
            $values = [$name, $email, $password, $where];
            $colunmToSelect = 'id';

            if($this->update($this->tableName, $columns, $values, $colunmToSelect)){

                echo 'true';
            }else{
                echo 'false';
            }
    }

    function selectProperties( $where = null){

            $columns =[ 'id','name','email','password'];
          
            if( $where !== null){

                $values = [$where];
                $colunmToSelect = 'email'; // where in sql clause

                if( $result = $this->select($this->tableName, $columns,$values,$colunmToSelect)){


                   return $result;

                }else{
                    return false;
                }
            }else{

                if($result = $this->select($this->tableName, $columns)){

                    return $result;
                }else{
                    return false;
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

    function getUserID(){

        return $this->id;
    }
        
}

class Products extends Db implements Cart{

    private $tableName = 'products';

    protected $this->column =['name', 'description', 'price', 'tags'];

    function setProperties( string $name, string $description, int $price, $tags ){

            $column = $this->column;
            $values = [$name, $description, $price, $tags];
        
            if($this->insert($this->tableName, $column, $values)){

                echo 'true';
            }else{
                echo 'false';
            }
    }

    function updateProperties( string $name, string $description, int $price, $tags,  $where){

            $column = $this->column;
            $values = [$name, $description, $price, $tags, $where];
            $colunmToSelect = 'id';

            if($this->update($this->tableName, $column, $values, $colunmToSelect)){

                echo 'true';
            }else{
                echo 'false';
            }
    }

    function selectProperties( $where = null){

        $column = $this->column;
        
            if( $where !== null){

                $values = [$where];
                $colunmToSelect = 'id';

                if($result = $this->select($this->tableName, $columns,$values,$colunmToSelect)){

                    return $result;
                }else{
                return false;
                }
            }else{

                if($result = $this->select($this->tableName, $columns)){

                return $result;
                }else{
                return false;
                }
            }
    }

    function deleteProperties( $value){

            $value = [$value];

            if($this->delete($this->tableName, $value, 'id')){

                return true;
            }else{

                return false;
            }
    }

    function getProductID(){

        $this->add();
        return $this->id;
    }

}

interface Cart {

    protected function add( int $id){
        if(isset($_SESSION['cart'])){

            $_SESSION['cart'] = $_SESSION['cart']. ','. $id ; 
        }else{

            $_SESSION['cart'] = $id;
        }
    }

    protected function delete(int $id){

        if(isset($_SESSION['cart'])){

           $cart = explode(',', $_SESSION['cart']);

           $i = 0;
           foreach ($cart as $key){
                if( $key !== $id){
                    $newCart[$i] = $key;
                    $i++;
                }
           }

           $_SESSION['cart'] = implode(',', $newCart);

           return true;
        }else{

           return false;
        }
    }
    protected function checkout(){

        if(isset($_SESSION['cart'])){

            $cart = explode(',', $_SESSION['cart']);

            return $cart;
        }

        return false;

    }
}