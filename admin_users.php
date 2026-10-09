<?php
require_once 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$user = current_user();
if ($user['user_type'] !== 'Admin') { flash('error', 'Admins only'); header('Location: dashboard.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetId = (int)($_POST['user_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($action === 'create_staff') {
        $fullName = trim($_POST['full_name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($fullName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Enter a valid full name and email address.');
        } elseif (strlen($password) < 8) {
            flash('error', 'Staff passwords must contain at least 8 characters.');
        } else {
            $check = $con->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
            $check->bind_param('s', $email);
            $check->execute();
            if ($check->get_result()->num_rows) {
                flash('error', 'An account with that email already exists.');
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $role = 'Staff';
                $gender = 'Other';
                $stmt = $con->prepare('INSERT INTO users (full_name, email, password, phone, gender, user_type) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->bind_param('ssssss', $fullName, $email, $passwordHash, $phone, $gender, $role);
                if ($stmt->execute()) {
                    flash('success', 'Staff account created successfully.');
                } else {
                    flash('error', 'Unable to create the staff account.');
                }
            }
        }
    } elseif ($targetId === (int)$user['id']) {
        flash('error', 'You cannot change or delete your own admin account.');
    } elseif ($action === 'change_role') {
        $allowedRoles = ['Admin', 'Staff', 'Donor', 'Recipient'];
        $role = $_POST['user_type'] ?? '';
        if (in_array($role, $allowedRoles, true)) {
            $stmt = $con->prepare('UPDATE users SET user_type = ? WHERE id = ?');
            $stmt->bind_param('si', $role, $targetId);
            $stmt->execute();
            flash('success', 'User role updated successfully.');
        } else {
            flash('error', 'Invalid user role.');
        }
    } elseif ($action === 'delete') {
        $stmt = $con->prepare('DELETE FROM users WHERE id = ?');
        $stmt->bind_param('i', $targetId);
        $stmt->execute();
        flash('success', 'User account deleted.');
    }
    header('Location: admin_users.php'); exit;
}

$users = $con->query('SELECT id, full_name, email, phone, blood_type, user_type, created_at FROM users ORDER BY created_at DESC');
include 'header.php';
?>

<div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
  <div class="flex items-center justify-between mb-6">
    <div><h2 class="text-2xl font-bold">User Management</h2><p class="text-gray-400 text-sm">Manage accounts and assign roles</p></div>
    <button id="openStaffModal" type="button" class="px-5 py-3 bg-green-600 hover:bg-green-700 rounded-lg font-semibold transition-colors">
      <i class="fas fa-user-plus mr-2"></i>Create New Staff
    </button>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-gray-900"><tr>
        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase">User</th>
        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase">Contact</th>
        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase">Blood</th>
        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase">Role</th>
        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase">Joined</th>
        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase">Actions</th>
      </tr></thead>
      <tbody class="divide-y divide-gray-700">
      <?php while ($row = $users->fetch_assoc()): ?>
        <tr>
          <td class="px-4 py-4"><p class="font-semibold"><?=htmlspecialchars($row['full_name'])?></p><p class="text-gray-400 text-sm"><?=htmlspecialchars($row['email'])?></p></td>
          <td class="px-4 py-4 text-gray-300"><?=htmlspecialchars($row['phone'] ?: '—')?></td>
          <td class="px-4 py-4"><?=htmlspecialchars($row['blood_type'] ?: '—')?></td>
          <td class="px-4 py-4"><span class="px-3 py-1 bg-blue-900/30 text-blue-400 rounded-full"><?=htmlspecialchars($row['user_type'])?></span></td>
          <td class="px-4 py-4 text-gray-400 text-sm"><?=date('M d, Y', strtotime($row['created_at']))?></td>
          <td class="px-4 py-4">
            <?php if ((int)$row['id'] !== (int)$user['id']): ?>
            <div class="flex gap-2">
              <form method="post" class="flex gap-2">
                <input type="hidden" name="action" value="change_role"><input type="hidden" name="user_id" value="<?=$row['id']?>">
                <select name="user_type" class="bg-gray-700 border border-gray-600 rounded px-2 py-2">
                  <?php foreach (['Admin','Staff','Donor','Recipient'] as $role): ?><option value="<?=$role?>" <?=$row['user_type'] === $role ? 'selected' : ''?>><?=$role?></option><?php endforeach; ?>
                </select>
                <button class="px-3 py-2 bg-blue-600 hover:bg-blue-700 rounded">Save</button>
              </form>
              <form method="post" onsubmit="return confirm('Delete this user and all related records?')">
                <input type="hidden" name="action" value="delete"><input type="hidden" name="user_id" value="<?=$row['id']?>">
                <button class="px-3 py-2 bg-red-600 hover:bg-red-700 rounded"><i class="fas fa-trash"></i></button>
              </form>
            </div>
            <?php else: ?><span class="text-gray-500 text-sm">Current account</span><?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Create Staff Modal -->
<div id="staffModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4" role="dialog" aria-modal="true" aria-labelledby="staffModalTitle">
  <div id="staffModalPanel" class="w-full max-w-2xl bg-gray-800 border border-gray-700 rounded-xl shadow-2xl">
    <div class="flex items-center justify-between p-6 border-b border-gray-700">
      <div>
        <h2 id="staffModalTitle" class="text-2xl font-bold">Create Staff Account</h2>
        <p class="text-gray-400 text-sm mt-1">Add a staff member who can manage blood operations</p>
      </div>
      <button id="closeStaffModal" type="button" class="w-10 h-10 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700" aria-label="Close modal">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <form method="post" class="p-6">
      <input type="hidden" name="action" value="create_staff">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label for="staff_full_name" class="block text-sm text-gray-300 mb-2">Full Name *</label>
          <input id="staff_full_name" name="full_name" required maxlength="150" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:border-green-500" placeholder="Staff member name">
        </div>
        <div>
          <label for="staff_email" class="block text-sm text-gray-300 mb-2">Email *</label>
          <input id="staff_email" name="email" type="email" required maxlength="150" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:border-green-500" placeholder="staff@example.com">
        </div>
        <div>
          <label for="staff_phone" class="block text-sm text-gray-300 mb-2">Phone</label>
          <input id="staff_phone" name="phone" maxlength="30" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:border-green-500" placeholder="Phone number">
        </div>
        <div>
          <label for="staff_password" class="block text-sm text-gray-300 mb-2">Temporary Password *</label>
          <input id="staff_password" name="password" type="password" required minlength="8" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:border-green-500" placeholder="At least 8 characters">
        </div>
      </div>
      <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-gray-700">
        <button id="cancelStaffModal" type="button" class="px-5 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg font-semibold">Cancel</button>
        <button type="submit" class="px-5 py-3 bg-green-600 hover:bg-green-700 rounded-lg font-semibold"><i class="fas fa-user-plus mr-2"></i>Create Staff</button>
      </div>
    </form>
  </div>
</div>

<script>
  const staffModal = document.getElementById('staffModal');
  const staffModalPanel = document.getElementById('staffModalPanel');
  const staffNameInput = document.getElementById('staff_full_name');

  function openStaffModal() {
    staffModal.classList.remove('hidden');
    staffModal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
    setTimeout(() => staffNameInput.focus(), 0);
  }

  function closeStaffModal() {
    staffModal.classList.add('hidden');
    staffModal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
    document.getElementById('openStaffModal').focus();
  }

  document.getElementById('openStaffModal').addEventListener('click', openStaffModal);
  document.getElementById('closeStaffModal').addEventListener('click', closeStaffModal);
  document.getElementById('cancelStaffModal').addEventListener('click', closeStaffModal);
  staffModal.addEventListener('click', (event) => {
    if (!staffModalPanel.contains(event.target)) closeStaffModal();
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !staffModal.classList.contains('hidden')) closeStaffModal();
  });
</script>
<?php include 'footer.php'; ?>
