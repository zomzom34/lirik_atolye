<?php
require_once "includes/auth.php";
require_once "config/database.php";

$user_id = $_SESSION["user_id"];
$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";

try {
    if (!empty($search)) {
        $sql = "SELECT * FROM song_projects 
                WHERE user_id = ? 
                AND (
                    title LIKE ? 
                    OR genre LIKE ? 
                    OR theme LIKE ?
                )
                ORDER BY created_at DESC";

        $stmt = $pdo->prepare($sql);
        $searchParam = "%" . $search . "%";
        $stmt->execute([$user_id, $searchParam, $searchParam, $searchParam]);
    } else {
        $sql = "SELECT * FROM song_projects WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);
    }

    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Veriler alınırken hata oluştu: " . $e->getMessage());
}
?>

<?php include "includes/header.php"; ?>

<div class="hero-card">
    <div class="row align-items-center">
        <div class="col-md-8">
            <span class="hero-label">LirikAtölye Paneli</span>
            <h1 class="mb-2 mt-2">Şarkı Taslaklarım</h1>
            <p class="mb-0">
                Merhaba, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>. 
                Şarkı fikirlerinizi, söz taslaklarınızı ve üretim sürecinizi tek bir yerde düzenleyebilirsiniz.
            </p>
        </div>

        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="song_add.php" class="btn btn-light">
                + Yeni Taslak Ekle
            </a>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="dashboard.php" class="row g-2 align-items-center">
            <div class="col-md-9">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control" 
                    placeholder="Şarkı başlığı, tür veya tema ara..."
                    value="<?php echo htmlspecialchars($search); ?>"
                >
            </div>

            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-dark">
                    Ara
                </button>
            </div>
        </form>

        <?php if (!empty($search)): ?>
            <div class="mt-3">
                <span class="text-muted">
                    Arama sonucu: 
                    <strong><?php echo htmlspecialchars($search); ?></strong>
                </span>
                <a href="dashboard.php" class="btn btn-sm btn-outline-secondary ms-2">
                    Temizle
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (count($songs) > 0): ?>
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Başlık</th>
                            <th>Tür</th>
                            <th>Tema</th>
                            <th>Üretim Aşaması</th>
                            <th>Öncelik</th>
                            <th>Planlanan Yayın</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($songs as $song): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($song["title"]); ?></td>
                                <td><?php echo htmlspecialchars($song["genre"]); ?></td>
                                <td><?php echo htmlspecialchars($song["theme"]); ?></td>
                                <td>
                                    <span class="badge-stage">
                                        <?php echo !empty($song["production_stage"]) ? htmlspecialchars($song["production_stage"]) : "Belirtilmedi"; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-priority">
                                        <?php echo !empty($song["priority_level"]) ? htmlspecialchars($song["priority_level"]) : "Belirtilmedi"; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    echo !empty($song["planned_release_date"]) 
                                        ? htmlspecialchars($song["planned_release_date"]) 
                                        : "-"; 
                                    ?>
                                </td>
                                <td>
                                    <a href="song_edit.php?id=<?php echo $song["id"]; ?>" class="btn btn-sm btn-warning">
                                        Düzenle
                                    </a>

                                    <a 
                                        href="song_delete.php?id=<?php echo $song["id"]; ?>" 
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Bu şarkı taslağını silmek istediğinize emin misiniz?');"
                                    >
                                        Sil
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="card shadow-sm mt-3">
        <div class="card-body text-center p-5">
            <h2 class="h4 mb-3">
             <?php echo !empty($search) ? "Aramanıza uygun taslak bulunamadı." : "Henüz şarkı taslağınız yok."; ?>
            </h2>
            <p class="text-muted">
                <?php if (!empty($search)): ?>
                    Farklı bir başlık, tür veya tema ile tekrar arama yapabilirsiniz.
                <?php else: ?>
                    İlk şarkı fikrinizi ekleyerek LirikAtölye defterinizi oluşturmaya başlayabilirsiniz.
                <?php endif; ?>
            </p>
            <a href="song_add.php" class="btn btn-dark">
                İlk Taslağımı Ekle
            </a>
        </div>
    </div>
<?php endif; ?>

<?php include "includes/footer.php"; ?>