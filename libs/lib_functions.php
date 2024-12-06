<?php 
if(!defined('TODO_PATH')){
    echo "permision denid!";
    die();
}

# folder fonctions
function getFolders(){
    global $conn;
    $query = "SELECT * FROM folders";
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
    $query = "INSERT INTO folders (name,user_id) VALUES (:folder_name,:user_id)";
    $stmt = $conn->prepare($query);
    $stmt->execute([':folder_name'=>$folder_name,':user_id' => 1]);
    return $conn->lastInsertId();
}
# task functions 

function getTasks(){
    global $conn;
    $folder_id = $_GET['folder_id'] ?? null;
    $conditionfile = '';
    if(isset($folder_id) && is_numeric($folder_id)){
        $conditionfile = "and folder_id=$folder_id";
    }
    $query = "SELECT * FROM tasks WHERE user_id=1  $conditionfile";
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


function addTask($title,$folder_id){
    global $conn;
    $query = "INSERT INTO tasks (user_id,folder_id,title) VALUES (:user_id,:folder_id,:title)";
    $stmt = $conn->prepare($query);
    $stmt->execute([':folder_id'=>$folder_id,':user_id' => 1,":title" => $title]);
    return $conn->lastInsertId();
}

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
            $status = "in-progress";
            break;
    }
    return $status;
}


function updateTaskStatus($taskid,$taskstatus){
    global $conn;
    $query = "UPDATE tasks SET status = :statustask WHERE id = :task_id AND user_id = :user";
    $stmt = $conn->prepare($query);
    $stmt->execute([':statustask'=>$taskstatus,':user' => 1,":task_id" => $taskid]);
    return $taskstatus;
}
