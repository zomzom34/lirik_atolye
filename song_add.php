<?php
require_once "includes/auth.php";
require_once "config/database.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION["user_id"];
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
            $sql = "INSERT INTO song_projects 
                    (user_id, title, genre, theme, inspiration, lyrics_draft, composition_notes, production_stage, priority_level, planned_release_date, notes)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $user_id,
                $title,
                $genre,
                $theme,
                $inspiration,
                $lyrics_draft,
                $composition_notes,
                $production_stage,
                $priority_level,
                !empty($planned_release_date) ? $planned_release_date : null,
                $notes
            ]);

            $success = "Şarkı taslağı başarıyla eklendi.";

            $title = "";
            $genre = "";
            $theme = "";
            $inspiration = "";
            $lyrics_draft = "";
            $composition_notes = "";
            $production_stage = "";
            $priority_level = "";
            $planned_release_date = "";
            $notes = "";

        } catch (PDOException $e) {
            $error = "Kayıt eklenirken hata oluştu: " . $e->getMessage();
        }
    }
}
?>

<?php include "includes/header.php"; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-3 page-title">Yeni Şarkı Taslağı Ekle</h1>
                <p class="text-muted">
                    Şarkı fikrinizi, söz taslağınızı ve üretim notlarınızı buradan kaydedebilirsiniz.
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

                <form method="POST" action="song_add.php">
                    <div class="mb-3">
                        <label for="title" class="form-label">Şarkı Başlığı *</label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title" 
                            class="form-control" 
                            value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>"
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
                                placeholder="Pop, rap, alternatif..."
                                value="<?php echo isset($genre) ? htmlspecialchars($genre) : ''; ?>"
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="theme" class="form-label">Tema</label>
                            <input 
                                type="text" 
                                name="theme" 
                                id="theme" 
                                class="form-control" 
                                placeholder="Ayrılık, umut, özlem..."
                                value="<?php echo isset($theme) ? htmlspecialchars($theme) : ''; ?>"
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
                        ><?php echo isset($inspiration) ? htmlspecialchars($inspiration) : ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="lyrics_draft" class="form-label">Söz Taslağı</label>
                        <textarea 
                            name="lyrics_draft" 
                            id="lyrics_draft" 
                            class="form-control" 
                            rows="6"
                        ><?php echo isset($lyrics_draft) ? htmlspecialchars($lyrics_draft) : ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="composition_notes" class="form-label">Beste / Melodi Notları</label>
                        <textarea 
                            name="composition_notes" 
                            id="composition_notes" 
                            class="form-control" 
                            rows="3"
                        ><?php echo isset($composition_notes) ? htmlspecialchars($composition_notes) : ''; ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="production_stage" class="form-label">Üretim Aşaması</label>
                            <select name="production_stage" id="production_stage" class="form-select">
                                <option value="">Seçiniz</option>
                                <option value="Fikir">Fikir</option>
                                <option value="Taslak">Taslak</option>
                                <option value="Üzerinde Çalışılıyor">Üzerinde Çalışılıyor</option>
                                <option value="Tamamlandı">Tamamlandı</option>
                                <option value="Yayınlandı">Yayınlandı</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="priority_level" class="form-label">Öncelik</label>
                            <select name="priority_level" id="priority_level" class="form-select">
                                <option value="">Seçiniz</option>
                                <option value="Düşük">Düşük</option>
                                <option value="Orta">Orta</option>
                                <option value="Yüksek">Yüksek</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="planned_release_date" class="form-label">Planlanan Yayın Tarihi</label>
                            <input 
                                type="date" 
                                name="planned_release_date" 
                                id="planned_release_date" 
                                class="form-control"
                                value="<?php echo isset($planned_release_date) ? htmlspecialchars($planned_release_date) : ''; ?>"
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
                        ><?php echo isset($notes) ? htmlspecialchars($notes) : ''; ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="dashboard.php" class="btn btn-secondary">
                            Geri Dön
                        </a>

                        <button type="submit" class="btn btn-dark">
                            Taslağı Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>