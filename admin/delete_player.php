<?php
session_start();
include '../backend/config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header("Location: ../security/login.php");
    exit;
}

$id = $_GET['id'];

$conn->query("DELETE FROM players WHERE player_id=$id");

header("Location: manage_player.php");
exit;
