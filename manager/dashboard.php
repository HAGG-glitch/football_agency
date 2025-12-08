<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Club Manager') {
    header("Location: ../security/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Manager record
$manager = $conn->query("SELECT * FROM managers WHERE user_id = $user_id")->fetch_assoc();
if (!$manager) {
    die("Manager record not found. Please contact admin.");
}


// Check if manager has a club assigned
$club_id = $manager['club_id'] ?? null;

// Total players in club (only if club assigned)
$totalPlayers = $club_id ? $conn->query("SELECT COUNT(*) AS c FROM players WHERE club_id = $club_id")->fetch_assoc()['c'] : 0;

// Total agents (still counts all)
$totalAgents = $conn->query("SELECT COUNT(*) AS c FROM agents")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Club Manager Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<header class="bg-navy text-white p-5 flex justify-between">
    <h1 class="text-xl font-bold">Club Manager Dashboard</h1>
    <a href="../security/logout.php" class="bg-red-500 px-4 py-2 rounded">Logout</a>
</header>

<main class="max-w-6xl mx-auto p-6">

    <div class="grid md:grid-cols-3 gap-6">

        <div class="bg-white p-6 shadow rounded text-center">
            <h3 class="text-gray-600">Players in Your Club</h3>
            <p class="text-4xl font-bold text-navy"><?= $totalPlayers ?></p>
        </div>

        <div class="bg-white p-6 shadow rounded text-center">
            <h3 class="text-gray-600">Available Agents</h3>
            <p class="text-4xl font-bold text-navy"><?= $totalAgents ?></p>
        </div>

        <a href="assign_player.php" class="bg-white p-6 shadow rounded hover:bg-gray-200 text-center">
            Assign Player → Agent
        </a>

    </div>

    <h2 class="text-2xl font-bold mt-10">Management</h2>

    <div class="grid md:grid-cols-3 gap-6 mt-4">

        <a href="manage_player.php" class="bg-white p-6 rounded shadow hover:bg-gray-200 text-center">
            Manage Club Players
        </a>

        <a href="view_agent.php" class="bg-white p-6 rounded shadow hover:bg-gray-200 text-center">
            View Agents
        </a>

    </div>

    <?php if(!$club_id): ?>
        <p class="text-red-600 mt-6 font-semibold">You currently have no club assigned. Please contact admin to assign a club.</p>
    <?php endif; ?>

</main>

</body>
</html>
