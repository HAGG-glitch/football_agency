<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header("Location: ../security/login.php");
    exit;
}

$clubs = $conn->query("SELECT * FROM clubs");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Clubs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { navy: "#0A2647", lime: "#57C84D", softblue: "#144272" } } } };
    </script>
</head>
<body class="bg-gray-100">

<header class="bg-navy text-white p-5 flex justify-between">
    <h1 class="text-xl font-bold">Manage Clubs</h1>
    <a href="dashboard.php" class="bg-softblue px-4 py-2 rounded">Back</a>
</header>

<main class="p-6 max-w-6xl mx-auto">
    <div class="flex justify-between mb-6">
        <h2 class="text-2xl font-semibold">Clubs List</h2>
        <a href="add_club.php" class="bg-lime px-4 py-2 rounded text-white">Add Club</a>
    </div>

    <table class="w-full bg-white shadow rounded">
        <tr class="bg-gray-200 text-left">
            <th class="p-3">ID</th>
            <th class="p-3">Club Name</th>
            <th class="p-3">Location</th>
            <th class="p-3">League</th>
            <th class="p-3">Actions</th>
        </tr>

        <?php while ($c = $clubs->fetch_assoc()): ?>
        <tr class="border-t">
            <td class="p-3"><?= $c['club_id'] ?></td>
            <td class="p-3"><?= $c['club_name'] ?></td>
            <td class="p-3"><?= $c['location'] ?></td>
            <td class="p-3"><?= $c['league'] ?></td>
            <td class="p-3 flex gap-3">
                <a href="edit_club.php?id=<?= $c['club_id'] ?>" class="text-blue-600">Edit</a>
                <a href="delete_club.php?id=<?= $c['club_id'] ?>" class="text-red-600" onclick="return confirm('Delete this club?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</main>
</body>
</html>
