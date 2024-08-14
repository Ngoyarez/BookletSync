<?php
include 'assets/includes/config.php';
include 'assets/templates/header.php';

$reg_number = isset($_GET['reg_number']) ? $_GET['reg_number'] : '';
$course_code = isset($_GET['course_code']) ? $_GET['course_code'] : '';
$class_name = isset($_GET['class_name']) ? $_GET['class_name'] : '';
$exam_date = isset($_GET['exam_date']) ? $_GET['exam_date'] : '';
$semester_id = isset($_GET['semester_id']) ? $_GET['semester_id'] : '';

if (!$class_name || !$semester_id) {
    echo "<div class='alert alert-warning'>Please select both Class and Semester to view results.</div>";
    exit;
}

// Fetch semester name for display
$semester_sql = "SELECT semester_name FROM semesters WHERE id = ?";
$semester_stmt = $conn->prepare($semester_sql);
$semester_stmt->bind_param('i', $semester_id);
$semester_stmt->execute();
$semester_result = $semester_stmt->get_result();
$semester_name = $semester_result->num_rows > 0 ? $semester_result->fetch_assoc()['semester_name'] : 'Unknown Semester';
$semester_stmt->close();

// Prepare SQL query
$sql = "SELECT DISTINCT students.reg_number, students.name, courses.course_code, courses.course_name, booklets.booklet_number, marks.exam_marks, marks.cat_marks, classes.class_name, marks.exam_date,
               CASE 
                   WHEN (marks.exam_marks + marks.cat_marks) >= 70 THEN 'A'
                   WHEN (marks.exam_marks + marks.cat_marks) >= 60 THEN 'B'
                   WHEN (marks.exam_marks + marks.cat_marks) >= 50 THEN 'C'
                   WHEN (marks.exam_marks + marks.cat_marks) >= 40 THEN 'D'
                   ELSE 'F'
               END AS grade
        FROM students
        JOIN marks ON students.id = marks.student_id
        JOIN courses ON marks.course_id = courses.id
        JOIN booklets ON booklets.course_id = courses.id
        JOIN classes ON classes.class_name = students.class_name
        JOIN semesters ON semesters.id = courses.semester_id
        WHERE 1=1";

$params = [];
$types = '';

if ($reg_number) {
    $sql .= " AND students.reg_number = ?";
    $params[] = $reg_number;
    $types .= 's';
}

if ($course_code) {
    $sql .= " AND courses.course_code = ?";
    $params[] = $course_code;
    $types .= 's';
}

if ($class_name) {
    $sql .= " AND classes.class_name = ?";
    $params[] = $class_name;
    $types .= 's';
}

if ($exam_date) {
    $sql .= " AND marks.exam_date = ?";
    $params[] = $exam_date;
    $types .= 's';
}

$sql .= " AND semesters.id = ?";
$params[] = $semester_id;
$types .= 'i';

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container mt-4">
        <h5>
            <?php
            $searchQuery = "Search Results";
            if ($reg_number) {
                $searchQuery .= " for Registration Number: " . htmlspecialchars($reg_number);
            }
            if ($course_code) {
                $searchQuery .= " for Course Code: " . htmlspecialchars($course_code);
            }
            if ($class_name) {
                $searchQuery .= " for Class: " . htmlspecialchars($class_name);
            }
            if ($semester_name) {
                $searchQuery .= " Semester: " . htmlspecialchars($semester_name);
            }
            if ($exam_date) {
                $searchQuery .= " for Exam Date: " . htmlspecialchars($exam_date);
            }
            echo $searchQuery;
            ?>
        </h5>

        <!-- Button to Generate PDF Report -->
        <?php if ($result->num_rows > 0): ?>
            <form action="generate_report.php" method="get">
                <input type="hidden" name="reg_number" value="<?php echo htmlspecialchars($reg_number); ?>">
                <input type="hidden" name="course_code" value="<?php echo htmlspecialchars($course_code); ?>">
                <input type="hidden" name="class_name" value="<?php echo htmlspecialchars($class_name); ?>">
                <input type="hidden" name="exam_date" value="<?php echo htmlspecialchars($exam_date); ?>">
                <input type="hidden" name="semester_id" value="<?php echo htmlspecialchars($semester_id); ?>">
                <button type="submit" class="btn btn-primary mb-3">Generate Report</button>
            </form>

            <table id="results_table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Registration Number</th>
                        <th>Name</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Booklet Number</th>
                        <th>Exam Marks</th>
                        <th>CAT Marks</th>
                        <th>Total Marks</th>
                        <th>Grade</th>
                        <th>Class</th>
                        <th>Exam Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['reg_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['booklet_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['exam_marks']); ?></td>
                            <td><?php echo htmlspecialchars($row['cat_marks']); ?></td>
                            <td><?php echo htmlspecialchars($row['exam_marks'] + $row['cat_marks']); ?></td>
                            <td><?php echo htmlspecialchars($row['grade']); ?></td>
                            <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['exam_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No results found for the selected criteria.</p>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#results_table').DataTable();
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
