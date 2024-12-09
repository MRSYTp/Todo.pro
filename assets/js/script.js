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
                alert("تسک بسته شده است ! ");
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
});
