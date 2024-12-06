<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Task manager UI</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
<!-- partial:index.partial.html -->
<div class="page">
  <div class="pageHeader">
    <div class="title">Dashboard</div>
    <div class="userPanel"><i class="fa fa-chevron-down"></i><span class="username">John Doe </span></div>
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
              <a href="?delete_folder=<?= $folder->id ?>" class="trash" onclick="return confirm('Are You Sure to delete this Item?\n<?=$folder->name?>');">
                <i class="fa fa-trash-o trash"></i>
              </a>

            </li>
            <?php endforeach; ?>
        </ul>
      </div>
      <div>
        <input type="text" id="addFolderInput" style='width: 65%;margin-left:3%' placeholder="Add New Folder"/>
        <button id="addFolderBtn" class="btn clickable">+</button>
      </div>
    </div>
    <div class="view">
      <div class="viewHeader">
      <div class="title" style="width: 50%;">
        <input type="text" id="taskNameInput" style="width: 100%;margin-left:3%;line-height: 30px;" placeholder="Add New Task">
        </div>
        <div class="functions">
          <div class="button active">Add New Task</div>
          <div class="button">Completed</div>
          <div class="button inverz"><i class="fa fa-trash-o"></i></div>
        </div>
      </div>
      <div class="content">
        <div class="list">
          <div class="title">Today</div>
          <ul class="task-list">
            <?php if(sizeof($tasks)):?>
          <?php foreach($tasks as $task): ?>
            <li class="<?= $task->status == "complete" ? "checked" : "" ;?>">
              <i id="tiggleDone" data-taskstatus="<?= $task->status ?>" data-taskid="<?= $task->id ?>" class="clickable <?= $task->status == "complete" ? "fa fa-check-square-o" : "fa fa-square-o" ;?>"></i>
              <span><?= $task->title;?></span>
              <div class="info">
                <span class="task-time"><?= $task->task_time; ?></span>
                <a href="?delete_task=<?= $task->id ?>" class="trash" onclick="return confirm('Are You Sure to delete this Item?\n<?=$task->title?>');">
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
        <div class="list">
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
        </div>
      </div>
    </div>
  </div>
</div>
<!-- partial -->
  <script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script><script  src="assets/js/script.js"></script>
  <script>
  $(document).ready(function(){
    
            $("i#tiggleDone").click(function (e) {
            e.preventDefault();

            // دریافت مقادیر در هنگام کلیک
            var $icon = $(this);
            var tid = $icon.attr("data-taskid");
            var taskStatus = $icon.attr("data-taskstatus");

            // ارسال درخواست AJAX
            $.ajax({
                url: "process/ajax_Handler.php",
                method: "POST",
                data: {
                    "action": "task_complete",
                    "taskid": tid,
                    "status": taskStatus
                },
                success: function (response) {
                    // بررسی پاسخ و اعمال تغییرات
                    if (response.trim() === "complete") {
                        // تغییر وضعیت به "complete"
                        $icon
                            .attr("data-taskstatus", "complete")
                            .removeClass("fa-square-o")
                            .addClass("fa-check-square-o");

                        $icon.closest("li")
                            .addClass("checked");
                    } else if (response.trim() === "in-progress") {
                        // تغییر وضعیت به "progress"
                        $icon
                            .attr("data-taskstatus", "in-progress")
                            .removeClass("fa-check-square-o")
                            .addClass("fa-square-o");

                        $icon.closest("li")
                            .removeClass("checked");
                    } else {
                        alert("Unexpected response: " + response);
                    }
                },
                error: function () {
                    alert("An error occurred while processing the request.");
                }
            });
        });


      var input = $("input#addFolderInput");
      $("#addFolderBtn").click(function(e){
          $.ajax({
            url : "process/ajax_Handler.php",
            method : "POST", 
            data : {"action" : "add_folder" , "name" : input.val()},
            success : function(response){
              if (response > 0) {
                const folderId = response; 
                const folderName = input ? input.val() : "Unnamed Folder";
                const listItem = `
                    <li>
                        <a href="?folder_id=${folderId}" style="text-decoration: none; color: #1f6674;">
                            <i class="fa fa-folder"></i> ${folderName}
                        </a>
                        <a href="?delete_folder=${folderId}" class="trash">
                            <i class="fa fa-trash-o trash"></i>
                        </a>
                    </li>`;
                $(listItem).appendTo("ul.folder-list");
              }
            } 
          });
      });

        $('input#taskNameInput').keypress(function(e) {
      if (e.which == 13) {
          const taskTitle = $('input#taskNameInput').val().trim();
          if (taskTitle === "") {
              alert("لطفاً یک نام وارد کنید.");
              return;
          }

          $.ajax({
              url: "process/ajax_Handler.php",
              method: "POST",
              data: {
                  "action": "add_task",
                  "title": taskTitle,
                  "folder_id": <?= $_GET['folder_id'] ?? 0 ?>
              },
              success: function(response) {
                  if (!isNaN(response) && response > 0) {
                      const taskId = response;
                      const sanitizedTitle = $('<div>').text(taskTitle).html();
                      const taskItem = `
                          <li>
                              <i class="clickable fa fa-square-o"></i>
                              <span>${sanitizedTitle}</span>
                              <div class="info">
                                  <span class="task-time">تایم مشخص نشده</span>
                                  <a href="?delete_task=${taskId}" class="trash" onclick="return confirm('آیا از حذف این وظیفه مطمئن هستید؟\n${sanitizedTitle}');">
                                      <i class="fa fa-trash-o trash"></i>
                                  </a>
                              </div>
                          </li>`;
                      $(taskItem).appendTo("ul.task-list");
                  } else {
                      alert("مشکلی پیش آمد. لطفاً دوباره تلاش کنید.");
                  }
              }
          });
      }
    });

  });
  </script>
</body>
</html>
