<?php
require_once __DIR__ . '/../init.php';
require_login();

// handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');
    $copies = intval($_POST['copies'] ?? 1);
    if ($title) {
        $ins = $conn->prepare('INSERT INTO books (title,author,isbn,copies) VALUES (?,?,?,?)');
        $ins->bind_param('sssi', $title, $author, $isbn, $copies);
        $ins->execute();
        header('Location: books.php'); exit;
    }
}
$books = $conn->query('SELECT * FROM books ORDER BY title')->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Books</title></head><body>
<h1>Manage Books</h1>
<p><a href="dashboard.php">Back</a></p>
<h2>Add Book</h2>
<form method="post">
    Title: <input name="title" required><br>
    Author: <input name="author"><br>
    ISBN: <input name="isbn"><br>
    Copies: <input name="copies" type="number" value="1"><br>
    <button>Add</button>

</form>

<h2>books available </h2>
<table border="1" cellpadding="6">
<tr><th>Title</th><th>Author</th><th>ISBN</th><th>Copies</th></tr>
<?php foreach ($books as $b): ?>
    <tr>
        <td><?php echo htmlspecialchars($b['title']); ?></td>
        <td><?php echo htmlspecialchars($b['author']); ?></td>
        <td><?php echo htmlspecialchars($b['isbn']); ?></td>
        <td><?php echo (int)$b['copies']; ?></td>
    </tr>
<?php endforeach; ?>
</table>
</body></html>
