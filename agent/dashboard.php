<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Agent') {
    header("Location: ../security/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Fetch agent info
$agent = $conn->query("SELECT * FROM agents WHERE user_id = $user_id")->fetch_assoc();
if (!$agent) {
    die("Agent record not found. Please contact admin.");
}
$experience = $agent['experience_years'] ?? 0;
$agency_name = $agent['agency_name'] ?? 'N/A';
$agent_id = $agent['agent_id'];

// Total players assigned
$totalAssigned = $conn->query("
    SELECT COUNT(*) AS total 
    FROM player_agent_assignments
    WHERE agent_id = $agent_id
")->fetch_assoc()['total'] ?? 0;

// Assignments per month
$assignmentData = $conn->query("
    SELECT DATE_FORMAT(assigned_at, '%Y-%m') AS month, COUNT(*) AS total
    FROM player_agent_assignments
    WHERE agent_id = $agent_id
    GROUP BY month
    ORDER BY month ASC
");

$months = [];
$totals = [];
while ($row = $assignmentData->fetch_assoc()) {
    $months[] = $row['month'];
    $totals[] = (int)$row['total'];
}

// Average assignments and trend
$avgAssignments = $totals ? round(array_sum($totals) / count($totals), 2) : 0;
$trend = '';
$trendColor = '';
if (count($totals) >= 2) {
    $last = $totals[count($totals)-1];
    $prev = $totals[count($totals)-2];
    if ($last > $prev) {
        $trend = '↑';
        $trendColor = 'text-green-600';
    } elseif ($last < $prev) {
        $trend = '↓';
        $trendColor = 'text-red-600';
    } else {
        $trend = '→';
        $trendColor = 'text-gray-500';
    }
}

// Recent assignments (last 5)
$recentAssignments = $conn->query("
    SELECT p.player_id, u.name AS player_name, p.position, paa.assigned_at
    FROM player_agent_assignments paa
    JOIN players p ON paa.player_id = p.player_id
    JOIN users u ON p.user_id = u.id
    WHERE paa.agent_id = $agent_id
    ORDER BY paa.assigned_at DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<!-- Toast Notifications -->
<?php if(isset($_SESSION['toast'])): 
    $toast = $_SESSION['toast']; 
    unset($_SESSION['toast']);
?>
<div id="toast" class="fixed top-5 right-5 p-4 rounded shadow-lg text-white font-semibold 
    <?= $toast['type'] === 'success' ? 'bg-green-500' : 'bg-red-500' ?> transition-opacity duration-500 opacity-0">
    <?= htmlspecialchars($toast['message']) ?>
</div>
<script>
    const toast = document.getElementById('toast');
    toast.classList.add('opacity-100');
    setTimeout(() => {
        toast.classList.remove('opacity-100');
        toast.classList.add('opacity-0');
    }, 3000);
</script>
<?php endif; ?>

<!-- Header -->
<header class="bg-gray-900 p-6 text-white flex justify-between items-center shadow-lg">
    <h1 class="font-bold text-2xl tracking-wide">Agent Dashboard</h1>
    <div class="flex items-center gap-4">
        <span class="text-gray-300">Hello, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
        <a href="../security/logout.php" class="bg-red-600 hover:bg-red-700 transition px-5 py-2 rounded shadow font-medium">Logout</a>
    </div>
</header>

<main class="max-w-7xl mx-auto p-8 space-y-10">

    <!-- Stats Cards -->
    <div class="grid md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-xl transition transform hover:-translate-y-1">
            <p class="text-gray-500 font-semibold">Total Players Assigned</p>
            <p class="text-4xl font-bold text-blue-700 mt-2"><?= $totalAssigned ?></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-xl transition transform hover:-translate-y-1">
            <p class="text-gray-500 font-semibold">Experience (Years)</p>
            <p class="text-4xl font-bold text-green-700 mt-2"><?= $experience ?></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-xl transition transform hover:-translate-y-1">
            <p class="text-gray-500 font-semibold">Agency Name</p>
            <p class="text-2xl font-bold text-purple-700 mt-2"><?= htmlspecialchars($agency_name) ?></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-xl transition transform hover:-translate-y-1 flex items-center justify-between">
            <div>
                <p class="text-gray-500 font-semibold">Avg Assignments/Month</p>
                <p class="text-3xl font-bold mt-2"><?= $avgAssignments ?></p>
            </div>
            <div class="text-3xl font-bold <?= $trendColor ?>"><?= $trend ?></div>
        </div>
    </div>

    <!-- Assignments Chart -->
    <div class="bg-white p-6 rounded-xl shadow hover:shadow-xl transition">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Player Assignments Over Time</h2>
        <canvas id="assignmentChart" class="w-full h-64"></canvas>
    </div>

    <!-- Recent Assignments Table -->
    <div class="bg-white p-6 rounded-xl shadow hover:shadow-xl transition">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Recent Player Assignments</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-200 rounded-t-lg">
                    <tr>
                        <th class="text-left px-6 py-3 text-gray-700 font-medium">Player Name</th>
                        <th class="text-left px-6 py-3 text-gray-700 font-medium">Position</th>
                        <th class="text-left px-6 py-3 text-gray-700 font-medium">Assigned At</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($recentAssignments->num_rows > 0): ?>
                    <?php while ($row = $recentAssignments->fetch_assoc()): ?>
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="px-6 py-3"><?= htmlspecialchars($row['player_name']) ?></td>
                            <td class="px-6 py-3"><?= htmlspecialchars($row['position']) ?></td>
                            <td class="px-6 py-3"><?= htmlspecialchars($row['assigned_at']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">No recent assignments</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Action Buttons -->
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="assignment.php" class="bg-blue-600 text-white p-6 rounded-xl shadow hover:shadow-2xl transition transform hover:-translate-y-1 flex items-center justify-center font-semibold">
            View Players
        </a>
        <a href="add_player_stats.php" class="bg-green-600 text-white p-6 rounded-xl shadow hover:shadow-2xl transition transform hover:-translate-y-1 flex items-center justify-center font-semibold">
            Add Player Stats
        </a>
        <a href="generate_reports.php" class="bg-purple-600 text-white p-6 rounded-xl shadow hover:shadow-2xl transition transform hover:-translate-y-1 flex items-center justify-center font-semibold">
            Generate Reports
        </a>
        <a href="manage_contracts.php" class="bg-yellow-600 text-white p-6 rounded-xl shadow hover:shadow-2xl transition transform hover:-translate-y-1 flex items-center justify-center font-semibold">
            Manage Contracts
        </a>
    </div>

</main>

<script>
const ctx = document.getElementById('assignmentChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            label: 'Players Assigned',
            data: <?= json_encode($totals) ?>,
            backgroundColor: [
                'rgba(37, 99, 235, 0.7)',
                'rgba(5, 150, 105, 0.7)',
                'rgba(124, 58, 237, 0.7)',
                'rgba(202, 138, 4, 0.7)',
                'rgba(16, 185, 129, 0.7)',
                'rgba(14, 165, 233, 0.7)'
            ],
            borderColor: [
                'rgba(37, 99, 235, 1)',
                'rgba(5, 150, 105, 1)',
                'rgba(124, 58, 237, 1)',
                'rgba(202, 138, 4, 1)',
                'rgba(16, 185, 129, 1)',
                'rgba(14, 165, 233, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { color: '#374151' } },
            x: { ticks: { color: '#374151' } }
        }
    }
});
</script>

</body>
</html>
