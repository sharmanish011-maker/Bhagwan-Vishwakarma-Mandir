<?php
/** Admin Gallery Module */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrfToken()) {
    $action = sanitize($_POST['action'] ?? '');
    if ($action === 'create_album') {
        $slug = createSlug($_POST['title_en']);
        Database::insert("INSERT INTO gallery_albums (title_en, title_hi, slug, sort_order, is_active) VALUES (:t, :th, :s, :o, 1)",
            ['t' => sanitize($_POST['title_en']), 'th' => sanitize($_POST['title_hi'] ?? ''), 's' => $slug, 'o' => sanitizeInt($_POST['sort_order'] ?? 0)]);
        setFlash('success', 'Album created.');
    } elseif ($action === 'delete_album') {
        Database::execute("DELETE FROM gallery_albums WHERE id = :id", ['id' => sanitizeInt($_POST['album_id'])]);
        setFlash('success', 'Album deleted.');
    } elseif ($action === 'delete_image') {
        $img = Database::queryOne("SELECT * FROM gallery_images WHERE id = :id", ['id' => sanitizeInt($_POST['image_id'])]);
        if ($img) {
            @unlink(GALLERY_PATH . '/' . $img['image_path']);
            @unlink(GALLERY_PATH . '/' . $img['thumbnail_path']);
            Database::execute("DELETE FROM gallery_images WHERE id = :id", ['id' => $img['id']]);
        }
        setFlash('success', 'Image deleted.');
    } elseif ($action === 'upload' && isset($_FILES['images'])) {
        $albumId = sanitizeInt($_POST['album_id']);
        foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
            if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                $result = uploadImage([
                    'name' => $_FILES['images']['name'][$i],
                    'type' => $_FILES['images']['type'][$i],
                    'tmp_name' => $tmp,
                    'size' => $_FILES['images']['size'][$i],
                    'error' => $_FILES['images']['error'][$i],
                ], GALLERY_PATH, 'gallery');
                if ($result['success']) {
                    Database::insert("INSERT INTO gallery_images (album_id, image_path, thumbnail_path, title, is_active) VALUES (:aid, :img, :thumb, :title, 1)",
                        ['aid' => $albumId, 'img' => $result['filename'], 'thumb' => $result['thumbnail'] ?? $result['filename'], 'title' => pathinfo($_FILES['images']['name'][$i], PATHINFO_FILENAME)]);
                }
            }
        }
        setFlash('success', 'Images uploaded.');
    }
    redirect('index.php?module=gallery');
}

$albums = Database::query("SELECT ga.*, COUNT(gi.id) as img_count FROM gallery_albums ga LEFT JOIN gallery_images gi ON ga.id = gi.album_id GROUP BY ga.id ORDER BY ga.sort_order");
$images = Database::query("SELECT gi.*, ga.title_en as album_name FROM gallery_images gi JOIN gallery_albums ga ON gi.album_id = ga.id ORDER BY gi.id DESC LIMIT 30");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-images me-2"></i>Gallery</h5>
    <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#albumModal"><i class="fas fa-plus me-2"></i>New Album</button>
</div>

<!-- Albums -->
<div class="row g-3 mb-4">
    <?php foreach ($albums as $a): ?>
    <div class="col-md-3">
        <div class="admin-form-card text-center p-3">
            <i class="fas fa-folder text-saffron" style="font-size:2rem;"></i>
            <h6 class="mt-2"><?= e($a['title_en']) ?></h6>
            <small class="text-muted"><?= $a['img_count'] ?> images</small><br>
            <form method="POST" class="d-inline mt-2" onsubmit="return confirm('Delete album and all images?')"><?= csrfField() ?><input type="hidden" name="action" value="delete_album"><input type="hidden" name="album_id" value="<?= $a['id'] ?>"><button class="btn btn-outline-danger btn-sm mt-2"><i class="fas fa-trash"></i></button></form>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Upload Images -->
<?php if (!empty($albums)): ?>
<div class="admin-form-card mb-4">
    <h6><i class="fas fa-upload me-2"></i>Upload Images</h6>
    <form method="POST" enctype="multipart/form-data">
        <?= csrfField() ?><input type="hidden" name="action" value="upload">
        <div class="row g-3">
            <div class="col-md-4"><select name="album_id" class="form-select" required><option value="">Select Album</option><?php foreach ($albums as $a): ?><option value="<?= $a['id'] ?>"><?= e($a['title_en']) ?></option><?php endforeach; ?></select></div>
            <div class="col-md-5"><input type="file" name="images[]" class="form-control" multiple accept="image/*" required></div>
            <div class="col-md-3"><button class="btn btn-admin-primary w-100"><i class="fas fa-upload me-2"></i>Upload</button></div>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- Images Grid -->
<div class="admin-table-card">
    <div class="card-header"><h5>Recent Images</h5></div>
    <div class="p-3"><div class="row g-3">
        <?php foreach ($images as $img): ?>
        <div class="col-lg-2 col-md-3 col-4 text-center">
            <div class="position-relative">
                <img src="<?= GALLERY_URL ?>/<?= e($img['thumbnail_path'] ?? $img['image_path']) ?>" class="rounded" style="width:100%;height:100px;object-fit:cover;" alt="">
                <form method="POST" class="position-absolute top-0 end-0" onsubmit="return confirm('Delete?')"><?= csrfField() ?><input type="hidden" name="action" value="delete_image"><input type="hidden" name="image_id" value="<?= $img['id'] ?>"><button class="btn btn-danger btn-sm" style="border-radius:50%;width:24px;height:24px;padding:0;font-size:10px;"><i class="fas fa-times"></i></button></form>
            </div>
            <small class="text-muted d-block mt-1"><?= e($img['album_name'] ?? '') ?></small>
        </div>
        <?php endforeach; ?>
        <?php if (empty($images)): ?><div class="col-12 text-center py-3 text-muted">No images uploaded</div><?php endif; ?>
    </div></div>
</div>

<!-- Create Album Modal -->
<div class="modal fade" id="albumModal"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5>Create Album</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <form method="POST"><div class="modal-body"><?= csrfField() ?><input type="hidden" name="action" value="create_album">
        <div class="mb-3"><label class="form-label">Album Title (EN) *</label><input type="text" name="title_en" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Album Title (HI)</label><input type="text" name="title_hi" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" value="0"></div>
    </div><div class="modal-footer"><button class="btn btn-admin-primary"><i class="fas fa-save me-2"></i>Create Album</button></div></form>
</div></div></div>
