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

    /**
     * Get value of the URL param.
     * @return string parameter value
     * @throws NotFoundException if the param is not found in the URL
     */
    public static function getUrlParam($name) {
        if (!array_key_exists($name, $_GET)) {
            throw new NotFoundException('URL parameter "' . $name . '" not found.');
        }
        return $_GET[$name];
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
        unset($params['page']);
        return '?'.http_build_query(array_merge(['page' => $page], $params));
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
 * User class Api
 */
final class User{

    private $id;
    private $name;
    private $email;
    private $password;

    function __construct(){

    }

    public function setParam( array $user){

        if(array_key_exists('id', $user)){
            $this->id = $user->id;
        }

        if(array_key_exists('name', $user)){
            $this->name = $user['name'];
        }

        if(array_key_exists('email', $user)){
            $this->email = $user['email'];
        }

        if(array_key_exists('password', $user)){
            $this->password = $user['password'];
        }
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

    public function setParam( $product){

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

    public function getId(){
        return $this->id;
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

/*
    interface Cart {

        function add( int $id){
            if(isset($_SESSION['cart'])){

                $_SESSION['cart'] = $_SESSION['cart']. ','. $id ; 
            }else{

                $_SESSION['cart'] = $id;
            }
        }

        function delete(int $id){

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

        function checkout(){

            if(isset($_SESSION['cart'])){

                $cart = explode(',', $_SESSION['cart']);

                return $cart;
            }

            return false;

        }
    }
*/
