<?php
include 'dbconn.php';


//select data yang mau di edit
$q_select = "SELECT * FROM tasks WHERE taskid = '".$_GET['id']."' ";
$run_q_select = mysqli_query($conn, $q_select);
$d = mysqli_fetch_object($run_q_select);


//edit data
if(isset ($_POST['edit'])){
    $q_update = "update tasks set tasklabel = '".$_POST['task']."' where taskid = '".$_GET['id']."' ";
    $run_q_update = mysqli_query($conn, $q_update);

    header('Refresh:0; url=todo.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List w Database V3</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="title">
               <a href="todo.php"><i class='bx bx-chevron-left'></i></a>
                <span>Back</span>
            </div>
            <div class="description">
                <?= date("l, d M Y") ?>
            </div>
        </div>
        <div class="content">
            <div class="card">
                <form action="" method="post">
                    <input type="text" name="task" class="input-control" placeholder="Edit Rencana" value="<?= $d->tasklabel?>">
                    <div class="text-right">
                        <button type="submit" name="edit">Edit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        function updateStatus(taskId, currentStatus) {
            // Kirim permintaan AJAX ke server untuk memperbarui status tugas
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "?done=" + taskId + "&status=" + currentStatus, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Perbarui tampilan jika permintaan berhasil
                    var taskItem = document.querySelector("[data-taskid='" + taskId + "']");
                    if (taskItem) {
                        taskItem.classList.toggle("done");
                    }
                }
            };
            xhr.send();
        }
    </script>
</body>

</html>


<style>
    
  @import url('https://fonts.googleapis.com/css2?family=Comic+Neue&family=Dancing+Script:wght@700&family=Poppins:wght@600&family=Roboto:wght@400;500;700&family=Tilt+Neon&display=swap');
  *{
    padding: 0;
    margin: 0;
    box-sizing: border-box;
  }

  body{
    font-family: 'Roboto', sans-serif;
    background: #6190E8;  /* fallback for old browsers */
    background: -webkit-linear-gradient(to right, #A7BFE8, #6190E8);  /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to right, #A7BFE8, #6190E8); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

  }

  .container{
    width: 590px;
    height: 100vh;
    margin: 0 auto;
  }

  .header {
    padding: 15px;
    color : #fff;
  }

  .header .title{
    display: flex;
    align-items: center;
    margin-bottom: 7px;
  }
  .header .title i{
    font-size: 24px;
    margin-right: 10px;
    color: #fff;
  }
  .header .title span{
    font-size: 18px;
  }

  .header .description{
    font-size: 13px;
  }

  .content{
    padding: 15px;
  }

  .card{
    background-color: #fff;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 10px;
  }

  .input-control{
    width: 100%;
    display:block;
    padding: 0.5rem;
    font-size: 1rem;
    margin-bottom: 10px;
  }

  .text-right{
    text-align: right;
  }

  button{
    padding: 0.5rem 1rem;
    font-size: 1rem;
    cursor: pointer;
    background: #6190E8;  /* fallback for old browsers */
    background: -webkit-linear-gradient(to right, #A7BFE8, #6190E8);  /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to right, #A7BFE8, #6190E8); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    color:#fff;
    border: 1px solid;
    border-radius: 3px;
  }

  .task-item{
    display: flex;
    justify-content: space-between;
  }
  .task-item.done span{
    text-decoration: line-through;
    color: #ccc;
  }

  .text-orange{
    color: orange;
  }
  .text-red{
    color: red;
  }


  @media (max-width: 768px) {
  .container {
    width: 100%;
  }
  .header .title {
    font-size: 20px;
  }
  .header .description {
    font-size: 12px;
  }
  .content {
    padding: 10px;
  }
  .card {
    padding: 10px;
    margin-bottom: 8px;
  }
  .input-control {
    margin-bottom: 8px;
  }
  button {
    padding: 0.4rem 0.8rem;
    font-size: 0.9rem;
  }
}


</style>