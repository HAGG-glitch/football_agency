<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header("Location: ../security/login.php");
    exit;
}

// Fetch agents with associated user names
$agents = $conn->query("
    SELECT agents.*, users.name AS user_name 
    FROM agents
    LEFT JOIN users ON agents.user_id = users.id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Agents</title>
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
    <h1 class="text-xl font-bold">Manage Agents</h1>
    <a href="dashboard.php" class="bg-softblue px-4 py-2 rounded">Back</a>
</header>

<main class="p-6 max-w-6xl mx-auto">

    <div class="flex justify-between mb-6">
        <h2 class="text-2xl font-semibold">Agents List</h2>
        <a href="add_agent.php" class="bg-lime px-4 py-2 rounded text-white">Add Agent</a>
    </div>

    <table class="w-full bg-white shadow rounded">
        <tr class="bg-gray-200 text-left">
            <th class="p-3">ID</th>
            <th class="p-3">Name</th>
            <th class="p-3">License No</th>
            <th class="p-3">Experience (Years)</th>
            <th class="p-3">Agency Name</th>
            <th class="p-3">Actions</th>
        </tr>

        <?php while ($a = $agents->fetch_assoc()): ?>
        <tr class="border-t">
            <td class="p-3"><?= $a['agent_id'] ?></td>
            <td class="p-3"><?= $a['user_name'] ?></td>
            <td class="p-3"><?= $a['license_no'] ?></td>
            <td class="p-3"><?= $a['experience_years'] ?></td>
            <td class="p-3"><?= $a['agency_name'] ?></td>

            <td class="p-3 flex gap-3">
                <a href="edit_agent.php?id=<?= $a['agent_id'] ?>" class="text-blue-600">Edit</a>
                <a href="delete_agent.php?id=<?= $a['agent_id'] ?>" class="text-red-600"
                   onclick="return confirm('Delete this agent?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>

</main>
</body>
</html>
