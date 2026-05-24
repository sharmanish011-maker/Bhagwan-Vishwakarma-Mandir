<?php
/** Admin Gallery Module (Images & Videos) */
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
    } elseif ($action === 'add_video') {
        $title_en = sanitize($_POST['title_en'] ?? '');
        $title_hi = sanitize($_POST['title_hi'] ?? '');
        $youtube_url = sanitize($_POST['youtube_url'] ?? '');
        $sort_order = sanitizeInt($_POST['sort_order'] ?? 0);
        
        Database::insert("INSERT INTO videos (title_en, title_hi, youtube_url, sort_order, is_active) VALUES (:ten, :thi, :url, :so, 1)",
            ['ten' => $title_en, 'thi' => $title_hi, 'url' => $youtube_url, 'so' => $sort_order]);
        setFlash('success', 'Video added to gallery.');
    } elseif ($action === 'delete_video') {
        Database::execute("DELETE FROM videos WHERE id = :id", ['id' => sanitizeInt($_POST['video_id'])]);
        setFlash('success', 'Video deleted.');
    }
    
    // Maintain the active tab on reload
    $activeTab = in_array($action, ['add_video', 'delete_video']) ? 'videos' : 'photos';
    redirect('index.php?module=gallery&tab=' . $activeTab);
}

$albums = Database::query("SELECT ga.*, COUNT(gi.id) as img_count FROM gallery_albums ga LEFT JOIN gallery_images gi ON ga.id = gi.album_id GROUP BY ga.id ORDER BY ga.sort_order");
$images = Database::query("SELECT gi.*, ga.title_en as album_name FROM gallery_images gi JOIN gallery_albums ga ON gi.album_id = ga.id ORDER BY gi.id DESC LIMIT 30");
$videos = Database::query("SELECT * FROM videos ORDER BY sort_order ASC, id DESC");

$currentTab = sanitize($_GET['tab'] ?? 'photos');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-photo-film me-2 text-saffron"></i>Gallery Management</h5>
    <?php if ($currentTab === 'photos'): ?>
        <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#albumModal"><i class="fas fa-folder-plus me-2"></i>New Album</button>
    <?php endif; ?>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-tabs mb-4 border-bottom-0" id="galleryTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link border-0 px-4 py-2 fw-600 <?= $currentTab === 'photos' ? 'active text-saffron border-bottom border-3 border-saffron bg-transparent' : 'text-muted bg-transparent' ?>" href="?module=gallery&tab=photos" role="tab" style="transition: all 0.2s;"><i class="fas fa-images me-2"></i>Photo Gallery</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link border-0 px-4 py-2 fw-600 <?= $currentTab === 'videos' ? 'active text-saffron border-bottom border-3 border-saffron bg-transparent' : 'text-muted bg-transparent' ?>" href="?module=gallery&tab=videos" role="tab" style="transition: all 0.2s;"><i class="fas fa-video me-2"></i>Video Gallery</a>
    </li>
</ul>

<!-- Tabs Content -->
<div class="tab-content" id="galleryTabsContent">
    
    <!-- PHOTO GALLERY PANEL -->
    <?php if ($currentTab === 'photos'): ?>
    <div class="tab-pane fade show active" id="photos-panel" role="tabpanel">
        <!-- Albums list -->
        <div class="row g-3 mb-4">
            <?php foreach ($albums as $a): ?>
            <div class="col-md-3">
                <div class="admin-form-card text-center p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <i class="fas fa-folder text-saffron" style="font-size:2.5rem; filter: drop-shadow(0 4px 6px rgba(255,153,51,0.2));"></i>
                        <h6 class="mt-2 mb-1 fw-600 text-maroon"><?= e($a['title_en']) ?></h6>
                        <small class="text-muted d-block"><?= $a['img_count'] ?> images</small>
                    </div>
                    <form method="POST" class="d-inline mt-3" onsubmit="return confirm('Delete album and all images?')">
                        <?= csrfField() ?>
                        <input type="hidden" name="action" value="delete_album">
                        <input type="hidden" name="album_id" value="<?= $a['id'] ?>">
                        <button class="btn btn-outline-danger btn-sm w-100"><i class="fas fa-trash me-1"></i>Delete Album</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($albums)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center py-4"><i class="fas fa-info-circle me-2"></i>No albums created yet. Click "New Album" to get started!</div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Upload Images -->
        <?php if (!empty($albums)): ?>
        <div class="admin-form-card mb-4 border border-1" style="border-radius: var(--card-radius); box-shadow: var(--shadow-sm);">
            <h6 class="fw-600 text-maroon mb-3"><i class="fas fa-cloud-arrow-up me-2 text-saffron"></i>Upload Images</h6>
            <form method="POST" enctype="multipart/form-data">
                <?= csrfField() ?>
                <input type="hidden" name="action" value="upload">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <select name="album_id" class="form-select" required>
                            <option value="">Select Album</option>
                            <?php foreach ($albums as $a): ?>
                                <option value="<?= $a['id'] ?>"><?= e($a['title_en']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*" required>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-admin-primary w-100"><i class="fas fa-upload me-2"></i>Upload Images</button>
                    </div>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- Images Grid -->
        <div class="admin-table-card">
            <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center bg-transparent">
                <h5 class="mb-0 fw-600 text-maroon"><i class="fas fa-images me-2 text-saffron"></i>Recent Images</h5>
            </div>
            <div class="p-3">
                <div class="row g-3">
                    <?php foreach ($images as $img): ?>
                    <div class="col-lg-2 col-md-3 col-6 text-center">
                        <div class="position-relative overflow-hidden rounded-3 animate-hover shadow-sm border border-light" style="aspect-ratio: 4/3;">
                            <img src="<?= GALLERY_URL ?>/<?= e($img['thumbnail_path'] ?? $img['image_path']) ?>" class="w-100 h-100" style="object-fit:cover;" alt="">
                            <form method="POST" class="position-absolute top-0 end-0 m-1" onsubmit="return confirm('Delete image?')">
                                <?= csrfField() ?>
                                <input type="hidden" name="action" value="delete_image">
                                <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
                                <button class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" style="border-radius:50%; width:28px; height:28px; padding:0; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"><i class="fas fa-times"></i></button>
                            </form>
                        </div>
                        <small class="text-muted d-block mt-2 text-truncate" title="<?= e($img['album_name'] ?? '') ?>"><?= e($img['album_name'] ?? '') ?></small>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($images)): ?>
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="fas fa-image text-muted d-block mb-3" style="font-size: 3rem;"></i>
                            No images uploaded yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- VIDEO GALLERY PANEL -->
    <?php if ($currentTab === 'videos'): ?>
    <div class="tab-pane fade show active" id="videos-panel" role="tabpanel">
        
        <!-- Add Video Form -->
        <div class="admin-form-card mb-4 border border-1" style="border-radius: var(--card-radius); box-shadow: var(--shadow-sm);">
            <h6 class="fw-600 text-maroon mb-3"><i class="fas fa-video-camera me-2 text-saffron"></i>Add New Video</h6>
            <form method="POST">
                <?= csrfField() ?>
                <input type="hidden" name="action" value="add_video">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-600">Video Title (English) *</label>
                        <input type="text" name="title_en" class="form-control" placeholder="e.g. Maha Aarti Live" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-600">Video Title (Hindi)</label>
                        <input type="text" name="title_hi" class="form-control" placeholder="e.g. महा आरती लाइव">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-600">YouTube Video URL *</label>
                        <input type="url" name="youtube_url" class="form-control" placeholder="e.g. https://www.youtube.com/watch?v=..." required>
                    </div>
                    <div class="col-md-2 offset-md-10 mt-3 text-end">
                        <button class="btn btn-admin-primary w-100"><i class="fas fa-plus me-2"></i>Add Video</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Videos Grid -->
        <div class="admin-table-card">
            <div class="card-header border-bottom py-3 bg-transparent">
                <h5 class="mb-0 fw-600 text-maroon"><i class="fas fa-circle-play me-2 text-saffron"></i>Recent Videos</h5>
            </div>
            <div class="p-3">
                <div class="row g-4">
                    <?php foreach ($videos as $vid): 
                        $ytId = getYouTubeId($vid['youtube_url']);
                        $thumbUrl = $ytId ? "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg" : BASE_URL . "/frontend/assets/images/video_placeholder.jpg";
                    ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="bvm-card border border-light h-100 d-flex flex-column justify-content-between overflow-hidden shadow-sm rounded-3">
                            <div class="position-relative" style="aspect-ratio: 16/9; background:#000;">
                                <img src="<?= $thumbUrl ?>" class="w-100 h-100" style="object-fit:cover; opacity: 0.85;" alt="">
                                <a href="<?= e($vid['youtube_url']) ?>" target="_blank" class="position-absolute top-50 start-50 translate-middle text-white" style="font-size:3rem; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.5)); transition: transform 0.2s;"><i class="fas fa-circle-play text-saffron"></i></a>
                                
                                <form method="POST" class="position-absolute top-0 end-0 m-2" onsubmit="return confirm('Delete this video?')">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="action" value="delete_video">
                                    <input type="hidden" name="video_id" value="<?= $vid['id'] ?>">
                                    <button class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" style="border-radius:50%; width:32px; height:32px; padding:0; box-shadow:0 2px 6px rgba(0,0,0,0.4);"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-600 text-maroon mb-1"><?= e($vid['title_en']) ?></h6>
                                <?php if (!empty($vid['title_hi'])): ?>
                                    <small class="text-muted d-block mb-2 font-hindi"><?= e($vid['title_hi']) ?></small>
                                <?php endif; ?>
                                <small class="text-truncate d-block text-muted" style="font-size:0.75rem;"><i class="fab fa-youtube text-danger me-1"></i><?= e($vid['youtube_url']) ?></small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($videos)): ?>
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="fas fa-video-slash text-muted d-block mb-3" style="font-size: 3rem;"></i>
                            No videos added yet. Link a YouTube video above to populate!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- Create Album Modal -->
<div class="modal fade" id="albumModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--card-radius);">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-600 text-maroon"><i class="fas fa-folder-plus me-2 text-saffron"></i>Create New Album</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <?= csrfField() ?>
                    <input type="hidden" name="action" value="create_album">
                    <div class="mb-3">
                        <label class="form-label fw-600">Album Title (English) *</label>
                        <input type="text" name="title_en" class="form-control" placeholder="e.g. Shivratri 2026" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Album Title (Hindi)</label>
                        <input type="text" name="title_hi" class="form-control" placeholder="e.g. महा शिवरात्रि २०२६">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-admin-primary"><i class="fas fa-save me-2"></i>Create Album</button>
                </div>
            </form>
        </div>
    </div>
</div>
