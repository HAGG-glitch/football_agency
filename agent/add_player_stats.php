<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Agent') {
    header("Location: ../security/login.php");
    exit;
}

$conn->select_db("football_agency");

// Get all players assigned to this agent
$agent_id = $_SESSION['user']['id'];

$players = $conn->query("
    SELECT p.player_id, u.name AS player_name 
    FROM players p 
    JOIN users u ON p.user_id = u.id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Player Stats</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

<a href="dashboard.php" class="mb-6 inline-block bg-gray-700 text-white px-5 py-2 rounded-lg shadow">← Back</a>

<div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow">
    <h2 class="text-2xl font-bold mb-4">Add Player Stats</h2>

    <form action="save_player_stats.php" method="POST" class="space-y-5">
        <div>
            <label class="font-semibold">Select Player</label>
            <select name="player_id" required class="w-full px-4 py-2 border rounded-lg">
                <?php while ($p = $players->fetch_assoc()): ?>
                    <option value="<?= $p['player_id'] ?>"><?= $p['player_name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label class="font-semibold">Goals</label>
            <input type="number" name="goals" class="w-full px-4 py-2 border rounded-lg" required>
        </div>

        <div>
            <label class="font-semibold">Assists</label>
            <input type="number" name="assists" class="w-full px-4 py-2 border rounded-lg" required>
        </div>

        <div>
            <label class="font-semibold">Matches Played</label>
            <input type="number" name="matches" class="w-full px-4 py-2 border rounded-lg" required>
        </div>

        <button class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700">
            Save Stats
        </button>
    </form>
</div>

</body>
</html>
