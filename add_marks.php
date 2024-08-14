<?php
include 'assets/templates/header.php';
include 'assets/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reg_number = $_POST['reg_number'];
    $course_code = $_POST['course_code'];
    $booklet_number = $_POST['booklet_number'];
    $exam_marks = $_POST['exam_marks'];
    $cat_marks = $_POST['cat_marks'];
    $exam_date = $_POST['exam_date'];  // Capture the exam date

    // First, add the booklet
    $sql_booklet = "INSERT INTO booklets (booklet_number, student_id, course_id)
                    SELECT '$booklet_number', s.id, c.id
                    FROM students s, courses c
                    WHERE s.reg_number = '$reg_number' AND c.course_code = '$course_code'";
    
    if ($conn->query($sql_booklet) === TRUE) {
        $booklet_id = $conn->insert_id;

        // Now, add the marks including exam_date
        $sql_marks = "INSERT INTO marks (student_id, course_id, exam_marks, cat_marks, booklet_id, exam_date)
                      SELECT s.id, c.id, '$exam_marks', '$cat_marks', '$booklet_id', '$exam_date'
                      FROM students s, courses c
                      WHERE s.reg_number = '$reg_number' AND c.course_code = '$course_code'";

        if ($conn->query($sql_marks) === TRUE) {
            echo "<div class='alert alert-success'>Marks added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error adding marks: " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error adding booklet: " . $conn->error . "</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Student</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
    <h2>Add Marks</h2>
    <form method="post">
        <div class="mb-3">
            <label for="reg_number" class="form-label">Registration Number</label>
            <input type="text" class="form-control" id="reg_number" name="reg_number" required>
        </div>
        <div class="mb-3">
            <label for="course_code" class="form-label">Course Code</label>
            <input type="text" class="form-control" id="course_code" name="course_code" required>
        </div>
        <div class="mb-3">
            <label for="booklet_number" class="form-label">Booklet Number</label>
            <input type="text" class="form-control" id="booklet_number" name="booklet_number" required>
        </div>
        <div class="mb-3">
            <label for="exam_marks" class="form-label">Exam Marks</label>
            <input type="number" class="form-control" id="exam_marks" name="exam_marks" required>
        </div>
        <div class="mb-3">
            <label for="cat_marks" class="form-label">CAT Marks</label>
            <input type="number" class="form-control" id="cat_marks" name="cat_marks" required>
        </div>
        <div class="mb-3">
            <label for="exam_date" class="form-label">Exam Date</label>
            <input type="date" class="form-control" id="exam_date" name="exam_date" required>
        </div>
        <button type="submit" class="btn btn-custom text-white">Add Marks</button>
    </form>
</div>
</body>
</html>



<?php include 'assets/templates/footer.php'; ?>
