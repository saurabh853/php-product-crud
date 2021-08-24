<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=product', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$p_no = $_POST['p_no'] ?? null;

if (!$p_no) {
    header('Location:product.php');
    exit;
}

$statement = $pdo->prepare("DELETE FROM product_details WHERE p_no = :p_no");
$statement->bindValue(':p_no', $p_no);
$statement->execute();

header("Location:product.php");
