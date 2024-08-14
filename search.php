<?php
include 'assets/includes/config.php';
include 'assets/templates/header.php';

// Fetch classes for the class dropdown
$classes_sql = "SELECT class_name FROM classes";
$classes_result = $conn->query($classes_sql);

// Fetch semesters for the semester dropdown
$semesters_sql = "SELECT id, semester_name FROM semesters";
$semesters_result = $conn->query($semesters_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Search</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function validateForm() {
            var className = document.getElementById("class_name").value;
            var semesterId = document.getElementById("semester_id").value;

            if (!className && !semesterId) {
                // If neither class nor semester is selected
                alert("Please select at least one filter (Class or Semester) to perform the search.");
                return false;
            } else if (!className) {
                // If only semester is selected
                alert("Please select a Class before submitting.");
                return false;
            } else if (!semesterId) {
                // If only class is selected
                alert("Please select a Semester before submitting.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="container mt-4">
        <form action="search_results.php" method="GET" onsubmit="return validateForm()">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="reg_number" class="form-label">Registration Number</label>
                        <input type="text" class="form-control" id="reg_number" name="reg_number" placeholder="e.g COM/0047/21">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="course_code" class="form-label">Course Code</label>
                        <input type="text" class="form-control" id="course_code" name="course_code" placeholder="e.g CSC 310">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="class_name" class="form-label">Class Name</label>
                        <select class="form-control" id="class_name" name="class_name">
                            <option value="">Select Class</option>
                            <?php
                            if ($classes_result->num_rows > 0) {
                                while ($row = $classes_result->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($row['class_name']) . "'>" . htmlspecialchars($row['class_name']) . "</option>";
                                }
                            } else {
                                echo "<option value=''>No classes available</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="semester_id" class="form-label">Semester</label>
                        <select class="form-control" id="semester_id" name="semester_id">
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
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exam_date" class="form-label">Exam Date</label>
                        <input type="date" class="form-control" id="exam_date" name="exam_date">
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-custom text-white mt-4">Search</button>
                </div>
            </div>
        </form>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
