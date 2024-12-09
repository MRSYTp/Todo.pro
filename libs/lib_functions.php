<?php 
if(!defined('TODO_PATH')){
    echo "permision denid!";
    die();
}

# folder fonctions :


function getFolders(){
    global $conn;
    $userID = getCurrentUserId();
    $query = "SELECT * FROM folders WHERE user_id = $userID";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $folders = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $folders;
}



function deleteFolder($folder_id){
    global $conn;
    $query = "DELETE FROM folders WHERE id=$folder_id";
    $stmt = $conn->prepare($query);
    $stmt->execute();
}


function addFolder($folder_name){
    global $conn;
    $userID = getCurrentUserId();
    $query = "INSERT INTO folders (name,user_id) VALUES (:folder_name,:user_id)";
    $stmt = $conn->prepare($query);
    $stmt->execute([':folder_name'=>$folder_name,':user_id' => $userID]);
    return $conn->lastInsertId();
}


# task functions :


function getTasks(){
    global $conn;
    $userID = getCurrentUserId();
    $folder_id = $_GET['folder_id'] ?? null;
    $conditionfile = '';
    if(isset($folder_id) && is_numeric($folder_id)){
        $conditionfile = "and folder_id=$folder_id";
    }
    $query = "SELECT * FROM tasks WHERE user_id=$userID  $conditionfile";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $tasks;
}



function deleteTask($task_id){
    global $conn;
    $query = "DELETE FROM tasks WHERE id=$task_id";
    $stmt = $conn->prepare($query);
    $stmt->execute();
}



function fixTaskTime($time, $date) {

    if (!empty($date) && !empty($time)) {

        $datetime = $date . ' ' . $time . ':00'; // اضافه کردن ثانیه 00

        return $datetime;

    } else {
        return null;
    }
}


function addTask($title,$folder_id,$taskTime){
    global $conn;
    $userID = getCurrentUserId();
    $query = "INSERT INTO tasks (user_id,folder_id,title,task_time) VALUES (:user_id,:folder_id,:title,:Ttime)";
    $stmt = $conn->prepare($query);
    $stmt->execute([':folder_id'=>$folder_id,':user_id' => $userID,":title" => $title,":Ttime" => $taskTime]);
    return $conn->lastInsertId();
}




function updateTaskStatus($taskid,$taskstatus){
    global $conn;
    $userID = getCurrentUserId();
    $query = "UPDATE tasks SET status = :statustask WHERE id = :task_id AND user_id = :user";
    $stmt = $conn->prepare($query);
    $stmt->execute([':statustask'=>$taskstatus,':user' => $userID,":task_id" => $taskid]);
    return $taskstatus;
}


function checkFieldTask($task){

    date_default_timezone_set('Asia/Tehran');
    $currentTime = date('Y-m-d H:i:s');
    $currentTimestamp = strtotime($currentTime);
    // return $tasks;
    if ($task->status == "in-progress") {
        if ($task->task_time != "0000-00-00 00:00:00") {
            $timestampTask = strtotime($task->task_time);
            
            if ($timestampTask < $currentTimestamp) {

                $result = setStatusFailed($task->id);

            }else{
                return "monde hano";
            }
        }else {
            return "not set time!";
        }
    }


}


function setStatusFailed($taskID){
    global $conn;
    $userID = getCurrentUserId();
    $query = "UPDATE tasks SET status = :statustask WHERE id = :task_id AND user_id = :user";
    $stmt = $conn->prepare($query);
    $stmt->execute([':statustask'=> "failed",':user' => $userID,":task_id" => $taskID]);
    return $stmt->rowCount();
}



#switch func :

    function tiggleStatusTask($statusTask){
        $status = $statusTask;
        switch ($status) {
            case 'in-progress':
                $status = "complete";
            break;
            case 'complete':
                $status = "in-progress";
            break;
            
            default:
                $status = "failed";
                break;
        }
        return $status;
    }

    
    function liSwtcherClass($statusTask){
        $status = $statusTask;
        switch ($status) {
            case 'in-progress':
                $status = "";
            break;
            case 'complete':
                $status = "checked";
            break;
            case 'failed':
                $status = "failed-class";
            break;
            
            default:
                $status = "";
                break;
        }
        return $status;
    }
    function iconSwtcher($statusTask){
        $status = $statusTask;
        switch ($status) {
            case 'in-progress':
                $status = "clickable fa fa-square-o";
            break;
            case 'complete':
                $status = "clickable fa fa-check-square-o";
            break;
            case 'failed':
                $status = "fa fa-lock";
            break;
            
            default:
                $status = "";
                break;
        }
        return $status;
    }