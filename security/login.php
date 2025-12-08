<?php
session_start();
include '../backend/config.php';
$conn->select_db('football_agency');

$errors = [];
$success = '';
$redirect = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if (empty($email)) $errors[] = "Email is required.";
    if (empty($pass)) $errors[] = "Password is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {
            $user = $res->fetch_assoc();
            if (password_verify($pass, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];

                $success = "Login successful! Redirecting to your dashboard...";

                // Redirect by role
                switch($user['role']){
                    case 'Player': $redirect = '../player/dashboard.php'; break;
                    case 'Agent': $redirect = '../agent/dashboard.php'; break;
                    case 'Club Manager': $redirect = '../manager/dashboard.php'; break;
                    case 'Admin': $redirect = '../admin/dashboard.php'; break;
                    default: $redirect = '../security/login.php';
                }
            } else {
                $errors[] = "Invalid credentials.";
            }
        } else {
            $errors[] = "No user found with that email.";
        }
        $stmt->close();
    }

    $conn->close();
}
?>

<!-- HTML part remains the same with notifications, progress bar, and Tailwind styling -->


<!-- HTML part remains the same with notifications, progress bar, and Tailwind styling -->


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - Football Agency</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-cover bg-center bg-no-repeat relative" style="background-image: url('../assets/img/bg-image.jpeg');">

<!-- Overlay -->
<div class="absolute inset-0 bg-black/50"></div>

<div class="relative flex items-center justify-center min-h-screen px-4">
    <div class="bg-white bg-opacity-90 shadow-xl rounded-lg max-w-md w-full p-8 z-10">

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="../assets/img/sefa.png" alt="Football Agency Logo" class="h-16 w-auto">
        </div>

        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Welcome Back</h2>

        <!-- Notifications -->
        <div id="notification" class="fixed top-5 right-5 w-80 z-50"></div>

        <form method="post" class="space-y-4">
            <input type="email" name="email" placeholder="Enter your email" required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
            <input type="password" name="password" placeholder="Enter your password" required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold">Login</button>
        </form>

        <p class="text-center text-gray-500 mt-4">
            Don't have an account? <a href="register.php" class="text-blue-600 hover:underline font-medium">Register</a>
        </p>
    </div>
</div>

<script>
function showNotification(message, type="error") {
    const notif = document.createElement('div');
    notif.className = `mb-3 px-4 py-3 rounded shadow text-white font-medium transition-all duration-300 ${
        type === "success" ? "bg-green-600" : "bg-red-600"
    }`;
    notif.innerHTML = message;
    document.getElementById('notification').appendChild(notif);
    setTimeout(() => notif.remove(), 4000);
}

// Show PHP errors
<?php if(!empty($errors)): ?>
<?php foreach($errors as $err): ?>
showNotification("<?= addslashes($err) ?>", "error");
<?php endforeach; ?>
<?php endif; ?>

// Show success and redirect
<?php if(!empty($success)): ?>
showNotification("<?= addslashes($success) ?>", "success");

// Wait for notification to disappear before redirecting with progress bar
setTimeout(() => {
    // Overlay with progress bar
    const overlay = document.createElement('div');
    overlay.className = "fixed inset-0 bg-black/70 flex flex-col justify-center items-center z-50";
    overlay.innerHTML = `
        <p class="text-white font-semibold mb-4">Redirecting to your dashboard...</p>
        <div class="w-80 bg-gray-300 rounded-full overflow-hidden h-6 relative">
            <div id="progress-bar" class="bg-green-500 h-6 w-0 flex items-center justify-center text-white font-semibold"></div>
        </div>
    `;
    document.body.appendChild(overlay);

    let progress = 0;
    const progressBar = document.getElementById('progress-bar');
    const interval = setInterval(() => {
        progress++;
        progressBar.style.width = progress + '%';
        progressBar.textContent = progress + '%';
        if(progress >= 100){
            clearInterval(interval);
            window.location.href = "<?= $redirect ?>";
        }
    }, 50); // 50ms x 100 = 5 seconds
}, 4000); // wait 4s for notification
<?php endif; ?>
</script>

</body>
</html>
