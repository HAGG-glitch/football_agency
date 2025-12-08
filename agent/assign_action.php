<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Agent') {
    header("Location: ../security/login.php");
    exit;
}

include '../backend/config.php';
$conn->select_db('football_agency');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $player_id = intval($_POST['player_id']);
    $agent_id  = intval($_POST['agent_id']);

    // Check if player is already assigned
    $check = $conn->prepare("SELECT COUNT(*) FROM player_agent_assignments WHERE player_id = ?");
    $check->bind_param("i", $player_id);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count > 0) {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => "Player is already assigned to an agent."
        ];
        header("Location: dashboard.php");
        exit;
    }

    // Insert into player_agent_assignments
    $stmt = $conn->prepare("INSERT INTO player_agent_assignments (player_id, agent_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $player_id, $agent_id);

    if ($stmt->execute()) {
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => "Player successfully assigned!"
        ];
    } else {
        $_SESSION['toast'] = [
            'type' => 'error',
            'message' => "Failed to assign player. Please try again."
        ];
    }

    $stmt->close();
    header("Location: dashboard.php");
    exit;
} else {
    header("Location: assignment.php");
    exit;
}
