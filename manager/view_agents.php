<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Club Manager') {
    header("Location: ../security/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Manager club
$manager = $conn->query("SELECT * FROM club_managers WHERE user_id = $user_id")->fetch_assoc();
$club_id = $manager['club_id'] ?? null;

if(!$club_id) die("No club assigned.");

// Fetch agents assigned to club's players with user details
$agents = $conn->query("
    SELECT DISTINCT a.agent_id, u.name, u.email
    FROM agents a
    JOIN users u ON u.id = a.user_id
    JOIN players p ON a.agent_id = p.agent_id
    WHERE p.club_id = $club_id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Agents in Club</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow-lg">
    <h2 class="text-2xl font-bold mb-6">Agents in Your Club</h2>

    <div class="mb-4">
        <a href="dashboard.php" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 transition shadow">← Back to Dashboard</a>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <?php while($a = $agents->fetch_assoc()): ?>
            <div class="bg-green-50 p-4 rounded shadow hover:shadow-lg transition">
                <h3 class="text-lg font-semibold mb-2"><?= htmlspecialchars($a['name'] ?? "Agent ".$a['agent_id']) ?></h3>
                <p class="text-gray-700"><strong>ID:</strong> <?= $a['agent_id'] ?></p>
                <p class="text-gray-700"><strong>Email:</strong> <?= htmlspecialchars($a['email'] ?? "-") ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
