<?php
session_start();
include '../backend/config.php';

// Redirect if not logged in or not a player
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Player') {
    header("Location: ../security/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'] ?? 0;
if (!$user_id) die("User ID not found. Please log in.");

// Fetch player record
$stmt = $conn->prepare("SELECT * FROM players WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$player = $stmt->get_result()->fetch_assoc();
if (!$player) die("Player not found.");

$player_id = $player['player_id'];

// Fetch stats and auto-create if missing
$stats = $conn->query("SELECT * FROM player_stats WHERE player_id = $player_id")->fetch_assoc();
if (!$stats) {
    $conn->query("INSERT INTO player_stats (player_id) VALUES ($player_id)");
    $stats = $conn->query("SELECT * FROM player_stats WHERE player_id = $player_id")->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // BASIC INFO
    $name = $conn->real_escape_string($_POST['name']);
    $nationality = $conn->real_escape_string($_POST['nationality']);
    $position = $conn->real_escape_string($_POST['position']);
    $age = intval($_POST['age']);
    $height = $conn->real_escape_string($_POST['height']);
    $weight = $conn->real_escape_string($_POST['weight']);

    // IMAGE UPLOAD
    $imagePath = $player['image'];
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $file_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $imagePath = $target_file;
        }
    }

    // UPDATE PLAYERS TABLE
    $playerFields = [
        'nationality' => $nationality,
        'position' => $position,
        'age' => $age,
        'height' => $height,
        'weight' => $weight,
        'image' => $imagePath,
        'goals' => $_POST['goals'] ?? 0,
        'assists' => $_POST['assists'] ?? 0,
        'matches' => $_POST['matches'] ?? 0,
        'minutes_played' => $_POST['minutes_played'] ?? 0,
        'clean_sheets' => $_POST['clean_sheets'] ?? 0,
        'yellow_cards' => $_POST['yellow_cards'] ?? 0,
        'red_cards' => $_POST['red_cards'] ?? 0,
        'speed' => $_POST['speed'] ?? 0,
        'fitness_level' => $_POST['fitness_level'] ?? 0,
        'injury_history' => $conn->real_escape_string($_POST['injury_history'] ?? '')
    ];

    $updatePlayerSQL = [];
    foreach ($playerFields as $field => $value) {
        $value = $conn->real_escape_string($value);
        $updatePlayerSQL[] = "$field='$value'";
    }
    $conn->query("UPDATE players SET " . implode(", ", $updatePlayerSQL) . " WHERE player_id=$player_id");

    // UPDATE USER NAME
    $conn->query("UPDATE users SET name='$name' WHERE id=$user_id");
    $_SESSION['user']['name'] = $name; // update session

    // UPDATE PLAYER_STATS TABLE
    $statsFields = [
        'shots_on_target','conversion_rate','dribbles','pass_accuracy',
        'chances_created','tackles_won','clearances','blocks',
        'interceptions','saves','save_percentage'
    ];

    $updateStatsSQL = [];
    foreach ($statsFields as $field) {
        $value = $conn->real_escape_string($_POST[$field] ?? 0);
        $updateStatsSQL[] = "$field='$value'";
    }

    $conn->query("UPDATE player_stats SET " . implode(", ", $updateStatsSQL) . " WHERE player_id=$player_id");

    // REDIRECT TO DASHBOARD AFTER SAVE
    header("Location: dashboard.php?updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Edit Profile</h2>

    <form method="POST" enctype="multipart/form-data" class="space-y-4">

        <!-- NAME -->
        <div>
            <label class="block font-semibold">Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($_SESSION['user']['name']) ?>" class="w-full border p-2 rounded">
        </div>

        <!-- NATIONALITY -->
        <div>
            <label class="block font-semibold">Nationality</label>
            <input type="text" name="nationality" value="<?= htmlspecialchars($player['nationality']) ?>" class="w-full border p-2 rounded">
        </div>

        <!-- POSITION -->
        <div>
            <label class="block font-semibold">Position</label>
            <select name="position" class="w-full border p-2 rounded">
                <?php 
                $positions=['Goalkeeper','Defender','Midfielder','Forward']; 
                foreach($positions as $pos): ?>
                    <option value="<?= $pos ?>" <?= $player['position']==$pos?'selected':'' ?>>
                        <?= $pos ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- AGE + HEIGHT + WEIGHT + IMAGE -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold">Age</label>
                <input type="number" name="age" value="<?= htmlspecialchars($player['age']) ?>" class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-semibold">Height (cm)</label>
                <input type="text" name="height" value="<?= htmlspecialchars($player['height']) ?>" class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-semibold">Weight (kg)</label>
                <input type="text" name="weight" value="<?= htmlspecialchars($player['weight']) ?>" class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-semibold">Profile Image</label>
                <input type="file" name="image" id="imageInput" class="w-full border p-2 rounded">
                <img id="preview" src="<?= $player['image'] ?: '../assets/img/default-avatar.png' ?>" 
                     class="mt-2 w-32 h-32 object-cover rounded-full border">
            </div>
        </div>

        <!-- STATS -->
        <h3 class="text-xl font-bold mt-4 mb-2">Stats</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <?php
            $fields = [
                'goals'=>'Goals','assists'=>'Assists','matches'=>'Matches','minutes_played'=>'Minutes Played',
                'clean_sheets'=>'Clean Sheets','yellow_cards'=>'Yellow Cards','red_cards'=>'Red Cards',
                'shots_on_target'=>'Shots on Target','conversion_rate'=>'Conversion Rate (%)','dribbles'=>'Dribbles',
                'pass_accuracy'=>'Pass Accuracy (%)','chances_created'=>'Chances Created','tackles_won'=>'Tackles Won',
                'clearances'=>'Clearances','blocks'=>'Blocks','interceptions'=>'Interceptions','saves'=>'Saves',
                'save_percentage'=>'Save %','speed'=>'Top Speed (km/h)','fitness_level'=>'Fitness Level',
                'injury_history'=>'Injury History'
            ];

            foreach($fields as $key => $label): 
                $value = $stats[$key] ?? $player[$key] ?? '';
            ?>
                <div>
                    <label class="block font-semibold"><?= $label ?></label>
                    <input type="text" name="<?= $key ?>" value="<?= htmlspecialchars($value) ?>" class="w-full border p-2 rounded">
                </div>
            <?php endforeach; ?>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="flex justify-between mt-4">
            <a href="dashboard.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Changes</button>
        </div>
    </form>
</div>

<script>
// LIVE IMAGE PREVIEW
document.getElementById('imageInput').addEventListener('change', function(e) {
    const [file] = e.target.files;
    if (file) document.getElementById('preview').src = URL.createObjectURL(file);
});
</script>

</body>
</html>
