<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Player') {
    header("Location: ../security/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$player = $conn->query("SELECT * FROM players WHERE user_id = $user_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Player Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<header class="bg-navy p-5 text-white flex justify-between">
    <h1 class="font-bold text-xl">Player Dashboard</h1>
    <a href="../security/logout.php" class="bg-red-500 px-4 py-2 rounded">Logout</a>
</header>

<main class="max-w-4xl mx-auto p-6 bg-white shadow rounded">

    <div class="flex gap-6">
        <img src="<?= $player['image'] ?? '../assets/default_player.jpg' ?>" 
             class="h-28 w-28 rounded shadow" alt="player">

        <div>
            <h2 class="text-2xl font-bold"><?= $_SESSION['user']['name'] ?></h2>
            <p class="text-gray-500"><?= $player['position'] ?> • <?= $player['nationality'] ?></p>

            <a href="edit_profile.php" class="inline-block mt-4 bg-lime text-black px-4 py-2 rounded">
                Edit Profile
            </a>
        </div>
    </div>

</main>

</body>
</html>
