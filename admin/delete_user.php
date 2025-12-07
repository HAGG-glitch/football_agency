<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header("Location: ../security/login.php");
    exit;
}
include '../backend/config.php';
$conn->select_db('football_agency');

$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header("Location: manage_users.php");
exit;
?>
