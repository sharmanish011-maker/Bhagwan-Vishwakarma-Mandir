<?php
/** Admin Announcements Module */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrfToken()) {
    $action = sanitize($_POST['action'] ?? '');
    if ($action === 'create') {
        Database::insert("INSERT INTO announcements (message_en, message_hi, type, link, is_active, start_date, end_date) VALUES (:me, :mh, :t, :l, :act, :sd, :ed)",
            ['me' => sanitize($_POST['message_en']), 'mh' => sanitize($_POST['message_hi'] ?? ''), 't' => sanitize($_POST['type'] ?? 'info'), 'l' => sanitize($_POST['link'] ?? ''), 'act' => isset($_POST['is_active']) ? 1 : 0, 'sd' => !empty($_POST['start_date']) ? $_POST['start_date'] : null, 'ed' => !empty($_POST['end_date']) ? $_POST['end_date'] : null]);
        setFlash('success', 'Announcement created.');
    } elseif ($action === 'delete') {
        Database::execute("DELETE FROM announcements WHERE id = :id", ['id' => sanitizeInt($_POST['ann_id'])]);
        setFlash('success', 'Announcement deleted.');
    } elseif ($action === 'toggle') {
        Database::execute("UPDATE announcements SET is_active = 1 - is_active WHERE id = :id", ['id' => sanitizeInt($_POST['ann_id'])]);
    }
    redirect('index.php?module=announcements');
}
$announcements = Database::query("SELECT * FROM announcements ORDER BY id DESC");
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Announcements <span class="badge bg-primary ms-2"><?= count($announcements) ?></span></h5>
    <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#annModal"><i class="fas fa-plus me-2"></i>Add</button>
</div>
<div class="admin-table-card"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Message</th><th>Type</th><th>Active</th><th>Start</th><th>End</th><th>Actions</th></tr></thead><tbody>
    <?php foreach ($announcements as $a): ?>
    <tr><td><?= truncate(e($a['message_en']), 60) ?></td><td><span class="badge bg-<?= ['info'=>'primary','warning'=>'warning','success'=>'success','festival'=>'danger'][$a['type']] ?? 'secondary' ?>"><?= e(ucfirst($a['type'])) ?></span></td>
        <td><form method="POST" class="d-inline"><?= csrfField() ?><input type="hidden" name="action" value="toggle"><input type="hidden" name="ann_id" value="<?= $a['id'] ?>"><button class="btn btn-sm <?= $a['is_active'] ? 'btn-success' : 'btn-secondary' ?>"><?= $a['is_active'] ? 'On' : 'Off' ?></button></form></td>
        <td><?= $a['start_date'] ? formatDate($a['start_date']) : '-' ?></td><td><?= $a['end_date'] ? formatDate($a['end_date']) : '-' ?></td>
        <td><form method="POST" class="d-inline" onsubmit="return confirm('Delete?')"><?= csrfField() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="ann_id" value="<?= $a['id'] ?>"><button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button></form></td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($announcements)): ?><tr><td colspan="6" class="text-center py-4 text-muted">No announcements</td></tr><?php endif; ?>
</tbody></table></div></div>

<div class="modal fade" id="annModal"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5>Add Announcement</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <form method="POST"><div class="modal-body"><?= csrfField() ?><input type="hidden" name="action" value="create">
        <div class="mb-3"><label class="form-label">Message (EN) *</label><textarea name="message_en" class="form-control" rows="3" required></textarea></div>
        <div class="mb-3"><label class="form-label">Message (HI)</label><textarea name="message_hi" class="form-control" rows="3"></textarea></div>
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Type</label><select name="type" class="form-select"><option value="info">Info</option><option value="warning">Warning</option><option value="success">Success</option><option value="festival">Festival</option></select></div>
            <div class="col-md-6"><label class="form-label">Link (optional)</label><input type="url" name="link" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">End Date</label><input type="date" name="end_date" class="form-control"></div>
            <div class="col-12"><div class="form-check"><input type="checkbox" name="is_active" class="form-check-input" checked><label class="form-check-label">Active</label></div></div>
        </div>
    </div><div class="modal-footer"><button class="btn btn-admin-primary"><i class="fas fa-save me-2"></i>Save</button></div></form>
</div></div></div>
