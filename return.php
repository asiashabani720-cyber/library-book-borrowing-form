<?php
require_once __DIR__ . '/init.php';
require_login();

if (isset($_GET['loan_id'])) {
    $loan_id = intval($_GET['loan_id']);
    $stmt = $conn->prepare("UPDATE loans SET returned=1, returned_at=NOW() WHERE id=?");
    $stmt->bind_param('i', $loan_id);
    $stmt->execute();
}

header('Location: admin/loans.php'); exit;
