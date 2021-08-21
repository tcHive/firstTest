<?php
/**
 * Database, Data Access Api
 * 
 * @category Model
 * @package  FirstTest
 * @author   RamakhanyaD <techcodehive@gmail.com>
 * @license  openSource 
 * @link     https://revtech.co.za
 **/

 /**
  * Database config & access Api
  */
class Db
{
    private $_host = "localhost";
    private $_db_name = "testproject";
    private $_user = "root";
    private $_password = "";

    protected $pdo;

    /**
     * Function to establish pdo connection
     * 
     * @return Pdo
     * @throws Exception
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

            // for development purposes only
            // catch and resolve at production level

            throw $e;
        }
    }

    /**  
     * Insert Method Api
     *
     * @param $tableName name of table
     * @param Arr $columns   name of the column
     * @param Arr $values    value of the column
     * 
     * @return bool
     * @throws Exception
     * **/
    protected function insert(string $tableName, array $columns, array $values)
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

            return $this->pdo->lastInsertId();
        } 
            throw new Exception("Failed to save");
    }

    /**  
     * Update Method Api
     *
     * @param $tableName name of table
     * @param Arr $columns   name of the column
     * @param Arr $values    value of the column
     * @param $where     id of column we want to effect change
     * 
     * @return bool
     * @throws Exception
     * **/
    protected function update(string $tableName, array $columns, array $values, $where)
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
        } 
            throw new Exception("Failed to update query");
    }

    /**  
     * Select Method Api
     *
     * @param $tableName name of table
     * @param Arr $columns   name of the column
     * @param default:null $where     name of column
     * @param $values value for the where column
     * 
     * @return Sql
     * @throws Exception
     **/
    protected function select(string $tableName, $columns, $where = null,$values = null ) {
            
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

            if ( $num > 0  && $num < 2) {
              
                return $row = $stmt->fetchAll();
            }
                throw new NotFoundException("Failed to retrive record");

        } else {
                
            $stmt->execute();
            $num = $stmt->rowCount();

            if ($num > 0) {
            
                return $row = $stmt->fetchAll();
            }
                throw new NotFoundException("Failed to retrive records");
        }
    }

    /**  
     * Delete Method Api 
     * 
     * @param $tableName name of table
     * @param Arr $values    value of the column
     * @param Arr $where     name of column
     * 
     * @return true 
     * @throws exception on failure
     * **/
    protected function delete(string $tableName, $values, $where)
    { 
        $sql = "
                DELETE FROM $tableName WHERE $where = ?
            ";

        $stmt = $this->pdo->prepare($sql);

        if ($stmt->execute($values)) {

                return true;
        }
            throw new Exception("Failed to delete record");
    }

    /** 
      *  Null the PDO connector
     **/
    function __destruct()
    {
        $this->pdo = null;
    }
}

/**
 * Database handler class for sessions
 */
class mysqlsessionHandler extends Db implements SessionHandlerInterface {

    // properties 
    private $conn;
    //protected $tableName ="sessions";
    private $useTransactions;
    protected $unlockStatements = [];
    protected $collectGarbage = false;
    private $expiry;

    // methods
    function __construct($useTransactions = true){

        $this->conn = $this->connect();
        $this->useTransactions = $useTransactions;
        $this->expiry = time() + 60 * 60;
    }

    public function open( $savePath, $name){

        return true;
    }

    public function read( $session_id){

        try{

            $sql = "
                SELECT expiry, data FROM sessions WHERE sid = :id
            ";
 
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $session_id);
            $stmt->execute();
            $result = $stmt->fetch();

            if( isset($result)){
                if( @$result->expiry < time()){
                    return '';
                }
                return $result->data;
            }
            // -------------
            // register the session in the database
            if( $this->useTransactions){

                $this->initializeRecord( $stmt);
            }

            return '';
        }
        catch( Exception $e){

            throw $e;
        }
    }

    public function write( $session_id, $data){

        try{

            $sql = "
                    Insert Into sessions( sid, expiry, data)
                        Values (:id, :time, :data) On Duplicate key 
                            Update expiry = :time, data = :data
            ";
                
            $stmt = $this->conn->prepare( $sql);
            $stmt ->bindParam(':id',$session_id);
            $stmt ->bindParam(':time',$this->expiry);
            $stmt ->bindParam(':data',$data);
            $stmt->execute();

            return true;
        }
        catch( Exception $e){

            throw $e;
        }
    }

    public function close(){

        if( $this->collectGarbage){

            $sql = "
                DELETE FROM sessions WHERE expiry < :time
            ";
            $stmt = $this->conn->prepare( $sql);
            $stmt->bindParam( ':time', time(), PDO::PARAM_INT);
            $stmt->execute();
            $this->collectGarbage = false;
        }
        return true;
    }

    public function destroy( $session_id){

        $sql = "
            DELETE FROM sessions WHERE sid=:id
        ";
        try{
            $stmt = $this->conn->prepare( $sql);
            $stmt->bindParam(':id', $session_id);
            $stmt->execute();
        }
        catch( Exception $e){

            throw $e;
        }
        return true;
    }

    public function gc( $maxlifetime){

        $this->collectGarbage = true;
        return true;
    }
 
    protected function getLock( $session_id){

        $stmt = $this->conn->prepare('SELECT GET_LOCK(:id, 50)');
        $stmt->bindParam(':id', $session_id);
        $stmt->execute();

        $release = $this->conn->prepare('DO RELEASE_LOCK(:id)');
        $release->bindParam(':id', $session_id);

        return $release;
    }

    protected function initializeRecord( $stmt){

        try{ 
 
            $var = "";
            $sql = "
                INSERT INTO sessions( sid, expiry, data) VALUES ( :id, :time, :data)
            ";
            $stmt = $this->conn->prepare( $sql);
            $stmt->bindParam(':id', $session_id);
            $stmt->bindParam(':time',$this->expiry);
            $stmt->bindParam(':data', $var);
            $stmt->execute();
            
                return '';
        }
        catch( Exception $e){

           if( 0 === strpos( $e->getMessage(), '23')){

            $stmt->execute();
            $result = $stmt->fetch();
            
            if( $result){
                return $result->data;
            } 
                return '';
           } 

            throw $e;
        }
    }
    
    public function getExpiry(){
        
        return $this->expiry;
    }

    function __destruct(){
            
        $this->conn = null;
    }
}

/**
 * User database access object class
 */
class UserDao extends Db
{
    private $tableName = 'users';

    private $col = 'id';
    private $columns = ['name','email','password'];

    /**
     * Establish a Pdo 
     * connection under inheritance
     */
    function __construct(){

        $this->pdo = $this->connect();
    }

    function setProperties(User $obj){

        $values = [$obj->getName(), $obj->getEmail(),
            $obj->getPassword()];
           
        $result = $this->insert($this->tableName, $this->columns, $values);

        return $result;
    }

    function updateProperties(User $obj){

        $values = [$obj->getName(), $obj->getEmail(),
            $obj->getPassword(), $obj->getId()];

        $result = $this->update($this->tableName, $this->columns, $values, $this->col);

        return $result;
    }

    function selectProperties( string $identifier , User $obj ){

        $this->columns[3] = $this->col;
          
        if(isset($identifier)){

            $values = [$obj->getEmail()];

            $result = $this->select($this->tableName, 
                $this->columns, $identifier ,$values );

            $user = new User();
            $user->setParam($result[0]);

            return $user;
        }
            throw new Exception("User Locator cannot be empty");
        /*else{

            $result = $this->select($this->tableName, $this->columns);

            return $result;
            } */
    }
    /*
        function deleteProperties( int $value){

            $value = [$value];

            $result = $this->delete($this->tableName, $value, $this->col);

            return $result;
        }
        */    
}

/**
 * Product database access object class
 */
class ProductDao extends Db 
{
    private $tableName = 'products';

    private $col = 'id';
    private $column = [ 'owner','name', 'description',
                        'price', 'tags'];

    /**
     * Establish a Pdo 
     * connection under inheritance
     */
    function __construct(){

        $this->pdo = $this->connect();
    }

    function setProperties(Product $pdt ){

        $values = [$pdt->getOwnerId(), $pdt->getName(), $pdt->getDescription(),
            $pdt->getPrice(), $pdt->getTags()];
        
        $result = $this->insert($this->tableName, $this->column, $values);

        return $result;
    }

    function updateProperties( Product $pdt){

        $values = [$pdt->getName(),$pdt->getDescription(), $pdt->getPrice(),
            $pdt->getTags(), $pdt->getId()];

        $result = $this->update($this->tableName, $this->column, $values, $this->col);

        return $result;
    }

    function selectProperties( int $where = null){

        if( $where !== null){

            $values = [$where];

            $this->column[5] = $this->col;

            $result = $this->select($this->tableName, $this->column,
                    $this->col,$values);

            $product = new Product();
            $product->setParam($result[0]);

            return $product;

        }else{
                
            $this->column[5] = $this->col;

            $result = $this->select($this->tableName, $this->column);
            
            $value = [];

            foreach($result as $rw){

                $product = new Product();
                $product->setParam( $rw);

                $value[$product->getId()] = $product;
            }
            return $value;
        }
    }

    function deleteProperties( int $value){

            $value = [$value];

            return $this->delete($this->tableName, $value, $this->col); 
    }
}

class CartDao extends Db
{
    private $tableName = 'cart';

    private $column = ['id','product'];

    function __construct(){

        $this->pdo = $this->connect();
    }

    public function create(){

        $sql = "
                SELECT id FROM $this->tableName
            ";
        
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();
        $num = $stmt->rowCount();

        return ++$num;
    }

    public function inser( int $id){

        $sql = "
            Insert Into $this->tableName(id) Values(?)
        ";
     
        $value = [$id];
        $stmt = $this->pdo->prepare($sql);

        if ($stmt->execute($value)) {

                return true;
        } 
            throw new Exception("Failed to save");
    }

    public function add(array $pdt, int $id){

        //$pdt = implode(',', $pdt);

        $sql = "
            Update $this->tableName set product = ? where id = ?
        ";

        //$value = [$pdt, $id];

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(1,$pdt);
        $stmt->bindParam(2,$id);

        if ($stmt->execute()) {

            return true;
        } 
            throw new Exception("Failed to update query");
    }

    public function fetch( int $id){

        $sql = "
                SELECT product FROM $this->tableName where id = ?
            ";
        
        $stmt = $this->pdo->prepare($sql);

        $value = [$id];
        $stmt->execute($value);
        
        $row = $stmt->fetchAll();

        return $row[0];
    }
}