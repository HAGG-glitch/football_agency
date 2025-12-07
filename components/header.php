<!-- components/header.html -->
<?php session_start(); ?>
<header class="bg-black text-white px-6 py-4 shadow-md">
  <div class="max-w-7xl mx-auto flex items-center justify-between">

    <!-- Logo + Name -->
    <div class="flex items-center space-x-3">
      <img src="assets/img/sefa.png" alt="Logo" class="h-10 w-auto" />
      <span class="text-2xl font-bold">Sierra Elite Football Agency</span>
    </div>

    <!-- Hamburger Button (Mobile) -->
    <button id="menuBtn" class="md:hidden text-white text-3xl focus:outline-none">
      ☰
    </button>

    <!-- NAV MENU -->
    <nav id="navMenu" class="hidden md:flex space-x-8 text-lg font-medium md:items-center">
      <a href="index.php" class="hover:text-lime transition">Home</a>
      <a href="aboutus.php" class="hover:text-lime transition">About</a>
      <a href="services.php" class="hover:text-lime transition">Services</a>
      <a href="contactus.php" class="hover:text-lime transition">Contact</a>

      <?php if (!empty($_SESSION['user'])): ?>
        <span class="ml-4 text-white font-semibold">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
        <a href="security/logout.php" class="hover:text-lime transition">Logout</a>
      <?php else: ?>
        <a href="security/login.php" class="hover:text-lime transition">Login</a>
        <a href="security/register.php" class="hover:text-lime transition">Register</a>
      <?php endif; ?>
    </nav>
  </div>

  <!-- MOBILE DROPDOWN MENU -->
  <div id="mobileMenu" class="hidden flex-col bg-black px-6 pb-4 space-y-4 md:hidden text-lg">
    <a href="index.php" class="hover:text-lime transition">Home</a>
    <a href="aboutus.php" class="hover:text-lime transition">About</a>
    <a href="services.php" class="hover:text-lime transition">Services</a>
    <a href="contactus.php" class="hover:text-lime transition">Contact</a>

    <?php if (!empty($_SESSION['user'])): ?>
      <span class="text-white font-semibold">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
      <a href="security/logout.php" class="hover:text-lime transition">Logout</a>
    <?php else: ?>
      <a href="security/login.php" class="hover:text-lime transition">Login</a>
      <a href="security/register.php" class="hover:text-lime transition">Register</a>
    <?php endif; ?>
  </div>

  <script>
    const btn = document.getElementById("menuBtn");
    const mobileMenu = document.getElementById("mobileMenu");

    btn.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden");
    });
  </script>
</header>
