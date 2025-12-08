<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Agent') {
    header("Location: ../security/login.php");
    exit;
}

$conn->select_db("football_agency");

// Load players
$players = $conn->query("
    SELECT p.player_id, u.name AS player_name 
    FROM players p
    JOIN users u ON p.user_id = u.id
");

// Load existing contracts
$contracts = $conn->query("
    SELECT c.*, u.name AS player_name 
    FROM contracts c
    JOIN players p ON c.player_id = p.player_id
    JOIN users u ON p.user_id = u.id
    ORDER BY c.created_at DESC
");

// Expiring contracts (yellow = <90 days, red = <30 days)
$expiring = $conn->query("
    SELECT u.name AS player_name, c.end_date
    FROM contracts c
    JOIN players p ON c.player_id = p.player_id
    JOIN users u ON p.user_id = u.id
    WHERE c.end_date <= DATE_ADD(CURDATE(), INTERVAL 90 DAY)
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Manage Contracts</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-10">

<a href="dashboard.php" class="mb-6 inline-block bg-gray-700 text-white px-5 py-2 rounded-lg shadow">← Back</a>

<div class="max-w-4xl mx-auto space-y-12">

    <!-- Expiring Contracts Warning -->
    <?php if ($expiring->num_rows > 0): ?>
    <div class="p-6 bg-red-100 border-l-4 border-red-600 rounded-lg">
        <h3 class="text-red-700 font-bold text-lg">⚠ Contracts Expiring Soon</h3>
        <ul class="mt-2 text-red-600">
            <?php while ($e = $expiring->fetch_assoc()): ?>
                <li>
                    <strong><?= $e['player_name'] ?></strong> — expires on <?= $e['end_date'] ?>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- Add Contract Form -->
    <div class="bg-white p-10 rounded-xl shadow">
        <h2 class="text-2xl font-bold mb-4">Create New Contract</h2>

        <form action="save_contract.php" method="POST" enctype="multipart/form-data" class="space-y-5">

            <div>
                <label class="font-semibold">Player</label>
                <select name="player_id" required class="w-full border px-4 py-2 rounded-lg">
                    <?php while ($p = $players->fetch_assoc()): ?>
                        <option value="<?= $p['player_id'] ?>"><?= $p['player_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label class="font-semibold">Contract Duration (Months)</label>
                <input type="number" name="duration" min="1" required class="w-full border px-4 py-2 rounded-lg">
            </div>

            <div>
                <label class="font-semibold">Contract Value ($)</label>
                <input type="number" name="value" min="1" required class="w-full border px-4 py-2 rounded-lg">
            </div>

            <div>
                <label class="font-semibold">Upload Contract File</label>
                <input type="file" name="contract_file" accept=".pdf,.doc,.docx" class="w-full border px-4 py-2 rounded-lg">
            </div>

            <button class="w-full bg-yellow-600 text-white py-3 rounded-lg font-semibold hover:bg-yellow-700">
                Save Contract
            </button>

        </form>
    </div>

    <!-- Contract Table -->
    <div class="bg-white p-10 rounded-xl shadow">
        <h2 class="text-2xl font-bold mb-4">Existing Contracts</h2>

        <table class="w-full bg-white rounded-lg overflow-hidden">
            <thead class="bg-gray-900 text-white">
                <tr>
                    <th class="p-3">Player</th>
                    <th class="p-3">Duration</th>
                    <th class="p-3">Value</th>
                    <th class="p-3">End Date</th>
                    <th class="p-3">File</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($c = $contracts->fetch_assoc()): 
                      $color = "text-green-700";
                      if ($c['end_date'] <= date("Y-m-d", strtotime("+30 days"))) $color = "text-red-700 font-bold";
                      else if ($c['end_date'] <= date("Y-m-d", strtotime("+90 days"))) $color = "text-yellow-600 font-bold";
                ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3"><?= $c['player_name'] ?></td>
                    <td class="p-3"><?= $c['duration_months'] ?> months</td>
                    <td class="p-3">$<?= number_format($c['contract_value']) ?></td>
                    <td class="p-3 <?= $color ?>"><?= $c['end_date'] ?></td>

                    <td class="p-3">
                        <?php if ($c['contract_file']): ?>
                            <a href="../uploads/contracts/<?= $c['contract_file'] ?>" class="text-blue-600 underline" target="_blank">
                                Download
                            </a>
                        <?php else: ?>—<?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>

        </table>
    </div>

</div>

</body>
</html>
