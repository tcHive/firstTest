<?php

    // user template setup
    $view = [
        'register', 'login', 'forgot'
    ];

    if(array_key_exists('view',$_GET) && in_array($_GET['view'],$view)){
        $action = Helper::getUrlParam('view');
    }
    else{
        throw  new NotFoundException("View not found");
    }

    // user POST request 
    $errors = [];

    if(array_key_exists('register', $_POST)){
        $data = [
            'name' => isset($_POST['user']['name'])?$_POST['user']['name'] :'',
            'email' => isset($_POST['user']['email'])?$_POST['user']['email'] :'',
            'password' => isset($_POST['user']['password'])?$_POST['user']['password'] :'',
            'password2' => isset($_POST['user']['passwordRepeat'])?$_POST['user']['passwordRepeat'] :''
        ];
            $obj = new User();  
            $errors = $obj->register($data);

        if(empty($errors)){
           
           $user = new UserDao();
           $user->setProperties($obj);

           echo 'registration good';
        }
    }
    else if(array_key_exists('login', $_POST)){

        $data = [
            'email' => isset($_POST['user']['email'])?$_POST['user']['email'] :'',
            'password' => isset($_POST['user']['password'])?$_POST['user']['password'] :''
        ];

        $obj = new User();  
        $errors = $obj->login($data);

        if(empty($errors)){

            $user = new UserDao();
            $obj = $user->selectProperties('email', $obj);

            if($obj->getPassword() != $data['password']){
                $errors[] = new ValidatorError('Login Failed', 'Incorrect Username or Password');
            }
            else{
                echo 'welcome';
            }
        }
    }
    else if( array_key_exists('forgot', $_POST)){

        $data = [
            'name' => isset($_POST['user']['name'])?$_POST['user']['name'] :'',
            'email' => isset($_POST['user']['email'])?$_POST['user']['email'] :''
        ];

        $obj = new User();  
        $errors = $obj->forgot($data);
        if(empty($errors)){
            
            $user = new UserDao();
            $obj = $user->selectProperties('email', $obj);

            if($obj->getName() != $data['name']){
                $errors[] = new ValidatorError('error', 'Incorrect Name / Email');
            }
            else{
                echo 'welcome';
            }
        }
    }