<?php

    $id = Helper::urlArg();

    if(is_numeric($id[1]) && $id[1] > 0){
       
        $product = new ProductDao();
        $product = $product->selectProperties('id', $id[1]);
    }
    else{
        throw new NotFoundException("Product identify not found ");
    }