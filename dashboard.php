<?php
require_once __DIR__ . '/../init.php';
require_login();
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin</title></head><body>
<h1>MY LIBRARY</h1>
<p>my <?php echo htmlspecialchars($_SESSION['user']); ?> :   <a href="login.php">Refresh</a> | <a href="books.php">Manage Books</a> | <a href="loans.php">Loans</a> | <a href="logout.php">Logout</a></p>
</body></html>
