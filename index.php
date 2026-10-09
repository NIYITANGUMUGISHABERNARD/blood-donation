<?php include 'header.php'; ?>

<div class="min-h-screen">
  <!-- Hero Section -->
  <div class="bg-gradient-to-br from-red-600 via-red-700 to-red-800 rounded-lg p-12 mb-8 text-center shadow-2xl">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-5xl md:text-6xl font-extrabold text-white mb-4">
        Save Lives Today
      </h1>
      <p class="text-xl md:text-2xl text-red-100 mb-8">
        Join our community of heroes. Donate blood, save lives, make a difference.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="register.php" class="px-8 py-4 bg-white text-red-600 rounded-lg font-bold text-lg hover:bg-gray-100 transition-all duration-200 shadow-lg flex items-center justify-center gap-2">
          <i class="fas fa-user-plus"></i>
          <span>Register Now</span>
        </a>
        <a href="login.php" class="px-8 py-4 bg-red-900 text-white rounded-lg font-bold text-lg hover:bg-red-950 transition-all duration-200 shadow-lg flex items-center justify-center gap-2">
          <i class="fas fa-sign-in-alt"></i>
          <span>Sign In</span>
        </a>
      </div>
    </div>
  </div>

  <!-- Features Grid -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-8 hover:shadow-xl transition-all duration-300 group">
      <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-700 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
        <i class="fas fa-user-plus text-white text-2xl"></i>
      </div>
      <h3 class="text-xl font-bold text-white mb-2">Easy Registration</h3>
      <p class="text-gray-400 mb-4">Create an account quickly as a donor or recipient. Join our life-saving community in minutes.</p>
      <a href="register.php" class="text-red-400 hover:text-red-300 font-medium inline-flex items-center gap-2">
        Get Started
        <i class="fas fa-arrow-right text-sm"></i>
      </a>
    </div>

    <div class="bg-gray-800 border border-gray-700 rounded-lg p-8 hover:shadow-xl transition-all duration-300 group">
      <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
        <i class="fas fa-heartbeat text-white text-2xl"></i>
      </div>
      <h3 class="text-xl font-bold text-white mb-2">Donate Blood</h3>
      <p class="text-gray-400 mb-4">Schedule your donations easily. Track your donation history and see the impact you're making.</p>
      <a href="login.php" class="text-blue-400 hover:text-blue-300 font-medium inline-flex items-center gap-2">
        Start Donating
        <i class="fas fa-arrow-right text-sm"></i>
      </a>
    </div>

    <div class="bg-gray-800 border border-gray-700 rounded-lg p-8 hover:shadow-xl transition-all duration-300 group">
      <div class="w-16 h-16 bg-gradient-to-br from-green-600 to-green-700 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
        <i class="fas fa-warehouse text-white text-2xl"></i>
      </div>
      <h3 class="text-xl font-bold text-white mb-2">Check Inventory</h3>
      <p class="text-gray-400 mb-4">View real-time blood stock levels. See which blood types are most needed right now.</p>
      <a href="inventory.php" class="text-green-400 hover:text-green-300 font-medium inline-flex items-center gap-2">
        View Stock
        <i class="fas fa-arrow-right text-sm"></i>
      </a>
    </div>
  </div>

  <!-- Statistics Section -->
  <div class="bg-gray-800 border border-gray-700 rounded-lg p-8 mb-8">
    <h2 class="text-3xl font-bold text-white text-center mb-8">Our Impact</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <?php
      require_once 'db.php';
      $total_donations = $con->query("SELECT COUNT(*) as count FROM donations WHERE status='Approved'")->fetch_assoc()['count'];
      $total_donors = $con->query("SELECT COUNT(*) as count FROM users WHERE user_type='Donor'")->fetch_assoc()['count'];
      $total_requests = $con->query("SELECT COUNT(*) as count FROM requests WHERE status='Approved'")->fetch_assoc()['count'];
      $total_blood = $con->query("SELECT SUM(quantity_ml) as total FROM blood_inventory")->fetch_assoc()['total'] ?? 0;
      ?>
      
      <div class="text-center">
        <div class="text-4xl font-bold text-red-400 mb-2"><?=number_format($total_donations)?></div>
        <p class="text-gray-400">Successful Donations</p>
      </div>
      
      <div class="text-center">
        <div class="text-4xl font-bold text-blue-400 mb-2"><?=number_format($total_donors)?></div>
        <p class="text-gray-400">Registered Donors</p>
      </div>
      
      <div class="text-center">
        <div class="text-4xl font-bold text-green-400 mb-2"><?=number_format($total_requests)?></div>
        <p class="text-gray-400">Lives Helped</p>
      </div>
      
      <div class="text-center">
        <div class="text-4xl font-bold text-yellow-400 mb-2"><?=number_format($total_blood / 1000, 1)?>L</div>
        <p class="text-gray-400">Blood Available</p>
      </div>
    </div>
  </div>

  <!-- Why Donate Section -->
  <div class="bg-gray-800 border border-gray-700 rounded-lg p-8 mb-8">
    <h2 class="text-3xl font-bold text-white text-center mb-8">Why Donate Blood?</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="flex gap-4">
        <div class="flex-shrink-0">
          <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center">
            <i class="fas fa-heart text-white"></i>
          </div>
        </div>
        <div>
          <h4 class="text-white font-semibold mb-2">Save Up to 3 Lives</h4>
          <p class="text-gray-400 text-sm">A single donation can help save up to three lives by providing different blood components.</p>
        </div>
      </div>
      
      <div class="flex gap-4">
        <div class="flex-shrink-0">
          <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
            <i class="fas fa-shield-alt text-white"></i>
          </div>
        </div>
        <div>
          <h4 class="text-white font-semibold mb-2">Safe & Quick Process</h4>
          <p class="text-gray-400 text-sm">Donating blood is a safe procedure that takes only about 10-15 minutes of your time.</p>
        </div>
      </div>
      
      <div class="flex gap-4">
        <div class="flex-shrink-0">
          <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center">
            <i class="fas fa-hand-holding-heart text-white"></i>
          </div>
        </div>
        <div>
          <h4 class="text-white font-semibold mb-2">Always Needed</h4>
          <p class="text-gray-400 text-sm">Blood is needed every two seconds. Your donation makes a real difference.</p>
        </div>
      </div>
      
      <div class="flex gap-4">
        <div class="flex-shrink-0">
          <div class="w-12 h-12 bg-yellow-600 rounded-full flex items-center justify-center">
            <i class="fas fa-trophy text-white"></i>
          </div>
        </div>
        <div>
          <h4 class="text-white font-semibold mb-2">Health Benefits</h4>
          <p class="text-gray-400 text-sm">Regular blood donation can help reduce iron levels and improve cardiovascular health.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Call to Action -->
  <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-lg p-12 text-center shadow-xl">
    <h2 class="text-4xl font-bold text-white mb-4">Ready to Make a Difference?</h2>
    <p class="text-xl text-red-100 mb-8">Join thousands of donors who are saving lives every day.</p>
    <a href="register.php" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-red-600 rounded-lg font-bold text-lg hover:bg-gray-100 transition-all duration-200 shadow-lg">
      <i class="fas fa-hand-holding-heart"></i>
      <span>Become a Donor Today</span>
    </a>
  </div>
</div>

<?php include 'footer.php'; ?>
