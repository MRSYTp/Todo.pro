<?php 
include "bootstrap/init.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $action = $_GET['action'];
        
        if($action == "login"){

            login($_POST["email"],$_POST["password"]);

            if (isLoggedIn()) {
                header(redirect(site_url()));
            }
        }
        if($action == "register"){

            register($_POST);
        }

    }

include TODO_PATH .  "template/tpl-auth.php";