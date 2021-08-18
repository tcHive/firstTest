<?php

    $id = Helper::getUrlParam('id');
    $product = new ProductDao();
    $product = $product->selectProperties($id);