<?php
// insert_sample_users.php
include 'config.php';
$conn->select_db('football_agency');

// Sample users (plaintext passwords will be hashed)
$users = [
    ['John Admin', 'admin1@example.com', 'adminpass1', 'Admin'],
    ['Sarah Admin', 'admin2@example.com', 'adminpass2', 'Admin'],

    ['Michael Striker', 'player1@example.com', 'playerpass1', 'Player'],
    ['Samuel Midfielder', 'player2@example.com', 'playerpass2', 'Player'],

    ['Alex Agent', 'agent1@example.com', 'agentpass1', 'Agent'],
    ['Rico Agent', 'agent2@example.com', 'agentpass2', 'Agent'],

    ['Chris Manager', 'manager1@example.com', 'managerpass1', 'Club Manager'],
    ['Daniel Manager', 'manager2@example.com', 'managerpass2', 'Club Manager']
];

$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

foreach ($users as $u) {
    $hashed = password_hash($u[2], PASSWORD_DEFAULT);
    $stmt->bind_param("ssss", $u[0], $u[1], $hashed, $u[3]);
    // ignore duplicate email errors to allow re-running safely
    try {
        $stmt->execute();
    } catch (Exception $e) {
        // continue if duplicate or other DB error
    }
}

$stmt->close();
$conn->close();

echo "✔ Sample users inserted (passwords hashed).";
?>
