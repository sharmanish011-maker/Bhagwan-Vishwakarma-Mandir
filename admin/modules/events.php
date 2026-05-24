<?php
/**
 * Admin Events Module — CRUD
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrfToken()) {
    $action = sanitize($_POST['action'] ?? '');
    
    if ($action === 'create' || $action === 'update') {
        $data = [
            'title_en' => sanitize($_POST['title_en']),
            'title_hi' => sanitize($_POST['title_hi'] ?? ''),
            'description_en' => sanitize($_POST['description_en'] ?? ''),
            'description_hi' => sanitize($_POST['description_hi'] ?? ''),
            'category' => sanitize($_POST['category'] ?? 'festival'),
            'start_date' => sanitize($_POST['start_date']),
            'end_date' => sanitize($_POST['end_date'] ?? $_POST['start_date']),
            'location' => sanitize($_POST['location'] ?? ''),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];
        
        if ($action === 'create') {
            Database::insert("INSERT INTO events (title_en, title_hi, description_en, description_hi, category, start_date, end_date, location, is_featured, is_active) VALUES (:title_en, :title_hi, :description_en, :description_hi, :category, :start_date, :end_date, :location, :is_featured, :is_active)", $data);
            setFlash('success', 'Event created.');
        } else {
            $data['id'] = sanitizeInt($_POST['event_id']);
            Database::execute("UPDATE events SET title_en=:title_en, title_hi=:title_hi, description_en=:description_en, description_hi=:description_hi, category=:category, start_date=:start_date, end_date=:end_date, location=:location, is_featured=:is_featured, is_active=:is_active WHERE id=:id", $data);
            setFlash('success', 'Event updated.');
        }
    } elseif ($action === 'delete') {
        Database::execute("DELETE FROM events WHERE id = :id", ['id' => sanitizeInt($_POST['event_id'])]);
        setFlash('success', 'Event deleted.');
    }
    redirect('index.php?module=events');
}

$events = Database::query("SELECT * FROM events ORDER BY start_date DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-calendar-days me-2"></i>Events <span class="badge bg-primary ms-2"><?= count($events) ?></span></h5>
    <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#eventModal"><i class="fas fa-plus me-2"></i>Add Event</button>
</div>

<div class="admin-table-card">
    <div class="table-responsive"><table class="table table-hover"><thead><tr><th>Title</th><th>Category</th><th>Start Date</th><th>Featured</th><th>Active</th><th>Actions</th></tr></thead><tbody>
        <?php foreach ($events as $ev): ?>
        <tr>
            <td><strong><?= e($ev['title_en']) ?></strong></td>
            <td><span class="event-category-badge"><?= e(ucfirst($ev['category'])) ?></span></td>
            <td><?= formatDateTime($ev['start_date']) ?></td>
            <td><?= $ev['is_featured'] ? '⭐' : '-' ?></td>
            <td><?= $ev['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' ?></td>
            <td>
                <form method="POST" class="d-inline" onsubmit="return confirm('Delete?')"><?= csrfField() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="event_id" value="<?= $ev['id'] ?>"><button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button></form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($events)): ?><tr><td colspan="6" class="text-center py-4 text-muted">No events</td></tr><?php endif; ?>
    </tbody></table></div>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="eventModal"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Add Event</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form method="POST"><div class="modal-body"><?= csrfField() ?><input type="hidden" name="action" value="create">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Title (English) *</label><input type="text" name="title_en" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Title (Hindi)</label><input type="text" name="title_hi" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Start Date *</label><input type="datetime-local" name="start_date" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">End Date</label><input type="datetime-local" name="end_date" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Category</label><select name="category" class="form-select"><option value="festival">Festival</option><option value="puja">Puja</option><option value="cultural">Cultural</option><option value="community">Community</option></select></div>
            <div class="col-md-6"><label class="form-label">Location</label><input type="text" name="location" class="form-control"></div>
            <div class="col-12"><label class="form-label">Description (English)</label><textarea name="description_en" class="form-control" rows="3"></textarea></div>
            <div class="col-12"><label class="form-label">Description (Hindi)</label><textarea name="description_hi" class="form-control" rows="3"></textarea></div>
            <div class="col-md-6"><div class="form-check"><input type="checkbox" name="is_featured" class="form-check-input" id="isFeatured"><label class="form-check-label" for="isFeatured">Featured Event</label></div></div>
            <div class="col-md-6"><div class="form-check"><input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked><label class="form-check-label" for="isActive">Active</label></div></div>
        </div>
    </div><div class="modal-footer"><button class="btn btn-admin-primary" type="submit"><i class="fas fa-save me-2"></i>Save Event</button></div></form>
</div></div></div>
