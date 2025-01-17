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
            showErrorMessage('password not found please check password.');
        }
    }else {
        showErrorMessage("Error User not found!");
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
        showSuccessMessage('wellcome '.$userdata["name"].' in TodoPro <br>Login please');
    }else{
        showErrorMessage("field to register try again later!");
    }
}


