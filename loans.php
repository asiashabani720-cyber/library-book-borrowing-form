<?php
require_once __DIR__ . '/../init.php';
require_login();

// handle return via link
if (isset($_GET['return']) && is_numeric($_GET['return'])) {
    $id = intval($_GET['return']);
    $stmt = $conn->prepare('UPDATE loans SET returned=1, returned_at=NOW() WHERE id=?');
    $stmt->bind_param('i',$id);
    $stmt->execute();
    header('Location: loans.php'); exit;
}

$res = $conn->query('SELECT l.*, b.title FROM loans l JOIN books b ON l.book_id=b.id ORDER BY l.borrowed_at DESC');
$loans = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Loans</title></head><body>
<h1>Loans</h1>
<p><a href="dashboard.php">Back</a></p>
<table border="1" cellpadding="6">
<tr><th>Book</th><th>Borrower</th><th>Borrowed At</th><th>Returned</th><th>Action</th></tr>
<?php foreach ($loans as $l): ?>
    <tr>
        <td><?php echo htmlspecialchars($l['title']); ?></td>
        <td><?php echo htmlspecialchars($l['borrower_name']); ?></td>
        <td><?php echo htmlspecialchars($l['borrowed_at']); ?></td>
        <td><?php echo $l['returned'] ? 'Yes ('.htmlspecialchars($l['returned_at']).')' : 'No'; ?></td>
        <td>
            <?php if (!$l['returned']): ?>
                <a href="loans.php?return=<?php echo $l['id']; ?>">Mark returned</a>
            <?php else: ?>
                -
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>
</body></html>
