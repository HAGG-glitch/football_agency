<?php
session_start();
include '../backend/config.php';
$conn->select_db('football_agency');

$errors = [];
$success = '';
$redirect = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $role  = $_POST['role'] ?? 'Player';

    // Validation
    if (empty($name)) $errors[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address.";
    if (strlen($pass) < 6) $errors[] = "Password must be at least 6 characters.";

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $errors[] = "This email is already registered.";
    }
    $stmt->close();

    if (empty($errors)) {
        // Insert user
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hash, $role);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            // Auto-login
            $_SESSION['user'] = [
                'id' => $user_id,
                'name' => $name,
                'email' => $email,
                'role' => $role
            ];

            // Auto-create player record if role is Player
            if ($role === 'Player') {
                $player_stmt = $conn->prepare("
                    INSERT INTO players (user_id, age, position, nationality, height, weight, image)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $default_age = 18;
                $default_position = "Forward";
                $default_nat = "Unknown";
                $default_height = "170";
                $default_weight = "70";
                $default_image = "../assets/default_player.jpg";

                $player_stmt->bind_param(
                    "issssss",
                    $user_id,
                    $default_age,
                    $default_position,
                    $default_nat,
                    $default_height,
                    $default_weight,
                    $default_image
                );

                if ($player_stmt->execute()) {
                    $player_id = $conn->insert_id;

                    // Create default stats
                    $stats_stmt = $conn->prepare("INSERT INTO player_stats (player_id) VALUES (?)");
                    $stats_stmt->bind_param("i", $player_id);
                    $stats_stmt->execute();
                    $stats_stmt->close();
                }
                $player_stmt->close();
            } elseif ($role === 'Agent') {
                $stmtAgent = $conn->prepare("
        INSERT INTO agents (user_id, license_no, experience_years, agency_name)
        VALUES (?, ?, ?, ?)
    ");
                $license_no = "Pending";       // default or empty
                $experience_years = 0;
                $agency_name = "Unknown";
                $stmtAgent->bind_param("isis", $user_id, $license_no, $experience_years, $agency_name);
                $stmtAgent->execute();
                $stmtAgent->close();
            } elseif ($role === 'Club Manager') {
                $stmtManager = $conn->prepare("
        INSERT INTO managers (user_id, club_name, experience_years)
        VALUES (?, ?, ?)
    ");
                $club_name = "Unknown";
                $experience_years = 0;
                $stmtManager->bind_param("ssi", $user_id, $club_name, $experience_years);
                $stmtManager->execute();
                $stmtManager->close();
            }

            // Set dashboard redirect
            switch ($role) {
                case 'Player':
                    $redirect = '../player/dashboard.php';
                    break;
                case 'Agent':
                    $redirect = '../agent/dashboard.php';
                    break;
                case 'Club Manager':
                    $redirect = '../manager/dashboard.php';
                    break;
                default:
                    $redirect = '../security/login.php';
            }

            $success = "Registration successful! Redirecting to your dashboard...";
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!-- HTML part remains mostly the same with notifications and Tailwind styling -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - Football Agency</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-cover bg-center bg-no-repeat relative" style="background-image: url('../assets/img/mk.jpg');">

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative flex items-center justify-center min-h-screen px-4">
        <div class="bg-white bg-opacity-90 shadow-xl rounded-lg max-w-md w-full p-8 z-10">

            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img src="../assets/img/sefa.png" alt="Football Agency Logo" class="h-16 w-auto">
            </div>

            <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Create an Account</h2>

            <!-- Notifications -->
            <div id="notification" class="fixed top-5 right-5 w-80 z-50"></div>

            <form method="post" class="space-y-4">
                <input type="text" name="name" placeholder="Full Name" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition" />
                <input type="email" name="email" placeholder="Email" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition" />
                <input type="password" name="password" placeholder="Password" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition" />
                <select name="role" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                    <option>Player</option>
                    <option>Agent</option>
                    <option>Club Manager</option>
                </select>
                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition font-semibold">Register</button>
            </form>

            <p class="text-center text-gray-500 mt-4">
                Already have an account? <a href="login.php" class="text-blue-600 hover:underline font-medium">Login</a>
            </p>
        </div>
    </div>

    <script>
        function showNotification(message, type = "success") {
            const notif = document.createElement('div');
            notif.className = `mb-3 px-4 py-3 rounded shadow text-white font-medium transition-all duration-300 ${
        type === "success" ? "bg-green-600" : "bg-red-600"
    }`;
            notif.innerHTML = message;
            document.getElementById('notification').appendChild(notif);
            setTimeout(() => notif.remove(), 4000);
        }

        // PHP errors
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $err): ?>
                showNotification("<?= addslashes($err) ?>", "error");
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            showNotification("<?= addslashes($success) ?>", "success");

            // Redirect after notification + progress bar
            setTimeout(() => {
                // Create overlay with progress bar
                const overlay = document.createElement('div');
                overlay.className = "fixed inset-0 bg-black/70 flex flex-col justify-center items-center z-50";
                overlay.innerHTML = `
        <p class="text-white font-semibold mb-4">Redirecting to your dashboard...</p>
        <div class="w-64 h-4 bg-gray-300 rounded overflow-hidden">
            <div id="progressBar" class="h-full bg-green-500 w-0"></div>
        </div>
    `;
                document.body.appendChild(overlay);

                // Animate progress bar over 5s
                const progressBar = document.getElementById('progressBar');
                let width = 0;
                const interval = setInterval(() => {
                    if (width >= 100) {
                        clearInterval(interval);
                        window.location.href = "<?= $redirect ?>";
                    } else {
                        width += 1;
                        progressBar.style.width = width + "%";
                    }
                }, 50); // 50ms * 100 = 5s
            }, 4000); // wait for notification to disappear
        <?php endif; ?>
    </script>

</body>

</html>