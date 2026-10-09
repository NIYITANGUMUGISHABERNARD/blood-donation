<?php
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); exit;
}
$user = current_user();
include 'header.php';

// Get statistics
$stats = [];
if ($user['user_type'] === 'Donor') {
    $result = $con->query("SELECT COUNT(*) as total, SUM(CASE WHEN status='Approved' THEN 1 ELSE 0 END) as approved FROM donations WHERE donor_id={$user['id']}");
    $stats = $result->fetch_assoc();
} elseif ($user['user_type'] === 'Recipient') {
    $result = $con->query("SELECT COUNT(*) as total, SUM(CASE WHEN status='Approved' THEN 1 ELSE 0 END) as approved FROM requests WHERE recipient_id={$user['id']}");
    $stats = $result->fetch_assoc();
} elseif ($user['user_type'] === 'Staff') {
    $donations = $con->query("SELECT COUNT(*) as count FROM donations WHERE status='Pending'")->fetch_assoc();
    $requests = $con->query("SELECT COUNT(*) as count FROM requests WHERE status='Pending'")->fetch_assoc();
    $stats['pending_donations'] = $donations['count'];
    $stats['pending_requests'] = $requests['count'];
}
?>

<!-- Welcome Section -->
<div class="bg-gradient-to-r from-red-600 to-red-700 rounded-lg p-8 mb-6 shadow-lg">
  <h2 class="text-3xl font-bold text-white mb-2">Welcome Back, <?=htmlspecialchars($user['full_name'])?> 👋</h2>
  <p class="text-red-100">You're logged in as <span class="font-semibold"><?=htmlspecialchars($user['user_type'])?></span></p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
  <?php if ($user['user_type'] === 'Donor'): ?>
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-200">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-gray-400 text-sm font-medium">Total Donations</h3>
        <i class="fas fa-hand-holding-heart text-red-500 text-2xl"></i>
      </div>
      <p class="text-4xl font-bold text-white"><?=$stats['total']?></p>
      <p class="text-gray-400 text-sm mt-2"><?=$stats['approved']?> Approved</p>
    </div>
    
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-200">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-gray-400 text-sm font-medium">Blood Type</h3>
        <i class="fas fa-tint text-blue-500 text-2xl"></i>
      </div>
      <p class="text-4xl font-bold text-white"><?=htmlspecialchars($user['blood_type'] ?: 'N/A')?></p>
      <p class="text-gray-400 text-sm mt-2">Your blood type</p>
    </div>
    
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-200">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-gray-400 text-sm font-medium">Status</h3>
        <i class="fas fa-check-circle text-green-500 text-2xl"></i>
      </div>
      <p class="text-2xl font-bold text-green-400">Active Donor</p>
      <p class="text-gray-400 text-sm mt-2">Thank you for saving lives</p>
    </div>
    
  <?php elseif ($user['user_type'] === 'Recipient'): ?>
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-200">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-gray-400 text-sm font-medium">Total Requests</h3>
        <i class="fas fa-paper-plane text-red-500 text-2xl"></i>
      </div>
      <p class="text-4xl font-bold text-white"><?=$stats['total']?></p>
      <p class="text-gray-400 text-sm mt-2"><?=$stats['approved']?> Approved</p>
    </div>
    
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-200">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-gray-400 text-sm font-medium">Blood Type Needed</h3>
        <i class="fas fa-tint text-blue-500 text-2xl"></i>
      </div>
      <p class="text-4xl font-bold text-white"><?=htmlspecialchars($user['blood_type'] ?: 'N/A')?></p>
      <p class="text-gray-400 text-sm mt-2">Your blood type</p>
    </div>
    
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-200">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-gray-400 text-sm font-medium">Status</h3>
        <i class="fas fa-user-check text-green-500 text-2xl"></i>
      </div>
      <p class="text-2xl font-bold text-green-400">Recipient</p>
      <p class="text-gray-400 text-sm mt-2">We're here to help</p>
    </div>
    
  <?php elseif ($user['user_type'] === 'Staff'): ?>
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-200">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-gray-400 text-sm font-medium">Pending Donations</h3>
        <i class="fas fa-clock text-yellow-500 text-2xl"></i>
      </div>
      <p class="text-4xl font-bold text-white"><?=$stats['pending_donations']?></p>
      <p class="text-gray-400 text-sm mt-2">Awaiting approval</p>
    </div>
    
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-200">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-gray-400 text-sm font-medium">Pending Requests</h3>
        <i class="fas fa-exclamation-circle text-orange-500 text-2xl"></i>
      </div>
      <p class="text-4xl font-bold text-white"><?=$stats['pending_requests']?></p>
      <p class="text-gray-400 text-sm mt-2">Awaiting approval</p>
    </div>
    
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-200">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-gray-400 text-sm font-medium">Staff Operations</h3>
        <i class="fas fa-user-shield text-blue-500 text-2xl"></i>
      </div>
      <p class="text-2xl font-bold text-blue-400">Operational Access</p>
      <p class="text-gray-400 text-sm mt-2">Manage blood operations</p>
    </div>
  <?php else: ?>
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
      <h3 class="text-gray-400 text-sm font-medium">Administration</h3>
      <p class="text-2xl font-bold text-blue-400 mt-4">Users & Reports</p>
      <p class="text-gray-400 text-sm mt-2">Restricted administrative access</p>
    </div>
  <?php endif; ?>
</div>

<!-- Quick Actions -->
<div class="bg-gray-800 border border-gray-700 rounded-lg p-6 mb-6">
  <h3 class="text-xl font-bold text-white mb-4">Quick Actions</h3>
  
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <?php if ($user['user_type'] === 'Donor'): ?>
      <a href="donate.php" class="flex items-center gap-4 p-4 bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg">
        <i class="fas fa-hand-holding-heart text-white text-2xl"></i>
        <div>
          <p class="text-white font-semibold">Donate Blood</p>
          <p class="text-red-100 text-sm">Submit new donation</p>
        </div>
      </a>
      <a href="my_donations.php" class="flex items-center gap-4 p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-all duration-200">
        <i class="fas fa-history text-blue-400 text-2xl"></i>
        <div>
          <p class="text-white font-semibold">My Donations</p>
          <p class="text-gray-400 text-sm">View history</p>
        </div>
      </a>
      
    <?php elseif ($user['user_type'] === 'Recipient'): ?>
      <a href="requests.php" class="flex items-center gap-4 p-4 bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg">
        <i class="fas fa-paper-plane text-white text-2xl"></i>
        <div>
          <p class="text-white font-semibold">Request Blood</p>
          <p class="text-red-100 text-sm">Submit new request</p>
        </div>
      </a>
      <a href="my_requests.php" class="flex items-center gap-4 p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-all duration-200">
        <i class="fas fa-list text-blue-400 text-2xl"></i>
        <div>
          <p class="text-white font-semibold">My Requests</p>
          <p class="text-gray-400 text-sm">View history</p>
        </div>
      </a>
      
    <?php elseif ($user['user_type'] === 'Staff'): ?>
      <a href="admin_actions.php" class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg">
        <i class="fas fa-tasks text-white text-2xl"></i>
        <div>
          <p class="text-white font-semibold">Manage Actions</p>
          <p class="text-blue-100 text-sm">Approve/Reject</p>
        </div>
      </a>
    <?php else: ?>
      <a href="admin_users.php" class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg">
        <i class="fas fa-users-cog text-white text-2xl"></i>
        <div><p class="text-white font-semibold">Manage Users</p><p class="text-blue-100 text-sm">Roles and accounts</p></div>
      </a>
      <a href="reports.php" class="flex items-center gap-4 p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-all duration-200">
        <i class="fas fa-chart-bar text-green-400 text-2xl"></i>
        <div><p class="text-white font-semibold">View Reports</p><p class="text-gray-400 text-sm">System activity</p></div>
      </a>
    <?php endif; ?>
    
    <?php if ($user['user_type'] !== 'Admin'): ?>
      <a href="inventory.php" class="flex items-center gap-4 p-4 bg-gray-700 hover:bg-gray-600 rounded-lg transition-all duration-200">
        <i class="fas fa-warehouse text-green-400 text-2xl"></i>
        <div>
          <p class="text-white font-semibold">View Inventory</p>
          <p class="text-gray-400 text-sm">Check stock levels</p>
        </div>
      </a>
    <?php endif; ?>
  </div>
</div>

<?php include 'footer.php'; ?>
