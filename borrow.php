<?php
require_once __DIR__ . '/init.php';

$book_id = isset($_REQUEST['book_id']) ? intval($_REQUEST['book_id']) : 0;
if (!$book_id) {
    header('Location: index.php'); exit;
}

// check availability
$stmt = $conn->prepare("SELECT copies, title FROM books WHERE id=?");
$stmt->bind_param('i', $book_id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();
if (!$book) { header('Location: index.php'); exit; }

$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM loans WHERE book_id=? AND returned=0");
$stmt->bind_param('i', $book_id);
$stmt->execute();
$cnt = $stmt->get_result()->fetch_assoc();
$available = max(0, $book['copies'] - intval($cnt['cnt']));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name && $available > 0) {
        $ins = $conn->prepare("INSERT INTO loans (book_id, borrower_name) VALUES (?,?)");
        $ins->bind_param('is', $book_id, $name);
        $ins->execute();
        header('Location: index.php'); exit;
    }
}

?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Borrow</title></head><body>
<h1>Borrow: <?php echo htmlspecialchars($book['title']); ?></h1>
<p>Available: <?php echo $available; ?></p>
<?php if ($available <= 0): ?>
    <p>Sorry, no copies available right now.</p>
    <p><a href="index.php">Back to catalog</a></p>
<?php else: ?>
    <form method="post">
        Your name: <input name="name" required>
        <button type="submit">Borrow</button>
    </form>
    <p><a href="index.php">Back to catalog</a></p>
<?php endif; ?>
</body></html>
