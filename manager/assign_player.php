<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Club Manager') {
    header("Location: ../security/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Get manager club
$manager = $conn->query("SELECT * FROM club_managers WHERE user_id = $user_id")->fetch_assoc();
$club_id = $manager['club_id'] ?? null;

if(!$club_id) die("No club assigned. Contact admin.");

// Handle form submission
if(isset($_POST['assign'])){
    $player_id = $_POST['player_id'];
    $agent_id = $_POST['agent_id'];
    $conn->query("UPDATE players SET agent_id = $agent_id WHERE player_id = $player_id AND club_id = $club_id");
    $msg = "✅ Player assigned to agent successfully!";
}

// Fetch club players
$players = $conn->query("SELECT * FROM players WHERE club_id = $club_id");

// Fetch all agents
$agents = $conn->query("SELECT * FROM agents");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Assign Player → Agent</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow-lg">
    <h2 class="text-2xl font-bold mb-6">Assign Player → Agent</h2>

    <?php if(isset($msg)) echo "<p class='text-green-600 mb-4 font-semibold'>$msg</p>"; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block mb-2 font-semibold">Select Player:</label>
            <select name="player_id" class="w-full p-2 border rounded hover:border-green-500 transition">
                <?php while($p = $players->fetch_assoc()): ?>
                    <option value="<?= $p['player_id'] ?>"><?= $p['player_id'] ?> - <?= $p['position'] ?> - <?= $p['age'] ?>y</option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label class="block mb-2 font-semibold">Select Agent:</label>
            <select name="agent_id" class="w-full p-2 border rounded hover:border-green-500 transition">
                <?php while($a = $agents->fetch_assoc()): ?>
                    <option value="<?= $a['agent_id'] ?>"><?= $a['name'] ?? "Agent ".$a['agent_id'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="flex items-center space-x-4">
            <button type="submit" name="assign" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition shadow">Assign Player</button>
            <a href="dashboard.php" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400 transition shadow">← Back to Dashboard</a>
        </div>
    </form>
</div>

</body>
</html>
