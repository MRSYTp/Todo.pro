<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>TODO PRO</title>
  <link rel="stylesheet" href="<?=  site_url('assets/css/style.css'); ?>">
  <link rel="stylesheet" href="<?=  site_url('assets/css/custom.css'); ?>">
  <style>
    html {
    background-image: url('assets/img/bg.png');
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    color: #F0F4F5;
    }
  .failed-class{
    text-decoration: line-through;
    background-color: #ffd8d8;


    }
  </style>
</head>
<body>
<!-- partial:index.partial.html -->
<div class="page">
  <div class="pageHeader">
    <div class="title">Todo Pro</div>
    <div class="userPanel">
      <a style="color: #ffffff;font-size: 17px;" href="<?= site_url("?logout=true")?>">
        <i class="fa fa-sign-out"></i>
      </a>
      <span class="username"><?= getUserData()->name; ?></span>
    </div>
  </div>
  <div class="main">
    <div class="nav">
      <div class="searchbox">
        <div><i class="fa fa-search"></i>
          <input type="search" placeholder="Search"/>
        </div>
      </div>
      <div class="menu">
        <div class="title">FOLDERS</div>
        <ul class="folder-list">
        <li class="<?= (isset($_GET['folder_id'])) ? '' : 'active' ;?>">
              <a href="<?= site_url()?>" style="text-decoration: none;">
                <i class="<?= (isset($_GET['folder_id'])) ? 'fa fa-folder' : 'fa fa-folder-open' ;?>"></i>All
              </a>

            </li>
          <?php foreach($folders as $folder): ?>
            <li class="<?= (isset($_GET['folder_id']) && $_GET['folder_id'] == $folder->id) ? 'active' : '' ;?>">
              <a href="?folder_id=<?= $folder->id ?>" style="text-decoration: none;">
                <i class="<?= (isset($_GET['folder_id']) && $_GET['folder_id'] == $folder->id) ? 'fa fa-folder-open' : 'fa fa-folder' ;?>"></i><?= $folder->name ?>
              </a>
              <a  href="?delete_folder=<?= $folder->id ?>" class="trash" onclick="return confirm('Are You Sure to delete this Item?\n<?=$folder->name?>');">
                <i class="fa fa-trash-o trash"></i>
              </a>

            </li>
            <?php endforeach; ?>
        </ul>
      </div>
      <div>
        <input type="text" id="addFolderInput" style='width: 65%;margin-left:3%' placeholder="Add New Folder"/>
        <button id="addFolderBtn" class="btn clickable"><i class="fa fa-plus-circle"></i></button>
      </div>
    </div>
    <div class="view">
      <div class="viewHeader">
      <div class="title" style="width: 50%;">
        <input type="text" id="taskNameInput" style="width: 100%;margin-left:3%;line-height: 30px;" placeholder="Add New Task">
        </div>
        <div class="functions"style="width: 50%;">
          <div class="button" style="background-color: #ffffff;"><input type="time" id="time-input" style="width: 100%;margin-left:3%;line-height: 30px;"></div>
          <div class="button " style="background-color: #ffffff;"><input type="date"id="date-input"  style="width: 100%;margin-left:3%;line-height: 30px;"></div>
        </div>
      </div>
      <div class="content">
        <div class="list">
          <div class="title">Today</div>
          <ul class="task-list">
            <?php if(sizeof($tasks)):?>
          <?php foreach($tasks as $task): ?>
            
            <li class="<?= liSwtcherClass($task->status); ?>">
              <i id="tiggleDone" data-taskstatus="<?= $task->status ?>" data-taskid="<?= $task->id ?>" class="<?= iconSwtcher($task->status); ?>"></i>
              <span><?= $task->title;?></span>
              <div class="info">
                <span class="task-time"><?= $task->task_time == "0000-00-00 00:00:00" ? "NOT SET" : $task->task_time; ?></span>
                <a style="color: #a3a4a3;" href="?delete_task=<?= $task->id ?>" class="trash" onclick="return confirm('Are You Sure to delete this Item?\n<?=$task->title?>');">
                <i class="fa fa-trash-o trash"></i>
                </a>
              </div>
            </li>
            <?php endforeach; ?>
            <?php else: ?>
              <li><span>No task Here</span></li>
            <?php endif; ?>

          </ul>
        </div>
        <!-- <div class="list">
          <div class="title">Tomorrow</div>
          <ul>
          <li><i class="fa fa-square-o"></i><span>Design a new logo</span>
              <div class="info">
                <div class="button">Pending</div><span>Complete by 10/04/2014</span>
              </div>
            </li>
            <li><i class="fa fa-square-o"></i><span>Find a front end developer</span>
              <div class="info"><div class="button green">In progress</div></div>
            </li>
            <li><i class="fa fa-square-o"></i><span>Find front end developer</span>
              <div class="info"></div>
            </li>
          </ul>
        </div> -->
      </div>
    </div>
  </div>
</div>
<!-- partial -->
  <script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script><script  src="http://localhost/todo-expert/assets/js/script.js"></script>
  <script>
  $(document).ready(function(){

    $('input#taskNameInput').keypress(function(e) {
      if (e.which == 13) {
          const taskTitle = $('input#taskNameInput').val().trim();
          const taskTime = $('input#time-input').val().trim();
          const taskDate = $('input#date-input').val().trim();
          if (taskTitle === "") {
              alert("لطفاً یک نام وارد کنید.");
              return;
          }
        let dateTime = null;
        if (taskDate && taskTime) {
            dateTime = `${taskDate} ${taskTime}:00`;
        }else {
            dateTime = "N/A"; // مقدار پیش‌فرض
        }

          $.ajax({
              url: "process/ajax_Handler.php",
              method: "POST",
              data: {
                  "action": "add_task",
                  "title": taskTitle,
                  "folder_id": <?= $_GET['folder_id'] ?? 0 ?>,
                  "task_time" : taskTime,
                  "task_date" : taskDate
              },
              success: function(response) {
              
                  if (!isNaN(response) && response > 0) {
                      const taskId = response;
                      const sanitizedTitle = $('<div>').text(taskTitle).html();
                      const sanitizedDateTime = $('<div>').text(dateTime).html();
                      const taskItem = `
                        <li>
                            <i class="clickable fa fa-square-o"></i>
                            <span>${sanitizedTitle}</span>
                            <div class="info">
                                <span class="task-time">${sanitizedDateTime}</span>
                                <a style="color: #a3a4a3;" href="?delete_task=${taskId}" class="trash" onclick="return confirm('آیا از حذف این وظیفه مطمئن هستید؟\n${sanitizedTitle}');">
                                    <i class="fa fa-trash-o trash"></i>
                                </a>
                            </div>
                        </li>`;
                      $(taskItem).appendTo("ul.task-list");
                  } else {
                      alert(response);
                  }
              }
          });
      }
    });

  });
  </script>
</body>
</html>
