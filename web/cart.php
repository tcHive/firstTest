<?php

    $cart = new CartDao();

    if(!array_key_exists('cart', $_SESSION) && array_key_exists('add',$_GET)){

        $_SESSION['cart'] = $cart->create();

        $cart->inser($_SESSION['cart']);
    }


    if(array_key_exists('id',$_GET)){
        $id = Helper::getUrlParam('id');

        $product = $cart->fetch($_SESSION['cart']);
        
        if(!trim($product->product === null)){
            $product = explode(',',$product->product);
        }
        $num = sizeof($product);
        
        $product[$num] = $id;
        $cart->add($product,$_SESSION['cart']);
    }
    /*else{

        $product = $cart->fetch($_SESSION['cart']);

    }*/

    if(array_key_exists('cart', $_SESSION)){
        $product = $cart->fetch($_SESSION['cart']);
        $product = explode(',',$product->product);
        var_dump($product);
        
        foreach( $product as $pdt){
            echo $pdt.'<br />';
            $products = new ProductDao();
            $products = $products->selectProperties((int)$pdt);
        }
    }