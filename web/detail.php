<?php

    if(array_key_exists('id', $_GET)){
        $id = Helper::getUrlParam('id');

        $product = new ProductDao();
        $product = $product->selectProperties($id);
    }
    else{
        throw new NotFoundException("Product identify not found ");
    }