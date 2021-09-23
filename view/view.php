<?php
    
/**
 * View Class Api
 * 
 * @category View
 * @package  FirstTest
 * @author   RamakhanyaD <techcodehive@gmail.com>
 * @license  openSource 
 * @link     https://revtech.co.za
 */

 /**
  * Assisting methods for the application
  */
final class Helper{ 	

    public static function base(){
        return preg_replace('/index.php/', '', $_SERVER['PHP_SELF']);
    }
    /**
     * Get value of the URL arguments.
     * @return array|null parameter value
     */
    public static function urlArg(){
        if(! empty($_SERVER['QUERY_STRING'])){
            $arg = explode('/',$_SERVER['QUERY_STRING']);

            return $arg;
        }
        return null;
    }

    /**
     * Redirect to the given page.
     * @param type $page target page
     * @param array $params page parameters
     */
    public static function redirect($page, array $params = []) {
        header('Location: ' . self::createLink($page, $params));
        die();
    }

    /**
     * Generate link.
     * @param string $page target page
     * @param array $params page parameters
     * @return 
     */
    public static function createLink($page, array $params = []) {
        //unset($params['page']);
        if(empty($params)){
            return Helper::base().$page;
        }else{
            return Helper::base().$page.'/'.implode('/', $params);
        }
    }

    /**
     * Capitalize the first letter of the given string
     * @param string $string string to be capitalized
     * @return string capitalized string
     */
    public static function capitalize($string) {
        return ucfirst(mb_strtolower($string));
    }

    /**
     * Escape the given string
     * @param string $string string to be escaped
     * @return string escaped string
     */
    public static function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES);
    }
}

/**
 * Validation error.
 */
final class ValidatorError {

    private $source;
    private $message;

    /**
     * Create new validation error.
     * @param mixed $source source of the error
     * @param string $message error message
     */
    function __construct($source, $message) {
        $this->source = $source;
        $this->message = $message;
    }

    /**
     * Get source of the error.
     * @return mixed source of the error
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * Get error message.
     * @return string error message
     */
    public function getMessage() {
        return $this->message;
    }
}

/**
 * User class Api
 */
final class User{

    private $id;
    private $name;
    private $email;
    private $password;

    function __construct(){

    }

    public function setParam(stdClass $user){

        if(array_key_exists('id', $user)){
            $this->id = $user->id;
        }

        if(array_key_exists('name', $user)){
            $this->name = $user->name;
        }

        if(array_key_exists('email', $user)){
            $this->email = $user->email;
        }

        if(array_key_exists('password', $user)){
            $this->password = $user->password;
        }
    }

    public function register( array $user){
        $errors = [];

        if(trim($user['name'])){
            $this->name = $user['name'];
        }
        else{
            $errors[] = new ValidatorError('name','name cannot be empty');
        }

        if(trim($user['email'])){
            $this->email = $user['email'];
        }
        else{
            $errors[] = new ValidatorError('email','email cannot be empty');
        }

        if(trim($user['password'])){
            $this->password = $user['password'];
        }
        else{
            $errors[] = new ValidatorError('password','password cannot be empty');
        }
        
        if(trim($user['password2'])){
            if($user['password2'] != $this->password){
                $errors[] = new ValidatorError('Password Match','Password and passwordRepeat must match');
            }
        }
        else{
            $errors[] = new ValidatorError('passwordRepeat','passwordRepeat cannot be empty');
        }
        
        return $errors;
    }

    public function login( array $user){
        $errors = [];

        if(trim($user['email'])){
            $this->email = $user['email'];
        }
        else{
            $errors[] = new ValidatorError('email','email cannot be empty');
        }

        if(trim($user['password'])){
            $this->password = $user['password'];
        }
        else{
            $errors[] = new ValidatorError('password','password cannot be empty');
        }

        return $errors;
    }

    public function forgot( array $user){
        $errors = [];
        if(trim($user['name'])){
            $this->name = $user['name'];
        }
        else{
            $errors[] = new ValidatorError('name','name cannot be empty');
        }

        if(trim($user['email'])){
            $this->email = $user['email'];
        }
        else{
            $errors[] = new ValidatorError('email','email cannot be empty');
        }

        return $errors;
    }

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getPassword(){
        return $this->password;
    }
}

/**
 * Product class Api
 */
final class Product{

    private $id;
    private $ownerId;
    private $name;
    private $description;
    private $price;
    private $tags;

    function __construct(){

    }

    public function setParam(stdClass $product){

        if(array_key_exists('id', $product)){
            $this->id = $product->id;
        }

        if(array_key_exists('owner', $product)){
            $this->ownerId = $product->owner;
        }

        if(array_key_exists('name', $product)){
            $this->name = $product->name;
        }

        if(array_key_exists('description', $product)){
            $this->description = $product->description;
        }

        if(array_key_exists('price', $product)){
            $this->price = $product->price;
        }

        if(array_key_exists('tags',$product)){
            $this->tags = $product->tags;
        }
    }

    public function addEdit( array $pdt){
        $errors = [];

        if(trim($pdt['name'])){
            $this->name = $pdt['name'];
        }
        else{
            $errors[] = new ValidatorError('name','name cannot be empty');
        }
        if(trim($pdt['price'])){
            $this->price = $pdt['price'];
        }
        else{
            $errors[] = new ValidatorError('price','price cannot be empty');
        }
        if(trim($pdt['description'])){
            $this->description = $pdt['description'];
        }
        else{
            $errors[] = new ValidatorError('description','description cannot be empty');
        }
        if(trim($pdt['tags'])){
            $this->tags = $pdt['tags'];
        }
        else{
            $errors[] = new ValidatorError('tags','tags cannot be empty');
        }
        return $errors;
    }

    public function setId( int $id){
        $this->id = $id;
    }
    
    public function getId(){
        return $this->id;
    }

    public function setOwnerId(int $id){
        $this->ownerId = $id;
    }

    public function getOwnerId(){
        return $this->ownerId;
    }

    public function getName(){
        return $this->name;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getPrice(){
        return $this->price;
    }

    public function getTags(){
        return $this->tags;
    }
}

final class Cart {

    private static $price = 0;

    public function __construct(){
 
        $_SESSION['cartId'] = (new CartDao())
                                ->create();
    }

    public static function add(int $id){
    
        $_SESSION['cart'][$id] = $id;

        $page = explode('/',preg_replace(Helper::base(),'',$_SERVER['HTTP_REFERER']));

        Helper::redirect('list');
    }

    public static function delete(int $id){

        unset($_SESSION['cart'][$id]);

        $page = explode('/',preg_replace(Helper::base(),'',$_SERVER['HTTP_REFERER']));
        if(in_array('cart', $page) && self::count() > 0)
            Helper::redirect('cart');

        Helper::redirect('list');
    }

    public static function view(){
        $i = 0;
        $products[] = new Product;
        foreach( $_SESSION['cart'] as $pdt){
            $product = new ProductDao();
            $products[$i++] = $product->selectProperties('id',(int)$pdt);
        }
        foreach( $products as $pdt){
            self::$price += $pdt->getPrice();
        }
        return $products;
    }

    public static function button( int $id){

        if(! @array_key_exists( $id, @$_SESSION['cart'])){
            return '<p><a class="btn btn-success float-right" type="button" 
                        href="'.Helper::createLink('cart', [$id,'add']).
                            '" class="card-link" style="marging-left:10px;"> + </a></p>';
        }
        else{
            return '<p><a class="btn btn-danger float-right" type="button"
                        href="'. Helper::createLink('cart', [$id,'delete']).
                            '" class="card-link" style="marging-left:10px;"> x </a></p>';
        }
    }

    public static function count(){
        
        return isset($_SESSION['cart'])?sizeof($_SESSION['cart']):0;
    }

    public static function total(){
        return self::$price;
    }
}
