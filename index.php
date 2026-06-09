<?php
require_once __DIR__ . '/init.php';

$books = [];
$res = $conn->query("SELECT * FROM books ORDER BY title");
while ($r = $res->fetch_assoc()) {
    // compute availability
    $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM loans WHERE book_id=? AND returned=0");
    $stmt->bind_param('i', $r['id']);
    $stmt->execute();
    $cr = $stmt->get_result()->fetch_assoc();
    $r['borrowed'] = intval($cr['cnt']);
    $r['available'] = max(0, intval($r['copies']) - $r['borrowed']);
    $books[] = $r;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Library</title></head>
<body>
<h1>Library service</h1>
<?php if (is_logged_in()): ?>
    <p>Logged in as <?php echo htmlspecialchars($_SESSION['user']); ?> — <a href="admin/dashboard.php">Admin</a></p>
<?php else: ?>
    <p><a href="admin/login.php">Admin login</a></p>
<?php endif;?>

<table border="1" cellpadding="6" cellspacing="0">
<tr><th>Title</th><th>Author</th><th>ISBN</th><th>Copies</th><th>Available</th><th>Action</th></tr>
<?php foreach ($books as $b): ?>
    <tr>
        <td><?php echo htmlspecialchars($b['title']); ?></td>
        <td><?php echo htmlspecialchars($b['author']); ?></td>
        <td><?php echo htmlspecialchars($b['isbn']); ?></td>
        <td><?php echo (int)$b['copies']; ?></td>
        <td><?php echo (int)$b['available']; ?></td>
        <td>
            <?php if ($b['available'] > 0): ?>
                <a href="borrow.php?book_id=<?php echo $b['id']; ?>">Borrow</a>
            <?php else: ?>
                Unavailable
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>
</body>
</html>
