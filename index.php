<?php 
include 'assets/templates/header.php'; 
include 'assets/includes/config.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to BookletSync</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: url('assets/images/system.jpg') no-repeat center center;
            background-size: cover;
            color: #00144D;
            padding: 180px 0;
            text-align: center;
            border-radius: 8px;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        .hero-section p {
            font-size: 1.25rem;
        }
        .feature-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: box-shadow 0.3s ease;
        }
        .feature-box:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Hero Section -->
        <section class="hero-section mb-4">
            <!-- <h1>Welcome to BookletSync</h1> -->
            <!-- <p>Your solution for managing exam booklets and marks.</p> -->
        </section>

        <!-- Features Section -->
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="feature-box bg-light">
                    <h2 class="h4">Manage Students</h2>
                    <p>Efficiently manage student records and track their performance.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-box bg-light">
                    <h2 class="h4">Track Marks</h2>
                    <p>Keep track of exam and CAT marks with ease.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-box bg-light">
                    <h2 class="h4">Generate Reports</h2>
                    <p>Create detailed reports and export data as needed.</p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center my-4">
            <a href="search.php" class="btn btn-custom text-white btn-lg">Find Student Marks</a>
        </div>
    </div>

    <?php include 'assets/templates/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

