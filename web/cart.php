<?php

    // user defined cart actions
    $action = [
        'add', 'delete'
    ];

    if(array_key_exists('cart', $_GET))
        $act = Helper::getUrlParam('cart');

    if(isset($act) && ! in_array( $act, $action)){
        throw  new NotFoundException("Cart activity not found");
    }
        // enable the cart variable at specific instance
        // rather than wen user enters application
    if(! array_key_exists('cartId', $_SESSION)){

        new Cart();
    }

    if( array_key_exists('id',$_GET) && @$act == 'add'){
        
        Cart::add();
    }

    if( array_key_exists('id',$_GET) && @$act == 'delete'){
 
        Cart::delete();
    }

    if( array_key_exists('cart', $_SESSION)){

       $products = Cart::view();
    }