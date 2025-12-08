<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Agent') {
    header("Location: ../security/login.php");
    exit;
}

$conn->select_db("football_agency");

$agent_id = $_SESSION['user']['id'];

$data = $conn->query("
    SELECT u.name AS player_name, p.position, paa.assigned_at
    FROM player_agent_assignments paa
    JOIN players p ON paa.player_id = p.player_id
    JOIN users u ON p.user_id = u.id
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Assignments</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-10">
<a href="dashboard.php" class="inline-block mb-6 bg-gray-700 text-white px-5 py-2 rounded-lg shadow">← Back</a>

<h2 class="text-2xl font-bold mb-6">Player Assignments</h2>

<table class="min-w-full bg-white shadow rounded-xl overflow-hidden">
    <thead class="bg-gray-300">
        <tr>
            <th class="p-3 text-left">Player</th>
            <th class="p-3 text-left">Position</th>
            <th class="p-3 text-left">Assigned At</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $data->fetch_assoc()): ?>
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3"><?= $row['player_name'] ?></td>
                <td class="p-3"><?= $row['position'] ?></td>
                <td class="p-3"><?= $row['assigned_at'] ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
