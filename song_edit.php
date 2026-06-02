<?php
require_once "includes/auth.php";
require_once "config/database.php";

$error = "";
$success = "";
$user_id = $_SESSION["user_id"];

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: dashboard.php");
    exit();
}

$song_id = $_GET["id"];

try {
    $sql = "SELECT * FROM song_projects WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$song_id, $user_id]);
    $song = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$song) {
        header("Location: dashboard.php");
        exit();
    }
} catch (PDOException $e) {
    die("Kayıt alınırken hata oluştu: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $genre = trim($_POST["genre"]);
    $theme = trim($_POST["theme"]);
    $inspiration = trim($_POST["inspiration"]);
    $lyrics_draft = trim($_POST["lyrics_draft"]);
    $composition_notes = trim($_POST["composition_notes"]);
    $production_stage = trim($_POST["production_stage"]);
    $priority_level = trim($_POST["priority_level"]);
    $planned_release_date = $_POST["planned_release_date"];
    $notes = trim($_POST["notes"]);

    if (empty($title)) {
        $error = "Şarkı başlığı zorunludur.";
    } else {
        try {
            $updateSql = "UPDATE song_projects SET 
                            title = ?,
                            genre = ?,
                            theme = ?,
                            inspiration = ?,
                            lyrics_draft = ?,
                            composition_notes = ?,
                            production_stage = ?,
                            priority_level = ?,
                            planned_release_date = ?,
                            notes = ?
                          WHERE id = ? AND user_id = ?";

            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([
                $title,
                $genre,
                $theme,
                $inspiration,
                $lyrics_draft,
                $composition_notes,
                $production_stage,
                $priority_level,
                !empty($planned_release_date) ? $planned_release_date : null,
                $notes,
                $song_id,
                $user_id
            ]);

            $success = "Şarkı taslağı başarıyla güncellendi.";

            $sql = "SELECT * FROM song_projects WHERE id = ? AND user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$song_id, $user_id]);
            $song = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            $error = "Güncelleme sırasında hata oluştu: " . $e->getMessage();
        }
    }
}
?>

<?php include "includes/header.php"; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-3 page-title">Şarkı Taslağını Düzenle</h1>
                <p class="text-muted">
                    Kaydettiğiniz şarkı fikrinin bilgilerini güncelleyebilirsiniz.
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

                <form method="POST" action="song_edit.php?id=<?php echo htmlspecialchars($song_id); ?>">
                    <div class="mb-3">
                        <label for="title" class="form-label">Şarkı Başlığı *</label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($song["title"]); ?>"
                            required
                        >
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="genre" class="form-label">Tür</label>
                            <input 
                                type="text" 
                                name="genre" 
                                id="genre" 
                                class="form-control" 
                                value="<?php echo htmlspecialchars($song["genre"]); ?>"
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="theme" class="form-label">Tema</label>
                            <input 
                                type="text" 
                                name="theme" 
                                id="theme" 
                                class="form-control" 
                                value="<?php echo htmlspecialchars($song["theme"]); ?>"
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="inspiration" class="form-label">İlham Kaynağı</label>
                        <textarea 
                            name="inspiration" 
                            id="inspiration" 
                            class="form-control" 
                            rows="2"
                        ><?php echo htmlspecialchars($song["inspiration"]); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="lyrics_draft" class="form-label">Söz Taslağı</label>
                        <textarea 
                            name="lyrics_draft" 
                            id="lyrics_draft" 
                            class="form-control" 
                            rows="6"
                        ><?php echo htmlspecialchars($song["lyrics_draft"]); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="composition_notes" class="form-label">Beste / Melodi Notları</label>
                        <textarea 
                            name="composition_notes" 
                            id="composition_notes" 
                            class="form-control" 
                            rows="3"
                        ><?php echo htmlspecialchars($song["composition_notes"]); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="production_stage" class="form-label">Üretim Aşaması</label>
                            <select name="production_stage" id="production_stage" class="form-select">
                                <?php
                                $stages = ["", "Fikir", "Taslak", "Üzerinde Çalışılıyor", "Tamamlandı", "Yayınlandı"];
                                foreach ($stages as $stage):
                                ?>
                                    <option 
                                        value="<?php echo htmlspecialchars($stage); ?>"
                                        <?php echo ($song["production_stage"] === $stage) ? "selected" : ""; ?>
                                    >
                                        <?php echo $stage === "" ? "Seçiniz" : htmlspecialchars($stage); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="priority_level" class="form-label">Öncelik</label>
                            <select name="priority_level" id="priority_level" class="form-select">
                                <?php
                                $priorities = ["", "Düşük", "Orta", "Yüksek"];
                                foreach ($priorities as $priority):
                                ?>
                                    <option 
                                        value="<?php echo htmlspecialchars($priority); ?>"
                                        <?php echo ($song["priority_level"] === $priority) ? "selected" : ""; ?>
                                    >
                                        <?php echo $priority === "" ? "Seçiniz" : htmlspecialchars($priority); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="planned_release_date" class="form-label">Planlanan Yayın Tarihi</label>
                            <input 
                                type="date" 
                                name="planned_release_date" 
                                id="planned_release_date" 
                                class="form-control"
                                value="<?php echo htmlspecialchars($song["planned_release_date"]); ?>"
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Ek Notlar</label>
                        <textarea 
                            name="notes" 
                            id="notes" 
                            class="form-control" 
                            rows="3"
                        ><?php echo htmlspecialchars($song["notes"]); ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="dashboard.php" class="btn btn-secondary">
                            Geri Dön
                        </a>

                        <button type="submit" class="btn btn-dark">
                            Değişiklikleri Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>