<?php
require_once __DIR__ . '/db.php';
$user = current_user();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Blood Donation Platform</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/custom.css" />
</head>
<body class="bg-gray-950 text-white overflow-x-hidden">
  <?php if ($user): ?>
  <!-- Sidebar -->
  <div id="sidebar" class="fixed left-0 top-0 w-64 h-screen bg-gray-900 border-r border-gray-800 z-40 transition-all duration-300 flex flex-col overflow-y-auto md:translate-x-0 -translate-x-full">
    <div class="border-b border-gray-800 p-6 text-center flex-shrink-0 mt-16 md:mt-0">
      <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-700 rounded-full mx-auto mb-3 flex items-center justify-center overflow-hidden flex-shrink-0">
        <i class="fas fa-user text-white text-2xl"></i>
      </div>
      <h3 class="text-white font-semibold text-sm mb-1"><?php echo htmlspecialchars($user['full_name']); ?></h3>
      <p class="text-gray-400 text-xs uppercase tracking-wide"><?php echo htmlspecialchars($user['user_type']); ?></p>
    </div>
    
    <nav class="flex-1 py-4 px-0">
      <a href="dashboard.php" class="nav-item flex items-center px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-800 transition-all duration-200 <?php echo $current_page == 'dashboard.php' ? 'bg-gray-800 text-white' : ''; ?>">
        <i class="fas fa-tachometer-alt w-5 text-center mr-4"></i>
        <span class="nav-text">Dashboard</span>
      </a>
      
      <?php if ($user['user_type'] === 'Donor'): ?>
        <a href="donate.php" class="nav-item flex items-center px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-800 transition-all duration-200 <?php echo $current_page == 'donate.php' ? 'bg-gray-800 text-white' : ''; ?>">
          <i class="fas fa-hand-holding-heart w-5 text-center mr-4"></i>
          <span class="nav-text">Donate Blood</span>
        </a>
        <a href="my_donations.php" class="nav-item flex items-center px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-800 transition-all duration-200 <?php echo $current_page == 'my_donations.php' ? 'bg-gray-800 text-white' : ''; ?>">
          <i class="fas fa-history w-5 text-center mr-4"></i>
          <span class="nav-text">My Donations</span>
        </a>
      <?php elseif ($user['user_type'] === 'Recipient'): ?>
        <a href="requests.php" class="nav-item flex items-center px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-800 transition-all duration-200 <?php echo $current_page == 'requests.php' ? 'bg-gray-800 text-white' : ''; ?>">
          <i class="fas fa-paper-plane w-5 text-center mr-4"></i>
          <span class="nav-text">Request Blood</span>
        </a>
        <a href="my_requests.php" class="nav-item flex items-center px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-800 transition-all duration-200 <?php echo $current_page == 'my_requests.php' ? 'bg-gray-800 text-white' : ''; ?>">
          <i class="fas fa-list w-5 text-center mr-4"></i>
          <span class="nav-text">My Requests</span>
        </a>
      <?php elseif ($user['user_type'] === 'Admin'): ?>
        <a href="admin_users.php" class="nav-item flex items-center px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-800 transition-all duration-200 <?php echo $current_page == 'admin_users.php' ? 'bg-gray-800 text-white' : ''; ?>">
          <i class="fas fa-users-cog w-5 text-center mr-4"></i>
          <span class="nav-text">User Management</span>
        </a>
        <a href="reports.php" class="nav-item flex items-center px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-800 transition-all duration-200 <?php echo $current_page == 'reports.php' ? 'bg-gray-800 text-white' : ''; ?>">
          <i class="fas fa-chart-bar w-5 text-center mr-4"></i>
          <span class="nav-text">Reports</span>
        </a>
      <?php elseif ($user['user_type'] === 'Staff'): ?>
        <a href="admin_actions.php" class="nav-item flex items-center px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-800 transition-all duration-200 <?php echo $current_page == 'admin_actions.php' ? 'bg-gray-800 text-white' : ''; ?>">
          <i class="fas fa-tasks w-5 text-center mr-4"></i>
          <span class="nav-text">Blood Operations</span>
        </a>
      <?php endif; ?>
      
      <?php if ($user['user_type'] !== 'Admin'): ?>
        <a href="inventory.php" class="nav-item flex items-center px-6 py-3 text-gray-300 hover:text-white hover:bg-gray-800 transition-all duration-200 <?php echo $current_page == 'inventory.php' ? 'bg-gray-800 text-white' : ''; ?>">
          <i class="fas fa-warehouse w-5 text-center mr-4"></i>
          <span class="nav-text">Blood Inventory</span>
        </a>
      <?php endif; ?>
    </nav>
    
    <div class="border-t border-gray-800 p-4 flex-shrink-0">
      <a href="logout.php" class="flex items-center px-4 py-3 text-red-400 hover:text-red-300 hover:bg-red-900/20 rounded-lg transition-all duration-200">
        <i class="fas fa-sign-out-alt w-5 text-center mr-4"></i>
        <span class="nav-text">Logout</span>
      </a>
    </div>
  </div>
  
  <!-- Mobile Menu Toggle -->
  <button id="menuToggle" class="fixed top-5 left-5 w-11 h-11 bg-gray-800 border border-gray-700 rounded-lg flex items-center justify-center md:hidden z-50 hover:bg-gray-700 transition-colors">
    <i class="fas fa-bars text-white"></i>
  </button>
  
  <!-- Overlay -->
  <div id="overlay" class="fixed inset-0 bg-black/60 z-30 md:hidden opacity-0 invisible transition-all duration-300"></div>
  
  <!-- Main Content -->
  <div class="md:ml-64 transition-all duration-300">
    <!-- Top Header -->
    <div class="bg-gray-900 border-b border-gray-800 sticky top-0 z-20">
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 p-6 mt-16 md:mt-0">
        <div class="flex-1">
          <h1 class="text-3xl font-bold text-white mb-1">Blood Donation Platform</h1>
          <p class="text-gray-400 text-sm"><?php echo htmlspecialchars($user['user_type']); ?> Dashboard</p>
        </div>
        <div class="flex items-center gap-4 w-full md:w-auto">
          <div class="flex items-center gap-3 bg-gray-800 px-3 py-2 rounded-lg">
            <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center">
              <i class="fas fa-user text-white text-sm"></i>
            </div>
            <span class="text-sm text-white hidden sm:inline">Hi, <?php echo htmlspecialchars($user['full_name']); ?></span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Flash Messages -->
    <div class="p-6">
      <?php if ($m = flash('success')): ?>
        <div class="bg-green-900/30 border border-green-700 text-green-400 px-4 py-3 rounded-lg mb-4 flex items-center">
          <i class="fas fa-check-circle mr-3"></i>
          <span><?php echo htmlspecialchars($m); ?></span>
        </div>
      <?php endif; ?>
      <?php if ($m = flash('error')): ?>
        <div class="bg-red-900/30 border border-red-700 text-red-400 px-4 py-3 rounded-lg mb-4 flex items-center">
          <i class="fas fa-exclamation-circle mr-3"></i>
          <span><?php echo htmlspecialchars($m); ?></span>
        </div>
      <?php endif; ?>
  <?php else: ?>
  <!-- Guest Navigation -->
  <nav class="bg-gradient-to-r from-red-600 to-red-700 text-white p-4 shadow-lg">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <a href="index.php" class="font-bold text-xl flex items-center gap-2">
        <i class="fas fa-heartbeat"></i>
        Blood Donation
      </a>
      <div class="flex gap-4">
        <a href="register.php" class="px-4 py-2 bg-white text-red-600 rounded-lg hover:bg-gray-100 transition-colors font-medium">Register</a>
        <a href="login.php" class="px-4 py-2 bg-red-800 hover:bg-red-900 rounded-lg transition-colors font-medium">Login</a>
      </div>
    </div>
  </nav>
  <main class="max-w-7xl mx-auto p-6">
    <?php if ($m = flash('success')): ?>
      <div class="bg-green-900/30 border border-green-700 text-green-400 px-4 py-3 rounded-lg mb-4 flex items-center">
        <i class="fas fa-check-circle mr-3"></i>
        <span><?php echo htmlspecialchars($m); ?></span>
      </div>
    <?php endif; ?>
    <?php if ($m = flash('error')): ?>
      <div class="bg-red-900/30 border border-red-700 text-red-400 px-4 py-3 rounded-lg mb-4 flex items-center">
        <i class="fas fa-exclamation-circle mr-3"></i>
        <span><?php echo htmlspecialchars($m); ?></span>
      </div>
    <?php endif; ?>
  <?php endif; ?>
