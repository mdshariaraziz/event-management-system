
<?php

session_start();
include("config.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);


$user_id = 0; // guest user
$user_name = '';
$user_email = '';

// If logged in, auto fill data
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['name'] ?? '';
    $user_email = $_SESSION['email'] ?? '';
}
$vendors = [
    "vendor1" => [
        "name" => "ShutterMagic Photography",
        "category" => "Photographer",
        "image" => "images/pic-10.jpg",
        "price" => "$299",
        "rating" => 4.8,
        "reviews" => 124,
        "experience" => "3+ years"
    ],
    "vendor2" => [
        "name" => "Gourmet Delights Catering",
        "category" => "Caterer",
        "image" => "images/pic-11.jpg",
        "price" => "$15/person",
        "rating" => 5.0,
        "reviews" => 89,
        "experience" => "500+ events"
    ],
    "vendor3" => [
        "name" => "Elegant Decorators",
        "category" => "Decorator",
        "image" => "images/pic-12.jpg",
        "price" => "$499",
        "rating" => 4.9,
        "reviews" => 210,
        "experience" => "8+ years"
    ],
    "vendor4" => [
        "name" => "BeatBlast DJ & Sound",
        "category" => "DJ/Sound",
        "image" => "images/pic-13.jpg",
        "price" => "$399",
        "rating" => 4.7,
        "reviews" => 67,
        "experience" => "200+ events"
    ]
];


$vendor_id = $_GET['id'] ?? '';
$vendor = $vendors[$vendor_id] ?? null;

if (!$vendor) {
    die("<div style='font-family:Inter; text-align:center; padding:50px;'><h2>Vendor not found</h2><a href='index.php#marketplace'>← Back to Marketplace</a></div>");
}


$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Sanitize inputs
    $customer_name = trim($_POST['fullName'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $phone         = trim($_POST['phone'] ?? '');
    $event_date    = $_POST['eventDate'] ?? '';
    $event_type    = $_POST['eventType'] ?? '';
    $requirements  = trim($_POST['requirements'] ?? '');
    $vendor_name   = $vendor['name'];

   
    if (empty($customer_name) || empty($email) || empty($phone) || empty($event_date) || empty($event_type)) {
        $error_msg = "Please fill all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Invalid email address.";
    } else {
       
        $sql = "INSERT INTO vendor_hire_requests 
                (user_id, vendor_id, vendor_name, customer_name, email, phone, event_date, event_type, requirements, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("issssssss", $user_id, $vendor_id, $vendor_name, $customer_name, $email, $phone, $event_date, $event_type, $requirements);
            if ($stmt->execute()) {
                $success_msg = "Hire request submitted successfully!";
                // Redirect after 2 seconds (or instantly with alert)
                echo "<script>alert('$success_msg'); window.location='index.php#marketplace';</script>";
                exit();
            } else {
                $error_msg = "Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error_msg = "Failed to prepare statement: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hire Vendor | Eventify</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            padding: 40px 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .card {
            background: white;
            border-radius: 32px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        }
        h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        .vendor-detail {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
            align-items: center;
        }
        .vendor-image {
            flex: 1;
            min-width: 150px;
        }
        .vendor-image img {
            width: 100%;
            border-radius: 24px;
            object-fit: cover;
        }
        .vendor-info {
            flex: 2;
        }
        .rating {
            color: #fbbf24;
            margin: 0.5rem 0;
        }
        .price {
            font-size: 1.4rem;
            font-weight: 800;
            color: #5D3FD3;
            margin: 1rem 0;
        }
        .form-group {
            margin-bottom: 1.2rem;
        }
        label {
            font-weight: 600;
            display: block;
            margin-bottom: 0.4rem;
        }
        input, textarea, select {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            font-family: inherit;
            transition: 0.2s;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #5D3FD3;
            box-shadow: 0 0 0 3px rgba(93,63,211,0.1);
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .btn-submit {
            background: #5D3FD3;
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 60px;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            font-size: 1rem;
            transition: 0.2s;
        }
        .btn-submit:hover {
            background: #4c33b0;
            transform: translateY(-2px);
        }
        .back-link {
            display: inline-block;
            margin-top: 1rem;
            color: #5D3FD3;
            text-decoration: none;
            font-weight: 500;
        }
        .alert {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px;
            border-radius: 20px;
            margin-bottom: 1rem;
            border-left: 4px solid #dc2626;
        }
        .alert-success {
            background: #dcfce7;
            color: #15803d;
            border-left-color: #15803d;
        }
        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #eef2ff;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1><i class="fas fa-handshake"></i> Hire a Vendor</h1>
        <p style="color:#64748b; margin-bottom: 1rem;">Fill out the form to request this vendor for your event.</p>
        <hr>

        <!-- Vendor Info (PHP rendered) -->
        <div class="vendor-detail">
            <div class="vendor-image">
                <img src="<?= htmlspecialchars($vendor['image']) ?>" alt="<?= htmlspecialchars($vendor['name']) ?>">
            </div>
            <div class="vendor-info">
                <h2><?= htmlspecialchars($vendor['name']) ?></h2>
                <div class="rating">
                    <?php 
                    $full_stars = floor($vendor['rating']);
                    $half = ($vendor['rating'] - $full_stars) >= 0.5;
                    for($i=1; $i<=5; $i++){
                        if($i <= $full_stars) echo '★';
                        elseif($half && $i == $full_stars+1) echo '½';
                        else echo '☆';
                    }
                    ?>
                    <span> <?= $vendor['rating'] ?> (<?= $vendor['reviews'] ?> reviews)</span>
                </div>
                <p><strong>Category:</strong> <?= htmlspecialchars($vendor['category']) ?></p>
                <p><strong>Experience:</strong> <?= htmlspecialchars($vendor['experience']) ?></p>
                <div class="price">Starting at <?= htmlspecialchars($vendor['price']) ?></div>
            </div>
        </div>

    
        <?php if($error_msg): ?>
            <div class="alert"><?= htmlspecialchars($error_msg) ?></div>
        <?php endif; ?>
        <?php if($success_msg): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_msg) ?></div>
        <?php endif; ?>

    
        <form method="POST">
            <div class="form-group">
                <label>Your Full Name *</label>
                <input type="text" name="fullName" value="<?= htmlspecialchars($user_name) ?>" required>
            </div>

            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user_email) ?>" required>
            </div>

            <div class="form-group">
                <label>Phone Number *</label>
                <input type="text" name="phone" placeholder="e.g., 017XXXXXXXX" required>
            </div>

            <div class="form-group">
                <label>Event Date *</label>
                <input type="date" name="eventDate" required>
            </div>

            <div class="form-group">
                <label>Event Type *</label>
                <select name="eventType" required>
                    <option value="">Select</option>
                    <option value="Wedding">Wedding</option>
                    <option value="Birthday">Birthday</option>
                    <option value="Corporate">Corporate Event</option>
                    <option value="Concert">Concert</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label>Special Requirements (optional)</label>
                <textarea name="requirements" placeholder="Tell us any specific needs..."></textarea>
            </div>

            <button type="submit" name="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> Submit Hire Request
            </button>
        </form>

        <a href="index.php#marketplace" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Marketplace
        </a>
    </div>
</div>


<script>

    const dateInput = document.querySelector('input[name="eventDate"]');
    if(dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    }
</script>
</body>
</html>