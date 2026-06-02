<?php
require_once "includes/auth.php";
require_once "config/database.php";

$user_id = $_SESSION["user_id"];

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: dashboard.php");
    exit();
}

$song_id = $_GET["id"];

try {
    $sql = "DELETE FROM song_projects WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$song_id, $user_id]);

    header("Location: dashboard.php");
    exit();

} catch (PDOException $e) {
    die("Silme işlemi sırasında hata oluştu: " . $e->getMessage());
}
?>