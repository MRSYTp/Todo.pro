<?php 


function getUserByEmail($email){
    global $conn;
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->execute([":email" => $email]);
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $result[0] ?? null;

}


function login($email,$pass){

    $user = getUserByEmail($email);
    if (!is_null($user)) {
        if (password_verify($pass,$user->password)) {
            $_SESSION["login"] = $user;
        }else{
            message('password not currect please check password.');
        }
    }else {
        message("Error User not found!");
    }

}




function logout(){

    unset($_SESSION['login']);
    redirect(site_url("auth.php"));

}



function getUserData(){
    return $_SESSION['login'];
}





function getCurrentUserId(){
    return getUserData()->id;
}



function isLoggedIn(){
    
    return isset($_SESSION["login"]) ? true : false;

}


function register($userdata){
    global $conn;
    $hushpass = password_hash($userdata['password'],PASSWORD_BCRYPT);
    $query = "INSERT INTO users (name,email,password) VALUES (:name,:email,:pass)";
    $stmt = $conn->prepare($query);
    $stmt->execute([':name'=>$userdata['name'],':email' => $userdata['email'],':pass' => $hushpass]);
    $iduser = $conn->lastInsertId();
    if($iduser > 0){
        message('wellcome '.$userdata["name"].' in TodoPro <br><a href="'.site_url("auth.php").'" style="text-decoration: none;">Login please</a>');
    }else{
        message("field to register try again later!");
    }
}


