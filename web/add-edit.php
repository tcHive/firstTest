<?php
// product template setup
$id = Helper::urlArg();

if(isset($id[1]) && is_numeric($id[1]) && $id[1] > 0){

    $id = $id[1];
    $action = 'edit';

    $pdt = new Product;
    $product = new ProductDao();
    $pdt = $product->selectProperties('id', $id);
}else{
    $action = 'add';
}

$errors = [];
    
if( array_key_exists('add', $_POST)){

        $data = [
            'name' => isset($_POST['product']['name'])?$_POST['product']['name'] :'',
            'price' => isset($_POST['product']['price'])?$_POST['product']['price'] :'',
            'description' => isset($_POST['product']['description'])?$_POST['product']['description'] :'',
            'tags' => isset($_POST['product']['tags'])?$_POST['product']['tags'] :''
        ];

    $pdt = new Product();
    $pdt->setOwnerId(9);
    $errors = $pdt->addEdit($data);
    
    if( empty($errors)){
        $product = new ProductDao();
        $id = $product->setProperties($pdt);

        Helper::redirect('detail', [$id]);
    }
}
elseif( array_key_exists('edit', $_POST)){

    $data = [
        'name' => isset($_POST['product']['name'])?$_POST['product']['name'] :'',
        'price' => isset($_POST['product']['price'])?$_POST['product']['price'] :'',
        'description' => isset($_POST['product']['description'])?$_POST['product']['description'] :'',
        'tags' => isset($_POST['product']['tags'])?$_POST['product']['tags'] :''
    ];

    $product = new ProductDao();
    $pdt = $product->selectProperties('id', $id);
    $errors = $pdt->addEdit($data);

    if( empty($errors)){
        $product = new ProductDao();
        $product->updateProperties($pdt);

        Helper::redirect('detail', [$id]);
    }
}

