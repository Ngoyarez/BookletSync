<?php
include 'assets/includes/config.php';
include 'assets/templates/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];
    $class_id = $_POST['class_id'];
    $semester_id = $_POST['semester_id'];

    $sql = "INSERT INTO courses (course_code, course_name, class_id, semester_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssii', $course_code, $course_name, $class_id, $semester_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Course added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Fetch classes for dropdown
$classes_sql = "SELECT id, class_name FROM classes";
$classes_result = $conn->query($classes_sql);

// Fetch semesters for dropdown
$semesters_sql = "SELECT id, semester_name FROM semesters";
$semesters_result = $conn->query($semesters_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Course</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Add Course</h2>
        <form method="post">
            <div class="mb-3">
                <label for="course_code" class="form-label">Course Code</label>
                <input type="text" class="form-control" id="course_code" name="course_code" required>
            </div>
            <div class="mb-3">
                <label for="course_name" class="form-label">Course Name</label>
                <input type="text" class="form-control" id="course_name" name="course_name" required>
            </div>
            <div class="mb-3">
                <label for="class_id" class="form-label">Class</label>
                <select class="form-control" id="class_id" name="class_id" required>
                    <option value="">Select Class</option>
                    <?php
                    if ($classes_result->num_rows > 0) {
                        while ($row = $classes_result->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['class_name']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No classes available</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="semester_id" class="form-label">Semester</label>
                <select class="form-control" id="semester_id" name="semester_id" required>
                    <option value="">Select Semester</option>
                    <?php
                    if ($semesters_result->num_rows > 0) {
                        while ($row = $semesters_result->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['semester_name']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No semesters available</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-custom text-white">Add Course</button>
        </form>
    </div>
    <?php
    $conn->close();
    include 'assets/templates/footer.php';
    ?>
</body>
</html>
