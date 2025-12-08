<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Player') {
    header("Location: ../security/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Fetch player info
$stmt = $conn->prepare("SELECT * FROM players WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$player = $stmt->get_result()->fetch_assoc();

// Fetch player stats
$stats = $conn->query("SELECT * FROM player_stats WHERE player_id={$player['player_id']}")->fetch_assoc();

// Fetch club info if exists
$club = null;
if($player['club_id']){
    $club = $conn->query("SELECT * FROM clubs WHERE club_id={$player['club_id']}")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Player Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Header Navigation -->
<header class="bg-gray-800 text-white p-5 flex justify-between items-center">
    <h1 class="font-bold text-xl">Player Dashboard</h1>
    <div class="flex gap-4">
        <a href="dashboard.php" class="bg-gray-700 px-4 py-2 rounded hover:bg-gray-600 transition">Dashboard</a>
        <a href="edit_profile.php" class="bg-lime-500 px-4 py-2 rounded hover:bg-lime-600 transition text-black">Edit Profile</a>
        <a href="../security/logout.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600 transition">Logout</a>
    </div>
</header>

<main class="max-w-6xl mx-auto p-6">

    <!-- Player Info -->
    <div class="flex gap-6 mb-6 bg-white p-6 rounded shadow">
        <img src="<?= $player['image'] ?: '../assets/default_player.jpg' ?>" class="h-32 w-32 rounded shadow">
        <div>
            <h2 class="text-3xl font-bold"><?= $_SESSION['user']['name'] ?></h2>
            <p class="text-gray-500"><?= $player['position'] ?> • <?= $player['nationality'] ?></p>
            <p class="text-gray-500">Age: <?= $player['age'] ?> | Height: <?= $player['height'] ?> | Weight: <?= $player['weight'] ?></p>
        </div>
    </div>

    <!-- Club Info -->
    <?php if(!$club): ?>
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-6 font-semibold">
            You are not assigned to a club yet. Admin will assign your club soon.
        </div>
    <?php else: ?>
        <div class="bg-green-100 text-green-800 p-4 rounded mb-6 font-semibold">
            Club: <?= $club['club_name'] ?> | League: <?= $club['league'] ?? 'N/A' ?>
        </div>
    <?php endif; ?>

    <!-- Player Statistics -->
    <h3 class="text-xl font-bold mb-3">Player Statistics</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <?php 
        $displayFields = ['goals','assists','matches','minutes_played','clean_sheets','yellow_cards','red_cards'];
        foreach($displayFields as $field): ?>
            <div class="bg-gray-100 p-4 rounded text-center">
                <h4 class="text-lg font-bold"><?= $stats[$field] ?? 0 ?></h4>
                <p class="text-gray-500"><?= ucwords(str_replace('_',' ',$field)) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

</main>

</body>
</html>
