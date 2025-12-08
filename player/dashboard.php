<?php
session_start();
include '../backend/config.php';

// Redirect if not logged in or not a player
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Player') {
    header("Location: ../security/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'] ?? 0;
if (!$user_id) die("User ID not found. Please log in.");

// Fetch player info
$stmt = $conn->prepare("SELECT * FROM players WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$player = $stmt->get_result()->fetch_assoc();
if (!$player) die("Player not found. Contact admin.");

// Fetch player stats
$stats = $conn->query("SELECT * FROM player_stats WHERE player_id={$player['player_id']}")->fetch_assoc();

// Merge player and player_stats for easy access
$all_stats = array_merge($player ?? [], $stats ?? []);

// Fetch club info if exists
$club = $player['club_id'] ? $conn->query("SELECT * FROM clubs WHERE club_id={$player['club_id']}")->fetch_assoc() : null;

// Dummy data for performance graph
$last5matches = [5, 6, 7, 8, 7];
$match_labels = ["M1", "M2", "M3", "M4", "M5"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Player Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Header Navigation -->
<header class="bg-gray-800 text-white p-5 flex justify-between items-center">
    <h1 class="font-bold text-xl">Player Dashboard</h1>
    <div class="flex gap-3">
        <a href="dashboard.php" class="bg-gray-700 px-4 py-2 rounded hover:bg-gray-600 transition">Dashboard</a>
        <a href="edit_profile.php" class="bg-lime-500 px-4 py-2 rounded hover:bg-lime-600 transition text-black">Edit Profile</a>
        <a href="../security/logout.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600 transition">Logout</a>
    </div>
</header>

<main class="max-w-6xl mx-auto p-6">

    <!-- Player Info -->
    <div class="flex gap-6 mb-6 bg-white p-6 rounded shadow">
        <img src="<?= $player['image'] ?: '../assets/default_player.jpg' ?>" class="h-32 w-32 rounded shadow" alt="player">
        <div>
            <h2 class="text-3xl font-bold"><?= $_SESSION['user']['name'] ?></h2>
            <p class="text-gray-500"><?= $player['position'] ?> • <?= $player['nationality'] ?></p>
            <p class="text-gray-500">Age: <?= $player['age'] ?? 'N/A' ?> | Height: <?= $player['height'] ?? 'N/A' ?> | Weight: <?= $player['weight'] ?? 'N/A' ?></p>
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
        $main_stats = [
            'Goals' => $all_stats['goals'] ?? 0,
            'Assists' => $all_stats['assists'] ?? 0,
            'Matches' => $all_stats['matches'] ?? 0,
            'Minutes Played' => $all_stats['minutes_played'] ?? 0,
            'Clean Sheets' => $all_stats['clean_sheets'] ?? 0,
            'Yellow Cards' => $all_stats['yellow_cards'] ?? 0,
            'Red Cards' => $all_stats['red_cards'] ?? 0
        ];

        foreach($main_stats as $label => $value): ?>
            <div class="bg-gray-100 p-4 rounded text-center">
                <h4 class="text-lg font-bold"><?= $value ?></h4>
                <p class="text-gray-500"><?= $label ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Position Based Stats -->
    <h3 class="text-xl font-bold mb-3">Position Stats</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <?php
        switch($player['position']){
            case 'Forward':
                $pos_stats = [
                    'Shots' => $all_stats['shots_on_target'] ?? 0,
                    'Conversion' => ($all_stats['conversion_rate'] ?? 0) . '%',
                    'Dribbles' => $all_stats['dribbles'] ?? 0
                ];
                break;
            case 'Midfielder':
                $pos_stats = [
                    'Pass Accuracy' => ($all_stats['pass_accuracy'] ?? 0) . '%',
                    'Chances Created' => $all_stats['chances_created'] ?? 0,
                    'Tackles Won' => $all_stats['tackles_won'] ?? 0
                ];
                break;
            case 'Defender':
                $pos_stats = [
                    'Clearances' => $all_stats['clearances'] ?? 0,
                    'Blocks' => $all_stats['blocks'] ?? 0,
                    'Interceptions' => $all_stats['interceptions'] ?? 0,
                    'Clean Sheets' => $all_stats['clean_sheets'] ?? 0
                ];
                break;
            case 'Goalkeeper':
                $pos_stats = [
                    'Saves' => $all_stats['saves'] ?? 0,
                    'Save %' => ($all_stats['save_percentage'] ?? 0) . '%',
                    'Clean Sheets' => $all_stats['clean_sheets'] ?? 0
                ];
                break;
            default:
                $pos_stats = [];
        }
        foreach($pos_stats as $k=>$v): ?>
            <div class="bg-gray-100 p-4 rounded text-center">
                <h4 class="text-lg font-bold"><?= $v ?></h4>
                <p class="text-gray-500"><?= $k ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Physical Stats -->
    <h3 class="text-xl font-bold mb-3">Physical Stats</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gray-100 p-4 rounded text-center">
            <h4><?= $all_stats['speed'] ?? 'N/A' ?> km/h</h4>
            <p class="text-gray-500">Top Speed</p>
        </div>
        <div class="bg-gray-100 p-4 rounded text-center">
            <h4><?= $all_stats['fitness_level'] ?? 'N/A' ?>/100</h4>
            <p class="text-gray-500">Fitness Level</p>
        </div>
        <div class="bg-gray-100 p-4 rounded text-center">
            <p><?= $all_stats['injury_history'] ?: 'No injuries recorded' ?></p>
            <p class="text-gray-500">Injury History</p>
        </div>
    </div>

    <!-- Performance & Graph -->
    <div class="grid grid-cols-12 gap-6 mb-6">
        <div class="col-span-4 bg-white p-4 rounded shadow">
            <h4 class="font-bold mb-2">Last Match Performance</h4>
            <p>Rating: ⭐⭐⭐⭐☆</p>
            <p>Minutes Played: 90</p>
            <p>Man of the Match: No</p>
        </div>
        <div class="col-span-8 bg-white p-4 rounded shadow">
            <h4 class="font-bold mb-2">Last 5 Matches Performance</h4>
            <canvas id="performanceChart"></canvas>
        </div>
    </div>

    <!-- Upcoming Matches & Training -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-4 rounded shadow">
            <h4 class="font-bold mb-2">Upcoming Matches</h4>
            <p>No schedule yet</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h4 class="font-bold mb-2">Training Schedule</h4>
            <p>Coach will update your schedule</p>
        </div>
    </div>

    <!-- Coach Remarks -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <h4 class="font-bold mb-2">Coach Remarks</h4>
        <p>No remarks yet</p>
    </div>

</main>

<script>
const ctx = document.getElementById('performanceChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($match_labels) ?>,
        datasets: [{
            label: "Rating",
            data: <?= json_encode($last5matches) ?>,
            borderColor: 'rgb(34,197,94)',
            backgroundColor: 'rgba(34,197,94,0.2)',
            tension: 0.3
        }]
    },
    options: {
        scales: { y: { beginAtZero: true, max: 10 } }
    }
});
</script>

</body>
</html>
