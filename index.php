<?php
/**
 * Index page
 * 
 * @category Database
 * @package  FirstTest
 * Connector and data handler function 
 * @author   RamakhanyaD <techcodehive@gmail.com>
 * @license  openSource 
 * @version  PHP_version:<php_7.6>
 * @link     https://revtech.co.za
 */

    require "Db.php";
    require "User.php";

    $user = new User();

    $user->selectProperties('27');
?>