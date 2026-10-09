<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $con->real_escape_string($_POST['full_name']);
    $email = $con->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $con->real_escape_string($_POST['phone']);
    $gender = $con->real_escape_string($_POST['gender']);
    $blood_type = $con->real_escape_string($_POST['blood_type']);
    $address = $con->real_escape_string($_POST['address']);
    // Public registration cannot create privileged Admin or Staff accounts.
    $user_type = in_array($_POST['user_type'], ['Donor','Recipient'], true) ? $_POST['user_type'] : 'Donor';

    $sql = "INSERT INTO users (full_name,email,password,phone,gender,blood_type,address,user_type) VALUES ('{$full_name}','{$email}','{$password}','{$phone}','{$gender}','{$blood_type}','{$address}','{$user_type}')";
    if ($con->query($sql)) {
        flash('success', 'Registration successful. Please login.');
        header('Location: login.php'); exit;
    } else {
        flash('error', 'Registration failed: ' . $con->error);
    }
}
include 'header.php';
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-2xl w-full space-y-8">
    <div class="text-center">
      <div class="mx-auto h-20 w-20 bg-gradient-to-br from-red-600 to-red-700 rounded-full flex items-center justify-center mb-4">
        <i class="fas fa-user-plus text-white text-3xl"></i>
      </div>
      <h2 class="text-4xl font-extrabold text-white mb-2">Create Account</h2>
      <p class="text-gray-400">Join our blood donation community today</p>
    </div>
    
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl p-8">
      <form method="post" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="full_name" class="block text-sm font-medium text-gray-300 mb-2">Full Name *</label>
            <input 
              name="full_name" 
              id="full_name" 
              required 
              placeholder="Enter your full name" 
              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-red-500 transition-colors" 
            />
          </div>
          
          <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address *</label>
            <input 
              name="email" 
              id="email" 
              type="email" 
              required 
              placeholder="your@email.com" 
              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-red-500 transition-colors" 
            />
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password *</label>
            <input 
              name="password" 
              id="password" 
              type="password" 
              required 
              placeholder="Create a strong password" 
              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-red-500 transition-colors" 
            />
          </div>
          
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
            <input 
              name="phone" 
              id="phone" 
              placeholder="Your phone number" 
              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-red-500 transition-colors" 
            />
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="gender" class="block text-sm font-medium text-gray-300 mb-2">Gender</label>
            <select 
              name="gender" 
              id="gender" 
              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-500 transition-colors"
            >
              <option value="Other">Other</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
          
          <div>
            <label for="blood_type" class="block text-sm font-medium text-gray-300 mb-2">Blood Type</label>
            <select 
              name="blood_type" 
              id="blood_type" 
              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-500 transition-colors"
            >
              <option value="">-- Select (optional) --</option>
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
        </div>
        
        <div>
          <label for="address" class="block text-sm font-medium text-gray-300 mb-2">Address</label>
          <textarea 
            name="address" 
            id="address" 
            rows="3" 
            placeholder="Your address" 
            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-red-500 transition-colors"
          ></textarea>
        </div>
        
        <div>
          <label for="user_type" class="block text-sm font-medium text-gray-300 mb-2">Register As *</label>
          <select 
            name="user_type" 
            id="user_type" 
            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-500 transition-colors"
          >
            <option value="Donor">Donor - I want to donate blood</option>
            <option value="Recipient">Recipient - I need blood</option>
          </select>
        </div>
        
        <div class="pt-4">
          <button type="submit" class="w-full flex justify-center items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg">
            <i class="fas fa-user-check"></i>
            <span>Create Account</span>
          </button>
        </div>
        
        <div class="text-center pt-4 border-t border-gray-700">
          <p class="text-gray-400 text-sm">
            Already have an account?
            <a href="login.php" class="text-red-400 hover:text-red-300 font-medium ml-1">Sign in here</a>
          </p>
        </div>
      </form>
    </div>
    
    <div class="text-center">
      <a href="index.php" class="text-gray-400 hover:text-white text-sm transition-colors inline-flex items-center gap-2">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Home</span>
      </a>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
