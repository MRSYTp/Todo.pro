<?php
include "bootstrap/init.php";


if(isLoggedIn()){
    
    if(isset($_GET["logout"]) && $_GET['logout'] == "true"){
        logout();
        die();
    }


    if(isset($_GET["delete_folder"]) && is_numeric($_GET["delete_folder"])){
        deleteFolder($_GET["delete_folder"]);
        
    }
    
    if(isset($_GET["delete_task"]) && is_numeric($_GET["delete_task"])){
        deleteTask($_GET["delete_task"]);
        
    }
    
    $getForCheckTask = getTasks();
    foreach($getForCheckTask as $task){
        checkFieldTask($task);
    }
    
    $folders = getFolders();
    $tasks = getTasks();
    
    
    include "template/tpl-index.php";
}else {
    redirect(site_url("auth.php"));
}
