<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header("Location: ../security/login.php");
    exit;
}

$users = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { navy: "#0A2647", lime: "#57C84D", softblue: "#144272" } } } };
    </script>
</head>
<body class="bg-gray-100">

<header class="bg-navy text-white p-5 flex justify-between">
    <h1 class="text-xl font-bold">Manage Users</h1>
    <a href="dashboard.php" class="bg-softblue px-4 py-2 rounded">Back</a>
</header>

<main class="p-6 max-w-6xl mx-auto">
    <div class="flex justify-between mb-6">
        <h2 class="text-2xl font-semibold">Users List</h2>
        <a href="add_user.php" class="bg-lime px-4 py-2 rounded text-white">Add User</a>
    </div>

    <table class="w-full bg-white shadow rounded">
        <tr class="bg-gray-200 text-left">
            <th class="p-3">ID</th>
            <th class="p-3">Name</th>
            <th class="p-3">Email</th>
            <th class="p-3">Role</th>
            <th class="p-3">Phone</th>
            <th class="p-3">Actions</th>
        </tr>

        <?php while ($u = $users->fetch_assoc()): ?>
        <tr class="border-t">
            <td class="p-3"><?= $u['id'] ?></td>
            <td class="p-3"><?= $u['name'] ?></td>
            <td class="p-3"><?= $u['email'] ?></td>
            <td class="p-3"><?= $u['role'] ?></td>
            <td class="p-3"><?= $u['phone'] ?></td>
            <td class="p-3 flex gap-3">
                <a href="edit_user.php?id=<?= $u['id'] ?>" class="text-blue-600">Edit</a>
                <a href="delete_user.php?id=<?= $u['id'] ?>" class="text-red-600" onclick="return confirm('Delete this user?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</main>
</body>
</html>
