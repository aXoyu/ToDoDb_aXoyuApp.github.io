<?php
session_start(); // Start session

if (!isset($_SESSION['id'])) {
    header("Location: index.php"); // Redirect to index if user is not logged in
    exit();
}

include 'dbconn.php';

// Insert data
if (isset($_POST['add'])) {
    $id = $_SESSION['id']; // Get user ID from session
    $task = trim($_POST['task']);
    if (!empty($task)) {
        $q_insert = "INSERT INTO tasks (tasklabel, taskstatus, id) VALUES (?, 'open', ?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $q_insert)) {
            echo "Failed to add data: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($stmt, "si", $task, $id);
            mysqli_stmt_execute($stmt);
            header('Refresh:0; url=index.php');
            exit();
        }
    }
}

// Show data
$id = $_SESSION['id']; // Get user ID from session
$q_select = "SELECT * FROM tasks WHERE id = ? ORDER BY taskid DESC";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $q_select)) {
    echo "Database error!";
} else {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $run_q_select = mysqli_stmt_get_result($stmt);
}

// Delete data
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $q_delete = "DELETE FROM tasks WHERE taskid = ? AND id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $q_delete)) {
        echo "Failed to delete data: " . mysqli_error($conn);
    } else {
        mysqli_stmt_bind_param($stmt, "ii", $delete_id, $id);
        mysqli_stmt_execute($stmt);
        header('Refresh:0; url=index.php');
        exit();
    }
}

// Update data close or open
if (isset($_GET['done'])) {
    $status = ($_GET['status'] == 'open') ? 'close' : 'open';
    $update_id = $_GET['done'];

    $q_update = "UPDATE tasks SET taskstatus = ? WHERE taskid = ? AND id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $q_update)) {
        echo "Failed to update data: " . mysqli_error($conn);
    } else {
        mysqli_stmt_bind_param($stmt, "sii", $status, $update_id, $id);
        mysqli_stmt_execute($stmt);
        // header('Refresh:0; url=index.php');
        // exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List w Database V3</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <style>
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
</head>
<body>
<div class="container">
        <div class="header">
            <div class="title">
                <i class='bx bx-sun'></i>
                <span>To Do List</span>
            </div>
            <div class="description">
                <?= date("l, d M Y") ?>
            </div>
        </div>
        <div class="content">
            <div class="card">
                <form action="" method="post" onsubmit="return validateForm()">
                    <input type="text" name="task" class="input-control" placeholder="Tambahkan Rencana Anda" autocomplete="off">
                    <div class="text-right">
                        <button type="submit" name="add">Tambah</button>
                        <a href="logout.php" class="btn btn-warning">Logout</a>
                    </div>
                </form>

            </div>

            <?php
            if ($run_q_select && mysqli_num_rows($run_q_select) > 0) {
                while ($r = mysqli_fetch_array($run_q_select)) {
                    ?>
                    <div class="card">
                        <div class="task-item <?= $r['taskstatus'] == 'close' ? 'done' : '' ?>"
                            data-taskid="<?= $r['taskid'] ?>">
                            <div>
                                <input type="checkbox" onclick="updateStatus(<?= $r['taskid'] ?>, '<?= $r['taskstatus'] ?>')"
                                    <?= $r['taskstatus'] == 'close' ? 'checked' : '' ?>>
                                <span>
                                    <?= $r['tasklabel'] ?>
                                </span>
                            </div>
                            <div>
                                <a href="edit.php?id=<?= $r['taskid'] ?>" class="text-orange" title="edit"><i
                                        class="bx bx-edit"></i></a>
                                <a href="?delete=<?= $r['taskid'] ?>" class="text-red" title="remove"
                                    onclick="return confirm('Yakin ingin menghapus rencana?')"><i class="bx bx-trash"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <!-- <div>Isi rencana terlebih dahulu</div> -->
                <?php
            }
            ?>

        </div>
    </div>

    <script>
        function updateStatus(taskId, currentStatus) {
            // Kirim permintaan AJAX ke server untuk memperbarui status tugas
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "?done=" + taskId + "&status=" + currentStatus, true);
            xhr.onreadystatechange = function () {
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

        
            // Fungsi untuk menampilkan prompt hanya saat data kosong pada saat submit
            function validateForm() {
            var taskInput = document.querySelector("[name='task']").value.trim();
            if (taskInput === '') {
            alert('Input tidak boleh kosong!');
            return false; // Mencegah formulir dikirim jika data kosong
         }
            return true; // Biarkan formulir dikirim jika data tidak kosong

        }
    </script>
</body>
</html>
