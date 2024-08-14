<?php
include 'assets/includes/config.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';
$value = isset($_GET['value']) ? $_GET['value'] : '';

if ($type && $value) {
    if ($type === 'reg_number') {
        // Display marks and booklets for a specific registration number
        $sql = "SELECT DISTINCT students.reg_number, students.name, marks.exam_marks, marks.cat_marks, courses.course_code, courses.course_name, booklets.booklet_number
                FROM students
                JOIN marks ON students.id = marks.student_id
                JOIN courses ON marks.course_id = courses.id
                JOIN booklets ON marks.course_id = courses.id
                WHERE students.reg_number = ?";
    } elseif ($type === 'course') {
        // Display all students and booklets for a specific course
        $sql = "SELECT DISTINCT students.reg_number, students.name, marks.exam_marks, marks.cat_marks, courses.course_code, courses.course_name, booklets.booklet_number
                FROM courses
                JOIN marks ON courses.id = marks.course_id
                JOIN students ON marks.student_id = students.id
                JOIN booklets ON marks.course_id = courses.id
                WHERE courses.course_code = ?";
    } elseif ($type === 'class') {
        // Display all students and booklets for a specific class
        $sql = "SELECT DISTINCT students.reg_number, students.name, marks.exam_marks, marks.cat_marks, classes.class_name, booklets.booklet_number
                FROM students
                JOIN marks ON students.id = marks.student_id
                JOIN classes ON students.class = classes.id
                JOIN booklets ON marks.course_id = courses.id
                WHERE classes.class_name = ?";
    } else {
        echo "Invalid search type.";
        exit();
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $value);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Details</h2>
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Reg Number</th>
                        <th>Name</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Booklet Number</th>
                        <th>Exam Marks</th>
                        <th>CAT Marks</th>
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
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No details found.</p>
        <?php endif; ?>
        <?php $stmt->close(); $conn->close(); ?>
    </div>
</body>
</html>
