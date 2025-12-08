<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Agent') {
    header("Location: ../security/login.php");
    exit;
}

$conn->select_db("football_agency");

$player_id = $_POST['player_id'];
$goals = $_POST['goals'];
$assists = $_POST['assists'];
$matches = $_POST['matches'];

$stmt = $conn->prepare("
    INSERT INTO player_stats (player_id, goals, assists, matches_played)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("iiii", $player_id, $goals, $assists, $matches);

if ($stmt->execute()) {
    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Player stats saved!'];
} else {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Failed to save stats.'];
}

$stmt->close();
header("Location: dashboard.php");
exit;
