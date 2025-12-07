<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Agent') {
    header("Location: ../security/login.php");
    exit;
}

$agent_id = $_SESSION['user']['id'];

// Count assigned players
$sql = "
SELECT COUNT(*) AS total 
FROM player_agent_assignments paa
JOIN agents a ON paa.agent_id = a.agent_id
WHERE a.user_id = $agent_id";
$totalAssigned = $conn->query($sql)->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agent Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<header class="bg-navy p-5 text-white flex justify-between">
    <h1 class="font-bold text-xl">Agent Dashboard</h1>
    <a href="../security/logout.php" class="bg-red-500 px-4 py-2 rounded">Logout</a>
</header>

<main class="max-w-5xl mx-auto p-6">

    <div class="bg-white p-6 shadow rounded">
        <h2 class="text-2xl font-bold">Your Stats</h2>
        <p class="text-gray-500 mt-2">Players assigned to you</p>

        <div class="mt-4 text-center">
            <p class="text-5xl text-navy font-bold"><?= $totalAssigned ?></p>
        </div>
    </div>

    <h2 class="text-xl font-bold mt-8">Navigation</h2>

    <div class="grid md:grid-cols-2 gap-6 mt-4">
        <a href="assignment.php" class="bg-white p-6 shadow rounded hover:bg-gray-200">
            View Assigned Players
        </a>
        <a href="assign_action.php" class="bg-white p-6 shadow rounded hover:bg-gray-200">
            Assign Player
        </a>
    </div>

</main>

</body>
</html>
