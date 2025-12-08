<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Agent') {
    header("Location: ../security/login.php");
    exit;
}

include '../backend/config.php';
$conn->select_db('football_agency');

// Fetch players who are NOT yet assigned
$players = $conn->query("
    SELECT p.player_id, u.name
    FROM players p
    JOIN users u ON p.user_id = u.id
    WHERE p.player_id NOT IN (
        SELECT player_id FROM player_agent_assignments
    )
");

// Fetch all agents
$agents = $conn->query("
    SELECT a.agent_id, u.name 
    FROM agents a
    JOIN users u ON a.user_id = u.id
");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Player Assignment</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<main class="max-w-5xl mx-auto p-8">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Player Assignment</h1>
        <a href="dashboard.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded shadow transition">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="p-4 mb-6 bg-green-100 text-green-800 rounded shadow"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="p-4 mb-6 bg-red-100 text-red-800 rounded shadow"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Assignment Card -->
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-6">Assign Player to Agent</h2>
        <form method="post" action="assign_action.php" class="grid gap-6 md:grid-cols-2">

            <!-- Player Selection -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Select Player</label>
                <select name="player_id" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300 hover:border-blue-400 transition">
                    <option value="" disabled selected>-- Choose a Player --</option>
                    <?php while ($p = $players->fetch_assoc()): ?>
                        <option value="<?= $p['player_id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Agent Selection -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Select Agent</label>
                <select name="agent_id" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 hover:border-green-400 transition">
                    <option value="" disabled selected>-- Choose an Agent --</option>
                    <?php while ($a = $agents->fetch_assoc()): ?>
                        <option value="<?= $a['agent_id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="md:col-span-2 flex flex-col sm:flex-row gap-4 mt-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition shadow">
                    Assign Player
                </button>
                <a href="players.php" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 rounded-lg text-center shadow transition">
                    View All Players
                </a>
                <a href="assignments.php" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg text-center shadow transition">
                    View Assignments
                </a>
                <a href="search_player.php" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 rounded-lg text-center shadow transition">
                    Search Player
                </a>
            </div>
        </form>
    </div>

</main>
</body>
</html>
