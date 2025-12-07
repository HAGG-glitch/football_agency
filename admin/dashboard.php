<?php
session_start();
include '../backend/config.php';

// Security
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header("Location: ../security/login.php");
    exit;
}

// Fetch counts
$totalUsers = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
$totalPlayers = $conn->query("SELECT COUNT(*) AS c FROM players")->fetch_assoc()['c'];
$totalAgents = $conn->query("SELECT COUNT(*) AS c FROM agents")->fetch_assoc()['c'];
$totalAssignments = $conn->query("SELECT COUNT(*) AS c FROM player_agent_assignments")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: "#0A2647",
                        lime: "#57C84D",
                        softblue: "#144272",
                    }
                }
            }
        };
    </script>
</head>

<body class="bg-gray-100">

    <header class="bg-navy text-white p-5 flex justify-between">
        <h1 class="text-xl font-bold">Admin Dashboard</h1>
        <a href="../security/logout.php" class="bg-red-500 px-4 py-2 rounded">Logout</a>
    </header>

    <main class="p-6 max-w-6xl mx-auto">

        <!-- Stats -->
        <div class="grid md:grid-cols-4 gap-6">
            <div class="bg-white shadow p-6 rounded text-center">
                <h3 class="text-gray-600">Total Users</h3>
                <p class="text-4xl font-bold text-navy"><?= $totalUsers ?></p>
            </div>
            <div class="bg-white shadow p-6 rounded text-center">
                <h3 class="text-gray-600">Players</h3>
                <p class="text-4xl font-bold text-navy"><?= $totalPlayers ?></p>
            </div>
            <div class="bg-white shadow p-6 rounded text-center">
                <h3 class="text-gray-600">Agents</h3>
                <p class="text-4xl font-bold text-navy"><?= $totalAgents ?></p>
            </div>
            <div class="bg-white shadow p-6 rounded text-center">
                <h3 class="text-gray-600">Assignments</h3>
                <p class="text-4xl font-bold text-navy"><?= $totalAssignments ?></p>
            </div>
        </div>

        <!-- Navigation -->
        <h2 class="text-2xl font-semibold mt-10 mb-4 text-softblue">Management</h2>

        <div class="grid md:grid-cols-3 gap-6">
            <a href="manage_users.php" class="bg-white shadow p-6 rounded hover:bg-gray-200 text-center">
                Manage Users
            </a>

            <a href="manage_player.php" class="bg-white shadow p-6 rounded hover:bg-gray-200 text-center">
                Manage Players
            </a>

            <a href="manage_agent.php" class="bg-white shadow p-6 rounded hover:bg-gray-200 text-center">
                Manage Agents
            </a>

             <a href="manage_club_manager.php" class="bg-white shadow p-6 rounded hover:bg-gray-200 text-center">
                Manage Club Managers
            </a>

            <a href="manage_club.php" class="bg-white shadow p-6 rounded hover:bg-gray-200 text-center">
                Manage Clubs
            </a>

            <a href="view_assignments.php" class="bg-white shadow p-6 rounded hover:bg-gray-200 text-center">
                View Player Assignments
            </a>

            <a href="settings.php" class="bg-white shadow p-6 rounded hover:bg-gray-200 text-center">
                System Settings
            </a>
        </div>

    </main>

</body>

</html>