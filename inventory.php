<?php
require_once 'db.php';
include 'header.php';
?>

<div class="mb-6">
  <h2 class="text-2xl font-bold text-white mb-2">Blood Inventory</h2>
  <p class="text-gray-400">Current blood stock levels across all blood types</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
  <?php
  $res = $con->query("SELECT * FROM blood_inventory ORDER BY blood_type");
  $total_ml = 0;
  $blood_types = [];
  
  if ($res && $res->num_rows) {
      while ($row = $res->fetch_assoc()) {
          $blood_types[] = $row;
          $total_ml += $row['quantity_ml'];
      }
  }
  
  if (count($blood_types) > 0) {
      foreach ($blood_types as $row) {
          $quantity = (int)$row['quantity_ml'];
          $liters = number_format($quantity / 1000, 2);
          
          // Determine status color
          if ($quantity < 1000) {
              $status_color = 'from-red-600 to-red-700';
              $status_text = 'Critical';
              $icon_color = 'text-red-400';
          } elseif ($quantity < 3000) {
              $status_color = 'from-yellow-600 to-yellow-700';
              $status_text = 'Low';
              $icon_color = 'text-yellow-400';
          } else {
              $status_color = 'from-green-600 to-green-700';
              $status_text = 'Good';
              $icon_color = 'text-green-400';
          }
          
          echo '<div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden hover:shadow-xl transition-all duration-300">';
          echo '<div class="bg-gradient-to-r ' . $status_color . ' p-4">';
          echo '<div class="flex items-center justify-between">';
          echo '<div>';
          echo '<h3 class="text-white text-3xl font-bold">' . htmlspecialchars($row['blood_type']) . '</h3>';
          echo '<p class="text-white/80 text-sm">' . $status_text . ' Stock</p>';
          echo '</div>';
          echo '<i class="fas fa-tint text-white text-4xl opacity-50"></i>';
          echo '</div>';
          echo '</div>';
          echo '<div class="p-4">';
          echo '<div class="space-y-2">';
          echo '<div class="flex justify-between items-center">';
          echo '<span class="text-gray-400 text-sm">Volume</span>';
          echo '<span class="text-white font-semibold">' . number_format($quantity) . ' ml</span>';
          echo '</div>';
          echo '<div class="flex justify-between items-center">';
          echo '<span class="text-gray-400 text-sm">Liters</span>';
          echo '<span class="text-white font-semibold">' . $liters . ' L</span>';
          echo '</div>';
          echo '</div>';
          echo '<div class="mt-4 pt-4 border-t border-gray-700">';
          echo '<div class="flex items-center gap-2">';
          echo '<i class="fas fa-circle ' . $icon_color . ' text-xs"></i>';
          echo '<span class="text-gray-400 text-xs">' . $status_text . ' Level</span>';
          echo '</div>';
          echo '</div>';
          echo '</div>';
          echo '</div>';
      }
  } else {
      echo '<div class="col-span-full bg-gray-800 border border-gray-700 rounded-lg p-12 text-center">';
      echo '<i class="fas fa-tint text-gray-600 text-6xl mb-4"></i>';
      echo '<p class="text-gray-400 text-xl">No inventory records found.</p>';
      echo '<p class="text-gray-500 text-sm mt-2">Blood inventory will appear here once donations are approved.</p>';
      echo '</div>';
  }
  ?>
</div>

<!-- Total Summary -->
<?php if (count($blood_types) > 0): ?>
<div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg p-6">
  <div class="flex items-center justify-between">
    <div>
      <h3 class="text-xl font-bold text-white mb-1">Total Blood Stock</h3>
      <p class="text-gray-400 text-sm">Combined volume across all blood types</p>
    </div>
    <div class="text-right">
      <p class="text-4xl font-bold text-white"><?=number_format($total_ml)?> <span class="text-2xl text-gray-400">ml</span></p>
      <p class="text-gray-400 text-sm"><?=number_format($total_ml / 1000, 2)?> Liters</p>
    </div>
  </div>
</div>

<!-- Legend -->
<div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg p-6">
  <h4 class="text-white font-semibold mb-4">Stock Level Legend</h4>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="flex items-center gap-3">
      <div class="w-4 h-4 bg-gradient-to-r from-red-600 to-red-700 rounded"></div>
      <div>
        <p class="text-white font-medium">Critical</p>
        <p class="text-gray-400 text-sm">Less than 1,000 ml</p>
      </div>
    </div>
    <div class="flex items-center gap-3">
      <div class="w-4 h-4 bg-gradient-to-r from-yellow-600 to-yellow-700 rounded"></div>
      <div>
        <p class="text-white font-medium">Low</p>
        <p class="text-gray-400 text-sm">1,000 - 3,000 ml</p>
      </div>
    </div>
    <div class="flex items-center gap-3">
      <div class="w-4 h-4 bg-gradient-to-r from-green-600 to-green-700 rounded"></div>
      <div>
        <p class="text-white font-medium">Good</p>
        <p class="text-gray-400 text-sm">More than 3,000 ml</p>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php include 'footer.php'; ?>
