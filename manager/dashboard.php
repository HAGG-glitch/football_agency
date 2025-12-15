<?php
session_start();
include '../backend/config.php';

// Redirect if not a Club Manager
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Club Manager') {
    header("Location: ../security/login.php");
    exit;
}

$user_id = (int)$_SESSION['user']['id'];

// Fetch manager record
$managerResult = $conn->query("SELECT * FROM club_managers WHERE user_id = $user_id");
$manager = $managerResult ? $managerResult->fetch_assoc() : null;
if (!$manager) {
    die("Manager record not found. Please contact admin.");
}

// Club info
$club_id = $manager['club_id'] ?? null;

// Total players
$totalPlayers = 0;
if ($club_id) {
    $res = $conn->query("SELECT COUNT(*) AS c FROM players WHERE club_id = $club_id");
    $totalPlayers = $res ? (int)$res->fetch_assoc()['c'] : 0;
}

// Total agents
$res = $conn->query("SELECT COUNT(*) AS c FROM agents");
$totalAgents = $res ? (int)$res->fetch_assoc()['c'] : 0;

// Total goals
$totalGoals = 0;
if ($club_id) {
    $res = $conn->query("SELECT SUM(goals) AS g FROM players WHERE club_id = $club_id");
    $totalGoals = $res ? (int)$res->fetch_assoc()['g'] : 0;
}

// Total assists
$totalAssists = 0;
if ($club_id) {
    $res = $conn->query("SELECT SUM(assists) AS a FROM players WHERE club_id = $club_id");
    $totalAssists = $res ? (int)$res->fetch_assoc()['a'] : 0;
}

// Player positions
$positions = [];
$positionCounts = [];
if ($club_id) {
    $positionQuery = $conn->query("SELECT position, COUNT(*) as count FROM players WHERE club_id = $club_id GROUP BY position");
    if ($positionQuery) {
        while ($row = $positionQuery->fetch_assoc()) {
            $positions[] = $row['position'] ?? "Unknown";
            $positionCounts[] = (int)$row['count'];
        }
    }
}

// Top 10 players for charts
$playerNames = $goalsData = $assistsData = $fitnessData = $speedData = [];
if ($club_id) {
    $playerQuery = $conn->query("SELECT p.player_id, u.name, p.goals, p.assists, p.fitness_level, p.speed 
        FROM players p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.club_id = $club_id 
        ORDER BY p.goals DESC 
        LIMIT 10");
    if ($playerQuery) {
        while ($row = $playerQuery->fetch_assoc()) {
            $playerNames[] = $row['name'];
            $goalsData[] = (int)$row['goals'];
            $assistsData[] = (int)$row['assists'];
            $fitnessData[] = (int)$row['fitness_level'];
            $speedData[] = (int)$row['speed'];
        }
    }
}

// Top 5 players for table
$topPlayers = [];
if ($club_id) {
    $topPlayersQuery = $conn->query("SELECT p.*, u.name 
        FROM players p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.club_id = $club_id 
        ORDER BY p.goals DESC 
        LIMIT 5");
    if ($topPlayersQuery) {
        while ($row = $topPlayersQuery->fetch_assoc()) {
            $topPlayers[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Club Manager Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">

<header class="bg-green-600 text-white p-5 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Club Manager Dashboard</h1>
    <a href="../security/logout.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600 transition flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
        </svg>
        Logout
    </a>
</header>

<main class="max-w-7xl mx-auto p-6">

    <!-- Summary Cards -->
    <div class="grid md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded shadow text-center">
            <h3 class="text-gray-600 mb-2">Total Players</h3>
            <p class="text-4xl font-bold text-green-600"><?= $totalPlayers ?></p>
        </div>
        <div class="bg-white p-6 rounded shadow text-center">
            <h3 class="text-gray-600 mb-2">Total Agents</h3>
            <p class="text-4xl font-bold text-green-600"><?= $totalAgents ?></p>
        </div>
        <div class="bg-white p-6 rounded shadow text-center">
            <h3 class="text-gray-600 mb-2">Total Goals</h3>
            <p class="text-4xl font-bold text-green-600"><?= $totalGoals ?></p>
        </div>
        <div class="bg-white p-6 rounded shadow text-center">
            <h3 class="text-gray-600 mb-2">Total Assists</h3>
            <p class="text-4xl font-bold text-green-600"><?= $totalAssists ?></p>
        </div>
    </div>

    <!-- Management Actions -->
    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <a href="assign_player.php" class="bg-green-600 text-white p-6 rounded shadow hover:bg-green-700 text-center transition font-semibold flex flex-col items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Assign Player → Agent
        </a>

        <a href="manage_player.php" class="bg-blue-600 text-white p-6 rounded shadow hover:bg-blue-700 text-center transition font-semibold flex flex-col items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Manage Players / Starting 11
        </a>

        <a href="view_agents.php" class="bg-yellow-500 text-white p-6 rounded shadow hover:bg-yellow-600 text-center transition font-semibold flex flex-col items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A11.955 11.955 0 0112 15c2.387 0 4.613.707 6.879 1.804M12 15v6m0-6c-3.866 0-7-3.134-7-7 0-1.343.423-2.588 1.134-3.605M12 9h.01M12 15a7 7 0 100-14 7 7 0 000 14z" />
            </svg>
            View Agents in Club
        </a>
    </div>

    <!-- Charts -->
    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-gray-700 font-semibold mb-4">Goals vs Assists (Top Players)</h3>
            <canvas id="goalsAssistsChart" class="w-full h-64"></canvas>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-gray-700 font-semibold mb-4">Player Distribution by Position</h3>
            <canvas id="positionChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <div class="grid md:grid-cols-1 gap-6 mb-8">
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-gray-700 font-semibold mb-4">Fitness Level & Speed (Top Players)</h3>
            <canvas id="fitnessSpeedChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <!-- Top Players Table -->
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-gray-700 font-semibold mb-4">Top 5 Players</h3>
        <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600">Name</th>
                    <th class="px-4 py-2 text-center text-gray-600">Goals</th>
                    <th class="px-4 py-2 text-center text-gray-600">Assists</th>
                    <th class="px-4 py-2 text-center text-gray-600">Matches</th>
                    <th class="px-4 py-2 text-center text-gray-600">Fitness</th>
                    <th class="px-4 py-2 text-center text-gray-600">Speed</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($topPlayers as $p): ?>
                    <tr>
                        <td class="px-4 py-2"><?= htmlspecialchars($p['name']) ?></td>
                        <td class="px-4 py-2 text-center"><?= (int)$p['goals'] ?></td>
                        <td class="px-4 py-2 text-center"><?= (int)$p['assists'] ?></td>
                        <td class="px-4 py-2 text-center"><?= (int)$p['matches'] ?></td>
                        <td class="px-4 py-2 text-center"><?= (int)$p['fitness_level'] ?></td>
                        <td class="px-4 py-2 text-center"><?= (int)$p['speed'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>

</main>

<script>
// Goals vs Assists
const goalsCtx = document.getElementById('goalsAssistsChart').getContext('2d');
new Chart(goalsCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($playerNames) ?>,
        datasets: [
            { label: 'Goals', data: <?= json_encode($goalsData) ?>, backgroundColor: 'rgba(34,197,94,0.7)' },
            { label: 'Assists', data: <?= json_encode($assistsData) ?>, backgroundColor: 'rgba(16,185,129,0.7)' }
        ]
    },
    options: { responsive: true, plugins: { legend: { position: 'top' } } }
});

// Player positions pie chart
const positionCtx = document.getElementById('positionChart').getContext('2d');
new Chart(positionCtx, {
    type: 'pie',
    data: {
        labels: <?= json_encode($positions) ?>,
        datasets: [{ data: <?= json_encode($positionCounts) ?>, backgroundColor: ['#34D399','#10B981','#059669','#047857','#065F46','#064E3B'] }]
    },
    options: { responsive: true }
});

// Fitness & Speed radar chart
const fitnessCtx = document.getElementById('fitnessSpeedChart').getContext('2d');
new Chart(fitnessCtx, {
    type: 'radar',
    data: {
        labels: <?= json_encode($playerNames) ?>,
        datasets: [
            { label: 'Fitness Level', data: <?= json_encode($fitnessData) ?>, fill: true, backgroundColor: 'rgba(34,197,94,0.2)', borderColor: 'rgba(34,197,94,1)' },
            { label: 'Speed', data: <?= json_encode($speedData) ?>, fill: true, backgroundColor: 'rgba(16,185,129,0.2)', borderColor: 'rgba(16,185,129,1)' }
        ]
    },
    options: { responsive: true, scales: { r: { beginAtZero: true } } }
});
</script>

</body>
</html>
