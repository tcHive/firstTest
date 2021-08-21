<?php

    // user defined cart actions
    $action = [
        'add', 'delete', 'view'
    ];

    $act = Helper::getUrlParam('cart');

    if(! (array_key_exists( 'cart', $_GET) 
            && in_array( $act, $action))){
        throw  new NotFoundException("Cart activity not found");
    }

    $cart = new CartDao();

    if(!array_key_exists('cartId', $_SESSION) 
        && array_key_exists('cart',$_GET)){

        $_SESSION['cartId'] = $cart->create();

        //$cart->inser($_SESSION['cart']);
    }

    if( array_key_exists('id',$_GET) 
        && $act == 'add'){

        $id = Helper::getUrlParam('id');
     
        $_SESSION['cart'][$id] = $id;
        
        echo 'trying to add';
        /*
            $product = $cart->fetch($_SESSION['cart']);
            
            if(!trim($product->product === null)){
                $product = explode(',',$product->product);
            }

            $num = sizeof($product);
            
            $product[$num] = $id;
            $cart->add($product,$_SESSION['cart']);
        */
    }

    if( array_key_exists('id',$_GET) 
        && $act == 'delete'){

        $id = Helper::getUrlParam('id');
     
        if( array_key_exists($id, $_SESSION['cart'])){
            unset($_SESSION['cart'][$id]);
        }
            
        //$_SESSION['cart'][] = $id;
    }

    if(array_key_exists('cart', $_SESSION)
        && $act == 'view'){
        //$product = $cart->fetch($_SESSION['cart']);
        //$product = explode(',',$product->product);
        //var_dump($_SESSION['cart']);
        
        $products[] = new Product;
        foreach( $_SESSION['cart'] as $pdt){
            $product = new ProductDao();
            $products[] = $product->selectProperties((int)$pdt);
        }
    }