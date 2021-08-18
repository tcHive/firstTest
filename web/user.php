<?php


    $errors = [];

    if(array_key_exists('register', $_POST)){
        

        $data = [
            'name' => $_POST['user']['name'],
            'email' => $_POST['user']['email'],
            'password' => $_POST['user']['password'],
            'password2' => $_POST['user']['passwordRepeat']
        ];
            //var_dump($data);
        // $errors = UserValidator::validate($obj);
         
        if(empty($errors)){
            
            $obj = new User();
            $obj->setParam($data);

            $user = new UserDao();
            $user->setProperties($obj);
        }
    }
    else if(array_key_exists('login', $_POST)){

        $data = [
            'email' => $_POST['user']['email'],
            'password' => $_POST['user']['password']
        ];

        // $errors = UserValidator::validate($obj);
         
        if(empty($errors)){

            $user = (new UserDao())
                ->selectProperties();

            if($user->getPassword() === $data['password']){

            }
        }
    }
    else if( array_key_exists('forgot', $_POST)){

        $data = [
            'name' => $_POST['user']['name'],
            'email' => $_POST['user']['email'],
        ];

        //$errors = UserValidator::validate($obj);
         
        /*if(empty($errors)){
            
            $user = (new UserDao())
                ->selectProperties();

            
        }*/
    }

   
    var_dump($_POST);