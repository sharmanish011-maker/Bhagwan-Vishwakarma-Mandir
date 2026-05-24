<?php
/** Admin Testimonials Module */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrfToken()) {
    $id = sanitizeInt($_POST['test_id'] ?? 0);
    $action = sanitize($_POST['action'] ?? '');
    if ($id > 0) {
        if ($action === 'approve') Database::execute("UPDATE testimonials SET is_approved = 1 WHERE id = :id", ['id' => $id]);
        elseif ($action === 'reject') Database::execute("UPDATE testimonials SET is_approved = 0 WHERE id = :id", ['id' => $id]);
        elseif ($action === 'feature') Database::execute("UPDATE testimonials SET is_featured = 1 - is_featured WHERE id = :id", ['id' => $id]);
        elseif ($action === 'delete') Database::execute("DELETE FROM testimonials WHERE id = :id", ['id' => $id]);
        setFlash('success', 'Testimonial updated.');
    }
    redirect('index.php?module=testimonials');
}
$testimonials = Database::query("SELECT * FROM testimonials ORDER BY created_at DESC");
?>
<h5 class="mb-4"><i class="fas fa-quote-left me-2"></i>Testimonials <span class="badge bg-primary ms-2"><?= count($testimonials) ?></span></h5>
<div class="admin-table-card"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Name</th><th>Location</th><th>Rating</th><th>Message</th><th>Approved</th><th>Featured</th><th>Actions</th></tr></thead><tbody>
    <?php foreach ($testimonials as $t): ?>
    <tr><td><?= e($t['name']) ?></td><td><?= e($t['location'] ?? '-') ?></td><td><?php for($i=0;$i<$t['rating'];$i++) echo '⭐'; ?></td><td><?= truncate(e($t['message_en'] ?? ''), 60) ?></td>
        <td><?= $t['is_approved'] ? '✅' : '⏳' ?></td><td><?= $t['is_featured'] ? '⭐' : '-' ?></td>
        <td>
            <div class="d-flex gap-1 flex-nowrap">
                <form method="POST" class="d-inline"><?= csrfField() ?><input type="hidden" name="test_id" value="<?= $t['id'] ?>"><input type="hidden" name="action" value="<?= $t['is_approved'] ? 'reject' : 'approve' ?>"><button class="btn btn-sm <?= $t['is_approved'] ? 'btn-outline-warning' : 'btn-outline-success' ?>"><?= $t['is_approved'] ? 'Unapprove' : 'Approve' ?></button></form>
                <form method="POST" class="d-inline"><?= csrfField() ?><input type="hidden" name="test_id" value="<?= $t['id'] ?>"><input type="hidden" name="action" value="feature"><button class="btn btn-outline-warning btn-sm">⭐</button></form>
                <form method="POST" class="d-inline" onsubmit="return confirm('Delete?')"><?= csrfField() ?><input type="hidden" name="test_id" value="<?= $t['id'] ?>"><input type="hidden" name="action" value="delete"><button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button></form>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($testimonials)): ?><tr><td colspan="7" class="text-center py-4 text-muted">No testimonials</td></tr><?php endif; ?>
</tbody></table></div></div>
