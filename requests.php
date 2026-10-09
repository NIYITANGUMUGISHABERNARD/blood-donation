<?php
require_once 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$user = current_user();
if ($user['user_type'] !== 'Recipient') { flash('error','Only recipients can request blood'); header('Location: dashboard.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blood_type = $con->real_escape_string($_POST['blood_type']);
    $quantity = (int)$_POST['quantity_ml'];
    $sql = "INSERT INTO requests (recipient_id,blood_type,quantity_ml) VALUES ({$user['id']},'{$blood_type}',{$quantity})";
    if ($con->query($sql)) {
        flash('success','Request submitted'); header('Location: dashboard.php'); exit;
    } else { flash('error','Failed: ' . $con->error); }
}

include 'header.php';
?>

<div class="bg-gray-800 border border-gray-700 rounded-lg p-6 max-w-2xl mx-auto">
  <div class="mb-6">
    <h2 class="text-2xl font-bold text-white mb-2">Request Blood</h2>
    <p class="text-gray-400">Submit a blood request. Our team will process your request and verify availability.</p>
  </div>
  
  <form method="post" class="space-y-4">
    <div>
      <label for="blood_type" class="block text-sm font-medium text-gray-300 mb-2">Blood Type Needed</label>
      <select name="blood_type" id="blood_type" required class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-500 transition-colors">
        <option value="">Select blood type</option>
        <option value="A+">A+ (A Positive)</option>
        <option value="A-">A- (A Negative)</option>
        <option value="B+">B+ (B Positive)</option>
        <option value="B-">B- (B Negative)</option>
        <option value="AB+">AB+ (AB Positive)</option>
        <option value="AB-">AB- (AB Negative)</option>
        <option value="O+">O+ (O Positive)</option>
        <option value="O-">O- (O Negative)</option>
      </select>
    </div>
    
    <div>
      <label for="quantity_ml" class="block text-sm font-medium text-gray-300 mb-2">Quantity Required (ml)</label>
      <input name="quantity_ml" id="quantity_ml" type="number" min="50" max="5000" required placeholder="Enter quantity in ml (e.g., 450)" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-red-500 transition-colors" />
      <p class="text-gray-500 text-xs mt-1">Standard unit is 450ml</p>
    </div>
    
    <div class="p-4 bg-yellow-900/20 border border-yellow-700 rounded-lg">
      <h4 class="text-yellow-400 font-semibold mb-2 flex items-center gap-2">
        <i class="fas fa-exclamation-triangle"></i>
        Note
      </h4>
      <p class="text-gray-400 text-sm">
        Your request will be reviewed by our blood bank staff. Approval depends on blood availability in our inventory.
      </p>
    </div>
    
    <div class="flex gap-3 pt-4">
      <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg transition-all duration-200 font-semibold flex items-center justify-center gap-2">
        <i class="fas fa-paper-plane"></i>
        <span>Submit Request</span>
      </button>
      <a href="dashboard.php" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors font-semibold">
        Cancel
      </a>
    </div>
  </form>
  
  <div class="mt-6 p-4 bg-blue-900/20 border border-blue-700 rounded-lg">
    <h4 class="text-blue-400 font-semibold mb-2 flex items-center gap-2">
      <i class="fas fa-info-circle"></i>
      Blood Compatibility
    </h4>
    <div class="text-gray-400 text-sm space-y-2">
      <p>• <strong>O-</strong> can donate to everyone (Universal donor)</p>
      <p>• <strong>AB+</strong> can receive from everyone (Universal recipient)</p>
      <p>• Check with medical staff for specific compatibility</p>
      <p>• Emergency requests are prioritized</p>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
