<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Agent') {
    header("Location: ../security/login.php");
    exit;
}

$conn->select_db("football_agency");

$players = $conn->query("
    SELECT p.player_id, u.name, p.position 
    FROM players p 
    JOIN users u ON p.user_id = u.id
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Players</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

<a href="dashboard.php" class="mb-6 inline-block bg-gray-700 text-white px-5 py-2 rounded-lg shadow">← Back</a>

<h2 class="text-2xl font-bold mb-6">All Players</h2>

<div class="grid md:grid-cols-3 gap-6">
<?php while ($row = $players->fetch_assoc()): ?>
    <div class="bg-white p-6 rounded-xl shadow hover:shadow-xl">
        <h3 class="text-xl font-bold"><?= htmlspecialchars($row['name']) ?></h3>
        <p class="text-gray-600">Position: <?= htmlspecialchars($row['position']) ?></p>
    </div>
<?php endwhile; ?>
</div>

</body>
</html>
