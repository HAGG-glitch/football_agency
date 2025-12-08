<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Agent') {
    header("Location: ../security/login.php");
    exit;
}

$conn->select_db("football_agency");

$results = [];
if (isset($_GET['q'])) {
    $q = $conn->real_escape_string($_GET['q']);
    $results = $conn->query("
        SELECT u.name, p.position 
        FROM players p
        JOIN users u ON p.user_id = u.id
        WHERE u.name LIKE '%$q%' OR p.position LIKE '%$q%'
    ");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Player</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-10">
<a href="dashboard.php" class="inline-block mb-6 bg-gray-700 text-white px-5 py-2 rounded-lg shadow">← Back</a>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow">
    <h2 class="text-2xl font-bold mb-4">Search Player</h2>

    <form action="" method="GET" class="flex gap-3 mb-5">
        <input type="text" name="q" placeholder="Enter name or position..." 
            class="w-full border px-4 py-2 rounded-lg">
        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Search</button>
    </form>

    <?php if ($results): ?>
    <div class="space-y-3">
        <?php while ($row = $results->fetch_assoc()): ?>
            <div class="p-4 bg-gray-100 rounded-lg shadow">
                <p class="font-bold text-lg"><?= $row['name'] ?></p>
                <p class="text-gray-600">Position: <?= $row['position'] ?></p>
            </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
</div>

</body>
</html>
