<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

// Handle Add Drink
if(isset($_POST['add_drink'])){
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category = $_POST['category'] ?? '';
    $image = null;

    // Handle Image Upload
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        // Create uploads folder if it doesn't exist
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/$image");
    }

    if($name && $price){
        $stmt = $conn->prepare("INSERT INTO drinks (name, description, price, category, image) VALUES (?,?,?,?,?)");
        $stmt->bind_param("ssdss", $name, $description, $price, $category, $image);
        $stmt->execute();
    }
}

// Fetch Drinks
$drinks = $conn->query("SELECT * FROM drinks");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Drinks | Bar Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 p-6">

<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold text-blue-900">Manage Drinks/Menu</h1>
    <a href="admin_dashboard.php" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded shadow">
        ← Back to Dashboard
    </a>
</div>

<!-- Add Drink Form -->
<form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow mb-6 grid gap-3 md:grid-cols-2">
    <input type="text" name="name" placeholder="Drink Name" class="p-2 border rounded" required>
    <input type="text" name="category" placeholder="Category" class="p-2 border rounded">
    <input type="number" step="0.01" name="price" placeholder="Price" class="p-2 border rounded" required>
    <input type="text" name="description" placeholder="Description" class="p-2 border rounded">
    <input type="file" name="image" class="p-2 border rounded" accept="image/*">
    <button type="submit" name="add_drink" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded col-span-full font-bold">Add Drink</button>
</form>

<!-- Drinks Table -->
<table class="bg-white w-full rounded shadow">
    <thead class="bg-blue-100 text-blue-900">
        <tr>
            <th class="p-2">ID</th>
            <th class="p-2">Image</th>
            <th class="p-2">Name</th>
            <th class="p-2">Category</th>
            <th class="p-2">Price</th>
            <th class="p-2">Description</th>
            <th class="p-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($drink = $drinks->fetch_assoc()): ?>
        <tr class="border-t">
            <td class="p-2"><?= $drink['id'] ?></td>
            <td class="p-2">
                <?php if($drink['image']): ?>
                    <img src="uploads/<?= htmlspecialchars($drink['image']) ?>" alt="Drink Image" class="w-16 h-16 object-cover rounded">
                
                <?php endif; ?>
            </td>
            <td class="p-2"><?= htmlspecialchars($drink['name']) ?></td>
            <td class="p-2"><?= htmlspecialchars($drink['category']) ?></td>
            <td class="p-2"><?= number_format($drink['price'],2) ?></td>
            <td class="p-2"><?= htmlspecialchars($drink['description']) ?></td>
            <td class="p-2 space-x-2">
                <a href="edit_drink.php?id=<?= $drink['id'] ?>" class="bg-yellow-400 hover:bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                <a href="delete_drink.php?id=<?= $drink['id'] ?>" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
