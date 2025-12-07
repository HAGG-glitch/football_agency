<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header("Location: ../security/login.php");
    exit;
}

$users = $conn->query("SELECT * FROM users WHERE role='Player'");
$clubs = $conn->query("SELECT * FROM clubs");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $uid = $_POST['user_id'];
    $club = $_POST['club_id'];
    $age = $_POST['age'];
    $pos = $_POST['position'];
    $nat = $_POST['nationality'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];

    $stmt = $conn->prepare("
        INSERT INTO players (user_id, club_id, age, position, nationality, height, weight)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iiissss", $uid, $club, $age, $pos, $nat, $height, $weight);
    $stmt->execute();

    header("Location: manage_player.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Player</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<header class="bg-navy text-white p-5 flex justify-between">
    <h1 class="text-xl font-bold">Add Player</h1>
    <a href="manage_player.php" class="bg-softblue px-4 py-2 rounded">Back</a>
</header>

<main class="p-6 max-w-3xl mx-auto bg-white shadow rounded">

<form method="POST" class="space-y-4">

    <label>User</label>
    <select name="user_id" class="w-full p-2 border rounded" required>
        <?php while ($u = $users->fetch_assoc()): ?>
            <option value="<?= $u['id'] ?>"><?= $u['name'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Club</label>
    <select name="club_id" class="w-full p-2 border rounded" required>
        <?php while ($c = $clubs->fetch_assoc()): ?>
            <option value="<?= $c['club_id'] ?>"><?= $c['club_name'] ?></option>
        <?php endwhile; ?>
    </select>

    <input name="age" placeholder="Age" class="w-full p-2 border rounded" required>
    <input name="position" placeholder="Position" class="w-full p-2 border rounded">
    <input name="nationality" placeholder="Nationality" class="w-full p-2 border rounded">
    <input name="height" placeholder="Height" class="w-full p-2 border rounded">
    <input name="weight" placeholder="Weight" class="w-full p-2 border rounded">

    <button class="bg-lime text-white px-4 py-2 rounded">Save</button>

</form>

</main>
</body>
</html>
