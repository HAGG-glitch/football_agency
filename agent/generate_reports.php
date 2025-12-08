<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Agent') {
    header("Location: ../security/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Generate Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

<a href="dashboard.php" class="mb-6 inline-block bg-gray-700 text-white px-5 py-2 rounded-lg shadow">← Back</a>

<div class="max-w-3xl mx-auto bg-white p-10 rounded-xl shadow">
    <h2 class="text-2xl font-bold mb-4">Generate Reports</h2>

    <p class="text-gray-600 mb-6">Select a report type to generate.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-xl shadow text-center font-semibold">
            Player Performance Report
        </a>
        <a class="bg-purple-600 hover:bg-purple-700 text-white p-6 rounded-xl shadow text-center font-semibold">
            Assigned Players Report
        </a>
    </div>
</div>

</body>
</html>
