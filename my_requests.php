<?php
require_once 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$user = current_user();
if ($user['user_type'] !== 'Recipient') { flash('error','Only recipients'); header('Location: dashboard.php'); exit; }

include 'header.php';
?>

<div class="mb-6">
  <h2 class="text-2xl font-bold text-white mb-2">My Requests</h2>
  <p class="text-gray-400">View all your blood requests and their approval status</p>
</div>

<div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
  <?php
  $res = $con->query("SELECT * FROM requests WHERE recipient_id={$user['id']} ORDER BY created_at DESC");
  if ($res && $res->num_rows) {
      echo '<div class="overflow-x-auto">';
      echo '<table class="w-full">';
      echo '<thead class="bg-gray-900">';
      echo '<tr>';
      echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Blood Type</th>';
      echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Quantity</th>';
      echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>';
      echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Requested On</th>';
      echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Updated</th>';
      echo '</tr>';
      echo '</thead>';
      echo '<tbody class="divide-y divide-gray-700">';
      
      while ($r = $res->fetch_assoc()) {
          // Status badge styling
          $status = $r['status'];
          if ($status === 'Approved') {
              $badge_class = 'bg-green-900/30 text-green-400 border-green-700';
              $icon = 'fa-check-circle';
          } elseif ($status === 'Rejected') {
              $badge_class = 'bg-red-900/30 text-red-400 border-red-700';
              $icon = 'fa-times-circle';
          } else {
              $badge_class = 'bg-yellow-900/30 text-yellow-400 border-yellow-700';
              $icon = 'fa-clock';
          }
          
          echo '<tr class="hover:bg-gray-750 transition-colors">';
          echo '<td class="px-6 py-4 whitespace-nowrap">';
          echo '<div class="flex items-center gap-2">';
          echo '<i class="fas fa-tint text-red-500"></i>';
          echo '<span class="text-white font-semibold">' . htmlspecialchars($r['blood_type']) . '</span>';
          echo '</div>';
          echo '</td>';
          echo '<td class="px-6 py-4 whitespace-nowrap text-white">' . number_format($r['quantity_ml']) . ' ml</td>';
          echo '<td class="px-6 py-4 whitespace-nowrap">';
          echo '<span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium border ' . $badge_class . '">';
          echo '<i class="fas ' . $icon . '"></i>';
          echo $status;
          echo '</span>';
          echo '</td>';
          echo '<td class="px-6 py-4 whitespace-nowrap text-gray-400 text-sm">' . date('M d, Y', strtotime($r['created_at'])) . '</td>';
          echo '<td class="px-6 py-4 whitespace-nowrap text-gray-400 text-sm">' . date('M d, Y g:i A', strtotime($r['updated_at'] ?? $r['created_at'])) . '</td>';
          echo '</tr>';
      }
      
      echo '</tbody>';
      echo '</table>';
      echo '</div>';
  } else {
      echo '<div class="p-12 text-center">';
      echo '<i class="fas fa-paper-plane text-gray-600 text-6xl mb-4"></i>';
      echo '<p class="text-gray-400 text-xl mb-2">No requests yet</p>';
      echo '<p class="text-gray-500 text-sm mb-6">Submit your first blood request when needed</p>';
      echo '<a href="requests.php" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg transition-all duration-200 font-semibold">';
      echo '<i class="fas fa-plus"></i>';
      echo '<span>Make First Request</span>';
      echo '</a>';
      echo '</div>';
  }
  ?>
</div>

<?php if ($res && $res->num_rows): ?>
<!-- Stats Summary -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
  <?php
  $stats_res = $con->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status='Approved' THEN 1 ELSE 0 END) as approved,
    SUM(CASE WHEN status='Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status='Approved' THEN quantity_ml ELSE 0 END) as total_ml
    FROM requests WHERE recipient_id={$user['id']}");
  $stats = $stats_res->fetch_assoc();
  ?>
  
  <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
    <div class="flex items-center justify-between mb-2">
      <h3 class="text-gray-400 text-sm font-medium">Total Requests</h3>
      <i class="fas fa-list text-blue-500 text-xl"></i>
    </div>
    <p class="text-3xl font-bold text-white"><?=$stats['total']?></p>
    <p class="text-gray-400 text-sm mt-1"><?=$stats['approved']?> approved</p>
  </div>
  
  <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
    <div class="flex items-center justify-between mb-2">
      <h3 class="text-gray-400 text-sm font-medium">Blood Received</h3>
      <i class="fas fa-flask text-green-500 text-xl"></i>
    </div>
    <p class="text-3xl font-bold text-white"><?=number_format($stats['total_ml'])?> <span class="text-lg text-gray-400">ml</span></p>
    <p class="text-gray-400 text-sm mt-1"><?=number_format($stats['total_ml'] / 1000, 2)?> liters received</p>
  </div>
  
  <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
    <div class="flex items-center justify-between mb-2">
      <h3 class="text-gray-400 text-sm font-medium">Pending Review</h3>
      <i class="fas fa-hourglass-half text-yellow-500 text-xl"></i>
    </div>
    <p class="text-3xl font-bold text-white"><?=$stats['pending']?></p>
    <p class="text-gray-400 text-sm mt-1">Awaiting approval</p>
  </div>
</div>
<?php endif; ?>

<?php include 'footer.php'; ?>
