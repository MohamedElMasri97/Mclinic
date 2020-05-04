<?php
    echo('something');
    if(!empty($_POST['action'])){
        if($_POST['action'] == 'checkemail'){
            //initialize variables
            $email = '';

            $error_array = array();

            //connecting to db
            $db = mysqli_connect('localhost','root','','health_care') or die('did not connect to data base');

            //registring email
            $email = mysqli_real_escape_string($db,$_POST['email']);

            //checking for already exist
            $user_check_query = 'SELECT * FROM  `users_registration` WHERE `email` = "'.$email.'" limit 1 ';
            $results = mysqli_query($db, $user_check_query);
            $user = mysqli_fetch_assoc($results);
    
            if($user){
                if($user['email'] == $email){array_push($error_array,'email already exists');}
            }

            // registering the user
            if(count($error_array) == 0){
                echo json_encode([true,'']);
            }else{
                echo json_encode([false,$error_array]);
            }
        }
        if($_POST['action'] == 'register_patient'){
            //initialize variables
            $name = '';
            $birth = '';
            $email = '';
            $password = '';
            $city = '';
            $phone = '';
            $nearest_point = '';
            $national_number = '';
            $gender = 0;
            $note = '';

            $error_array = array();

            // connect to db
            $db = mysqli_connect('localhost','root','','company') or die('did not connect to data base');

            //registring string
            $email = mysqli_real_escape_string($db,$_POST['email']);
            $password = mysqli_real_escape_string($db,$_POST['password']);
            $birth = mysqli_real_escape_string($db,$_POST['birth']);
            $city = mysqli_real_escape_string($db,$_POST['city']);
            $phone = mysqli_real_escape_string($db,$_POST['phone']);
            $name = mysqli_real_escape_string($db,$_POST['name']);
            $nearest_point = mysqli_real_escape_string($db,$_POST['nearest_point']);
            $national_number = mysqli_real_escape_string($db,$_POST['nationanl_number']);
            $gender = mysqli_real_escape_string($db,$_POST['gender']);
            $note = mysqli_real_escape_string($db,$_POST['note']);
           
            //checking for already exist
            $user_check_query = 'SELECT * FROM  `users_registration` WHERE `email` = "'.$email.'" limit 1 ';
            $results = mysqli_query($db, $user_check_query);
            $user = mysqli_fetch_assoc($results);
    
            if($user){
                if($user['email'] == $email){array_push($error_array,'email already exists');}
            }
            // registering the user
            
            if(count($error_array) == 0){
                $query = 'INSERT INTO `patient` (email, password, name, birth, gender, note, city, nearest_point, phone, national_number) values("'.$email.'", "'.$password.'", "'.$name.'", "'.$birth.'", "'.$gender.'", "'.$note.'", "'.$city.'", "'.$nearest_point.'", "'.$phone.'", "'.$national_number.'");';
                $results = mysqli_query($db, $query);
                if($results){
                    echo json_encode([true,'']);
                }else{
                    echo json_encode([false,'حدث خطأ أثناء الادخال']);
                }
            }else{
                echo json_encode([false,$error_array]);
            }
        }
    }