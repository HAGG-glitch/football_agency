<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header("Location: ../security/login.php");
    exit;
}

$id = $_GET['id'];
$player = $conn->query("SELECT * FROM players WHERE player_id=$id")->fetch_assoc();

$clubs = $conn->query("SELECT * FROM clubs");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $club = $_POST['club_id'];
    $age = $_POST['age'];
    $pos = $_POST['position'];
    $nat = $_POST['nationality'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];

    $stmt = $conn->prepare("
        UPDATE players SET club_id=?, age=?, position=?, nationality=?, height=?, weight=?
        WHERE player_id=?
    ");
    $stmt->bind_param("iissssi", $club, $age, $pos, $nat, $height, $weight, $id);
    $stmt->execute();

    header("Location: manage_player.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Player</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<header class="bg-navy text-white p-5 flex justify-between">
    <h1 class="text-xl font-bold">Edit Player</h1>
    <a href="manage_player.php" class="bg-softblue px-4 py-2 rounded">Back</a>
</header>

<main class="p-6 max-w-3xl mx-auto bg-white shadow rounded">

<form method="POST" class="space-y-4">

    <label>Club</label>
    <select name="club_id" class="w-full p-2 border rounded" required>
        <?php while ($c = $clubs->fetch_assoc()): ?>
            <option value="<?= $c['club_id'] ?>" <?= $c['club_id']==$player['club_id']?'selected':'' ?>>
                <?= $c['club_name'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <input name="age" value="<?= $player['age'] ?>" class="w-full p-2 border rounded">
    <input name="position" value="<?= $player['position'] ?>" class="w-full p-2 border rounded">
    <input name="nationality" value="<?= $player['nationality'] ?>" class="w-full p-2 border rounded">
    <input name="height" value="<?= $player['height'] ?>" class="w-full p-2 border rounded">
    <input name="weight" value="<?= $player['weight'] ?>" class="w-full p-2 border rounded">

    <button class="bg-lime text-white px-4 py-2 rounded">Update</button>

</form>

</main>
</body>
</html>
