<?php
/** Admin Volunteers Module */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrfToken()) {
    $id = sanitizeInt($_POST['vol_id'] ?? 0);
    $action = sanitize($_POST['action'] ?? '');
    if ($id > 0) {
        if ($action === 'delete') { Database::execute("DELETE FROM volunteers WHERE id = :id", ['id' => $id]); setFlash('success', 'Volunteer deleted.'); }
        elseif (in_array($action, ['approved', 'active', 'inactive'])) { Database::execute("UPDATE volunteers SET status = :s WHERE id = :id", ['s' => $action, 'id' => $id]); setFlash('success', 'Status updated.'); }
    }
    redirect('index.php?module=volunteers');
}
$volunteers = Database::query("SELECT * FROM volunteers ORDER BY created_at DESC");
?>
<h5 class="mb-4"><i class="fas fa-handshake me-2"></i>Volunteers <span class="badge bg-info ms-2"><?= count($volunteers) ?></span></h5>
<div class="admin-table-card"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>City</th><th>Availability</th><th>Status</th><th>Applied</th><th>Actions</th></tr></thead><tbody>
    <?php foreach ($volunteers as $v): ?>
    <tr><td><?= e($v['name']) ?></td><td><?= e($v['email']) ?></td><td><?= e($v['phone']) ?></td><td><?= e($v['city'] ?? '-') ?></td><td><?= e(ucfirst($v['availability'] ?? '-')) ?></td><td><?= statusBadge($v['status']) ?></td><td><?= formatDate($v['created_at']) ?></td>
        <td>
            <form method="POST" class="d-inline"><?= csrfField() ?><input type="hidden" name="vol_id" value="<?= $v['id'] ?>"><select name="action" class="form-select form-select-sm d-inline-block" style="width:auto;" onchange="this.form.submit()"><option value="">--</option><option value="approved">✅ Approve</option><option value="active">🟢 Active</option><option value="inactive">🔴 Inactive</option></select></form>
            <form method="POST" class="d-inline" onsubmit="return confirm('Delete?')"><?= csrfField() ?><input type="hidden" name="vol_id" value="<?= $v['id'] ?>"><input type="hidden" name="action" value="delete"><button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button></form>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($volunteers)): ?><tr><td colspan="8" class="text-center py-4 text-muted">No volunteers</td></tr><?php endif; ?>
</tbody></table></div></div>
