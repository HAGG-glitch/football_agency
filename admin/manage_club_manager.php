<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header("Location: ../security/login.php");
    exit;
}

$managers = $conn->query("
    SELECT cm.*, u.name AS user_name, c.club_name 
    FROM club_managers cm
    LEFT JOIN users u ON cm.user_id = u.id
    LEFT JOIN clubs c ON cm.club_id = c.club_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Club Managers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { navy: "#0A2647", lime: "#57C84D", softblue: "#144272" } } } };
    </script>
</head>
<body class="bg-gray-100">

<header class="bg-navy text-white p-5 flex justify-between">
    <h1 class="text-xl font-bold">Manage Club Managers</h1>
    <a href="dashboard.php" class="bg-softblue px-4 py-2 rounded">Back</a>
</header>

<main class="p-6 max-w-6xl mx-auto">
    <div class="flex justify-between mb-6">
        <h2 class="text-2xl font-semibold">Club Managers List</h2>
        <a href="add_manager.php" class="bg-lime px-4 py-2 rounded text-white">Add Manager</a>
    </div>

    <table class="w-full bg-white shadow rounded">
        <tr class="bg-gray-200 text-left">
            <th class="p-3">ID</th>
            <th class="p-3">Name</th>
            <th class="p-3">Club</th>
            <th class="p-3">Office Number</th>
            <th class="p-3">Age</th>
            <th class="p-3">Actions</th>
        </tr>

        <?php while ($m = $managers->fetch_assoc()): ?>
        <tr class="border-t">
            <td class="p-3"><?= $m['manager_id'] ?></td>
            <td class="p-3"><?= $m['user_name'] ?></td>
            <td class="p-3"><?= $m['club_name'] ?></td>
            <td class="p-3"><?= $m['office_number'] ?></td>
            <td class="p-3"><?= $m['age'] ?></td>
            <td class="p-3 flex gap-3">
                <a href="edit_manager.php?id=<?= $m['manager_id'] ?>" class="text-blue-600">Edit</a>
                <a href="delete_manager.php?id=<?= $m['manager_id'] ?>" class="text-red-600" onclick="return confirm('Delete this manager?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</main>
</body>
</html>
