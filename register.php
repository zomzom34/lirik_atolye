<?php
require_once "config/database.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $password_confirm = $_POST["password_confirm"];

    if (empty($name) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = "Lütfen tüm alanları doldurunuz.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Geçerli bir e-posta adresi giriniz.";
    } elseif (strlen($password) < 6) {
        $error = "Şifre en az 6 karakter olmalıdır.";
    } elseif ($password !== $password_confirm) {
        $error = "Şifreler birbiriyle eşleşmiyor.";
    } else {
        try {
            $checkSql = "SELECT id FROM users WHERE email = ?";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([$email]);

            if ($checkStmt->rowCount() > 0) {
                $error = "Bu e-posta adresi zaten kayıtlı.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $insertSql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
                $insertStmt = $pdo->prepare($insertSql);
                $insertStmt->execute([$name, $email, $hashedPassword]);

                $success = "Kayıt başarılı! Şimdi giriş yapabilirsiniz.";
            }
        } catch (PDOException $e) {
            $error = "Bir hata oluştu: " . $e->getMessage();
        }
    }
}
?>

<?php include "includes/header.php"; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 text-center mb-3 page-title">Kayıt Ol</h1>
                <p class="text-muted text-center mb-4">
                    LirikAtölye hesabınızı oluşturun.
                </p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="register.php">
                    <div class="mb-3">
                        <label for="name" class="form-label">Ad Soyad</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            class="form-control" 
                            value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-posta</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="form-control" 
                            value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Şifre</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">Şifre Tekrar</label>
                        <input 
                            type="password" 
                            name="password_confirm" 
                            id="password_confirm" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-dark w-100">
                        Kayıt Ol
                    </button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Zaten hesabınız var mı?
                    <a href="login.php">Giriş yapın</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>