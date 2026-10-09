<?php
require_once 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$user = current_user();
if ($user['user_type'] !== 'Admin') { flash('error', 'Admins only'); header('Location: dashboard.php'); exit; }

$counts = $con->query("SELECT
  (SELECT COUNT(*) FROM users) total_users,
  (SELECT COUNT(*) FROM donations) total_donations,
  (SELECT COALESCE(SUM(quantity_ml),0) FROM donations WHERE status='Approved') donated_ml,
  (SELECT COUNT(*) FROM requests) total_requests,
  (SELECT COALESCE(SUM(quantity_ml),0) FROM blood_inventory) inventory_ml")->fetch_assoc();
$roles = $con->query('SELECT user_type, COUNT(*) total FROM users GROUP BY user_type ORDER BY user_type');
$donationStatuses = $con->query('SELECT status, COUNT(*) total, COALESCE(SUM(quantity_ml),0) quantity FROM donations GROUP BY status');
$requestStatuses = $con->query('SELECT status, COUNT(*) total, COALESCE(SUM(quantity_ml),0) quantity FROM requests GROUP BY status');
$logs = $con->query('SELECT l.action, l.created_at, u.full_name FROM admins_logs l JOIN users u ON u.id=l.admin_id ORDER BY l.created_at DESC LIMIT 20');
include 'header.php';
?>
<div class="mb-6"><h2 class="text-3xl font-bold">System Reports</h2><p class="text-gray-400">Read-only overview of users and blood activity</p></div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
<?php foreach ([['Users',$counts['total_users'],'fa-users'],['Donations',$counts['total_donations'],'fa-hand-holding-heart'],['Approved Blood',number_format($counts['donated_ml']).' ml','fa-tint'],['Requests',$counts['total_requests'],'fa-paper-plane'],['Inventory',number_format($counts['inventory_ml']).' ml','fa-warehouse']] as $card): ?>
  <div class="bg-gray-800 border border-gray-700 rounded-lg p-5"><i class="fas <?=$card[2]?> text-red-500 text-xl"></i><p class="text-gray-400 text-sm mt-3"><?=$card[0]?></p><p class="text-2xl font-bold mt-1"><?=$card[1]?></p></div>
<?php endforeach; ?>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
  <?php foreach ([['Users by Role',$roles],['Donations by Status',$donationStatuses],['Requests by Status',$requestStatuses]] as $section): ?>
  <div class="bg-gray-800 border border-gray-700 rounded-lg p-6"><h3 class="text-xl font-bold mb-4"><?=$section[0]?></h3>
    <?php while ($row = $section[1]->fetch_assoc()): ?><div class="flex justify-between border-b border-gray-700 py-3"><span><?=htmlspecialchars($row['user_type'] ?? $row['status'])?></span><span class="font-bold"><?=$row['total']?><?php if (isset($row['quantity'])): ?> <small class="text-gray-400">(<?=number_format($row['quantity'])?> ml)</small><?php endif; ?></span></div><?php endwhile; ?>
  </div>
  <?php endforeach; ?>
</div>
<div class="bg-gray-800 border border-gray-700 rounded-lg p-6"><h3 class="text-xl font-bold mb-4">Recent Operational Activity</h3>
  <?php if ($logs->num_rows): while ($log = $logs->fetch_assoc()): ?><div class="flex justify-between gap-4 border-b border-gray-700 py-3"><div><p><?=htmlspecialchars($log['action'])?></p><p class="text-gray-400 text-sm"><?=htmlspecialchars($log['full_name'])?></p></div><span class="text-gray-400 text-sm whitespace-nowrap"><?=date('M d, Y H:i', strtotime($log['created_at']))?></span></div><?php endwhile; else: ?><p class="text-gray-400">No activity recorded.</p><?php endif; ?>
</div>
<?php include 'footer.php'; ?>
