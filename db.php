<?php
// db.php - creates $con mysqli connection and starts session
session_start();

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'blood_donation_db';

$con = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($con->connect_errno) {
    die('DB connection failed: ' . $con->connect_error);
}

// helper: simple flash message
function flash($key, $val = null) {
    if ($val === null) {
        if (isset($_SESSION['flash'][$key])) {
            $v = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $v;
        }
        return null;
    }
    $_SESSION['flash'][$key] = $val;
}

function current_user() {
    global $con;
    if (!isset($_SESSION['user_id'])) return null;
    $id = (int)$_SESSION['user_id'];
    $res = $con->query("SELECT * FROM users WHERE id=$id");
    if ($res && $res->num_rows) return $res->fetch_assoc();
    return null;
}

?>
