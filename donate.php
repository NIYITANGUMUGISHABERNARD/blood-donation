<?php
require_once 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$user = current_user();
if ($user['user_type'] !== 'Donor') { flash('error','Only donors can submit donations'); header('Location: dashboard.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blood_type = $con->real_escape_string($_POST['blood_type']);
    $quantity = (int)$_POST['quantity_ml'];
    $date_donated = $con->real_escape_string($_POST['date_donated']);
    $sql = "INSERT INTO donations (donor_id,blood_type,quantity_ml,date_donated) VALUES ({$user['id']},'{$blood_type}',{$quantity},'{$date_donated}')";
    if ($con->query($sql)) {
        flash('success','Donation submitted and pending approval');
        header('Location: dashboard.php'); exit;
    } else {
        flash('error','Failed to submit donation: ' . $con->error);
    }
}

include 'header.php';
?>

<div class="bg-gray-800 border border-gray-700 rounded-lg p-6 max-w-2xl mx-auto">
  <div class="mb-6">
    <h2 class="text-2xl font-bold text-white mb-2">Donate Blood</h2>
    <p class="text-gray-400">Fill in the details below to submit a new blood donation request. A staff member will review and approve it.</p>
  </div>
  
  <form method="post" class="space-y-4">
    <div>
      <label for="blood_type" class="block text-sm font-medium text-gray-300 mb-2">Blood Type</label>
      <select name="blood_type" id="blood_type" required class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-500 transition-colors">
        <option value="">Select blood type</option>
        <option value="A+">A+</option>
        <option value="A-">A-</option>
        <option value="B+">B+</option>
        <option value="B-">B-</option>
        <option value="AB+">AB+</option>
        <option value="AB-">AB-</option>
        <option value="O+">O+</option>
        <option value="O-">O-</option>
      </select>
    </div>
    
    <div>
      <label for="quantity_ml" class="block text-sm font-medium text-gray-300 mb-2">Quantity (ml)</label>
      <input name="quantity_ml" id="quantity_ml" type="number" min="50" max="500" required placeholder="Enter quantity in ml (e.g., 450)" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-red-500 transition-colors" />
      <p class="text-gray-500 text-xs mt-1">Typical donation is 450ml</p>
    </div>
    
    <div>
      <label for="date_donated" class="block text-sm font-medium text-gray-300 mb-2">Date of Donation</label>
      <input name="date_donated" id="date_donated" type="date" max="<?=date('Y-m-d')?>" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-500 transition-colors" />
      <p class="text-gray-500 text-xs mt-1">Leave empty to use today's date</p>
    </div>
    
    <div class="flex gap-3 pt-4">
      <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg transition-all duration-200 font-semibold flex items-center justify-center gap-2">
        <i class="fas fa-hand-holding-heart"></i>
        <span>Submit Donation</span>
      </button>
      <a href="dashboard.php" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors font-semibold">
        Cancel
      </a>
    </div>
  </form>
  
  <div class="mt-6 p-4 bg-blue-900/20 border border-blue-700 rounded-lg">
    <h4 class="text-blue-400 font-semibold mb-2 flex items-center gap-2">
      <i class="fas fa-info-circle"></i>
      Important Information
    </h4>
    <ul class="text-gray-400 text-sm space-y-1">
      <li>• You must be at least 18 years old to donate blood</li>
      <li>• Donors should weigh at least 50 kg (110 lbs)</li>
      <li>• Wait 56 days between whole blood donations</li>
      <li>• Your donation will be reviewed by a staff member</li>
    </ul>
  </div>
</div>

<?php include 'footer.php'; ?>
