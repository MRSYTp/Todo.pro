<?php 
include  "../bootstrap/init.php";

if(!isAjaxRequest()){
    diePage("invalid Request!!");
}
if(!isset($_POST["action"]) && empty($_POST["action"])){
    diePage("invalid Action!!");
}
switch($_POST["action"]){
    case "add_folder":
        if(!isset($_POST["name"]) && empty($_POST["name"])){
            echo "فیلد را پر کنید";
            die();
        }
       echo addFolder($_POST["name"]);
    break;
    case "add_task":
        if(isset($_POST["folder_id"]) && $_POST["folder_id"] > 0){


            if(isset($_POST["title"]) && !empty($_POST["title"])){

                echo addTask($_POST["title"],$_POST['folder_id']);
            }
        }
    break;
    case "task_complete":
        
        if(isset($_POST["taskid"]) && $_POST["taskid"] > 0){

            if(isset($_POST["status"]) && !empty($_POST["status"])){

                $tiggleStatus = tiggleStatusTask($_POST["status"]);
               
                echo updateTaskStatus($_POST["taskid"],$tiggleStatus);
            }
        }
    break;
    default : 
    diePage("invalid Action!!");
}
