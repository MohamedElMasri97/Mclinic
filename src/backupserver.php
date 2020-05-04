<?php

// function notify($type,$db,$to,$note,$category,$from = ""){
//     if($type == 1){ 
//         // $sSQL= 'SET CHARACTER SET utf8'; 
//         // mysqli_query($db,$sSQL);  
//         $query = 'INSERT into notifications(type,note, touser) values("'.$category.'", "'.$note.'",(select userid from users_registration where email = "'.$to.'" limit 1)) ;';
//         return json_encode([true, mysqli_query($db,$query),$query]);
//     } else if($type ==2){
//         $query = 'INSERT into notifications(type,note, touser) values("'.$category.'", "'.$note.'",'.$to.');';
//         return mysqli_query($db,$query);
//     } else if($type == 3){
//     }
// }
function checkemail($email, $db)
{
    //checking for already exist
    $user=  '';
    $error_array=  [];
    $user_check_query = 'SELECT * FROM  `users_registration` WHERE `email` = "' . $email . '" limit 1 ';
    $results = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($results);

    if ($user) {
        if ($user['email'] == $email) {
            array_push($error_array, 'email already exists');
        }
    }

    // echoing the results 
    if (count($error_array) == 0) {
        return true;
    } else {
        return false;
    }
}

if (!empty($_POST['action'])) {

    // useful data
    $mapgender = ['0' => 'male', '1' => 'female'];
    $mapcity = [
        '1' => 'tripoli',
        '2' => 'beghazi',
        '3' => 'mesorata',
        '4' => 'zawiah',
        '5' => 'tchad'
    ];



    // Store cipher method 
    $ciphering = "AES-128-CTR";
    $decryption_key = $encryption_key = 'mylovedkey';
    $encryption_iv = '1234567891011121';
    // Use OpenSSl encryption method 
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;
    if ($_POST['pass'] == 'TMC123') {

        // initiate connecton
        $db = mysqli_connect('localhost', 'root', 'P@ssw0rd18', 'health_care') or die('did not connect to data base');
        // $sSQL= 'SET CHARACTER SET utf8'; 
        // mysqli_query($db,$sSQL);  

        // authintication
        if ($_POST['action'] == 'hi') {
            echo (json_encode(['error' => 'heythere']));
        } else if ($_POST['action'] == 'checkemail') {
            //initialize variables
            $email = '';

            $error_array = array();

            //registring email
            $email = mysqli_real_escape_string($db, $_POST['email']);

            //checking for already exist
            $user_check_query = 'SELECT * FROM  `users_registration` WHERE `email` = "' . $email . '" limit 1 ';
            $results = mysqli_query($db, $user_check_query);
            $user = mysqli_fetch_assoc($results);

            if ($user) {
                if ($user['email'] == $email) {
                    array_push($error_array, 'email already exists');
                }
            }

            // echoing the results 
            if (count($error_array) == 0) {
                echo json_encode([true, '']);
            } else {
                echo json_encode([false, $error_array]);
            }
        } else if ($_POST['action'] == 'register_patient') {
            //initialize variables
            $email = '';
            $userpassword = '';
            $np = '';
            $note = '';
            $city = '';
            $phone = '';
            $username = '';
            $birth = '';
            $gender = '';
            $nationalnumber = '';

            $error_array = array();


            //registring string
            $email = mysqli_real_escape_string($db, $_POST['email']);
            if(checkemail($email,$db)){
                $userpassword = mysqli_real_escape_string($db, $_POST['password']);
                $np = mysqli_real_escape_string($db, $_POST['np']);
                $note = mysqli_real_escape_string($db, $_POST['note']);
                $city = mysqli_real_escape_string($db, $_POST['city']);
                $phone = mysqli_real_escape_string($db, $_POST['phone']);
                $username = mysqli_real_escape_string($db, $_POST['username']);
                $birth = mysqli_real_escape_string($db, $_POST['birth']);
                $gender = mysqli_real_escape_string($db, $_POST['gender']);
                $nationalnumber = mysqli_real_escape_string($db, $_POST['nationalnumber']);

                // registering the user
                if (count($error_array) == 0) {
                    $query =   'INSERT INTO `users_registration`(ustate,email, userpassword, usertype) values("0","' . $email . '", "' . $userpassword . '", 0) ';
                    $results1 = mysqli_query($db, $query);
                    $query =   'INSERT INTO `users_patient`(fuserid,  np,note, city, phone, username, birth, gender, nationalnumber) values((SELECT `userid` from `users_registration` where email = "' . $email . '" ), "' . $np . '", "' . $note . '",   "' . $city . '",  ' . $phone . ',  "' . $username . '", "' . $birth . '",  ' . $gender . ',  "' . $nationalnumber .  '");';
                    $results2 = mysqli_query($db, $query);
                    if ($results1 && $results2) {
                        echo (json_encode([true, '']));
                        //notify(1,$db,$email,'welcome '.$username.' and thank you for registering in our platform.',"1");
                    } else {
                        $query2 =  'DELETE FROM `users_registration` WHERE email = "' . $email . '"  ';
                        $results = mysqli_query($db, $query2);
                        echo json_encode([false, $query]);
                    }
                } else {
                    echo json_encode([false, '??? ??? ????? ???????']);
                }
            }else {
                echo json_encode([false, '??? ??? ????? ???????']);
            }
        } else if ($_POST['action'] == 'register_doctor') {
            //initialize variables
            $email = '';
            $userpassword = '';
            $np = '';
            $note = '';
            $city = '';
            $phone = '';
            $username = '';
            $birth = '';
            $gender = '';
            $nationalnumber = '';
            $speciality = '';

            $error_array = array();


            //registring string
            $email = mysqli_real_escape_string($db, $_POST['email']);
            if(checkemail($email,$db)){
                $userpassword = mysqli_real_escape_string($db, $_POST['password']);
                $np = mysqli_real_escape_string($db, $_POST['np']);
                $note = mysqli_real_escape_string($db, $_POST['note']);
                $city = mysqli_real_escape_string($db, $_POST['city']);
                $phone = mysqli_real_escape_string($db, $_POST['phone']);
                $username = mysqli_real_escape_string($db, $_POST['username']);
                $birth = mysqli_real_escape_string($db, $_POST['birth']);
                $gender = mysqli_real_escape_string($db, $_POST['gender']);
                $nationalnumber = mysqli_real_escape_string($db, $_POST['nationalnumber']);
                $speciality = mysqli_real_escape_string($db, $_POST['speciality']);

                // registering the user
                if (count($error_array) == 0) {
                    $query =   'INSERT INTO `users_registration`(ustate,email, userpassword, usertype) values("0","' . $email . '", "' . $userpassword . '", 1) ';
                    $results1 = mysqli_query($db, $query);
                    $query =   'INSERT INTO `users_doctor`(verified,fuserid,  np,note, city, phone, username, birth, gender, nationalnumber,speciality) values(1,(SELECT `userid` from `users_registration` where email = "' . $email . '" ), "' . $np . '", "' . $note . '",   "' . $city . '",  ' . $phone . ',  "' . $username . '", "' . $birth . '",  ' . $gender . ',  "' . $nationalnumber . '",  "' . $speciality . '");';
                    $results2 = mysqli_query($db, $query);
                    if ($results1 && $results2) {
                        echo (json_encode([true, '']));
                    } else {
                        $query2 =  'DELETE FROM `users_registration` WHERE email = "' . $email . '"  ';
                        $results = mysqli_query($db, $query2);
                        echo json_encode([false, $query]);
                        ////notify(1,$db,$email,'welcome '.$username.' and thank you for registering in our platform.',"1");
                    }
                } else {
                    echo json_encode([false, '??? ??? ????? ???????']);
                }
            }else {
                echo json_encode([false, '??? ??? ????? ???????']);
            }
        } else if ($_POST['action'] == 'register_pharmacy') {
            //initialize variables
            $email = '';
            $userpassword = '';
            $np = '';
            $city = '';
            $phone = '';
            $username = '';
            $title = '';
            $note = '';

            $error_array = array();


            //registring string
            $email = mysqli_real_escape_string($db, $_POST['email']);
            if(checkemail($email,$db)){
                $userpassword = mysqli_real_escape_string($db, $_POST['password']);
                $np = mysqli_real_escape_string($db, $_POST['np']);
                $city = mysqli_real_escape_string($db, $_POST['city']);
                $phone = mysqli_real_escape_string($db, $_POST['phone']);
                $username = mysqli_real_escape_string($db, $_POST['username']);
                $title = mysqli_real_escape_string($db, $_POST['title']);
                $note = mysqli_real_escape_string($db, $_POST['note']);

                // registering the user

                if (count($error_array) == 0) {
                    $query =   'INSERT INTO `users_registration`(ustate,email, userpassword, usertype) values("0","' . $email . '", "' . $userpassword . '", 2) ';
                    $results1 = mysqli_query($db, $query);
                    $query =   'INSERT INTO `users_pharmacy`(fuserid,  verified, city,username, title, phone, np, note) values((SELECT `userid` from `users_registration` where email = "' . $email . '" ),  1,   "' . $city . '", "' . $username . '",   "' . $title . '",  ' . $phone . ',  "' . $np . '", "' . $note . '");';
                    $results2 = mysqli_query($db, $query);
                    if ($results1 && $results2) {
                        echo (json_encode([true, $_POST]));
                    } else {
                        $query =  'DELETE FROM `users_registration` WHERE email = "' . $email . '"  ';
                        $results = mysqli_query($db, $query);
                        echo json_encode([false, $query]);
                        //notify(1,$db,$email,'welcome '.$username.' and thank you for registering in our platform.',"1");
                    }
                } else {
                    echo json_encode([false, '??? ??? ????? ???????']);
                }
            }else {
                echo json_encode([false, '??? ??? ????? ???????']);
            }
        } else if ($_POST['action'] == 'register_lab') {
            //initialize variables
            $email = '';
            $userpassword = '';
            $np = '';
            $city = '';
            $phone = '';
            $username = '';
            $title = '';
            $note = '';

            $error_array = array();


            //registring string
            $email = mysqli_real_escape_string($db, $_POST['email']);
            if(checkemail($email,$db)){
                $userpassword = mysqli_real_escape_string($db, $_POST['password']);
                $np = mysqli_real_escape_string($db, $_POST['np']);
                $city = mysqli_real_escape_string($db, $_POST['city']);
                $phone = mysqli_real_escape_string($db, $_POST['phone']);
                $username = mysqli_real_escape_string($db, $_POST['username']);
                $title = mysqli_real_escape_string($db, $_POST['title']);
                $note = mysqli_real_escape_string($db, $_POST['note']);

                // registering the user

                if (count($error_array) == 0) {
                    $query =   'INSERT INTO `users_registration`(ustate,email, userpassword, usertype) values("0","' . $email . '", "' . $userpassword . '", 3) ';
                    $results1 = mysqli_query($db, $query);
                    $query =   'INSERT INTO `users_lab`(fuserid,  verified, city,username, title, phone, np, note) values((SELECT `userid` from `users_registration` where email = "' . $email . '" ),  1,   "' . $city . '", "' . $username . '",   "' . $title . '",  ' . $phone . ',  "' . $np . '", "' . $note . '");';
                    $results2 = mysqli_query($db, $query);
                    if ($results1 && $results2) {
                        echo (json_encode([true, '']));
                    } else {
                        $query =  'DELETE FROM `users_registration` WHERE email = "' . $email . '"  ';
                        $results = mysqli_query($db, $query);
                        echo json_encode([false, $query]);
                        //notify(1,$db,$email,'welcome '.$username.' and thank you for registering in our platform.',"1");
                    }
                } else {
                    echo json_encode([false, '??? ??? ????? ???????']);
                }
            } else {
                echo json_encode([false, '??? ??? ????? ???????']);
            }
        } else if ($_POST['action'] == 'login') {
            //initialize variables
            $password = '';
            $email = '';

            $error_array = array();

            //registring variables
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $password = mysqli_real_escape_string($db, $_POST['password']);

            //checking for already exist
            $user_check_query = 'SELECT * FROM  `users_registration` WHERE `email` = "' . $email . '" AND `userpassword` = "' . $password . '" limit 1 ';
            $results = mysqli_query($db, $user_check_query);
            $user = mysqli_fetch_assoc($results);

            // echoing the results 
            if ($user) {

                // update state to one
                $user_check_query = 'UPDATE `users_registration` SET `ustate`="1" WHERE `email` = "' . $email . '" limit 1 ';
                $results = mysqli_query($db, $user_check_query);

                // Encryption of string process starts 
                $encryption = openssl_encrypt(
                    $user['userpassword'],
                    $ciphering,
                    $encryption_key,
                    $options,
                    $encryption_iv
                );

                //fitching the rest of the data
                if ($user['usertype'] == 2) {
                    $user_check_query = 'SELECT * FROM  `health_care`.`users_pharmacy` WHERE `fuserid` = ' . $user['userid'] . ';';
                    $results1 = mysqli_query($db, $user_check_query);
                    $userdata = mysqli_fetch_assoc($results1);
                    if ($userdata) {
                        echo json_encode([true, $encryption, $userdata['username'], $user['usertype']]);
                    } else {
                        echo json_encode([false, ['error 4', $user_check_query, $user, $results1]]);
                    }
                } else if ($user['usertype'] == 0) {
                    $user_check_query = 'SELECT * FROM  `users_patient` WHERE `fuserid` = ' . $user['userid'] . ' limit 1 ';
                    $results = mysqli_query($db, $user_check_query);
                    $userdata = mysqli_fetch_assoc($results);
                    if ($userdata) {
                        echo json_encode([true, $encryption, $userdata['username'], $user['usertype']]);
                    } else {
                        echo json_encode([false, ['error 1', $user_check_query, $user]]);
                    }
                } else if ($user['usertype'] == 1) {
                    $user_check_query = 'SELECT * FROM  `users_doctor` WHERE `fuserid` = ' . $user['userid'] . ' limit 1 ';
                    $results = mysqli_query($db, $user_check_query);
                    $userdata = mysqli_fetch_assoc($results);
                    if ($userdata) {
                        echo json_encode([true, $encryption, $userdata['username'], $user['usertype']]);
                    } else {
                        echo json_encode([false, ['error 1', $user_check_query, $user]]);
                    }
                } else if ($user['usertype'] == 3) {
                    $user_check_query = 'SELECT * FROM  `users_lab` WHERE `fuserid` = ' . $user['userid'] . ' limit 1 ';
                    $results = mysqli_query($db, $user_check_query);
                    $userdata = mysqli_fetch_assoc($results);
                    if ($userdata) {
                        echo json_encode([true, $encryption, $userdata['username'], $user['usertype']]);
                    } else {
                        echo json_encode([false, ['error 1', $user_check_query, $user]]);
                    }
                }
            } else {
                echo json_encode([false, ['error 2', $user_check_query, $user]]);
            }
        } else if ($_POST['action'] == 'checkme') {
            //initialize variables
            $encpassword = '';
            $email = '';

            $error_array = array();


            //registring variables
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $encpassword = mysqli_real_escape_string($db, $_POST['password']);

            // Descrypt the string 
            $password = openssl_decrypt(
                $encpassword,
                $ciphering,
                $decryption_key,
                $options,
                $encryption_iv
            );

            //checking for already exist
            $user_check_query = 'SELECT * FROM  `users_registration` WHERE `email` = "' . $email . '" AND `userpassword` = "' . $password . '" limit 1 ';
            $results = mysqli_query($db, $user_check_query);
            $user = mysqli_fetch_assoc($results);

            if (!$user) {
                array_push($error_array, 'wrong authintications');
            }

            // echoing the results 
            if (count($error_array) == 0) {

                echo json_encode([true, '']);
            } else {
                echo json_encode([false, [$error_array, $user, $user_check_query, $results]]);
            }
        } else if ($_POST['action'] == 'logout') {
            //initialize variables
            $email = '';

            $error_array = array();

            //connecting to db
            $db = mysqli_connect('localhost', 'root', '', 'health_care') or die('did not connect to data base');

            //registring variables
            $email = mysqli_real_escape_string($db, $_POST['email']);


            //checking for already exist
            $user_check_query = 'UPDATE `users_registration` SET `ustate`="0" WHERE `email` = "' . $email . '" limit 1 ';
            $results = mysqli_query($db, $user_check_query);


            if (!$results) {
                array_push($error_array, 'something went wrong');
            }

            // echoing the results 
            if (count($error_array) == 0) {

                echo json_encode([true, '']);
            } else {
                echo json_encode([false, [$error_array, $user_check_query, $results]]);
            }
        }

        // making request
        else if ($_POST['action'] == 'checkreq') { // souitable for multiple reqs

            // initiate variables
            $email = '';
            $req_type = '';
            $limit = '';
            $error_array = array();


            //registring variables
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $req_type = mysqli_real_escape_string($db, $_POST['req_type']);
            $table = '';
            if ($req_type == '1') {
                $table = 'patient_req_doctor';
            }
            if ($req_type == '2') {
                $table = 'patient_req_pharmacy';
            }
            if ($req_type == '3') {
                $table = 'patient_req_lab';
            }

            // query to check
            $user_check_query = 'SELECT * FROM  ' . $table . ' where requestby = "0" and fuserid = (select userid from `users_registration` where `email` = "' . $email . '" );';
            $results = mysqli_query($db, $user_check_query);
            if (mysqli_num_rows($results) >= 2) {
                echo json_encode([false, 'he has passed the limit', $results]);
            } else {
                echo json_encode([true, $user_check_query]);
            }
        } else if ($_POST['action'] == 'rig_doctor_req') { // souitable for multiple reqs
            //variables
            $city = '';
            $email = '';
            $gender = '';
            $note = '';
            $np = '';

            //registing vars
            $city = mysqli_real_escape_string($db, $_POST['city']);
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $gender = mysqli_real_escape_string($db, $_POST['gender']);
            $note = mysqli_real_escape_string($db, $_POST['note']);
            $np = mysqli_real_escape_string($db, $_POST['np']);

            $query = 'INSERT INTO `patient_req_doctor`(fuserid,city,gender,note,np) values((select userid from `users_registration` where email = "' . $email . '" limit 1), "' . $city . '","' . $gender . '","' . $note . '","' . $np . '");';
            $results = mysqli_query($db, $query);
            if ($results) {
                echo json_encode([true, '']);
                //notify(1,$db,$email,'your request has been registered successfully.',"1");
            } else {
                echo json_encode([false, $results, $query]);
            }
        } else if ($_POST['action'] == 'rig_pharmacy_req') { // souitable for multiple reqs
            //variables
            $city = '';
            $email = '';
            $why = '';
            $barcode = '';
            $note = '';
            $np = '';

            //registing vars
            $city = mysqli_real_escape_string($db, $_POST['city']);
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $why = mysqli_real_escape_string($db, $_POST['why']);
            $barcode = mysqli_real_escape_string($db, $_POST['barcode']);
            $note = mysqli_real_escape_string($db, $_POST['note']);
            $np = mysqli_real_escape_string($db, $_POST['np']);

            $query = 'INSERT INTO `patient_req_pharmacy`(fuserid,city,why,note,np,barcode) values((select userid from `users_registration` where email = "' . $email . '" limit 1), "' . $city . '","' . $why . '","' . $note . '","' . $np . '","' . $barcode . '");';
            $results = mysqli_query($db, $query);
            if ($results) {
                //notify(1,$db,$email,'your request has been registered successfully.',"1");
                echo json_encode([true, '']);
            } else {
                echo json_encode([false, $results, $query]);
            }
        } else if ($_POST['action'] == 'rig_lab_req') { // souitable for multiple reqs
            //variables
            $city = '';
            $email = '';
            $why = '';
            $barcode = '';
            $note = '';
            $np = '';

            //registing vars
            $city = mysqli_real_escape_string($db, $_POST['city']);
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $why = mysqli_real_escape_string($db, $_POST['why']);
            $note = mysqli_real_escape_string($db, $_POST['note']);
            $np = mysqli_real_escape_string($db, $_POST['np']);

            $query = 'INSERT INTO `patient_req_lab`(fuserid,city,why,note,np) values((select userid from `users_registration` where email = "' . $email . '" limit 1), "' . $city . '","' . $why . '","' . $note . '","' . $np . '");';
            $results = mysqli_query($db, $query);
            if ($results) {
                //notify(1,$db,$email,'your request has been registered successfully.',"1");
                echo json_encode([true, '']);
            } else {
                echo json_encode([false, $results, $query]);
            }
        } else if ($_POST['action']  == 'fetch_patients_fordoctor') { // souitable for multiple reqs
            $city = '';
            $limit = '';
            if (!empty($_POST['city'])) {
                $city = mysqli_real_escape_string($db, $_POST['city']);
            } else {
                $city = 'all';
            }
            $limit = mysqli_real_escape_string($db, $_POST['limit']);
            if ($city != 'all') {
                $query = 'SELECT patient_req_doctor.req_id as req,users_patient.phone as phone, patient_req_doctor.fuserid as id,patient_req_doctor.ts as ts,patient_req_doctor.note as note,patient_req_doctor.np as np,patient_req_doctor.city as city,users_patient.username as username,users_patient.birth as birth,users_patient.gender as gender from `patient_req_doctor` INNER JOIN `users_patient` ON patient_req_doctor.fuserid = users_patient.fuserid  where patient_req_doctor.req_state = "0" and patient_req_doctor.city = "' . $city . '" limit ' . $limit . ';';
            } else {
                $query = 'SELECT patient_req_doctor.req_id as req,users_patient.phone as phone, patient_req_doctor.fuserid as id,patient_req_doctor.ts as ts,patient_req_doctor.note as note,patient_req_doctor.np as np,patient_req_doctor.city as city,users_patient.username as username,users_patient.birth as birth,users_patient.gender as gender from `patient_req_doctor` INNER JOIN `users_patient` ON patient_req_doctor.fuserid = users_patient.fuserid where patient_req_doctor.req_state = "0" limit ' . $limit . ';';
            }


            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $each_row['name'] = $each_row['username'];
                    $all_rows[] = $each_row;
                }
                echo json_encode([true, $all_rows]);
            } else {
                echo json_encode([false, 'something went wrong no results', $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'rig_doctor_offer') { // souitable for multiple reqs
            $patientid = mysqli_real_escape_string($db, $_POST['patientid']);
            $reqid = mysqli_real_escape_string($db, $_POST['reqid']);
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $note = mysqli_real_escape_string($db, $_POST['note']);
            $time = mysqli_real_escape_string($db, $_POST['time']);
            $pricing = mysqli_real_escape_string($db, $_POST['pricing']);

            $check = 'SELECT * FROM `doctor_offer` WHERE state = "0" and req_id = ' . $reqid . '  and doctorid = (select userid from users_registration where email = "' . $email . '" limit 1) limit 1';
            $results = mysqli_query($db, $check);
            if (mysqli_num_rows($results) > 0) {
                mysqli_query($db, 'UPDATE `doctor_offer` set `state` = "2"  where req_id = ' . $reqid . ' and doctorid = (select userid from users_registration where email = "' . $email . '" limit 1);'); // state =2 means updated
            }

            $query = 'INSERT INTO `doctor_offer`(req_id,doctorid,patientid,offernote,appointment,pricing) values(' . $reqid . ',(select userid from users_registration where email = "' . $email . '" limit 1),' . $patientid . ',"' . $note . '","' . $time . '","' . $pricing . '");';
            $results = mysqli_query($db, $query);
            if ($results) {
                //notify(1,$db,$email,'your offer has been registered successfully.',"1");
                // $query = 'SELECT username from users_doctor where fuserid = (select userid from users_registration where email  = "'.$email.'") limit 1;';
                // $resutls  = mysqli_query($db,$query);
                // $username = mysqli_fetch_assoc($resutls);
                //notify(2,$db,$patientid,'???? ??? ??? ???? ?? ??? ?????? '.$username['username'].'',"1");
                echo json_encode([true, $query]);
            } else {
                echo json_encode([false, $_POST, $results, $query]);
            }
        } else if ($_POST['action']  == 'fetch_patients_forlab') { // souitable for multiple reqs
            $city = '';
            $limit = '';
            if (!empty($_POST['city'])) {
                $city = mysqli_real_escape_string($db, $_POST['city']);
            } else {
                $city = 'all';
            }
            $limit = mysqli_real_escape_string($db, $_POST['limit']);
            if ($city != 'all') {
                $query = 'SELECT patient_req_lab.req_id as req,patient_req_lab.why as why,users_patient.phone as phone, patient_req_lab.fuserid as id,patient_req_lab.ts as ts,patient_req_lab.note as note,patient_req_lab.np as np,patient_req_lab.city as city,users_patient.username as username,users_patient.birth as birth,users_patient.gender as gender from `patient_req_lab` INNER JOIN `users_patient` ON patient_req_lab.fuserid = users_patient.fuserid  where patient_req_lab.req_state = "0" and patient_req_lab.city = "' . $city . '" limit ' . $limit . ';';
            } else {
                $query = 'SELECT patient_req_lab.req_id as req,patient_req_lab.why as why,users_patient.phone as phone, patient_req_lab.fuserid as id,patient_req_lab.ts as ts,patient_req_lab.note as note,patient_req_lab.np as np,patient_req_lab.city as city,users_patient.username as username,users_patient.birth as birth,users_patient.gender as gender from `patient_req_lab` INNER JOIN `users_patient` ON patient_req_lab.fuserid = users_patient.fuserid  where patient_req_lab.req_state = "0" limit ' . $limit . ';';
            }


            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $each_row['name'] = $each_row['username'];
                    $all_rows[] = $each_row;
                }
                echo json_encode([true, $all_rows]);
            } else {
                echo json_encode([false, 'something went wrong no results', $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'rig_lab_offer') { // souitable for multiple reqs
            $patientid = mysqli_real_escape_string($db, $_POST['patientid']);
            $reqid = mysqli_real_escape_string($db, $_POST['reqid']);
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $note = mysqli_real_escape_string($db, $_POST['note']);
            $time = mysqli_real_escape_string($db, $_POST['time']);
            $pricing = mysqli_real_escape_string($db, $_POST['pricing']);

            $check = 'SELECT * FROM `lab_offer` WHERE state = "0" and req_id = ' . $reqid . ' and labid = (select userid from users_registration where email = "' . $email . '" limit 1) limit 1';
            $results = mysqli_query($db, $check);
            if (mysqli_num_rows($results) > 0) {
                mysqli_query($db, 'UPDATE `lab_offer` set `state` = "2"  where req_id = ' . $reqid . ' and labid = (select userid from users_registration where email = "' . $email . '" limit 1);'); // state =2 means updated
            }


            $query = 'INSERT INTO `lab_offer`(req_id,labid,patientid,offernote,appointment,pricing) values(' . $reqid . ',(select userid from users_registration where email = "' . $email . '" limit 1),' . $patientid . ',"' . $note . '","' . $time . '","' . $pricing . '");';
            $results = mysqli_query($db, $query);
            if ($results) {
                //notify(1,$db,$email,'your offer has been registered successfully.',"1");
                // $query = 'SELECT title from users_lab where fuserid = (select userid from users_registration where email  = "'.$email.'") limit 1;';
                // $resutls  = mysqli_query($db,$query);
                // $username = mysqli_fetch_assoc($results);
                //notify(2,$db,$patientid,'???? ??? ??? ???? ?? ???  '.$username['username'].'',"1");
                echo json_encode([true, '']);
            } else {
                echo json_encode([false, $_POST, $results, $query]);
            }
        } else if ($_POST['action']  == 'fetch_patients_forpharmacy') { // souitable for multiple reqs
            $city = '';
            $limit = '';
            if (!empty($_POST['city'])) {
                $city = mysqli_real_escape_string($db, $_POST['city']);
            } else {
                $city = 'all';
            }
            $limit = mysqli_real_escape_string($db, $_POST['limit']);
            if ($city != 'all') {
                $query = 'SELECT patient_req_pharmacy.barcode as barcode,patient_req_pharmacy.req_id as req,patient_req_pharmacy.why as why,users_patient.phone as phone, patient_req_pharmacy.fuserid as id,patient_req_pharmacy.ts as ts,patient_req_pharmacy.note as note,patient_req_pharmacy.np as np,patient_req_pharmacy.city as city,users_patient.username as username,users_patient.birth as birth,users_patient.gender as gender from `patient_req_pharmacy` INNER JOIN `users_patient` ON patient_req_pharmacy.fuserid = users_patient.fuserid  where patient_req_pharmacy.req_state = "0" and patient_req_pharmacy.city = "' . $city . '" limit ' . $limit . ';';
            } else {
                $query = 'SELECT patient_req_pharmacy.barcode as barcode,patient_req_pharmacy.req_id as req,patient_req_pharmacy.why as why,users_patient.phone as phone, patient_req_pharmacy.fuserid as id,patient_req_pharmacy.ts as ts,patient_req_pharmacy.note as note,patient_req_pharmacy.np as np,patient_req_pharmacy.city as city,users_patient.username as username,users_patient.birth as birth,users_patient.gender as gender from `patient_req_pharmacy` INNER JOIN `users_patient` ON patient_req_pharmacy.fuserid = users_patient.fuserid  where patient_req_pharmacy.req_state = "0" limit ' . $limit . ';';
            }


            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $each_row['name'] = $each_row['username'];
                    $all_rows[] = $each_row;
                }
                echo json_encode([true, $all_rows]);
            } else {
                echo json_encode([false, 'something went wrong no results', $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'rig_pharmacy_offer') { // souitable for multiple reqs
            $patientid = mysqli_real_escape_string($db, $_POST['patientid']);
            $reqid = mysqli_real_escape_string($db, $_POST['reqid']);
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $note = mysqli_real_escape_string($db, $_POST['note']);
            $time = mysqli_real_escape_string($db, $_POST['time']);
            $pricing = mysqli_real_escape_string($db, $_POST['pricing']);

            $check = 'SELECT * FROM `pharmacy_offer` WHERE state = "0" and req_id = ' . $reqid . '  and pharmacyid = (select userid from users_registration where email = "' . $email . '" limit 1) limit 1';
            $results = mysqli_query($db, $check);
            if (mysqli_num_rows($results) > 0) {
                mysqli_query($db, 'UPDATE `pharmacy_offer` set `state` = "2"  where req_id = ' . $reqid . ' and pharmacyid = (select userid from users_registration where email = "' . $email . '" limit 1);'); // state =2 means updated
            }

            $query = 'INSERT INTO `pharmacy_offer`(req_id,pharmacyid,patientid,offernote,appointment,pricing) values(' . $reqid . ',(select userid from users_registration where email = "' . $email . '" limit 1),' . $patientid . ',"' . $note . '","' . $time . '","' . $pricing . '");';
            $results = mysqli_query($db, $query);
            if ($results) {
                //notify(1,$db,$email,'your offer has been registered successfully.',"1");
                // $query = 'SELECT title from users_pharmacy where fuserid = (select userid from users_registration where email  = "'.$email.'") limit 1;';
                // $resutls  = mysqli_query($db,$query);
                // $username = mysqli_fetch_assoc($results);
                //notify(2,$db,$patientid,'???? ??? ??? ???? ?? ???  '.$username['username'].'',"1");
                echo json_encode([true, '']);
            } else {
                echo json_encode([false, $_POST, $results, $query]);
            }
        } else if ($_POST['action']  == 'fetch_patient_reqs_for_pharmacy_mobile') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);

            // $query = 'SELECT * FROM patient_req_pharmacy where req_state = "0" and fuserid = (select userid from `users_registration` where `email` = "' . $email . '" ) ;';
            $query = 'SELECT  req_id,fuserid,requestby, req_state, note, (SELECT cities.name  FROM cities WHERE cities.id = patient_req_pharmacy.city ) as city, np, geographiclocation , ts from patient_req_pharmacy where req_state = "0" and fuserid = (select userid from `users_registration` where `email` = "' . $email . '" ) ;';

            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $all_rows[] = $each_row;
                }
                echo json_encode([true, $all_rows, $query, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_patient_reqs_for_doctor_mobile') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);

            // $query = 'SELECT * FROM patient_req_doctor where req_state = "0" and fuserid = (select userid from `users_registration` where `email` = "' . $email . '" ) ;';
            $query = 'SELECT  req_id, fuserid, requestby, req_state, note, (SELECT cities.name  FROM cities WHERE cities.id = patient_req_doctor.city ) as city,   gender, np,  geographiclocation , ts FROM patient_req_doctor  where req_state = "0" and fuserid = (select userid from `users_registration` where `email` = "' . $email . '" ) ;';

            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $all_rows[] = $each_row;
                }
                echo json_encode([true, $all_rows, $query, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_patient_reqs_for_lab_mobile') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);

            // $query = 'SELECT * FROM patient_req_lab where req_state = "0" and fuserid = (select userid from `users_registration` where `email` = "' . $email . '" ) ;';
            $query = 'SELECT   req_id, fuserid, requestby, req_state, note, (SELECT cities.name  FROM cities WHERE cities.id = patient_req_lab.city ) as city, np, geographiclocation , ts from patient_req_lab where req_state = "0" and fuserid = (select userid from `users_registration` where `email` = "' . $email . '" ) ;';


            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $all_rows[] = $each_row;
                }
                echo json_encode([true, $all_rows, $query, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_patient_offers_from_pharmacy_mobile') { // souitable for multiple reqs
            $req_id = mysqli_real_escape_string($db, $_POST['req_id']);
            $query = 'SELECT  req_id, fuserid, requestby, req_state, note, (SELECT cities.name  FROM cities WHERE cities.id = patient_req_pharmacy.city ) as city, np, geographiclocation , ts  FROM patient_req_pharmacy where req_state = "0" and  req_id = ' . $req_id . ';';
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results)) {

                // fetching request
                $req = mysqli_fetch_assoc($results);

                //$query = 'SELECT users_lab.city as city, users_lab.fuserid as id, users_lab.username as name, users_lab.np as np, users_lab.phone as phone,  lab_offer.offernote as note, lab_offer.pricing as price, lab_offer.ts as ts, lab_offer.offerid as oid, lab_offer.appointment as time from lab_offer inner join users_lab on lab_offer.labid = users_lab.fuserid where lab_offer.state = "0" and lab_offer.req_id = ' . $patient['req_id'] . ';';

                // $query = 'SELECT users_doctor.city as city, users_doctor.fuserid as id, users_doctor.username as name, users_doctor.np as np,users_doctor.phone as phone, users_doctor.gender as gender, doctor_offer.offernote as note, doctor_offer.pricing as price, doctor_offer.ts as ts, doctor_offer.offerid as oid, doctor_offer.appointment as time from doctor_offer inner join users_doctor on doctor_offer.doctorid = users_doctor.fuserid where doctor_offer.state = "0" and doctor_offer.req_id = ' . $req_id . ';';

                $query = 'SELECT (SELECT cities.name  FROM cities WHERE cities.id = users_pharmacy.city ) as city, users_pharmacy.fuserid as id, users_pharmacy.title as title, users_pharmacy.np as np, users_pharmacy.phone as phone, pharmacy_offer.offernote as note, pharmacy_offer.pricing as price, pharmacy_offer.ts as ts, pharmacy_offer.offerid as oid, pharmacy_offer.appointment as time from pharmacy_offer inner join users_pharmacy on pharmacy_offer.pharmacyid = users_pharmacy.fuserid where pharmacy_offer.state = "0" and pharmacy_offer.req_id = ' . $req_id . ';';
                // $query = 'SELECT users_pharmacy.city as city, users_pharmacy.fuserid as id, users_pharmacy.title as title, users_pharmacy.np as np, users_pharmacy.phone as phone, pharmacy_offer.offernote as note, pharmacy_offer.pricing as price, pharmacy_offer.ts as ts, pharmacy_offer.offerid as oid, pharmacy_offer.appointment as time from pharmacy_offer inner join users_pharmacy on pharmacy_offer.pharmacyid = users_pharmacy.fuserid where pharmacy_offer.state = "0" and pharmacy_offer.req_id = ' . $req_id . ';';


                $results = mysqli_query($db, $query);
                if (mysqli_num_rows($results) > 0) {
                    while ($each_row = $results->fetch_assoc()) {
                        $all_rows[] = $each_row;
                    }
                    echo json_encode(['1', $req, $all_rows, $query, $_POST]);
                } else {
                    echo json_encode(['2', $req, $results, $query, $_POST]);
                }
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_patient_offers_from_doctor_mobile') { // souitable for multiple reqs
            $req_id = mysqli_real_escape_string($db, $_POST['req_id']);
            $query = 'SELECT  req_id, fuserid, requestby, req_state, note, (SELECT cities.name  FROM cities WHERE cities.id = patient_req_doctor.city ) as city, gender , np, geographiclocation , ts from patient_req_doctor where req_state = "0" and  req_id = ' . $req_id . ';';
            // $query = 'SELECT  * from patient_req_doctor where req_state = "0" and  req_id = '.$req_id.';';
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results)) {

                // fetching request
                $req = mysqli_fetch_assoc($results);

                //$query = 'SELECT users_pharmacy.city as city, users_pharmacy.fuserid as id, users_pharmacy.username as name, users_pharmacy.np as np, users_pharmacy.phone as phone, pharmacy_offer.offernote as note, pharmacy_offer.pricing as price, pharmacy_offer.ts as ts, pharmacy_offer.offerid as oid, pharmacy_offer.appointment as time from pharmacy_offer inner join users_pharmacy on pharmacy_offer.pharmacyid = users_pharmacy.fuserid where pharmacy_offer.state = "0" and pharmacy_offer.req_id = ' . $req_id . ';';

                //$query = 'SELECT users_lab.city as city, users_lab.fuserid as id, users_lab.username as name, users_lab.np as np, users_lab.phone as phone,  lab_offer.offernote as note, lab_offer.pricing as price, lab_offer.ts as ts, lab_offer.offerid as oid, lab_offer.appointment as time from lab_offer inner join users_lab on lab_offer.labid = users_lab.fuserid where lab_offer.state = "0" and lab_offer.req_id = ' . $patient['req_id'] . ';';

                $query = 'SELECT (SELECT cities.name  FROM cities WHERE cities.id = users_doctor.city ) as city, users_doctor.fuserid as id, users_doctor.username as name, users_doctor.np as np,users_doctor.phone as phone, users_doctor.gender as gender, doctor_offer.offernote as note, doctor_offer.pricing as price, doctor_offer.ts as ts, doctor_offer.offerid as oid, doctor_offer.appointment as time from doctor_offer inner join users_doctor on doctor_offer.doctorid = users_doctor.fuserid where doctor_offer.state = "0" and doctor_offer.req_id = ' . $req_id . ';';
                // $query = 'SELECT users_doctor.city as city, users_doctor.fuserid as id, users_doctor.username as name, users_doctor.np as np,users_doctor.phone as phone, users_doctor.gender as gender, doctor_offer.offernote as note, doctor_offer.pricing as price, doctor_offer.ts as ts, doctor_offer.offerid as oid, doctor_offer.appointment as time from doctor_offer inner join users_doctor on doctor_offer.doctorid = users_doctor.fuserid where doctor_offer.state = "0" and doctor_offer.req_id = ' . $req_id . ';';


                $results = mysqli_query($db, $query);
                if (mysqli_num_rows($results) > 0) {
                    while ($each_row = $results->fetch_assoc()) {
                        $all_rows[] = $each_row;
                    }
                    echo json_encode(['1', $req, $all_rows, $query, $_POST]);
                } else {
                    echo json_encode(['2', $req, $results, $query, $_POST]);
                }
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_patient_offers_from_lab_mobile') { // souitable for multiple reqs
            $req_id = mysqli_real_escape_string($db, $_POST['req_id']);
            $query = 'SELECT  req_id, fuserid, requestby, req_state, note, (SELECT cities.name  FROM cities WHERE cities.id = patient_req_lab.city ) as city, np, geographiclocation , ts  FROM patient_req_lab where req_state = "0" and  req_id = ' . $req_id . ';';
            // $query = 'SELECT * FROM patient_req_lab where req_state = "0" and  req_id = '.$req_id.';';
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results)) {

                // fetching request
                $req = mysqli_fetch_assoc($results);

                //$query = 'SELECT users_pharmacy.city as city, users_pharmacy.fuserid as id, users_pharmacy.username as name, users_pharmacy.np as np, users_pharmacy.phone as phone, pharmacy_offer.offernote as note, pharmacy_offer.pricing as price, pharmacy_offer.ts as ts, pharmacy_offer.offerid as oid, pharmacy_offer.appointment as time from pharmacy_offer inner join users_pharmacy on pharmacy_offer.pharmacyid = users_pharmacy.fuserid where pharmacy_offer.state = "0" and pharmacy_offer.req_id = ' . $req_id . ';';

                $query = 'SELECT (SELECT cities.name  FROM cities WHERE cities.id = users_lab.city ) as city, users_lab.fuserid as id, users_lab.title as title, users_lab.np as np, users_lab.phone as phone,  lab_offer.offernote as note, lab_offer.pricing as price, lab_offer.ts as ts, lab_offer.offerid as oid, lab_offer.appointment as time from lab_offer inner join users_lab on lab_offer.labid = users_lab.fuserid where lab_offer.state = "0" and lab_offer.req_id = ' . $req_id . ';';
                // $query = 'SELECT users_lab.city as city, users_lab.fuserid as id, users_lab.title as title, users_lab.np as np, users_lab.phone as phone,  lab_offer.offernote as note, lab_offer.pricing as price, lab_offer.ts as ts, lab_offer.offerid as oid, lab_offer.appointment as time from lab_offer inner join users_lab on lab_offer.labid = users_lab.fuserid where lab_offer.state = "0" and lab_offer.req_id = ' . $req_id . ';';

                // $query = 'SELECT users_doctor.city as city, users_doctor.fuserid as id, users_doctor.username as name, users_doctor.np as np,users_doctor.phone as phone, users_doctor.gender as gender, doctor_offer.offernote as note, doctor_offer.pricing as price, doctor_offer.ts as ts, doctor_offer.offerid as oid, doctor_offer.appointment as time from doctor_offer inner join users_doctor on doctor_offer.doctorid = users_doctor.fuserid where doctor_offer.state = "0" and doctor_offer.req_id = ' . $req_id . ';';


                $results = mysqli_query($db, $query);
                if (mysqli_num_rows($results) > 0) {
                    while ($each_row = $results->fetch_assoc()) {
                        $all_rows[] = $each_row;
                    }
                    echo json_encode(['1', $req, $all_rows, $query, $_POST]);
                } else {
                    echo json_encode(['2', $req, $results, $query, $_POST]);
                }
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_patient_reqs_for_pharmacy') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);

            $query = 'SELECT * FROM patient_req_pharmacy where req_state = "0" and fuserid = (select userid from `users_registration` where `email` = "' . $email . '" ) ;';
            // $query = 'SELECT  req_id,fuserid,requestby, req_state, note, (SELECT cities.name  FROM cities WHERE cities.id = patient_req_pharmacy.city ) as city, np, geographiclocation , ts from patient_req_pharmacy where req_state = "0" and fuserid = (select userid from `users_registration` where `email` = "' . $email . '" ) ;';

            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $all_rows[] = $each_row;
                }
                echo json_encode([true, $all_rows, $query, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_patient_reqs_for_doctor') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);

            $query = 'SELECT * FROM patient_req_doctor where req_state = "0" and fuserid = (select userid from `users_registration` where `email` = "' . $email . '" ) ;';
            // $query = 'SELECT  req_id, fuserid, requestby, req_state, note, (SELECT cities.name  FROM cities WHERE cities.id = patient_req_doctor.city ) as city,   gender, np,  geographiclocation , ts FROM patient_req_doctor  where req_state = "0" and fuserid = (select userid from `users_registration` where `email` = "' . $email . '" ) ;';

            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $all_rows[] = $each_row;
                }
                echo json_encode([true, $all_rows, $query, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_patient_reqs_for_lab') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);

            $query = 'SELECT * FROM patient_req_lab where req_state = "0" and fuserid = (select userid from `users_registration` where `email` = "' . $email . '" ) ;';
            // $query = 'SELECT   req_id, fuserid, requestby, req_state, note, (SELECT cities.name  FROM cities WHERE cities.id = patient_req_lab.city ) as city, np, geographiclocation , ts from patient_req_lab where req_state = "0" and fuserid = (select userid from `users_registration` where `email` = "' . $email . '" ) ;';


            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $all_rows[] = $each_row;
                }
                echo json_encode([true, $all_rows, $query, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_patient_offers_from_pharmacy') { // souitable for multiple reqs
            $req_id = mysqli_real_escape_string($db, $_POST['req_id']);
            // $query = 'SELECT  req_id, fuserid, requestby, req_state, note, (SELECT cities.name  FROM cities WHERE cities.id = patient_req_pharmacy.city ) as city, np, geographiclocation , ts  FROM patient_req_pharmacy where req_state = "0" and  req_id = '.$req_id.';';
            $query = 'SELECT * FROM patient_req_pharmacy where req_state = "0" and  req_id = ' . $req_id . ';';
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results)) {

                // fetching request
                $req = mysqli_fetch_assoc($results);

                //$query = 'SELECT users_lab.city as city, users_lab.fuserid as id, users_lab.username as name, users_lab.np as np, users_lab.phone as phone,  lab_offer.offernote as note, lab_offer.pricing as price, lab_offer.ts as ts, lab_offer.offerid as oid, lab_offer.appointment as time from lab_offer inner join users_lab on lab_offer.labid = users_lab.fuserid where lab_offer.state = "0" and lab_offer.req_id = ' . $patient['req_id'] . ';';

                // $query = 'SELECT users_doctor.city as city, users_doctor.fuserid as id, users_doctor.username as name, users_doctor.np as np,users_doctor.phone as phone, users_doctor.gender as gender, doctor_offer.offernote as note, doctor_offer.pricing as price, doctor_offer.ts as ts, doctor_offer.offerid as oid, doctor_offer.appointment as time from doctor_offer inner join users_doctor on doctor_offer.doctorid = users_doctor.fuserid where doctor_offer.state = "0" and doctor_offer.req_id = ' . $req_id . ';';

                // $query = 'SELECT (SELECT cities.name  FROM cities WHERE cities.id = users_pharmacy.city ) as city, users_pharmacy.fuserid as id, users_pharmacy.title as title, users_pharmacy.np as np, users_pharmacy.phone as phone, pharmacy_offer.offernote as note, pharmacy_offer.pricing as price, pharmacy_offer.ts as ts, pharmacy_offer.offerid as oid, pharmacy_offer.appointment as time from pharmacy_offer inner join users_pharmacy on pharmacy_offer.pharmacyid = users_pharmacy.fuserid where pharmacy_offer.state = "0" and pharmacy_offer.req_id = ' . $req_id . ';';
                $query = 'SELECT users_pharmacy.city as city, users_pharmacy.fuserid as id, users_pharmacy.title as title, users_pharmacy.np as np, users_pharmacy.phone as phone, pharmacy_offer.offernote as note, pharmacy_offer.pricing as price, pharmacy_offer.ts as ts, pharmacy_offer.offerid as oid, pharmacy_offer.appointment as time from pharmacy_offer inner join users_pharmacy on pharmacy_offer.pharmacyid = users_pharmacy.fuserid where pharmacy_offer.state = "0" and pharmacy_offer.req_id = ' . $req_id . ';';


                $results = mysqli_query($db, $query);
                if (mysqli_num_rows($results) > 0) {
                    while ($each_row = $results->fetch_assoc()) {
                        $all_rows[] = $each_row;
                    }
                    echo json_encode(['1', $req, $all_rows, $query, $_POST]);
                } else {
                    echo json_encode(['2', $req, $results, $query, $_POST]);
                }
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_patient_offers_from_doctor') { // souitable for multiple reqs
            $req_id = mysqli_real_escape_string($db, $_POST['req_id']);
            // $query = 'SELECT  req_id, fuserid, requestby, req_state, note, (SELECT cities.name  FROM cities WHERE cities.id = patient_req_doctor.city ) as city, gender , np, geographiclocation , ts from patient_req_doctor where req_state = "0" and  req_id = '.$req_id.';';
            $query = 'SELECT  * from patient_req_doctor where req_state = "0" and  req_id = ' . $req_id . ';';
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results)) {

                // fetching request
                $req = mysqli_fetch_assoc($results);

                //$query = 'SELECT users_pharmacy.city as city, users_pharmacy.fuserid as id, users_pharmacy.username as name, users_pharmacy.np as np, users_pharmacy.phone as phone, pharmacy_offer.offernote as note, pharmacy_offer.pricing as price, pharmacy_offer.ts as ts, pharmacy_offer.offerid as oid, pharmacy_offer.appointment as time from pharmacy_offer inner join users_pharmacy on pharmacy_offer.pharmacyid = users_pharmacy.fuserid where pharmacy_offer.state = "0" and pharmacy_offer.req_id = ' . $req_id . ';';

                //$query = 'SELECT users_lab.city as city, users_lab.fuserid as id, users_lab.username as name, users_lab.np as np, users_lab.phone as phone,  lab_offer.offernote as note, lab_offer.pricing as price, lab_offer.ts as ts, lab_offer.offerid as oid, lab_offer.appointment as time from lab_offer inner join users_lab on lab_offer.labid = users_lab.fuserid where lab_offer.state = "0" and lab_offer.req_id = ' . $patient['req_id'] . ';';

                // $query = 'SELECT (SELECT cities.name  FROM cities WHERE cities.id = users_doctor.city ) as city, users_doctor.fuserid as id, users_doctor.username as name, users_doctor.np as np,users_doctor.phone as phone, users_doctor.gender as gender, doctor_offer.offernote as note, doctor_offer.pricing as price, doctor_offer.ts as ts, doctor_offer.offerid as oid, doctor_offer.appointment as time from doctor_offer inner join users_doctor on doctor_offer.doctorid = users_doctor.fuserid where doctor_offer.state = "0" and doctor_offer.req_id = ' . $req_id . ';';
                $query = 'SELECT users_doctor.city as city, users_doctor.fuserid as id, users_doctor.username as name, users_doctor.np as np,users_doctor.phone as phone, users_doctor.gender as gender, doctor_offer.offernote as note, doctor_offer.pricing as price, doctor_offer.ts as ts, doctor_offer.offerid as oid, doctor_offer.appointment as time from doctor_offer inner join users_doctor on doctor_offer.doctorid = users_doctor.fuserid where doctor_offer.state = "0" and doctor_offer.req_id = ' . $req_id . ';';


                $results = mysqli_query($db, $query);
                if (mysqli_num_rows($results) > 0) {
                    while ($each_row = $results->fetch_assoc()) {
                        $all_rows[] = $each_row;
                    }
                    echo json_encode(['1', $req, $all_rows, $query, $_POST]);
                } else {
                    echo json_encode(['2', $req, $results, $query, $_POST]);
                }
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_patient_offers_from_lab') { // souitable for multiple reqs
            $req_id = mysqli_real_escape_string($db, $_POST['req_id']);
            // $query = 'SELECT  req_id, fuserid, requestby, req_state, note, (SELECT cities.name  FROM cities WHERE cities.id = patient_req_lab.city ) as city, np, geographiclocation , ts  FROM patient_req_lab where req_state = "0" and  req_id = '.$req_id.';';
            $query = 'SELECT * FROM patient_req_lab where req_state = "0" and  req_id = ' . $req_id . ';';
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results)) {

                // fetching request
                $req = mysqli_fetch_assoc($results);

                //$query = 'SELECT users_pharmacy.city as city, users_pharmacy.fuserid as id, users_pharmacy.username as name, users_pharmacy.np as np, users_pharmacy.phone as phone, pharmacy_offer.offernote as note, pharmacy_offer.pricing as price, pharmacy_offer.ts as ts, pharmacy_offer.offerid as oid, pharmacy_offer.appointment as time from pharmacy_offer inner join users_pharmacy on pharmacy_offer.pharmacyid = users_pharmacy.fuserid where pharmacy_offer.state = "0" and pharmacy_offer.req_id = ' . $req_id . ';';

                // $query = 'SELECT (SELECT cities.name  FROM cities WHERE cities.id = users_lab.city ) as city, users_lab.fuserid as id, users_lab.title as title, users_lab.np as np, users_lab.phone as phone,  lab_offer.offernote as note, lab_offer.pricing as price, lab_offer.ts as ts, lab_offer.offerid as oid, lab_offer.appointment as time from lab_offer inner join users_lab on lab_offer.labid = users_lab.fuserid where lab_offer.state = "0" and lab_offer.req_id = ' . $req_id . ';';
                $query = 'SELECT users_lab.city as city, users_lab.fuserid as id, users_lab.title as title, users_lab.np as np, users_lab.phone as phone,  lab_offer.offernote as note, lab_offer.pricing as price, lab_offer.ts as ts, lab_offer.offerid as oid, lab_offer.appointment as time from lab_offer inner join users_lab on lab_offer.labid = users_lab.fuserid where lab_offer.state = "0" and lab_offer.req_id = ' . $req_id . ';';

                // $query = 'SELECT users_doctor.city as city, users_doctor.fuserid as id, users_doctor.username as name, users_doctor.np as np,users_doctor.phone as phone, users_doctor.gender as gender, doctor_offer.offernote as note, doctor_offer.pricing as price, doctor_offer.ts as ts, doctor_offer.offerid as oid, doctor_offer.appointment as time from doctor_offer inner join users_doctor on doctor_offer.doctorid = users_doctor.fuserid where doctor_offer.state = "0" and doctor_offer.req_id = ' . $req_id . ';';


                $results = mysqli_query($db, $query);
                if (mysqli_num_rows($results) > 0) {
                    while ($each_row = $results->fetch_assoc()) {
                        $all_rows[] = $each_row;
                    }
                    echo json_encode(['1', $req, $all_rows, $query, $_POST]);
                } else {
                    echo json_encode(['2', $req, $results, $query, $_POST]);
                }
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_patient_detailed_doctor_offer') { // souitable for multiple reqs
            $req_id = mysqli_real_escape_string($db, $_POST['req_id']);
            $offerid = mysqli_real_escape_string($db, $_POST['offerid']);
            $providerid = mysqli_real_escape_string($db, $_POST['providerid']);
            $query1 = 'SELECT * FROM patient_req_doctor where req_state = "0" and req_id = ' . $req_id . ';';
            $query2 = 'SELECT * FROM doctor_offer where state = "0" and offerid = ' . $offerid . ';';
            $query3 = 'SELECT * FROM users_doctor where fuserid = ' . $providerid . ';';
            $results1 = mysqli_query($db, $query1);
            $results2 = mysqli_query($db, $query2);
            $results3 = mysqli_query($db, $query3);
            if ((mysqli_num_rows($results1) > 0)  && (mysqli_num_rows($results2) > 0) && (mysqli_num_rows($results3) > 0)) {
                echo json_encode([true, mysqli_fetch_assoc($results1), mysqli_fetch_assoc($results2), mysqli_fetch_assoc($results3)]);
            } else {
                echo json_encode([false, $results1, $results2, $results3, $_POST, $query1, $query2, $query3]);
            }
        } else if ($_POST['action']  == 'fetch_patient_detailed_pharmacy_offer') { // souitable for multiple reqs
            $req_id = mysqli_real_escape_string($db, $_POST['req_id']);
            $offerid = mysqli_real_escape_string($db, $_POST['offerid']);
            $providerid = mysqli_real_escape_string($db, $_POST['providerid']);
            $query1 = 'SELECT * FROM patient_req_pharmacy where req_state = "0" and req_id = ' . $req_id . ';';
            $query2 = 'SELECT * FROM pharmacy_offer where state = "0" and offerid = ' . $offerid . ';';
            $query3 = 'SELECT * FROM users_pharmacy where fuserid = ' . $providerid . ';';
            $results1 = mysqli_query($db, $query1);
            $results2 = mysqli_query($db, $query2);
            $results3 = mysqli_query($db, $query3);
            if ((mysqli_num_rows($results1) > 0)  && (mysqli_num_rows($results2) > 0) && (mysqli_num_rows($results3) > 0)) {
                echo json_encode([true, mysqli_fetch_assoc($results1), mysqli_fetch_assoc($results2), mysqli_fetch_assoc($results3)]);
            } else {
                echo json_encode([false, $results1, $results2, $results3, $_POST, $query1, $query2, $query3]);
            }
        } else if ($_POST['action']  == 'fetch_patient_detailed_lab_offer') { // souitable for multiple reqs
            $req_id = mysqli_real_escape_string($db, $_POST['req_id']);
            $offerid = mysqli_real_escape_string($db, $_POST['offerid']);
            $providerid = mysqli_real_escape_string($db, $_POST['providerid']);
            $query1 = 'SELECT * FROM patient_req_lab where req_state = "0" and req_id = ' . $req_id . ';';
            $query2 = 'SELECT * FROM lab_offer where state = "0" and offerid = ' . $offerid . ';';
            $query3 = 'SELECT * FROM users_lab where fuserid = ' . $providerid . ';';
            $results1 = mysqli_query($db, $query1);
            $results2 = mysqli_query($db, $query2);
            $results3 = mysqli_query($db, $query3);
            if ((mysqli_num_rows($results1) > 0)  && (mysqli_num_rows($results2) > 0) && (mysqli_num_rows($results3) > 0)) {
                echo json_encode([true, mysqli_fetch_assoc($results1), mysqli_fetch_assoc($results2), mysqli_fetch_assoc($results3)]);
            } else {
                echo json_encode([false, $results1, $results2, $results3, $_POST, $query1, $query2, $query3]);
            }
        } else if ($_POST['action']  == 'del_req') { // souitable for multiple reqs // in deleting the patient request its just setting the state in the offers and in the req_table and in the offers table to state 3 which means it has been deleted from the patient
            $type =  mysqli_real_escape_string($db, $_POST['type']);
            $req_id = mysqli_real_escape_string($db, $_POST['req_id']);
            $pid = mysqli_real_escape_string($db, $_POST['pid']);
            $req_table = '';
            $offers_table = '';
            $providerid = '';
            if ($type == '1') {
                $req_table = 'patient_req_doctor';
                $offers_table = 'doctor_offer';
                $providerid = 'doctorid';
            } else if ($type == '2') {
                $req_table = 'patient_req_pharmacy';
                $offers_table = 'pharmacy_offer';
                $providerid = 'pharmacyid';
            } else if ($type == '3') {
                $req_table = 'patient_req_lab';
                $offers_table = 'lab_offer';
                $providerid = 'labid';
            }

            // $query = 'SELECT fuserid from '.$req_table.' where req_id = '.$req_id.' limit 1';
            // $results = mysqli_query($db, $query);
            // $userid = mysqli_fetch_assoc($results);

            //notify(2,$db,$userid['fuserid'],'The request has been removed.',"3");

            // $query = 'SELECT username from users_patient where fuserid = '.$pid.' limit 1';
            // $results = mysqli_query($db, $query);
            // $username = mysqli_fetch_assoc($results);

            // $query = 'SELECT '.$providerid.' from '.$offers_table.' where req_id = '.$req_id.' and state = "0";';
            // $results = mysqli_query($db, $query);
            // if (mysqli_num_rows($results) > 0) {
            //     while ($each_row = $results->fetch_assoc()) {
            //         //notify(2,$db,$each_row[$providerid],' patient '.$username['username'].' has deleted the request which you have applied an offer to.',"3");
            //     }
            // }

            $query1 = 'UPDATE ' . $req_table . ' set req_state = "3", requestby = "3"  where req_id = ' . $req_id . ';';
            $query2 = 'UPDATE ' . $offers_table . ' set state = "3" where req_id  = ' . $req_id . ';';
            $result1 = mysqli_query($db, $query1);
            if ($result1) {
                $result2 = mysqli_query($db, $query2);
                if ($result2) {
                    echo json_encode([true, '']);
                } else {
                    echo json_encode([false, $query1, $query2, $_POST]);
                }
            } else {
                echo json_encode([false, $query1, $query2, $_POST]);
            }
        } else if ($_POST['action']  == 'accept_offer') { // souitable for multiple reqs
            $type = mysqli_real_escape_string($db, $_POST['type']);
            if ($type ==  '1') {
                $oid = mysqli_real_escape_string($db, $_POST['oid']);
                $req_id = mysqli_real_escape_string($db, $_POST['req_id']);
                $query = 'UPDATE patient_req_doctor SET req_state = "1" where req_state = "0" and req_id = ' . $req_id . ';';
                $results = mysqli_query($db, $query);
                if ($results) {
                    $query = 'UPDATE doctor_offer SET state = "1" where state = "0" and offerid = ' . $oid . '; ';
                    $results = mysqli_query($db, $query);
                    if ($results) {
                        $query = 'UPDATE doctor_offer SET state = "4" where state = "0" and offerid <> ' . $oid . ' and req_id = ' . $req_id . '; ';
                        $results = mysqli_query($db, $query);
                        if ($results) {

                            // $query = 'SELECT fuserid from patient_req_doctor where req_id = '.$req_id.' limit 1';
                            // $results = mysqli_query($db, $query);
                            // $userid = mysqli_fetch_assoc($results);
                            // notify(2,$db,$userid['fuserid'],'The offer has been approved and moved to appointments.',"1");

                            // $query = 'SELECT username from users_patient where fuserid = '.$userid['fuserid'].' limit 1';
                            // $results = mysqli_query($db, $query);
                            // $username = mysqli_fetch_assoc($results);

                            // $query = 'SELECT doctorid from doctor_offer where offerid = '.$oid.' limit 1';
                            // $results = mysqli_query($db, $query);
                            // $doctorid = mysqli_fetch_assoc($results);
                            // notify(2,$db,$doctorid['doctorid'],'your offer on '.$username['username'].' has been accepted and moved to appointments ',"1");

                            echo json_encode([true, '']);
                        } else {
                            echo json_encode([false, $query, $_POST, $results]);
                        }
                    } else {
                        echo json_encode([false, $query, $_POST, $results]);
                    }
                } else {
                    echo json_encode([false, $query, $_POST, $results]);
                }
            } else if ($type ==  '2') {
                $oid = mysqli_real_escape_string($db, $_POST['oid']);
                $req_id = mysqli_real_escape_string($db, $_POST['req_id']);
                $query = 'UPDATE patient_req_pharmacy SET req_state = "1" where req_state = "0" and req_id = ' . $req_id . ';';
                $results = mysqli_query($db, $query);
                if ($results) {
                    $query = 'UPDATE pharmacy_offer SET state = "1" where state = "0" and offerid = ' . $oid . '; ';
                    $results = mysqli_query($db, $query);
                    if ($results) {
                        $query = 'UPDATE pharmacy_offer SET state = "4" where state = "0" and offerid <> ' . $oid . ' and req_id = ' . $req_id . '; ';
                        $results = mysqli_query($db, $query);
                        if ($results) {

                            // $query1 = 'SELECT fuserid from patient_req_pharmacy where req_id = '.$req_id.' limit 1';
                            // $results = mysqli_query($db, $query1);
                            // $userid = mysqli_fetch_assoc($results);
                            // //notify(2,$db,$userid['fuserid'],'The offer has been approved and moved to appointments.',"1");

                            // $query2 = 'SELECT username from users_patient where fuserid = '.$userid['fuserid'].' limit 1';
                            // $results = mysqli_query($db, $query2);
                            // $username = mysqli_fetch_assoc($results);

                            // $query = 'SELECT pharmacyid from pharmacy_offer where offerid = '.$oid.' limit 1';
                            // $results = mysqli_query($db, $query);
                            // $pharmacyid = mysqli_fetch_assoc($results);
                            //notify(2,$db,$pharmacyid['pharmacyid'],'your offer on '.$username['username'].' has been accepted and moved to requests ',"1");

                            echo json_encode([true, '']);
                        } else {
                            echo json_encode([false, $query, $_POST, $results]);
                        }
                    } else {
                        echo json_encode([false, $query, $_POST, $results]);
                    }
                } else {
                    echo json_encode([false, $query, $_POST, $results]);
                }
            } else if ($type ==  '3') {
                $oid = mysqli_real_escape_string($db, $_POST['oid']);
                $req_id = mysqli_real_escape_string($db, $_POST['req_id']);
                $query = 'UPDATE patient_req_lab SET req_state = "1" where req_state = "0" and req_id = ' . $req_id . ';';
                $results = mysqli_query($db, $query);
                if ($results) {
                    $query = 'UPDATE lab_offer SET state = "1" where state = "0" and offerid = ' . $oid . '; ';
                    $results = mysqli_query($db, $query);
                    if ($results) {
                        $query = 'UPDATE lab_offer SET state = "4" where state = "0" and offerid <> ' . $oid . ' and req_id = ' . $req_id . '; ';
                        $results = mysqli_query($db, $query);
                        if ($results) {

                            // $query = 'SELECT fuserid from patient_req_lab where req_id = '.$req_id.' limit 1';
                            // $results = mysqli_query($db, $query);
                            // $userid = mysqli_fetch_assoc($results);
                            // //notify(2,$db,$userid['fuserid'],'The offer has been approved and moved to appointments.',"1");

                            // $query = 'SELECT username from users_patient where fuserid = '.$userid['fuserid'].' limit 1';
                            // $results = mysqli_query($db, $query);
                            // $username = mysqli_fetch_assoc($results);

                            // $query = 'SELECT labid from lab_offer where offerid = '.$oid.' limit 1';
                            // $results = mysqli_query($db, $query);
                            // $labid = mysqli_fetch_assoc($results);
                            // //notify(2,$db,$labid['labid'],'your offer on '.$username['username'].' has been accepted and moved to requests ',"1");

                            echo json_encode([true, '']);
                        } else {
                            echo json_encode([false, $query, $_POST, $results]);
                        }
                    } else {
                        echo json_encode([false, $query, $_POST, $results]);
                    }
                } else {
                    echo json_encode([false, $query, $_POST, $results]);
                }
            }
        } else if ($_POST['action']  == 'get_appointments') { // souitable for multiple reqs
            $type = mysqli_real_escape_string($db, $_POST['type']);
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $req_table = '';
            $offer_table = '';
            $providerid = '';
            if ($type == '1') {
                $req_table = 'patient_req_doctor';
                $offer_table = 'doctor_offer';
                $providertable = 'users_doctor';
                $providerid = 'doctorid';
            } else if ($type == '2') {
                $req_table = 'patient_req_pharmacy';
                $providerid = 'pharmacyid';
                $providertable = 'users_pharmacy';
                $offer_table = 'pharmacy_offer';
            } else if ($type == '3') {
                $req_table = 'patient_req_lab';
                $providerid = 'labid';
                $providertable = 'users_lab';
                $offer_table = 'lab_offer';
            }

            $query = 'SELECT * FROM ' . $req_table . ' where req_state = "1" and fuserid = (select userid from users_registration where email = "' . $email . '")';
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                $row = [];
                for ($i = 0; $i < mysqli_num_rows($results); $i++) {
                    $rows[$i]['req'] = mysqli_fetch_assoc($results);
                    $query1 = 'SELECT * from ' . $offer_table . ' where req_id = ' . $rows[$i]['req']['req_id'] . ' and state = "1"';
                    $results1 = mysqli_query($db, $query1);
                    $rows[$i]['offer'] = mysqli_fetch_assoc($results1);
                    $query2 = 'SELECT * from ' . $providertable . ' where fuserid = ' . $rows[$i]['offer'][$providerid] . ';';
                    $results2 = mysqli_query($db, $query2);
                    $rows[$i]['provider'] = mysqli_fetch_assoc($results2);
                }
                echo json_encode([true, $rows, $_POST, $results, $results1, $results2, $query, $query1, $query2]);
            } else {
                echo json_encode([false, $results, $query, $_POST, $results]);
            }
        } else if ($_POST['action']  == 'get_complete_appointments') { // souitable for multiple reqs
            $type = mysqli_real_escape_string($db, $_POST['type']);
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $req_table = '';
            $offer_table = '';
            $providerid = '';
            if ($type == '1') {
                $req_table = 'patient_req_doctor';
                $offer_table = 'doctor_offer';
                $providertable = 'users_doctor';
                $providerid = 'doctorid';
            } else if ($type == '2') {
                $req_table = 'patient_req_pharmacy';
                $providerid = 'pharmacyid';
                $providertable = 'users_pharmacy';
                $offer_table = 'pharmacy_offer';
            } else if ($type == '3') {
                $req_table = 'patient_req_lab';
                $providerid = 'labid';
                $providertable = 'users_lab';
                $offer_table = 'lab_offer';
            }

            $query = 'SELECT * FROM ' . $req_table . ' where req_state = "5" and fuserid = (select userid from users_registration where email = "' . $email . '")';
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                $row = [];
                for ($i = 0; $i < mysqli_num_rows($results); $i++) {
                    $rows[$i]['req'] = mysqli_fetch_assoc($results);
                    $query1 = 'SELECT * from ' . $offer_table . ' where req_id = ' . $rows[$i]['req']['req_id'] . ' and state = "5"';
                    $results1 = mysqli_query($db, $query1);
                    $rows[$i]['offer'] = mysqli_fetch_assoc($results1);
                    $query2 = 'SELECT * from ' . $providertable . ' where fuserid = ' . $rows[$i]['offer'][$providerid] . ';';
                    $results2 = mysqli_query($db, $query2);
                    $rows[$i]['provider'] = mysqli_fetch_assoc($results2);
                }
                echo json_encode([true, $rows, $_POST, $results, $results1, $results2, $query, $query1, $query2]);
            } else {
                echo json_encode([false, $results, $query, $_POST, $results]);
            }
        } else if ($_POST['action']  == 'get_detailed_appointment') { // souitable for multiple reqs
            $type = mysqli_real_escape_string($db, $_POST['type']);
            $req_id = mysqli_real_escape_string($db, $_POST['req_id']);
            $offer = mysqli_real_escape_string($db, $_POST['offerid']);
            $provider = mysqli_real_escape_string($db, $_POST['providerid']);
            $req_table = '';
            $offer_table = '';
            $providerid = '';
            if ($type == '1') {
                $req_table = 'patient_req_doctor';
                $offer_table = 'doctor_offer';
                $providertable = 'users_doctor';
                $providerid = 'doctorid';
            } else if ($type == '2') {
                $req_table = 'patient_req_pharmacy';
                $providerid = 'pharmacyid';
                $providertable = 'users_pharmacy';
                $offer_table = 'pharmacy_offer';
            } else if ($type == '3') {
                $req_table = 'patient_req_lab';
                $providerid = 'labid';
                $providertable = 'users_lab';
                $offer_table = 'lab_offer';
            }

            $query = 'SELECT * FROM ' . $req_table . ' where req_state=  "1" and req_id = ' . $req_id . ';';
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                $req = mysqli_fetch_assoc($results);
                $query = 'SELECT * from ' . $offer_table . ' where offerid = ' . $offer . ' and state = "1"';
                $results = mysqli_query($db, $query);
                $offer = mysqli_fetch_assoc($results);
                $query = 'SELECT * from ' . $providertable . ' where fuserid = ' . $provider . ';';
                $results = mysqli_query($db, $query);
                $providerO = mysqli_fetch_assoc($results);
                echo json_encode([true, $req, $offer, $providerO, $_POST, $results, $query]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'patient_del_appointment') { // souitable for multiple reqs
            $type = mysqli_real_escape_string($db, $_POST['type']);
            $oid = mysqli_real_escape_string($db, $_POST['oid']);
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $query1 = '';
            $query2 = '';
            $query3 = '';
            if ($type == '1') {
                $req_table = 'patient_req_doctor';
                $offer_table = 'doctor_offer';
                $providerid = 'doctorid';
            } else if ($type == '2') {
                $req_table = 'patient_req_pharmacy';
                $providerid = 'pharmacyid';
                $offer_table = 'pharmacy_offer';
            } else if ($type == '3') {
                $req_table = 'patient_req_lab';
                $providerid = 'labid';
                $offer_table = 'lab_offer';
            }
            $q1 = 'SELECT * FROM ' . $req_table . ' where req_state = "1" and fuserid = (select userid from users_registration where email = "' . $email . '") limit 1';
            $results = mysqli_query($db, $q1);
            $preq = mysqli_fetch_assoc($results);
            $q2 = 'UPDATE ' . $req_table . ' set req_state = "0" where req_id = ' . $preq['req_id'] . ';';
            $results = mysqli_query($db, $q2);
            $q3 = 'UPDATE ' . $offer_table . ' set state = "0" where req_id = ' . $preq['req_id'] . '  and (state = "1" or state = "4");';
            $results = mysqli_query($db, $q3);
            if ($results) {
                //notify(2,$db,$preq['fuserid'],'The appointment has been deleted and returned to pending state.',"2");

                // $query = 'SELECT username from users_patient where fuserid = '.$preq['fuserid'].' limit 1';
                // $results = mysqli_query($db, $query);
                // $username = mysqli_fetch_assoc($results);

                // $query = 'SELECT '.$providerid.' from '.$offer_table.' where offerid = '.$oid.' limit 1';
                // $results = mysqli_query($db, $query);
                // $provider = mysqli_fetch_assoc($results);
                //notify(2,$db,$provider[$providerid],'your appointment with '.$username['username'].' has been deleted.',"2");

                echo (json_encode([true, $_POST, $q1, $q2, $q3]));
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'pcomplete') { // souitable for multiple reqs // completing the appointemtn from patient !! and works for any type 
            $oid = mysqli_real_escape_string($db, $_POST['oid']);
            $type = mysqli_real_escape_string($db, $_POST['type']);
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $query1 = '';
            $query2 = '';
            $query3 = '';
            if ($type == '1') {
                $req_table = 'patient_req_doctor';
                $offer_table = 'doctor_offer';
                $providerid = 'doctorid';
            } else if ($type == '2') {
                $req_table = 'patient_req_pharmacy';
                $providerid = 'pharmacyid';
                $offer_table = 'pharmacy_offer';
            } else if ($type == '3') {
                $req_table = 'patient_req_lab';
                $providerid = 'labid';
                $offer_table = 'lab_offer';
            }
            $q1 = 'SELECT * FROM ' . $req_table . ' where req_state = "1" and fuserid = (select userid from users_registration where email = "' . $email . '") limit 1';
            $results = mysqli_query($db, $q1);
            $preq = mysqli_fetch_assoc($results);
            $q2 = 'UPDATE ' . $req_table . ' set req_state = "5",requestby = "5" where req_id = ' . $preq['req_id'] . ';';
            $results = mysqli_query($db, $q2);
            $q3 = 'UPDATE ' . $offer_table . ' set state = "5" where offerid = ' . $oid . '  and state = "1" ;';
            $results = mysqli_query($db, $q3);
            $q4 = 'UPDATE ' . $offer_table . ' set state = "6" where offerid = ' . $oid . ' and state = "4" ;';
            $results = mysqli_query($db, $q4);
            if ($results) {
                //notify(2,$db,$preq['fuserid'],'The appointment is recorded as completed successfully.','1');

                // $query = 'SELECT username from users_patient where fuserid = '.$preq['fuserid'].' limit 1';
                // $results = mysqli_query($db, $query);
                // $username = mysqli_fetch_assoc($results);
                // $query = 'SELECT '.$providerid.' from '.$offer_table.' where offerid = '.$oid.' limit 1';
                // $results = mysqli_query($db, $query);
                // $provider = mysqli_fetch_assoc($results);
                //notify(2,$db,$provider[$providerid],'your appointment with '.$username['username'].' has been completed.',"1");

                echo (json_encode([true, $_POST, $q1, $q2, $q3, $q4]));
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_doctor_offers') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $query = 'SELECT * from doctor_offer  where doctorid = (select userid from users_registration where email = "' . $email . '") and state = "0"  ;';
            $results = mysqli_query($db, $query);
            $elements = [];
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $offer = $each_row;
                    $query = 'SELECT * FROM users_patient where fuserid = ' . $each_row['patientid'] . ' limit 1;';
                    $temp = mysqli_query($db, $query);
                    $patient = mysqli_fetch_assoc($temp);
                    $query = 'SELECT * FROM patient_req_doctor where req_id = ' . $each_row['req_id'] . ' and req_state = "0" limit 1;';
                    $temp = mysqli_query($db, $query);
                    $req = mysqli_fetch_assoc($temp);
                    array_push($elements, ['offer' => $offer, 'req' => $req, 'patient' => $patient]);
                }
                echo json_encode([true, $elements]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'del_doctor_offer') { // souitable for multiple reqs
            $offerid = mysqli_real_escape_string($db, $_POST['offer']);
            $query = 'UPDATE doctor_offer set state = "3" where offerid = ' . $offerid . ';';
            $results = mysqli_query($db, $query);
            if ($results) {
                // $query = 'SELECT doctorid from doctor_offer where offerid = '.$offerid.' limit 1';
                // $results = mysqli_query($db, $query);
                // $provider = mysqli_fetch_assoc($results);
                //notify(2,$db,$provider['doctorid'],'your offer has been deleted successfully.',"2");
                echo json_encode([true, $results, $query, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_pharmacy_offers') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $query = 'SELECT * from pharmacy_offer  where pharmacyid = (select userid from users_registration where email = "' . $email . '") and state = "0"  ;';
            $results = mysqli_query($db, $query);
            $elements = [];
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $offer = $each_row;
                    $query = 'SELECT * FROM users_patient where fuserid = ' . $each_row['patientid'] . ' limit 1;';
                    $temp = mysqli_query($db, $query);
                    $patient = mysqli_fetch_assoc($temp);
                    $query = 'SELECT * FROM patient_req_pharmacy where req_id = ' . $each_row['req_id'] . ' and req_state = "0" limit 1;';
                    $temp = mysqli_query($db, $query);
                    $req = mysqli_fetch_assoc($temp);
                    array_push($elements, ['offer' => $offer, 'req' => $req, 'patient' => $patient]);
                }
                echo json_encode([true, $elements]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'del_pharmacy_offer') { // souitable for multiple reqs
            $offerid = mysqli_real_escape_string($db, $_POST['offer']);
            $query = 'UPDATE pharmacy_offer set state = "3" where offerid = ' . $offerid . ';';
            $results = mysqli_query($db, $query);
            if ($results) {
                // $query = 'SELECT pharmacyid from pharmacy_offer where offerid = '.$offerid.' limit 1';
                // $results = mysqli_query($db, $query);
                // $provider = mysqli_fetch_assoc($results);
                //notify(2,$db,$provider['pharmacyid'],'your offer has been deleted successfully.',"2");
                echo json_encode([true, $results, $query, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_lab_offers') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $query = 'SELECT * from lab_offer  where labid = (select userid from users_registration where email = "' . $email . '") and state = "0"  ;';
            $results = mysqli_query($db, $query);
            $elements = [];
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $offer = $each_row;
                    $query = 'SELECT * FROM users_patient where fuserid = ' . $each_row['patientid'] . ' limit 1;';
                    $temp = mysqli_query($db, $query);
                    $patient = mysqli_fetch_assoc($temp);
                    $query = 'SELECT * FROM patient_req_lab where req_id = ' . $each_row['req_id'] . ' and req_state = "0" limit 1;';
                    $temp = mysqli_query($db, $query);
                    $req = mysqli_fetch_assoc($temp);
                    array_push($elements, ['offer' => $offer, 'req' => $req, 'patient' => $patient]);
                }
                echo json_encode([true, $elements]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'del_lab_offer') { // souitable for multiple reqs
            $offerid = mysqli_real_escape_string($db, $_POST['offer']);
            $query = 'UPDATE lab_offer set state = "3" where offerid = ' . $offerid . ';';
            $results = mysqli_query($db, $query);
            if ($results) {
                // $query = 'SELECT labid from lab_offer where offerid = '.$offerid.' limit 1';
                // $results = mysqli_query($db, $query);
                // $provider = mysqli_fetch_assoc($results);
                //notify(2,$db,$provider['labid'],'your offer has been deleted successfully.',"2");
                echo json_encode([true, $results, $query, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_doctor_appointments') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $query = 'SELECT * from doctor_offer  where doctorid = (select userid from users_registration where email = "' . $email . '") and state = "1"  ;';
            $results = mysqli_query($db, $query);
            $elements = [];
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $offer = $each_row;
                    $query = 'SELECT * FROM users_patient where fuserid = ' . $each_row['patientid'] . ' limit 1;';
                    $temp = mysqli_query($db, $query);
                    $patient = mysqli_fetch_assoc($temp);
                    $query = 'SELECT * FROM patient_req_doctor where req_id = ' . $each_row['req_id'] . ' and req_state = "1" limit 1;';
                    $temp = mysqli_query($db, $query);
                    $req = mysqli_fetch_assoc($temp);
                    array_push($elements, ['offer' => $offer, 'req' => $req, 'patient' => $patient]);
                }
                echo json_encode([true, $elements]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_pharmacy_appointments') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $query = 'SELECT * from pharmacy_offer  where pharmacyid = (select userid from users_registration where email = "' . $email . '") and state = "1"  ;';
            $results = mysqli_query($db, $query);
            $elements = [];
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $offer = $each_row;
                    $query = 'SELECT * FROM users_patient where fuserid = ' . $each_row['patientid'] . ' limit 1;';
                    $temp = mysqli_query($db, $query);
                    $patient = mysqli_fetch_assoc($temp);
                    $query = 'SELECT * FROM patient_req_pharmacy where req_id = ' . $each_row['req_id'] . ' and req_state = "1" limit 1;';
                    $temp = mysqli_query($db, $query);
                    $req = mysqli_fetch_assoc($temp);
                    array_push($elements, ['offer' => $offer, 'req' => $req, 'patient' => $patient]);
                }
                echo json_encode([true, $elements]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'fetch_lab_appointments') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $query = 'SELECT * from lab_offer  where labid = (select userid from users_registration where email = "' . $email . '") and state = "1"  ;';
            $results = mysqli_query($db, $query);
            $elements = [];
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $offer = $each_row;
                    $query = 'SELECT * FROM users_patient where fuserid = ' . $each_row['patientid'] . ' limit 1;';
                    $temp = mysqli_query($db, $query);
                    $patient = mysqli_fetch_assoc($temp);
                    $query = 'SELECT * FROM patient_req_lab where req_id = ' . $each_row['req_id'] . ' and req_state = "1" limit 1;';
                    $temp = mysqli_query($db, $query);
                    $req = mysqli_fetch_assoc($temp);
                    array_push($elements, ['offer' => $offer, 'req' => $req, 'patient' => $patient]);
                }
                echo json_encode([true, $elements]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'del_lab_appointment') { // souitable for multiple reqs
            $offerid = mysqli_real_escape_string($db, $_POST['offer']);
            $query = 'SELECT * FROM lab_offer where offerid = ' . $offerid . ' limit 1;';
            $results = mysqli_query($db, $query);
            $offer = mysqli_fetch_assoc($results);
            $query = 'UPDATE lab_offer set state = "3" where offerid = ' . $offerid . ';';
            $results = mysqli_query($db, $query);
            $query = 'UPDATE patient_req_lab set req_state = "0" where req_state = "1" and req_id = ' . $offer['req_id'] . ';';
            $results = mysqli_query($db, $query);
            if ($results) {
                // notify(2,$db,$offer['labid'],'The appoitment has been deleted successfully.',"2");
                // $query = 'SELECT title from users_lab where fuserid = '.$offer['labid'].';';
                // $results = mysqli_query($db,$query);
                // $providername = mysqli_fetch_assoc($results);
                // notify(2,$db,$offer['patientid'],'your appointment with lab '.$providername['title'].' has been deleted.',"2");
                // $results = mysqli_query($db, $query);
                echo json_encode([true, $results, $query, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'del_doctor_appointment') { // souitable for multiple reqs
            $offerid = mysqli_real_escape_string($db, $_POST['offer']);
            $query = 'SELECT * FROM doctor_offer where offerid = ' . $offerid . ' limit 1;';
            $results = mysqli_query($db, $query);
            $offer = mysqli_fetch_assoc($results);
            $query = 'UPDATE doctor_offer set state = "3" where offerid = ' . $offerid . ';';
            $results = mysqli_query($db, $query);
            $query = 'UPDATE patient_req_doctor set req_state = "0" where req_state = "1" and req_id = ' . $offer['req_id'] . ';';
            $results = mysqli_query($db, $query);
            if ($results) {
                //notify(2,$db,$offer['doctorid'],'The appoitment has been deleted successfully.',"2");
                // $query = 'SELECT username from users_doctor where fuserid = '.$offer['doctorid'].';';
                // $results = mysqli_query($db,$query);
                // $providername = mysqli_fetch_assoc($results);
                //notify(2,$db,$offer['patientid'],'your appointment with doctor '.$providername['username'].' has been deleted.',"2");

                echo json_encode([true, $results, $query, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'del_pharmacy_appointment') { // souitable for multiple reqs
            $offerid = mysqli_real_escape_string($db, $_POST['offer']);
            $query = 'SELECT * FROM pharmacy_offer where offerid = ' . $offerid . ' limit 1;';
            $results = mysqli_query($db, $query);
            $offer = mysqli_fetch_assoc($results);
            $query = 'UPDATE pharmacy_offer set state = "3" where offerid = ' . $offerid . ';';
            $results = mysqli_query($db, $query);
            $query = 'UPDATE patient_req_pharmacy set req_state = "0" where req_state = "1" and req_id = ' . $offer['req_id'] . ';';
            $results = mysqli_query($db, $query);
            if ($results) {
                //notify(2,$db,$offer['pharmacyid'],'The appoitment has been deleted successfully.',"2");
                // $query = 'SELECT title from users_pharmacy where fuserid = '.$offer['pharmacyid'].';';
                // $results = mysqli_query($db,$query);
                // $providername = mysqli_fetch_assoc($results);
                //notify(2,$db,$offer['patientid'],'your appointment with pharmacy '.$providername['title'].' has been deleted.',"2");
                echo json_encode([true, $results, $query, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'getCountnotifications') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $query = 'SELECT COUNT(notifyid) FROM notifications where state = "0" and touser = (select userid from users_registration where email = "' . $email . '") ORDER BY ts DESC limit 100;';
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $all_rows[] = $each_row;
                }
                echo json_encode([true, $all_rows, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'getnotifications') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $query = 'SELECT * FROM notifications where touser = (select userid from users_registration where email = "' . $email . '") ORDER BY ts DESC limit 100;';
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $all_rows[] = $each_row;
                }
                echo json_encode([true, $all_rows, mysqli_num_rows($results), $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'insertcity') { // souitable for multiple reqs
            $city = mysqli_real_escape_string($db, $_POST['city']);
            $query = 'INSERT INTO cities(name) values("' . $city . '");';
            $results = mysqli_query($db, $query);
            if ($results) {
                echo json_encode([true, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'insertnotification') { // souitable for multiple reqs
            $msg = mysqli_real_escape_string($db, $_POST['msg']);
            $id = mysqli_real_escape_string($db, $_POST['id']);
            $query = 'INSERT INTO notificationmsgs(id,msg) values(' . $id . ',"' . $msg . '");';
            $results = mysqli_query($db, $query);
            if ($results) {
                echo json_encode([true, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'getcities') { // souitable for multiple reqs
            $query = 'SELECT * FROM cities;';
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $all_rows[] = $each_row;
                }
                echo json_encode([true, $all_rows, $query, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'getcarousel') { // souitable for multiple reqs
            $query = 'SELECT * FROM carousel;';
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                while ($each_row = $results->fetch_assoc()) {
                    $all_rows[] = $each_row;
                }
                echo json_encode([true, $all_rows, $query, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'updatenotification') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $query = 'UPDATE notifications set state = "1" where state = "0" and touser  = (select userid from users_registration where email = "' . $email . '" limit 1);';
            $results = mysqli_query($db, $query);
            if ($results) {
                echo json_encode([true, $query, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'getuserdata') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $type = mysqli_real_escape_string($db, $_POST['type']);
            if ($type == "0") {
                $user_table = 'patients_registration';
            }

            $query = 'SELECT * from ' . $user_table . ' where fuserid  = (select userid from users_registration where email = "' . $email . '" limit 1);';
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                $user = mysqli_fetch_assoc($results);
                echo json_encode([true, $user, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'updateuserdata') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $type = mysqli_real_escape_string($db, $_POST['type']);
            $phone = mysqli_real_escape_string($db, $_POST['phone']);
            $city = mysqli_real_escape_string($db, $_POST['city']);
            $np = mysqli_real_escape_string($db, $_POST['np']);
            $name = mysqli_real_escape_string($db, $_POST['name']);
            if ($type == "0") {
                $user_table = 'patients_registration';
            }

            $query = 'UPDATE ' . $user_table . ' SET phone = "' . $phone . '", city = "' . $city . '", np = "' . $np . '", username = "' . $name . '" where fuserid  = (select userid from users_registration where email = "' . $email . '" limit 1);';
            $results = mysqli_query($db, $query);
            if ($results) {
                echo json_encode([true, $results, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else if ($_POST['action']  == 'updatepassword') { // souitable for multiple reqs
            $email = mysqli_real_escape_string($db, $_POST['email']);
            $password = mysqli_real_escape_string($db, $_POST['password']);

            $query = 'UPDATE users_registration SET  userpassword = "' . $password . '" where email  =  "' . $email . '";';
            $results = mysqli_query($db, $query);
            if ($results) {
                echo json_encode([true, $results, $_POST]);
            } else {
                echo json_encode([false, $results, $query, $_POST]);
            }
        } else {
            echo (json_encode([false, 'no action']));
        }
    } else {
        echo json_encode([false, 'wrong pass']);
    }
} else {
    echo (json_encode([false, 'no such action', $_POST]));
}
