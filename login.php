<?php
require_once __DIR__ . '/../init.php';

if (is_logged_in()) {
    header('Location: dashboard.php'); exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        $stmt = $conn->prepare('SELECT * FROM users WHERE username=? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user && $user['password'] === $password) {
            $_SESSION['user'] = $user['username'];
            header('Location: dashboard.php'); exit;
        } else {
            $error = 'Invalid credentials';
        }
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>LIBRARY SERVICE</title></head><body>
<h1>LIBRARY SERVICE</h1>
<?php if ($error) echo '<p style="color:red">'.htmlspecialchars($error).'</p>'; ?>
<form method="post">
    <label>Username <input name="username" required></label><br>
    <label>Password <input name="password" type="password" required></label><br>
    <button>Login</button>
    </form>
</body></html>
