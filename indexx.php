<?php
include "db.php";

// ADD
if (isset($_POST['add'])) {
    $subject = $_POST['subject'];
    $date = $_POST['date'];

    mysqli_query($conn, "INSERT INTO exams (subject, exam_date) VALUES ('$subject', '$date')");
}

// DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM exams WHERE id=$id");
}

// EDIT (load data)
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM exams WHERE id=$id");
    $editData = mysqli_fetch_assoc($result);
}

// UPDATE
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $subject = $_POST['subject'];
    $date = $_POST['date'];

    mysqli_query($conn, "UPDATE exams SET subject='$subject', exam_date='$date' WHERE id=$id");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Exam Routine (MySQL)</title>
    <style>
        body { font-family: Arial; background: #eef2f7; padding: 30px; }
        .box { max-width: 500px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
        input, button { width: 100%; padding: 8px; margin-top: 10px; }
        ul { list-style: none; padding: 0; }
        li {
            background: #f0f4ff;
            margin-top: 8px;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            border-left: 5px solid #2a9d8f;
        }
        a { text-decoration: none; margin-left: 8px; }
        .delete { color: red; }
        .edit { color: green; }
    </style>
</head>

<body>

<div class="box">
    <h2>📚 Exam Routine (MySQL)</h2>

    <!-- Add / Edit Form -->
    <form method="POST">
        <input type="text" name="subject" placeholder="Subject name"
               value="<?= $editData['subject'] ?? '' ?>" required>

        <input type="date" name="date"
               value="<?= $editData['exam_date'] ?? '' ?>" required>

        <?php if ($editData): ?>
            <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <button name="update">Update Exam</button>
        <?php else: ?>
            <button name="add">Add Exam</button>
        <?php endif; ?>
    </form>

    <!-- Exam List -->
    <ul>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM exams ORDER BY exam_date ASC");
        while ($row = mysqli_fetch_assoc($result)):
            $days = (strtotime($row['exam_date']) - strtotime(date("Y-m-d"))) / 86400;
        ?>
            <li>
                <span>
                    <strong><?= $row['subject'] ?></strong><br>
                    🗓 <?= $row['exam_date'] ?><br>
                    <small>
                        <?= $days < 0 ? "❌ Expired" : "⏳ ".ceil($days)." days left" ?>
                    </small>
                </span>
                <span>
                    <a class="edit" href="?edit=<?= $row['id'] ?>">✏️</a>
                    <a class="delete" href="?delete=<?= $row['id'] ?>">❌</a>
                </span>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

</body>
</html>
