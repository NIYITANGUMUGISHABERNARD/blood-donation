<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $con->real_escape_string($_POST['email']);
  $password = $_POST['password'];
  $res = $con->query("SELECT * FROM users WHERE email='{$email}' LIMIT 1");
  if ($res && $res->num_rows) {
    $u = $res->fetch_assoc();
    if (password_verify($password, $u['password'])) {
      $_SESSION['user_id'] = $u['id'];
      flash('success', 'Logged in successfully');
      header('Location: dashboard.php');
      exit;
    }
  }
  flash('error', 'Invalid credentials');
}
include 'header.php';
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-md w-full space-y-8">
    <div class="text-center">
      <div
        class="mx-auto h-20 w-20 bg-gradient-to-br from-red-600 to-red-700 rounded-full flex items-center justify-center mb-4">
        <i class="fas fa-heartbeat text-white text-3xl"></i>
      </div>
      <h2 class="text-4xl font-extrabold text-white mb-2">Welcome Back</h2>
      <p class="text-gray-400">Sign in to your account to continue</p>
    </div>

    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl p-8">
      <form method="post" class="space-y-6">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-envelope text-gray-500"></i>
            </div>
            <input name="email" id="email" type="email" required placeholder="Enter your email"
              class="w-full pl-10 pr-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-red-500 transition-colors" />
          </div>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-lock text-gray-500"></i>
            </div>
            <input name="password" id="password" type="password" required placeholder="Enter your password"
              class="w-full pl-10 pr-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-red-500 transition-colors" />
          </div>
        </div>

        <div>
          <button type="submit"
            class="w-full flex justify-center items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg">
            <i class="fas fa-sign-in-alt"></i>
            <span>Sign In</span>
          </button>
        </div>

        <div class="text-center pt-4 border-t border-gray-700">
          <p class="text-gray-400 text-sm">
            Don't have an account?
            <a href="register.php" class="text-red-400 hover:text-red-300 font-medium ml-1">Register here</a>
          </p>
        </div>
      </form>
    </div>

    <div class="text-center">
      <a href="index.php"
        class="text-gray-400 hover:text-white text-sm transition-colors inline-flex items-center gap-2">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Home</span>
      </a>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>