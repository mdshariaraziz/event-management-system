<?php
session_start();
include("config.php");

$error = "";
$redirect = $_GET['redirect'] ?? 'index.php';

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND role=?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){

        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){

            // সেশনে সব প্রয়োজনীয় তথ্য রাখুন
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];   // ← ইমেইল যোগ করুন
            $_SESSION['role'] = $user['role'];

            header("Location: $redirect");
            exit();

        } else {
            $error = "Wrong Password!";
        }

    } else {
        $error = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Eventify</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
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

        .login-container {
            max-width: 480px;
            width: 100%;
        }

        .login-card {
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

        /* Role selection buttons */
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

        .login-btn {
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

        .login-btn:hover {
            background: #4a2fc2;
            transform: translateY(-2px);
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #64748b;
        }

        .register-link a {
            color: #5D3FD3;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
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

        @media (max-width: 500px) {
            .login-card {
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
    <div class="login-container">
        <div class="login-card">
            <div class="logo-area">
                <a href="index.php" class="logo">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Eventify</span>
                </a>
            </div>
            <h2>Welcome back</h2>
            <p class="subtitle">Sign in to your account</p>

            <div class="role-selector">
                <button class="role-btn" data-role="organizer">
                    <i class="fas fa-chalkboard-user"></i> Organizer
                </button>
                <button class="role-btn" data-role="guest">
                    <i class="fas fa-user"></i> Guest
                </button>
                <button class="role-btn" data-role="vendor">
                    <i class="fas fa-store"></i> Vendor
                </button>
                
            </div>
            
            <div class="error-message" id="errorMsg"></div>
            <?php if($error!=""){ ?>
                <div class="error-message" style="display:block;">
                    <?php echo $error; ?>
                </div>
                <?php } ?>
            <form method="POST" action="" id="loginForm">
                <input type="hidden" name="role" id="roleInput" value="guest">
                <div class="input-group">
                    <label>Email Address</label>
                   <input type="email" name="email" id="email"  placeholder="you@example.com" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                </div>
                <button type="submit" name="login" class="login-btn">Sign In <i class="fas fa-arrow-right"></i></button>
            </form>

            <div class="register-link">
                Don't have an account? <a href="register.php" id="registerLink">Create one</a>
            </div>
        </div>
        <div class="back-home">
            <a href="index.php"><i class="fas fa-home"></i> Back to Home</a>
        </div>
    </div>

    <script>
        // Role selection handling
        let selectedRole = 'guest'; // default

        const roleBtns = document.querySelectorAll('.role-btn');
        const errorMsgDiv = document.getElementById('errorMsg');
        const loginForm = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        roleBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                roleBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                selectedRole = btn.getAttribute('data-role');
            });
        });

        // Set default active role (Guest)
        document.querySelector('.role-btn[data-role="guest"]').classList.add('active');

        // Handle login
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const email = emailInput.value.trim();
            const password = passwordInput.value.trim();

            if (!email || !password) {
                showError('Please fill in both email and password.');
                return;
            }

            if (!email.includes('@') || !email.includes('.')) {
                showError('Please enter a valid email address.');
                return;
            }

            // Simple demo login - store user data in localStorage
            const userData = {
                email: email,
                role: selectedRole,
                name: email.split('@')[0],
                loggedIn: true,
                loginTime: new Date().toISOString()
            };

            localStorage.setItem('eventify_user', JSON.stringify(userData));
            
            // Show success and redirect to index (or dashboard preview)
            alert(`✅ Welcome ${userData.name}!\nYou are logged in as ${selectedRole.toUpperCase()}.\nRedirecting to homepage...`);
            
            // Redirect to index.html
            window.location.href = 'index.php';
        });

        function showError(msg) {
            errorMsgDiv.textContent = msg;
            errorMsgDiv.style.display = 'block';
            setTimeout(() => {
                errorMsgDiv.style.display = 'none';
            }, 3000);
        }

        // Register link (demo)
        document.getElementById('registerLink').addEventListener('click', (e) => {
            e.preventDefault();
            alert('📝 Registration page will be available in next step.\nFor now, please use demo login: any email/password.');
        });

        // Optional: if user already logged in and visits login page, redirect
        const storedUser = localStorage.getItem('eventify_user');
        if (storedUser) {
            // Optional: ask to logout first? We'll just clear for demo
            // localStorage.removeItem('eventify_user'); // uncomment if needed
        }


 
    </script>
</body>
</html>
