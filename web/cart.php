<?php

    // user defined cart actions
    $action = [
        'add', 'delete'
    ];

        $act = Helper::urlArg();
  
    if(isset($act[2]) && !  in_array( $act[2], $action)){
        throw  new NotFoundException("Cart activity not found");
    }
        // enable the cart variable at specific instance
        // rather than wen user enters application
    if(! array_key_exists('cartId', $_SESSION)){
        new Cart();
    }
    if( isset($act[1]) && is_numeric($act[1]) && $act[1] > 0 ){
        if(in_array('add', $act))
            Cart::add($act[1]);
        elseif( in_array('delete', $act)) 
            Cart::delete($act[1]);
    }
   
    if( Cart::count() > 0)
        $products = Cart::view();
    