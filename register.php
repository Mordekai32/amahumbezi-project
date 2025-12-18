<?php
session_start();
include 'db.php';

// Redirect logged-in users
if(isset($_SESSION['user_id'])){
    if($_SESSION['role'] == 'admin'){
        header("Location: admin_dashboard.php");
    } else {
        header("Location: customer_dashboard.php");
    }
    exit();
}

$errors = [];
$success = "";

// Safely retrieve POST values
$name = $email = $password = $confirm_password = $role = '';

if(isset($_POST['register'])){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Validation
    if(empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($role)){
        $errors[] = "All fields are required!";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "Invalid email format!";
    } elseif($password !== $confirm_password){
        $errors[] = "Passwords do not match!";
    } elseif(strlen($password) < 6){
        $errors[] = "Password must be at least 6 characters!";
    } elseif(!in_array($role, ['admin','customer'])){
        $errors[] = "Invalid role selected!";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $errors[] = "Email already registered!";
        } else {
            // Insert user
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
            $stmt->bind_param("ssss",$name,$email,$hashed_password,$role);
            if($stmt->execute()){
                $success = "Registration successful! Redirecting to login...";
                header("refresh:2; url=login.php");
            } else {
                $errors[] = "Database error: ".$stmt->error;
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
    <title>Register | Bar Website</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-600 to-blue-900 min-h-screen flex items-center justify-center p-4">

<div class="bg-white p-10 rounded-2xl shadow-2xl max-w-lg w-full border border-blue-100">

    <h1 class="text-3xl font-extrabold text-center text-blue-700 mb-6">
        Create Your Account
    </h1>

    <!-- Display Errors -->
    <?php if(!empty($errors)): ?>
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg border border-red-300">
            <ul class="list-disc pl-5">
                <?php foreach($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Display Success -->
    <?php if($success): ?>
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg border border-green-300 text-center">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <!-- Registration Form -->
    <form method="POST" class="space-y-5">

        <input 
            type="text" 
            name="name" 
            placeholder="Full Name"
            value="<?= htmlspecialchars($name) ?>"
            class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            required
        >

        <input 
            type="email" 
            name="email" 
            placeholder="Email Address"
            value="<?= htmlspecialchars($email) ?>"
            class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            required
        >

        <input 
            type="password" 
            name="password" 
            placeholder="Password"
            class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            required
        >

        <input 
            type="password" 
            name="confirm_password" 
            placeholder="Confirm Password"
            class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            required
        >

        <select 
            name="role" 
            class="w-full p-3 border rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            required
        >
            <option value="" disabled <?= $role=='' ? 'selected' : '' ?>>Select Role</option>
            <option value="customer" <?= $role=='customer' ? 'selected' : '' ?>>Customer</option>
             
            
            
          
        </select>

        <button 
            type="submit" 
            name="register"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition"
        >
            Register Now
        </button>
    </form>

    <p class="mt-4 text-center text-gray-700">
        Already a member? 
        <a href="login.php" class="text-blue-600 font-semibold hover:underline">
            Login here
        </a>
    </p>

</div>

</body>
</html>
