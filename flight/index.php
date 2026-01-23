<?php
// Start buffering & session BEFORE any HTML
session_start();
ob_start();
include('admin/db_connect.php');

// Load system settings
$query = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
foreach ($query as $key => $value) {
    if(!is_numeric($key))
        $_SESSION['setting_'.$key] = $value;
}

// Page routing
$page = isset($_GET['page']) ? $_GET['page'] : "home";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('header.php'); ?>
    <style>
      header.masthead {
        background: url(assets/img/<?php echo $_SESSION['setting_cover_img'] ?>);
        background-repeat: no-repeat;
        background-size: cover;
      }
    </style>
</head>

<body id="page-top" class="bg-white text-slate-800">

  <!-- NAVBAR -->
  <nav class="w-full bg-white/95 backdrop-blur border-b sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center">
      <a href="index.php" class="flex items-center gap-2">
        <span class="font-bold tracking-wide text-2xl">
          <span class="text-brand">4</span> STAR AVIATION
          <!-- <?php echo $_SESSION['setting_name'] ?? 'B AIRWAYS'; ?> -->
        </span>
      </a>

      <ul class="ml-auto hidden md:flex items-center gap-8 font-semibold">
        <li><a href="index.php" class="hover:text-slate-900">HOME</a></li>
        <li><a href="index.php?page=flights" class="hover:text-slate-900">FLIGHT</a></li>
        <li><a href="#about" class="hover:text-slate-900">ABOUT</a></li>

        <?php if (isset($_SESSION['customer_id'])): ?>
          <li><a href="index.php?page=my_bookings" class="hover:text-slate-900">MY BOOKINGS</a></li>
          <li><a href="logout.php" class="text-brand hover:text-brandDark font-bold">LOGOUT</a></li>
        <?php else: ?>
          <li><a href="index.php?page=login" class="text-brand hover:text-brandDark font-bold">LOGIN</a></li>
          <li><a href="index.php?page=signup" class="bg-brand text-white hover:bg-brandDark px-3 py-1 rounded-md">SIGN UP</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>

  <!-- PAGE CONTENT -->
  <?php include $page . '.php'; ?>

  <!-- FOOTER -->
  <footer class="mt-8 border-t">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid md:grid-cols-3 gap-8 text-sm">
      <div>
        <h4 class="font-semibold mb-3">Company</h4>
        <ul class="space-y-2">
          <li><a href="#about" class="hover:text-slate-900">About</a></li>
          <li><a href="index.php?page=flights" class="hover:text-slate-900">Booking</a></li>
          <li><a href="index.php?page=contact" class="hover:text-slate-900">Contact</a></li>
        </ul>
      </div>

      <div>
        <h4 class="font-semibold mb-3">Account</h4>
        <ul class="space-y-2">
          <?php if (isset($_SESSION['customer_id'])): ?>
            <li><a href="index.php?page=my_bookings" class="hover:text-slate-900">My Bookings</a></li>
            <li><a href="logout.php" class="hover:text-slate-900">Logout</a></li>
          <?php else: ?>
            <li><a href="index.php?page=login" class="hover:text-slate-900">Login</a></li>
            <li><a href="index.php?page=signup" class="hover:text-slate-900">Sign Up</a></li>
          <?php endif; ?>
        </ul>
      </div>

      <div>
        <h4 class="font-semibold mb-3">Legal</h4>
        <ul class="space-y-2">
          <li><a href="#" class="hover:text-slate-900">Terms & Conditions</a></li>
          <li><a href="#" class="hover:text-slate-900">Privacy Policy</a></li>
        </ul>
      </div>
    </div>

    <div class="text-center text-xs text-slate-500 py-4">
      &copy; <?php echo date('Y'); ?> B Airways. All rights reserved.
    </div>
  </footer>

  <?php include('footer.php'); ?>

</body>
</html>

<?php 
$conn->close();
ob_end_flush(); 
?>
