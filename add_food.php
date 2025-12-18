<?php
session_start();
include 'db.php';

// Admin access check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

$name = $price = "";
$errors = [];
$success = "";

// Check if editing
$edit_id = $_GET['edit_id'] ?? null;
if($edit_id){
    $edit_food = $conn->query("SELECT * FROM foods WHERE id=".intval($edit_id))->fetch_assoc();
    if($edit_food){
        $name = $edit_food['name'];
        $price = $edit_food['price'];
    } else {
        header("Location: manage_foods.php");
        exit();
    }
}

// Handle form submission
if(isset($_POST['save_food'])){
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $image_name = $edit_food['image'] ?? null;

    // Validation
    if(!$name) $errors[] = "Name is required.";
    if(!$price || !is_numeric($price)) $errors[] = "Valid price is required.";

    // Handle image upload
    if(isset($_FILES['image']) && $_FILES['image']['name']){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid().".$ext";
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image_name);
    }

    if(empty($errors)){
        if($edit_id){
            // Update
            $stmt = $conn->prepare("UPDATE foods SET name=?, price=?, image=? WHERE id=?");
            $stmt->bind_param("sdsi", $name, $price, $image_name, $edit_id);
            $stmt->execute();
            $success = "Food updated successfully.";
        } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO foods (name, price, image) VALUES (?, ?, ?)");
            $stmt->bind_param("sds", $name, $price, $image_name);
            $stmt->execute();
            $success = "Food added successfully.";
            $name = $price = "";
            $image_name = null;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $edit_id ? "Edit" : "Add" ?> Food | Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50">

<div class="max-w-2xl mx-auto p-6">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold"><?= $edit_id ? "Edit" : "Add" ?> Food</h1>
    <a href="admin_dashboard.php" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Back to Dashboard</a>
  </div>

  <?php if($errors): ?>
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
      <ul class="list-disc pl-5">
      <?php foreach($errors as $e): ?>
        <li><?= $e ?></li>
      <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if($success): ?>
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" class="bg-white rounded-xl p-6 shadow space-y-4">
    <div>
      <label class="block mb-1 font-semibold">Name</label>
      <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" class="w-full border px-3 py-2 rounded" required>
    </div>

    <div>
      <label class="block mb-1 font-semibold">Price </label>
      <input type="text" name="price" value="<?= htmlspecialchars($price) ?>" class="w-full border px-3 py-2 rounded" required>
    </div>

    <div>
      <label class="block mb-1 font-semibold">Image</label>
      <input type="file" name="image" class="w-full">
      <?php if(!empty($edit_food['image']) && file_exists("uploads/".$edit_food['image'])): ?>
        <img src="uploads/<?= $edit_food['image'] ?>" class="w-24 h-24 mt-2 object-cover rounded">
      <?php endif; ?>
    </div>

    <div class="flex gap-2">
      <button type="submit" name="save_food" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700"><?= $edit_id ? "Update" : "Add" ?></button>
      <a href="manage_foods.php" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</a>
    </div>
  </form>
</div>
</body>
</html>
