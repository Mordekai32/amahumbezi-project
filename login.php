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
$email = '';
$password = '';

if(isset($_POST['login'])){
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if(empty($email) || empty($password)){
        $errors[] = "Both email and password are required!";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "Invalid email format!";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
        if(!$stmt) die("SQL Error: ".$conn->error);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1){
            $user = $result->fetch_assoc();
            if(password_verify($password, $user['password'])){
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['name'];
                header($user['role'] == 'admin' ? "Location: admin_dashboard.php" : "Location: index.php");
                exit();
            } else $errors[] = "Incorrect password!";
        } else $errors[] = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Bar Website</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-900 via-cyan-500 to-blue-600 min-h-screen flex items-center justify-center">

<!-- Login Container -->
<div class="bg-white p-10 rounded-3xl shadow-2xl max-w-md w-full border border-cyan-300">

    <!-- Title -->
    <h1 class="text-4xl font-extrabold text-center text-blue-900 mb-6">
        Welcome Back 🍸
    </h1>

    <!-- Errors -->
    <?php if(!empty($errors)): ?>
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            <ul class="list-disc pl-5">
                <?php foreach($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <form method="POST" class="space-y-6">

        <input type="email" name="email" placeholder="Email Address" value="<?= htmlspecialchars($email) ?>" 
               class="w-full p-4 border rounded-xl focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400" required>

        <input type="password" name="password" placeholder="Password"
               class="w-full p-4 border rounded-xl focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400" required>

        <button type="submit" name="login"
                class="w-full bg-cyan-500 hover:bg-cyan-600 text-white font-bold py-4 rounded-xl shadow-md transition">
            Login
        </button>
    </form>

    <!-- Register Link -->
    <p class="mt-6 text-center text-gray-700">
        Don’t have an account?
        <a href="register.php" class="text-yellow-400 font-semibold hover:underline">
            Register here
        </a>
    </p>
</div>

</body>
</html>
