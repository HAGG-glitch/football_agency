<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header("Location: ../security/login.php");
    exit;
}

$players = $conn->query("
    SELECT players.*, users.name AS user_name, clubs.club_name 
    FROM players
    LEFT JOIN users ON players.user_id = users.id
    LEFT JOIN clubs ON players.club_id = clubs.club_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Players</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { navy: "#0A2647", lime: "#57C84D", softblue: "#144272" } } }
        };
    </script>
</head>
<body class="bg-gray-100">

<header class="bg-navy text-white p-5 flex justify-between">
    <h1 class="text-xl font-bold">Manage Players</h1>
    <a href="dashboard.php" class="bg-softblue px-4 py-2 rounded">Back</a>
</header>

<main class="p-6 max-w-6xl mx-auto">
    <div class="flex justify-between mb-6">
        <h2 class="text-2xl font-semibold">Players List</h2>
        <a href="add_player.php" class="bg-lime px-4 py-2 rounded text-white">Add Player</a>
    </div>

    <table class="w-full bg-white shadow rounded">
        <tr class="bg-gray-200 text-left">
            <th class="p-3">ID</th>
            <th class="p-3">Name</th>
            <th class="p-3">Club</th>
            <th class="p-3">Age</th>
            <th class="p-3">Position</th>
            <th class="p-3">Nationality</th>
            <th class="p-3">Actions</th>
        </tr>

        <?php while ($p = $players->fetch_assoc()): ?>
        <tr class="border-t">
            <td class="p-3"><?= $p['player_id'] ?></td>
            <td class="p-3"><?= $p['user_name'] ?></td>
            <td class="p-3"><?= $p['club_name'] ?></td>
            <td class="p-3"><?= $p['age'] ?></td>
            <td class="p-3"><?= $p['position'] ?></td>
            <td class="p-3"><?= $p['nationality'] ?></td>
            <td class="p-3 flex gap-3">
                <a href="edit_player.php?id=<?= $p['player_id'] ?>" class="text-blue-600">Edit</a>
                <a href="delete_player.php?id=<?= $p['player_id'] ?>" class="text-red-600" onclick="return confirm('Delete this player?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</main>
</body>
</html>
