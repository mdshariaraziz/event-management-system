<?php
session_start();
include("config.php");

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirmPassword'];
    $role = $_POST['role'];

    // Validation
    if (empty($fullname) || empty($email) || empty($password) || empty($confirm)) {
        $error = "All fields are required.";
    } elseif (strlen($fullname) < 2) {
        $error = "Name must be at least 2 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();
        if ($check_stmt->num_rows > 0) {
            $error = "Email already registered. Please login.";
        } else {
            // Hash password and insert
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)");
            $insert_stmt->bind_param("ssss", $fullname, $email, $hashed_password, $role);
            if ($insert_stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Database error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Eventify</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Your existing CSS - unchanged */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            max-width: 520px;
            width: 100%;
        }

        .register-card {
            background: white;
            border-radius: 40px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-area {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo-area .logo {
            font-size: 2rem;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #1E2F5E, #5D3FD3);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .logo-area .logo i {
            background: none;
            color: #5D3FD3;
            font-size: 2rem;
        }

        h2 {
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
            color: #0f172a;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }

        .role-selector {
            display: flex;
            gap: 12px;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .role-btn {
            flex: 1;
            min-width: 100px;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 60px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #475569;
        }

        .role-btn i {
            font-size: 1rem;
        }

        .role-btn.active {
            border-color: #5D3FD3;
            background: #f5f3ff;
            color: #5D3FD3;
        }

        .role-btn:hover:not(.active) {
            border-color: #cbd5e1;
            background: #f8fafc;
        }

        .input-group {
            margin-bottom: 1.2rem;
        }

        .input-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: #1e293b;
        }

        .input-group input {
            width: 100%;
            padding: 14px 18px;
            border: 1.5px solid #e2e8f0;
            border-radius: 60px;
            font-size: 0.95rem;
            font-family: inherit;
            transition: 0.2s;
        }

        .input-group input:focus {
            outline: none;
            border-color: #5D3FD3;
            box-shadow: 0 0 0 3px rgba(93, 63, 211, 0.1);
        }

        .register-btn {
            width: 100%;
            padding: 14px;
            background: #5D3FD3;
            color: white;
            border: none;
            border-radius: 60px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 0.5rem;
            font-family: inherit;
        }

        .register-btn:hover {
            background: #4a2fc2;
            transform: translateY(-2px);
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #64748b;
        }

        .login-link a {
            color: #5D3FD3;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 10px;
            border-radius: 60px;
            font-size: 0.8rem;
            text-align: center;
            margin-bottom: 1rem;
            display: none;
        }

        .success-message {
            background: #dcfce7;
            color: #16a34a;
            padding: 10px;
            border-radius: 60px;
            font-size: 0.8rem;
            text-align: center;
            margin-bottom: 1rem;
            display: none;
        }

        .back-home {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-home a {
            color: white;
            text-decoration: none;
            font-size: 0.85rem;
            opacity: 0.8;
            transition: 0.2s;
        }

        .back-home a:hover {
            opacity: 1;
        }

        @media (max-width: 550px) {
            .register-card {
                padding: 1.8rem;
            }
            .role-btn {
                font-size: 0.8rem;
                padding: 10px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="logo-area">
                <a href="index.php" class="logo">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Eventify</span>
                </a>
            </div>
            <h2>Create an account</h2>
            <p class="subtitle">Join as Organizer, Guest or Vendor</p>

            <div class="role-selector">
                <button type="button" class="role-btn" data-role="organizer">
                    <i class="fas fa-chalkboard-user"></i> Organizer
                </button>
                <button type="button" class="role-btn" data-role="guest">
                    <i class="fas fa-user"></i> Guest
                </button>
                <button type="button" class="role-btn" data-role="vendor">
                    <i class="fas fa-store"></i> Vendor
                </button>
            </div>

            <?php if (!empty($error)): ?>
            <div class="error-message" style="display: block;"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
            <div class="success-message" style="display: block;"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="POST" action="" id="registerForm">
                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" id="fullname" placeholder="John Doe" required>
                </div>
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" id="email" placeholder="you@example.com" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                </div>
                <div class="input-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirmPassword" id="confirmPassword" placeholder="••••••••" required>
                </div>
                <button type="submit" name="submit" class="register-btn">Sign Up</button>
                <input type="hidden" name="role" id="role" value="guest">
            </form>

            <div class="login-link">
                Already have an account? <a href="login.php">Sign in</a>
            </div>
        </div>
        <div class="back-home">
            <a href="index.php"><i class="fas fa-home"></i> Back to Home</a>
        </div>
    </div>

    <script>
        // Role selection
        let selectedRole = 'guest';
        const roleBtns = document.querySelectorAll('.role-btn');
        const roleInput = document.getElementById('role');
        const registerForm = document.getElementById('registerForm');
        const errorMsgDiv = document.getElementById('errorMsg');
        const successMsgDiv = document.getElementById('successMsg');

        // Function to show error message (client-side)
        function showError(msg) {
            if (errorMsgDiv) {
                errorMsgDiv.textContent = msg;
                errorMsgDiv.style.display = 'block';
                setTimeout(() => {
                    errorMsgDiv.style.display = 'none';
                }, 4000);
            } else {
                alert(msg);
            }
        }

        // Role button click handler
        roleBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                roleBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                selectedRole = btn.getAttribute('data-role');
                roleInput.value = selectedRole;
            });
        });

        // Set default active: Guest
        document.querySelector('.role-btn[data-role="guest"]').classList.add('active');
        roleInput.value = 'guest';

        // Client-side validation before submitting to PHP
        registerForm.addEventListener('submit', function(e) {
            const fullname = document.getElementById('fullname').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirmPassword').value;

            if (!fullname || !email || !password || !confirm) {
                e.preventDefault();
                showError('All fields are required.');
                return;
            }
            if (fullname.length < 2) {
                e.preventDefault();
                showError('Name must be at least 2 characters.');
                return;
            }
            if (!email.includes('@') || !email.includes('.')) {
                e.preventDefault();
                showError('Enter a valid email address.');
                return;
            }
            if (password.length < 6) {
                e.preventDefault();
                showError('Password must be at least 6 characters.');
                return;
            }
            if (password !== confirm) {
                e.preventDefault();
                showError('Passwords do not match.');
                return;
            }
            // If all client checks pass, form submits to PHP normally
        });
    </script>
</body>
</html>