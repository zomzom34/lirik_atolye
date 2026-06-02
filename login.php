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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $error = "Lütfen e-posta ve şifre alanlarını doldurunuz.";
    } else {
        try {
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["user_email"] = $user["email"];

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "E-posta veya şifre hatalı.";
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
                <h1 class="h3 text-center mb-3 page-title">Giriş Yap</h1>
                <p class="text-muted text-center mb-4">
                    Şarkı taslaklarınıza erişmek için giriş yapın.
                </p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label for="email" class="form-label">E-posta</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="form-control" 
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

                    <button type="submit" class="btn btn-dark w-100">
                        Giriş Yap
                    </button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Hesabınız yok mu?
                    <a href="register.php">Kayıt olun</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>