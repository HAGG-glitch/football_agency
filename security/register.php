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
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email is not valid.";
    }
    if (strlen($pass) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $errors[] = "This email is already registered.";
    }
    $stmt->close();

    // Insert user if no errors
    if (empty($errors)) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hash, $role);

        if ($stmt->execute()) {
            // Auto-login
            $_SESSION['user'] = [
                'id'    => $stmt->insert_id,
                'name'  => $name,
                'email' => $email,
                'role'  => $role
            ];

            // Set dashboard redirect
            switch ($role) {
                case 'Player': $redirect = '../player/dashboard.php'; break;
                case 'Agent': $redirect = '../agent/dashboard.php'; break;
                case 'Club Manager': $redirect = '../manager/dashboard.php'; break;
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
function showNotification(message, type="success") {
    const notif = document.createElement('div');
    notif.className = `mb-3 px-4 py-3 rounded shadow text-white font-medium transition-all duration-300 ${
        type === "success" ? "bg-green-600" : "bg-red-600"
    }`;
    notif.innerHTML = message;
    document.getElementById('notification').appendChild(notif);
    setTimeout(() => notif.remove(), 4000);
}

// PHP errors
<?php if(!empty($errors)): ?>
<?php foreach($errors as $err): ?>
showNotification("<?= addslashes($err) ?>", "error");
<?php endforeach; ?>
<?php endif; ?>

<?php if(!empty($success)): ?>
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
        if(width >= 100){
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
