<?php
require_once 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$user = current_user();
if ($user['user_type'] !== 'Staff') { flash('error','Staff only'); header('Location: dashboard.php'); exit; }

// Approve or reject donation
if (isset($_GET['action']) && isset($_GET['donation_id'])) {
    $id = (int)$_GET['donation_id'];
    if ($_GET['action'] === 'approve') {
        $res = $con->query("SELECT * FROM donations WHERE id={$id} LIMIT 1");
        if ($res && $res->num_rows) {
            $d = $res->fetch_assoc();
            // update donation
            $con->query("UPDATE donations SET status='Approved' WHERE id={$id}");
            // update inventory: add quantity
            $bt = $con->real_escape_string($d['blood_type']);
            $q = (int)$d['quantity_ml'];
            $con->query("UPDATE blood_inventory SET quantity_ml = quantity_ml + {$q} WHERE blood_type='{$bt}'");
            // log
            $con->query("INSERT INTO admins_logs (admin_id,action) VALUES ({$user['id']},'Approved donation {$id}')");
            flash('success','Donation approved and inventory updated');
        }
    } elseif ($_GET['action'] === 'reject') {
        $con->query("UPDATE donations SET status='Rejected' WHERE id={$id}");
        $con->query("INSERT INTO admins_logs (admin_id,action) VALUES ({$user['id']},'Rejected donation {$id}')");
        flash('success','Donation rejected');
    }
    header('Location: admin_actions.php'); exit;
}

// Approve or reject request
if (isset($_GET['req_action']) && isset($_GET['request_id'])) {
    $rid = (int)$_GET['request_id'];
    if ($_GET['req_action'] === 'approve') {
        $rres = $con->query("SELECT * FROM requests WHERE id={$rid} LIMIT 1");
        if ($rres && $rres->num_rows) {
            $r = $rres->fetch_assoc();
            $bt = $con->real_escape_string($r['blood_type']);
            $need = (int)$r['quantity_ml'];
            $inv = $con->query("SELECT * FROM blood_inventory WHERE blood_type='{$bt}' LIMIT 1");
            if ($inv && $inv->num_rows) {
                $invRow = $inv->fetch_assoc();
                if ($invRow['quantity_ml'] >= $need) {
                    $con->query("UPDATE requests SET status='Approved', fulfilled_by_donation_id = NULL WHERE id={$rid}");
                    $con->query("UPDATE blood_inventory SET quantity_ml = quantity_ml - {$need} WHERE blood_type='{$bt}'");
                    $con->query("INSERT INTO admins_logs (admin_id,action) VALUES ({$user['id']},'Approved request {$rid}')");
                    flash('success','Request approved and inventory decremented');
                } else {
                    flash('error','Not enough inventory to approve');
                }
            } else {
                flash('error','No inventory record found');
            }
        }
    } elseif ($_GET['req_action'] === 'reject') {
        $con->query("UPDATE requests SET status='Rejected' WHERE id={$rid}");
        $con->query("INSERT INTO admins_logs (admin_id,action) VALUES ({$user['id']},'Rejected request {$rid}')");
        flash('success','Request rejected');
    }
    header('Location: admin_actions.php'); exit;
}

include 'header.php';
?>

<!-- Staff Operations Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
  <?php
  $pending_donations = $con->query("SELECT COUNT(*) as count FROM donations WHERE status='Pending'")->fetch_assoc()['count'];
  $pending_requests = $con->query("SELECT COUNT(*) as count FROM requests WHERE status='Pending'")->fetch_assoc()['count'];
  $total_donors = $con->query("SELECT COUNT(*) as count FROM users WHERE user_type='Donor'")->fetch_assoc()['count'];
  $total_recipients = $con->query("SELECT COUNT(*) as count FROM users WHERE user_type='Recipient'")->fetch_assoc()['count'];
  ?>
  
  <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-200">
    <div class="flex items-center justify-between mb-2">
      <h3 class="text-gray-400 text-sm font-medium">Pending Donations</h3>
      <i class="fas fa-clock text-yellow-500 text-2xl"></i>
    </div>
    <p class="text-4xl font-bold text-white"><?=$pending_donations?></p>
    <p class="text-gray-400 text-sm mt-1">Awaiting approval</p>
  </div>
  
  <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-200">
    <div class="flex items-center justify-between mb-2">
      <h3 class="text-gray-400 text-sm font-medium">Pending Requests</h3>
      <i class="fas fa-exclamation-circle text-orange-500 text-2xl"></i>
    </div>
    <p class="text-4xl font-bold text-white"><?=$pending_requests?></p>
    <p class="text-gray-400 text-sm mt-1">Awaiting approval</p>
  </div>
  
  <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-200">
    <div class="flex items-center justify-between mb-2">
      <h3 class="text-gray-400 text-sm font-medium">Total Donors</h3>
      <i class="fas fa-user-friends text-green-500 text-2xl"></i>
    </div>
    <p class="text-4xl font-bold text-white"><?=$total_donors?></p>
    <p class="text-gray-400 text-sm mt-1">Registered donors</p>
  </div>
  
  <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 hover:shadow-lg transition-all duration-200">
    <div class="flex items-center justify-between mb-2">
      <h3 class="text-gray-400 text-sm font-medium">Recipients</h3>
      <i class="fas fa-users text-blue-500 text-2xl"></i>
    </div>
    <p class="text-4xl font-bold text-white"><?=$total_recipients?></p>
    <p class="text-gray-400 text-sm mt-1">Registered recipients</p>
  </div>
</div>

<!-- Manage Donations Section -->
<div class="bg-gray-800 border border-gray-700 rounded-lg p-6 mb-6">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-2xl font-bold text-white mb-1">Manage Donations</h2>
      <p class="text-gray-400 text-sm">Review and approve blood donations from donors</p>
    </div>
    <i class="fas fa-hand-holding-heart text-red-500 text-3xl"></i>
  </div>
  
  <div class="overflow-x-auto">
    <?php
    $donRes = $con->query("SELECT d.*, u.full_name, u.phone FROM donations d JOIN users u ON u.id=d.donor_id ORDER BY d.created_at DESC");
    if ($donRes && $donRes->num_rows) {
        echo '<table class="w-full">';
        echo '<thead class="bg-gray-900">';
        echo '<tr>';
        echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Donor</th>';
        echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Blood Type</th>';
        echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Quantity</th>';
        echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>';
        echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Date</th>';
        echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody class="divide-y divide-gray-700">';
        
        while ($row = $donRes->fetch_assoc()) {
            $status = $row['status'];
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
            echo '<td class="px-6 py-4">';
            echo '<div class="text-white font-semibold">' . htmlspecialchars($row['full_name']) . '</div>';
            echo '<div class="text-gray-400 text-sm">' . htmlspecialchars($row['phone'] ?? 'No phone') . '</div>';
            echo '</td>';
            echo '<td class="px-6 py-4 whitespace-nowrap">';
            echo '<div class="flex items-center gap-2">';
            echo '<i class="fas fa-tint text-red-500"></i>';
            echo '<span class="text-white font-semibold">' . htmlspecialchars($row['blood_type']) . '</span>';
            echo '</div>';
            echo '</td>';
            echo '<td class="px-6 py-4 whitespace-nowrap text-white font-medium">' . number_format($row['quantity_ml']) . ' ml</td>';
            echo '<td class="px-6 py-4 whitespace-nowrap">';
            echo '<span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium border ' . $badge_class . '">';
            echo '<i class="fas ' . $icon . '"></i>';
            echo $status;
            echo '</span>';
            echo '</td>';
            echo '<td class="px-6 py-4 whitespace-nowrap text-gray-400 text-sm">' . date('M d, Y', strtotime($row['created_at'])) . '</td>';
            echo '<td class="px-6 py-4 whitespace-nowrap">';
            
            if ($row['status'] === 'Pending') {
                echo '<div class="flex gap-2">';
                echo '<a href="?action=approve&donation_id=' . $row['id'] . '" class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm font-medium flex items-center gap-1" onclick="return confirm(\'Approve this donation?\')">';
                echo '<i class="fas fa-check"></i>';
                echo '<span>Approve</span>';
                echo '</a>';
                echo '<a href="?action=reject&donation_id=' . $row['id'] . '" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm font-medium flex items-center gap-1" onclick="return confirm(\'Reject this donation?\')">';
                echo '<i class="fas fa-times"></i>';
                echo '<span>Reject</span>';
                echo '</a>';
                echo '</div>';
            } else {
                echo '<span class="text-gray-500 text-sm">No action needed</span>';
            }
            
            echo '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<div class="text-center py-12">';
        echo '<i class="fas fa-inbox text-gray-600 text-6xl mb-4"></i>';
        echo '<p class="text-gray-400 text-xl">No donations yet</p>';
        echo '</div>';
    }
    ?>
  </div>
</div>

<!-- Manage Requests Section -->
<div class="bg-gray-800 border border-gray-700 rounded-lg p-6 mb-6">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-2xl font-bold text-white mb-1">Manage Requests</h2>
      <p class="text-gray-400 text-sm">Review and approve blood requests from recipients</p>
    </div>
    <i class="fas fa-paper-plane text-blue-500 text-3xl"></i>
  </div>
  
  <div class="overflow-x-auto">
    <?php
    $reqRes = $con->query("SELECT r.*, u.full_name, u.phone FROM requests r JOIN users u ON u.id=r.recipient_id ORDER BY r.created_at DESC");
    if ($reqRes && $reqRes->num_rows) {
        echo '<table class="w-full">';
        echo '<thead class="bg-gray-900">';
        echo '<tr>';
        echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Recipient</th>';
        echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Blood Type</th>';
        echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Quantity</th>';
        echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>';
        echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Date</th>';
        echo '<th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody class="divide-y divide-gray-700">';
        
        while ($row = $reqRes->fetch_assoc()) {
            $status = $row['status'];
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
            echo '<td class="px-6 py-4">';
            echo '<div class="text-white font-semibold">' . htmlspecialchars($row['full_name']) . '</div>';
            echo '<div class="text-gray-400 text-sm">' . htmlspecialchars($row['phone'] ?? 'No phone') . '</div>';
            echo '</td>';
            echo '<td class="px-6 py-4 whitespace-nowrap">';
            echo '<div class="flex items-center gap-2">';
            echo '<i class="fas fa-tint text-red-500"></i>';
            echo '<span class="text-white font-semibold">' . htmlspecialchars($row['blood_type']) . '</span>';
            echo '</div>';
            echo '</td>';
            echo '<td class="px-6 py-4 whitespace-nowrap text-white font-medium">' . number_format($row['quantity_ml']) . ' ml</td>';
            echo '<td class="px-6 py-4 whitespace-nowrap">';
            echo '<span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium border ' . $badge_class . '">';
            echo '<i class="fas ' . $icon . '"></i>';
            echo $status;
            echo '</span>';
            echo '</td>';
            echo '<td class="px-6 py-4 whitespace-nowrap text-gray-400 text-sm">' . date('M d, Y', strtotime($row['created_at'])) . '</td>';
            echo '<td class="px-6 py-4 whitespace-nowrap">';
            
            if ($row['status'] === 'Pending') {
                echo '<div class="flex gap-2">';
                echo '<a href="?req_action=approve&request_id=' . $row['id'] . '" class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-sm font-medium flex items-center gap-1" onclick="return confirm(\'Approve this request? Check if inventory is sufficient.\')">';
                echo '<i class="fas fa-check"></i>';
                echo '<span>Approve</span>';
                echo '</a>';
                echo '<a href="?req_action=reject&request_id=' . $row['id'] . '" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm font-medium flex items-center gap-1" onclick="return confirm(\'Reject this request?\')">';
                echo '<i class="fas fa-times"></i>';
                echo '<span>Reject</span>';
                echo '</a>';
                echo '</div>';
            } else {
                echo '<span class="text-gray-500 text-sm">No action needed</span>';
            }
            
            echo '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<div class="text-center py-12">';
        echo '<i class="fas fa-inbox text-gray-600 text-6xl mb-4"></i>';
        echo '<p class="text-gray-400 text-xl">No requests yet</p>';
        echo '</div>';
    }
    ?>
  </div>
</div>

<!-- Current Inventory Section -->
<div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h2 class="text-2xl font-bold text-white mb-1">Current Blood Inventory</h2>
      <p class="text-gray-400 text-sm">Monitor stock levels for all blood types</p>
    </div>
    <i class="fas fa-warehouse text-green-500 text-3xl"></i>
  </div>
  
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <?php
    $inv = $con->query("SELECT * FROM blood_inventory ORDER BY blood_type");
    if ($inv && $inv->num_rows) {
        while ($i = $inv->fetch_assoc()) {
            $quantity = (int)$i['quantity_ml'];
            
            if ($quantity < 1000) {
                $color = 'from-red-600 to-red-700';
                $text_color = 'text-red-400';
            } elseif ($quantity < 3000) {
                $color = 'from-yellow-600 to-yellow-700';
                $text_color = 'text-yellow-400';
            } else {
                $color = 'from-green-600 to-green-700';
                $text_color = 'text-green-400';
            }
            
            echo '<div class="bg-gray-700 rounded-lg p-4 border border-gray-600">';
            echo '<div class="flex items-center justify-between mb-2">';
            echo '<span class="text-2xl font-bold text-white">' . htmlspecialchars($i['blood_type']) . '</span>';
            echo '<i class="fas fa-tint ' . $text_color . ' text-xl"></i>';
            echo '</div>';
            echo '<p class="text-white font-semibold text-lg">' . number_format($quantity) . ' ml</p>';
            echo '<p class="text-gray-400 text-sm">' . number_format($quantity / 1000, 2) . ' liters</p>';
            echo '</div>';
        }
    } else {
        echo '<div class="col-span-full text-center py-8">';
        echo '<p class="text-gray-400">No inventory data available</p>';
        echo '</div>';
    }
    ?>
  </div>
</div>

<?php include 'footer.php'; ?>
